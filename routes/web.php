<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();
Route::group(['middleware' => 'auth'],function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/get-data/{userId}', [MessageController::class, 'conversation']);
    Route::get('/get-messages/{user_id}/{friend_id}',[MessageController::class,'getMessages']);
    Route::post('/send-message',[MessageController::class,'sendMessage'])->name('message.send-message');
});
