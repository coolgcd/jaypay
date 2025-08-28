<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\BinaryMatchingHelper;
use Carbon\Carbon;

class MemberTopupController extends Controller
{
    // Member topup page
    public function topupPin()
    {
        $member = Auth::user(); // Assuming member is authenticated

        // FIXED: Updated join to work with show_mem_id
        $availablePins = DB::table('binary_pin as bp')
            ->join('member as m', 'bp.member_id', '=', 'm.show_mem_id') // Changed join condition
            ->select(
                'bp.*',
                'm.name as member_name',
                'm.show_mem_id as member_id'
            )
            ->where('bp.member_id', $member->show_mem_id) // Changed from $member->id to $member->show_mem_id
            ->where('bp.status', 0)
            ->orderBy('bp.created_at', 'desc')
            ->paginate(10);

        return view('member.topup.pin', compact('availablePins'));
    }




    public function processTopup(Request $request)
    {
        $request->validate([
            'pin_id' => 'required',
            'new_member_id' => 'required',
            'password' => 'required'
        ]);

        $member = Auth::user();

        // Password check
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $member->password)) {
            return back()->with('error', 'Invalid password');
        }

        // Validate pin: must belong to logged-in member and be unused
        $pin = \Illuminate\Support\Facades\DB::table('binary_pin')
            ->where('id', $request->pin_id)
            ->where('member_id', $member->show_mem_id)
            ->where('status', 0)
            ->first();

        if (!$pin) {
            return back()->with('error', 'Invalid PIN or PIN already used');
        }

        // Target member
        $newMember = \Illuminate\Support\Facades\DB::table('member')
            ->where('show_mem_id', $request->new_member_id)
            ->first();

        if (!$newMember) {
            return back()->with('error', 'Invalid Member ID');
        }


        $alreadyActivated = \Illuminate\Support\Facades\DB::table('member_daily_income')
            ->where('member_id', $newMember->show_mem_id)
            ->exists();

        if ($alreadyActivated) {
            try {
                \Illuminate\Support\Facades\DB::beginTransaction();

                $currentTimestamp = now()->timestamp;
                $activateDate = $currentTimestamp;
                $endDate = \Carbon\Carbon::createFromTimestamp($activateDate)->addDays(200)->timestamp;

                // Mark PIN used
                \Illuminate\Support\Facades\DB::table('binary_pin')
                    ->where('id', $request->pin_id)
                    ->update([
                        'used_by' => $newMember->show_mem_id,
                        'status'  => 1,
                        'used_at' => now(),
                        'updated_at' => now(),
                    ]);

                // Working status based on active directs
                $isWorking = \Illuminate\Support\Facades\DB::table('member')
                    ->where('sponsorid', $newMember->show_mem_id)
                    ->where('status', 1)
                    ->exists();

                $multiplier = $isWorking ? 3 : 2;
                $newCap = (int)$pin->pinamt * $multiplier;

                $existingCap = (int) (\Illuminate\Support\Facades\DB::table('member')
                    ->where('id', $newMember->id)
                    ->value('tot_cpping_amt') ?? 0);

                $totalCap = $existingCap + $newCap;

                // Update member cumulative values
                \Illuminate\Support\Facades\DB::table('member')
                    ->where('id', $newMember->id)
                    ->update([
                        'payment'        => \Illuminate\Support\Facades\DB::raw('payment + ' . (int)$pin->pinamt),
                        'tot_cpping_amt' => $totalCap,
                        'status'         => 1,
                        'active'         => 1,
                        'is_laps'        => 0,
                        'activate_date'  => $activateDate,
                    ]);

                // Update binary table and PV
                \Illuminate\Support\Facades\DB::table('member_binary')
                    ->where('memid', $request->new_member_id)
                    ->update([
                        'is_laps'      => 0,
                        'activatedate' => $activateDate,
                        'payamount'    => (int)$pin->pinamt,
                        'totpv'        => \Illuminate\Support\Facades\DB::raw('COALESCE(totpv,0) + ' . (int)$pin->pinamt),
                    ]);

                // Close previous daily income entry and create a new one
                // \Illuminate\Support\Facades\DB::table('member_daily_income')
                //     ->where('member_id', $newMember->show_mem_id)
                //     ->update(['is_laps' => 1]);

                \Illuminate\Support\Facades\DB::table('member_daily_income')->insert([
                    'member_id'      => $newMember->show_mem_id,
                    'amount'         => (int)$pin->pinamt,
                    'start_date'     => $activateDate,
                    'end_date'       => $endDate,
                    'total_received' => 0,
                    'created_at'     => $currentTimestamp,
                    'updated_at'     => $currentTimestamp,
                ]);

                // Sponsor income cap entry
                $sponsorIncomeAmount = (int)$pin->pinamt;
                $sponsorEndDate = \Carbon\Carbon::createFromTimestamp($activateDate)->addDays(200)->timestamp;

                \Illuminate\Support\Facades\DB::table('direct_payment_tbl')->insert([
                    'member_id'      => $newMember->sponsorid,
                    'from_id'        => $newMember->show_mem_id,
                    'amount'         => $sponsorIncomeAmount,
                    'start_date'     => $activateDate,
                    'total_received' => 0,
                    'end_date'       => $sponsorEndDate,
                    'created_at'     => $currentTimestamp,
                    'updated_at'     => $currentTimestamp,
                ]);

                \Illuminate\Support\Facades\DB::commit();

                \App\Helpers\BinaryMatchingHelper::process();

                return back()->with('success', 'Top-up successful: cap extended, daily income restarted, and sponsor cap updated.');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('error', 'Failed to process top-up: ' . $e->getMessage());
            }
        }

        // First-time activation
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $currentTimestamp = now()->timestamp;
            $activateDate = $currentTimestamp;
            $endDate = \Carbon\Carbon::createFromTimestamp($activateDate)->addDays(200)->timestamp;

            // Mark PIN used
            \Illuminate\Support\Facades\DB::table('binary_pin')
                ->where('id', $request->pin_id)
                ->update([
                    'used_by'   => $newMember->show_mem_id,
                    'status'    => 1,
                    'used_at'   => now(),
                    'updated_at' => now(),
                ]);

            // Working status based on active directs
            $isWorking = \Illuminate\Support\Facades\DB::table('member')
                ->where('sponsorid', $newMember->show_mem_id)
                ->where('status', 1)
                ->exists();

            $multiplier = $isWorking ? 3 : 2;
            $calculatedCap = (int)$pin->pinamt * $multiplier;

            // Update member record
            \Illuminate\Support\Facades\DB::table('member')
                ->where('id', $newMember->id)
                ->update([
                    'paid'           => 'yes',
                    'free'           => 'no',
                    'payment'        => (int)$pin->pinamt,
                    'status'         => 1,
                    'active'         => 1,
                    'activate_date'  => $activateDate,
                    'tot_cpping_amt' => $calculatedCap,
                    'tot_income_amt' => 0,
                ]);

            // Update member_binary
            \Illuminate\Support\Facades\DB::table('member_binary')
                ->where('memid', $request->new_member_id)
                ->update([
                    'status'       => 1,
                    'activ'        => 1,
                    'activatedate' => $activateDate,
                    'payamount'    => (int)$pin->pinamt,
                    'totpv'        => (int)$pin->pinamt,
                ]);

            // Create daily income entry
            \Illuminate\Support\Facades\DB::table('member_daily_income')->insert([
                'member_id'      => $newMember->show_mem_id,
                'amount'         => (int)$pin->pinamt,
                'start_date'     => $activateDate,
                'end_date'       => $endDate,
                'total_received' => 0,
                'created_at'     => $currentTimestamp,
                'updated_at'     => $currentTimestamp,
            ]);

            // Sponsor income cap entry
            $sponsorIncomeAmount = (int)$pin->pinamt;
            $sponsorEndDate = \Carbon\Carbon::createFromTimestamp($activateDate)->addDays(200)->timestamp;

            \Illuminate\Support\Facades\DB::table('direct_payment_tbl')->insert([
                'member_id'      => $newMember->sponsorid,
                'from_id'        => $newMember->show_mem_id,
                'amount'         => $sponsorIncomeAmount,
                'start_date'     => $activateDate,
                'end_date'       => $sponsorEndDate,
                'total_received' => 0,
                'created_at'     => $currentTimestamp,
                'updated_at'     => $currentTimestamp,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            \App\Helpers\BinaryMatchingHelper::process();

            return back()->with('success', 'Member activated successfully, daily income started, and sponsor cap configured.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Failed to activate member: ' . $e->getMessage());
        }
    }
    public function usedPins()
    {
        $member = Auth::user();

        // FIXED: Updated join to work with show_mem_id
        $usedPins = DB::table('binary_pin as bp')
            ->join('member as m', 'bp.used_by', '=', 'm.show_mem_id') // Changed join condition
            ->select(
                'bp.*',
                'm.name as used_by_name',
                'm.show_mem_id as used_by_id'
            )
            ->where('bp.member_id', $member->show_mem_id) // Changed from $member->id to $member->show_mem_id
            ->where('bp.status', 1)
            ->orderBy('bp.used_at', 'desc')
            ->paginate(10);

        return view('member.topup.used', compact('usedPins'));
    }
}
