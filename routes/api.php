<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//damn workaround to get laravel passport to work as it should
Route::get('/user', function (Request $request) {
    $bearerToken = $request->bearerToken() ?? '';
    $accessToken = DB::table('oauth_access_tokens')->where('id', $bearerToken)->first();
    $user = User::findOrFail($accessToken->user_id ?? 0);
    return $user;
    //return $request->user();
})/*->middleware('auth:api')*/;
