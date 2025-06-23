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
use App\Http\Controllers\AdminMonitoringController;


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
});    // Course routes - accessible to everyone
Route::prefix('courses')->name('courses.')->group(function () {
    // Public routes
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/{id}', [CourseController::class, 'show'])->name('show');

    // Routes requiring authentication
    Route::middleware('auth')->group(function () {
        Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('my-courses');
        Route::post('/{id}/start', [CourseController::class, 'start'])->name('start');

        // Course progress and learning
        Route::get('/{course}/progress', [CourseController::class, 'progress'])->name('progress');
        Route::post('/{course}/video/{video}/watch', [CourseController::class, 'watchVideo'])->name('watch-video');

        Route::get('/{courseId}/video/{videoId}', [CourseController::class, 'video'])->name('video');
        Route::post('/{courseId}/video/{videoId}/complete', [CourseController::class, 'completeVideo'])->name('video.complete');
        Route::post('/{courseId}/video/{videoId}/watch-time', [CourseController::class, 'updateWatchTime'])->name('video.watch-time');
        Route::get('/{id}/progress', [CourseController::class, 'progress'])->name('progress');
        Route::get('/{courseId}/review/{reviewId}', [CourseController::class, 'quickReview'])->name('quick-review');

        // Quiz routes
        Route::get('/{courseId}/quiz/{quizId}', [QuizController::class, 'showQuizPage'])->name('quiz.show');
        Route::post('/{courseId}/quiz/{quizId}/submit', [QuizController::class, 'submitQuizAnswers'])->name('quiz.submit');
        Route::get('/{courseId}/quiz-results', [QuizController::class, 'getUserResults'])->name('quiz.results');
        Route::get('/{courseId}/quiz-report', [CourseController::class, 'getQuizReport'])->name('quiz-report');
        Route::get('/{courseId}/video/{videoId}/quizzes', [QuizController::class, 'getVideoQuizzes'])->name('video.quizzes');

        // Temporary GET route for debugging - redirect to show with error
        Route::get('/{id}/start', function($id) {
            return redirect()->route('courses.show', $id)
                ->with('error', 'Gunakan tombol "Mulai Pembelajaran" untuk memulai course ini.');
        });
    });
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

        // Get dashboard data server-side
        $controller = new \App\Http\Controllers\DashboardController();
        $response = $controller->customerDashboard();
        $dashboardData = json_decode($response->getContent(), true);

        return view('dashboard.customer')->with('dashboardData', $dashboardData);
    })->name('dashboard');

    // Additional route for AJAX dashboard data fetching (helps with auth issues)
    Route::get('/dashboard/fetch-data', function () {
        $controller = new \App\Http\Controllers\DashboardController();
        return $controller->customerDashboard();
    })->name('dashboard.fetch-data');

    // Direct route for dashboard data (no middleware wrapping)
    Route::get('/dashboard-data-direct', function () {
        $controller = new \App\Http\Controllers\DashboardController();
        return $controller->customerDashboard();
    })->middleware(['web']);

    // Customer routes
    Route::middleware('check.role:CU')->group(function () {
        // Customer dashboard route
        Route::get('/customer/dashboard', function () {
            // Get dashboard data server-side
            $controller = new \App\Http\Controllers\DashboardController();
            $response = $controller->customerDashboard();
            $dashboardData = json_decode($response->getContent(), true);

            return view('dashboard.customer')->with('dashboardData', $dashboardData);
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
            Route::put('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('web.feedback.update');
            Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('web.feedback.destroy');
            Route::get('/feedback/course/{courseId}', [FeedbackController::class, 'getUserCourseFeedback'])->name('web.feedback.course');
            Route::post('/bookmark', [BookmarkController::class, 'store'])->name('web.bookmark.store');
            Route::post('/bookmark/course', [BookmarkController::class, 'storeCourse'])->name('web.bookmark.course.store');
            Route::post('/bookmark/video/{videoId}', [BookmarkController::class, 'toggleVideoBookmark'])->name('web.bookmark.video.toggle');
            Route::get('/bookmark/video/{videoId}/check', [BookmarkController::class, 'checkVideoBookmark'])->name('web.bookmark.video.check');
            Route::delete('/bookmark/{courseId}', [BookmarkController::class, 'destroy'])->name('web.bookmark.destroy');
            Route::get('/bookmark/check/{courseId}', [BookmarkController::class, 'checkBookmark'])->name('web.bookmark.check');
            Route::get('/bookmark/course/check/{courseId}', [BookmarkController::class, 'checkCourseBookmark'])->name('web.bookmark.course.check');

            // Quiz routes for course detail page
            Route::prefix('courses/{courseId}')->group(function () {
                Route::get('/video/{videoId}/quizzes', [QuizController::class, 'getVideoQuizzes'])->name('web.video.quizzes');
            });
        });

        // Learning Progress Routes
        Route::prefix('learning')->name('learning.')->group(function () {
            Route::get('/course/{courseId}/syllabus', [App\Http\Controllers\DashboardController::class, 'getCourseSyllabus'])->name('course.syllabus');
            Route::get('/quiz-reports', [App\Http\Controllers\DashboardController::class, 'getQuizReportsAjax'])->name('quiz.reports');
        });

        // API routes for AJAX quiz functionality
        Route::prefix('api')->group(function () {
            Route::get('/quiz/{quizId}', [QuizController::class, 'getQuizData'])->name('api.quiz.get');
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
            Route::post('/{courseId}/sections/reorder', [AdminCourseController::class, 'reorderSections'])->name('reorder-sections');

            // Video management
            Route::post('/{courseId}/videos', [AdminCourseController::class, 'addVideoToSection'])->name('add-video');
            Route::delete('/{courseId}/videos/{courseVideoId}', [AdminCourseController::class, 'removeVideo'])->name('remove-video');
            Route::post('/{courseId}/videos/reorder', [AdminCourseController::class, 'reorderVideos'])->name('reorder-videos');

            // Quiz placement in course
            Route::get('/{courseId}/available-quizzes', [AdminCourseController::class, 'getAvailableQuizzes'])->name('available-quizzes');
            Route::post('/{courseId}/quizzes/add', [AdminCourseController::class, 'addQuizToCourse'])->name('add-quiz-to-course');
            Route::delete('/{courseId}/quizzes/{courseQuizId}/remove', [AdminCourseController::class, 'removeQuizFromCourse'])->name('remove-quiz-from-course');

            // Quick Review management
            Route::post('/{courseId}/reviews', [AdminCourseController::class, 'addQuickReview'])->name('add-review');

            // Quiz management
            Route::get('/{courseId}/quizzes', [QuizController::class, 'adminIndex'])->name('quizzes');
            Route::post('/{courseId}/quizzes', [QuizController::class, 'adminStore'])->name('quiz.store');
            Route::get('/quiz/{quizId}', [QuizController::class, 'show'])->name('quiz.show');
            Route::put('/quiz/{quizId}', [QuizController::class, 'adminUpdate'])->name('quiz.update');
            Route::delete('/quiz/{quizId}', [QuizController::class, 'adminDestroy'])->name('quiz.destroy');
            Route::get('/{courseId}/quiz-statistics', [QuizController::class, 'getCourseStatistics'])->name('quiz.statistics');

            // Quiz Questions management
            Route::get('/quiz/{quizId}/questions', [App\Http\Controllers\QuizQuestionController::class, 'index'])->name('quizzes.questions.index');
            Route::post('/quiz/{quizId}/questions', [App\Http\Controllers\QuizQuestionController::class, 'store'])->name('quizzes.questions.store');
            Route::get('/quiz-questions/{questionId}/edit', [App\Http\Controllers\QuizQuestionController::class, 'edit'])->name('quizzes.questions.edit');
            Route::put('/quiz-questions/{questionId}', [App\Http\Controllers\QuizQuestionController::class, 'update'])->name('quizzes.questions.update');
            Route::delete('/quiz/questions/{questionId}', [App\Http\Controllers\QuizQuestionController::class, 'destroy'])->name('quizzes.questions.destroy');
            Route::post('/quiz/{quizId}/questions/reorder', [App\Http\Controllers\QuizQuestionController::class, 'reorder'])->name('quizzes.questions.reorder');
            Route::post('/quiz-questions/update-order', [App\Http\Controllers\QuizQuestionController::class, 'updateOrder'])->name('quizzes.questions.update-order');

            // Course statistics and management
            Route::get('/{courseId}/history-statistics', [RiwayatTontonController::class, 'getCourseStatistics'])->name('history.statistics');
        });

        // Admin Feedback Management
        Route::prefix('admin/feedback')->name('admin.feedback.')->group(function () {
            Route::get('/', [FeedbackController::class, 'adminIndex'])->name('index');
            Route::post('/{feedback}/reply', [FeedbackController::class, 'reply'])->name('reply');
            Route::patch('/{feedback}/status', [FeedbackController::class, 'updateStatus'])->name('status');
        });

        // Admin Monitoring
        Route::prefix('admin/monitoring')->name('admin.monitoring.')->group(function () {
            Route::get('/', [AdminMonitoringController::class, 'index'])->name('index');
            Route::get('/course/{courseId}', [AdminMonitoringController::class, 'courseDetail'])->name('course-detail');
            Route::get('/student/{userId}', [AdminMonitoringController::class, 'studentDetail'])->name('student-detail');
        });

        // Quiz Migration (temporary routes for migration)
        Route::prefix('admin/quiz-migration')->name('admin.quiz-migration.')->group(function () {
            Route::get('/status', [App\Http\Controllers\QuizMigrationController::class, 'checkQuizMigrationStatus'])->name('status');
            Route::post('/migrate', [App\Http\Controllers\QuizMigrationController::class, 'migrateOldQuizzes'])->name('migrate');
        });
    });
});
