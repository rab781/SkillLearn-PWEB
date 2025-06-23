<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VidioController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RiwayatTontonController;
use App\Http\Controllers\QuizController;

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

// Public course routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/popular', [CourseController::class, 'popular']);
Route::get('/courses/latest', [CourseController::class, 'latest']);
Route::get('/courses/category/{kategoriId}', [CourseController::class, 'getByCategory']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Kategori public routes
Route::get('/categories', [KategoriController::class, 'index']);
Route::get('/categories/{kategori}', [KategoriController::class, 'show']);

// YouTube utilities
Route::get('/youtube/video-data', [\App\Http\Controllers\Api\YouTubeController::class, 'getVideoData']);

// Protected routes (require authentication)
// Use web session for dashboard access from web interface
Route::middleware(['web', 'auth'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Customer routes
    Route::middleware('check.role:CU')->group(function () {
        // Course-based bookmarks
        Route::get('/bookmarks', [BookmarkController::class, 'index']);
        Route::post('/bookmarks', [BookmarkController::class, 'store']);
        Route::delete('/bookmarks/{courseId}', [BookmarkController::class, 'destroy']);
        Route::get('/bookmarks/check/{courseId}', [BookmarkController::class, 'checkBookmark']);

        // Course-based feedback
        Route::get('/my-feedbacks', [FeedbackController::class, 'myFeedbacks']);
        Route::post('/feedbacks', [FeedbackController::class, 'store']);
        Route::put('/feedbacks/{feedback}', [FeedbackController::class, 'update']);
        Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy']);

        // Course history
        Route::get('/course-history', [RiwayatTontonController::class, 'index']);
        Route::get('/course-history/recent', [RiwayatTontonController::class, 'recentlyWatched']);
        Route::get('/course-history/{courseId}', [RiwayatTontonController::class, 'getCourseHistory']);
        Route::get('/course-history/continue/{courseId}', [RiwayatTontonController::class, 'continueWatching']);
        Route::post('/course-history/record', [RiwayatTontonController::class, 'recordWatch']);
        Route::delete('/course-history/{courseId}', [RiwayatTontonController::class, 'deleteHistory']);

        // Quiz
        Route::get('/courses/{courseId}/quizzes', [QuizController::class, 'index']);
        Route::get('/quiz/{quizId}', [QuizController::class, 'show']);
        Route::post('/quiz/{quizId}/submit', [QuizController::class, 'submitAnswer']);
        Route::get('/courses/{courseId}/quiz-results', [QuizController::class, 'getUserResults']);

        // Dashboard customer
        Route::get('/dashboard', [DashboardController::class, 'customerDashboard']);
        Route::get('/dashboard/data', [DashboardController::class, 'customerDashboard']); // Alternative endpoint
        Route::get('/customer/stats', [DashboardController::class, 'customerStats']);

        // Watch history (legacy - redirect to course history)
        Route::post('/watch-history', [RiwayatTontonController::class, 'recordWatch']);
    });

    // Admin routes
    Route::middleware('check.role:AD')->group(function () {
        // Video management (legacy)
        Route::post('/admin/videos', [VidioController::class, 'store']);
        Route::put('/admin/videos/{vidio}', [VidioController::class, 'update']);
        Route::delete('/admin/videos/{vidio}', [VidioController::class, 'destroy']);

        // Category management
        Route::post('/admin/categories', [KategoriController::class, 'store']);
        Route::put('/admin/categories/{kategori}', [KategoriController::class, 'update']);
        Route::delete('/admin/categories/{kategori}', [KategoriController::class, 'destroy']);

        // Course-based feedback management
        Route::get('/admin/feedbacks', [FeedbackController::class, 'index']);
        Route::put('/admin/feedbacks/{feedback}/reply', [FeedbackController::class, 'reply']);
        Route::delete('/admin/feedbacks/{feedback}', [FeedbackController::class, 'destroy']);

        // Quiz management
        Route::post('/admin/courses/{courseId}/quizzes', [QuizController::class, 'store']);
        Route::put('/admin/quiz/{quizId}', [QuizController::class, 'update']);
        Route::delete('/admin/quiz/{quizId}', [QuizController::class, 'destroy']);
        Route::get('/admin/courses/{courseId}/quiz-statistics', [QuizController::class, 'getCourseStatistics']);

        // Course statistics
        Route::get('/admin/courses/{courseId}/history-statistics', [RiwayatTontonController::class, 'getCourseStatistics']);

        // Dashboard admin & reports
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard']);
        Route::get('/admin/reports/users', [DashboardController::class, 'usersReport']);
        Route::get('/admin/reports/videos', [DashboardController::class, 'videosReport']);
        Route::get('/admin/reports/courses', [DashboardController::class, 'coursesReport']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
