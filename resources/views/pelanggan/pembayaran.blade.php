@extends('layouts.pelanggan')

@section('content')

<div class="container-fluid p-0 pb-5 mb-5 mx-auto" style="max-width: 600px;">
    
    {{-- Header with Back Button --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <h1 class="fw-bold fs-5 mb-0 text-gray-900">Pembayaran Pesanan</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm" style="font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info rounded-3 shadow-sm" style="font-size: 13px;">
            {{ session('info') }}
        </div>
    @endif

    <div class="bg-white rounded-3 shadow-sm mb-4 p-4 border text-center">
        <p class="text-muted mb-1" style="font-size: 13px;">No. Tiket Pesanan</p>
        <h3 class="fw-bold text-gray-900 mb-3">{{ $data['id'] }}</h3>
        
        <p class="text-muted mb-1" style="font-size: 13px;">Total yang harus dibayar</p>
        <h2 class="font-mono fw-bold text-siraos-primary-dark mb-0">Rp {{ number_format($data['total'], 0, ',', '.') }}</h2>
    </div>

    <form id="paymentForm" enctype="multipart/form-data">
        @csrf

        {{-- Bukti Transfer --}}
        <div class="bg-white rounded-3 shadow-sm mb-4 p-3 p-md-4 border">
            <p class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                Upload Bukti Pembayaran <span class="text-danger">*</span>
            </p>
            <div class="mb-3">
                <div class="alert alert-info py-3" style="font-size: 12px;">
                    <p class="mb-3">Silakan transfer sebesar <strong class="fs-6">Rp {{ number_format($data['total'], 0, ',', '.') }}</strong> ke salah satu rekening berikut:</p>
                    
                    <div class="d-flex align-items-center justify-content-between bg-white border rounded px-3 py-2 mb-2 shadow-sm">
                        <span><strong>BCA:</strong> 1234567890 <br><span class="text-muted" style="font-size:10px;">a.n Dalaraos</span></span>
                        <button type="button" class="btn btn-sm btn-light border fw-bold" style="font-size:11px;" onclick="copyText('1234567890', this)">Salin</button>
                    </div>

                    <div class="d-flex align-items-center justify-content-between bg-white border rounded px-3 py-2 shadow-sm">
                        <span><strong>Mandiri:</strong> 0987654321 <br><span class="text-muted" style="font-size:10px;">a.n Dalaraos</span></span>
                        <button type="button" class="btn btn-sm btn-light border fw-bold" style="font-size:11px;" onclick="copyText('0987654321', this)">Salin</button>
                    </div>
                </div>
            </div>
            
            <label class="form-label mb-1" style="font-size: 11px; font-weight: 700; color: var(--siraos-muted);">Pilih file bukti transfer (JPG/PNG)</label>
            <input class="form-control form-control-sm @error('bukti_transfer') is-invalid @enderror" type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" required>
            @error('bukti_transfer')
                <div class="invalid-feedback" style="font-size: 11px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" id="submitBtn" class="btn-siraos-primary w-100 py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
            Kirim Bukti Pembayaran
        </button>
    </form>

</div>

{{-- Success Modal --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-body text-center p-5">
        <div class="mb-4 d-flex justify-content-center">
            <div style="width: 80px; height: 80px; background: var(--siraos-primary-glass); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--siraos-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
        <h4 class="fw-bold mb-3">Pesanan Berhasil!</h4>
        <p class="text-muted mb-4" style="font-size: 14px;">
            Bukti pembayaran berhasil diunggah! Pesanan Anda telah dibuat dan sedang menunggu verifikasi admin.
        </p>
        <button type="button" id="btnLacakPesanan" class="btn-siraos-primary w-100 py-3 rounded-3 fw-bold" data-bs-dismiss="modal">
            Lihat Status Pesanan Saya
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');

    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable button, show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...';

        const formData = new FormData(this);

        fetch('/pembayaran', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Save to recent searches history
                let searches = JSON.parse(localStorage.getItem('siraos_tickets')) || [];
                if (!searches.includes(data.order_id)) {
                    searches.unshift(data.order_id);
                    if(searches.length > 5) searches.pop();
                    localStorage.setItem('siraos_tickets', JSON.stringify(searches));
                }

                // Set destination for the button
                document.getElementById('btnLacakPesanan').onclick = function() {
                    window.location.href = '/pesanan/' + data.order_id;
                };

                // Show modal
                var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                myModal.show();
            } else {
                alert(data.message || 'Terjadi kesalahan saat mengunggah.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Kirim Bukti Pembayaran';
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan koneksi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Kirim Bukti Pembayaran';
        });
    });
});

// Helper for Copy feature
function copyText(text, btnElement) {
    navigator.clipboard.writeText(text).then(function() {
        var originalText = btnElement.innerText;
        btnElement.innerText = 'Tersalin!';
        btnElement.classList.replace('btn-light', 'btn-success');
        btnElement.classList.add('text-white');
        setTimeout(function() {
            btnElement.innerText = originalText;
            btnElement.classList.replace('btn-success', 'btn-light');
            btnElement.classList.remove('text-white');
        }, 2000);
    }).catch(function(err) {
        alert('Gagal menyalin: ' + err);
    });
}
</script>
@endpush
