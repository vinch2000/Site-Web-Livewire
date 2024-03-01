<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // Vérifie si l'email existe
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => 'L\'email fourni ne correspond à aucun compte.']);
        }
    
        // Tente d'authentifier l'utilisateur
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
    
            return redirect()->route('biens.index');
        }
    
        // Si l'authentification échoue mais que l'email existe, le problème vient du mot de passe
        return back()->withErrors(['password' => 'Le mot de passe fourni est incorrect.']);
    }

    public function inscription()
    {
        $pays   = Pays::orderBy("nom_pays")->get();
        $villes = Ville::orderBy("ville")->get();

        return view('auth.register', compact('pays', 'villes'));
    }

    public function register()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'email' => 'required|email|confirmed',
            'password' => 'required|min:8|confirmed',
            'adresse' => 'required',
            'ville_id' => 'required',
            'pays_id' => 'required',
            'conditionsgenerale' => 'accepted',
        ], [
            'name.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être une adresse email valide',
            'email.confirmed' => 'L\'email de confirmation ne correspond pas.',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'adresse.required' => 'L\'adresse est obligatoire',
            'ville_id.required' => 'La ville est obligatoire',
            'pays_id.required' => 'Le pays est obligatoire',
            'conditionsgenerale.accepted' => 'Vous devez accepter les conditions générales d\'utilisation pour vous inscrire.',
        ]);

        $user = User::create($attributes);
    
        auth()->login($user);
    
        return redirect()->route('biens.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->check()) {
            return redirect()->route('biens.index');
        }
        return view('auth.login');
    }

    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/');
    }
}
