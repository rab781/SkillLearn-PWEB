<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Registrasi customer baru
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:12|unique:users',
            'username' => 'required|string|max:84|unique:users',
            'email' => 'required|string|email|max:84|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'no_telepon' => $request->no_telepon,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'CU' // Default customer
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 201);
    }

    /**
     * Login user (admin/customer)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // bisa username atau email
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah login menggunakan email atau username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
            /** @var User $user */
            $user = Auth::user();

            // Create token for API access
            $token = $user->createToken('auth-token')->plainTextToken;

            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'user' => $user,
                    'token' => $token,
                    'redirect' => route('dashboard.admin')
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'user' => $user,
                    'token' => $token,
                    'redirect' => route('dashboard')
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Username/email atau password salah'
        ], 401);
    }

    /**
     * Login user khusus untuk web (dengan redirect langsung)
     */
    public function loginWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah login menggunakan email atau username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt login
        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            // Handle JSON request (API)
            if ($request->wantsJson()) {
                $token = $user->createToken('auth-token')->plainTextToken;
                $redirectUrl = $user->role === 'AD' ? route('dashboard.admin') : route('dashboard');

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'user' => $user,
                    'token' => $token,
                    'redirect' => $redirectUrl
                ]);
            }

                // Handle web form request (normal browser request)
            // Get intended URL if any, otherwise use default dashboard
            $intended = redirect()->intended($user->role === 'AD' ? route('dashboard.admin') : route('dashboard'));

            // Clear any intended URL to prevent issues on next login
            $request->session()->forget('url.intended');

            return $intended;
        }

        // Login failed
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Username/email atau password salah'
            ], 401);
        }

        return back()->withErrors([
            'login' => 'Username/email atau password salah.'
        ])->withInput();
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // For API requests (with token)
        if ($request->bearerToken()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        }

        // For web requests (with session)
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Return appropriate response based on request type
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        }

        // Redirect to home page for web requests
        return redirect('/')->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Get user profile
     */
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'sometimes|string|max:100',
            'no_telepon' => 'sometimes|string|max:12|unique:users,no_telepon,' . $user->users_id . ',users_id',
            'username' => 'sometimes|string|max:84|unique:users,username,' . $user->users_id . ',users_id',
            'email' => 'sometimes|string|email|max:84|unique:users,email,' . $user->users_id . ',users_id',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['nama_lengkap', 'no_telepon', 'username', 'email']);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diupdate',
            'user' => $user
        ]);
    }
}
