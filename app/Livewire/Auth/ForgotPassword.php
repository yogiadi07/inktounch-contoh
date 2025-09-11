<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.guest')]
class ForgotPassword extends Component
{
    #[Rule('required|email|exists:users,email')]
    public string $email = '';

    #[Rule('required|min:6|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function resetPassword()
    {
        $this->validate();

        $user = User::where('email', $this->email)->firstOrFail();
        $user->password = Hash::make($this->password);
        $user->save();

        session()->flash('success', 'Password berhasil direset. Silakan login.');
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
