<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicule;
use App\Models\LocationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Benefice;

class LocationRequestController extends Controller
{
    /**
     * Affiche le formulaire de demande de location
     */
    public function create($vehicule_id)
    {
        // Vérification de l'authentification
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        // Validation de l'ID du véhicule
        $validated = validator(['vehicule_id' => $vehicule_id], [
            'vehicule_id' => 'required|integer|exists:vehicules,id'
        ])->validate();

        try {
            $vehicule = Vehicule::findOrFail($vehicule_id);
            
            // Vérifier que le véhicule est disponible et en location
            if (!$vehicule->disponibilite || !$vehicule->visibilite || $vehicule->etat_vehicule !== 'location') {
                return redirect()->back()->with('error', 'Ce véhicule n\'est pas disponible à la location.');
            }

            // Vérifier si le véhicule a déjà des demandes en attente ou approuvées
            $demandesExistantes = LocationRequest::where('vehicule_id', $vehicule_id)
                ->whereIn('statut', ['en_attente', 'approuvee'])
                ->exists();

            if ($demandesExistantes) {
                return redirect()->back()->with('error', 'Ce véhicule a déjà une demande en cours de traitement.');
            }

            // Récupérer les réservations approuvées pour ce véhicule (pour information)
            $reservationsApprouvees = LocationRequest::where('vehicule_id', $vehicule_id)
                ->where('statut', 'approuvee')
                ->orderBy('date_debut')
                ->get(['date_debut', 'date_fin']);

            // Vérifier si l'utilisateur a déjà une demande en cours pour ce véhicule
            $existingRequest = LocationRequest::where('user_id', auth()->id())
                ->where('vehicule_id', $vehicule_id)
                ->whereIn('statut', ['en_attente', 'approuvee'])
                ->first();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Vous avez déjà une demande en cours pour ce véhicule.');
            }
            
            return view('autres.rqt_location', compact('vehicule', 'reservationsApprouvees'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Véhicule non trouvé.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Enregistre une nouvelle demande de location
     */
    public function store(Request $request){

        // Validation des données du formulaire
        $validated = $request->validate([
            'vehicule_id' => 'required|integer|exists:vehicules,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_depart' => 'required|date_format:H:i',
            'ville_depart' => 'required|string|max:255',
            'ville_destination' => 'required|string|max:255',
            'mode_paiement' => 'required|string',
            'prix_total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'point_depart' => 'nullable|string|max:255',
        ]);
        
        try {
            // Récupération du véhicule
            $vehicule = Vehicule::findOrFail($request->vehicule_id);
            
            // Vérification que le véhicule est disponible et en location
            if (!$vehicule->disponibilite || $vehicule->etat_vehicule !== 'location') {
                return redirect()->back()->with('error', 'Ce véhicule n\'est pas disponible à la location.')->withInput();
            }
            
            // Vérifier s'il existe déjà des demandes en attente ou approuvées pour ce véhicule
            $demandesExistantes = LocationRequest::where('vehicule_id', $request->vehicule_id)
                ->whereIn('statut', ['en_attente', 'approuvee'])
                ->exists();
                
            if ($demandesExistantes) {
                return redirect()->back()->with('error', 'Ce véhicule a déjà une demande en cours de traitement.')->withInput();
            }
            
            // Vérifier s'il y a des chevauchements avec d'autres réservations approuvées
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = Carbon::parse($request->date_fin);
            
            $chevauchement = LocationRequest::where('vehicule_id', $request->vehicule_id)
                ->where('statut', 'approuvee')
                ->where(function($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                        ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                        ->orWhere(function($q) use ($dateDebut, $dateFin) {
                            $q->where('date_debut', '<=', $dateDebut)
                                ->where('date_fin', '>=', $dateFin);
                        });
                })
                ->exists();
                
            if ($chevauchement) {
                return redirect()->back()->with('error', 'Le véhicule n\'est pas disponible aux dates sélectionnées.')->withInput();
            }
            
            // Récupération de l'utilisateur connecté
            $user = Auth::user();
            
            // Création de la demande de location
            $locationRequest = new LocationRequest();
            
            // Attribution des valeurs selon les exigences
            $locationRequest->user_id = $user->id;
            $locationRequest->vehicule_id = $vehicule->id;
            $locationRequest->marque_vehicule = $vehicule->marque;
            $locationRequest->serie_vehicule = $vehicule->serie;
            
            // Attribution des informations du chauffeur si disponible
            if ($vehicule->user_id) {
                $chauffeur = User::find($vehicule->user_id);
                if ($chauffeur) {
                    $locationRequest->chauffeur_id = $chauffeur->id;
                    $locationRequest->nom_chauffeur = $chauffeur->name ?? 'Non spécifié';
                    $locationRequest->prenom_chauffeur = $chauffeur->prenom ?? 'Non spécifié';
                    $locationRequest->numero_telephone_chauffeur = $chauffeur->telephone ?? 'Non spécifié';
                }
            } else {
                // Valeurs par défaut si pas de chauffeur assigné
                $locationRequest->nom_chauffeur = 'À déterminer';
                $locationRequest->prenom_chauffeur = 'À déterminer';
                $locationRequest->numero_telephone_chauffeur = 'À déterminer';
            }
            
            // Attribution des autres informations
            $locationRequest->date_debut = $request->date_debut;
            $locationRequest->date_fin = $request->date_fin;
            $locationRequest->heure_depart = $request->heure_depart;
            $locationRequest->point_depart = $request->point_depart;
            $locationRequest->lieu_depart = $request->ville_depart;
            $locationRequest->lieu_arrivee = $request->ville_destination;
            $locationRequest->prix_total = $request->prix_total;
            $locationRequest->mode_paiement = $request->mode_paiement;
            $locationRequest->notes = $request->notes;
            
            // Informations utilisateur
            $locationRequest->nom = $user->name ?? 'Non spécifié';
            $locationRequest->prenom = $user->prenom ?? 'Non spécifié';
            $locationRequest->email = $user->email;
            $locationRequest->numero_telephone = $user->telephone ?? 'Non spécifié';
            
            // Génération d'une référence de paiement unique
            $reference = 'LOC-' . strtoupper(substr(md5(uniqid()), 0, 8));
            
            $locationRequest->reference_paiement = $reference;
            // Statut par défaut
            $locationRequest->statut = 'en_attente';
            
            // Enregistrement de la demande
            $locationRequest->save();
            
            // Mettre à jour la disponibilité du véhicule à 0 (non disponible)
            $vehicule->disponibilite = false;
            $vehicule->save();

            $benefice = new Benefice();
            $benefice->montant = $request->prix_total;
            $benefice->categorie = 'location';
            $benefice->remarques = $reference;
            $benefice->date = now()->format('Y-m-d');
            $benefice->user_id = $user->id;
            $benefice->location_request_id = $locationRequest->id;
            $benefice->save();
            
            // Redirection avec message de succès
            return redirect()->route('mes_reservations')->with('success', 'Votre demande de location a été enregistrée avec succès. Elle est en cours de traitement.');
            
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande: ' . $e->getMessage())->withInput();
        }
    }

    public function mes_reservations(){
        $query = LocationRequest::where('user_id', auth()->id());
        
        // Filtrer par statut si demandé
        if (request()->has('statut')) {
            $query->where('statut', request('statut'));
        }
        
        $locationRequests = $query->orderBy('created_at', 'desc')->get();
        return view('autres.mes_reservations', compact('locationRequests'));
    }

    /**
     * Affiche la page de confirmation de demande
     */
    public function confirmation($id)
    {
        $locationRequest = LocationRequest::with('vehicule')->findOrFail($id);
        
        // Vérifier que l'utilisateur est bien le propriétaire de la demande
        if ($locationRequest->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('location.confirmation', compact('locationRequest'));
    }

    /**
     * Affiche la liste des demandes de l'utilisateur connecté
     */
    public function userRequests()
    {
        $locationRequests = LocationRequest::with('vehicule')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('location.user_requests', compact('locationRequests'));
    }

    /**
     * Affiche la liste des demandes (pour admin)
     */
    public function adminIndex()
    {
        $locationRequests = LocationRequest::with(['vehicule', 'user'])
            ->latest()
            ->paginate(15);
            
        return view('admin.location.index', compact('locationRequests'));
    }

    /**
     * Met à jour le statut d'une demande
     */
    public function updateStatus(Request $request, $id)
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        $validatedData = $request->validate([
            'statut' => 'required|in:en_attente,approuvee,refusee,terminee',
            'mode_paiement' => 'nullable|string|max:255',
        ]);
        
        $locationRequest = LocationRequest::findOrFail($id);
        $oldStatus = $locationRequest->statut;
        $newStatus = $validatedData['statut'];
        
        // Si le statut n'a pas changé, ne rien faire
        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('info', 'Aucun changement de statut détecté.');
        }
        
        $locationRequest->statut = $newStatus;
        
        if ($request->has('mode_paiement')) {
            $locationRequest->mode_paiement = $validatedData['mode_paiement'];
        }
        
        $vehicule = $locationRequest->vehicule;
        
        // Si la demande passe à approuvée
        if ($newStatus === 'approuvee') {
            // Vérifier les conflits de dates avec d'autres réservations approuvées
            $chevauchement = LocationRequest::where('vehicule_id', $locationRequest->vehicule_id)
                ->where('id', '!=', $locationRequest->id)
                ->where('statut', 'approuvee')
                ->where(function($query) use ($locationRequest) {
                    $query->whereBetween('date_debut', [$locationRequest->date_debut, $locationRequest->date_fin])
                        ->orWhereBetween('date_fin', [$locationRequest->date_debut, $locationRequest->date_fin])
                        ->orWhere(function($q) use ($locationRequest) {
                            $q->where('date_debut', '<=', $locationRequest->date_debut)
                                ->where('date_fin', '>=', $locationRequest->date_fin);
                        });
                })
                ->exists();
                
            if ($chevauchement) {
                return redirect()->back()->with('error', 'Il y a un conflit de dates avec une autre réservation approuvée.');
            }
            
            // Le véhicule est déjà marqué comme indisponible dès la création de la demande
            // donc nous n'avons pas besoin de le changer ici
        }
        // Si la demande devient refusée ou terminée ou annulée
        else if ($newStatus === 'refusee' || $newStatus === 'terminee' || $newStatus === 'annulee') {
            // Rendre le véhicule à nouveau disponible
            if ($vehicule) {
                $vehicule->disponibilite = true;
                $vehicule->save();
            }
        }
        
        // Sauvegarder les modifications
        $locationRequest->save();
        
        if ($vehicule) {
            $vehicule->save();
        }
        
        $statusMessages = [
            'en_attente' => 'mise en attente',
            'approuvee' => 'approuvée',
            'refusee' => 'refusée',
            'terminee' => 'marquée comme terminée'
        ];
        
        $message = isset($statusMessages[$newStatus]) ? 
            'La réservation a été ' . $statusMessages[$newStatus] . ' avec succès.' : 
            'Le statut de la réservation a été mis à jour avec succès.';
            
        return redirect()->back()->with('success', $message);
    }

    /**
     * Met à jour le lieu de départ d'une réservation
     */
    public function updateDeparture(Request $request, $id)
    {
        // Valider les données
        $validated = $request->validate([
            'point_depart' => 'required|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'address_details' => 'nullable|string',
        ]);

        try {
            // Récupérer la demande de location
            $locationRequest = LocationRequest::findOrFail($id);
            
            // Vérifier que l'utilisateur est autorisé à modifier cette demande
            if ($locationRequest->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette réservation.');
            }
            
            // Vérifier que la demande n'est pas dans un état terminal
            if (in_array($locationRequest->statut, ['terminee', 'annulee', 'rejetee'])) {
                return redirect()->back()->with('error', 'Cette réservation ne peut plus être modifiée.');
            }
            
            // Mettre à jour les informations de départ
            $locationRequest->point_depart = $request->point_depart;
            $locationRequest->save();
            
            return redirect()->route('location.show', $locationRequest->id)->with('success', 'Le lieu de départ a été mis à jour avec succès.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du lieu de départ: ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'une demande
     */
    public function show($id)
    {
        $locationRequest = LocationRequest::with(['vehicule', 'user'])->findOrFail($id);
        
        // Vérifier que l'utilisateur est admin ou le propriétaire de la demande
        if ($locationRequest->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }
        
        // Selon le rôle de l'utilisateur, rediriger vers la vue appropriée
        if (Auth::user()->hasRole('admin')) {
            return view('admin.reservations.show', ['reservation' => $locationRequest]);
        } else {
            return view('location.show', compact('locationRequest'));
        }
    }

    public function showvente($id)
    {
        $vehicule = Vehicule::findOrFail($id);
        return view('autres.rdv_vente', compact('vehicule'));
    }

    /**
     * Enregistre un nouveau rendez-vous de vente
     */
    public function store_rdv(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'vehicule_id' => 'required|integer|exists:vehicules,id',
            'date_rdv' => 'required|date|after_or_equal:today',
            'heure_rdv' => 'required|date_format:H:i',
            'nom_complet' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);
        
        try {
            // Récupération du véhicule
            $vehicule = Vehicule::findOrFail($request->vehicule_id);
            
            // Création du rendez-vous de vente
            $rendezVous = new \App\Models\RendezVousVente();
            
            // Informations du véhicule
            $rendezVous->vehicule_id = $vehicule->id;
            $rendezVous->marque_vehicule = $vehicule->marque;
            $rendezVous->serie_vehicule = $vehicule->serie;
            $rendezVous->annee_vehicule = $vehicule->annee;
            $rendezVous->transmission_vehicule = $vehicule->transmission;
            $rendezVous->carburant_vehicule = $vehicule->carburant;
            $rendezVous->nb_places_vehicule = $vehicule->nb_places;
            $rendezVous->image_vehicule = $vehicule->image_principale;
            $rendezVous->prix_vehicule = $vehicule->prix_vente;
            
            // Informations du rendez-vous
            $rendezVous->date_rdv = $request->date_rdv;
            $rendezVous->heure_rdv = $request->heure_rdv;
            $rendezVous->nom_complet = $request->nom_complet;
            $rendezVous->telephone = $request->telephone;
            $rendezVous->email = $request->email;
            $rendezVous->notes = $request->notes;
            
            // Statut par défaut
            $rendezVous->statut = 'en_attente';
            
            // Utilisateur connecté (si disponible)
            $rendezVous->user_id = auth()->check() ? auth()->id() : null;
            
            // Enregistrement du rendez-vous
            $rendezVous->save();
            
            // Rendre le véhicule à nouveau disponible
            if ($vehicule) {
                $vehicule->visibilite = false;
                $vehicule->disponibilite = false;
                $vehicule->save();
            }
            // Redirection avec message de succès
            return redirect()->route('mes_rdv')->with('success', 'Votre rendez-vous a été enregistré avec succès. Nous vous contacterons pour confirmation.');
            
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre rendez-vous: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Approuve une réservation
     */
    public function approve($id)
    {
        $reservation = LocationRequest::findOrFail($id);
        
        if ($reservation->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les réservations en attente peuvent être approuvées.');
        }
        
        // Vérifier s'il y a des chevauchements avec d'autres réservations approuvées
        $chevauchement = LocationRequest::where('vehicule_id', $reservation->vehicule_id)
            ->where('id', '!=', $reservation->id)
            ->where('statut', 'approuvee')
            ->where(function($query) use ($reservation) {
                $query->whereBetween('date_debut', [$reservation->date_debut, $reservation->date_fin])
                    ->orWhereBetween('date_fin', [$reservation->date_debut, $reservation->date_fin])
                    ->orWhere(function($q) use ($reservation) {
                        $q->where('date_debut', '<=', $reservation->date_debut)
                            ->where('date_fin', '>=', $reservation->date_fin);
                    });
            })
            ->exists();
            
        if ($chevauchement) {
            return redirect()->back()->with('error', 'Il y a un conflit de dates avec une autre réservation approuvée.');
        }
        
        $reservation->statut = 'approuvee';
        $reservation->save();
        
        // Le véhicule est déjà marqué comme indisponible dès la création de la demande
        // donc nous n'avons pas besoin de le modifier ici
        
        // On pourrait envoyer un email au client ici
        
        return redirect()->route('admin.reservations')->with('success', 'La réservation a été approuvée avec succès.');
    }

    /**
     * Rejette une réservation
     */
    public function reject($id)
    {
        $reservation = LocationRequest::findOrFail($id);

        // Supprimer l'enregistrement correspondant dans la table des bénéfices
        Benefice::where('location_request_id', $id)->delete();
        
        if ($reservation->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les réservations en attente peuvent être rejetées.');
        }
        
        $reservation->statut = 'refusee';
        $reservation->save();

        // Supprimer l'enregistrement correspondant dans la table des bénéfices
        Benefice::where('location_request_id', $id)->delete();
        
        
        // Rendre le véhicule à nouveau disponible
        $vehicule = $reservation->vehicule;
        if ($vehicule) {
            $vehicule->disponibilite = true;
            $vehicule->save();
        }
        
        // On pourrait envoyer un email au client ici
        
        return redirect()->route('admin.reservations')->with('success', 'La réservation a été rejetée.');
    }

    /**
     * Marque une réservation comme terminée
     */
    public function complete($id)
    {
        $reservation = LocationRequest::findOrFail($id);
        
        if ($reservation->statut !== 'approuvee') {
            return redirect()->back()->with('error', 'Seules les réservations approuvées peuvent être marquées comme terminées.');
        }
        
        $dateFinReservation = Carbon::parse($reservation->date_fin);
        $aujourdhui = Carbon::now();
        
        // Déterminer si la réservation est terminée naturellement (date de fin passée)
        $terminaisonNaturelle = $aujourdhui->greaterThanOrEqualTo($dateFinReservation);
        
        // Marquer la réservation comme terminée
        $reservation->statut = 'terminee';
        $reservation->save();
        
        // Rendre le véhicule à nouveau disponible
        $vehicule = Vehicule::find($reservation->vehicule_id);
        if ($vehicule) {
            $vehicule->disponibilite = true;
            $vehicule->save();
        }
        
        $message = $terminaisonNaturelle 
            ? 'La réservation a été automatiquement marquée comme terminée car la date de fin est passée.' 
            : 'La réservation a été marquée comme terminée.';
        
        return redirect()->route('admin.reservations')->with('success', $message);
    }

    /**
     * Permet à un utilisateur d'annuler sa propre réservation en attente
     */
    public function cancel($id)
    {
        $reservation = LocationRequest::findOrFail($id);
        
        // Vérifier que l'utilisateur est bien le propriétaire de la demande
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler cette réservation.');
        }
        
        // Vérifier que la réservation est en attente
        if ($reservation->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les réservations en attente peuvent être annulées.');
        }
        
        // Marquer comme annulée
        $reservation->statut = 'annulee';
        $reservation->save();
        
        // S'assurer que le véhicule reste disponible
        $vehicule = $reservation->vehicule;
        if ($vehicule) {
            $vehicule->disponibilite = true;
            $vehicule->save();
        }
        
        return redirect()->route('mes_reservations')->with('success', 'Votre réservation a été annulée avec succès.');
    }

    public function mes_rdv()
    {
        $rendezVous = \App\Models\RendezVousVente::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        
        // Filtrer par statut si demandé
        if (request()->has('statut')) {
            $rendezVous = \App\Models\RendezVousVente::where('user_id', Auth::id())
                ->where('statut', request('statut'))
                ->get();
        }
        
        return view('autres.mes_rdv', compact('rendezVous'));
    }
    
    /**
     * Permet à un utilisateur d'annuler son propre rendez-vous de vente en attente
     */
    public function cancel_rdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        // Vérifier que l'utilisateur est bien le propriétaire du rendez-vous
        if ($rendezVous->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler ce rendez-vous.');
        }
        
        // Vérifier que le rendez-vous est en attente
        if ($rendezVous->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seuls les rendez-vous en attente peuvent être annulés.');
        }
        
        // Marquer comme annulé
        $rendezVous->statut = 'annule';
        $rendezVous->save();
        
        // Rendre le véhicule à nouveau visible et disponible
        $vehicule = $rendezVous->vehicule;
        if ($vehicule) {
            $vehicule->visibilite = true;
            $vehicule->disponibilite = true;
            $vehicule->save();
        }
        
        return redirect()->route('mes_rdv')->with('success', 'Votre rendez-vous a été annulé avec succès.');
    }

    /**
     * Affiche les détails d'un rendez-vous pour l'admin
     */
    public function showRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        return view('admin.rdv.show', compact('rendezVous'));
    }

    /**
     * Confirme un rendez-vous
     */
    public function confirmRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        if ($rendezVous->statut !== 'en_attente') {
            return redirect()->back()->with('error', 'Seuls les rendez-vous en attente peuvent être confirmés.');
        }
        
        $rendezVous->statut = 'confirme';
        $rendezVous->save();
        
        return redirect()->route('admin.rdv')->with('success', 'Le rendez-vous a été confirmé avec succès.');
    }

    /**
     * Annule un rendez-vous (admin)
     */
    public function cancelRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        if ($rendezVous->statut === 'annule') {
            return redirect()->back()->with('error', 'Ce rendez-vous est déjà annulé.');
        }
        
        $rendezVous->statut = 'annule';
        $rendezVous->save();
        
        // Rendre le véhicule à nouveau visible et disponible
        $vehicule = Vehicule::find($rendezVous->vehicule_id);
        if ($vehicule) {
            $vehicule->visibilite = true;
            $vehicule->disponibilite = true;
            $vehicule->save();
        }
        
        return redirect()->route('admin.rdv')->with('success', 'Le rendez-vous a été annulé avec succès.');
    }

    /**
     * Marque un rendez-vous comme en négociation
     */
    public function negociationRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        if ($rendezVous->statut !== 'confirme') {
            return redirect()->back()->with('error', 'Seuls les rendez-vous confirmés peuvent être mis en négociation.');
        }
        
        $rendezVous->statut = 'en_negociation';
        $rendezVous->save();
        
        return redirect()->route('admin.rdv')->with('success', 'Le rendez-vous a été mis en négociation avec succès.');
    }

