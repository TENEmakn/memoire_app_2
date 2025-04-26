@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
<style>
    #map {
        height: 350px;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .leaflet-bar a, .leaflet-bar a:hover {
        background-color: #fff;
        color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .leaflet-bar a.leaflet-disabled {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    .leaflet-touch .leaflet-control-layers, .leaflet-touch .leaflet-bar {
        border: 2px solid rgba(23, 162, 184, 0.4);
        border-radius: 6px;
    }
    
    .map-instructions {
        padding: 10px 15px;
        background-color: rgba(250, 250, 250, 0.9);
        border-left: 4px solid #17a2b8;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .leaflet-container {
        font-family: 'Roboto', Arial, sans-serif;
    }
    
    .location-actions {
        display: flex;
        flex-direction: column;
        margin-top: 12px;
        gap: 10px;
    }
    
    .marker-pulse {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(23, 162, 184, 0.7);
        }
        
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(23, 162, 184, 0);
        }
        
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(23, 162, 184, 0);
        }
    }
    
    .leaflet-control-geocoder-form input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 12px;
        width: 100%;
        max-width: 250px;
        font-size: 14px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .map-overlay {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: white;
        padding: 10px 15px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        font-size: 13px;
        max-width: 200px;
    }
    
    /* Nouveaux styles pour les fonctionnalités de carte améliorées */
    .hover-tooltip {
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid #17a2b8;
        border-radius: 4px;
        padding: 6px 8px;
        font-size: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    
    .address-card {
        background: white;
        padding: 12px;
        border-radius: 6px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 10px;
        border-left: 4px solid #17a2b8;
        font-size: 13px;
    }
    
    .address-card h6 {
        margin-bottom: 8px;
        color: #17a2b8;
        font-weight: 600;
    }
    
    .map-layers-control {
        border-radius: 4px;
        overflow: hidden;
    }
    
    .map-layers-control label {
        margin-bottom: 0;
        padding: 5px 8px;
    }
    
    .position-marker {
        position: relative;
    }
    
    .position-marker::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #17a2b8;
    }
    
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 3px 14px rgba(0, 0, 0, 0.2);
    }
    
    .leaflet-popup-content {
        margin: 12px 16px;
    }
    
    .location-badge {
        display: inline-block;
        background-color: #e9ecef;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-top: 6px;
    }
    
    .map-button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    
    .map-button {
        flex: 1 1 30%;
        min-width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    @media (max-width: 576px) {
        .map-button {
            flex: 1 1 100%;
        }
    }
    
    .map-button:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    .map-button i {
        margin-right: 6px;
        color: #17a2b8;
    }
    
    /* Responsive styles */
    @media (max-width: 767px) {
        .card-body {
            padding: 1rem;
        }
        
        #map {
            height: 300px;
        }
        
        .price-card h5 {
            font-size: 1rem;
        }
        
        .vehicle-details {
            font-size: 0.9rem;
        }
        
        .main-image {
            height: 180px;
        }
        
        .thumbnail-gallery .col-4 {
            padding: 0 5px;
        }
        
        .thumbnail-image img {
            height: 50px !important;
        }
    }
    
    @media (max-width: 576px) {
        .map-instructions {
            font-size: 12px;
        }
        
        .address-card {
            font-size: 12px;
        }
        
        .location-badge {
            font-size: 10px;
        }
        
        .map-layers-control label {
            padding: 3px 6px;
            font-size: 12px;
        }
        
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>

<div class="container py-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(!$vehicule->disponibilite || $vehicule->etat_vehicule !== 'location')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Ce véhicule n'est actuellement pas disponible à la location.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="text-center font-weight-bold">Requête de Location</h2>
            <p class="text-center text-muted">Complétez le formulaire ci-dessous pour réserver votre véhicule</p>
            <hr class="my-4 w-50 mx-auto">
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Vehicle Information Card -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow h-100 border-0 rounded-lg">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h4 class="mb-0 text-center">{{ $vehicule->marque }} {{ $vehicule->serie }}</h4>
                    @if(!$vehicule->disponibilite || $vehicule->etat_vehicule !== 'location')
                        <div class="badge bg-danger mt-2">Non disponible</div>
                    @else
                        <div class="badge bg-success mt-2">Disponible</div>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Vehicle Images Section -->
                    <div class="mb-4">
                        <div class="image-gallery">
                            <div class="main-image mb-3 shadow-sm overflow-hidden rounded">
                                <img src="{{ asset('storage/' . $vehicule->image_principale) }}" class="img-fluid" alt="{{ $vehicule->marque }} {{ $vehicule->serie }}" id="image-principale" style="height: 200px; width: 100%; object-fit: cover;" onclick="openImageModal(this.src)">
                            </div>
                            
                            <div class="row thumbnail-gallery">
                                <div class="col-4">
                                    <div class="thumbnail-image shadow-sm overflow-hidden rounded active" data-src="{{ asset('storage/' . $vehicule->image_principale) }}">
                                        <img src="{{ asset('storage/' . $vehicule->image_principale) }}" class="img-fluid" alt="Image principale" style="height: 70px; object-fit: cover;" onclick="openImageModal(this.src)">
                                    </div>
                                </div>
                                @if($vehicule->image_secondaire)
                                <div class="col-4">
                                    <div class="thumbnail-image shadow-sm overflow-hidden rounded" data-src="{{ asset('storage/' . $vehicule->image_secondaire) }}">
                                        <img src="{{ asset('storage/' . $vehicule->image_secondaire) }}" class="img-fluid" alt="Image secondaire" style="height: 70px; object-fit: cover;" onclick="openImageModal(this.src)">
                                    </div>
                                </div>
                                @endif
                                @if($vehicule->image_tertiaire)
                                <div class="col-4">
                                    <div class="thumbnail-image shadow-sm overflow-hidden rounded" data-src="{{ asset('storage/' . $vehicule->image_tertiaire) }}">
                                        <img src="{{ asset('storage/' . $vehicule->image_tertiaire) }}" class="img-fluid" alt="Image tertiaire" style="height: 70px; object-fit: cover;" onclick="openImageModal(this.src)">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vehicle Details -->
                    <div class="vehicle-details bg-light p-3 rounded mb-3">
                        <h5 class="border-bottom pb-2 mb-3">Caractéristiques</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><i class="fas fa-car-side me-2"></i>Type:</span>
                            <span class="font-weight-bold">{{ $vehicule->type_vehicule }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><i class="fas fa-calendar-alt me-2"></i>Année:</span>
                            <span class="font-weight-bold">{{ $vehicule->annee }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><i class="fas fa-gas-pump me-2"></i>Carburant:</span>
                            <span class="font-weight-bold">{{ $vehicule->carburant }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><i class="fas fa-cogs me-2"></i>Boîte:</span>
                            <span class="font-weight-bold">{{ $vehicule->transmission }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><i class="fas fa-users me-2"></i>Places:</span>
                            <span class="font-weight-bold">{{ $vehicule->nb_places }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing Information -->
                <div class="card-footer bg-light">
                    <h5 class="border-bottom pb-2 mb-3">Tarifs</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="price-card text-center mb-0">
                                <div class="price-header bg-dark text-white py-1 rounded-top">
                                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> Abidjan</h6>
                                </div>
                                <div class="price-body bg-info text-white py-2 rounded-bottom">
                                    <h5 class="mb-0">{{ number_format($vehicule->prix_location_abidjan, 0, ',', '.') }}fr <small>/Jr</small></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="price-card text-center mb-0">
                                <div class="price-header bg-dark text-white py-1 rounded-top">
                                    <h6 class="mb-0"><i class="fas fa-map-marker-alt me-1"></i> Intérieur</h6>
                                </div>
                                <div class="price-body bg-info text-white py-2 rounded-bottom">
                                    <h5 class="mb-0">{{ number_format($vehicule->prix_location_interieur, 0, ',', '.') }}fr <small>/Jr</small></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rental Form Section -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow h-100 border-0 rounded-lg">
                <div class="card-header bg-gradient-info text-white py-3">
                    <h4 class="mb-0 text-center">Détails de la Location</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('location.store') }}">
                        @csrf
                        <input type="hidden" name="vehicule_id" value="{{ $vehicule->id }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label for="date_debut" class="form-label"><i class="fas fa-calendar-plus me-1"></i> <strong>Date de début</strong></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="date_debut" name="date_debut" min="{{ date('Y-m-d') }}" required>
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <small class="text-muted">La date de début doit être aujourd'hui ou ultérieure</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="date_fin" class="form-label"><i class="fas fa-calendar-minus me-1"></i> <strong>Date de fin</strong></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="date_fin" name="date_fin" min="{{ date('Y-m-d') }}" required>
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <small class="text-muted">La date de fin doit être postérieure à la date de début</small>
                                </div>
                            </div>
                        </div>

                        @if(isset($reservationsApprouvees) && $reservationsApprouvees->count() > 0)
                        <div class="alert alert-warning mb-4">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Périodes déjà réservées</h6>
                            <p class="mb-2">Ce véhicule est déjà réservé aux dates suivantes :</p>
                            <ul class="mb-0">
                                @foreach($reservationsApprouvees as $reservation)
                                <li>Du {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2 mb-0"><strong>Veuillez choisir des dates qui ne chevauchent pas ces périodes</strong></p>
                        </div>
                        @endif
                        
                        <div class="row mb-4">
                            <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label for="ville_depart" class="form-label"><i class="fas fa-map-pin me-1"></i> <strong>Ville de départ</strong></label>
                                    <input type="text" class="form-control" id="ville_depart" name="ville_depart" placeholder="Entrez la ville de départ" required>
                                    <small class="text-muted">Exemple: Abidjan, Yamoussoukro, etc.</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="ville_destination" class="form-label"><i class="fas fa-map-marker-alt me-1"></i> <strong>Ville de destination</strong></label>
                                    <input type="text" class="form-control" id="ville_destination" name="ville_destination" placeholder="Entrez la ville de destination" required>
                                    <small class="text-muted">Exemple: Abidjan, Yamoussoukro, etc.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="heure_depart" class="form-label"><i class="fas fa-clock me-1"></i> <strong>Heure de départ</strong></label>
                                    <div class="input-group">
                                        <input type="time" class="form-control" id="heure_depart" name="heure_depart" required>
                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    </div>
                                    <small class="text-muted">Veuillez indiquer l'heure de prise en charge souhaitée</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="lieu_prise" class="form-label"><i class="fas fa-map-marked-alt me-1"></i> <strong>Lieu de prise en charge</strong></label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" id="lieu_prise" name="point_depart" placeholder="sélectionnez votre adresse de prise en charge" readonly required>
                                        <button class="btn btn-outline-info" type="button" id="open-map-btn">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    </div>
                                    <div id="map-link-container" class="mb-2 d-none">
                                        <a href="#" id="map-link" target="_blank" class="text-info">
                                            <i class="fas fa-external-link-alt me-1"></i> Voir cette adresse sur OpenStreetMap
                                        </a>
                                    </div>
                                    <div id="map-container" class="mb-4 d-none">
                                        <div class="map-instructions mb-2">
                                            <i class="fas fa-info-circle me-1 text-info"></i> 
                                            <strong>Comment sélectionner votre lieu de prise en charge :</strong>
                                            <ul class="mb-0 mt-1 ps-3">
                                                <li>Cliquez sur la carte pour placer un marqueur</li>
                                                <li>Utilisez la barre de recherche pour trouver une adresse</li>
                                                <li>Déplacez le marqueur pour ajuster précisément la position</li>
                                            </ul>
                                        </div>
                                        <div id="map" class="border rounded shadow-sm"></div>
                                        
                                        <div id="address-display" class="address-card d-none">
                                            <h6><i class="fas fa-map-pin me-1"></i> Adresse sélectionnée</h6>
                                            <div id="address-details">Chargement...</div>
                                            <span id="coordinates-badge" class="location-badge">
                                                <i class="fas fa-crosshairs me-1"></i> <span id="lat-display">0.000</span>, <span id="lng-display">0.000</span>
                                            </span>
                                        </div>
                                        
                                        <div class="map-button-row mt-3">
                                            <div class="map-button" id="toggle-satellite-btn">
                                                <i class="fas fa-satellite"></i> Satellite
                                            </div>
                                            <div class="map-button" id="toggle-traffic-btn">
                                                <i class="fas fa-road"></i> Traffic
                                            </div>
                                            <div class="map-button" id="points-of-interest-btn">
                                                <i class="fas fa-map-signs"></i> Points d'intérêt
                                            </div>
                                        </div>
                                        
                                        <div class="location-actions mt-4 d-flex flex-column">
                                            <button type="button" class="btn btn-info px-4 mb-2 w-100" id="confirm-location-btn">
                                                <i class="fas fa-check-circle me-2"></i> Confirmer cette position
                                            </button>
                                            <button type="button" class="btn btn-outline-primary px-3 mb-2 w-100" id="center-map-btn" title="Ma position actuelle">
                                                <i class="fas fa-crosshairs me-2"></i> Ma position
                                            </button>  
                                            <button type="button" class="btn btn-outline-danger px-3 w-100" id="close-map-btn">
                                                <i class="fas fa-times me-2"></i> Fermer
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="lat" name="lat" value="">
                                    <input type="hidden" id="lng" name="lng" value="">
                                    <input type="hidden" id="address_details" name="address_details" value="">
                                    <small class="text-muted">Cliquez sur l'icône pour sélectionner un lieu précis sur la carte</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label for="notes" class="form-label"><i class="fas fa-sticky-note me-1"></i> <strong>Notes spéciales</strong></label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Informations supplémentaires, besoins spécifiques..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="mode_paiement" class="form-label"><i class="fas fa-credit-card me-1"></i> <strong>Mode de paiement</strong></label>
                                    <select class="form-control" id="mode_paiement" name="mode_paiement" required>
                                        <option value="">Sélectionner un mode de paiement</option>
                                        <option value="Wave">Wave</option>
                                        <option value="Orange_Money">Orange Money</option>
                                        <option value="Moov_Money">Moov Money</option>
                                        <option value="MTN_Money">MTN Money</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-receipt me-1"></i> Récapitulatif</h5>
                                <div class="price-details">
                                    <div class="detail-row d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <span>Durée de location:</span>
                                        <span class="font-weight-bold" id="duree">0 jour(s)</span>
                                    </div>
                                    <div class="detail-row d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <span>Prix journalier:</span>
                                        <span class="font-weight-bold" id="prix_jour">0 FCFA</span>
                                    </div>
                                    <div class="detail-row d-flex justify-content-between align-items-center py-2">
                                        <span class="h5 mb-0">Total:</span>
                                        <span class="h5 font-weight-bold text-info mb-0" id="total">0 FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="conditions" required>
                            <label class="form-check-label" for="conditions">
                                <i class="fas fa-file-contract me-1"></i> J'ai lu et j'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#conditionsModal">conditions du contrat de location</a>
                            </label>
                        </div>
                        
                        <input type="hidden" id="prix_total" name="prix_total" value="0">
                        
                        <button type="submit" class="btn btn-info btn-lg btn-block py-2 shadow-sm w-100">
                            <i class="fas fa-check-circle me-2"></i> Réserver maintenant
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for terms and conditions -->
<div class="modal fade" id="conditionsModal" tabindex="-1" aria-labelledby="conditionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="conditionsModalLabel">Conditions de location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Conditions générales</h6>
                <p>Le contrat de location est conclu entre la société de location et le locataire identifié sur le contrat. Le véhicule ne peut être conduit que par les conducteurs mentionnés sur le contrat.</p>
                
                <h6>2. Durée de la location</h6>
                <p>La location est consentie pour la durée déterminée au contrat. Si le véhicule n'est pas restitué à la date prévue et en l'absence d'accord pour une prolongation, la société se réserve le droit de reprendre le véhicule où qu'il se trouve, aux frais du locataire.</p>
                
                <h6>3. Prix et paiement</h6>
                <p>Le prix de la location est indiqué au contrat. Un dépôt de garantie sera demandé au locataire lors de la prise du véhicule. Ce dépôt sera restitué au retour du véhicule, déduction faite des éventuels frais de remise en état.</p>
                
                <h6>4. Assurance</h6>
                <p>Le véhicule est assuré contre les dommages corporels et matériels qu'il pourrait causer à des tiers. Les dommages au véhicule loué sont à la charge du locataire, sauf souscription d'une assurance complémentaire.</p>
                
                <h6>5. Responsabilité</h6>
                <p>Le locataire est responsable du véhicule dont il a la garde. En cas d'accident, il s'engage à prévenir immédiatement la société et à remplir un constat amiable.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-info" data-bs-dismiss="modal" id="acceptTerms">J'accepte</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for vehicle images -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">{{ $vehicule->marque }} {{ $vehicule->serie }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Vehicle image">
            </div>
        </div>
    </div>
</div>

<!-- Loader overlay -->
<div class="loader-overlay" id="loader-overlay">
    <div class="loader-container">
        <div class="chase"></div>
        <h5>Traitement en cours...</h5>
        <p>Veuillez patienter pendant que nous traitons votre demande</p>
    </div>
</div>

<style>
    /* Custom styles for the rental request page */
    .thumbnail-image {
        cursor: pointer;
        opacity: 0.7;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .thumbnail-image.active, .thumbnail-image:hover {
        opacity: 1;
        border-color: #17a2b8;
    }
    
    .price-card {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .price-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }
    
    .detail-row {
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .main-image {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .main-image img {
        transition: transform 0.5s ease;
    }
    
    .main-image:hover img {
        transform: scale(1.05);
    }
    
    /* Loader styles */
    :root {
        --primary: #17a2b8;
    }
    
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        visibility: hidden;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .loader-overlay.show {
        visibility: visible;
        opacity: 1;
    }
    
    .loader-container {
        text-align: center;
    }
    
    .chase {
        position: relative;
        width: 30px;
        height: 30px;
        margin: 0 auto 20px; 
    }
    
    .chase:after, .chase:before {
        content: '';
        height: 30px;
        width: 30px;
        display: block;
        -webkit-animation: out .5s backwards, spin 1.25s .5s infinite ease;
                animation: out .5s backwards, spin 1.25s .5s infinite ease;
        border: 5px solid var(--primary);
        border-radius: 100%;
        -webkit-box-shadow: 0 -40px 0 -5px var(--primary);
                box-shadow: 0 -40px 0 -5px var(--primary);
        position: absolute; 
    }
    
    .chase:after {
        -webkit-animation-delay: 0s, 1.25s;
                animation-delay: 0s, 1.25s; 
    }

    @-webkit-keyframes out {
        from {
            -webkit-box-shadow: 0 0 0 -5px var(--primary);
                    box-shadow: 0 0 0 -5px var(--primary); 
        } 
    }

    @keyframes out {
        from {
            -webkit-box-shadow: 0 0 0 -5px var(--primary);
                    box-shadow: 0 0 0 -5px var(--primary); 
        } 
    }

    @-webkit-keyframes spin {
        to {
            -webkit-transform: rotate(360deg);
                    transform: rotate(360deg); 
        } 
    }

    @keyframes spin {
        to {
            -webkit-transform: rotate(360deg);
                    transform: rotate(360deg); 
        } 
    }
</style>

@section('scripts')
<script>
    // Dates de réservation déjà approuvées
    const reservationsApprouvees = [
        @if(isset($reservationsApprouvees))
            @foreach($reservationsApprouvees as $reservation)
                {
                    debut: new Date('{{ $reservation->date_debut }}'),
                    fin: new Date('{{ $reservation->date_fin }}')
                },
            @endforeach
        @endif
    ];

    // Prix des locations
    const prixAbidjan = {{ $vehicule->prix_location_abidjan }};
    const prixInterieur = {{ $vehicule->prix_location_interieur }};
    let prixJournalier = 0;
    
    // Fonction pour ouvrir la modal d'image
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
    
    // Sélection des thumbnails d'images
    document.querySelectorAll('.thumbnail-image').forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            // Mise à jour de l'image principale
            document.getElementById('image-principale').src = this.getAttribute('data-src');
            
            // Mise à jour des classes active
            document.querySelectorAll('.thumbnail-image').forEach(function(t) {
                t.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // Calcul du prix total
    function calculerPrix() {
        const dateDebut = document.getElementById('date_debut').value;
        const dateFin = document.getElementById('date_fin').value;
        const villeDepart = document.getElementById('ville_depart').value;
        const villeDestination = document.getElementById('ville_destination').value;
        
        if (!dateDebut || !dateFin) return;
        
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        
        // Vérifier que la date de fin est après la date de début
        if (fin <= debut) {
            document.getElementById('date_fin').setCustomValidity('La date de fin doit être postérieure à la date de début');
            return;
        } else {
            document.getElementById('date_fin').setCustomValidity('');
        }
        
        // Vérifier les chevauchements avec les réservations existantes
        let chevauchement = false;
        for (const reservation of reservationsApprouvees) {
            if (
                (debut >= reservation.debut && debut <= reservation.fin) ||
                (fin >= reservation.debut && fin <= reservation.fin) ||
                (debut <= reservation.debut && fin >= reservation.fin)
            ) {
                chevauchement = true;
                break;
            }
        }
        
        if (chevauchement) {
            alert('Attention: Les dates sélectionnées chevauchent une réservation existante. Veuillez choisir d\'autres dates.');
            document.getElementById('date_debut').setCustomValidity('Chevauchement avec une réservation existante');
            document.getElementById('date_fin').setCustomValidity('Chevauchement avec une réservation existante');
            return;
        } else {
            document.getElementById('date_debut').setCustomValidity('');
            document.getElementById('date_fin').setCustomValidity('');
        }
        
        // Calcul du nombre de jours
        const diffTime = Math.abs(fin - debut);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        // Si la durée est de 0 jours (même jour), considérer comme 1 jour
        const duree = diffDays || 1;
        
        // Déterminer le prix journalier en fonction des villes de départ et de destination
        // Si départ ET destination sont à Abidjan, on utilise le tarif Abidjan, sinon tarif intérieur
        const isAbidjanDepart = villeDepart.toLowerCase().includes('abidjan');
        const isAbidjanDestination = villeDestination.toLowerCase().includes('abidjan');
        
        prixJournalier = (isAbidjanDepart && isAbidjanDestination) ? prixAbidjan : prixInterieur;
        
        // Calcul du montant total
        const montantTotal = duree * prixJournalier;
        
        // Mise à jour de l'affichage
        document.getElementById('duree').textContent = duree + ' jour(s)';
        document.getElementById('prix_jour').textContent = prixJournalier.toLocaleString('fr-FR') + ' FCFA';
        document.getElementById('total').textContent = montantTotal.toLocaleString('fr-FR') + ' FCFA';
        
        // Mise à jour du champ caché
        document.getElementById('prix_total').value = montantTotal;
    }
    
    // Écouteurs d'événements pour le calcul du prix
    document.getElementById('date_debut').addEventListener('change', calculerPrix);
    document.getElementById('date_fin').addEventListener('change', calculerPrix);
    document.getElementById('ville_depart').addEventListener('input', calculerPrix);
    document.getElementById('ville_destination').addEventListener('input', calculerPrix);
    
    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        if (document.getElementById('prix_total').value <= 0) {
            e.preventDefault();
            alert('Veuillez compléter correctement les informations de dates et de trajet pour calculer le prix.');
        } else {
            // Afficher le loader lors de la soumission du formulaire
            document.getElementById('loader-overlay').classList.add('show');
        }
    });

    // Leaflet pour le lieu de prise en charge
    let map, marker, locationCircle, hoverTooltip, currentMarkerAddress;
    let satelliteLayer, trafficLayer, markersLayer;
    const defaultPosition = [5.3599517, -4.0082563]; // Coordonnées d'Abidjan par défaut
    let isSatelliteOn = false;
    let isTrafficOn = false;
    let arePointsOfInterestOn = false;
    
    // Initialisation de la carte Leaflet
    function initMap() {
        console.log("Initialisation de la carte Leaflet");
        
        try {
            // Créer la carte
            map = L.map('map', {
                zoomControl: false, // On va repositionner les contrôles
                minZoom: 4,
                maxZoom: 18
            }).setView(defaultPosition, 13);
            
            // Ajouter les contrôles de zoom en haut à droite
            L.control.zoom({
                position: 'topright'
            }).addTo(map);
            
            // Ajouter la couche OpenStreetMap (carte standard)
            const standardLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Préparer la couche satellite (ne pas l'ajouter tout de suite)
            satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Imagery &copy; Esri',
                maxZoom: 19
            });
            
            // Préparer une "fausse" couche de trafic (à des fins de démonstration)
            trafficLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Traffic data',
                opacity: 0.5
            });
            
            // Créer une couche pour les points d'intérêt
            markersLayer = L.layerGroup().addTo(map);
            
            // Ajouter un tooltip qui suit la souris
            map.on('mousemove', function(e) {
                showLocationOnHover(e.latlng);
            });
            
            // Créer un contrôle personnalisé pour afficher l'info au survol
            hoverTooltip = L.control({position: 'bottomright'});
            hoverTooltip.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'hover-tooltip');
                div.innerHTML = 'Survolez la carte pour voir les détails';
                div.style.display = 'none';
                return div;
            };
            hoverTooltip.addTo(map);
            
            // Icône personnalisée pour le marqueur
            const customIcon = L.icon({
                iconUrl: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            // Créer un marqueur par défaut avec l'icône personnalisée
            marker = L.marker(defaultPosition, {
                draggable: true,
                icon: customIcon
            }).addTo(map);
            
            // Ajouter un popup au marqueur
            marker.bindPopup("<b>Lieu de prise en charge</b><br>Déplacez ce marqueur pour ajuster").openPopup();
            
            // Ajouter un cercle autour du marqueur pour une meilleure visibilité
            locationCircle = L.circle(defaultPosition, {
                color: '#17a2b8',
                fillColor: '#17a2b8',
                fillOpacity: 0.15,
                radius: 200,
                weight: 2
            }).addTo(map);
            
            // Ajouter un contrôle de recherche d'adresse amélioré
            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false,
                position: 'topleft',
                placeholder: "Rechercher une adresse...",
                errorMessage: "Aucun résultat trouvé",
                suggestMinLength: 3,
                showResultIcons: true,
                collapsed: false,
                expand: 'click',
                geocoder: L.Control.Geocoder.nominatim({
                    geocodingQueryParams: {
                        countrycodes: 'ci', // Limiter à la Côte d'Ivoire
                        'accept-language': 'fr'
                    }
                })
            }).addTo(map);
            
            // Quand une adresse est sélectionnée dans la recherche
            geocoder.on('markgeocode', function(e) {
                const result = e.geocode;
                const latlng = result.center;
                
                // Animation lorsqu'un nouveau lieu est sélectionné
                marker.setLatLng(latlng);
                locationCircle.setLatLng(latlng);
                
                // Zoom et centrage sur la position
                map.flyTo(latlng, 16, {
                    animate: true,
                    duration: 1
                });
                
                // Animation du marqueur
                animateMarker();
                
                // Créer l'URL OpenStreetMap avec les coordonnées
                const osmUrl = `https://www.openstreetmap.org/?mlat=${latlng.lat}&mlon=${latlng.lng}#map=17/${latlng.lat}/${latlng.lng}`;
                
                // Mettre à jour les champs avec l'adresse trouvée
                updateLocationFields(latlng, result.name);
                
                // Afficher les détails de l'adresse
                document.getElementById('address-display').classList.remove('d-none');
                document.getElementById('address-details').textContent = result.name || 'Adresse trouvée';
                document.getElementById('lat-display').textContent = latlng.lat.toFixed(6);
                document.getElementById('lng-display').textContent = latlng.lng.toFixed(6);
                
                // Sauvegarder l'adresse actuelle du marqueur
                currentMarkerAddress = result.name;
            });
            
            // Ajouter un gestionnaire d'événement de clic sur la carte
            map.on('click', function(e) {
                console.log("Clic sur la carte:", e.latlng.lat, e.latlng.lng);
                placeMarker(e.latlng);
                animateMarker();
                
                // Afficher l'adresse du lieu cliqué
                reverseGeocode(e.latlng);
            });
            
            // Ajouter un gestionnaire d'événement pour la fin du déplacement du marqueur
            marker.on('dragend', function() {
                console.log("Marqueur déplacé:", marker.getLatLng());
                locationCircle.setLatLng(marker.getLatLng());
                updateLocationFields(marker.getLatLng());
                
                // Afficher l'adresse du nouveau lieu
                reverseGeocode(marker.getLatLng());
            });
            
            // Ajouter un gestionnaire d'événement pour le début du déplacement du marqueur
            marker.on('dragstart', function() {
                marker._icon.classList.add('dragging');
            });
            
            console.log("Carte initialisée avec succès");
        } catch (error) {
            console.error("Erreur lors de l'initialisation de la carte:", error);
        }
    }
    
    // Fonction pour afficher les informations de localisation au survol
    function showLocationOnHover(latlng) {
        const tooltipDiv = document.querySelector('.hover-tooltip');
        if (tooltipDiv) {
            tooltipDiv.style.display = 'block';
            tooltipDiv.innerHTML = `
                <i class="fas fa-map-marker-alt me-1"></i> Position:
                <div>${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}</div>
            `;
        }
    }
    
    // Animation pour le marqueur
    function animateMarker() {
        marker._icon.classList.add('marker-pulse');
        setTimeout(() => {
            marker._icon.classList.remove('marker-pulse');
        }, 1500);
    }
    
    // Fonction pour placer un marqueur à un endroit spécifique
    function placeMarker(latlng) {
        // Normaliser le format des coordonnées (accepter à la fois [lat, lng] et {lat, lng})
        let lat, lng;
        
        if (Array.isArray(latlng)) {
            // Si c'est un tableau [lat, lng]
            lat = latlng[0];
            lng = latlng[1];
        } else {
            // Si c'est un objet {lat, lng}
            lat = latlng.lat;
            lng = latlng.lng;
        }
        
        // Créer un objet LatLng Leaflet
        const position = L.latLng(lat, lng);
        
        // Mettre à jour le marqueur et le cercle
        marker.setLatLng(position);
        locationCircle.setLatLng(position);
        
        // Zoom et centrage sur la position avec animation
        map.flyTo(position, 16, {
            animate: true,
            duration: 0.5
        });
        
        // Mettre à jour les champs de latitude et longitude
        updateLocationFields(position);
        
        // Mettre à jour les affichages de coordonnées
        document.getElementById('lat-display').textContent = lat.toFixed(6);
        document.getElementById('lng-display').textContent = lng.toFixed(6);
        
        // Afficher la carte d'adresse si elle était cachée
        document.getElementById('address-display').classList.remove('d-none');
        
        return position;
    }
    
    // Obtenir la position actuelle de l'utilisateur
    function getCurrentPosition() {
        if (navigator.geolocation) {
            // Mettre à jour l'apparence du bouton pendant le chargement
            const centerButton = document.getElementById("center-map-btn");
            centerButton.disabled = true;
            centerButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Options de géolocalisation
            const options = {
                enableHighAccuracy: true, // Demander la meilleure précision possible
                timeout: 10000,           // Délai maximal de 10 secondes
                maximumAge: 0             // Ne pas utiliser de position en cache
            };
            
            // Demander la position actuelle
            navigator.geolocation.getCurrentPosition(
                // Succès
                function(position) {
                    console.log("Position obtenue:", position.coords.latitude, position.coords.longitude);
                    
                    // Créer l'objet LatLng pour la position
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    // Placer le marqueur et animer la carte
                    placeMarker(userLocation);
                    animateMarker();
                    
                    // Afficher un message de succès
                    const accuracy = Math.round(position.coords.accuracy);
                    console.log(`Position trouvée avec une précision de ${accuracy} mètres`);
                    
                    // Effectuer un géocodage inversé pour obtenir l'adresse
                    reverseGeocode(userLocation);
                    
                    // Rétablir l'apparence du bouton
                    centerButton.disabled = false;
                    centerButton.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    centerButton.classList.add('btn-success');
                    centerButton.classList.remove('btn-outline-secondary');
                    
                    // Rétablir l'apparence normale après 2 secondes
                    setTimeout(() => {
                        centerButton.classList.remove('btn-success');
                        centerButton.classList.add('btn-outline-secondary');
                    }, 2000);
                },
                // Erreur
                function(error) {
                    console.error("Erreur de géolocalisation:", error);
                    let errorMessage = "Impossible d'obtenir votre position actuelle.";
                    
                    // Messages d'erreur spécifiques selon le type d'erreur
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "Vous avez refusé la géolocalisation. Veuillez autoriser l'accès à votre position dans les paramètres de votre navigateur.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Les informations de géolocalisation ne sont pas disponibles. Votre GPS est-il activé?";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "La demande de géolocalisation a expiré. Veuillez réessayer.";
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage = "Une erreur inconnue s'est produite. Veuillez réessayer.";
                            break;
                    }
                    
                    // Afficher l'erreur
                    alert(errorMessage);
                    
                    // Rétablir l'apparence du bouton
                    centerButton.disabled = false;
                    centerButton.innerHTML = '<i class="fas fa-crosshairs"></i>';
                    centerButton.classList.add('btn-danger');
                    centerButton.classList.remove('btn-outline-secondary');
                    
                    // Rétablir l'apparence normale après 2 secondes
                    setTimeout(() => {
                        centerButton.classList.remove('btn-danger');
                        centerButton.classList.add('btn-outline-secondary');
                    }, 2000);
                },
                // Options
                options
            );
        } else {
            // Navigateur ne supportant pas la géolocalisation
            alert("Votre navigateur ne supporte pas la géolocalisation.");
        }
    }
    
    // Mettre à jour les champs de latitude et longitude
    function updateLocationFields(latlng, addressName = null) {
        console.log("Mise à jour des coordonnées:", latlng.lat, latlng.lng);
        
        // Mettre à jour les champs cachés
        document.getElementById("lat").value = latlng.lat;
        document.getElementById("lng").value = latlng.lng;
        
        // Créer le lien OpenStreetMap
        const osmUrl = `https://www.openstreetmap.org/?mlat=${latlng.lat}&mlon=${latlng.lng}#map=17/${latlng.lat}/${latlng.lng}`;
        document.getElementById("map-link").href = osmUrl;
        document.getElementById("map-link-container").classList.remove("d-none");
        
        // Afficher l'URL complet dans le champ input
        document.getElementById("lieu_prise").value = osmUrl;
        
        // Si une adresse est fournie, l'enregistrer dans le champ caché
        if (addressName) {
            document.getElementById("address_details").value = addressName;
        }
    }
    
    // Fonction pour faire un géocodage inversé (coordonnées -> adresse)
    function reverseGeocode(latlng) {
        document.getElementById('address-display').classList.remove('d-none');
        document.getElementById('address-details').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Recherche de l\'adresse...';
        
        // Faire un géocodage inversé avec Nominatim (service gratuit d'OpenStreetMap)
        const nominatimUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&zoom=18&addressdetails=1`;
        
        fetch(nominatimUrl, {
            headers: {
                'Accept': 'application/json',
                'User-Agent': 'VehiculeLocationApp/1.0'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log("Réponse du géocodage Nominatim:", data);
            if (data && data.display_name) {
                // Formatage de l'adresse pour un affichage plus convivial
                let formattedAddress = data.display_name;
                
                // Si on a des détails d'adresse complets, on peut les formater de manière plus lisible
                if (data.address) {
                    const addressParts = [];
                    
                    // Construire une adresse bien formatée à partir des composants disponibles
                    if (data.address.road) addressParts.push(data.address.road);
                    if (data.address.house_number) addressParts.push(data.address.house_number);
                    if (data.address.suburb) addressParts.push(data.address.suburb);
                    if (data.address.city || data.address.town) addressParts.push(data.address.city || data.address.town);
                    if (data.address.state) addressParts.push(data.address.state);
                    if (data.address.country) addressParts.push(data.address.country);
                    
                    if (addressParts.length > 0) {
                        formattedAddress = addressParts.join(', ');
                    }
                }
                
                // Mettre à jour l'affichage de l'adresse
                document.getElementById('address-details').textContent = formattedAddress;
                document.getElementById('address_details').value = formattedAddress;
                
                // Sauvegarder l'adresse actuelle du marqueur
                currentMarkerAddress = formattedAddress;
                
                // Mettre à jour le popup du marqueur
                marker.bindPopup(`<b>${formattedAddress}</b>`).openPopup();
            } else {
                document.getElementById('address-details').textContent = 'Adresse non disponible';
            }
        })
        .catch(error => {
            console.error("Erreur lors de la requête Nominatim:", error);
            document.getElementById('address-details').textContent = 'Erreur lors de la recherche de l\'adresse';
        });
    }
    
    // Fonction pour ajouter des points d'intérêt autour de la position actuelle
    function addPointsOfInterest() {
        // Effacer les anciens marqueurs
        markersLayer.clearLayers();
        
        if (!arePointsOfInterestOn) return;
        
        // Position actuelle du marqueur
        const centerPos = marker.getLatLng();
        
        // Exemples de points d'intérêt (simulés pour la démo)
        const pois = [
            { name: "Pharmacie", lat: centerPos.lat + 0.003, lng: centerPos.lng - 0.002, type: "pharmacy" },
            { name: "Restaurant", lat: centerPos.lat - 0.002, lng: centerPos.lng + 0.003, type: "restaurant" },
            { name: "Station-service", lat: centerPos.lat + 0.001, lng: centerPos.lng + 0.004, type: "gas_station" },
            { name: "Hôtel", lat: centerPos.lat - 0.004, lng: centerPos.lng - 0.001, type: "hotel" },
            { name: "Supermarché", lat: centerPos.lat + 0.005, lng: centerPos.lng - 0.003, type: "supermarket" }
        ];
        
        // Créer une icône pour chaque type de POI
        const poiIcons = {
            "pharmacy": L.divIcon({
                html: '<i class="fas fa-clinic-medical" style="color: #5cb85c;"></i>',
                className: 'position-marker',
                iconSize: [20, 20]
            }),
            "restaurant": L.divIcon({
                html: '<i class="fas fa-utensils" style="color: #d9534f;"></i>',
                className: 'position-marker',
                iconSize: [20, 20]
            }),
            "gas_station": L.divIcon({
                html: '<i class="fas fa-gas-pump" style="color: #f0ad4e;"></i>',
                className: 'position-marker',
                iconSize: [20, 20]
            }),
            "hotel": L.divIcon({
                html: '<i class="fas fa-bed" style="color: #5bc0de;"></i>',
                className: 'position-marker',
                iconSize: [20, 20]
            }),
            "supermarket": L.divIcon({
                html: '<i class="fas fa-shopping-cart" style="color: #428bca;"></i>',
                className: 'position-marker',
                iconSize: [20, 20]
            })
        };
        
        // Ajouter chaque point d'intérêt
        pois.forEach(poi => {
            const poiMarker = L.marker([poi.lat, poi.lng], {
                icon: poiIcons[poi.type] || L.divIcon({
                    html: '<i class="fas fa-map-marker" style="color: #17a2b8;"></i>',
                    className: 'position-marker',
                    iconSize: [20, 20]
                })
            }).bindPopup(`<b>${poi.name}</b><br>À proximité de votre lieu de prise en charge`);
            
            markersLayer.addLayer(poiMarker);
        });
    }
    
    // Gestionnaires d'événements pour les boutons de la carte
    document.getElementById("open-map-btn").addEventListener("click", function() {
        const mapContainer = document.getElementById("map-container");
        mapContainer.classList.remove("d-none");
        
        // Initialiser Leaflet si ce n'est pas déjà fait
        if (typeof map === 'undefined') {
            initMap();
        } else {
            // Redimensionner la carte si elle existe déjà
            map.invalidateSize();
        }
    });
    
    document.getElementById("close-map-btn").addEventListener("click", function() {
        document.getElementById("map-container").classList.add("d-none");
    });
    
    document.getElementById("confirm-location-btn").addEventListener("click", function() {
        console.log("Bouton de confirmation cliqué");
        if (marker) {
            console.log("Position du marqueur:", marker.getLatLng());
            // Créer l'URL OpenStreetMap avec les coordonnées actuelles du marqueur
            const latlng = marker.getLatLng();
            const osmUrl = `https://www.openstreetmap.org/?mlat=${latlng.lat}&mlon=${latlng.lng}#map=17/${latlng.lat}/${latlng.lng}`;
            
            // Mettre à jour le champ input avec l'URL OpenStreetMap
            document.getElementById("lieu_prise").value = osmUrl;
            
            // Fermer la carte
            document.getElementById("map-container").classList.add("d-none");
        } else {
            console.error("Marqueur non défini");
        }
    });
    
    // Bouton pour centrer la carte sur la position actuelle
    document.getElementById("center-map-btn").addEventListener("click", function() {
        getCurrentPosition();
    });
    
    // Bouton pour basculer en mode satellite
    document.getElementById("toggle-satellite-btn").addEventListener("click", function() {
        isSatelliteOn = !isSatelliteOn;
        if (isSatelliteOn) {
            // Ajouter la couche satellite
            satelliteLayer.addTo(map);
            this.style.backgroundColor = '#17a2b8';
            this.style.color = 'white';
        } else {
            // Supprimer la couche satellite
            map.removeLayer(satelliteLayer);
            this.style.backgroundColor = '';
            this.style.color = '';
        }
    });
    
    // Bouton pour activer/désactiver la couche de trafic
    document.getElementById("toggle-traffic-btn").addEventListener("click", function() {
        isTrafficOn = !isTrafficOn;
        if (isTrafficOn) {
            // Ajouter la couche de trafic
            trafficLayer.addTo(map);
            this.style.backgroundColor = '#17a2b8';
            this.style.color = 'white';
        } else {
            // Supprimer la couche de trafic
            map.removeLayer(trafficLayer);
            this.style.backgroundColor = '';
            this.style.color = '';
        }
    });
    
    // Bouton pour afficher les points d'intérêt
    document.getElementById("points-of-interest-btn").addEventListener("click", function() {
        arePointsOfInterestOn = !arePointsOfInterestOn;
        if (arePointsOfInterestOn) {
            // Ajouter les points d'intérêt
            addPointsOfInterest();
            this.style.backgroundColor = '#17a2b8';
            this.style.color = 'white';
        } else {
            // Supprimer les points d'intérêt
            markersLayer.clearLayers();
            this.style.backgroundColor = '';
            this.style.color = '';
        }
    });
    
    // Initialisation de la carte au chargement
    document.addEventListener('DOMContentLoaded', function() {
        // La carte sera initialisée quand l'utilisateur cliquera sur le bouton
    });
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Leaflet Geocoder -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
@endsection
@endsection


