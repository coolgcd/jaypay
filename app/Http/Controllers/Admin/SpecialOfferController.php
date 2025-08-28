<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpecialOfferController extends Controller
{
  protected $specialOfferSlabs = [
    200000    => ['rank' => 'Washing Machine'],
    500000    => ['rank' => 'Andaman Nicobar Tour'],    
    700000    => ['rank' => 'Bike Splendor Plus + Tour'],
    1000000   => ['rank' => 'Car down payment 2 Lakhs (WagonR) + Tour'],
    1500000   => ['rank' => 'Car down payment 3 Lakhs (Swift VDI) + Tour'],
    2000000   => ['rank' => 'Car down payment 5 Lakhs (Tata Punch) + Tour'],
    3000000   => ['rank' => 'Car down payment 1.5 Lakhs (Mahindra XUV) + Tour'],
    4000000   => ['rank' => 'Car down payment 10 Lakhs (Tata Curve) + Tour'],
    5000000   => ['rank' => 'Car down payment 15 Lakhs (Scorpio N) + Tour']
];


    public function index(Request $request)
    {
        $stats = $this->getOfferStats();
        $eligibleMembers = $this->getEligibleMembers($request);
        $todayAchievers = $this->getTodayAchievers();

        return view('admin.special-offer.index', compact('stats', 'eligibleMembers', 'todayAchievers'));
    }

    public function process(Request $request)
    {
        try {
            // Check if offer is within valid dates
            $currentDate = Carbon::now();
            $offerStartDate = Carbon::parse('2025-08-01');
            $offerEndDate = Carbon::parse('2025-09-15');

            if ($currentDate->lt($offerStartDate) || $currentDate->gt($offerEndDate)) {
                return redirect()->back()->with('error', 'Monsoon Offer period has expired! (Valid from 01 August to 15 September)');
            }

            $processedCount = $this->processSpecialOfferRewards();

            return redirect()->back()->with('success', "Special Monsoon Offer processed! {$processedCount} new achievements recorded.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing special offer: ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $achievements = $this->getAchievementsReport($request);
        $levelStats = $this->getLevelWiseStats();

        return view('admin.special-offer.report', compact('achievements', 'levelStats'));
    }

    // private function processSpecialOfferRewards()
    // {
    //     $members = DB::table('member_binary')
    //         ->where('left', '!=', '')
    //         ->where('right', '!=', '')
    //         ->where('status', 1)
    //         ->select('memid')
    //         ->get();

    //     $processedCount = 0;
    //     $currentTimestamp = Carbon::now();

    //     foreach ($members as $member) {
    //         $rewardSlabs = collect($this->specialOfferSlabs)->sortKeys();

    //         $gettotmatching = DB::table('binary_payouts')
    //             ->where('member_id', $member->memid)
    //             ->sum('tot_matching');

    //         $usedPairs = DB::table('special_offer_rewards')
    //             ->where('member_id', $member->memid)
    //             ->sum('matching_pair');

    //         $availablePairs = (int)$gettotmatching - (int)$usedPairs;

    //         if ($availablePairs <= 0) {
    //             continue;
    //         }

    //         foreach ($rewardSlabs as $requiredPairs => $data) {
    //             if ($availablePairs < $requiredPairs) {
    //                 continue;
    //             }

    //             $alreadyExists = DB::table('special_offer_rewards')
    //                 ->where('member_id', $member->memid)
    //                 ->where('matching_pair', $requiredPairs)
    //                 ->exists();

    //             if ($alreadyExists) {
    //                 continue;
    //             }

    //             // Just record the achievement - NO PAYMENT
    //             DB::table('special_offer_rewards')->insert([
    //                 'member_id'        => $member->memid,
    //                 'matching_pair'    => $requiredPairs,
    //                 'rank'             => $data['rank'],
    //                 'offer_type'       => 'monsoon_2025',
    //                 'achieved_date'    => $currentTimestamp,
    //                 'status'           => 1,
    //                 'created_at'       => $currentTimestamp,
    //                 'updated_at'       => $currentTimestamp,
    //             ]);

    //             $availablePairs -= $requiredPairs;
    //             $processedCount++;

    //             if ($availablePairs <= 0) {
    //                 break;
    //             }
    //         }
    //     }

    //     return $processedCount;
    // }

    private function getOfferStats()
    {
        $offerStartDate = Carbon::parse('2025-08-01');
        $offerEndDate = Carbon::parse('2025-09-15');
        $currentDate = Carbon::now();

        $totalAchievements = DB::table('special_offer_rewards')
            ->where('offer_type', 'monsoon_2025')
            ->count();

        $todayAchievements = DB::table('special_offer_rewards')
            ->where('offer_type', 'monsoon_2025')
            ->whereDate('achieved_date', Carbon::today())
            ->count();

        return [
            'total_achievements' => $totalAchievements,
            'today_achievements' => $todayAchievements,
            'unique_achievers' => DB::table('special_offer_rewards')->distinct('member_id')->count(),
            'offer_active' => $currentDate->between($offerStartDate, $offerEndDate),
            'days_remaining' => $currentDate->lt($offerEndDate) ? $currentDate->diffInDays($offerEndDate) : 0,
            'offer_start_date' => $offerStartDate->format('d M Y'),
            'offer_end_date' => $offerEndDate->format('d M Y'),
        ];
    }

    private function getEligibleMembers($request)
{
    $augustStartDate = '2025-08-01 00:00:00';
    
    $query = "
        SELECT 
            mb.memid,
            m.name as member_name,
            COALESCE(SUM(bp.tot_matching), 0) as total_matching_august,
            COALESCE(sor_used.used_pairs, 0) as used_pairs,
            (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) as available_pairs,
           CASE 
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 5000000 THEN 'Car down payment 15 Lakhs (Scorpio N) + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 4000000 THEN 'Car down payment 10 Lakhs (Tata Curve) + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 3000000 THEN 'Car down payment 1.5 Lakhs (Mahindra XUV) + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 2000000 THEN 'Car down payment 5 Lakhs (Tata Punch) + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 1500000 THEN 'Car down payment 3 Lakhs (Swift VDI) + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 1000000 THEN 'Car down payment 2 Lakhs (WagonR) + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 700000 THEN 'Bike Splendor Plus + Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 500000 THEN 'Andaman Nicobar Tour'
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 200000 THEN 'Washing Machine'
    ELSE 'Working towards first level'
END as eligible_for,

          CASE 
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 5000000 THEN 5000000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 4000000 THEN 4000000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 3000000 THEN 3000000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 2000000 THEN 2000000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 1500000 THEN 1500000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 1000000 THEN 1000000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 700000 THEN 700000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 500000 THEN 500000
    WHEN (COALESCE(SUM(bp.tot_matching), 0) - COALESCE(sor_used.used_pairs, 0)) >= 200000 THEN 200000
    ELSE 0
END as eligible_level

        FROM member_binary mb
        LEFT JOIN member m ON mb.memid = m.show_mem_id
        LEFT JOIN binary_payouts bp ON mb.memid = bp.member_id 
            AND bp.created_at >= UNIX_TIMESTAMP('$augustStartDate')
        LEFT JOIN (
            SELECT member_id, SUM(matching_pair) as used_pairs 
            FROM special_offer_rewards 
            GROUP BY member_id
        ) sor_used ON mb.memid = sor_used.member_id
        WHERE mb.status = 1 
        AND mb.left != '' 
        AND mb.right != ''
        GROUP BY mb.memid, m.name, sor_used.used_pairs
        HAVING COALESCE(SUM(bp.tot_matching), 0) > 0
    ";

    // Fix the ORDER BY clause
    $sortBy = $request->get('sort', 'available_pairs');
    $sortDirection = $request->get('direction', 'desc');
    
    $sortMapping = [
        'available_pairs' => 'available_pairs',
        'total_matching' => 'total_matching_august',
        'eligible_level' => 'eligible_level',
        'member_name' => 'm.name'
    ];
    
    $actualSortBy = $sortMapping[$sortBy] ?? 'available_pairs';
    $query .= " ORDER BY {$actualSortBy} {$sortDirection}";

    return DB::select($query);
}



    // Also update the processSpecialOfferRewards method
    private function processSpecialOfferRewards()
    {
        $members = DB::table('member_binary')
            ->where('left', '!=', '')
            ->where('right', '!=', '')
            ->where('status', 1)
            ->select('memid')
            ->get();

        $processedCount = 0;
        $currentTimestamp = Carbon::now();
        $augustStartTimestamp = Carbon::parse('2025-08-01 00:00:00')->timestamp;

        foreach ($members as $member) {
            $rewardSlabs = collect($this->specialOfferSlabs)->sortKeys();

            // Get matching from August 1st onwards only
            $gettotmatching = DB::table('binary_payouts')
                ->where('member_id', $member->memid)
                ->where('created_at', '>=', $augustStartTimestamp)
                ->sum('tot_matching');

            $usedPairs = DB::table('special_offer_rewards')
                ->where('member_id', $member->memid)
                ->sum('matching_pair');

            $availablePairs = (int)$gettotmatching - (int)$usedPairs;

            if ($availablePairs <= 0) {
                continue;
            }

            foreach ($rewardSlabs as $requiredPairs => $data) {
                if ($availablePairs < $requiredPairs) {
                    continue;
                }

                $alreadyExists = DB::table('special_offer_rewards')
                    ->where('member_id', $member->memid)
                    ->where('matching_pair', $requiredPairs)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                // Just record the achievement - NO PAYMENT
                DB::table('special_offer_rewards')->insert([
                    'member_id'        => $member->memid,
                    'matching_pair'    => $requiredPairs,
                    'rank'             => $data['rank'],
                    'offer_type'       => 'monsoon_2025',
                    'achieved_date'    => $currentTimestamp,
                    'status'           => 1,
                    'created_at'       => $currentTimestamp,
                    'updated_at'       => $currentTimestamp,
                ]);

                $availablePairs -= $requiredPairs;
                $processedCount++;

                if ($availablePairs <= 0) {
                    break;
                }
            }
        }

        return $processedCount;
    }


    private function getTodayAchievers()
    {
        return DB::table('special_offer_rewards as sor')
            ->join('member as m', 'sor.member_id', '=', 'm.show_mem_id')
            ->select(
                'sor.*',
                'm.name as member_name'
            )
            ->whereDate('sor.achieved_date', Carbon::today())
            ->orderBy('sor.matching_pair', 'desc')
            ->get();
    }

    private function getAchievementsReport($request)
    {
        $query = DB::table('special_offer_rewards as sor')
            ->join('member as m', 'sor.member_id', '=', 'm.show_mem_id')
            ->select(
                'sor.*',
                'm.name as member_name'
            )
            ->where('sor.offer_type', 'monsoon_2025');

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('sor.achieved_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sor.achieved_date', '<=', $request->date_to);
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('sor.matching_pair', $request->level);
        }

        return $query->orderBy('sor.achieved_date', 'desc')
            ->paginate(25);
    }

    private function getLevelWiseStats()
    {
        return DB::table('special_offer_rewards')
            ->select('matching_pair', 'rank', DB::raw('COUNT(*) as total_achievers'))
            ->where('offer_type', 'monsoon_2025')
            ->groupBy('matching_pair', 'rank')
            ->orderBy('matching_pair', 'desc')
            ->get();
    }
}
