<?php

namespace App\Http\Controllers;
use App\Models\RepurchaseMember;
use App\Models\Member;
use Illuminate\Support\Facades\DB; // Import the DB facade

use App\Models\Repurchase;
use Illuminate\Http\Request;

class RepurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Fetch paginated repurchase records
        $repurchases = Repurchase::orderBy('closing_date', 'desc')->paginate(25);

        return view('admin.repurchase.repurchase', compact('repurchases'));
    }

    public function globle()
    {
        $repurchase = Repurchase::first(); // Get the first record from repurchase_tbl
        $members = RepurchaseMember::orderBy('memid', 'asc')->get(); // Fetch members ordered by memid

        return view('admin.repurchase.globle', compact('repurchase', 'members'));
    }

    public function active()
    {
        $repurchase = Repurchase::first(); // Get the first record from repurchase_tbl
        $members = Member::where('matchbv', '!=', 0)->orderBy('mem_id', 'asc')->get(); // Fetch members with matchbv not equal to 0

        return view('admin.repurchase.active', compact('repurchase', 'members'));
    }

    public function leader()
    {
        // Fetch data from the repurchase_tbl
        $semll = DB::table('repurchase_tbl')->first();
        $totCompanyBV = $semll->totcompanybv ?? 0;

        // Fetch the total leadership BV from the members
        $totLeadershipBV = Member::sum('leadershipbbv');
        $Mcto = $totCompanyBV + $totLeadershipBV;
        $leadership = round($Mcto * 15 / 100);
        $Mntrate = round($leadership / 5079);

        // Fetch members with specific conditions
        $members = Member::where('totselfpurch', '!=', 0)
            ->orWhere('mastrtotbb', '!=', 0)
            ->orderBy('mem_id')
            ->get();

        // Pass data to the view
        return view('admin.repurchase.leadership', compact('members', 'Mcto', 'leadership', 'Mntrate'));
    }
    public function executivedirector()
    {
        // Fetch all members
        $members = Member::all(); // You can also paginate or filter if needed

        return view('admin.repurchase.executive_director', compact('members'));
    }
    public function platinum_director()
    {
        // Fetch all members from the database
        $members = Member::all();

        // Return the view with members data
        return view('admin.repurchase.platinum_director', compact('members'));
    }
    public function crowndirector()
    {
        // Fetch all members from the database
        $members = Member::all();

        // Return the view and pass the members' data to it
        return view('admin.repurchase.Crown_Director', compact('members'));
    }
    public function crownambessador()
    {
        // Fetch the members from the database (ensure you have a Member model)
        $members = Member::all();

        // Pass data to the view
        return view('admin.repurchase.crown_ambessador', compact('members'));
    }
    public function royal_corwn_ambb()
    {
        // Fetch the members from the database (ensure you have a Member model)
        $members = Member::all();

        // Pass data to the view
        return view('admin.repurchase.crown_ambessador', compact('members'));
    }
}
