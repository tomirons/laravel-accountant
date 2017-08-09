<?php

namespace TomIrons\Accountant\Http\Controllers;


class HomeController extends Controller
{
    /**
     * Display catch all view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('accountant::dashboard');
    }
}