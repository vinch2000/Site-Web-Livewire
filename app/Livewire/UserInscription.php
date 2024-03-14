<?php

namespace App\Livewire;

use App\Models\Pays;
use App\Models\User;
use App\Models\Ville;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserInscription extends Component
{
    public $email;
    public $email_confirmation;
    public $password;
    public $password_confirmation;
    public $name;
    public $adresse;
    public $selectedPays = null;
    public $ville_id;
    public $conditionsgenerale = false;
    public $loadingVilles = false;
    
    public $pays; // Contiendra tous les pays
    public $villes = []; // Vide par défaut, peuplé basé sur le pays sélectionné

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|confirmed|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'adresse' => 'required',
        'ville_id' => 'required',
        'selectedPays' => 'required',
        'conditionsgenerale' => 'accepted',
    ];

    protected $messages = [
        'name.required' => 'Le nom est obligatoire',
        'email.required' => 'L\'email est obligatoire',
        'email.email' => 'L\'email doit être une adresse email valide',
        'email.confirmed' => 'L\'email de confirmation ne correspond pas.',
        'email.unique' => 'Un membre possède déjà cet email.',
        'password.required' => 'Le mot de passe est obligatoire',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        'adresse.required' => 'L\'adresse est obligatoire',
        'ville_id.required' => 'La ville est obligatoire',
        'selectedPays.required' => 'Le pays est obligatoire',
        'conditionsgenerale.accepted' => 'Vous devez accepter les conditions générales d\'utilisation pour vous inscrire.'
    ];
    
    public function mount()
    {
        $this->pays = Pays::orderBy("nom_pays")->get();
    }
    
    public function updatedSelectedPays($value)
    {
        $this->ville_id = null;
        $this->villes = collect([]);
    
        if (!is_null($value)) {
            // Affichage du loader et désactivation du select ville pendant le chargement
            $this->selectedPays = $value;
    
            // On mets en cache les Villes pendant 60 jours
            $cacheKey = "villes_pays_{$value}";
            $this->villes = Cache::remember($cacheKey, 60*60*24*60, function () use ($value) {
                return Ville::where('pays_id', $value)->orderBy('ville')->get();
            });
        }
    }

    public function inscription()
    {
        $validatedData = $this->validate();

        // Hashage du mot de passe avant la création de l'utilisateur
        $validatedData['password'] = bcrypt($validatedData['password']);
    
        // S'assurer que le modèle User est correctement configuré pour accepter ces attributs en masse (champs fillable)
        $user = User::create($validatedData);

        // On récupère le role créé dans PermissionSeeder
        $role = Role::findByName('utilisateur');

        // Et on assigne ce rôle à notre utilisateur
        $user->assignRole($role);
        $user->syncPermissions($role->permissions);
        $user->save();
    
        Auth::login($user);
    
        return redirect()->route('biens.index');
    }

    public function render()
    {
        return view('livewire.user-inscription')
        ->extends('layouts.app');
    }
}
