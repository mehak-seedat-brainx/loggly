<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function index() {

        $title = 'Home!';
        if (Auth::guest()) {
            return view('pages.index', compact('title'));
        } else {
            $user_id = auth()->user()->id;
            $user = User::find($user_id);
            Log::info("husdfd");
            return view('home');
        }
    }
    public function about() {
        $title = 'About!';
        return view ('pages.about')->with('title', $title);
    }
    public function services() {
        $data = array(
            'title' => 'Services:',
            'services' => ['Web', 'Mobile']

        );
        return view ('pages.services')->with($data);
    }
}
