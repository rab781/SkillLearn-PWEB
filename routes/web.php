<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

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
    return view('home');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Authentication routes
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/videos', function () {
    return view('videos.index');
});

Route::get('/videos/{id}', function ($id) {
    return view('videos.show')->with('id', $id);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard umum untuk customer (tanpa middleware role karena sudah di cek di controller)
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        }
        return view('dashboard.customer');
    })->name('dashboard');

    // Customer routes
    Route::middleware('check.role:CU')->group(function () {
        Route::get('/customer/dashboard', function () {
            return view('dashboard.customer');
        });
    });

    // Admin routes
    Route::middleware('check.role:AD')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('dashboard.admin');
        });
    });
});
