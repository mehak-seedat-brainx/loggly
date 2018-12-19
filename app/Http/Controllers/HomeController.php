<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use PragmaRX\ZipCode\ZipCode;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id=auth()->user()->id;
        $user=User::find($user_id);
        $z = new ZipCode();
        $z->setCountry('United States');
        Log::info($z->find('10006')->toArray());
        return view('home')->with('posts', $user->posts);
    }
}
