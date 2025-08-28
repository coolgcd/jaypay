<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class StockistController extends Controller
{
    public function index(Request $request)
    {
        $msg = '';
        $keywords = $request->input('keyword');
        $members = Member::where('status', 1)
            ->where('cur_rank', 'Stockist')
            ->where('payment', '!=', 0)
            ->when($keywords, function ($query, $keywords) {
                return $query->where('name', 'LIKE', "%$keywords%")
                             ->orWhere('mem_id', 'LIKE', "%$keywords%");
            })
            ->paginate(25); // Pagination

        // Handle status change
        if ($request->input('opr') === 'status') {
            $member = Member::find($request->input('RecID'));

            if ($member) {
                if ($member->active === 'yes') {
                    $member->update(['active' => '', 'inactive' => 'yes']);
                    $msg = "Member deactivated successfully.";
                } else {
                    $member->update(['active' => 'yes', 'inactive' => '']);
                    $msg = "Member activated successfully.";
                }
            } else {
                $msg = "Member not found.";
            }
        }

        return view('admin.members.stockist', compact('members', 'msg', 'keywords'));
    }

    public function showMessage($memId)
    {
        return view('admin.stockist.message', compact('memId'));
    }
}
