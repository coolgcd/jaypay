<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about() {
        return view('front.about');
    }

    public function property() {
        return view('front.property');
    }

    public function product() {
        return view('front.product');
    }

    public function legal() {
        return view('front.legal');
    }

    public function contact() {
        return view('front.contact');
    }
}
