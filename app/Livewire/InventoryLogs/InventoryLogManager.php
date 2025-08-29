<?php

namespace App\Livewire\InventoryLogs;

use App\Models\InventoryLog;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InventoryLogManager extends Component
{
    use WithPagination;

    public $showCreateForm = false;
    public $editingLogId = null;
    
    // Filter properties
    public $start_date = '';
    public $end_date = '';
    public $product_id = '';
    public $type = '';
    
    // Form properties
    #[Rule('required|exists:products,id')]
    public $form_product_id = '';
    
    #[Rule('required|in:in,out')]
    public $form_type = 'in';
    
    #[Rule('required|integer|min:1')]
    public $form_quantity = 1;
    
    #[Rule('required|string|max:255')]
    public $form_description = '';

    public function mount()
    {
        $this->start_date = Carbon::today()->subDays(30)->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function save()
    {
        $this->validate([
            'form_product_id' => 'required|exists:products,id',
            'form_type' => 'required|in:in,out',
            'form_quantity' => 'required|integer|min:1',
            'form_description' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($this->form_product_id);

        // Check stock for 'out' type
        if ($this->form_type === 'out' && $product->stock < $this->form_quantity) {
            session()->flash('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
            return;
        }

        InventoryLog::create([
            'product_id' => $this->form_product_id,
            'type' => $this->form_type,
            'quantity' => $this->form_quantity,
            'description' => $this->form_description,
            'logged_at' => now(),
        ]);

        // Update product stock
        if ($this->form_type === 'in') {
            $product->increment('stock', $this->form_quantity);
        } else {
            $product->decrement('stock', $this->form_quantity);
        }

        $this->closeCreateForm();
        session()->flash('success', 'Log inventori berhasil ditambahkan.');
    }

    public function edit($logId)
    {
        $log = InventoryLog::findOrFail($logId);
        
        $this->editingLogId = $logId;
        $this->form_product_id = $log->product_id;
        $this->form_type = $log->type;
        $this->form_quantity = $log->quantity;
        $this->form_description = $log->description;
        $this->showCreateForm = true;
    }

    public function update()
    {
        $this->validate([
            'form_product_id' => 'required|exists:products,id',
            'form_type' => 'required|in:in,out',
            'form_quantity' => 'required|integer|min:1',
            'form_description' => 'required|string|max:255',
        ]);

        $log = InventoryLog::findOrFail($this->editingLogId);
        $product = Product::findOrFail($this->form_product_id);

        // Revert previous stock change
        if ($log->type === 'in') {
            $product->decrement('stock', $log->quantity);
        } else {
            $product->increment('stock', $log->quantity);
        }

        // Check stock for new 'out' type
        if ($this->form_type === 'out' && $product->stock < $this->form_quantity) {
            // Reapply original change if validation fails
            if ($log->type === 'in') {
                $product->increment('stock', $log->quantity);
            } else {
                $product->decrement('stock', $log->quantity);
            }
            
            session()->flash('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
            return;
        }

        // Update log
        $log->update([
            'product_id' => $this->form_product_id,
            'type' => $this->form_type,
            'quantity' => $this->form_quantity,
            'description' => $this->form_description,
        ]);

        // Apply new stock change
        if ($this->form_type === 'in') {
            $product->increment('stock', $this->form_quantity);
        } else {
            $product->decrement('stock', $this->form_quantity);
        }

        $this->closeCreateForm();
        session()->flash('success', 'Log inventori berhasil diperbarui.');
    }

    public function delete($logId)
    {
        $log = InventoryLog::findOrFail($logId);
        $product = Product::findOrFail($log->product_id);

        // Revert stock change
        if ($log->type === 'in') {
            $product->decrement('stock', $log->quantity);
        } else {
            $product->increment('stock', $log->quantity);
        }

        $log->delete();
        session()->flash('success', 'Log inventori berhasil dihapus.');
    }

    public function applyFilter()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->start_date = Carbon::today()->subDays(30)->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
        $this->product_id = '';
        $this->type = '';
        $this->resetPage();
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingLogId = null;
        $this->form_product_id = '';
        $this->form_type = 'in';
        $this->form_quantity = 1;
        $this->form_description = '';
        $this->resetValidation();
    }

    public function render()
    {
        $query = InventoryLog::with('product');
        
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('logged_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }
        
        if ($this->product_id) {
            $query->where('product_id', $this->product_id);
        }
        
        if ($this->type) {
            $query->where('type', $this->type);
        }
        
        $logs = $query->latest('logged_at')->paginate(15);
        $products = Product::orderBy('name')->get();
        
        return view('livewire.inventory-logs.inventory-log-manager', [
            'logs' => $logs,
            'products' => $products
        ]);
    }
}
