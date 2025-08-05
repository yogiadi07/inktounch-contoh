<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\InventoryLog;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'nullable|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'unit' => 'required|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
        }

        Product::create([
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'purchase_price' => $request->purchase_price,
            'selling_price'  => $request->selling_price,
            'stock'          => $request->stock,
            'sku'            => 'SKU-' . strtoupper(uniqid()), // SKU otomatis
            'image'          => $imageName,
        ]);
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.create', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'nullable|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'unit'           => 'required|string',
            'added_stock'    => 'nullable|integer|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $imageName = $product->image;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
        }
    
        // Update data utama (tanpa stok)
        $product->update([
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'purchase_price' => $request->purchase_price,
            'selling_price'  => $request->selling_price,
            'unit'           => $request->unit,
            'image'          => $imageName,
        ]);
    
        // Jika ada penambahan stok
        $addedStock = (int) $request->input('added_stock', 0);
        if ($addedStock > 0) {
            $product->increment('stock', $addedStock);
    
            InventoryLog::create([
                'product_id'  => $product->id,
                'type'        => 'in',
                'quantity'    => $addedStock,
                'description' => 'Penambahan stok dari edit produk',
                'logged_at'   => now(),
            ]);
        }
    
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }
        

    public function destroy(Product $product)
    {
        // Hapus file gambar jika ada
        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
