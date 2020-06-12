<?php

namespace App\Http\Controllers\AbyssUser;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    protected $redirectTo = '/abyssuser/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('abyss_user.auth:abyss_user');
    }

    /**
     * Show the AbyssUser dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('abyssuser.home');
    }

}