@extends('layouts.pelanggan')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card animate-fade-in">

        {{-- Logo --}}
        <div class="auth-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                <path d="M12 3v6"/>
            </svg>
        </div>

        <h1 class="auth-title">Selamat Datang</h1>
        <p class="auth-subtitle">Masuk ke akun SIRAOS Anda</p>

        {{-- Flash error --}}
        @if (session('error'))
        <div class="alert-siraos alert-siraos-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 style="flex-shrink:0">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email_or_phone">Email / No. Telepon</label>
                <input type="text" name="email_or_phone" id="email_or_phone"
                       class="form-control @error('email_or_phone') is-invalid @enderror"
                       placeholder="email@contoh.com atau 08xxx"
                       value="{{ old('email_or_phone') }}" required autocomplete="username">
                @error('email_or_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label d-flex justify-content-between align-items-center" for="password">
                    <span>Password</span>
                    <a href="#" class="text-decoration-none"
                       style="font-size: 10px; font-weight: 700; color: var(--siraos-primary);">
                        Lupa password?
                    </a>
                </label>
                <div class="position-relative">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password Anda" required autocomplete="current-password">
                    <button type="button" onclick="togglePassword('password', this)"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%);
                                   background:none; border:none; color:var(--siraos-muted); cursor:pointer; padding:0;">
                        <svg id="eye_password" xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-checkout" id="btn_login">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" x2="3" y1="12" y2="12"/>
                </svg>
                Masuk ke Akun
            </button>
        </form>

        <div class="auth-footer-text">
            Belum punya akun?
            <a href="/register">Daftar sekarang &rarr;</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const isPass = field.type === 'password';
    field.type = isPass ? 'text' : 'password';
    btn.querySelector('svg').innerHTML = isPass
        ? '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>'
        : '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
}
</script>
@endpush
