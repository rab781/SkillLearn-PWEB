<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\RiwayatTontonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BookmarkController;


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
})->name('home');

// Halaman pembelajaran (alias untuk courses)
Route::get('/pembelajaran', function () {
    return redirect('/courses');
})->name('pembelajaran');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Authentication routes
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Debug routes - remove after debugging
Route::get('/debug/auth', function () {
    $user = auth()->user();
    return [
        'authenticated' => auth()->check(),
        'user' => $user ? [
            'id' => $user->users_id ?? $user->id,
            'name' => $user->nama_lengkap,
            'email' => $user->email,
            'role' => $user->role,
        ] : null,
        'session_id' => session()->getId(),
        'guards' => config('auth.guards'),
        'default_guard' => config('auth.defaults.guard'),
    ];
});

Route::get('/debug/middleware', function () {
    return response()->json([
        'message' => 'This route works without middleware',
        'user' => auth()->user(),
    ]);
});

Route::middleware('check.role:AD')->get('/debug/admin', function () {
    return response()->json([
        'message' => 'Admin access granted!',
        'user' => auth()->user(),
    ]);
});

Route::get('/videos', function () {
    return redirect('/courses');
});

Route::get('/videos/{id}', function ($id) {
    return redirect('/courses/' . $id);
});

// Public course routes (accessible without login)
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard umum untuk customer (tanpa middleware role karena sudah di cek di controller)
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        // Get dashboard data server-side
        $controller = new \App\Http\Controllers\DashboardController();
        $response = $controller->customerDashboard();
        $dashboardData = json_decode($response->getContent(), true);

        return view('dashboard.customer')->with('dashboardData', $dashboardData);
    })->name('dashboard');

    // Customer routes
    Route::middleware('check.role:CU')->group(function () {
        Route::get('/customer/dashboard', function () {
            // Get dashboard data server-side
            $controller = new \App\Http\Controllers\DashboardController();
            $response = $controller->customerDashboard();
            $dashboardData = json_decode($response->getContent(), true);

            return view('dashboard.customer')->with('dashboardData', $dashboardData);
        });

        // Course routes for customers
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('my-courses');
            Route::get('/{id}', [CourseController::class, 'show'])->name('show');
            Route::post('/{id}/start', [CourseController::class, 'start'])->name('start');
            // Temporary GET route for debugging - redirect to show with error
            Route::get('/{id}/start', function($id) {
                return redirect()->route('courses.show', $id)
                    ->with('error', 'Gunakan tombol "Mulai Pembelajaran" untuk memulai course ini.');
            });
            Route::get('/{courseId}/video/{videoId}', [CourseController::class, 'video'])->name('video');
            Route::post('/{courseId}/video/{videoId}/complete', [CourseController::class, 'completeVideo'])->name('video.complete');
            Route::post('/{courseId}/video/{videoId}/watch-time', [CourseController::class, 'updateWatchTime'])->name('video.watch-time');
            Route::get('/{id}/progress', [CourseController::class, 'progress'])->name('progress');
            Route::get('/{courseId}/review/{reviewId}', [CourseController::class, 'quickReview'])->name('quick-review');

            // Quiz routes
            Route::get('/{courseId}/quiz/{quizId}', [QuizController::class, 'show'])->name('quiz.show');
            Route::post('/{courseId}/quiz/{quizId}/submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');
            Route::get('/{courseId}/quiz-results', [QuizController::class, 'getUserResults'])->name('quiz.results');
        });

        // History routes
        Route::prefix('history')->name('history.')->group(function () {
            Route::get('/', [RiwayatTontonController::class, 'index'])->name('index');
            Route::get('/recent', [RiwayatTontonController::class, 'recentlyWatched'])->name('recent');
            Route::get('/course/{courseId}', [RiwayatTontonController::class, 'getCourseHistory'])->name('course');
            Route::get('/continue/{courseId}', [RiwayatTontonController::class, 'continueWatching'])->name('continue');
            Route::post('/record', [RiwayatTontonController::class, 'recordWatch'])->name('record');
            Route::delete('/course/{courseId}', [RiwayatTontonController::class, 'deleteHistory'])->name('delete');
        });

        // Add web routes for customer feedback and bookmark
        Route::prefix('web')->group(function () {
            Route::post('/feedback', [FeedbackController::class, 'store'])->name('web.feedback.store');
            Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('web.feedback.destroy');
            Route::post('/bookmark', [BookmarkController::class, 'store'])->name('web.bookmark.store');
            Route::post('/bookmark/course', [BookmarkController::class, 'storeCourse'])->name('web.bookmark.course.store');
            Route::post('/bookmark/video/{videoId}', [BookmarkController::class, 'toggleVideoBookmark'])->name('web.bookmark.video.toggle');
            Route::get('/bookmark/video/{videoId}/check', [BookmarkController::class, 'checkVideoBookmark'])->name('web.bookmark.video.check');
            Route::delete('/bookmark/{courseId}', [BookmarkController::class, 'destroy'])->name('web.bookmark.destroy');
            Route::get('/bookmark/check/{courseId}', [BookmarkController::class, 'checkBookmark'])->name('web.bookmark.check');
            Route::get('/bookmark/course/check/{courseId}', [BookmarkController::class, 'checkCourseBookmark'])->name('web.bookmark.course.check');
        });
    });

    // Profil umum untuk CU dan AD (pindahkan ke luar group check.role:CU)
    Route::middleware('check.role:CU,AD')
    ->prefix('profil')
    ->as('profil.')
    ->group(function () {
        Route::get('/', [ProfilController::class, 'show'])->name('show');
        Route::get('/edit', [ProfilController::class, 'edit'])->name('edit');
        Route::put('/', [ProfilController::class, 'update'])->name('update');
});


    // Admin routes
    Route::middleware('check.role:AD')->group(function () {
        Route::get('/admin/dashboard', [AdminCourseController::class, 'dashboard'])->name('dashboard.admin');

        // Admin Course Management
        Route::prefix('admin/courses')->name('admin.courses.')->group(function () {
            Route::get('/', [AdminCourseController::class, 'index'])->name('index');
            Route::get('/create', [AdminCourseController::class, 'create'])->name('create');
            Route::post('/', [AdminCourseController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminCourseController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminCourseController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminCourseController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminCourseController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [AdminCourseController::class, 'toggleStatus'])->name('toggle-status');

            // Section management
            Route::post('/{courseId}/sections', [AdminCourseController::class, 'addSection'])->name('add-section');
            Route::delete('/{courseId}/sections/{sectionId}', [AdminCourseController::class, 'removeSection'])->name('remove-section');

            // Video management
            Route::post('/{courseId}/videos', [AdminCourseController::class, 'addVideoToSection'])->name('add-video');
            Route::delete('/{courseId}/videos/{courseVideoId}', [AdminCourseController::class, 'removeVideo'])->name('remove-video');
            Route::post('/{courseId}/videos/reorder', [AdminCourseController::class, 'reorderVideos'])->name('reorder-videos');

            // Quick Review management
            Route::post('/{courseId}/reviews', [AdminCourseController::class, 'addQuickReview'])->name('add-review');

            // Quiz management
            Route::get('/{courseId}/quizzes', [QuizController::class, 'adminIndex'])->name('quizzes');
            Route::post('/{courseId}/quizzes', [QuizController::class, 'adminStore'])->name('quiz.store');
            Route::get('/quiz/{quizId}', [QuizController::class, 'show'])->name('quiz.show');
            Route::put('/quiz/{quizId}', [QuizController::class, 'adminUpdate'])->name('quiz.update');
            Route::delete('/quiz/{quizId}', [QuizController::class, 'adminDestroy'])->name('quiz.destroy');
            Route::get('/{courseId}/quiz-statistics', [QuizController::class, 'getCourseStatistics'])->name('quiz.statistics');

            // Course statistics and management
            Route::get('/{courseId}/history-statistics', [RiwayatTontonController::class, 'getCourseStatistics'])->name('history.statistics');
        });

        // Admin Feedback Management
        Route::prefix('admin/feedback')->name('admin.feedback.')->group(function () {
            Route::get('/', [FeedbackController::class, 'adminIndex'])->name('index');
            Route::post('/{feedback}/reply', [FeedbackController::class, 'reply'])->name('reply');
            Route::patch('/{feedback}/status', [FeedbackController::class, 'updateStatus'])->name('status');
        });
    });
});
