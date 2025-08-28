<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberUpgrade; // Make sure you have a model for member_upgrade_tbl
use App\Models\Member; // Make sure you have a model for member
use App\Models\MemberMonthIncome;
use App\Models\MemLevelIncome;

class MemberUpgradeController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->input('keyword');

        // Query to get member upgrades with optional keyword filtering
        $upgrades = MemberUpgrade::query()
            ->when($keywords, function ($query, $keywords) {
                return $query->where('memid', $keywords);
            })
            ->where('status', 0); // Set pagination limit

        return view('admin.manage_upgrade_list', compact('upgrades', 'keywords'));
    }

    public function upgrade($id)
    {
        $upgrade = MemberUpgrade::findOrFail($id);
        $upgrade->upg_confirm_date = time();
        $upgrade->status = 1;
        $upgrade->upgradetype = 'Paid';
        $upgrade->save();

        return redirect()->route('member.upgrade.index')->with('message', 'Member upgraded successfully.');
    }

    public function freeUpgrade($id)
    {
        $upgrade = MemberUpgrade::findOrFail($id);
        $upgrade->upg_confirm_date = time();
        $upgrade->status = 1;
        $upgrade->upgradetype = 'Free';
        $upgrade->save();

        return redirect()->route('member.upgrade.index')->with('message', 'Member free upgraded successfully.');
    }

    public function addmember()
    {


        return view('admin.add_new_upgrade');
    }
    public function upgradeMember(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'nmemid' => 'required|string|max:255',
        'nmlevelupg' => 'required|string'
    ]);

    $memid = $validatedData['nmemid'];
    $levelData = explode("_", $validatedData['nmlevelupg']);
    $levelId = $levelData[0];
    $upgradeAmount = $levelData[1];

    // Check if the member has already been upgraded
    $existingUpgrade = MemberUpgrade::where('memid', $memid)
        ->where('levelid', $levelId)
        ->where('upg_amount', $upgradeAmount)
        ->first();

    if ($existingUpgrade) {
        $msg = "Member already upgraded!";
    } else {
        // Insert new member upgrade record
        MemberUpgrade::create([
            'memid' => $memid,
            'levelid' => $levelId,
            'upg_amount' => $upgradeAmount,
            'upg_req_date' => time(),
            'upg_confirm_date' => time(),
            'status' => 1
        ]);

        $msg = "Member upgraded successfully.";
    }

    // Return back to the view with a message
    return back()->with('message', $msg);
}

public function singleLegPayout(Request $request)
{
    $keywords = $request->input('keyword');
    $query = MemberMonthIncome::query();

    // Filter by keyword (Member ID)
    if ($keywords) {
        $query->where('memid', $keywords);
    }

    // Pagination and sorting by receive date
    $payouts = $query->orderBy('receivedate', 'desc');

    // Return the view with the payouts and keywords
    return view('admin.single_leg_payout', compact('payouts', 'keywords'));
}
public function leg()
{
    $results = MemberMonthIncome::orderBy('receivedate', 'desc');
    return view('admin.laps_single_pay_list', compact('results'));
}

public function legsearch(Request $request)
{
    $keyword = $request->input('keyword');

    $query = MemberMonthIncome::query();

    if ($keyword) {
        $query->where('memid', $keyword);
    }

    $results = $query->orderBy('receivedate', 'desc')->paginate(100);

    return view('admin.single_leg_payout', compact('results', 'keyword'));
}
public function leval(Request $request)
{
    $keyword = $request->input('keyword');
    $query = MemLevelIncome::query();

    if ($keyword) {
        $query->where('memid', $keyword);
    }

    $incomes = $query->orderBy('rec_date', 'desc');

    return view('admin.level_payout_list', compact('incomes', 'keyword'));
}

}
