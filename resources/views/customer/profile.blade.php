@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Profil Pelanggan</h2>
    <p><strong>Nama:</strong> {{ $pelanggan->nama_lengkap }}</p>
    <p><strong>Email:</strong> {{ $pelanggan->email }}</p>
    <p><strong>Telepon:</strong> {{ $pelanggan->no_telepon }}</p>
    <p><strong>Username:</strong> {{ $pelanggan->username }}</p>

</div>
@endsection
