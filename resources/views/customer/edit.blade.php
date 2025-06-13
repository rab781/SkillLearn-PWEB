@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Profil</h2>

    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ $pelanggan->nama_lengkap }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $pelanggan->email }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" name="no_telepon" value="{{ $pelanggan->no_telepon }}">
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="{{ $pelanggan->username }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (Opsional)</label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
