<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagDetailController extends Controller
{
    public function index()
    {
        return view('tag-detail');
    }
}