@extends('layouts.app')

@section('content')
<form action="{{ route('profil.update') }}" method="POST" autocomplete="off">
    @csrf
    @method('PUT')

    <div class="edit-page">
        <div class="bubble small bubble-1"></div>
        <div class="bubble medium bubble-2"></div>


    <div class="container mx-auto max-w-xl mt-10">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center mb-6">
                <div class="avatar-circle mr-6">
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

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        class="form-control-static" required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="form-control-static" required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="text" value="{{ $user->email }}"
                        class="form-control-static" readonly>
                </div>
                <div>
                    <label class="block font-semibold mb-1">No Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                        class="form-control-static">
                </div>
            </div>

            <hr class="my-6">

            <h5 class="font-bold mb-3 text-blue-700">Ganti Password 🔒</h5>
            <div class="space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Password Lama</label>
                    <input type="password" name="password_lama" autocomplete="current-password"
                        class="form-control-static">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Password Baru</label>
                    <input type="password" name="password_baru" autocomplete="new-password"
                        class="form-control-static">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi_password" autocomplete="new-password"
                        class="form-control-static">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="submit"
                    class="btn btn-gradient w-50 me-2">
                    Simpan Perubahan
                </button>
                <a href="{{ route('profil.show') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2 rounded-lg shadow">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
