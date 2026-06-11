<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PosPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Kasir / POS';
    protected static ?string $title = 'Sistem Kasir (POS)';
    protected string $view = 'filament.pages.pos-page';

    public $cart = [];
    public $type = 'Dine-in';
    public $payment_method = 'Cash';
    public $notes = '';
    public $lastTransactionId = null;

    public function addToCart($id, $type, $name, $price)
    {
        $key = $type . '_' . $id;
        
        if (isset($this->cart[$key])) {
            $this->cart[$key]['qty']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['qty'] * $price;
        } else {
            $this->cart[$key] = [
                'id' => $id,
                'type' => $type,
                'name' => $name,
                'price' => $price,
                'qty' => 1,
                'subtotal' => $price
            ];
        }
    }

    public function removeFromCart($key)
    {
        if (isset($this->cart[$key])) {
            unset($this->cart[$key]);
        }
    }

    public function updateQty($key, $qty)
    {
        if ($qty <= 0) {
            $this->removeFromCart($key);
        } elseif (isset($this->cart[$key])) {
            $this->cart[$key]['qty'] = $qty;
            $this->cart[$key]['subtotal'] = $qty * $this->cart[$key]['price'];
        }
    }

    public function getTotalProperty()
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        // DB Transaction
        \Illuminate\Support\Facades\DB::transaction(function () {
            $transaction = \App\Models\PosTransaction::create([
                'receipt_number' => 'POS-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'type' => $this->type,
                'total_amount' => $this->getTotalProperty(),
                'payment_method' => $this->payment_method,
                'notes' => $this->notes,
            ]);

            $this->lastTransactionId = $transaction->id;
            foreach ($this->cart as $item) {
                \App\Models\PosTransactionItem::create([
                    'pos_transaction_id' => $transaction->id,
                    'item_type' => $item['type'],
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'qty' => $item['qty'],
                    'price_per_item' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Deduct Stock
                if ($item['type'] === 'Menu') {
                    $stock = \App\Models\DailyStock::find($item['id']);
                    if ($stock) {
                        $stock->stok_terjual += $item['qty'];
                        $stock->stok_sisa -= $item['qty'];
                        $stock->save();
                    }
                } elseif ($item['type'] === 'Keringan') {
                    $stock = \App\Models\StokBarangKering::find($item['id']);
                    if ($stock) {
                        $stock->jumlah_stok -= $item['qty'];
                        $stock->save();
                    }
                }
            }
        });

        // Clear Cart
        $this->cart = [];
        $this->notes = '';

        \Filament\Notifications\Notification::make()
            ->title('Transaksi Berhasil Disimpan')
            ->success()
            ->send();

        // Trigger print script with new transaction
        $this->dispatch('printReceipt', id: $this->lastTransactionId);
    }

    protected function getViewData(): array
    {
        return [
            'dailyStocks' => \App\Models\DailyStock::where('tanggal', date('Y-m-d'))
                                ->where('status', 'Aktif')
                                ->where('stok_sisa', '>', 0)
                                ->with('menu')->get(),
            'keringans' => \App\Models\StokBarangKering::where('jumlah_stok', '>', 0)->get(),
        ];
    }
}
