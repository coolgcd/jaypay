<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Closing;

class ClosingController extends Controller
{
    public function index(Request $request)
    {
        // Fetch distinct closing dates from the database
        $closings = Closing::select('closing_date')
                            ->distinct()
                            ->orderBy('closing_date', 'asc')
                            ->get();

        // Show closing report view
        return view('admin.products.closing_report', [
            'closings' => $closings,
        ]);
    }

    public function add()
    {
        // Set session for new closing date
        session(['closing_date' => mktime(23, 50, 0, date('m'), date('d'), date('Y'))]);

        return redirect()->route('closing.index')
                         ->with('message', 'New closing date added successfully');
    }

    public function showDetails($closdate)
    {
        // return )->with('message', 'Record Deleted Successfully.');

        // Logic to show closing details based on $closdate
        return redirect()->route('manageClosingReport', ['closing_date' => $closdate]);
    }
}
