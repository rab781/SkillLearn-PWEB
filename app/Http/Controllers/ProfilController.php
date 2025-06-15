<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Kalau kamu pengen admin & customer bisa akses profilnya masing-masing
        // tinggal kasih pengecekan di blade nanti, tapi kalau profil hanya untuk customer:
        if (!in_array($user->role, ['CU', 'AD'])) {
            abort(403, 'Role tidak diizinkan.');
        }

        return view('profil.profile', compact('user'));
    }


public function edit()
{
    $user = Auth::user();
    return view('profil.edit', compact('user'));
}

public function update(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
    'nama_lengkap'         => 'required|string|max:255',
    'no_telepon'           => 'nullable|string|max:20',
    'username'             => 'required|string|max:255|unique:users,username,'.$user->users_id.',users_id',
    'password_lama'        => 'nullable|string',
    'password_baru'        => 'nullable|string|min:6',
    'konfirmasi_password'  => 'nullable|string|same:password_baru'
]);

    $user->nama_lengkap = $request->nama_lengkap;
    $user->no_telepon   = $request->no_telepon;
    $user->username     = $request->username;

    // Kalau password lama diisi, berarti user mau ganti password
   if ($request->filled('password_baru') || $request->filled('konfirmasi_password')) {
    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
    }

    $validated = $request->validate([
        'password_baru' => 'required|string|min:6',
        'konfirmasi_password' => 'required|string|same:password_baru'
    ]);

    $user->password = Hash::make($request->password_baru);
}


    $user->save();

    return redirect()->route('profil.show')->with('success', 'Profil berhasil diperbarui.');
}
}

