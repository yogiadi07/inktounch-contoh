<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
   public function index(Request $request)
{
    $query = Transaction::query();

    if ($request->start_date && $request->end_date) {
        $query->whereBetween('transaction_date', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    $transactions = $query->latest()->get();
    return view('transactions.index', compact('transactions'));
}


    public function create()
    {
        $products = Product::all();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity'   => 'required|integer|min:1',
            'payment_method'        => 'required|string',
            'paid_amount'           => 'required|integer|min:0',
        ]);
    
        DB::beginTransaction();
    
        try {
            $total = 0;
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $total += $product->selling_price * $item['quantity'];
            }
    
            $transaction = Transaction::create([
                'no_invoice'       => 'INV-' . strtoupper(uniqid()),
                'transaction_date' => Carbon::now(),
                'total_price'      => $total,
                'paid_amount'      => $request->paid_amount,
                'change_amount'    => $request->paid_amount - $total,
                'payment_method'   => $request->payment_method,
            ]);
    
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
    
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'quantity'       => $item['quantity'],
                    'unit_price'     => $product->selling_price,
                    'subtotal'       => $product->selling_price * $item['quantity'],
                ]);
    
                // Kurangi stok
                $product->decrement('stock', $item['quantity']);
    
                // Catat log inventaris
                InventoryLog::create([
                    'product_id'  => $product->id,
                    'type'        => 'out',
                    'quantity'    => $item['quantity'],
                    'description' => 'Penjualan #' . $transaction->no_invoice,
                    'logged_at'   => now(),
                ]);
            }
    
            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
            public function show(Transaction $transaction)
        {
            $transaction->load('details.product'); // include produk di setiap detail
            return view('transactions.show', compact('transaction'));
        }

        
}
