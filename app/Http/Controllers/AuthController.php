<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        if (Auth::check()) {
            // L'utilisateur est authentifié
            return redirect()->route('biens.index');
        } else {
            // L'utilisateur n'est pas authentifié
            return redirect()->route('connection');
        }
    }
}