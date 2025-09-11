<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.app')]
class UserManager extends Component
{
    public $users;

    // form fields
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('nullable|min:6|confirmed')]
    public $password = '';
    public $password_confirmation = '';

    #[Rule('required|in:admin,staff')]
    public $role = 'staff';

    public $editingId = null;

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::orderBy('name')->get();
    }

    public function openCreate()
    {
        $this->resetForm();
    }

    public function openEdit($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function save()
    {
        if ($this->editingId) {
            $this->updateUser();
        } else {
            $this->createUser();
        }
    }

    private function createUser()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        } else {
            $this->addError('password', 'Password wajib diisi saat membuat user.');
            return;
        }

        User::create($data);
        session()->flash('success', 'User berhasil dibuat.');
        $this->resetForm();
        $this->loadUsers();
    }

    private function updateUser()
    {
        $user = User::findOrFail($this->editingId);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->role = $this->role;
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        session()->flash('success', 'User berhasil diperbarui.');
        $this->resetForm();
        $this->loadUsers();
    }

    public function resetUserPassword($userId)
    {
        $user = User::findOrFail($userId);
        $new = 'password';
        $user->password = Hash::make($new);
        $user->save();
        session()->flash('success', 'Password user di-reset ke: "password". Harap segera diganti.');
        $this->loadUsers();
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        if (auth()->id() === $user->id) {
            session()->flash('error', 'Tidak dapat menghapus akun diri sendiri.');
            return;
        }
        $user->delete();
        session()->flash('success', 'User berhasil dihapus.');
        $this->loadUsers();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'staff';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.user-manager');
    }
}
