<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function test() {
        $title = "Testing Github API";
        //return view('pages.index', compact('title'));
        return view('pages.test')->with('title', $title);
    }

    public function test1() {
        $title = "FlexClub Senior PHP / Fullstack Developer Project Brief v2";
        //return view('pages.index', compact('title'));
        return view('pages.repos')->with('title', $title);
    }

}
