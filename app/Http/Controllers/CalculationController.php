<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalculationController extends Controller
{
    public function index(Request $request)
    {
        $MSG = null;

        if ($request->query('Opr') == 'Add') {
            session(['ClosingDate' => Carbon::create(null, null, null, 23, 50, 0)->timestamp]);
        }

        $closingDate = session('ClosingDate', null);
        $results = [];

        if ($closingDate) {
            $results = DB::table('repurchase_tbl')
                ->where('closing_date', $closingDate)
                ->get();
        }

        return view('admin.products.calculations', compact('MSG', 'results'));
    }
}
