<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin::'], function () {
    Route::get('/', 'AdminController@index')
        ->name('index');

    Route::get('/clients', 'AdminController@clients')
        ->name('clients');

    Route::post('/change-user/{userId}', 'AdminController@changeUser')
        ->name('changeUser')
        ->where(['userId' => '[0-9]+']);

    Route::get('/create-voting-form', 'AdminController@createVotingForm')
        ->name('createVotingForm');
    Route::post('/create-voting', 'AdminController@createVoting')
        ->name('createVoting');
    Route::get('/voting/{votingId}', 'AdminController@voting')
        ->name('voting')
        ->where(['votingId' => '[0-9]+']);
    Route::post('/voting/{votingId}/candidate/add', 'AdminController@addCandidate')
        ->name('addCandidate')
        ->where(['votingId' => '[0-9]+']);
    Route::get('/candidate/{candidateId}/delete', 'AdminController@deleteCandidate')
        ->name('deleteCandidate')
        ->where(['candidateId' => '[0-9]+']);
    Route::get('/voting/{votingId}/publish', 'AdminController@publishVoting')
        ->name('publishVoting')
        ->where(['votingId' => '[0-9]+']);
    Route::get('/voting/{votingId}/delete', 'AdminController@deleteVoting')
        ->name('deleteVoting')
        ->where(['votingId' => '[0-9]+']);
});
