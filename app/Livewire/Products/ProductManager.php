<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class ProductManager extends Component
{
    use WithFileUploads;

    public $products;
    public $categories;
    public $showForm = false;
    public $editingProduct = null;

    // Form properties
    #[Rule('required|string|max:255')]
    public $name = '';
    
    #[Rule('nullable|exists:categories,id')]
    public $category_id = '';
    
    #[Rule('required|numeric|min:0')]
    public $purchase_price = '';
    
    #[Rule('required|numeric|min:0')]
    public $selling_price = '';
    
    #[Rule('required|integer|min:0')]
    public $stock = '';
    
    #[Rule('required|string')]
    public $unit = '';
    
    #[Rule('nullable|integer|min:0')]
    public $added_stock = '';
    
    #[Rule('nullable|image|mimes:jpg,jpeg,png|max:2048')]
    public $image;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->products = Product::with('category')->get();
        $this->categories = Category::all();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingProduct = null;
    }

    public function openEditForm($productId)
    {
        $product = Product::findOrFail($productId);
        $this->editingProduct = $product;
        
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->stock = $product->stock;
        $this->unit = $product->unit;
        $this->added_stock = '';
        
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingProduct) {
            $this->updateProduct();
        } else {
            $this->createProduct();
        }

        $this->closeForm();
        $this->loadData();
        session()->flash('success', $this->editingProduct ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');
    }

    private function createProduct()
    {
        $imageName = null;
        if ($this->image) {
            $imageName = time() . '_' . $this->image->getClientOriginalName();
            $this->image->storeAs('products', $imageName, 'public');
        }

        Product::create([
            'name' => $this->name,
            'category_id' => $this->category_id ?: null,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'sku' => 'SKU-' . strtoupper(uniqid()),
            'image' => $imageName,
        ]);
    }

    private function updateProduct()
    {
        $imageName = $this->editingProduct->image;
        if ($this->image) {
            $imageName = time() . '_' . $this->image->getClientOriginalName();
            $this->image->storeAs('products', $imageName, 'public');
        }

        $this->editingProduct->update([
            'name' => $this->name,
            'category_id' => $this->category_id ?: null,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'unit' => $this->unit,
            'image' => $imageName,
        ]);

        // Handle stock addition
        $addedStock = (int) $this->added_stock;
        if ($addedStock > 0) {
            $this->editingProduct->increment('stock', $addedStock);

            InventoryLog::create([
                'product_id' => $this->editingProduct->id,
                'type' => 'in',
                'quantity' => $addedStock,
                'description' => 'Penambahan stok dari edit produk',
                'logged_at' => now(),
            ]);
        }
    }

    public function delete($productId)
    {
        $product = Product::findOrFail($productId);
        
        // Delete image file if exists
        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();
        $this->loadData();
        session()->flash('success', 'Produk berhasil dihapus.');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->category_id = '';
        $this->purchase_price = '';
        $this->selling_price = '';
        $this->stock = '';
        $this->unit = '';
        $this->added_stock = '';
        $this->image = null;
        $this->editingProduct = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.products.product-manager');
    }
}
