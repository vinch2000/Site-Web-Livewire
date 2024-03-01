<?php
namespace App\Livewire;

use Livewire\Component;

class AuthLogin extends Component
{
    public $email, $password;

    public function render()
    {
        return view('livewire.auth-login');
    }

    public function save()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($credentials)) {
            return redirect()->route('biens.index');
        }

        $this->addError('email', 'Ces informations d\'identification ne correspondent pas à nos enregistrements.');
    }
}
