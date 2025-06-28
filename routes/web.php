<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
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
    // Rotas para o gerenciamento de produtos
    Route::resource('products', ProductController::class);

    // Rotas para o gerenciamento de eventos
    Route::get('/calendar', [EventController::class, 'index'])->name('events.calendar');
    Route::get('/api/events', [EventController::class, 'getEvents'])->name('api.events');
    Route::post('/api/events', [EventController::class, 'store']);
    Route::put('/api/events/{event}', [EventController::class, 'update']);
    Route::delete('/api/events/{event}', [EventController::class, 'destroy']);

    // Rotas para gerenciamento de usuários
    Route::resource('users', UserController::class)->middleware('can:viewAny,App\Models\User');
    Route::get('/profile/password', [ProfileController::class, 'changePasswordForm'])->name('profile.change_password_form');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');

    // Rotas para o gerenciamento de clientes
    Route::resource('customers', CustomerController::class);
});