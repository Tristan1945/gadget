@extends('layouts.app')

@section('title', 'Profil')
@section('page_heading', 'Pengaturan Profil')

@section('content')
<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="font-weight-bold mb-1">Informasi Akun</h5>
                <p class="text-muted small mb-0">Perbarui nama dan alamat email Anda.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Nama</label>
                        <input id="name" name="name" type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email" class="font-weight-bold">Email</label>
                        <input id="email" name="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="font-weight-bold mb-1">Perbarui Password</h5>
                <p class="text-muted small mb-0">Gunakan password yang kuat dan unik.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="current_password" class="font-weight-bold">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Password Baru</label>
                        <input id="password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bold">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-key mr-2"></i>Perbarui Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
