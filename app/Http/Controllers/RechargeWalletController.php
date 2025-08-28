<?php

namespace App\Http\Controllers;

use App\Models\RechargeWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechargeWalletController extends Controller
{
    public function index(Request $request)
    {
        $msg = '';

        // Delete action
        if ($request->input('Morl') == 'Del') {
            RechargeWallet::where('ewallid', $request->input('MTransID'))->delete();
            $msg = "Record deleted.";
        }

        // Fund transfer action
        if ($request->input('hdntransfer') == 1) {
            RechargeWallet::create([
                'memid' => $request->input('memid'),
                'creditamt' => $request->input('fundval'),
                'add_date' => time(),
                'purpose' => 'Admin Credit',
            ]);
            $msg = "Fund Transfer Successfully.";
        }

        $rechargeWallets = RechargeWallet::where('purpose', 'Admin Credit')->paginate(10);

        return view('admin.recharge.manage_payment_list', compact('rechargeWallets', 'msg'));
    }
}
