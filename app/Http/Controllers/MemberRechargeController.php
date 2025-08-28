<?php

namespace App\Http\Controllers;

use App\Models\MemberRechargeList;
use App\Models\Member; // Ensure you have this model

use Illuminate\Http\Request;

class MemberRechargeController extends Controller
{
    public function index(Request $request)
    {
        $recharges = MemberRechargeList::orderBy('add_date', 'desc')->paginate(25);
        return view('admin.recharge.member_recharge', compact('recharges'));
    }

    public function getName($memid)
    {
        $member = Member::find($memid); // Get member by memid
        return $member ? $member->name : 'Unknown Member'; // Return name or default
    }
}
