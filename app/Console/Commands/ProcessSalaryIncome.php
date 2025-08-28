<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\WalletHelper;

class ProcessSalaryIncome extends Command
{
    protected $signature = 'income:salary';
    protected $description = 'Process salary income for members based on matching business slabs';

    protected $salarySlabs = [
        50000     => 500,
        100000    => 1000,
        250000    => 2000,
        500000    => 4000,
        1000000   => 8000,
        2500000   => 20000,
        5000000   => 40000,
        10000000  => 100000,
    ];

    public function handle()
    {
        $members = DB::table('binary_payouts')->where('status', 1)->get();

        foreach ($members as $member) {
            foreach ($this->salarySlabs as $match => $salary) {
                if ((int)$member->tot_matching >= $match) {
                    $exists = DB::table('salary_income')
                        ->where('member_id', $member->member_id)
                        ->where('matching_income', $match)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // ⛔ CAP CHECK START
                    $mem = DB::table('member')->where('show_mem_id', $member->member_id)->first();
                    if (!$mem) {
                        continue;
                    }

                    $packageAmount = $mem->payment ?? 0;

                   $isWorking = DB::table('member')
    ->where('sponsorid', $mem->show_mem_id)
    ->where('status', 1)
    ->exists();

                    $capAmount = $isWorking ? ($packageAmount * 3) : ($packageAmount * 2);
                  $totalEarned = WalletHelper::getMemberEarnings($mem->show_mem_id)['total_income'];

                    if ($totalEarned >= $capAmount) {
                        continue; // cap already reached
                    }

                    if (($totalEarned + $salary) > $capAmount) {
                        $salary = $capAmount - $totalEarned; // trim salary
                        if ($salary <= 0) {
                            continue;
                        }
                    }
                    // ✅ CAP CHECK END

                    $fromDate = Carbon::now()->startOfDay()->timestamp;
                    $toDate = Carbon::now()->addMonths(6)->startOfDay()->timestamp;

                    $salaryIncomeId = DB::table('salary_income')->insertGetId([
                        'member_id'        => $member->member_id,
                        'amount'           => $salary,
                        'matching_income'  => $match,
                        'from_date'        => $fromDate,
                        'to_date'          => $toDate,
                        'created_at'       => now()->timestamp,
                        'updated_at'       => now()->timestamp,
                    ]);

                    DB::table('salary_income_logs')->insert([
                        'salary_income_id' => $salaryIncomeId,
                        'member_id'        => $member->member_id,
                        'amount'           => $salary,
                        'date'             => $fromDate,
                        'created_at'       => now()->timestamp,
                        'updated_at'       => now()->timestamp,
                    ]);

                    $this->info("✅ Salary income added for {$member->member_id} at ₹{$salary}.");
                }
            }
        }

        $this->info('Salary income processing completed.');
    }
}
