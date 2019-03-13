<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Voting page
     */
    public function voting(Request $request, int $votingId) {
        $user = Auth::user();
        $voting = Voting::where('id', $votingId)
                    ->where('is_published', true)
                    ->whereHas('admittedVoters', function($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->firstOrFail();

        return view('voting', [
            'voting' => $voting
        ]);
    }
}
