@extends('layouts.pelanggan')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card animate-fade-in" style="max-width: 460px;">

        {{-- Logo --}}
        <div class="auth-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                <path d="M12 3v6"/>
            </svg>
        </div>

        <h1 class="auth-title">Buat Akun SIRAOS</h1>
        <p class="auth-subtitle">Isi data diri Anda untuk mulai memesan</p>

        {{-- Flash error --}}
        @if ($errors->any())
        <div class="alert-siraos alert-siraos-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 style="flex-shrink:0">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label" for="name">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Nama lengkap Anda"
                       value="{{ old('name') }}" required autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email & HP --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="email">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="email@contoh.com"
                           value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="phone">
                        No. WhatsApp <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="phone" id="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="08xxxxxxxxxx"
                           value="{{ old('phone') }}" required autocomplete="tel">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="form-label" for="password">
                    Password <span class="text-danger">*</span>
                </label>
                <div class="position-relative">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 karakter" required autocomplete="new-password">
                    <button type="button" onclick="togglePassword('password', this)"
                            style="position:absolute; right:12px; top:50%; transform:translateY(-50%);
                                   background:none; border:none; color:var(--siraos-muted); cursor:pointer; padding:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
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
                <div style="font-size: 10px; color: var(--siraos-muted-light); margin-top: 4px;">
                    Gunakan minimal 8 karakter dengan kombinasi huruf &amp; angka.
                </div>
            </div>

            <button type="submit" class="btn-checkout" id="btn_register">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" x2="19" y1="8" y2="14"/>
                    <line x1="22" x2="16" y1="11" y2="11"/>
                </svg>
                Daftar Sekarang
            </button>
        </form>

        <div class="auth-footer-text">
            Sudah punya akun?
            <a href="/login">&larr; Masuk di sini</a>
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
