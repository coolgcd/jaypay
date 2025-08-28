<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\ManagePv;
use App\Models\BinaryPin;
use App\Helpers\PaymentLogHelper;

use App\Models\Admin;
use App\Models\Member;

use Carbon\Carbon;

class AdminTopupPinController extends Controller
{
  
    public function create(Request $request)
    {
        $pinTypes = ManagePV::where('status', 1)->get();

        return view('admin.topuppin.create', [
            'pinTypes' => $pinTypes,
           'prefill' => [
        'member_id' => $request->query('member_id'),
        'pin_type' => $request->query('package_amount'), // <-- this is what you're sending
        'quantity' => $request->query('pin_count'),
    ],
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'pintype' => 'required',
            'pin_count' => 'required|integer|min:1|max:10',
            'member_id' => 'required'
        ]);

        // Check if member exists
        $member = DB::table('member')->where('show_mem_id', $request->member_id)->first();
        if (!$member) {
            return back()->with('error', 'Invalid Member ID');
        }

        // Get pin type details
        $pinType = DB::table('manage_pv')->where('id', $request->pintype)->first();
        if (!$pinType) {
            return back()->with('error', 'Invalid Pin Type');
        }

        $generatedPins = [];
        
        for ($i = 0; $i < $request->pin_count; $i++) {
            // Generate random 15 digit PIN
            $pincode = $this->generateRandomPin();
            
            // Insert into binary_pin table
            // FIXED: Use show_mem_id instead of internal id
            DB::table('binary_pin')->insert([
                'member_id' => $member->show_mem_id, // Changed from $member->id to $member->show_mem_id
                'pincode' => $pincode,
                'pinamt' => $pinType->pv_amount,
                'total_pv' => $pinType->pv_value,
                'used_by' => 0,
                'transfer_to' => 0,
                'transfer_date' => 0,
                'status' => 0, // 0 = unused, 1 = used
                'joinid' => 0,
                'pintype' => $pinType->pintype,
                'pintp' => $pinType->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $generatedPins[] = $pincode;
        }

        

          return redirect()
        ->route('admin.payments.index')
        ->with('success', 'PINs generated successfully for Member ID: ' . $request->member_id)
        ->with('generated_pins', $generatedPins);
}


    // Generate random 15 digit PIN
    private function generateRandomPin()
    {
        do {
            $pin = '';
            for ($i = 0; $i < 15; $i++) {
                $pin .= rand(0, 9);
            }
        } while (DB::table('binary_pin')->where('pincode', $pin)->exists());
        
        return $pin;
    }

    // Used PINs page
    public function used()
    {
        // FIXED: Updated joins to work with show_mem_id
        $usedPins = DB::table('binary_pin as bp')
            ->join('member as m1', 'bp.member_id', '=', 'm1.show_mem_id') // Changed join condition
            ->leftJoin('member as m2', 'bp.used_by', '=', 'm2.show_mem_id') // Changed join condition
            ->select(
                'bp.*',
                'm1.name as member_name',
                'm1.show_mem_id as member_id',
                'm2.name as used_by_name',
                'm2.show_mem_id as used_by_id'
            )
            ->where('bp.status', 1)
            ->orderBy('bp.used_at', 'desc')
            ->paginate(15);

        return view('admin.topuppin.used', compact('usedPins'));
    }

    // Unused PINs page
    public function unused()
    {
        // FIXED: Updated join to work with show_mem_id
        $unusedPins = DB::table('binary_pin as bp')
            ->join('member as m', 'bp.member_id', '=', 'm.show_mem_id') // Changed join condition
            ->select(
                'bp.*',
                'm.name as member_name',
                'm.show_mem_id as member_id'
            )
            ->where('bp.status', 0)
            ->orderBy('bp.created_at', 'desc')
            ->paginate(15);

        return view('admin.topuppin.unused', compact('unusedPins'));
    }

    // Delete unused PIN
    public function deletePin($id)
    {
        $pin = DB::table('binary_pin')->where('id', $id)->where('status', 0)->first();
        if ($pin) {
            DB::table('binary_pin')->where('id', $id)->delete();
            return back()->with('success', 'PIN deleted successfully');
        }
        return back()->with('error', 'PIN not found or already used');
    }
}