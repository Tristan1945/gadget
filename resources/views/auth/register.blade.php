<x-guest-layout>
    <div class="text-center">
        <div class="auth-brand"><i class="fas fa-user-plus fa-lg"></i></div>
        <h3 class="font-weight-bold mb-1">Buat Akun</h3>
        <p class="text-muted small mb-4">Daftar untuk melihat inventaris gadget</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name" class="font-weight-bold">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="email" class="font-weight-bold">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password" class="font-weight-bold">Password</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password_confirmation" class="font-weight-bold">Konfirmasi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-user-check mr-2"></i>Daftar
        </button>
    </form>

    <div class="text-center mt-4 small">
        <span class="text-muted">Sudah punya akun?</span>
        <a href="{{ route('login') }}">Masuk</a>
    </div>
</x-guest-layout>
