<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VidioController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public video routes (for browsing without login)
Route::get('/videos', [VidioController::class, 'index']);
Route::get('/videos/popular', [VidioController::class, 'popular']);
Route::get('/videos/latest', [VidioController::class, 'latest']);
Route::get('/videos/category/{kategoriId}', [VidioController::class, 'getByCategory']);
Route::get('/videos/{vidio}', [VidioController::class, 'show']);
Route::post('/videos/{vidio}/increment-view', [VidioController::class, 'incrementView']);

// Kategori public routes
Route::get('/categories', [KategoriController::class, 'index']);
Route::get('/categories/{kategori}', [KategoriController::class, 'show']);

// Protected routes (require authentication)
// Use web session for dashboard access from web interface
Route::middleware(['web', 'auth'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Customer routes
    Route::middleware('check.role:CU')->group(function () {
        // Bookmark routes
        Route::get('/bookmarks', [BookmarkController::class, 'index']);
        Route::post('/bookmarks', [BookmarkController::class, 'store']);
        Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy']);
        Route::get('/bookmarks/check/{video}', [BookmarkController::class, 'checkBookmark']);

        // Feedback routes (customer)
        Route::get('/my-feedbacks', [FeedbackController::class, 'myFeedbacks']);
        Route::post('/feedbacks', [FeedbackController::class, 'store']);
        Route::put('/feedbacks/{feedback}', [FeedbackController::class, 'update']);
        Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy']);



        // Dashboard customer
        Route::get('/dashboard', [DashboardController::class, 'customerDashboard']);
        Route::get('/customer/stats', [DashboardController::class, 'customerStats']);

        // Watch history
        Route::post('/watch-history', [DashboardController::class, 'recordWatchHistory']);
    });

    // Admin routes
    Route::middleware('check.role:AD')->group(function () {
        // Video management
        Route::post('/admin/videos', [VidioController::class, 'store']);
        Route::put('/admin/videos/{vidio}', [VidioController::class, 'update']);
        Route::delete('/admin/videos/{vidio}', [VidioController::class, 'destroy']);

        // Category management
        Route::post('/admin/categories', [KategoriController::class, 'store']);
        Route::put('/admin/categories/{kategori}', [KategoriController::class, 'update']);
        Route::delete('/admin/categories/{kategori}', [KategoriController::class, 'destroy']);

        // Feedback management
        Route::get('/admin/feedbacks', [FeedbackController::class, 'index']);
        Route::put('/admin/feedbacks/{feedback}/reply', [FeedbackController::class, 'reply']);
        Route::delete('/admin/feedbacks/{feedback}', [FeedbackController::class, 'destroy']);

        // Dashboard admin & reports
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard']);
        Route::get('/admin/reports/users', [DashboardController::class, 'usersReport']);
        Route::get('/admin/reports/videos', [DashboardController::class, 'videosReport']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
