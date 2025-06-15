@extends('layouts.app')

@section('content')
<div class="profil-page">
    <div class="bubble small bubble-1"></div>
    <div class="bubble medium bubble-2"></div>
    <div class="bubble small bubble-3"></div>

<div class="container mx-auto max-w-xl mt-10">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="avatar-circle">
            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-2xl font-bold mb-1">{{ $user->nama_lengkap }}</h2>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                    {{ $user->role === 'AD' ? 'bg-indigo-100 text-indigo-700' : 'bg-green-100 text-green-700' }}">
                    {{ $user->role === 'AD' ? 'Admin' : 'Customer' }}
                </span>
            </div>
        </div>

<div class="profile-section">
    <div class="form-group mb-3">
        <label class="form-label">Nama Lengkap</label>
        <div class="form-control-static">{{ $user->nama_lengkap }}</div>
    </div>

 <div class="form-group mb-3">
        <label class="form-label">Username</label>
        <div class="form-control-static">{{ $user->username }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">Email</label>
        <div class="form-control-static">{{ $user->email }}</div>
    </div>

    <div class="form-group mb-3">
        <label class="form-label">No Telepon</label>
        <div class="form-control-static">{{ $user->no_telepon }}</div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('profil.edit') }}" class="btn btn-gradient w-50 me-2">Edit Profil</a>
        <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg shadow">Kembali</a>
    </div>

</div>

@endsection
