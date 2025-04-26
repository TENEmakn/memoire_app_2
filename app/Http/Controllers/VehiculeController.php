<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class VehiculeController extends Controller
{
   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chauffeurs = User::where('status', 'chauffeur')->get();
        return view('admin.vehicules.create_vehicule', compact('chauffeurs'));
    }

    public function store_location(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'marque' => 'required|string|max:255',
            'serie' => 'required|string|max:255',
            'type_vehicule' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'immatriculation' => 'required|string|max:255|unique:vehicules',
            'nb_places' => 'required|integer|min:1',
            'prix_location_abidjan' => 'required|numeric|min:0',
            'prix_location_interieur' => 'required|numeric|min:0',
            'carburant' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_secondaire' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_tertiaire' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'nullable|exists:users,id',
            'etat_vehicule' => 'required|string|in:location'
        ]);

        // Gestion des images
        $imagePaths = [];
        $imageFields = ['image_principale', 'image_secondaire', 'image_tertiaire'];
        
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('vehicules', 'public');
                $imagePaths[$field] = $path;
            }
        }

        // Création du véhicule
        $vehicule = new Vehicule();
        $vehicule->marque = $validatedData['marque'];
        $vehicule->serie = $validatedData['serie'];
        $vehicule->type_vehicule = $validatedData['type_vehicule'];
        $vehicule->annee = $validatedData['annee'];
        $vehicule->immatriculation = $validatedData['immatriculation'];
        $vehicule->nb_places = $validatedData['nb_places'];
        $vehicule->prix_location_abidjan = $validatedData['prix_location_abidjan'];
        $vehicule->prix_location_interieur = $validatedData['prix_location_interieur'];
        $vehicule->carburant = $validatedData['carburant'];
        $vehicule->transmission = $validatedData['transmission'];
        $vehicule->etat_vehicule = $validatedData['etat_vehicule'];
        $vehicule->user_id = $validatedData['user_id'] ?? null;
        
        // Attribution des chemins d'images
        foreach ($imagePaths as $field => $path) {
            $vehicule->$field = $path;
        }

        $vehicule->save();

        return redirect()->route('admin.vehicules')
            ->with('success', 'Véhicule de location ajouté avec succès.');
    }

    public function store_vente(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'marque' => 'required|string|max:255',
            'serie' => 'required|string|max:255',
            'type_vehicule' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'immatriculation' => 'required|string|max:255|unique:vehicules',
            'nb_places' => 'required|integer|min:1',
            'prix_vente' => 'required|numeric|min:0',
            'carburant' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_secondaire' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_tertiaire' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'etat_vehicule' => 'required|string|in:vente'
        ]);

        // Gestion des images
        $imagePaths = [];
        $imageFields = ['image_principale', 'image_secondaire', 'image_tertiaire'];
        
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('vehicules', 'public');
                $imagePaths[$field] = $path;
            }
        }

        // Création du véhicule
        $vehicule = new Vehicule();
        $vehicule->marque = $validatedData['marque'];
        $vehicule->serie = $validatedData['serie'];
        $vehicule->type_vehicule = $validatedData['type_vehicule'];
        $vehicule->annee = $validatedData['annee'];
        $vehicule->immatriculation = $validatedData['immatriculation'];
        $vehicule->nb_places = $validatedData['nb_places'];
        $vehicule->prix_vente = $validatedData['prix_vente'];
        $vehicule->carburant = $validatedData['carburant'];
        $vehicule->transmission = $validatedData['transmission'];
        $vehicule->etat_vehicule = $validatedData['etat_vehicule'];
        
        // Attribution des chemins d'images
        foreach ($imagePaths as $field => $path) {
            $vehicule->$field = $path;
        }

        $vehicule->save();

        return redirect()->route('admin.vehicules')
            ->with('success', 'Véhicule en vente ajouté avec succès.');
    }

    /**
     * Search for vehicles based on the provided criteria.
     */
    public function search(Request $request)
    {
        try {
            // Validation des entrées
            $validated = $request->validate([
                'brand' => 'nullable|string|max:50',
                'series' => 'nullable|string|max:50',
                'type' => 'nullable|string|max:50',
                'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'transaction' => 'nullable|in:location,vente',
                'page' => 'nullable|integer|min:1'
            ]);

            $perPage = 6;

            $baseQuery = Vehicule::query()->orderBy('disponibilite', 'desc');

            if (!empty($validated['brand'])) {
                $baseQuery->where('marque', '=', $validated['brand']);
            }
            
            if (!empty($validated['series'])) {
                $baseQuery->where('serie', '=', $validated['series']);
            }
            
            if (!empty($validated['type'])) {
                $baseQuery->where('type_vehicule', '=', $validated['type']);
            }
            
            if (!empty($validated['year'])) {
                $baseQuery->where('annee', '=', $validated['year']);
            }

            // Initialisation par défaut des variables
            $vehiculesLocation = collect([]);
            $vehiculesVente = collect([]);

            // Filter par type de transaction si spécifié
            if (!empty($validated['transaction'])) {
                if ($validated['transaction'] == 'location') {
                    $locationQuery = clone $baseQuery;
                    $locationQuery->where('prix_location_abidjan', '>', 0);
                    $vehiculesLocation = $locationQuery->latest()
                        ->paginate($perPage, ['*'], 'location_page')
                        ->appends($request->except('location_page'));
                } elseif ($validated['transaction'] == 'vente') {
                    $venteQuery = clone $baseQuery;
                    $venteQuery->where('prix_vente', '>', 0);
                    $vehiculesVente = $venteQuery->latest()
                        ->paginate($perPage, ['*'], 'vente_page')
                        ->appends($request->except('vente_page'));
                }
            } else {
                // Si aucun type de transaction n'est spécifié, récupérer les deux
                $locationQuery = clone $baseQuery;
                $locationQuery->where('prix_location_abidjan', '>', 0);
                $vehiculesLocation = $locationQuery->latest()
                    ->paginate($perPage, ['*'], 'location_page')
                    ->appends($request->except('location_page'));

                $venteQuery = clone $baseQuery;
                $venteQuery->where('prix_vente', '>', 0);
                $vehiculesVente = $venteQuery->latest()
                    ->paginate($perPage, ['*'], 'vente_page')
                    ->appends($request->except('vente_page'));
            }

            // Récupérer les listes pour filtres dynamiques
            $marques = Vehicule::select('marque')
                ->distinct()
                ->orderBy('marque')
                ->pluck('marque');
                
            $series = Vehicule::select('serie')
                ->distinct()
                ->orderBy('serie')
                ->pluck('serie');
                
            $types = Vehicule::select('type_vehicule')
                ->distinct()
                ->orderBy('type_vehicule')
                ->pluck('type_vehicule');
                
            $annees = Vehicule::select('annee')
                ->distinct()
                ->orderBy('annee', 'desc')
                ->pluck('annee');
                
            $disponibilite = Vehicule::select('disponibilite')
                ->distinct()
                ->orderBy('disponibilite')
                ->pluck('disponibilite');

            return view('index', compact(
                'vehiculesLocation',
                'vehiculesVente',
                'marques',
                'series',
                'types',
                'annees',
                'disponibilite',
            ));
        } catch (\Exception $e) {
            return redirect()->route('index')
                ->with('error', 'Une erreur est survenue lors de la recherche: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified vehicule in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            'marque' => 'required|string|max:255',
            'serie' => 'required|string|max:255',
            'type_vehicule' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation,' . $id,
            'nb_places' => 'required|integer|min:1',
            'prix_location_abidjan' => 'nullable|numeric|min:0',
            'prix_location_interieur' => 'nullable|numeric|min:0',
            'prix_vente' => 'nullable|numeric|min:0',
            'carburant' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_secondaire' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_tertiaire' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'nullable',
            'visibilite' => 'required|boolean',
            'etat_vehicule' => 'required|string|in:location,vente',
            'fin_vehicule' => 'nullable|string|in:vendu,non_vendu',
        ]);

        // Récupérer le véhicule
        $vehicule = Vehicule::findOrFail($id);
        
        // Gérer les valeurs spéciales pour user_id
        if ($request->user_id === 'null') {
            $vehicule->user_id = null;
        } elseif ($request->has('user_id') && $request->user_id !== '') {
            $vehicule->user_id = $validatedData['user_id'];
        }
        
        // Gestion des images
        $imageFields = ['image_principale', 'image_secondaire', 'image_tertiaire'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Supprimer l'ancienne image si elle existe
                if ($vehicule->$field) {
                    Storage::disk('public')->delete($vehicule->$field);
                }
                // Stocker la nouvelle image
                $path = $request->file($field)->store('vehicules', 'public');
                $vehicule->$field = $path;
            }
        }

        // Mise à jour des attributs
        $vehicule->marque = $validatedData['marque'];
        $vehicule->serie = $validatedData['serie'];
        $vehicule->type_vehicule = $validatedData['type_vehicule'];
        $vehicule->annee = $validatedData['annee'];
        $vehicule->immatriculation = $validatedData['immatriculation'];
        $vehicule->nb_places = $validatedData['nb_places'];
        $vehicule->carburant = $validatedData['carburant'];
        $vehicule->transmission = $validatedData['transmission'];
        $vehicule->etat_vehicule = $validatedData['etat_vehicule'];
        $vehicule->visibilite = $validatedData['visibilite'];
        
        // Mise à jour des prix selon l'état du véhicule
        if (isset($validatedData['prix_location_abidjan'])) {
            $vehicule->prix_location_abidjan = $validatedData['prix_location_abidjan'];
        }
        if (isset($validatedData['prix_location_interieur'])) {
            $vehicule->prix_location_interieur = $validatedData['prix_location_interieur'];
        }
        if (isset($validatedData['prix_vente'])) {
            $vehicule->prix_vente = $validatedData['prix_vente'];
        }
        
        // Mettre à jour le statut de vente si applicable
        if ($vehicule->etat_vehicule == 'vente' && isset($validatedData['fin_vehicule'])) {
            $vehicule->fin_vehicule = $validatedData['fin_vehicule'];
        }

        $vehicule->save();

        if($vehicule->user_id == null){
            $vehicule->visibilite = false;
            $vehicule->save();
        }
        if($vehicule->user_id != null){
            $vehicule->visibilite = true;
            $vehicule->save();
        }

        return redirect()->route('admin.vehicules')
            ->with('success', 'Véhicule mis à jour avec succès.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy($id)
    {
        $vehicule = Vehicule::findOrFail($id);
        
        // Delete the vehicle images from storage
        $imageFields = ['image_principale', 'image_secondaire', 'image_tertiaire'];
        foreach ($imageFields as $field) {
            if ($vehicule->$field) {
                Storage::disk('public')->delete($vehicule->$field);
            }
        }
        
        // Delete the vehicle record
        $vehicule->delete();
        
        return redirect()->route('admin.vehicules')
            ->with('success', 'Véhicule supprimé avec succès.');
    }

    /**
     * Get series by brand for AJAX requests
     */
    public function getSeriesByBrand($brand)
    {
        try {
            // Récupérer toutes les séries distinctes pour la marque spécifiée
            $series = Vehicule::where('marque', $brand)
                ->select('id', 'serie as name')
                ->distinct('serie')
                ->orderBy('serie')
                ->get();
            
            return response()->json($series);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}