<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class UtilityController extends Controller
{
    public function index()
    {
        return view('member.utility.index');
    }
}
