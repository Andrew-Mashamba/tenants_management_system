<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $address;
    public $role = 'tenant';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'role' => 'required|in:tenant,landlord,agent',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        // Assign role
        $role = Role::where('slug', $this->role)->first();
        if ($role) {
            $user->assignRole($role);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
