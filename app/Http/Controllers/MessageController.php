<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function showForm()
    {
        return view('admin.members.send-message', ['message' => '', 'totnum' => 0, 'sendsuccess' => false, 'error' => '']);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|max:160',
        ]);

        $message = $request->input('message');
        $members = Member::where('status', 1)
            ->whereNotNull('mobileno')
            ->distinct()
            ->limit(2)
            ->get();

        $totnum = $members->count();
        $sendsuccess = false;
        $Sn = 0;

        foreach ($members as $member) {
            if ($member->mobileno) {
                $mmessage = str_replace('#NAME#', $member->name, $message);
                sendsms(trim($member->mobileno), $mmessage);
                $Sn++;
                $sendsuccess = true;
            }
        }

        return redirect()->route('send.message.form')
            ->with('success', "Total {$Sn} Message sent out of total members: {$totnum}")
            ->with('sendsuccess', $sendsuccess);
    }
}
