<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberBankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $keyword = $request->input('keyword', '');

        $query = Member::where('status', 1);

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('mem_id', 'like', "%{$keyword}%")
                  ->orWhere('mobileno', 'like', "%{$keyword}%");
            });
        }

        $members = $query->paginate(100);
        return view('admin.members.kyc', compact('members', 'keyword'));
    }

    public function update(Request $request)
    {
        $recId = $request->input('RecID');
        $approve = $request->input('approve');

        $member = Member::findOrFail($recId);

        if ($approve == 1) {
            $member->activate_date = 1;
            $msg = "KYC Approved";
        } elseif ($approve == 0) {
            $member->activate_date = 0;
            $msg = "KYC Disapproved";
        } else {
            $member->activate_date = 0;
            $msg = "KYC Disapproved";
        }

        $member->save();

        return redirect()->route('manage.kyc')->with('message', $msg);
    }
}
