<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BinaryPin; // Assuming you create a model for the binary_pin table
use App\Models\ManagePv; // Assuming you create a model for the manage_pv table

class PinController extends Controller
{
    public function create()
    {
        $managePvs = ManagePv::where('status', 1)->get();
        return view('admin.pin_create', compact('managePvs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'totalpin' => 'required|numeric',
            'addedfor' => 'required',
            'pinamt' => 'required',
            'regpinno' => 'required|array',
        ]);

        $managePv = ManagePv::where('pv_amount', $request->pinamt)->first();

        if ($managePv) {
            $totpay = $managePv->pv_amount;
            $totpv = $managePv->pv_value;

            foreach ($request->regpinno as $nvalue) {
                BinaryPin::create([
                    'memid' => $request->addedfor,
                    'pincode' => $nvalue,
                    'pinamt' => $totpay,
                    'total_pv' => $totpv,
                    'creat_date' => now(),
                    'used_date' => null,
                    'transfer_to' => null,
                    'transfer_date' => null,
                    'pintype' => $managePv->comment,
                    'pintp' => $managePv->pid,
                ]);
            }

            $message = "Total : " . $request->totalpin . " Pin transfer to member ID: " . $request->addedfor . " On Date: " . now()->format("h:i A d-m-Y");
            // Optionally send SMS here

            return redirect()->route('pins.create')->with('success', $message);
        }

        return redirect()->route('pins.create')->withErrors('Failed to find the specified pin amount.');
    }

    public function generatePins(Request $request)
    {
        $totalGenPin = $request->input('totalpin');
        $pins = [];

        for ($i = 1; $i <= $totalGenPin; $i++) {
            $pins[] = $this->generateUniquePin();
        }

        return view('pins.generate', compact('pins', 'totalGenPin'));
    }

    private function generateUniquePin($length = 6)
    {
        $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRESTUVWXYZ_";
        $password = "";

        while (strlen($password) < $length) {
            $char = substr($possible, rand(0, strlen($possible) - 1), 1);
            if (strpos($password, $char) === false) {
                $password .= $char;
            }
        }

        return $password;
    }
}
