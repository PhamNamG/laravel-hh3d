<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopViewController extends Controller
{
    public function index()
    {
        return view('top-view');
    }
}