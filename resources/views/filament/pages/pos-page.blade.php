<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- LEFT: PRODUCTS SECTION --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Tabs / Filter --}}
            <x-filament::section>
                <div class="flex flex-wrap gap-4 items-center">
                    <x-filament::button 
                        color="{{ $activeTab === 'Menu' ? 'primary' : 'gray' }}"
                        wire:click="$set('activeTab', 'Menu')"
                    >
                        Menu Makanan
                    </x-filament::button>
                    
                    <x-filament::button 
                        color="{{ $activeTab === 'Keringan' ? 'primary' : 'gray' }}"
                        wire:click="$set('activeTab', 'Keringan')"
                    >
                        Barang Kering
                    </x-filament::button>
                </div>
            </x-filament::section>

            {{-- Products Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                @if($activeTab === 'Menu')
                    @forelse($menus as $menu)
                        <div wire:click="addToCart('{{ $menu->id }}', 'Menu', '{{ $menu->nama_menu }}', {{ $menu->harga }})" 
                             class="fi-ta-record cursor-pointer hover:ring-1 hover:ring-primary-500 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 transition duration-200">
                            <h3 class="font-semibold text-sm text-gray-950 dark:text-white">{{ $menu->nama_menu }}</h3>
                            <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $menu->kategori }}</p>
                        </div>
                    @empty
                        <div class="col-span-full p-6 text-center text-gray-500 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 border-dashed border border-gray-300 dark:border-gray-700">
                            Belum ada menu yang didaftarkan.
                        </div>
                    @endforelse
                @else
                    @forelse($keringans as $kering)
                        <div wire:click="addToCart('{{ $kering->id }}', 'Keringan', '{{ $kering->nama_barang }}', {{ $kering->harga_jual }})" 
                             class="fi-ta-record cursor-pointer hover:ring-1 hover:ring-primary-500 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-4 transition duration-200">
                            <h3 class="font-semibold text-sm text-gray-950 dark:text-white">{{ $kering->nama_barang }}</h3>
                            <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($kering->harga_jual, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-2">Sisa: {{ $kering->jumlah_stok }} {{ $kering->satuan }}</p>
                            @if($kering->tanggal_expired && \Carbon\Carbon::parse($kering->tanggal_expired)->isPast())
                                <x-filament::badge color="danger" class="mt-2">Kadaluarsa!</x-filament::badge>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full p-6 text-center text-gray-500 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 border-dashed border border-gray-300 dark:border-gray-700">
                            Stok barang kering kosong.
                        </div>
                    @endforelse
                @endif
            </div>
        </div>

        {{-- RIGHT: CART SECTION --}}
        <div class="md:col-span-1">
            <x-filament::section class="h-full flex flex-col" heading="Keranjang Belanja">
                <x-slot name="headerEnd">
                    <x-filament::badge color="primary">{{ count($cart) }} Item</x-filament::badge>
                </x-slot>

                <div class="flex-1 overflow-y-auto max-h-[400px] mb-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-gray-200 dark:border-white/10">
                                <tr>
                                    <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">Item</th>
                                    <th class="pb-2 font-medium text-gray-500 dark:text-gray-400 text-center">Qty</th>
                                    <th class="pb-2 font-medium text-gray-500 dark:text-gray-400 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @forelse($cart as $key => $item)
                                    <tr>
                                        <td class="py-3 pr-2 align-middle">
                                            <div class="font-medium text-gray-950 dark:text-white leading-snug">{{ $item['name'] }}</div>
                                            <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                                        </td>
                                        <td class="py-3 px-2 text-center align-middle">
                                            <div class="inline-flex items-center gap-2 bg-gray-50 dark:bg-white/5 rounded-lg p-1 ring-1 ring-gray-950/10 dark:ring-white/20">
                                                <button type="button" wire:click="updateQty('{{ $key }}', {{ $item['qty'] - 1 }})" class="w-6 h-6 flex items-center justify-center bg-white dark:bg-gray-800 rounded shadow-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 font-bold">-</button>
                                                <span class="text-sm font-bold w-6 text-center dark:text-white">{{ $item['qty'] }}</span>
                                                <button type="button" wire:click="updateQty('{{ $key }}', {{ $item['qty'] + 1 }})" class="w-6 h-6 flex items-center justify-center bg-white dark:bg-gray-800 rounded shadow-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 font-bold">+</button>
                                            </div>
                                        </td>
                                        <td class="py-3 pl-2 text-right align-middle font-bold text-gray-950 dark:text-white whitespace-nowrap">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-10 text-center text-gray-400 dark:text-gray-500">
                                            <x-heroicon-o-shopping-cart class="mx-auto mb-3 opacity-50" style="width: 3rem; height: 3rem;" />
                                            <p class="text-sm">Keranjang masih kosong</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-white/10 space-y-4">
                    
                    {{-- Tipe Transaksi --}}
                    <div>
                        <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white mb-2 block">Tipe Pemesanan</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="$set('transactionType', 'Dine-in')" class="py-2 text-sm font-semibold rounded-lg ring-1 ring-inset transition-colors {{ $transactionType === 'Dine-in' ? 'bg-primary-50 text-primary-600 ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30' : 'bg-white text-gray-900 ring-gray-300 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:ring-white/20' }}">
                                Dine-in
                            </button>
                            <button type="button" wire:click="$set('transactionType', 'Take-away')" class="py-2 text-sm font-semibold rounded-lg ring-1 ring-inset transition-colors {{ $transactionType === 'Take-away' ? 'bg-primary-50 text-primary-600 ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30' : 'bg-white text-gray-900 ring-gray-300 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:ring-white/20' }}">
                                Take-away
                            </button>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="text-sm font-medium leading-6 text-gray-950 dark:text-white mb-2 block">Metode Pembayaran</label>
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model="payment_method">
                                <option value="Cash">Tunai (Cash)</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Transfer">Transfer Bank</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-t border-gray-200 dark:border-white/10 mt-2">
                        <span class="font-semibold text-gray-950 dark:text-white">Total Tagihan</span>
                        <span class="text-xl font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                    </div>

                    <x-filament::button 
                        wire:click="checkout" 
                        color="success" 
                        size="lg" 
                        class="w-full"
                        :disabled="empty($cart)"
                    >
                        Proses Pembayaran
                    </x-filament::button>
                </div>
            </x-filament::section>
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
</x-filament-panels::page>
