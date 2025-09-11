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
    public $products = [];
    public $showCreateForm = false;
    public $showDetailModal = false;
    public $selectedTransaction = null;
    
    // Filter properties
    public $start_date = '';
    public $end_date = '';
    
    // Product search properties
    public $search = '';
    public $selectedCategory = '';
    public $categories = [];
    public $cart = [];
    public $quantities = [];
    public $total = 0;
    public $paid_amount = 0;
    public $change = 0;
    public $cashier_name = '';
    public $payment_method = 'cash';

    public function mount()
    {
        $this->loadData();
        $this->cashier_name = auth()->user()->name;
        $this->categories = \App\Models\Category::all();
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
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $query = Product::where('stock', '>', 0);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }
        
        $this->products = $query->orderBy('name')->get();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function updatedSelectedCategory()
    {
        $this->loadProducts();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->loadProducts();
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

        // Get quantity from input or default to 1
        $quantity = $this->quantities[$productId] ?? 1;
        
        if ($quantity <= 0) {
            session()->flash('error', 'Jumlah harus lebih dari 0.');
            return;
        }

        $existingIndex = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($existingIndex !== false) {
            $newQuantity = $this->cart[$existingIndex]['quantity'] + $quantity;
            if ($newQuantity <= $product->stock) {
                $this->cart[$existingIndex]['quantity'] = $newQuantity;
                session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
            } else {
                session()->flash('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
                return;
            }
        } else {
            if ($quantity <= $product->stock) {
                $this->cart[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->selling_price,
                    'quantity' => $quantity,
                    'stock' => $product->stock,
                    'image' => $product->image,
                ];
                session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
            } else {
                session()->flash('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
                return;
            }
        }

        // Reset quantity input after adding
        $this->quantities[$productId] = 1;
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
            return floatval($item['price']) * intval($item['quantity']);
        });
        
        $this->change = max(0, floatval($this->paid_amount) - floatval($this->total));
    }

    public function getCartTotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return floatval($item['price']) * intval($item['quantity']);
        });
    }

    public function updatedPaidAmount()
    {
        $this->calculateTotal();
    }

    public function save()
    {
        try {
            // Ensure all values are properly typed
            $this->paid_amount = floatval($this->paid_amount);
            
            // Calculate total first
            $this->calculateTotal();
            
            if (empty($this->cart)) {
                session()->flash('error', 'Keranjang belanja kosong.');
                return;
            }
            
            $this->validate([
                'cashier_name' => 'required|string|max:255',
                'payment_method' => 'required|string',
                'paid_amount' => 'required|numeric|min:' . floatval($this->total),
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Validation error: ' . $e->getMessage());
            return;
        }

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'no_invoice' => 'INV-' . strtoupper(uniqid()),
                'cashier_name' => $this->cashier_name,
                'transaction_date' => Carbon::now(),
                'total_price' => floatval($this->total),
                'paid_amount' => floatval($this->paid_amount),
                'change_amount' => floatval($this->change),
                'payment_method' => $this->payment_method,
            ]);

            foreach ($this->cart as $item) {
                $product = Product::findOrFail($item['product_id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => intval($item['quantity']),
                    'unit_price' => floatval($product->selling_price),
                    'subtotal' => floatval($product->selling_price) * intval($item['quantity']),
                ]);

                // Reduce stock
                $product->decrement('stock', intval($item['quantity']));

                // Log inventory
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => intval($item['quantity']),
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
