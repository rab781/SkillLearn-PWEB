<?php

namespace App\Http\Controllers;

use App\Models\ProfilPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfilPelangganController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role !== 'CU') {
            abort(403, 'Hanya Customer User yang bisa akses profil ini.');
        }

        // $pelanggan = ProfilPelanggan::findOrFail($id);
        $pelanggan = Auth::user();

        if ($user->id != $pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('customer.profile', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = ProfilPelanggan::findOrFail($id);
        return view('customer.edit_profile', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = ProfilPelanggan::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:12|unique:profil_pelanggans,no_telepon,' . $id,
            'username' => 'required|string|max:84|unique:profil_pelanggans,username,' . $id,
            'email' => 'required|string|email|max:84|unique:profil_pelanggans,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except('password', 'password_confirmation');

        // Jika ada password baru, hash dan simpan
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.show', $pelanggan->id)->with('success', 'Profil berhasil diperbarui');
    }
}
