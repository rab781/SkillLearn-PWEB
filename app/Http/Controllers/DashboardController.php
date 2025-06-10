<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vidio;
use App\Models\Kategori;
use App\Models\Feedback;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Customer Dashboard
     */
    public function customerDashboard()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get user statistics
        $bookmarksCount = Bookmark::where('users_id', $user->users_id)->count();
        $feedbacksCount = Feedback::where('users_id', $user->users_id)->count();
        
        // Get recent bookmarks
        $recentBookmarks = Bookmark::with('vidio.kategori')
            ->where('users_id', $user->users_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get popular videos
        $popularVideos = Vidio::with('kategori')
            ->orderBy('jumlah_tayang', 'desc')
            ->take(6)
            ->get();
        
        // Get latest videos
        $latestVideos = Vidio::with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Get categories with video count
        $categories = Kategori::withCount('vidios')->get();
        
        return response()->json([
            'success' => true,
            'user' => $user,
            'stats' => [
                'bookmarks_count' => $bookmarksCount,
                'feedbacks_count' => $feedbacksCount,
            ],
            'recent_bookmarks' => $recentBookmarks,
            'popular_videos' => $popularVideos,
            'latest_videos' => $latestVideos,
            'categories' => $categories
        ]);
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
}
