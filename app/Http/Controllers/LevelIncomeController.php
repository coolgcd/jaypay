<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelIncome;
use App\Models\Member; // Make sure to create a Member model

class LevelIncomeController extends Controller
{
    public function index(Request $request)
    {
        // Default query to fetch all level income records
        $levelIncomes = LevelIncome::orderBy('rec_date', 'desc')->paginate(25);

        return view('admin.payouts.mnglevelincome', compact('levelIncomes'));
    }

    public function search(Request $request)
    {
        // Keyword search logic
        $keyword = $request->input('keyword');

        $levelIncomes = LevelIncome::where('memid', 'like', "%$keyword%")
            ->orWhere('fromid', 'like', "%$keyword%")
            ->orderBy('rec_date', 'desc')
            ->paginate(25);

        return view('admin.payouts.mnglevelincome', compact('levelIncomes', 'keyword'));
    }

    public function silverclub(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $page = $request->input('page', 1);
        $limit = 25;

        $query = Member::where('tot_ref', '>', 9)
            ->where('status', 1)
            ->where('promotdate', '!=', 0);

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhere('mem_id', 'like', "%$keyword%")
                  ->orWhere('mobileno', 'like', "%$keyword%");
            });
        }

        $members = $query->orderBy('activate_date', 'desc')->paginate($limit);

        return view('admin.payouts.silverclub', compact('members', 'keyword'));
    }
}
