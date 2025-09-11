<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class ProductManager extends Component
{
    use WithFileUploads;

    public $products;
    public $categories;
    public $showForm = false;
    public $editingProduct = null;
    public $uploaded_image_path = null; // holds instantly uploaded path like 'products/filename.ext'

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
    
    #[Rule('nullable|image|mimes:jpg,jpeg,png,jfif,webp,gif|max:5120')]
    public $image;

    public function mount()
    {
        $this->cleanupMissingImages();
        $this->loadData();
    }

    private function cleanupMissingImages()
    {
        $products = Product::whereNotNull('image')->get();
        foreach ($products as $product) {
            $normalized = $this->normalizeImagePath($product->image);
            if ($normalized !== $product->image) {
                $product->update(['image' => $normalized]);
                Log::info('ProductManager.cleanup: normalized image path', [
                    'id' => $product->id,
                    'from' => $product->image,
                    'to' => $normalized,
                ]);
                continue;
            }
            // If still not resolvable, null it out
            if (!$normalized) {
                $product->update(['image' => null]);
                Log::warning('ProductManager.cleanup: unresolved image, set null', [
                    'id' => $product->id,
                    'image' => $product->image,
                ]);
            }
        }
    }

    private function normalizeImagePath(?string $image): ?string
    {
        if (!$image) return null;

        // Case 1: Already stored as 'products/filename.ext' on public disk
        if (\Illuminate\Support\Str::startsWith($image, 'products/')) {
            return Storage::disk('public')->exists($image) ? $image : null;
        }

        // Case 2: Legacy DB value only contains filename (no folder)
        $candidate = 'products/' . $image;
        if (Storage::disk('public')->exists($candidate)) {
            Log::info('ProductManager.normalize: candidate found on public disk', ['candidate' => $candidate]);
            return $candidate; // normalize DB
        }

        // Case 3: Legacy file lives in public/uploads/products
        $legacyPublicPath = public_path('uploads/products/' . $image);
        if (file_exists($legacyPublicPath)) {
            try {
                // Move (copy) it into storage public disk
                $put = Storage::disk('public')->putFileAs('products', new File($legacyPublicPath), $image);
                if ($put) {
                    // Optionally remove old file
                    @unlink($legacyPublicPath);
                    Log::info('ProductManager.normalize: migrated legacy image', [
                        'from' => $legacyPublicPath,
                        'to' => 'products/' . $image,
                    ]);
                    return 'products/' . $image;
                }
            } catch (\Exception $e) {
                Log::error('ProductManager.normalize: migration failed', [
                    'from' => $legacyPublicPath,
                    'error' => $e->getMessage(),
                ]);
                // fallthrough to null
            }
        }

        return null;
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

    // Livewire hook: called automatically when $image changes (file selected)
    public function updatedImage()
    {
        try {
            $this->validateOnly('image');

            if (!$this->image) return;

            // generate unique name and store on public disk
            $extension = $this->image->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $storedPath = $this->image->storeAs('products', $fileName, 'public');

            if (!$storedPath || !Storage::disk('public')->exists($storedPath)) {
                session()->flash('error', 'Gagal mengunggah gambar.');
                return;
            }

            $this->uploaded_image_path = $storedPath;

            // If we are editing an existing product, immediately persist the new image
            if ($this->editingProduct) {
                $old = $this->editingProduct->image;
                $this->editingProduct->image = $storedPath;
                $this->editingProduct->touch();
                $this->editingProduct->save();

                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            // clear temp file input to avoid re-triggering
            $this->image = null;

            // refresh list so table reflects new image without second edit
            $this->loadData();
            $this->dispatch('$refresh');
        } catch (\Throwable $e) {
            session()->flash('error', 'Upload gagal: ' . $e->getMessage());
        }
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
        // Force re-render so latest image path appears in the table immediately
        $this->dispatch('$refresh');
    }

    private function createProduct()
    {
        // If user already selected a file, it has been stored in updatedImage()
        $imagePath = $this->uploaded_image_path;

        Product::create([
            'name' => $this->name,
            'category_id' => $this->category_id ?: null,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'sku' => 'SKU-' . strtoupper(uniqid()),
            'image' => $imagePath,
        ]);

        // Reset uploaded path after create
        $this->uploaded_image_path = null;
    }

    private function updateProduct()
    {
        $imagePath = $this->editingProduct->image; // may be like 'products/filename.ext'
        if ($this->image) {
            try {
                Log::info('ProductManager.updateProduct: upload started', [
                    'productId' => $this->editingProduct->id,
                    'originalName' => $this->image->getClientOriginalName(),
                ]);
                if (!$this->image->isValid()) {
                    session()->flash('error', 'File gambar tidak valid.');
                    Log::warning('ProductManager.updateProduct: invalid image');
                    return;
                }

                // Delete old image from public disk if exists
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    Log::info('ProductManager.updateProduct: old image deleted', ['old' => $imagePath]);
                }

                // Store new image on public disk
                $extension = $this->image->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $storedPath = $this->image->storeAs('products', $fileName, 'public');

                if (!$storedPath || !Storage::disk('public')->exists($storedPath)) {
                    session()->flash('error', 'Gagal menyimpan gambar produk baru.');
                    Log::error('ProductManager.updateProduct: stored path missing', [
                        'storedPath' => $storedPath,
                    ]);
                } else {
                    $imagePath = $storedPath;
                    session()->flash('success', 'Gambar berhasil diupdate: ' . $fileName);
                    Log::info('ProductManager.updateProduct: upload success', [
                        'storedPath' => $storedPath,
                    ]);
                }
            } catch (\Exception $e) {
                session()->flash('error', 'Error saat upload gambar: ' . $e->getMessage());
                Log::error('ProductManager.updateProduct: exception', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        $this->editingProduct->update([
            'name' => $this->name,
            'category_id' => $this->category_id ?: null,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'unit' => $this->unit,
            'image' => $imagePath,
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
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
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
        $this->uploaded_image_path = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.products.product-manager');
    }
}
