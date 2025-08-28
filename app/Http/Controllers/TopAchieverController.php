<?php

namespace App\Http\Controllers;

use App\Models\TopAchiever;
use Illuminate\Http\Request;

class TopAchieverController extends Controller
{
    public function index(Request $request)
    {
        $achievers = TopAchiever::where('status', 1)->paginate(25);
        return view('admin.manage_archivers', compact('achievers'));
    }

    public function store(Request $request)
    {
        $request->validate(['achiverdel' => 'required']);

        $exists = TopAchiever::where('tatitle', $request->achiverdel)->exists();

        if (!$exists) {
            TopAchiever::create([
                'tatitle' => $request->achiverdel,
                'add_date' => time(),
                'status' => 1,
            ]);
            return redirect()->route('achievers.index')->with('msg', 'Record Added Successfully.');
        } else {
            return redirect()->route('achievers.index')->with('msg', 'Record already Added Successfully.');
        }
    }

    public function destroy($id)
    {
        TopAchiever::destroy($id);
        return redirect()->route('achievers.index')->with('msg', 'Record Deleted Successfully.');
    }
}
