<?php
namespace App\Livewire;

use App\Models\Bien;
use Livewire\Component;
use App\Models\TypeBien;
use App\Models\TypeAnnonce;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads; // Importe le trait pour gérer l'upload de fichiers

class BienCrud extends Component
{
    use WithFileUploads;

    public $action;
    public $disabledForm = false;
    public $bienId, $sold, $lib, $description, $prix, $photo, $photoComplete, $classe_energie, $chambre, $sdb, $wc, $st, $sh, $type_bien_id, $type_annonce_id;
    public $typeAnnonce, $typeBien, $classeEnergieList = [
        1 => "A", 
        2 => "B", 
        3 => "C", 
        4 => "D", 
        5 => "E", 
        6 => "F"
    ];

    public function mount($id = null)
    {
        $this->typeAnnonce = TypeAnnonce::orderBy("type_annonce")->get();
        $this->typeBien = TypeBien::orderBy("type_bien")->get();

        if ($id && $this->action != 'ajouter') {
            $bien = Bien::findOrFail($id);
            $this->bienId = $bien->id;
            $this->sold = $bien->sold;
            $this->lib = $bien->lib;
            $this->description = $bien->description;
            $this->prix = $bien->prix;
            $this->classe_energie = $bien->classe_energie;
            $this->chambre = $bien->chambre;
            $this->sdb = $bien->sdb;
            $this->wc = $bien->wc;
            $this->st = $bien->st;
            $this->sh = $bien->sh;
            $this->type_bien_id = $bien->type_bien_id;
            $this->type_annonce_id = $bien->type_annonce_id;

            $this->photo = $bien->photo;
            $this->photoComplete = $bien->photoComplete;
        }

        if($this->action == 'consulter')
        {
            $this->disabledForm = true;
        }
    }

    public function save()
    {
        $rules = [
            'sold' => 'nullable',
            'lib' => 'required',
            'photo' => ['sometimes'],
            'description' => 'nullable',
            'prix' => 'required|numeric',
            'classe_energie' => 'required',
            'chambre' => 'required|integer',
            'sdb' => 'required|integer',
            'wc' => 'required|integer',
            'st' => 'required|integer',
            'sh' => 'required|integer',
            'type_bien_id' => 'required',
            'type_annonce_id' => 'required',
        ];

        // Ajouter les règles de validation spécifiques pour 'ajouter'
        if ($this->action == 'ajouter') {
            $rules['photo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        } elseif ($this->action == 'modifier' && !is_string($this->photo)) {
            // Pour l'édition, si 'photo' est un fichier téléchargé et non une chaîne
            $rules['photo'] = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        }

        $this->validate($rules, [
            'lib.required' => 'Le champ libellé est obligatoire',
            'photo.required' => 'La photo est obligatoire',
            'photo.image' => 'Le fichier doit être une image',
            'sh.required' => 'Le champ superficie habitable est obligatoire',
            'sh.integer' => 'Le champ superficie habitable doit être un chiffre',
            'st.required' => 'Le champ superficie terrain est obligatoire',
            'st.integer' => 'Le champ superficie terrain doit être un chiffre',
            'chambre.integer' => 'Le champ chambre doit être un chiffre',
            'sdb.integer' => 'Le champ sdb doit être un chiffre',
            'wc.integer' => 'Le champ wc doit être un chiffre',
            'type_bien_id.required' => 'Le type de bien est obligatoire',
            'type_annonce_id.required' => 'Le type d\'annonce est obligatoire',
            'classe_energie.required' => 'La classe énergétique est obligatoire',
            'required' => 'Le champ :attribute est obligatoire'
        ]);

        $bien = $this->bienId ? Bien::findOrFail($this->bienId) : new Bien;

        if (!$this->bienId || !is_string($this->photo)) {
            // Si une nouvelle photo a été téléchargée, supprimer l'ancienne photo du dossier
            if ($bien->photo) {
                Storage::delete('public/images/' . $bien->photo);
            }

            // Stocker la nouvelle photo et mettre à jour le nom du fichier dans $bien->photo
            $imageName = time() . '.' . $this->photo->extension();
            $this->photo->storeAs('public/images', $imageName);
            $bien->photo = $imageName;
        }

        $bien->sold = $this->sold ?? 0;
        $bien->lib = $this->lib;
        $bien->description = $this->description;
        $bien->prix = $this->prix;
        $bien->classe_energie = $this->classe_energie;
        $bien->chambre = $this->chambre;
        $bien->sdb = $this->sdb;
        $bien->wc = $this->wc;
        $bien->st = $this->st;
        $bien->sh = $this->sh;
        $bien->user_id = Auth::id();
        $bien->type_bien_id = $this->type_bien_id;
        $bien->type_annonce_id = $this->type_annonce_id;

        $bien->save();

        session()->flash('status', $this->bienId ? 'Bien mis à jour avec succès.' : 'Bien créé avec succès.');
        return redirect()->route('biens.index');
    }

    public function render()
    {
        return view('livewire.bien-crud')->extends("layouts.app");
    }
}
