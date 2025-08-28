<?php
namespace App\Http\Controllers;

use App\Models\TopAchiver;
use Illuminate\Http\Request;

class TopAchiverController extends Controller
{
    public function index(Request $request)
    {
        $achivers = TopAchiver::where('status', 1)->paginate(25);
        return view('admin.sitemanagment.mngachivers', compact('achivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'achiverdel' => 'required|string|max:255',
        ]);

        // Check if record already exists
        if (TopAchiver::where('tatitle', $request->achiverdel)->exists()) {
            return redirect()->route('achivers.index')->with('error', 'Record already added.');
        }

        // Create a new record
        TopAchiver::create([
            'tatitle' => $request->achiverdel,
            'add_date' => time(),
            'status' => 1,
        ]);

        return redirect()->route('achivers.index')->with('success', 'Record added successfully.');
    }

    public function destroy($id)
    {
        $achiver = TopAchiver::findOrFail($id);
        $achiver->delete();
        return redirect()->route('achivers.index')->with('success', 'Record deleted successfully.');
    }
}
