@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <div class="mb-4 d-flex align-items-center">
        <a href="{{ route('admin.vehicules') }}" class="btn btn-outline-primary me-3">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <h1 class="fw-bold text-primary mb-0">Ajouter un véhicule</h1>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <button type="button" class="btn btn-primary w-100 py-3" style="border-radius: 25px;" data-bs-toggle="modal" data-bs-target="#ajoutVehiculeLocation">
                <i class="fas fa-plus-circle me-2"></i> Ajouter une voiture location
            </button>
        </div>
        <div class="col-md-6 mb-3">
            <button type="button" class="btn btn-success w-100 py-3" style="border-radius: 25px;" data-bs-toggle="modal" data-bs-target="#ajoutVehiculeVente">
                <i class="fas fa-plus-circle me-2"></i> Ajouter une voiture vente
            </button>
        </div>
    </div>

    <!-- Overlay avec loader central -->
    <div id="fullPageLoader" class="overlay-loader d-none">
        <div class="chase-wrapper">
            <div class="chase chase-large"></div>
        </div>
    </div>

    <!-- Modal Ajout Véhicule Location -->
    <div class="modal fade" id="ajoutVehiculeLocation" tabindex="-1" aria-labelledby="ajoutVehiculeLocationLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="ajoutVehiculeLocationLabel">Ajouter une voiture location</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('vehicules.store_location') }}" method="POST" enctype="multipart/form-data" id="formVehiculeLocation">
                        @csrf
                        <input type="hidden" name="etat_vehicule" value="location">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="marque_location" class="form-label">Marque</label>
                                <input type="text" class="form-control" id="marque_location" name="marque" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="serie_location" class="form-label">Serie</label>
                                <input type="text" class="form-control" id="serie_location" name="serie" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="type_vehicule_location" class="form-label">Type de Vehicule</label>
                                <select class="form-select" id="type_vehicule_location" name="type_vehicule" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Berline">Berline</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Utilitaire">Utilitaire</option>
                                    <option value="Luxe">Luxe</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="annee_location" class="form-label">Annee</label>
                                <input type="number" class="form-control" id="annee_location" name="annee" min="1900" max="{{ date('Y') + 1 }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="immatriculation_location" class="form-label">Immatriculation</label>
                                <input type="text" class="form-control" id="immatriculation_location" name="immatriculation" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nb_places_location" class="form-label">Nombre de places</label>
                                <input type="number" class="form-control" id="nb_places_location" name="nb_places" min="1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="prix_location_abidjan" class="form-label">Prix location Abidjan</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="prix_location_abidjan" name="prix_location_abidjan" min="0" step="0.01" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="prix_location_interieur" class="form-label">Prix location Interieur</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="prix_location_interieur" name="prix_location_interieur" min="0" step="0.01" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="carburant_location" class="form-label">Carburant</label>
                                <select class="form-select" id="carburant_location" name="carburant" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Essence">Essence</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybride">Hybride</option>
                                    <option value="Électrique">Électrique</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="transmission_location" class="form-label">Transmission</label>
                                <select class="form-select" id="transmission_location" name="transmission" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Manuelle">Manuelle</option>
                                    <option value="Automatique">Automatique</option>
                                    <option value="Semi-automatique">Semi-automatique</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="chauffeur" class="form-label">Chauffeur</label>
                                <select class="form-select" id="chauffeur" name="user_id">
                                    <option value="" selected>Aucun chauffeur</option>
                                    @php
                                        $chauffeurs = App\Models\User::where('status', 'chauffeur')
                                                     ->whereNotIn('id', App\Models\Vehicule::whereNotNull('user_id')->pluck('user_id'))
                                                     ->get();
                                    @endphp
                                    @foreach ($chauffeurs as $chauffeur)
                                        <option value="{{ $chauffeur->id }}">{{ $chauffeur->name }} ({{ $chauffeur->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="image_principale_location" class="form-label">Image Principale</label>
                                <input type="file" class="form-control" id="image_principale_location" name="image_principale" accept="image/*" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="image_secondaire_location" class="form-label">Image Secondaire</label>
                                <input type="file" class="form-control" id="image_secondaire_location" name="image_secondaire" accept="image/*">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="image_tertiaire_location" class="form-label">Image Tertiaire</label>
                                <input type="file" class="form-control" id="image_tertiaire_location" name="image_tertiaire" accept="image/*">
                            </div>
                        </div>

                        <!-- Champ caché pour prix_vente (valeur minimale requise) -->
                        <input type="hidden" name="prix_vente" value="0">

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2" id="btnSubmitLocation">
                                <span class="button-text">Enregistrer le véhicule</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout Véhicule Vente -->
    <div class="modal fade" id="ajoutVehiculeVente" tabindex="-1" aria-labelledby="ajoutVehiculeVenteLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="ajoutVehiculeVenteLabel">Ajouter une voiture vente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('vehicules.store_vente') }}" method="POST" enctype="multipart/form-data" id="formVehiculeVente">
                        @csrf
                        <input type="hidden" name="etat_vehicule" value="vente">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="marque_vente" class="form-label">Marque</label>
                                <input type="text" class="form-control" id="marque_vente" name="marque" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="serie_vente" class="form-label">Serie</label>
                                <input type="text" class="form-control" id="serie_vente" name="serie" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="type_vehicule_vente" class="form-label">Type de Vehicule</label>
                                <select class="form-select" id="type_vehicule_vente" name="type_vehicule" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Berline">Berline</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Utilitaire">Utilitaire</option>
                                    <option value="Luxe">Luxe</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="annee_vente" class="form-label">Annee</label>
                                <input type="number" class="form-control" id="annee_vente" name="annee" min="1900" max="{{ date('Y') + 1 }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="immatriculation_vente" class="form-label">Immatriculation</label>
                                <input type="text" class="form-control" id="immatriculation_vente" name="immatriculation" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nb_places_vente" class="form-label">Nombre de places</label>
                                <input type="number" class="form-control" id="nb_places_vente" name="nb_places" min="1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="prix_vente" class="form-label">Prix de vente</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="prix_vente" name="prix_vente" min="0" step="0.01" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="carburant_vente" class="form-label">Carburant</label>
                                <select class="form-select" id="carburant_vente" name="carburant" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Essence">Essence</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybride">Hybride</option>
                                    <option value="Électrique">Électrique</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="transmission_vente" class="form-label">Transmission</label>
                                <select class="form-select" id="transmission_vente" name="transmission" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Manuelle">Manuelle</option>
                                    <option value="Automatique">Automatique</option>
                                    <option value="Semi-automatique">Semi-automatique</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="image_principale_vente" class="form-label">Image Principale</label>
                                <input type="file" class="form-control" id="image_principale_vente" name="image_principale" accept="image/*" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="image_secondaire_vente" class="form-label">Image Secondaire</label>
                                <input type="file" class="form-control" id="image_secondaire_vente" name="image_secondaire" accept="image/*">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="image_tertiaire_vente" class="form-label">Image Tertiaire</label>
                                <input type="file" class="form-control" id="image_tertiaire_vente" name="image_tertiaire" accept="image/*">
                            </div>
                        </div>

                        <!-- Champs cachés pour valeurs minimales requises -->
                        <input type="hidden" name="prix_location_abidjan" value="0">
                        <input type="hidden" name="prix_location_interieur" value="0">

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success py-2" id="btnSubmitVente">
                                <span class="button-text">Enregistrer le véhicule</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/**
  * Chase
  *
  * @author jh3y - jheytompkins.com
*/
.chase {
  position: relative; }
  .chase:after, .chase:before {
    content: '';
    height: 30px;
    width: 30px;
    display: block;
    -webkit-animation: out .5s backwards, spin 1.25s .5s infinite ease;
            animation: out .5s backwards, spin 1.25s .5s infinite ease;
    border: 5px solid var(--bs-primary);
    border-radius: 100%;
    -webkit-box-shadow: 0 -40px 0 -5px var(--bs-primary);
            box-shadow: 0 -40px 0 -5px var(--bs-primary);
    position: absolute; }
  .chase:after {
    -webkit-animation-delay: 0s, 1.25s;
            animation-delay: 0s, 1.25s; }

@-webkit-keyframes out {
  from {
    -webkit-box-shadow: 0 0 0 -5px var(--bs-primary);
            box-shadow: 0 0 0 -5px var(--bs-primary); } }

@keyframes out {
  from {
    -webkit-box-shadow: 0 0 0 -5px var(--bs-primary);
            box-shadow: 0 0 0 -5px var(--bs-primary); } }

@-webkit-keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg); } }

@keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg); } }

/* Overlay fullscreen loader */
.overlay-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.chase-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}

.chase-large:after, .chase-large:before {
    height: 60px;
    width: 60px;
    border-width: 8px;
    -webkit-box-shadow: 0 -80px 0 -8px var(--bs-primary);
    box-shadow: 0 -80px 0 -8px var(--bs-primary);
}

/* Styles pour le form bloqué */
.form-disabled {
    filter: blur(3px);
    pointer-events: none;
    user-select: none;
    opacity: 0.7;
}

/* Autres styles existants */
button {
    position: relative;
}

button.loading .button-text {
    visibility: hidden;
}

button.loading .loader {
    display: inline-block !important;
}

#btnSubmitVente .chase:after, #btnSubmitVente .chase:before {
    border-color: var(--bs-success);
    -webkit-box-shadow: 0 -40px 0 -5px var(--bs-success);
    box-shadow: 0 -40px 0 -5px var(--bs-success);
}

#btnSubmitLocation .chase:after, #btnSubmitLocation .chase:before {
    border-color: var(--bs-primary); 
    -webkit-box-shadow: 0 -40px 0 -5px var(--bs-primary);
    box-shadow: 0 -40px 0 -5px var(--bs-primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form Location
    const formLocation = document.getElementById('formVehiculeLocation');
    const btnSubmitLocation = document.getElementById('btnSubmitLocation');
    const fullPageLoader = document.getElementById('fullPageLoader');
    
    if (formLocation) {
        formLocation.addEventListener('submit', function(e) {
            // Afficher le loader plein écran
            fullPageLoader.classList.remove('d-none');
            
            // Désactiver le formulaire
            document.querySelector('.modal-body').classList.add('form-disabled');
            
            // Pour être sûr que le loader s'affiche avant la soumission
            setTimeout(function() {
                // Le formulaire sera soumis naturellement
            }, 100);
        });
    }
    
    // Form Vente
    const formVente = document.getElementById('formVehiculeVente');
    const btnSubmitVente = document.getElementById('btnSubmitVente');
    
    if (formVente) {
        formVente.addEventListener('submit', function(e) {
            // Afficher le loader plein écran
            fullPageLoader.classList.remove('d-none');
            
            // Désactiver le formulaire
            document.querySelector('.modal-body').classList.add('form-disabled');
            
            // Pour être sûr que le loader s'affiche avant la soumission
            setTimeout(function() {
                // Le formulaire sera soumis naturellement
            }, 100);
        });
    }
});
</script>

@endsection 