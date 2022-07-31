<?php

use App\Models\Pais;
use Illuminate\Support\Facades\Route;

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


//USERS
Route::get('/home', function(){
    $paises = Pais::get();
    return view('home')->with('paises',$paises);
})->middleware(['auth']);
Route::get('getusers','App\Http\Controllers\UserController@index')->middleware('auth');
Route::get('getuser/{id}','App\Http\Controllers\UserController@show')->middleware('auth');
Route::put('updateuser/{id}','App\Http\Controllers\UserController@update')->middleware('auth');
Route::post('deleteuser/{id}','App\Http\Controllers\UserController@destroy')->middleware('auth');


//EMAILS
Route::get('/emails', function(){
    return view('emails');
})->middleware(['auth']);
Route::get('getemails','App\Http\Controllers\EmailController@index')->middleware('auth');
Route::post('storemail','App\Http\Controllers\EmailController@store')->middleware('auth');