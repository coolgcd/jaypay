<?php
// app/Http/Controllers/AdminMemberController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\MemberBankDetail;


use Illuminate\Support\Facades\Auth;

class AdminMemberController extends Controller
{
    public function index(Request $request)
{
    $query = Member::query();

    if ($request->has('member_id') && $request->member_id != '') {
        $query->where('show_mem_id', 'like', '%' . $request->member_id . '%');
    }

    $members = $query->orderBy('id', 'desc')->paginate(15);
    $title = "Member List";

    return view('admin.members.index', compact('members', 'title'));
}
    public function active()
    {
        $members = Member::where('status', 1)->paginate(15);
        return view('admin.members.index', [
            'members' => $members,
            'title' => 'Manage Active Members',
        ]);
    }

    public function inactive()
    {
        $members = Member::where('status', 0)->paginate(15);
        return view('admin.members.index', [
            'members' => $members,
            'title' => 'Manage In-Active Members',
        ]);
    }

    public function toggleStatus($id)
    {
        try {
            DB::beginTransaction();
            
            $member = Member::findOrFail($id);
            $newStatus = !$member->status; // flip status
            
            // Update member table
            $member->status = $newStatus;
            $member->save();
            
            // SYNC with member_binary table
            DB::table('member_binary')
                ->where('memid', $member->show_mem_id)
                ->update([
                    'status' => $newStatus ? 1 : 0,
                    'activ' => $newStatus ? 1 : 0,
                ]);
            
            // SYNC with binary_payouts table
            DB::table('binary_payouts')
                ->where('member_id', $member->show_mem_id)
                ->update([
                    'status' => $newStatus ? 1 : 0,
                ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Member status updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update member status: ' . $e->getMessage());
        }
    }

    public function view($show_mem_id)
    {
        $member = Member::where('show_mem_id', $show_mem_id)->first();
        if (!$member) {
            return redirect()->back()->with('error', 'Member not found');
        }

        // Fetch bank details for the member
        $bankDetails = MemberBankDetail::where('member_id', $show_mem_id)->first();

        $dailyIncome = DB::table('member_daily_income')
            ->where('member_id', $show_mem_id)
            ->sum('total_received');

        $directIncome = DB::table('direct_payment_tbl')
            ->where('member_id', $show_mem_id)
            ->sum('total_received');

        $matchingIncome = DB::table('binary_payouts')
            ->where('member_id', $show_mem_id)
            ->sum('payamt');

        $salaryIncome = DB::table('salary_income')
            ->where('member_id', $show_mem_id)
            ->sum('amount');

        $rewardIncome = DB::table('reward_income')
            ->where('member_id', $show_mem_id)
            ->sum('amount');

        return view('admin.members.view', compact(
            'member',
            'bankDetails',
            'dailyIncome',
            'directIncome',
            'matchingIncome',
            'salaryIncome',
            'rewardIncome'
        ));
    }

   public function edit($show_mem_id)
{
    $member = Member::where('show_mem_id', $show_mem_id)->first();
    if (!$member) {
        return redirect()->back()->with('error', 'Member not found');
    }

    $bankDetails = MemberBankDetail::where('member_id', $show_mem_id)->first();
    return view('admin.members.edit', compact('member', 'bankDetails'));
}


public function update(Request $request, $show_mem_id)
{
    $request->validate([
        // Member details validation (all optional now)
        'name' => 'nullable|string|max:255',
        'father_name' => 'nullable|string|max:255',
        'gender' => 'nullable|in:Male,Female,Other',
        'emailid' => 'nullable|email|max:255',
        'mobileno' => 'nullable|string|max:15',
        'dob' => 'nullable|date',
        'pannumber' => 'nullable|string|max:10',
        'address' => 'nullable|string|max:500',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'pincode' => 'nullable|string|max:10',
        'status' => 'nullable|boolean',

        // Bank details validation (all optional)
        'accname' => 'nullable|string|max:255',
        'acctype' => 'nullable|string|max:50',
        'acc_number' => 'nullable|string|max:20',
        'bank_name' => 'nullable|string|max:255',
        'branch' => 'nullable|string|max:255',
        'ifsc_code' => 'nullable|string|max:11',
        'bank_address' => 'nullable|string|max:500',
        'micr' => 'nullable|string|max:9',
        'bank_pannumber' => 'nullable|string|max:10',
        'aadhar_number' => 'nullable|string|max:12',
        'googlepay' => 'nullable|string|max:15',
        'phonepay' => 'nullable|string|max:15',
    ]);

    try {
        DB::beginTransaction();

        // Find member
        $member = Member::where('show_mem_id', $show_mem_id)->first();
        if (!$member) {
            throw new \Exception('Member not found');
        }

        // ✅ Update ONLY provided member fields
        $memberFields = $request->only([
            'name', 'father_name', 'gender', 'emailid', 'mobileno', 'dob', 
            'pannumber', 'address', 'city', 'state', 'pincode', 'status'
        ]);
        $memberFields = array_filter($memberFields, fn($val) => !is_null($val)); // remove null fields

        if (!empty($memberFields)) {
            $member->update($memberFields);
        }

        // ✅ Sync status with other tables ONLY if given
        if ($request->has('status')) {
            DB::table('member_binary')
                ->where('memid', $show_mem_id)
                ->update([
                    'status' => $request->status ? 1 : 0,
                    'activ' => $request->status ? 1 : 0,
                ]);
            DB::table('binary_payouts')
                ->where('member_id', $show_mem_id)
                ->update([
                    'status' => $request->status ? 1 : 0,
                ]);
        }

        // ✅ Bank details: only update if at least one field is filled
        $bankData = $request->only([
            'accname', 'acctype', 'acc_number', 'bank_name', 'branch', 'ifsc_code',
            'bank_address', 'micr', 'bank_pannumber', 'aadhar_number', 'googlepay', 'phonepay'
        ]);
        $bankData = array_filter($bankData, fn($val) => !is_null($val)); // remove nulls

        if (!empty($bankData)) {
            MemberBankDetail::updateOrCreate(
                ['member_id' => $show_mem_id],
                $bankData
            );
        }

        DB::commit();

        return redirect()->route('admin.member.view', $show_mem_id)
            ->with('success', 'Member details updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update member details. Error: ' . $e->getMessage());
    }
}


    public function showTopupPinForm()
    {
        $packages = DB::table('manage_pv')
            ->where('pintype', 'topup')
            ->where('status', 1)
            ->get();

        return view('admin.topup_pin_generate', compact('packages'));
    }

    public function getMemberName($member_id)
    {
        $member = Member::where('show_mem_id', $member_id)->first();

        if ($member) {
            return response()->json([
                'success' => true,
                'name' => $member->name,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Member not found',
            ]);
        }
    }
public function byJoinDate()
{
    $members = Member::orderByDesc('joindate')->paginate(15);

    return view('admin.members.index', [
        'members' => $members,
        'title' => 'Members by Registration Date (Latest First)',
    ]);
}
    public function loginAsMember($show_mem_id)
    {
        // $member = Member::where('show_mem_id', $show_mem_id)->where('status', 1)->first();
        $member = Member::where('show_mem_id', $show_mem_id)->first();


        if (!$member) {
            return redirect()->back()->with('error', 'Member not found or inactive');
        }

        // Logout any currently logged-in member to prevent session issues
        Auth::guard('member')->logout();

        // Login as this member
        Auth::guard('member')->login($member);

        return redirect()->route('member.dashboard');
    }


}