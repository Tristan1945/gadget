<x-guest-layout>
    <div class="text-center">
        <div class="auth-brand"><i class="fas fa-mobile-alt fa-lg"></i></div>
        <h3 class="font-weight-bold mb-1">Gadget Store</h3>
        <p class="text-muted small mb-4">Masuk untuk mengelola inventaris</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email" class="font-weight-bold">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="nama@email.com" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="password" class="font-weight-bold">Password</label>
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Masukkan password" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="custom-control custom-checkbox mb-4">
            <input id="remember" type="checkbox" name="remember" class="custom-control-input">
            <label for="remember" class="custom-control-label text-muted small">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="login-submit">
            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
        </button>
    </form>

    <div class="text-center mt-4 small">
        <span class="text-muted">Belum punya akun?</span>
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </div>
</x-guest-layout>
