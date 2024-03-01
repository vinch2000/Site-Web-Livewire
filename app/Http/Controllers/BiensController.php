<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\TypeBien;
use App\Models\TypeAnnonce;
use Illuminate\Http\Request;

class BiensController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bien::query();
    
        // Filtrer par type de bien (mes biens, tous les biens)
        if ($request->input('typeBien') === 'self')
            $query->where("user_id", "=", Auth()->user()->id);
    
        // Filtrer par type d'annonce (vente, location)
        if ($request->has('etat')) {
            if ($request->input('etat') == 'vente')
                $query->where("type_annonce_id", "=", 1);
            elseif ($request->input('etat') == 'location')
                $query->where("type_annonce_id", "=", 2);
        }
    
        $biens = $query->orderBy('lib')->get();
    
        return view('biens.index', compact('biens'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'sold' => '',
            'lib' => 'required',
            'description' => '',
            'prix' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'classe_energie' => 'required',
            'chambre' => 'required|integer',
            'sdb' => 'required|integer',
            'wc' => 'required|integer',
            'st' => 'required|integer',
            'sh' => 'required|integer',
            'type_bien_id' => 'required',
            'type_annonce_id' => 'required',
        ], [
            'lib.required' => 'Le champ libellé est obligatoire',
            'sh.required' => 'Le champ superficie habitable est obligatoire',
            'sh.integer' => 'Le champ superficie habitable doit être un chiffre',
            'st.required' => 'Le champ superficie terrain est obligatoire',
            'st.integer' => 'Le champ superficie terrain doit être un chiffre',
            'chambre.integer' => 'Le champ chambre doit être un chiffre',
            'sdb.integer' => 'Le champ sdb doit être un chiffre',
            'wc.integer' => 'Le champ wc doit être un chiffre',
            'required' => 'Le champ :attribute est obligatoire'
        ]);

        $bien = new Bien();

        if ($request->hasFile('photo')) {
            $imageName = time().'.'.$request->photo->extension();  
            $request->photo->move(public_path('images'), $imageName);
            $bien->photo = $imageName;
        }

        $bien->sold = $request->input('sold');
        $bien->lib = $request->input('lib');
        $bien->description = $request->input('description');
        $bien->prix = $request->input('prix');
        $bien->classe_energie = $request->input('classe_energie');
        $bien->chambre = $request->input('chambre');
        $bien->sdb = $request->input('sdb');
        $bien->wc = $request->input('wc');
        $bien->st = $request->input('st');
        $bien->sh = $request->input('sh');
        $bien->user_id = Auth()->user()->id;
        $bien->type_bien_id = $request->input('type_bien_id');
        $bien->type_annonce_id = $request->input('type_annonce_id');

        $bien->save();

        return redirect()->route('biens.index')->with('status', 'Bien créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id = null, $action = 'ajouter')
    {
        $arrayEnergie = array(1 => "A", 2 => "B", 3 => "C", 4 => "D", 5 => "E", 6 => "F");
        $type_annonce = TypeAnnonce::orderBy("type_annonce")->get();
        $type_bien = TypeBien::orderBy("type_bien")->get();
    
        $bien = null;
        if ($id && $action != 'ajouter') {
            $bien = Bien::where("id", "=", $id)->first();
        }
    
        return view('biens.edit', [
            'bien' => $bien,
            'typeAnnonce' => $type_annonce,
            'typeBien' => $type_bien,
            'classeEnergie' => $arrayEnergie,
            'action' => $action
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sold' => '',
            'lib' => 'required',
            'description' => '',
            'prix' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'classe_energie' => 'required',
            'chambre' => 'required|integer',
            'sdb' => 'required|integer',
            'wc' => 'required|integer',
            'st' => 'required|integer',
            'sh' => 'required|integer',
            'type_bien_id' => 'required',
            'type_annonce_id' => 'required',
        ], [
            'lib.required' => 'Le champ libellé est obligatoire',
            'sh.required' => 'Le champ superficie habitable est obligatoire',
            'sh.integer' => 'Le champ superficie habitable doit être un chiffre',
            'st.required' => 'Le champ superficie terrain est obligatoire',
            'st.integer' => 'Le champ superficie terrain doit être un chiffre',
            'chambre.integer' => 'Le champ chambre doit être un chiffre',
            'sdb.integer' => 'Le champ sdb doit être un chiffre',
            'wc.integer' => 'Le champ wc doit être un chiffre',
            'required' => 'Le champ :attribute est obligatoire'
        ]);
    
        $bien = Bien::findOrFail($id);
    
        if ($request->hasFile('photo')) {
            // Utilisez la nouvelle méthode pour obtenir le chemin relatif de l'image
            $oldImagePath = public_path('images/' . $bien->photo_path);
            
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        
            // Traitement du nouveau fichier image
            $imageName = time().'.'.$request->photo->extension();  
            $request->photo->move(public_path('images'), $imageName);
            $bien->photo = $imageName;
        }
    
        $bien->sold = $request->input('sold');
        $bien->lib = $request->input('lib');
        $bien->description = $request->input('description');
        $bien->prix = $request->input('prix');
        $bien->classe_energie = $request->input('classe_energie');
        $bien->chambre = $request->input('chambre');
        $bien->sdb = $request->input('sdb');
        $bien->wc = $request->input('wc');
        $bien->st = $request->input('st');
        $bien->sh = $request->input('sh');
        $bien->user_id = Auth()->user()->id;
        $bien->type_bien_id = $request->input('type_bien_id');
        $bien->type_annonce_id = $request->input('type_annonce_id');

        $bien->save();
    
        return redirect()->route('biens.index')->with('status', 'Bien mis à jour avec succès');
    }
    

    public function destroy(string $id)
    {
        
    }
}