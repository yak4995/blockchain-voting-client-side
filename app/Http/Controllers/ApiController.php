<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Return information about current user..
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        $user = Auth::guard()->user();
        return response()->json([
            'id' => $user->id,
            'is_admin' => $user->is_admin,
            'name' => $user->name,
            'email' => $user->email,
            'faculty' => $user->employee->department->faculty->name,
            'department' => $user->employee->department->name,
            'position' => $user->employee->position->name
        ]);
    }
}
