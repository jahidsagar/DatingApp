<?php

use Illuminate\Support\Facades\Route;
//---------
use Illuminate\Http\Request;
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
// ----------- UserController
// registration route, if user already logged in 
// then redirect to /user dashboard
Route::get('/', 'Registration\UserController@index');
// registration form handeling
Route::post('/registration', 'Registration\UserController@registration');
// get signin view , if logged in then go to /user dashboard
Route::get('/signin', 'Registration\UserController@signin')->name('signin');
// handel signin credentials and logged in also user flush message
// if encounter any error
Route::post('/login', 'Registration\UserController@signinUser');
// logout user
Route::post('/logout',function(){
    Auth::logout();
    return redirect('/');
});

// ----------- DashboardController
// user dashboard
Route::get('/user', 'DashboardController@dashboard')->middleware('auth');
// user profile image upload, we don't user any db col 
// just a single photo handel it simply by id
Route::post('/imageupload','DashboardController@imageUpload')->middleware('auth');

// ----------- LikeController
// user like submission using jquery ajax
Route::post('/like','LikeController@record')->middleware('auth');
// user dislike submission using jquery ajax
Route::post('/dislike','LikeController@dislike')->middleware('auth');
// get all liked users via ajax
Route::get('/match', 'LikeController@getbyId');
// finding all liked via ajax, it requests after
// 5 seconds repeteadly
Route::post('/getmatch','LikeController@getMatch')->middleware('auth');

//create symlink , call once
Route::get('/linkstorage', function () {
    File::link(storage_path('app/public'), public_path('storage'));
    
    $exitCode = Artisan::call('storage:link', [] );
    echo $exitCode; // 0 exit code for no errors.
});
