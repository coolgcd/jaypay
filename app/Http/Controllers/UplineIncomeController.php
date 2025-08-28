<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UplineIncome; // Make sure to create a corresponding model
use App\Models\DownlineIncome; // Assuming you have a model for downline_income

use Illuminate\Support\Facades\DB;

class UplineIncomeController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->input('keyword', '');

        // Build the query
        $query = UplineIncome::query();

        if ($keywords) {
            $query->where('memid', 'like', "%{$keywords}%");
        }

        // Get paginated results
        $uplineIncomes = $query->orderBy('rec_date')->paginate(25);

        return view('admin.payouts.uplinelist', compact('uplineIncomes', 'keywords'));
    }
    public function downlineInc(Request $request)
    {
        // Default page number
        $page = $request->input('page', 1);
        $keyword = $request->input('keyword', '');

        // Querying the database
        $query = DownlineIncome::query();

        if ($keyword) {
            $query->where('memid', 'like', '%' . $keyword . '%');
        }

        // Paginate the results
        $downlineIncomes = $query->orderBy('rec_date', 'desc')->paginate(25);

        return view('admin.payouts.downlineinc', compact('downlineIncomes', 'keyword'));
    }
}
