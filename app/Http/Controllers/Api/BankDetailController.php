<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankDetailController extends Controller
{
    // Store user bank details
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:11',
            'branch_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bankDetail = BankDetail::create($request->all());

        return response()->json(['message' => 'Bank details saved successfully!', 'data' => $bankDetail], 201);
    }

    // Update user bank details
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:11',
            'branch_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bankDetail = BankDetail::findOrFail($id);
        $bankDetail->update($request->all());

        return response()->json(['message' => 'Bank details updated successfully!', 'data' => $bankDetail], 200);
    }
}
