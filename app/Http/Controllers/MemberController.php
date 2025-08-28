<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MemberBinary;
use App\Models\PaymentWithdraw;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use App\Models\MemberBankDetail;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Helpers\WalletHelper;
use App\Helpers\PaymentLogHelper;
use App\Helpers\placementHelper;


class MemberController extends Controller
{
    public function manageMembers(Request $request)
    {
        $msg = '';
        $keywords = $request->input('keyword', '');

        if ($request->isMethod('post')) {
            $operation = $request->input('opr');
            $recId = $request->input('RecID');

            switch ($operation) {
                case 'Deactivate':
                    $this->deactivateMember($recId);
                    break;

                case 'status':
                    $this->updateStatus($recId, $request);
                    break;

                case 'showtoday':
                    $this->updateShowToday($recId, $request);
                    $msg = "Record Updated Successfully.";
                    break;

                case 'chvalue':
                    $this->changeMemberValue($recId, $request);
                    $msg = "Member updated successfully.";
                    break;
            }
        }

        $members = Member::where('status', 1)
            ->where('activate_date', '!=', 0)
            ->where('payment', '!=', 0)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'like', "%{$keywords}%")
                    ->orWhere('mem_id', 'like', "%{$keywords}%");
            });

        return view('admin.members.manage_members', compact('members', 'msg', 'keywords'));
    }


    public function dashboard()
    {
        $member = Auth::guard('member')->user(); // Get the logged-in member
        //dd($member);

        $networkStats = $this->getNetworkStats($member->show_mem_id);
        // Individual incomes
        $dailyIncome = DB::table('member_daily_income')->where('member_id', $member->show_mem_id)->sum('amount') ?? 0;
        $directIncome = DB::table('direct_payment_tbl')->where('member_id', $member->show_mem_id)->sum('amount') ?? 0;
        $matchingIncome = DB::table('binary_payouts')->where('member_id', $member->show_mem_id)->where('status', 1)->sum('payamt') ?? 0;
        $salaryIncome = DB::table('salary_income')->where('member_id', $member->show_mem_id)->sum('amount') ?? 0;
        $rewardIncome = DB::table('reward_income')->where('member_id', $member->show_mem_id)->sum('amount') ?? 0;
        $wallet = WalletHelper::getMemberEarnings($member->show_mem_id);
        //  dd($wallet);
        // Total earnings
        $totalEarnings = $dailyIncome + $directIncome + $matchingIncome + $salaryIncome + $rewardIncome;

        // Withdrawn amount
        $totalWithdrawn = DB::table('withdraw_requests')
            ->where('member_id', $member->show_mem_id)
            ->where('status', 'approved')
            ->sum('amount') ?? 0;

        // Balance = earnings - withdrawn
        $balance = $totalEarnings - $totalWithdrawn;

        return view('member.dashboard', compact(
            'member',
            'networkStats',
            'dailyIncome',
            'directIncome',
            'matchingIncome',
            'salaryIncome',
            'rewardIncome',
            'totalEarnings',
            'totalWithdrawn',
            'balance',
            'wallet'
        ));
    }


    //

    private function getNetworkStats($memId)
    {
        // Get current member's binary record
        $currentMember = DB::table('member_binary')->where('memid', $memId)->first();

        if (!$currentMember) {
            return $this->getEmptyStats();
        }

        // Dynamic left network count
        $leftCount = 0;
        $leftParent = $currentMember->left;
        if (!empty($currentMember->left)) {
            $ltot = '';
            placementHelper::getLeftMembersRecursive($currentMember->left, $ltot);

            $ltot = $leftParent . $ltot;
            $ltot = ltrim($ltot, ',');
            // Append the immediate left child itself
            $leftCount = count(explode(',', $ltot));
        }

        // Dynamic right network count
        $rightCount = 0;
        $rightParent = $currentMember->right;
        if (!empty($currentMember->right)) {
            $rtot = '';
            placementHelper::getRightMembersRecursive($currentMember->right, $rtot);

            $rtot = $rightParent . $rtot;
            $rtot = ltrim($rtot, ',');
            // Append the immediate left child itself
            $rightCount = count(explode(',', $rtot));
        }



        $allCount = $leftCount + $rightCount;

        // Compose stats
        $stats = [
            'my_network'      => $allCount,
            'direct_members'  => $this->getDirectMembers($memId),
            'active_direct'   => $this->getActiveDirectMembers($memId),
            'left_network'    => $leftCount,
            'right_network'   => $rightCount,
            'active_left'     => $this->getActiveLeftMembers($memId),
            'active_right'    => $this->getActiveRightMembers($memId)
        ];

        return $stats;
    }

    //this fn replaced for showing wrong count in dashboard 


    private function getTotalNetworkSize($memId)
    {
        $currentMember = DB::table('member_binary')->where('memid', $memId)->first();
        return ($currentMember->tot_left ?? 0) + ($currentMember->tot_right ?? 0);
    }

    private function getDirectMembers($memId)
    {
        return DB::table('member_binary')
            ->where('sponsor_id', $memId)
            ->count();
    }

    private function getActiveDirectMembers($memId)
    {
        return DB::table('member_binary')
            ->where('sponsor_id', $memId)
            ->where('activ', 1) // Assuming 1 means active
            ->count();
    }

    private function getActiveLeftMembers($memId)
    {
        $currentMember = DB::table('member_binary')->where('memid', $memId)->first();
        if (!$currentMember || !$currentMember->left) return 0;

        return $this->getActiveCountInSubtree($currentMember->left);
    }

    private function getActiveRightMembers($memId)
    {
        $currentMember = DB::table('member_binary')->where('memid', $memId)->first();
        if (!$currentMember || !$currentMember->right) return 0;

        return $this->getActiveCountInSubtree($currentMember->right);
    }

    private function getActiveCountInSubtree($rootId)
    {
        if (!$rootId || $rootId <= 0) return 0;

        $activeCount = 0;
        $queue = [$rootId];
        $processed = [];

        while (!empty($queue) && count($processed) < 1000) { // Limit to prevent infinite loops
            $currentId = array_shift($queue);

            if (in_array($currentId, $processed)) continue;
            $processed[] = $currentId;

            $member = DB::table('member_binary')->where('memid', $currentId)->first();

            if ($member) {
                if ($member->activ == 1) {
                    $activeCount++;
                }

                if ($member->left && $member->left > 0 && !in_array($member->left, $processed)) {
                    $queue[] = $member->left;
                }

                if ($member->right && $member->right > 0 && !in_array($member->right, $processed)) {
                    $queue[] = $member->right;
                }
            }
        }

        return $activeCount;
    }

    private function getEmptyStats()
    {
        return [
            'my_network' => 0,
            'direct_members' => 0,
            'active_direct' => 0,
            'left_network' => 0,
            'right_network' => 0,
            'active_left' => 0,
            'active_right' => 0
        ];
    }



    //

    public function showProfile()
    {
        $member = Auth::guard('member')->user()->load('member_bank_details');

        return view('member.profile', compact('member'));
    }

    public function paymentHistory()
    {
        $memid = auth()->user()->show_mem_id;


        $payments = DB::table('payment_logs')  // Adjust table name if different
            ->where('member_id', $memid)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.payment_history', compact('payments'));
    }
    public function editBasic()
    {
        $member = Auth::guard('member')->user();
        return view('member.edit', compact('member'));
    }

    public function updateBasic(Request $request)
    {
        $member = Auth::guard('member')->user();

        $validated = $request->validate([
            'gender' => 'required',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $member->gender = $validated['gender'];
        $member->mobileno = $validated['mobile'];
        $member->address = $validated['address'] ?? '';
        $member->city = $validated['city'] ?? '';
        $member->country = $validated['country'] ?? '';

        $member->save();

        return redirect()->route('member.profile')->with('success', 'Profile updated successfully.');
    }


    public function directAssociates()
    {
        $myShowMemId = auth()->guard('member')->user()->show_mem_id;

        $directAssociates = Member::where('sponsorid', $myShowMemId)->paginate(20);

        return view('member.direct_associates', compact('directAssociates'));
    }
    public function associateNetwork()
    {
        $currentUser = auth()->guard('member')->user();
        $rootMemId = $currentUser->show_mem_id;

        $visited = [];
        $queue = [$rootMemId];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $children = DB::table('member_binary')
                ->where('parent', $current)
                ->pluck('memid')
                ->toArray();

            foreach ($children as $child) {
                if (!in_array($child, $visited)) {
                    $visited[] = $child;
                    $queue[] = $child;
                }
            }
        }

        $associates = Member::whereIn('show_mem_id', $visited)->paginate(20);

        return view('member.associate_network', compact('associates', 'currentUser'));
    }


    //tree view - my
    public function ajaxTreeNode($memid)
    {
        $tree = $this->buildTree($memid);
        return view('member.tree_node', ['node' => $tree, 'level' => 0]);
    }



    public function treeView(Request $request)
    {
        // Get root member ID from request or use authenticated user's ID
        $rootMemid = $request->get('root') ?: auth()->guard('member')->user()->show_mem_id;
        $tree = $this->buildTree($rootMemid);

        return view('member.tree', compact('tree'));
    }

    // private function buildTree($memid, $depth = 0, $maxDepth = 5)
    // {
    //     if ($depth > $maxDepth) {
    //         return null;
    //     }

    //     $memberBinary = MemberBinary::with(['member', 'leftChild.member', 'rightChild.member'])
    //         ->where('memid', $memid)
    //         ->first();

    //     if (!$memberBinary || !$memberBinary->member) {
    //         return null;
    //     }

    //     $payout = DB::table('binary_payouts')
    //         ->where('member_id', $memid)
    //         ->latest('created_at')
    //         ->first();

    //     return [
    //         'memid' => $memid,
    //         'name' => $memberBinary->member->name,
    //         'status' => $memberBinary->member->status == 1,
    //         'left' => $memberBinary->leftChild
    //             ? $this->buildTree($memberBinary->leftChild->memid, $depth + 1, $maxDepth)
    //             : null,
    //         'right' => $memberBinary->rightChild
    //             ? $this->buildTree($memberBinary->rightChild->memid, $depth + 1, $maxDepth)
    //             : null,
    //         'left_business' => number_format($payout->totleft_amount ?? 0, 2),
    //         'right_business' => number_format($payout->totright_amount ?? 0, 2),
    //     ];
    // }
    private function buildTree($memid, $depth = 0, $maxDepth = 5)
    {
        if ($depth > $maxDepth) {
            return null;
        }

        $memberBinary = MemberBinary::with(['member', 'leftChild.member', 'rightChild.member'])
            ->where('memid', $memid)
            ->first();

        if (!$memberBinary || !$memberBinary->member) {
            return null;
        }

        // ✅ FIXED: Use recursive tree calculation for entire leg volume
        $leftBusiness = 0;
        if ($memberBinary->left) {
            $leftBusiness = $this->_sumPlacementTreeBusiness($memberBinary->left);
        }

        $rightBusiness = 0;
        if ($memberBinary->right) {
            $rightBusiness = $this->_sumPlacementTreeBusiness($memberBinary->right);
        }

        return [
            'memid' => $memid,
            'name' => $memberBinary->member->name,
            'status' => $memberBinary->member->status == 1,
            'left' => $memberBinary->leftChild
                ? $this->buildTree($memberBinary->leftChild->memid, $depth + 1, $maxDepth)
                : null,
            'right' => $memberBinary->rightChild
                ? $this->buildTree($memberBinary->rightChild->memid, $depth + 1, $maxDepth)
                : null,
            'left_business' => number_format($leftBusiness, 2),
            'right_business' => number_format($rightBusiness, 2),
        ];
    }


    /**
     * Calculates total business volume for a downline leg by traversing the PLACEMENT tree.
     * This is a helper for the getMemberDetails tooltip.
     *
     * @param string|null $startNodeMemid The member ID of the direct child at the top of the leg.
     * @return float The total business volume.
     */
    // private function _sumPlacementTreeBusiness($startNodeMemid)
    // {
    //     if (empty($startNodeMemid) || $startNodeMemid == '0') {
    //         return 0;
    //     }

    //     $total = 0;
    //     $stack = [$startNodeMemid];
    //     $visited = [];

    //     while (!empty($stack)) {
    //         $currentMemid = array_pop($stack);

    //         if (in_array($currentMemid, $visited)) continue;
    //         $visited[] = $currentMemid;

    //         $node = DB::table('member_binary')->where('memid', $currentMemid)->first();
    //         if (!$node) continue;

    //         // Sum the business amount if the node is active.
    //         if ($node->status == 1) {
    //             $total += (float) $node->payamount;
    //         }

    //         // Add the node's direct children to the stack to continue traversal.
    //         if (!empty($node->left) && $node->left != '0') {
    //             $stack[] = $node->left;
    //         }
    //         if (!empty($node->right) && $node->right != '0') {
    //             $stack[] = $node->right;
    //         }
    //     }

    //     return $total;
    // }
    private function _sumPlacementTreeBusiness($startNodeMemid)
    {
        if (empty($startNodeMemid) || $startNodeMemid == '0') {
            return 0;
        }

        $total = 0;
        $stack = [$startNodeMemid];
        $visited = [];

        while (!empty($stack)) {
            $currentMemid = array_pop($stack);

            if (in_array($currentMemid, $visited)) continue;
            $visited[] = $currentMemid;

            $node = DB::table('member_binary')->where('memid', $currentMemid)->first();
            if (!$node) continue;

            // ✅ FIXED: Use totpv for cumulative volume calculation
            if ($node->status == 1) {
                $total += (float) $node->totpv; // ← Use totpv instead of payamount
            }

            // Add children to stack for recursive traversal
            if (!empty($node->left) && $node->left != '0') {
                $stack[] = $node->left;
            }
            if (!empty($node->right) && $node->right != '0') {
                $stack[] = $node->right;
            }
        }

        return $total;
    }



    public function getMemberDetails($memid)
    {
        $member = Member::where('show_mem_id', $memid)->first();
        $binary = MemberBinary::with(['sponsor', 'upline'])
            ->where('memid', $memid)
            ->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        // Calculate real-time business volume from the placement tree
        $leftBusiness = 0;
        $rightBusiness = 0;
        if ($binary) {
            $leftBusiness = $this->_sumPlacementTreeBusiness($binary->left);
            $rightBusiness = $this->_sumPlacementTreeBusiness($binary->right);
        }

        // Get network stats
        $stats = $this->getNetworkStats($memid);
        $joinDate = $member->joindate ? date('d-m-Y', strtotime($member->joindate)) : 'N/A';
        $activationDate = $member->activate_date ? date('d-m-Y', $member->activate_date) : 'N/A';

        $response = [
            'name' => $member->name,
            'memid' => $member->show_mem_id,
            'status' => $member->status == 1 ? 'Active' : 'Inactive',
            'sponsor' => $binary && $binary->sponsor
                ? $binary->sponsor->name . ' (' . $binary->sponsor->show_mem_id . ')'
                : 'N/A',
            'upline' => $binary && $binary->upline
                ? $binary->upline->name . ' (' . $binary->upline->show_mem_id . ')'
                : 'N/A',
            'join_date' => $joinDate,
            'joining_packag' => $binary->totpv, // ✅ Use totpv for joining package
            'activation_date' => $activationDate,
            'active_direct' => $stats['active_direct'],
            'inactive_direct' => $stats['direct_members'] - $stats['active_direct'],
            'active_network_left' => $stats['active_left'],
            'active_network_right' => $stats['active_right'],
            'inactive_network_left' => ($stats['left_network'] - $stats['active_left']),
            'inactive_network_right' => ($stats['right_network'] - $stats['active_right']),
            'total_team' => $stats['my_network'],
            'left_business' => number_format($leftBusiness, 2),
            'right_business' => number_format($rightBusiness, 2),
        ];

        return response()->json($response);
    }


    public function leftNetwork()
    {
        return $this->getSideNetwork('left');
    }

    public function rightNetwork()
    {
        return $this->getSideNetwork('right');
    }

    private function getSideNetwork($side)
    {
        $user = auth()->guard('member')->user();
        if (!$user) abort(403, 'Unauthorized');

        // Fix: Use show_mem_id from member table instead of memid
        $userId = $user->show_mem_id;
        $result = $this->fetchDownlines($userId, $side);

        return view('member.' . $side . '_network', compact('result'));
    }

    private function fetchDownlines($parentId, $side)
    {
        // Fix: Join both tables to get complete data in one query
        $members = DB::table('member_binary as mb')
            ->join('member as m', 'mb.memid', '=', 'm.show_mem_id')
            ->where('mb.parent', $parentId)
            ->where('mb.position', $side)
            ->select(
                'mb.*',
                'm.name',
                'm.show_mem_id as member_id',
                'm.payment'
            )
            ->get();

        $result = [];

        foreach ($members as $row) {
            // Get direct children for "Down To" field
            $directChildren = DB::table('member_binary as mb')
                ->join('member as m', 'mb.memid', '=', 'm.show_mem_id')
                ->where('mb.parent', $row->memid)
                ->pluck('m.name')
                ->toArray();

            $result[] = [
                'memid' => $row->member_id, // Use show_mem_id for display
                'name' => $row->name,
                'down_to' => implode(', ', $directChildren) ?: 'N/A',
                'position' => $row->position,
                'status' => $row->status == 1 ? 'Active' : 'Inactive',
                'joindate' => $row->joindate ? date('d-m-Y', $row->joindate) : 'N/A',
                'package' => $row->payment ?? 0,
            ];

            // Recursively add children
            $children = $this->fetchDownlines($row->memid, $side);
            $result = array_merge($result, $children);
        }

        return $result;
    }
    //


    //start 2

    // public function showRegisterForm()
    //     {
    //         return view('member.register');
    //     }

    public function showRegisterForm(Request $request)
    {
        $prefillSponsorId = $request->query('sponsor'); // capture from ?sponsor=
        $prefillPosition = $request->query('position'); // also capture position
        $member_id = $request->query('memberid');
        return view('member.register', compact('prefillSponsorId', 'prefillPosition', 'member_id'));
    }


    public function getSponsorName(Request $request)
    {
        $sponsor = Member::where('show_mem_id', $request->sponsor_show_id)->first();

        if ($sponsor) {
            return response()->json(['success' => true, 'name' => $sponsor->name]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function register(Request $request)
    {
        $request->validate([
            'sponsor_show_id' => 'required|exists:member,show_mem_id',
            'position' => 'required|in:left,right',
            'name' => 'required|string|max:255',
            'mobileno' => 'required|digits_between:8,15',
            'emailid' => 'required|email|max:255',

            'password' => 'required|string|min:6|confirmed',
        ]);

        $sponsor = Member::where('show_mem_id', $request->sponsor_show_id)->first();
        do {
            $randomNumber = random_int(10000, 99999); // 5-digit random
            $generatedId = 'JP' . $randomNumber;
        } while (Member::where('show_mem_id', $generatedId)->exists());

        if ($request->filled('member_id')) {
            $referrerBinary = DB::table('member_binary')
                ->where('memid', $request->member_id)
                ->first();

            if (!$referrerBinary) {
                $this->createInitialBinaryRecord($request->member_id);
                $referrerBinary = DB::table('member_binary')
                    ->where('memid', $request->member_id)
                    ->first();
            }

            // Insert new member into member_binary table
            DB::table('member_binary')->insert([
                'memid' => $generatedId,
                'position' => $request->position,
                'tot_left' => 0,
                'tot_right' => 0,
                'parent' => $request->member_id,
                'sponslev' => 1,
                'uplineid' => $referrerBinary->memid,
                'sponsor_id' => $sponsor->show_mem_id,
                'joindate' => now()->timestamp,
                'left' => 0,
                'right' => 0,
                'status' => 0,
                'activ' => 0,
                'activatedate' => 0,
                'totpv' => 0,
                'payamount' => 0,
                'spnslevel' => 1,
                'spngivid' => $referrerBinary->memid,
                'm_t' => 0,
                'closedate' => 0,
                'totseldbv' => 0,
                'capping' => 0
            ]);

            // Update parent pointer (left/right)
            DB::table('member_binary')
                ->where('memid', $request->member_id)
                ->update([
                    $request->position => $generatedId
                ]);
            $placementResult = [
                'parent_memid' => $request->member_id,
            ];
        } else {
            $placementResult = $this->placeMemberInBinaryTree(
                $generatedId,
                $sponsor->show_mem_id,
                $request->position
            );
        }


        // Create member record with parent info
        $member = new Member();
        $member->show_mem_id = $generatedId;
        $member->position = $request->position;
        $member->name = $request->name;
        $member->mobileno = $request->mobileno;
        $member->emailid = $request->emailid;
        $member->password = Hash::make($request->password);
        $member->sponsorid = $sponsor->show_mem_id;
        $member->parentid = $placementResult['parent_memid']; // Store parent's memid
        $member->joindate = now();

        // Set defaults
        $member->tot_ref = 0;
        $member->all_ref = 0;
        $member->all_pool = 0;
        $member->mypool = 0;
        $member->paid = 'N';
        $member->free = 'Y';
        $member->packagejoin = '';
        $member->father_name = '';
        $member->gender = '';
        $member->profile_pic = '';
        $member->pancardpic = '';
        $member->aadharcardpic = '';
        $member->aadharfinal = '';
        $member->dob = '';
        $member->address = '';
        $member->city = '';
        $member->state = '';
        $member->country = '';
        $member->pincode = '';
        $member->pannumber = '';
        $member->payment = 0;
        $member->active = '';
        $member->inactive = '';
        $member->suspend = '';
        $member->status = 0;
        $member->lastlogin = 0;
        $member->activate_date = 0;
        $member->monthly_income = 'N';
        $member->roi_income = 0;
        $member->totwithdraw = 0;
        $member->tot_cpping_amt = 0;
        $member->tot_income_amt = 0;

        $member->save();


        DB::table('member')
            ->where('id', $sponsor->id)
            ->update([
                'tot_cpping_amt' => ($sponsor->payment * 3)
            ]);

        //     Mail::to($member->emailid)->send(new WelcomeEmail(
        //     $member->emailid,
        //     $member->name,
        //     $request->password 
        // ));
        Mail::to($member->emailid)->send(new WelcomeEmail(
            $member->show_mem_id,  // ← send this instead
            $member->name,
            $request->password
        ));

        return redirect()->route('member.register.success')->with([
            'show_mem_id' => $generatedId,
            'name' => $member->name,
            'emailid' => $member->emailid,
        ]);
    }

    // Binary tree placement logic - Fixed to handle edge cases
    private function placeMemberInBinaryTree($newMemberID, $sponsorID, $preferredPosition)
    {
        // Find sponsor's binary record
        $sponsorBinary = DB::table('member_binary')
            ->where('memid', $sponsorID)
            ->first();

        if (!$sponsorBinary) {
            // If sponsor doesn't have binary record, create one first
            $this->createInitialBinaryRecord($sponsorID);
            $sponsorBinary = DB::table('member_binary')
                ->where('memid', $sponsorID)
                ->first();
        }

        // Find the actual placement position
        $placementResult = $this->findAvailablePosition($sponsorID, $preferredPosition);

        // Get parent's memid for the member table
        $parentBinary = DB::table('member_binary')
            ->where('id', $placementResult['parent_db_id'])
            ->first();

        // Create binary record for new member
        DB::table('member_binary')->insert([
            'memid' => $newMemberID,
            'position' => $preferredPosition,
            'tot_left' => 0,
            'tot_right' => 0,
            'parent' => $parentBinary->memid, // Store parent's memid, not table ID
            'sponslev' => $placementResult['level'],
            'uplineid' => $sponsorBinary->memid, // Store sponsor's memid, not table ID
            'sponsor_id' => $sponsorBinary->memid, // Store sponsor's memid, not table ID
            'joindate' => now()->timestamp,
            'left' => 0,        // Will store memid of left child
            'right' => 0,       // Will store memid of right child
            'status' => 0, // Initially inactive
            'activ' => 0,
            'activatedate' => 0,
            'totpv' => 0,
            'payamount' => 0,
            'spnslevel' => 1,
            'spngivid' => $sponsorBinary->memid, // Store sponsor's memid, not table ID
            'm_t' => 0,
            'closedate' => 0,
            'totseldbv' => 0,
            'capping' => 0
        ]);

        // Update parent's left/right pointer with child's memid
        $updateField = $placementResult['position'] == 'left' ? 'left' : 'right';

        DB::table('member_binary')
            ->where('id', $placementResult['parent_db_id'])
            ->update([
                $updateField => $newMemberID  // Store the memid (show_mem_id) not the binary table ID
            ]);

        // Update counts up the tree
        $this->updateTreeCounts($placementResult['parent_db_id'], $placementResult['position']);

        // Return parent info for member table
        return [
            'parent_memid' => $parentBinary->memid,
            'level' => $placementResult['level']
        ];
    }

    // Fixed findAvailablePosition with better error handling and fallback strategies
    private function findAvailablePosition($sponsorMemID, $preferredPosition)
    {
        // Try preferred position first with depth-first search
        $result = $this->findPositionDepthFirst($sponsorMemID, $preferredPosition);
        if ($result) {
            return $result;
        }

        // If preferred position failed, try opposite position
        $oppositePosition = $preferredPosition == 'left' ? 'right' : 'left';
        $result = $this->findPositionDepthFirst($sponsorMemID, $oppositePosition);
        if ($result) {
            return $result;
        }

        // If both depth-first failed, try breadth-first search for any available position
        $result = $this->findPositionBreadthFirst($sponsorMemID);
        if ($result) {
            return $result;
        }

        // Final fallback - this should rarely happen
        throw new Exception("Unable to find placement position after trying all strategies");
    }

    // Depth-first search for available position with loop protection
    private function findPositionDepthFirst($sponsorMemID, $position, $maxDepth = 1000)
    {
        $currentParent = DB::table('member_binary')
            ->where('memid', $sponsorMemID)
            ->first();

        if (!$currentParent) {
            return null;
        }

        $level = 1;
        $visitedNodes = []; // Track visited nodes to prevent infinite loops

        while ($currentParent && $level <= $maxDepth) {
            // Prevent infinite loops
            if (in_array($currentParent->memid, $visitedNodes)) {
                break;
            }
            $visitedNodes[] = $currentParent->memid;

            // Check if preferred position is available
            $childField = $position == 'left' ? 'left' : 'right';
            $childMemId = $currentParent->$childField;

            if (empty($childMemId) || $childMemId == 0 || $childMemId == '0') {
                return [
                    'parent_id' => $currentParent->memid,
                    'parent_db_id' => $currentParent->id,
                    'position' => $position,
                    'level' => $level
                ];
            }

            // Move to child node - validate it exists first
            $childNode = DB::table('member_binary')
                ->where('memid', $childMemId)
                ->first();

            if (!$childNode) {
                // Child reference is broken, this position is actually available
                return [
                    'parent_id' => $currentParent->memid,
                    'parent_db_id' => $currentParent->id,
                    'position' => $position,
                    'level' => $level
                ];
            }

            $currentParent = $childNode;
            $level++;
        }

        return null; // No position found in this path
    }

    // Breadth-first search as fallback - finds the first available position at any level
    private function findPositionBreadthFirst($sponsorMemID, $maxNodes = 100)
    {
        $queue = [];
        $visited = [];

        // Start with sponsor
        $sponsor = DB::table('member_binary')
            ->where('memid', $sponsorMemID)
            ->first();

        if (!$sponsor) {
            return null;
        }

        $queue[] = ['node' => $sponsor, 'level' => 1];
        $processedNodes = 0;

        while (!empty($queue) && $processedNodes < $maxNodes) {
            $current = array_shift($queue);
            $currentNode = $current['node'];
            $currentLevel = $current['level'];

            $processedNodes++;

            // Prevent infinite loops
            if (in_array($currentNode->memid, $visited)) {
                continue;
            }
            $visited[] = $currentNode->memid;

            // Check left position
            if (empty($currentNode->left) || $currentNode->left == 0 || $currentNode->left == '0') {
                return [
                    'parent_id' => $currentNode->memid,
                    'parent_db_id' => $currentNode->id,
                    'position' => 'left',
                    'level' => $currentLevel
                ];
            }

            // Check right position
            if (empty($currentNode->right) || $currentNode->right == 0 || $currentNode->right == '0') {
                return [
                    'parent_id' => $currentNode->memid,
                    'parent_db_id' => $currentNode->id,
                    'position' => 'right',
                    'level' => $currentLevel
                ];
            }

            // Add children to queue if they exist and are valid
            if (!empty($currentNode->left) && $currentNode->left != 0) {
                $leftChild = DB::table('member_binary')
                    ->where('memid', $currentNode->left)
                    ->first();
                if ($leftChild && !in_array($leftChild->memid, $visited)) {
                    $queue[] = ['node' => $leftChild, 'level' => $currentLevel + 1];
                }
            }

            if (!empty($currentNode->right) && $currentNode->right != 0) {
                $rightChild = DB::table('member_binary')
                    ->where('memid', $currentNode->right)
                    ->first();
                if ($rightChild && !in_array($rightChild->memid, $visited)) {
                    $queue[] = ['node' => $rightChild, 'level' => $currentLevel + 1];
                }
            }
        }

        return null; // No position found
    }

    // Create initial binary record for sponsor if doesn't exist
    private function createInitialBinaryRecord($memberID)
    {
        $member = DB::table('member')->where('show_mem_id', $memberID)->first();

        DB::table('member_binary')->insert([
            'memid' => $memberID,
            'position' => 'root',
            'tot_left' => 0,
            'tot_right' => 0,
            'parent' => 0, // Root has no parent
            'sponslev' => 0,
            'uplineid' => 0, // Root has no upline
            'sponsor_id' => 0, // Root has no sponsor
            'joindate' => $member->joindate ?? now()->timestamp,
            'left' => 0,        // Will store memid of left child
            'right' => 0,       // Will store memid of right child
            'status' => $member->status ?? 0,
            'activ' => 0,
            'activatedate' => $member->activate_date ?? 0,
            'totpv' => 0,
            'payamount' => $member->payment ?? 0,
            'spnslevel' => 0,
            'spngivid' => 0, // Root has no sponsor
            'm_t' => 0,
            'closedate' => 0,
            'totseldbv' => 0,
            'capping' => 0
        ]);
    }

    // Update tree counts recursively - Fixed with better error handling
    private function updateTreeCounts($parentId, $position)
    {
        $countField = $position == 'left' ? 'tot_left' : 'tot_right';
        $visited = []; // Prevent infinite loops
        $maxIterations = 50; // Safety limit
        $iterations = 0;

        while ($parentId != 0 && $iterations < $maxIterations) {
            $iterations++;

            // Prevent infinite loops
            if (in_array($parentId, $visited)) {
                break;
            }
            $visited[] = $parentId;

            // Update count
            DB::table('member_binary')
                ->where('id', $parentId)
                ->increment($countField);

            // Get parent record
            $parent = DB::table('member_binary')
                ->where('id', $parentId)
                ->first();

            if (!$parent || $parent->parent == 0 || empty($parent->parent)) {
                break;
            }

            // Find next parent by memid
            $nextParent = DB::table('member_binary')
                ->where('memid', $parent->parent)
                ->first();

            if (!$nextParent) {
                break;
            }

            $parentId = $nextParent->id;
        }
    }

    public function memberWithdrawals(Request $request, $memid)
    {
        // Get member details
        $member = DB::table('member')->where('show_mem_id', $memid)->first();

        if (!$member) {
            return redirect()->back()->with('error', 'Member not found');
        }

        // Get search parameters
        $search = $request->get('search');
        $status = $request->get('status');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        // Build query with search conditions
        $query = DB::table('payment_withdraw')
            ->where(function ($q) use ($member, $memid) {
                $q->where('member_id', $member->id)
                    ->orWhere('member_id', $memid);
            });

        // Apply search filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', '%' . $search . '%')
                    ->orWhere('accountname', 'like', '%' . $search . '%')
                    ->orWhere('accountno', 'like', '%' . $search . '%')
                    ->orWhere('ifsccode', 'like', '%' . $search . '%')
                    ->orWhere('mobileno', 'like', '%' . $search . '%')
                    ->orWhere('cur_withdraw_amt', 'like', '%' . $search . '%')
                    ->orWhere('final_amt', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        // Filter by date range
        if ($date_from) {
            $from_timestamp = strtotime($date_from . ' 00:00:00');
            $query->where('request_date', '>=', $from_timestamp);
        }

        if ($date_to) {
            $to_timestamp = strtotime($date_to . ' 23:59:59');
            $query->where('request_date', '<=', $to_timestamp);
        }

        // Get paginated results
        $withdrawals = $query
            ->orderBy('request_date', 'desc')
            ->paginate(10)
            ->appends($request->query());

        return view('member.withdrawls', compact('member', 'withdrawals', 'search', 'status', 'date_from', 'date_to'));
    }

    //start3

    /**
     * Display member daily income with pagination
     */
    // public function memberDailyIncome(Request $request)
    // {
    //     // Logged-in member ID
    //     $memid = auth()->user()->show_mem_id;

    //     // Get member record
    //     $member = DB::table('member')->where('show_mem_id', $memid)->first();

    //     if (!$member) {
    //         return abort(404, 'Member not found');
    //     }

    //     // Summary table: member_daily_income
    //     $dailyIncome = DB::table('member_daily_income')
    //         ->where('member_id', $memid)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     // Breakdown table: member_income_history
    //     $incomeHistory = DB::table('member_income_history')
    //         ->where('member_id', $memid)
    //         ->orderBy('date', 'desc')
    //         ->paginate(10);

    //     return view('member.daily_income', compact('member', 'dailyIncome', 'incomeHistory', 'memid'));
    // }
public function memberDailyIncome(Request $request)
{
    // Logged-in member ID
    $memid = auth()->user()->show_mem_id;

    // Get member record
    $member = DB::table('member')->where('show_mem_id', $memid)->first();

    if (!$member) {
        return abort(404, 'Member not found');
    }

    // Summary table: member_daily_income
    $dailyIncome = DB::table('member_daily_income')
        ->where('member_id', $memid)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // ✅ Get ALL income history (not paginated) for proper breakdown display
    $allIncomeHistory = DB::table('member_income_history')
        ->where('member_id', $memid)
        ->orderBy('date', 'desc')
        ->get();

    // ✅ Paginated income history for main display
    $incomeHistory = DB::table('member_income_history')
        ->where('member_id', $memid)
        ->orderBy('date', 'desc')
        ->paginate(10);

    // ✅ Calculate accurate totals
    $totalIncomeEarned = DB::table('member_income_history')
        ->where('member_id', $memid)
        ->sum('amount');

    $totalInvestment = DB::table('member_daily_income')
        ->where('member_id', $memid)
        ->sum('amount');

    return view('member.daily_income', compact(
        'member', 
        'dailyIncome', 
        'incomeHistory', 
        'allIncomeHistory',  // ✅ Add this for proper breakdown
        'memid',
        'totalIncomeEarned',
        'totalInvestment'
    ));
}




    /**
     * Display member direct payment with pagination
     */
    public function memberDirectPayment()
    {
        // Always use the logged-in member's show_mem_id
        $memid = auth()->user()->show_mem_id;

        // Get member using show_mem_id
        $member = DB::table('member')->where('show_mem_id', $memid)->first();

        if (!$member) {
            abort(404, 'Member not found');
        }

        // Fetch direct payments using show_mem_id as sponsor reference
        $directPayments = DB::table('direct_payment_tbl')
            ->where('member_id', $memid)
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        // Calculate total received so far
        $totalReceived = DB::table('direct_payment_tbl')
            ->where('member_id', $memid)
            ->sum('total_received');

        return view('member.direct_payment', compact('member', 'directPayments', 'memid', 'totalReceived'));
    }


    public function viewHistory($memberId)
    {
        $memid = auth()->user()->show_mem_id;

        // Get sponsor user
        $member = DB::table('member')->where('show_mem_id', $memid)->first();

        // Get referred member info
        $referredMember = DB::table('member')->where('show_mem_id', $memberId)->first();

        // Income history
        $incomeHistory = DB::table('sponsor_daily_income')
            ->where('from_id', $memberId)
            ->where('member_id', $memid)
            ->orderBy('created_at', 'asc')
            ->get();
        $totalReceived = $incomeHistory->sum('amount');

        return view('member.direct_history', compact('memberId', 'incomeHistory', 'referredMember', 'totalReceived'));
    }


    public function memberMatchingIncome(Request $request)
    {
        // Get logged-in member's ID
        $memid = auth()->user()->show_mem_id;

        // Fetch member record
        $member = Member::where('show_mem_id', $memid)->first();

        if (!$member) {
            abort(404, 'Member not found.');
        }

        // Build the query
        $query = DB::table('binary_payouts as b')
            ->join('member as m', 'b.member_id', '=', 'm.show_mem_id')
            ->select(
                'b.*',
                'm.name',
                'm.show_mem_id'
            )
            ->where('b.member_id', $memid)
            ->orderByDesc('b.confirm_date');

        // Optional date filter
        if ($request->filled('date')) {
            $timestamp = Carbon::parse($request->date)->startOfDay()->timestamp;
            $query->whereBetween('b.confirm_date', [$timestamp, $timestamp + 86400]);
        }

        $matchingIncome = $query->paginate(20);

        return view('member.matching_income', compact('member', 'memid', 'matchingIncome'));
    }



    // Add this method if you want to display summary of all incomes
    public function memberIncomeSummary($memid)
    {
        $member = DB::table('member')->where('show_mem_id', $memid)->first();

        if (!$member) {
            return abort(404, 'Member not found');
        }

        // Get summary data
        $dailyIncomeTotal = DB::table('member_daily_income')
            ->where('member_id', $member->id)
            ->sum('amount');

        $directPaymentTotal = DB::table('direct_payment_tbl')
            ->where('member_id', $member->id)
            ->sum('amount');

        $matchingIncomeTotal = DB::table('matching_income')
            ->where('member_id', $member->id)
            ->sum('amount');

        $salaryIncomeTotal = DB::table('salary_income')
            ->where('member_id', $member->id)
            ->sum('amount');

        return view('member.income_summary', compact(
            'member',
            'memid',
            'dailyIncomeTotal',
            'directPaymentTotal',
            'matchingIncomeTotal',
            'salaryIncomeTotal'
        ));
    }


    // end

    private function deactivateMember($recId)
    {
        $cartDetails = DB::table('cart_details')->where('memid', $recId)->where('joining', 'yes')->first();
        if ($cartDetails) {
            $sponsorId = DB::table('member')->where('mem_id', $recId)->value('sponsorid');

            DB::table('member')->where('mem_id', $recId)->update([
                'activate_date' => 0,
                'payment' => 0,
                'cur_rank' => '',
            ]);

            DB::table('member')->where('mem_id', $sponsorId)->decrement('tot_ref');

            DB::table('mem_level_income')->where('fromid', $recId)->delete();
            DB::table('cart_tbl1')->where('order_id', $cartDetails->orderid)->delete();
            DB::table('cart_payment')->where('orderid', $cartDetails->orderid)->delete();
            DB::table('cart_details')->where('orderid', $cartDetails->orderid)->delete();
        }
    }

    public function updateStatus(Request $request)
    {
        // Get the member based on the mem_id (RecID)
        $member = Member::find($request->RecID);

        // Check if the operation is for 'status'
        if ($request->opr == 'status') {
            if ($member) {
                if ($member->active == 'no') {
                    // Activate the member
                    $member->active = 'yes';
                    $member->inactive = 'no';
                } else {
                    // Deactivate the member
                    $member->active = 'no';
                    $member->inactive = 'yes';
                }
                $member->save(); // Save the changes
            }
        }

        return redirect()->back()->with('msg', 'Member status updated successfully!');
    }

    private function updateShowToday($recId, Request $request)
    {
        DB::table('member')->where('mem_id', $recId)->update(['showtoday' => $request->input('st')]);
    }

    private function changeMemberValue($recId, Request $request)
    {
        if ($request->input('stockist') === 'yes') {
            DB::table('member')->where('mem_id', $recId)->update(['cur_rank' => 'Stockist', 'promotdate' => time()]);
            DB::table('vender_tbl')->insert(['memid' => $recId]);
        } elseif ($request->input('Distributor') === 'yes') {
            DB::table('member')->where('mem_id', $recId)->update(['cur_rank' => 'Distributor', 'showtoday' => $request->input('ParentID'), 'promotdate' => time()]);
        } else {
            DB::table('member')->where('mem_id', $recId)->update(['cur_rank' => '', 'promotdate' => '', 'showtoday' => '']);
        }
    }

    public function manage(Request $request)
    {
        $msg = '';
        $keywords = $request->input('keyword');

        if ($request->isMethod('post')) {
            if ($request->input('opr') === 'status') {
                $member = Member::find($request->input('RecID'));
                if ($request->input('inactive') === 'yes') {
                    $member->update(['inactive' => 'yes', 'active' => '']);
                } elseif ($request->input('active') === 'yes') {
                    $member->update(['active' => 'yes', 'inactive' => '']);
                }
            } elseif ($request->input('opr') === 'showtoday') {
                $member = Member::find($request->input('RecID'));
                $member->update(['showtoday' => $request->input('st')]);
                $msg = "Record Updated Successfully.";
            } elseif ($request->input('opr') === 'chvalue') {
                $member = Member::find($request->input('RecID'));
                if ($request->input('stockist') === 'yes') {
                    $member->update(['cur_rank' => 'Stockist', 'promotdate' => time()]);
                    // Optionally insert into vender_tbl here
                    $msg = "Member updated as Stockist";
                } elseif ($request->input('Distributor') === 'yes') {
                    $member->update(['cur_rank' => 'Distributor', 'showtoday' => $request->input('ParentID'), 'promotdate' => time()]);
                } else {
                    $member->update(['cur_rank' => '', 'promotdate' => '', 'showtoday' => '']);
                    $msg = "Member dominated as normal member";
                }
            }
        }
        $query = Member::where('status', 1)
            ->where('activate_date', 0)
            ->where('payment', 0);

        if ($keywords) {
            $query->where(function ($q) use ($keywords) {
                $q->where('Name', 'like', "%{$keywords}%")
                    ->orWhere('mem_id', 'like', "%{$keywords}%");
            });
        }

        $members = $query->paginate(25);

        return view('admin.members.manage_member', compact('members', 'msg', 'keywords'));
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

    public function showBankForm()
    {
        $member = auth()->user(); // or however you get the current member

        // Check if member already has bank details
        $bankDetails = MemberBankDetail::where('member_id', $member->show_mem_id)->first();

        if ($bankDetails) {
            return redirect()->back()->with('error', 'Bank details already submitted. You can only submit once.');
        }

        return view('member.bank-form', compact('member'));
    }

    /**
     * Store bank details
     */
    public function storeBankDetails(Request $request)
    {
        $member = auth()->user(); // or however you get the current member

        // Check if member already has bank details
        $existingBankDetails = MemberBankDetail::where('member_id', $member->show_mem_id)->first();

        if ($existingBankDetails) {
            return redirect()->back()->with('error', 'Bank details already submitted. You can only submit once.');
        }

        // Validation rules
        $request->validate([
            'accname' => 'required|string|max:255',
            'acctype' => 'required|string|max:100',
            'acc_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/', // IFSC format validation
            'address' => 'required|string|max:255',
            'micr' => 'nullable|string|max:100',
            'pannumber' => 'required|string|max:50|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', // PAN format validation
            'aadhar_number' => 'nullable|string|max:20|regex:/^[0-9]{12}$/', // Aadhar format validation
            'googlepay' => 'nullable|string|max:50',
            'phonepay' => 'nullable|string|max:50',
        ]);

        try {
            // Create bank details
            MemberBankDetail::create([
                'member_id' => $member->show_mem_id,
                'accname' => $request->accname,
                'acctype' => $request->acctype,
                'acc_number' => $request->acc_number,
                'bank_name' => $request->bank_name,
                'branch' => $request->branch,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'address' => $request->address,
                'micr' => $request->micr,
                'pannumber' => strtoupper($request->pannumber),
                'aadhar_number' => $request->aadhar_number,
                'googlepay' => $request->googlepay,
                'phonepay' => $request->phonepay,
            ]);

            return redirect()->route('member.dashboard')->with('success', 'Bank details submitted successfully!');
        } catch (\Exception $e) {
            // Log the error message and stack trace for debugging
            Log::error('Bank detail submission failed for member ID: ' . $member->show_mem_id, [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Failed to submit bank details. Please try again.');
        }
    }

    /**
     * View bank details (read-only)
     */
    public function viewBankDetails()
    {
        $member = auth()->user(); // or however you get the current member

        $bankDetails = MemberBankDetail::where('member_id', $member->show_mem_id)->first();

        if (!$bankDetails) {
            return redirect()->route('member.bank.form')->with('info', 'Please submit your bank details first.');
        }

        return view('member.bank-details', compact('bankDetails', 'member'));
    }

    /**
     * Check if member has bank details (helper method)
     */
    public function hasBankDetails()
    {
        $member = auth()->user(); // or however you get the current member

        return MemberBankDetail::where('member_id', $member->show_mem_id)->exists();
    }


    public function editPassword()
    {
        return view('member.password.edit');
    }

    public function updatePassword(Request $request)
    {
        $member = Auth::guard('member')->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $member->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $member->password = Hash::make($request->new_password);
        $member->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
