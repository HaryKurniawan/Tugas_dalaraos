@extends('layouts.admin')

@section('title', 'Kasir (POS)')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kasir (Point of Sale)</h1>
        <p class="text-gray-500">Pemesanan di tempat (Walk-in)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="pos-app">
    <!-- Menu Kiri -->
    <div class="lg:col-span-2 flex flex-col h-[calc(100vh-200px)]">
        <!-- Kategori Filter -->
        <div class="bg-white rounded-t-lg shadow-sm border border-gray-200 border-b-0 p-4 overflow-x-auto">
            <div class="flex gap-2 min-w-max">
                <a href="{{ route('admin.pos.index') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ !$kategori ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua</a>
                @foreach($semuaKategori as $kat)
                <a href="{{ route('admin.pos.index', ['kategori' => $kat]) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ $kategori == $kat ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $kat }}</a>
                @endforeach
            </div>
        </div>

        <!-- Daftar Menu -->
        <div class="bg-gray-50 rounded-b-lg shadow-inner border border-gray-200 p-4 flex-1 overflow-y-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($menus as $menu)
                @php $sisaStok = $stocks[$menu->id] ?? 0; @endphp
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden {{ $sisaStok > 0 ? 'cursor-pointer hover:border-green-500 hover:shadow-md transition group' : 'opacity-60 cursor-not-allowed' }}" 
                     @if($sisaStok > 0) onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->nama_produk) }}', {{ $menu->harga }}, {{ $sisaStok }})" @endif>
                    <div class="h-32 bg-gray-200 relative">
                        @if($menu->gambar)
                        <img src="{{ str_starts_with($menu->gambar, 'http') ? $menu->gambar : Storage::url($menu->gambar) }}" alt="{{ $menu->nama_produk }}" class="w-full h-full object-cover {{ $sisaStok > 0 ? 'group-hover:scale-105' : '' }} transition-transform duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                        
                        @if($sisaStok <= 0)
                        <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                            <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">Habis</span>
                        </div>
                        @else
                        <div class="absolute top-2 right-2 bg-white/90 text-gray-800 text-xs font-bold px-2 py-1 rounded shadow-sm">
                            Stok: {{ $sisaStok }}
                        </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-bold text-gray-900 truncate" title="{{ $menu->nama_produk }}">{{ $menu->nama_produk }}</h3>
                        <p class="text-green-600 font-medium text-sm mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-10 text-center text-gray-500">
                    Tidak ada menu di kategori ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Keranjang Kanan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col h-[calc(100vh-200px)]">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
            <h2 class="text-lg font-bold text-gray-900">Keranjang</h2>
            <button onclick="clearCart()" class="text-sm text-red-600 hover:text-red-700 font-medium">Kosongkan</button>
        </div>

        <!-- Items List -->
        <div class="p-4 flex-1 overflow-y-auto" id="cart-items">
            <!-- JavaScript will populate this -->
            <div class="h-full flex items-center justify-center text-gray-400 text-sm flex-col" id="empty-cart-msg">
                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Belum ada menu di keranjang
            </div>
        </div>

        <!-- Rincian & Checkout -->
        <div class="border-t border-gray-200 p-4 bg-gray-50 rounded-b-lg">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-500 text-sm">Total Item:</span>
                <span class="font-medium text-gray-900" id="cart-count">0</span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-900 font-bold text-lg">Total Bayar:</span>
                <span class="font-bold text-2xl text-green-600" id="cart-total">Rp 0</span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="setPaymentMethod('Tunai')" id="btn-tunai" class="py-2 border-2 border-green-500 bg-green-50 text-green-700 rounded-md text-sm font-medium transition">Tunai</button>
                    <button type="button" onclick="setPaymentMethod('Transfer/QRIS')" id="btn-transfer" class="py-2 border border-gray-300 bg-white text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition">Transfer/QRIS</button>
                </div>
            </div>

            <div class="mb-4">
                <input type="text" id="transaction-notes" placeholder="Catatan transaksi (opsional)..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border">
            </div>

            <button onclick="processCheckout()" id="btn-checkout" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow disabled:opacity-50 disabled:cursor-not-allowed transition">
                Proses Pembayaran
            </button>
        </div>
    </div>
</div>

