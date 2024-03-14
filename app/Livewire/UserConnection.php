<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserConnection extends Component
{
    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        // Vérifie si l'utilisateur existe
        if (!$user) {
            $this->addError('email', 'L\'email fourni ne correspond à aucun compte.');
            return;
        }

        // Vérifie si le mot de passe est correct
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('password', 'Le mot de passe fourni est incorrect.');
            return;
        }

        session()->regenerate();
        
        return redirect()->route('biens.index');
    }

    public function render()
    {
        return view('livewire.user-connection')
        ->extends('layouts.app');
    }
}
