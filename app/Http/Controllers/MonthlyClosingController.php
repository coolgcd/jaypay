<?php

namespace App\Http\Controllers;

use App\Models\MonthlyClosing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MonthlyClosingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('closdate')) {
            Session::put('ClosSession', $request->input('closdate'));
        } else {
            $currentClose = MonthlyClosing::max('closing_date');
            Session::put('ClosSession', $currentClose);
        }

        $monthlyClosings = MonthlyClosing::where('closing_date', Session::get('ClosSession'))->get();
        return view('admin.payouts.monthly_closing', compact('monthlyClosings'));
    }

    public function store(Request $request)
    {
        if ($request->input('hdnclosing') == 1) {
            $totRecv = $request->input('chkDelete');

            foreach ($totRecv as $payValue) {
                $contID = explode("_", base64_decode($payValue));
                $memID = $contID[0];
                $pmtAmt = $contID[2];
                $closingDate = $contID[3];

                // Insert into paymentwidraw (create a model for this if needed)
                // PaymentWithdraw::create([...]);
            }
        }

        return redirect()->route('monthly_closing.index')->with('success', 'Payments processed successfully!');
    }

    public function manageClosingReport(Request $request)
    {
        // Set or get session data for the closing date
        if ($request->has('closdate')) {
            Session::put('ClosSession', $request->closdate);
        } else {
            $currentClosing = MonthlyClosing::max('closing_date');
            Session::put('ClosSession', $currentClosing);
        }

        // Get the distinct closing dates
        $closingDates = MonthlyClosing::select('closing_date')
                        ->distinct()
                        ->orderBy('closing_date', 'asc')
                        ->get();

        // Get data based on the closing date stored in the session
        $closSession = Session::get('ClosSession');
        $closingData = MonthlyClosing::where('closing_date', $closSession)->get();

        return view('admin.payouts.closing_report', compact('closingDates', 'closingData', 'closSession'));
    }

    public function oldlist(Request $request)
    {
        if ($request->has('closdate')) {
            Session::put('ClosSession', $request->input('closdate'));
        } else {
            $currentClose = DB::table('monthly_closing_tbl')
                ->select(DB::raw('MAX(closing_date) as currentclose'))
                ->first();

            Session::put('ClosSession', $currentClose->currentclose);
            Session::put('Maxclose', $currentClose->currentclose);
        }

        // Fetch distinct closing dates
        $closingDates = DB::table('monthly_closing_tbl')
            ->distinct()
            ->orderBy('closing_date', 'desc')
            ->pluck('closing_date');

        // Fetch closing records
        $closSession = Session::get('ClosSession');
        $records = DB::table('monthly_closing_tbl')
            ->where('closing_date', $closSession)
            ->where('oldpayment', '!=', 0)
            ->paginate(1000);

        return view('admin.payouts.oldlist', compact('closingDates', 'records'));
    }

    public function SponsorIncomeList(Request $request)
    {
        $stockistData = [];
        $fromDate = $request->input('fromdate');
        $toDate = $request->input('todate');
        $search = $request->input('hdnsearch');

        if ($search) {
            $fromDateTimestamp = strtotime($fromDate);
            $toDateTimestamp = strtotime($toDate);

            $stockistData = DB::table('member')
                ->where('status', 1)
                ->where('cur_rank', 'Stockist')
                ->get()
                ->map(function ($stockist) use ($fromDateTimestamp, $toDateTimestamp) {
                    $turnover = DB::table('cart_details')
                        ->where('stokist_dist', $stockist->mem_id)
                        ->whereBetween('order_date', [$fromDateTimestamp, $toDateTimestamp])
                        ->sum('net_amt');

                    $commission = $turnover * 2 / 100;
                    $adminFee = $commission * 5 / 100;
                    $tds = $commission * 5 / 100;
                    $finalAmount = $commission - ($adminFee + $tds);

                    return (object)[
                        'sponsor_id' => $stockist->stock_sponsor,
                        'sponsor_name' => GetName($stockist->stock_sponsor), // Replace GetName with your method
                        'stockist_id' => $stockist->mem_id,
                        'stockist_name' => $stockist->name,
                        'turnover' => $turnover,
                        'commission' => $commission,
                        'admin_fee' => $adminFee,
                        'tds' => $tds,
                        'final_amount' => $finalAmount,
                    ];
                });
        }

        return view('admin.payouts.sponsorIncomelist', compact('stockistData', 'fromDate', 'toDate'));
    }

    public function mngpayment(Request $request)
    {
        $limit = 100; // Limit per page
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        // Fetching payment withdrawal records with pagination
        $payments = DB::table('paymentwidraw')
            ->orderBy('wdatetime')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $totalRecords = DB::table('paymentwidraw')->count();

        // Fetch corresponding member name for each record
        $paymentData = $payments->map(function($payment) {
            $member = DB::table('member')
                ->where('mem_id', $payment->memid)
                ->first();
            $payment->member_name = $member ? $member->name : 'Unknown';
            return $payment;
        });

        return view('admin.payouts.mngpayment', compact('paymentData', 'page', 'limit', 'totalRecords'));
    }
}
