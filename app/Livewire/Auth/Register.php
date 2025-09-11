<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.guest')]
class Register extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:6|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[Rule('required|in:admin,staff')]
    public string $role = 'staff';

    public function register()
    {
        $this->validate();

        // Only admin can choose role; guests default to staff
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            $this->role = 'staff';
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        Auth::login($user);
        session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }

    public function render()
    {
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        return view('livewire.auth.register', compact('isAdmin'));
    }
}
