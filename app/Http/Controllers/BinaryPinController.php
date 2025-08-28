<?php
namespace App\Http\Controllers;


use App\Models\BinaryPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade

class BinaryPinController extends Controller
{
    public function index()
    {
        $pinsQuery = BinaryPin::query();
        $pins = $pinsQuery->paginate(10);
        return view('admin.pin_report', compact('pins'));
    }

    public function search(Request $request)
    {
        $SMEmID = $request->input('SMEmID');

        $pinsQuery = BinaryPin::query();

        if ($SMEmID) {
            $pinsQuery->where('memid', $SMEmID);
        }

        $pins = $pinsQuery->paginate(10);

        return view('binarypin.index', compact('pins'));
    }

    public function usedbinarypin(Request $request)
    {
        $SMEmID = $request->input('SMEmID');

        // Building the query to fetch pins
        $query = BinaryPin::where('status', 1)
            ->where('pintp', 1)
            ->orderBy('creat_date', 'desc');

        if ($SMEmID) {
            $query->where('memid', $SMEmID);
        }

        $pins = $query->paginate(10); // Paginate results

        return view('admin.used_binary_pin', compact('pins'));
    }

    public function unusedTopupPin(Request $request)
    {
        $MSG = '';
        $SMEmID = $request->input('SMEmID', null);
        $Pageno = $request->input('Pageno', 1);
        $limit = 5000;
        $offset = ($Pageno - 1) * $limit;

        if ($request->input('Oprs') === 'del') {
            DB::table('binary_pin')->where('bsn', $request->input('Msdelid'))->delete();
            $MSG = 'Pin deleted successfully';
        }

        $query = DB::table('binary_pin')
            ->where('status', 0)
            ->where('pintp', 1);

        if ($SMEmID) {
            $query->where('memid', $SMEmID);
        }

        $Num = $query->count();
        $numPages = ceil($Num / $limit);

        $pins = $query->orderByDesc('creat_date')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return view('admin.unused_topup_pin', compact('pins', 'MSG', 'Pageno', 'numPages', 'SMEmID', 'limit'));
    }

    public function matchinpair()
{
    // Fetching binary payouts from the database
    $binaryPayouts = DB::table('binary_payouts')->orderBy('calcdate', 'desc')->get();

    // Pagination logic (if necessary)
    $pagedResults = null; // Set up your pagination logic here if needed

    // Pass variables to the view
    return view('admin.matching_pair', compact('binaryPayouts', 'pagedResults', 'errorMsg'));
}

}
