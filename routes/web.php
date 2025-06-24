<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false, 
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);

    Route::resource('users', UserController::class)->middleware('can:viewAny,App\Models\User');

    Route::get('/profile/password', [ProfileController::class, 'changePasswordForm'])->name('profile.change_password_form');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');
});