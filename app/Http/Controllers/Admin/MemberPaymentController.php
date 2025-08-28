<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberPayment;
use App\Models\ManagePv; // Assuming package info stored here
use App\Helpers\PaymentLogHelper;

class MemberPaymentController extends Controller
{
    public function index()
    {
        $payments = MemberPayment::latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function update(Request $request, $id)
    {
        $payment = MemberPayment::findOrFail($id);
        
        // Check if payment is already approved - prevent any changes
        if ($payment->status === 'approved') {
            return back()->with('error', 'Cannot modify approved payments. Status is locked.');
        }
        
        // Store the old status for comparison
        $oldStatus = $payment->status;
        
        // Update the payment
        $payment->status = $request->input('status');
        $payment->admin_remarks = $request->input('admin_remarks');
        $payment->save();
        
        // If status was changed to approved, log the payment and redirect to topup
        if ($payment->status === 'approved') {
            PaymentLogHelper::log(
                type: 'member_payment',
                member_id: $payment->member_id,
                sub_type: 'admin_credit',
                amount: $payment->total_amount,
                direction: 'credit',
                source: 'admin',
                description: "Admin approved payment of ₹{$payment->total_amount} for member {$payment->member_id}",
                remarks: $payment->admin_remarks
            );
            
            return redirect()->route('admin.topuppin.create', [
                'member_id' => $payment->member_id, 
                'package_amount' => $payment->package_amount, 
                'pin_count' => $payment->quantity
            ])->with('success', 'Payment approved successfully and redirected to topup.');
        }
        
        // For other status changes (pending to rejected, rejected to pending, etc.)
        $statusMessage = match($payment->status) {
            'pending' => 'Payment status set to pending.',
            'rejected' => 'Payment has been rejected.',
            default => 'Payment status updated successfully.'
        };
        
        return back()->with('success', $statusMessage);
    }
}