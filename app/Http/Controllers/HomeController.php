<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the application About page.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('nav.about');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('nav.dashboard');
    }

    /**
     * Show the application Consumer Manager page.
     *
     * @return \Illuminate\Http\Response
     */
    public function consumers()
    {
        return view('consumers.index');
    }
}