    /**
     * Marque un rendez-vous comme terminé
     */
    public function completeRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        if ($rendezVous->statut !== 'en_negociation') {
            return redirect()->back()->with('error', 'Seuls les rendez-vous en négociation peuvent être marqués comme terminés.');
        }
        
        $rendezVous->statut = 'termine';
        $rendezVous->save();
        
        return redirect()->route('admin.rdv')->with('success', 'Le rendez-vous a été marqué comme terminé avec succès.');
    }

    public function show_r($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        return view('location.show_r', compact('rendezVous'));
    }
    
    /**
     * Marque un rendez-vous comme acheté
     */
    public function acheterRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        if ($rendezVous->statut !== 'termine') {
            return redirect()->back()->with('error', 'Seuls les rendez-vous terminés peuvent être marqués comme achetés.');
        }
        
        $rendezVous->fin_rdv = 'achete';
        $rendezVous->save();
        
        // Mettre à jour le statut du véhicule comme vendu
        $vehicule = Vehicule::find($rendezVous->vehicule_id);
        if ($vehicule) {
            $vehicule->visibilite = false;
            $vehicule->disponibilite = false;
            $vehicule->fin_vehicule = 'vendu';
            $vehicule->save();
        }
        
        return redirect()->route('admin.rdv')->with('success', 'Le véhicule a été marqué comme acheté avec succès.');
    }
    
    /**
     * Marque un rendez-vous comme refusé
     */
    public function refuserRdv($id)
    {
        $rendezVous = \App\Models\RendezVousVente::findOrFail($id);
        
        if ($rendezVous->statut !== 'termine') {
            return redirect()->back()->with('error', 'Seuls les rendez-vous terminés peuvent être marqués comme refusés.');
        }
        
        $rendezVous->fin_rdv = 'refuse';
        $rendezVous->save();
        
        // Rendre le véhicule à nouveau visible et disponible
        $vehicule = Vehicule::find($rendezVous->vehicule_id);
        if ($vehicule) {
            $vehicule->visibilite = true;
            $vehicule->disponibilite = true;
            $vehicule->save();
        }
        
        return redirect()->route('admin.rdv')->with('success', 'Le rendez-vous a été marqué comme refusé avec succès.');
    }
}
