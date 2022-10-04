<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(){
        return view('index');
    }

    public function privacy_policy(){
        return view('privacy-policy');
    }

    public function term_and_condition(){
        return view('term-&-condition');
    }
}
