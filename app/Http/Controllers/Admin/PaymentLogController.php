<?php
// app/Http/Controllers/Admin/PaymentLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentLog;
use App\Models\Member;

class PaymentLogController extends Controller
{
    public function index(Request $request)
{
    // 1. Build one base query with every filter the user selected.
    $base = PaymentLog::with('member')
        ->when($request->filled('member_id'), fn ($q) => $q->where('member_id', $request->member_id))
        ->when($request->filled('type'),      fn ($q) => $q->where('type',      $request->type))
        ->when($request->filled('sub_type'),  fn ($q) => $q->where('sub_type',  $request->sub_type))
        ->when($request->filled('direction'), fn ($q) => $q->where('direction', $request->direction))
        ->when($request->filled('from_date'), fn ($q) => $q->whereDate('created_at', '>=', $request->from_date))
        ->when($request->filled('to_date'),   fn ($q) => $q->whereDate('created_at', '<=', $request->to_date));

    /*
    |-------------------------------------------------
    | 2.   GRAND-TOTALS  (clone BEFORE paginate)
    |-------------------------------------------------
    */
    $totals = (clone $base)
        ->selectRaw('
            SUM(CASE WHEN direction = "credit" THEN amount ELSE 0 END)  AS total_credit,
            SUM(CASE WHEN direction = "debit"  THEN amount ELSE 0 END)  AS total_debit
        ')
        ->first();

    $totals->net_amount = $totals->total_credit - $totals->total_debit;

    /*
    |-------------------------------------------------
    | 3.   ROWS FOR THIS PAGE
    |-------------------------------------------------
    */
    $logs = $base->orderByDesc('created_at')->paginate(25);

    return view('admin.payment_logs.index', compact('logs', 'totals'));
}


    private function calculateTotals($query)
    {
        $totalCredit = (clone $query)->where('direction', 'credit')->sum('amount');
        $totalDebit = (clone $query)->where('direction', 'debit')->sum('amount');
        
        return [
            'total_credit' => $totalCredit,
            'total_debit' => $totalDebit,
            'net_amount' => $totalCredit - $totalDebit
        ];
    }
}