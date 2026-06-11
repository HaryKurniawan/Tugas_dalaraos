<x-filament-panels::page>
    <div class="flex flex-col md:flex-row gap-6">
        
        {{-- LEFT: PRODUCTS SECTION --}}
        <div class="w-full md:w-2/3 space-y-6">
            {{-- Tabs / Filter --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex gap-4">
                <button type="button" wire:click="$set('type', 'Dine-in')" class="px-4 py-2 rounded-lg font-bold {{ $type === 'Dine-in' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">Dine-in</button>
                <button type="button" wire:click="$set('type', 'Take-away')" class="px-4 py-2 rounded-lg font-bold {{ $type === 'Take-away' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">Take-away</button>
                <button type="button" wire:click="$set('type', 'Keringan')" class="px-4 py-2 rounded-lg font-bold {{ $type === 'Keringan' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }}">Barang Kering</button>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @if($type === 'Dine-in' || $type === 'Take-away')
                    @forelse($dailyStocks as $stock)
                        <div wire:click="addToCart('{{ $stock->id }}', 'Menu', '{{ $stock->menu->nama_menu }}', {{ $stock->menu->harga }})" 
                             class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 cursor-pointer hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition">
                            <h3 class="font-bold text-sm text-gray-900">{{ $stock->menu->nama_menu }}</h3>
                            <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($stock->menu->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">Sisa: {{ $stock->stok_sisa }}</p>
                        </div>
                    @empty
                        <div class="col-span-full p-6 text-center text-gray-500 bg-white rounded-xl border border-dashed">
                            Belum ada stok harian yang diinput untuk hari ini.
                        </div>
                    @endforelse
                @else
                    @forelse($keringans as $kering)
                        <div wire:click="addToCart('{{ $kering->id }}', 'Keringan', '{{ $kering->nama_barang }}', {{ $kering->harga_jual }})" 
                             class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 cursor-pointer hover:border-primary-500 hover:ring-1 hover:ring-primary-500 transition">
                            <h3 class="font-bold text-sm text-gray-900">{{ $kering->nama_barang }}</h3>
                            <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($kering->harga_jual, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">Sisa: {{ $kering->jumlah_stok }} {{ $kering->satuan }}</p>
                            @if($kering->tanggal_expired && \Carbon\Carbon::parse($kering->tanggal_expired)->isPast())
                                <p class="text-xs text-red-600 font-bold mt-1">⚠️ Kadaluarsa!</p>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full p-6 text-center text-gray-500 bg-white rounded-xl border border-dashed">
                            Stok barang kering kosong.
                        </div>
                    @endforelse
                @endif
            </div>
        </div>

        {{-- RIGHT: CART SECTION --}}
        <div class="w-full md:w-1/3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-full" style="min-height: 500px;">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                    <h2 class="font-bold text-gray-900 text-lg">Keranjang ({{ $type }})</h2>
                    <span class="bg-primary-100 text-primary-700 text-xs px-2 py-1 rounded-full font-bold">{{ count($cart) }} Item</span>
                </div>
                
                <div class="flex-1 p-4 overflow-y-auto space-y-3">
                    @forelse($cart as $key => $item)
                        <div class="flex justify-between items-start pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 leading-tight">{{ $item['name'] }}</h4>
                                <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-1 border">
                                    <button type="button" wire:click="updateQty('{{ $key }}', {{ $item['qty'] - 1 }})" class="w-6 h-6 flex items-center justify-center bg-white border rounded text-gray-600 hover:bg-gray-100">-</button>
                                    <span class="text-sm font-bold w-6 text-center">{{ $item['qty'] }}</span>
                                    <button type="button" wire:click="updateQty('{{ $key }}', {{ $item['qty'] + 1 }})" class="w-6 h-6 flex items-center justify-center bg-white border rounded text-gray-600 hover:bg-gray-100">+</button>
                                </div>
                                <div class="font-bold text-sm text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <x-heroicon-o-shopping-cart class="w-12 h-12 mb-2 opacity-50" />
                            <p>Keranjang masih kosong</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Metode Pembayaran</label>
                        <select wire:model="payment_method" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="Cash">Tunai (Cash)</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Transfer Bank</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-t border-gray-200 mt-2">
                        <span class="font-bold text-gray-600">Total Tagihan</span>
                        <span class="text-2xl font-bold text-primary-600">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                    </div>

                    <button type="button" wire:click="checkout" @if(empty($cart)) disabled @endif
                            class="w-full py-3 rounded-xl font-bold text-white transition-all {{ empty($cart) ? 'bg-gray-300 cursor-not-allowed' : 'bg-primary-600 hover:bg-primary-700 shadow-md' }}">
                        Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- Script for printing --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('printReceipt', (event) => {
                const id = event.id;
                if (id) {
                    window.open('/admin/print/struk/' + id, '_blank');
                }
            });
        });
    </script>
