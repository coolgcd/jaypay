<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminRechargeController extends Controller
{
    public function index()
    {
      $recharges = DB::table('recharge_requests')
    ->leftJoin('member', 'recharge_requests.member_id', '=', 'member.show_mem_id')
    ->select(
        'recharge_requests.*',
        'member.name as member_name'
    )
            ->orderByDesc('recharge_requests.id')
            ->get();

        return view('admin.recharge.index', compact('recharges'));
    }
}
