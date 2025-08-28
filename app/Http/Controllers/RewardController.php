<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RewardClaim; // Assuming you have a RewardClaim model
use App\Models\Member; // Assuming you have a Member model
use App\Models\Reward; // Assuming you have a RewardClaim model

class RewardController extends Controller
{
    public function index(Request $request)
    {
        // Handle search functionality
        $keyword = $request->input('keyword');

        if ($keyword) {
            $rewards = RewardClaim::where('memid', 'like', '%' . $keyword . '%')
                ->orWhere('rewardimg', 'like', '%' . $keyword . '%')
                ->orderBy('add_date', 'desc')
                ->paginate(25); // Using pagination
        } else {
            $rewards = RewardClaim::orderBy('add_date', 'desc')->paginate(25);
        }

        // Return to view with data
        return view('admin.payouts.rewardachive', compact('rewards', 'keyword'));
    }

    // Get member name by ID (if necessary)
    public static function getMemberName($memid)
    {
        return Member::where('mem_id', $memid)->first()->name ?? 'Unknown';
    }

    public function reward(Request $request)
    {
        $keywords = $request->input('keyword');
        $query = Reward::query()->where('useid', 0);

        if ($keywords) {
            $query->where('name', 'LIKE', '%' . $keywords . '%');
        }

        $rewards = $query;

        return view('admin.sitemanagment.mngreward', compact('rewards', 'keywords'));
    }

    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->delete();

        return redirect()->route('rewards.index')->with('message', 'Reward Deleted Successfully.');
    }
}
