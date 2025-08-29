<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class CategoryManager extends Component
{
    public $categories;
    public $showForm = false;
    public $editingCategory = null;

    #[Rule('required|string|max:255')]
    public $name = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->categories = Category::all();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingCategory = null;
    }

    public function openEditForm($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $this->editingCategory = $category;
        $this->name = $category->name;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingCategory) {
            $this->editingCategory->update(['name' => $this->name]);
            session()->flash('success', 'Kategori berhasil diperbarui.');
        } else {
            Category::create(['name' => $this->name]);
            session()->flash('success', 'Kategori berhasil ditambahkan.');
        }

        $this->closeForm();
        $this->loadData();
    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();
        $this->loadData();
        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->editingCategory = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.categories.category-manager');
    }
}