<!-- Modal Berhasil -->
<div id="success-modal" class="fixed z-50 inset-0 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Transaksi Berhasil</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Struk berhasil dicatat. Nomor Struk: <strong id="success-receipt"></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="finishTransaction()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Selesai & Kasir Baru
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];
    let paymentMethod = 'Tunai';

    function addToCart(id, name, price, maxStock) {
        let existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty < maxStock) {
                existing.qty++;
            } else {
                alert('Stok tidak mencukupi! Sisa stok hanya ' + maxStock);
            }
        } else {
            if (maxStock > 0) {
                cart.push({ id, name, price, qty: 1, maxStock });
            }
        }
        renderCart();
    }

    function updateQty(id, delta) {
        let existing = cart.find(item => item.id === id);
        if (existing) {
            let newQty = existing.qty + delta;
            if (newQty > existing.maxStock) {
                alert('Stok tidak mencukupi! Sisa stok hanya ' + existing.maxStock);
                return;
            }
            existing.qty = newQty;
            if (existing.qty <= 0) {
                cart = cart.filter(item => item.id !== id);
            }
        }
        renderCart();
    }

    function clearCart() {
        if(cart.length > 0 && confirm('Yakin ingin mengosongkan keranjang?')) {
            cart = [];
            renderCart();
        }
    }

    function setPaymentMethod(method) {
        paymentMethod = method;
        if(method === 'Tunai') {
            document.getElementById('btn-tunai').className = "py-2 border-2 border-green-500 bg-green-50 text-green-700 rounded-md text-sm font-medium transition";
            document.getElementById('btn-transfer').className = "py-2 border border-gray-300 bg-white text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition";
        } else {
            document.getElementById('btn-transfer').className = "py-2 border-2 border-green-500 bg-green-50 text-green-700 rounded-md text-sm font-medium transition";
            document.getElementById('btn-tunai').className = "py-2 border border-gray-300 bg-white text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition";
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('empty-cart-msg');
        const btnCheckout = document.getElementById('btn-checkout');
        
        let total = 0;
        let count = 0;

        if (cart.length === 0) {
            container.innerHTML = '';
            container.appendChild(emptyMsg);
            emptyMsg.style.display = 'flex';
            btnCheckout.disabled = true;
        } else {
            emptyMsg.style.display = 'none';
            btnCheckout.disabled = false;
            let html = '<ul class="divide-y divide-gray-200">';
            
            cart.forEach(item => {
                let subtotal = item.qty * item.price;
                total += subtotal;
                count += item.qty;

                html += `
                <li class="py-3 flex justify-between items-center">
                    <div class="flex-1 pr-2">
                        <p class="text-sm font-medium text-gray-900">${item.name}</p>
                        <p class="text-xs text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <div class="flex items-center border border-gray-300 rounded mb-1">
                            <button onclick="updateQty(${item.id}, -1)" class="px-2 py-0.5 text-gray-600 hover:bg-gray-100">-</button>
                            <span class="px-2 text-sm font-bold w-8 text-center">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="px-2 py-0.5 text-gray-600 hover:bg-gray-100">+</button>
                        </div>
                        <p class="text-sm font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</p>
                    </div>
                </li>`;
            });
            html += '</ul>';
            container.innerHTML = html;
        }

        document.getElementById('cart-count').innerText = count;
        document.getElementById('cart-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        btnCheckout.dataset.total = total;
    }

    async function processCheckout() {
        if (cart.length === 0) return;

        const btnCheckout = document.getElementById('btn-checkout');
        const totalAmount = btnCheckout.dataset.total;
        const notes = document.getElementById('transaction-notes').value;

        btnCheckout.disabled = true;
        btnCheckout.innerText = 'Memproses...';

        try {
            const response = await fetch("{{ route('admin.pos.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: cart,
                    payment_method: paymentMethod,
                    notes: notes,
                    total_amount: totalAmount
                })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('success-receipt').innerText = data.receipt_number;
                document.getElementById('success-modal').classList.remove('hidden');
            } else {
                alert('Terjadi kesalahan saat memproses transaksi.');
                btnCheckout.disabled = false;
                btnCheckout.innerText = 'Proses Pembayaran';
            }
        } catch (error) {
            console.error(error);
            alert('Kesalahan jaringan. Gagal memproses transaksi.');
            btnCheckout.disabled = false;
            btnCheckout.innerText = 'Proses Pembayaran';
        }
    }

    function finishTransaction() {
        cart = [];
        document.getElementById('transaction-notes').value = '';
        renderCart();
        document.getElementById('success-modal').classList.add('hidden');
    }

    // Initialize
    renderCart();
</script>
@endsection
