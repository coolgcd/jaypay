<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonsoonOfferController extends Controller
{
    protected $specialOfferSlabs = [
        200000    => ['rank' => 'Washing Machine', 'icon' => 'fas fa-tshirt', 'color' => 'info'],
        500000    => ['rank' => 'Andaman Nicobar Tour', 'icon' => 'fas fa-umbrella-beach', 'color' => 'success'],    
        700000    => ['rank' => 'Bike Splendor Plus + Tour', 'icon' => 'fas fa-motorcycle', 'color' => 'warning'],
        1000000   => ['rank' => 'Car down payment 2 Lakhs (WagonR) + Tour', 'icon' => 'fas fa-car', 'color' => 'primary'],
        1500000   => ['rank' => 'Car down payment 3 Lakhs (Swift VDI) + Tour', 'icon' => 'fas fa-car', 'color' => 'primary'],
        2000000   => ['rank' => 'Car down payment 5 Lakhs (Tata Punch) + Tour', 'icon' => 'fas fa-car', 'color' => 'primary'],
        3000000   => ['rank' => 'Car down payment 1.5 Lakhs (Mahindra XUV) + Tour', 'icon' => 'fas fa-truck', 'color' => 'dark'],
        4000000   => ['rank' => 'Car down payment 10 Lakhs (Tata Curve) + Tour', 'icon' => 'fas fa-truck', 'color' => 'dark'],
        5000000   => ['rank' => 'Car down payment 15 Lakhs (Scorpio N) + Tour', 'icon' => 'fas fa-truck', 'color' => 'danger']
    ];

    public function index()
    {
        $member = Auth::guard('member')->user();
        $memberProgress = $this->getMemberProgress($member->show_mem_id);
        $offerStats = $this->getOfferStats();
        $memberAchievements = $this->getMemberAchievements($member->show_mem_id);
          $specialOfferSlabs = $this->specialOfferSlabs; // Add this line
        
         return view('member.monsoon-offer', compact('memberProgress', 'offerStats', 'memberAchievements', 'specialOfferSlabs'));
    }

    private function getMemberProgress($memberId)
    {
        $augustStartTimestamp = Carbon::parse('2025-08-01 00:00:00')->timestamp;
        
        // Get member's total matching from August
        $totalMatching = DB::table('binary_payouts')
            ->where('member_id', $memberId)
            ->where('created_at', '>=', $augustStartTimestamp)
            ->sum('tot_matching');

        // Get used pairs from special offer
        $usedPairs = DB::table('special_offer_rewards')
            ->where('member_id', $memberId)
            ->sum('matching_pair');

        $availablePairs = (int)$totalMatching - (int)$usedPairs;

        // Calculate current level and next target
        $currentLevel = 0;
        $nextTarget = 200000;
        $currentPrize = 'Not eligible yet';
        $nextPrize = 'Washing Machine';
        $progressPercentage = 0;

        foreach ($this->specialOfferSlabs as $level => $data) {
            if ($availablePairs >= $level) {
                $currentLevel = $level;
                $currentPrize = $data['rank'];
            } else {
                $nextTarget = $level;
                $nextPrize = $data['rank'];
                $progressPercentage = ($availablePairs / $nextTarget) * 100;
                break;
            }
        }

        // If achieved highest level
        if ($availablePairs >= 5000000) {
            $nextTarget = 5000000;
            $nextPrize = 'Maximum Level Achieved!';
            $progressPercentage = 100;
        }

        return [
            'total_matching' => $totalMatching,
            'available_pairs' => $availablePairs,
            'current_level' => $currentLevel,
            'current_prize' => $currentPrize,
            'next_target' => $nextTarget,
            'next_prize' => $nextPrize,
            'progress_percentage' => min(100, $progressPercentage),
            'remaining_for_next' => max(0, $nextTarget - $availablePairs)
        ];
    }

    private function getOfferStats()
    {
        $offerStartDate = Carbon::parse('2025-08-01');
        $offerEndDate = Carbon::parse('2025-09-15');
        $currentDate = Carbon::now();

        return [
            'offer_active' => $currentDate->between($offerStartDate, $offerEndDate),
            'days_remaining' => $currentDate->lt($offerEndDate) ? $currentDate->diffInDays($offerEndDate) : 0,
            'offer_start_date' => $offerStartDate->format('d M Y'),
            'offer_end_date' => $offerEndDate->format('d M Y'),
            'total_participants' => DB::table('special_offer_rewards')->distinct('member_id')->count()
        ];
    }

    private function getMemberAchievements($memberId)
    {
        return DB::table('special_offer_rewards')
            ->where('member_id', $memberId)
            ->orderBy('matching_pair', 'desc')
            ->get();
    }
}
