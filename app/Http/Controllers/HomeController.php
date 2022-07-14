<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Show the home page
     *
     * @return  \Illuminate\Support\Facades\View
     */
    public function index()
    {
        return view('home');
    }
}
