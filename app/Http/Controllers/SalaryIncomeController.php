<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryIncomeController extends Controller
{
    // Show member salary income
   public function memberSalaryIncome()
{
      $memid = auth()->user()->show_mem_id;
    $member = DB::table('member')->where('show_mem_id', $memid)->first();
    
    if (!$member) {
        return abort(404, 'Member not found');
    }

       $salaryIncome = DB::table('salary_income')
    ->where('member_id', $member->show_mem_id)
    ->orderBy('id', 'asc')
    ->get()
    ->groupBy('matching_income');

    return view('member.salary_income', compact('member', 'salaryIncome', 'memid'));
}


    // Call this via command or background job when new slabs are achieved
    public function createSalaryEntries()
    {
        $salarySlabs = [
            50000     => 500,
            100000    => 1000,
            250000    => 2000,
            500000    => 4000,
            1000000   => 8000,
            2500000   => 20000,
            5000000   => 40000,
            10000000  => 100000,
        ];

        $members = DB::table('binary_payouts')->where('status', 1)->get();

        foreach ($members as $member) {
            foreach ($salarySlabs as $match => $salaryAmount) {
                if ((int)$member->tot_matching >= $match) {
                    $exists = DB::table('salary_income')
                        ->where('member_id', $member->member_id)
                        ->where('matching_income', $match)
                        ->exists();

                    if (!$exists) {
                        $from = Carbon::now();
                        $to = Carbon::now()->addMonths(6);

                        DB::table('salary_income')->insert([
                            'member_id'       => $member->member_id,
                            'amount'          => $salaryAmount,
                            'matching_income' => $match,
                            'from_date'       => $from->timestamp,
                            'to_date'         => $to->timestamp,
                            'created_at'      => now()->timestamp,
                            'updated_at'      => now()->timestamp,
                        ]);
                    }
                }
            }
        }
    }
}
