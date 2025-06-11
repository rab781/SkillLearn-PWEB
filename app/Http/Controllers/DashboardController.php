<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vidio;
use App\Models\Kategori;
use App\Models\Feedback;
use App\Models\Bookmark;
use App\Models\RiwayatTonton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Customer Dashboard
     */
    public function customerDashboard()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get user statistics
            $bookmarksCount = Bookmark::where('users_id', $user->users_id)->count();
            $feedbacksCount = Feedback::where('users_id', $user->users_id)->count();

            // Get recent bookmarks with error handling
            $recentBookmarks = Bookmark::with(['vidio' => function($query) {
                    $query->with('kategori');
                }])
                ->where('users_id', $user->users_id)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->filter(function($bookmark) {
                    return $bookmark->vidio !== null; // Filter out bookmarks with deleted videos
                });

            // Get recently watched videos (riwayat tonton) with error handling
            $recentlyWatched = RiwayatTonton::with(['video' => function($query) {
                    $query->with('kategori');
                }])
                ->byUser($user->users_id)
                ->recent(6)
                ->get()
                ->filter(function($history) {
                    return $history->video !== null; // Filter out history with deleted videos
                });

            // If no watch history, get latest videos as fallback
            if ($recentlyWatched->count() === 0) {
                $recentlyWatched = Vidio::with('kategori')
                    ->whereNotNull('nama')
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get()
                    ->map(function($video) {
                        // Transform to match watch history structure
                        return (object)[
                            'video' => $video,
                            'waktu_ditonton' => $video->created_at,
                            'persentase_progress' => 0.00
                        ];
                    });
            }

            // Get categories with video count
            $categories = Kategori::withCount('vidios')
                ->having('vidios_count', '>', 0) // Only categories with videos
                ->orderBy('vidios_count', 'desc')
                ->take(8)
                ->get();

            // Add default categories if none exist
            if ($categories->count() === 0) {
                $categories = collect([
                    (object)[
                        'kategori_id' => 1,
                        'kategori' => 'Programming',
                        'vidios_count' => 0
                    ],
                    (object)[
                        'kategori_id' => 2,
                        'kategori' => 'Design',
                        'vidios_count' => 0
                    ],
                    (object)[
                        'kategori_id' => 3,
                        'kategori' => 'Business',
                        'vidios_count' => 0
                    ],
                    (object)[
                        'kategori_id' => 4,
                        'kategori' => 'Marketing',
                        'vidios_count' => 0
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->users_id,
                    'name' => $user->nama_lengkap,
                    'email' => $user->email
                ],
                'stats' => [
                    'bookmarks_count' => $bookmarksCount,
                    'feedbacks_count' => $feedbacksCount,
                ],
                'recent_bookmarks' => $recentBookmarks->values(),
                'recently_watched' => $recentlyWatched->values(),
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat dashboard',
                'stats' => [
                    'bookmarks_count' => 0,
                    'feedbacks_count' => 0,
                ],
                'recent_bookmarks' => [],
                'recently_watched' => [],
                'categories' => []
            ], 500);
        }
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        // Get overall statistics
        $totalUsers = User::where('role', 'CU')->count();
        $totalVideos = Vidio::count();
        $totalCategories = Kategori::count();
        $totalFeedbacks = Feedback::count();
        $totalBookmarks = Bookmark::count();

        // Get recent activities
        $recentFeedbacks = Feedback::with(['user', 'vidio'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Get most popular videos
        $popularVideos = Vidio::with('kategori')
            ->orderBy('jumlah_tayang', 'desc')
            ->take(10)
            ->get();

        // Get user registration stats (last 7 days)
        $userRegistrations = User::where('role', 'CU')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get video views stats (last 7 days)
        $videoViews = Vidio::select(
                DB::raw('DATE(updated_at) as date'),
                DB::raw('SUM(jumlah_tayang) as total_views')
            )
            ->where('updated_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get categories with video count
        $categoriesStats = Kategori::withCount('vidios')
            ->orderBy('vidios_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_users' => $totalUsers,
                'total_videos' => $totalVideos,
                'total_categories' => $totalCategories,
                'total_feedbacks' => $totalFeedbacks,
                'total_bookmarks' => $totalBookmarks,
            ],
            'recent_feedbacks' => $recentFeedbacks,
            'popular_videos' => $popularVideos,
            'user_registrations' => $userRegistrations,
            'video_views' => $videoViews,
            'categories_stats' => $categoriesStats
        ]);
    }

    /**
     * Users Report (Admin only)
     */
    public function usersReport()
    {
        $users = User::where('role', 'CU')
            ->withCount(['feedbacks', 'bookmarks'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $userStats = [
            'total_users' => User::where('role', 'CU')->count(),
            'users_today' => User::where('role', 'CU')
                ->whereDate('created_at', today())
                ->count(),
            'users_this_week' => User::where('role', 'CU')
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
            'users_this_month' => User::where('role', 'CU')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'users' => $users,
            'stats' => $userStats
        ]);
    }

    /**
     * Videos Report (Admin only)
     */
    public function videosReport()
    {
        $videos = Vidio::with('kategori')
            ->withCount(['feedbacks', 'bookmarks'])
            ->orderBy('jumlah_tayang', 'desc')
            ->paginate(20);

        $videoStats = [
            'total_videos' => Vidio::count(),
            'total_views' => Vidio::sum('jumlah_tayang'),
            'videos_today' => Vidio::whereDate('created_at', today())->count(),
            'videos_this_week' => Vidio::where('created_at', '>=', now()->startOfWeek())->count(),
            'videos_this_month' => Vidio::where('created_at', '>=', now()->startOfMonth())->count(),
            'most_viewed_video' => Vidio::orderBy('jumlah_tayang', 'desc')->first(),
        ];

        return response()->json([
            'success' => true,
            'videos' => $videos,
            'stats' => $videoStats
        ]);
    }

    /**
     * Customer Statistics for video page
     */
    public function customerStats()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get customer specific stats
        $bookmarksCount = Bookmark::where('users_id', $user->users_id)->count();
        $feedbacksCount = Feedback::where('users_id', $user->users_id)->count();

        // Calculate learning streak (simplified - count consecutive days with activity)
        $recentActivity = collect([]);

        // Get recent bookmarks dates
        $recentBookmarks = Bookmark::where('users_id', $user->users_id)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->pluck('created_at')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->unique();

        // Get recent feedback dates
        $recentFeedbacks = Feedback::where('users_id', $user->users_id)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->pluck('created_at')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->unique();

        // Merge activity dates
        $activityDates = $recentBookmarks->merge($recentFeedbacks)->unique()->sort()->values();

        // Calculate streak
        $streak = 0;
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        if ($activityDates->contains($today) || $activityDates->contains($yesterday)) {
            $streak = 1;
            $currentDate = $activityDates->contains($today) ? $today : $yesterday;

            for ($i = 1; $i < $activityDates->count(); $i++) {
                $checkDate = now()->subDays($i)->format('Y-m-d');
                if ($activityDates->contains($checkDate)) {
                    $streak++;
                } else {
                    break;
                }
            }
        }

        // Get unique videos interacted with (approximation of videos watched)
        $videosWatched = Bookmark::where('users_id', $user->users_id)
            ->distinct('vidio_vidio_id')
            ->count('vidio_vidio_id');

        $feedbackVideos = Feedback::where('users_id', $user->users_id)
            ->distinct('vidio_vidio_id')
            ->count('vidio_vidio_id');

        $totalVideosWatched = max($videosWatched, $feedbackVideos);

        return response()->json([
            'success' => true,
            'stats' => [
                'videos_watched' => $totalVideosWatched,
                'learning_streak' => $streak,
                'total_bookmarks' => $bookmarksCount,
                'total_feedbacks' => $feedbacksCount
            ]
        ]);
    }

    /**
     * Record watch history when user watches a video
     */
    public function recordWatchHistory(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'video_id' => 'required|exists:vidio,vidio_id',
                'duration' => 'nullable|integer|min:0',
                'progress' => 'nullable|numeric|min:0|max:100'
            ]);

            $watchHistory = RiwayatTonton::recordWatch(
                $user->users_id,
                $request->video_id,
                $request->duration ?? 0,
                $request->progress ?? 0
            );

            // Also increment view count for the video
            $video = Vidio::find($request->video_id);
            if ($video) {
                $video->increment('jumlah_tayang');
            }

            return response()->json([
                'success' => true,
                'message' => 'Riwayat tonton berhasil dicatat',
                'data' => $watchHistory
            ]);

        } catch (\Exception $e) {
            Log::error('Watch history recording error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat riwayat tonton'
            ], 500);
        }
    }
}
