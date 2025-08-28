<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Repurchase;
use Illuminate\Http\Request;

class IncomeReportController extends Controller
{
    public function index()
    {
        $startingDate = mktime(23, 50, 0, 10, 4, 2020);
        $closingDate = mktime(23, 50, 0, 11, 5, 2020);

        // Fetch repurchase data
        $repurchaseData = Repurchase::first(); // Assuming there's only one record in repurchase_tbl

        // Fetch members with activated dates
        $members = Member::where('activate_date', '!=', 0)->get();

        $reportData = [];

        foreach ($members as $member) {
            $mySelfBv = $member->totselfpurch;
            $myTeamBV = $member->accuincome;
            $allTeamBV = $myTeamBV + $mySelfBv;

            // Determine the percentage based on Grand BV
            $bvSelfM = $this->calculateBvPercentage($allTeamBV);
            $bvSelfM = $member->coin != 0 ? $member->coin : $bvSelfM;

            $selfBvIncome = round($mySelfBv * $bvSelfM / 100);

            // Fetch sponsor's members
            $sponsoredMembers = Member::where('sponsorid', $member->mem_id)
                ->where('activate_date', '!=', 0)
                ->get();

            $teamIncome = 0;

            foreach ($sponsoredMembers as $sponsor) {
                $teamBV = $sponsor->accuincome + $sponsor->totselfpurch;
                $bvPercentage = $this->calculateBvPercentage($teamBV);
                $defferedPercent = $bvSelfM - $bvPercentage;

                $getIncome = $teamBV ? round($teamBV * $defferedPercent / 100) : 0;

                $teamIncome += $getIncome;

                // Store detailed data for the view
                $reportData[] = [
                    'member' => $member,
                    'self_bv' => $mySelfBv,
                    'team_bv' => $myTeamBV,
                    'all_team_bv' => $allTeamBV,
                    'bv_self_m' => $bvSelfM,
                    'self_bv_income' => $selfBvIncome,
                    'sponsor_id' => $sponsor->mem_id,
                    'sponsor_name' => $this->getMemName($sponsor->mem_id),
                    'team_income' => $getIncome,
                ];
            }
        }

        return view('admin.repurchase.income_report', compact('reportData', 'repurchaseData'));
    }

    private function calculateBvPercentage($grandBV)
    {
        if ($grandBV > 1 && $grandBV <= 1000) {
            return 5;
        } elseif ($grandBV > 1000 && $grandBV <= 5000) {
            return 10;
        } elseif ($grandBV > 5000 && $grandBV <= 20000) {
            return 13;
        } elseif ($grandBV > 20000 && $grandBV <= 40000) {
            return 16;
        } elseif ($grandBV > 40000 && $grandBV <= 75000) {
            return 20;
        } elseif ($grandBV > 75000) {
            return 25;
        }
        return 0;
    }

    private function getMemName($memId)
    {
        $member = Member::find($memId);
        return $member ? $member->name : 'Unknown';
    }
}
