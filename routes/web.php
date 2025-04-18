<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminQueueController;
use Inertia\Inertia;



Route::get('/', function () {

    if(auth()->user()){
        if(auth()->user()->hasRole('admin')) return redirect()->route('dashboard');
        if(auth()->user()->hasRole('user')) return redirect()->route('user');
    }




    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', ])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//user
Route::get('/user', [UserController::class, 'index'])->middleware(['auth'])->name('user');
Route::post('/user/queue/request', [UserController::class, 'requestQueue'])
    ->middleware(['auth', 'verified'])
    ->name('user.queue.request');
Route::patch('/user/queue/{queue}/cancel', [UserController::class, 'cancel'])
    ->middleware(['auth', 'verified'])
    ->name('user.queue.cancel');


//admin
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminQueueController::class, 'index'])->name('dashboard');
    Route::patch('/admin/queue/{queue}/complete', [AdminQueueController::class, 'complete'])->name('admin.queue.complete');
    Route::patch('/admin/queue/{queue}/cancel', [AdminQueueController::class, 'cancel'])->name('admin.queue.cancel');
});





require __DIR__.'/auth.php';