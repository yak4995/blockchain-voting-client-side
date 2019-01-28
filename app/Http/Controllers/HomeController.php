<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AdmittedVoter;
use App\Voting;

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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $votings = Voting::whereHas('admittedVoters', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('home', [
            'profile' => $user,
            'votings' => $votings,
        ]);
    }
}
