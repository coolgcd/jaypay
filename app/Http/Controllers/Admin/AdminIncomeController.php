<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminIncomeController extends Controller
{
    // DAILY INCOME
   public function daily(Request $request)
{
    $query = DB::table('member_income_history as mih')
        ->join('member as m', 'm.show_mem_id', '=', 'mih.member_id') // ⚠️ Your member_id is show_mem_id string
        ->select('mih.*', 'm.name', 'm.show_mem_id');

    if ($request->show_mem_id) {
        $query->where('m.show_mem_id', 'like', '%' . $request->show_mem_id . '%');
    }

    if ($request->name) {
        $query->where('m.name', 'like', '%' . $request->name . '%');
    }

    if ($request->date) {
        $timestamp = Carbon::parse($request->date)->startOfDay()->timestamp;
        $query->where('mih.date', $timestamp);
    }

    $records = $query->orderByDesc('mih.id')->paginate(15);

    return view('admin.income.daily', compact('records'));
}
    // DIRECT INCOME
   public function direct(Request $request)
{
    $query = DB::table('sponsor_daily_income as sdi')
        ->leftJoin('member as m', 'm.show_mem_id', '=', 'sdi.member_id')
        ->select('sdi.*', 'm.name', 'm.show_mem_id');

    if ($request->show_mem_id) {
        $query->where('sdi.member_id', 'like', '%' . $request->show_mem_id . '%');
    }

    if ($request->name) {
        $query->where('m.name', 'like', '%' . $request->name . '%');
    }

    if ($request->date) {
        $query->whereDate('sdi.created_at', $request->date);
    }

    $records = $query->orderByDesc('sdi.id')->paginate(20);

    return view('admin.income.direct', compact('records'));
}

    // MATCHING INCOME
 public function matching(Request $request)
{
    $query = DB::table('binary_payouts as b')
        ->join('member as m', 'b.member_id', '=', 'm.show_mem_id')

        ->select(
            'b.*',
            'm.name',
            'm.show_mem_id'
        )
        ->orderByDesc('b.id');

    if ($request->filled('show_mem_id')) {
        $query->where('m.show_mem_id', 'like', '%' . $request->show_mem_id . '%');
    }

    if ($request->filled('name')) {
        $query->where('m.name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('date')) {
        $timestamp = Carbon::parse($request->date)->startOfDay()->timestamp;
        $query->where('b.confirm_date', '>=', $timestamp)
              ->where('b.confirm_date', '<=', $timestamp + 86400);
    }

    $data = $query->paginate(15);

    return view('admin.income.matching', compact('data'));
}

    // SALARY INCOME
 public function salary(Request $request)
{
    $query = DB::table('salary_income as si')
        ->join('member as m', 'si.member_id', '=', 'm.show_mem_id')
        ->select('si.*', 'm.name')
        ->orderByDesc('si.id');

    // Filters
    if ($request->has('member_id') && $request->member_id !== null) {
        $query->where('si.member_id', 'like', '%' . $request->member_id . '%');
    }

    if ($request->has('name') && $request->name !== null) {
        $query->where('m.name', 'like', '%' . $request->name . '%');
    }

    if ($request->has('from_date') && $request->from_date !== null) {
        $timestamp = strtotime($request->from_date);
        $query->where('si.from_date', '>=', $timestamp);
    }

    if ($request->has('to_date') && $request->to_date !== null) {
        $timestamp = strtotime($request->to_date);
        $query->where('si.to_date', '<=', $timestamp);
    }

    $salaryIncomes = $query->paginate(15);

    return view('admin.income.salary', compact('salaryIncomes'));
}


    // REWARD INCOME
 public function reward(Request $request)
{
    $query = DB::table('reward_income as r')
        ->join('member as m', 'r.member_id', '=', 'm.show_mem_id')
        ->select(
            'r.*',
            'm.name as member_name'
        )
        ->orderByDesc('r.id');

    // Filters
    if ($request->filled('member_id')) {
        $query->where('r.member_id', 'like', '%' . $request->member_id . '%');
    }

    if ($request->filled('name')) {
        $query->where('m.name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('date')) {
        $query->whereDate('r.created_at', '=', $request->date);
    }

    $rewards = $query->paginate(15);

    return view('admin.income.reward', compact('rewards'));
}
}
