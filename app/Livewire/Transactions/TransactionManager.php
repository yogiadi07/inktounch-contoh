<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\InventoryLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class TransactionManager extends Component
{
    public $transactions;
    public $products;
    public $showCreateForm = false;
    public $showDetailModal = false;
    public $selectedTransaction = null;
    
    // Filter properties
    public $start_date = '';
    public $end_date = '';
    
    // Form properties
    #[Rule('required|string|max:255')]
    public $cashier_name = '';
    
    #[Rule('required|string')]
    public $payment_method = 'cash';
    
    #[Rule('required|integer|min:0')]
    public $paid_amount = 0;
    
    public $cart = [];
    public $total = 0;
    public $change = 0;

    public function mount()
    {
        $this->loadData();
        $this->cashier_name = auth()->user()->name;
    }

    public function loadData()
    {
        $query = Transaction::with('details.product');
        
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('transaction_date', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }
        
        $this->transactions = $query->latest()->get();
        $this->products = Product::where('stock', '>', 0)->get();
    }

    public function applyFilter()
    {
        $this->loadData();
    }

    public function clearFilter()
    {
        $this->start_date = '';
        $this->end_date = '';
        $this->loadData();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Produk tidak tersedia atau stok habis.');
            return;
        }

        $existingIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($existingIndex !== false) {
            if ($this->cart[$existingIndex]['quantity'] < $product->stock) {
                $this->cart[$existingIndex]['quantity']++;
            } else {
                session()->flash('error', 'Stok tidak mencukupi.');
                return;
            }
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => 1,
                'stock' => $product->stock
            ];
        }

        $this->calculateTotal();
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        } else {
            $this->cart[$index]['quantity'] = min($quantity, $this->cart[$index]['stock']);
        }
        
        $this->calculateTotal();
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        
        $this->change = max(0, $this->paid_amount - $this->total);
    }

    public function updatedPaidAmount()
    {
        $this->calculateTotal();
    }

    public function save()
    {
        $this->validate([
            'cashier_name' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|integer|min:' . $this->total,
        ]);

        if (empty($this->cart)) {
            session()->flash('error', 'Keranjang belanja kosong.');
            return;
        }

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'no_invoice' => 'INV-' . strtoupper(uniqid()),
                'cashier_name' => $this->cashier_name,
                'transaction_date' => Carbon::now(),
                'total_price' => $this->total,
                'paid_amount' => $this->paid_amount,
                'change_amount' => $this->change,
                'payment_method' => $this->payment_method,
            ]);

            foreach ($this->cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $product->selling_price * $item['quantity'],
                ]);

                // Reduce stock
                $product->decrement('stock', $item['quantity']);

                // Log inventory
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'description' => 'Penjualan #' . $transaction->no_invoice,
                    'logged_at' => now(),
                ]);
            }

            DB::commit();
            $this->closeCreateForm();
            $this->loadData();
            session()->flash('success', 'Transaksi berhasil! Invoice: ' . $transaction->no_invoice);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDetail($transactionId)
    {
        $this->selectedTransaction = Transaction::with('details.product')->findOrFail($transactionId);
        $this->showDetailModal = true;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
    }

    private function resetForm()
    {
        $this->cashier_name = auth()->user()->name;
        $this->payment_method = 'cash';
        $this->paid_amount = 0;
        $this->cart = [];
        $this->total = 0;
        $this->change = 0;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.transactions.transaction-manager');
    }
}
