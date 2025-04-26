@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Détails de la réservation</h2>
                <a href="{{ route('mes_reservations') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Informations de la réservation
                        <span class="float-end">
                            @switch($locationRequest->statut)
                                @case('en_attente')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                    @break
                                @case('approuvee')
                                    <span class="badge bg-success">Approuvée</span>
                                    @break
                                @case('rejetee')
                                    <span class="badge bg-danger">Rejetée</span>
                                    @break
                                @case('terminee')
                                    <span class="badge bg-info">Terminée</span>
                                    @break
                                @case('annulee')
                                    <span class="badge bg-secondary">Annulée</span>
                                    @break
                                @default
                                    <span class="badge bg-dark">{{ $locationRequest->statut }}</span>
                            @endswitch
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Informations du véhicule</h5>
                            <p><strong>Marque et Modèle :</strong> {{ $locationRequest->marque_vehicule }} {{ $locationRequest->serie_vehicule }}</p>
                            <p><strong>ID du véhicule :</strong> {{ $locationRequest->vehicule_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Informations du chauffeur</h5>
                            <p><strong>Nom :</strong> {{ $locationRequest->nom_chauffeur }} {{ $locationRequest->prenom_chauffeur }}</p>
                            <p><strong>Téléphone :</strong> {{ $locationRequest->numero_telephone_chauffeur }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Dates et trajet</h5>
                            <p><strong>Date de début :</strong> {{ \Carbon\Carbon::parse($locationRequest->date_debut)->format('d/m/Y') }}</p>
                            <p><strong>Date de fin :</strong> {{ \Carbon\Carbon::parse($locationRequest->date_fin)->format('d/m/Y') }}</p>
                            <p><strong>Heure de départ :</strong> {{ \Carbon\Carbon::parse($locationRequest->heure_depart)->format('H:i') }}</p>
                            <p><strong>Lieu de départ :</strong> <a href="{{ $locationRequest->point_depart }}" target="_blank" rel="noopener noreferrer">{{ $locationRequest->point_depart }}</a> 
                                <button type="button" class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#editDepartureModal">
                                    <i class="fas fa-map-marker-alt"></i> Modifier
                                </button>
                            </p>
                            <p><strong>Durée :</strong> 
                                {{ \Carbon\Carbon::parse($locationRequest->date_debut)->diffInDays(\Carbon\Carbon::parse($locationRequest->date_fin)) + 1 }} jours
                            </p>
                            <p><strong>ville de départ :</strong> {{ $locationRequest->lieu_depart }}</p>
                            <p><strong>ville d'arrivée :</strong> {{ $locationRequest->lieu_arrivee }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">Informations de paiement</h5>
                            <p><strong>Montant total :</strong> {{ number_format($locationRequest->prix_total, 0, ',', ' ') }} FCFA</p>
                            <p><strong>Mode de paiement :</strong> {{ str_replace('_', ' ', $locationRequest->mode_paiement) }}</p>
                            <p><strong>Référence :</strong> {{ $locationRequest->reference_paiement }}</p>
                            <p><strong>Statut de la réservation :</strong>
                                @switch($locationRequest->statut)
                                    @case('en_attente')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                        @break
                                    @case('approuvee')
                                        <span class="badge bg-success">Approuvée</span>
                                        @break
                                    @case('rejetee')
                                        <span class="badge bg-danger">Rejetée</span>
                                        @break
                                    @case('terminee')
                                        <span class="badge bg-info">Terminée</span>
                                        @break
                                    @case('annulee')
                                        <span class="badge bg-secondary">Annulée</span>
                                        @break
                                    @default
                                        <span class="badge bg-dark">{{ $locationRequest->statut }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                    
                    @if($locationRequest->notes)
                    <div class="mt-4">
                        <h5 class="border-bottom pb-2 mb-3">Notes spéciales</h5>
                        <div class="p-3 bg-light rounded">
                            {{ $locationRequest->notes }}
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i> Demande créée le {{ $locationRequest->created_at->format('d/m/Y à H:i') }}
                        </span>
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i> Dernière mise à jour le {{ $locationRequest->updated_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0"><i class="fas fa-tasks me-2"></i> Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('mes_reservations') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour à mes réservations
                        </a>
                        
                        @if($locationRequest->statut == 'en_attente')
                            <div class="alert alert-warning">
                                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Annulation de réservation</h5>
                                <p class="mb-0">Pour annuler cette réservation, veuillez contacter notre service de location aux coordonnées ci-dessous.</p>
                            </div>
                        @endif
                        
                        @if($locationRequest->statut == 'approuvee')
                            <div class="alert alert-success">
                                <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i> Réservation confirmée</h5>
                                <p class="mb-0">Votre réservation a été approuvée. Veuillez conserver la référence de paiement.</p>
                            </div>
                        @endif
                        
                        @if($locationRequest->statut == 'rejetee')
                            <div class="alert alert-danger">
                                <h5 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Réservation rejetée</h5>
                                <p class="mb-0">Votre réservation a été rejetée. Veuillez nous contacter pour plus d'informations.</p>
                            </div>
                        @endif
                        
                        @if($locationRequest->statut == 'terminee')
                            <div class="alert alert-info">
                                <h5 class="alert-heading"><i class="fas fa-check-double me-2"></i> Réservation terminée</h5>
                                <p class="mb-0">Votre réservation est terminée. Merci de votre confiance.</p>
                            </div>
                        @endif
                        
                        @if($locationRequest->statut == 'annulee')
                            <div class="alert alert-secondary">
                                <h5 class="alert-heading"><i class="fas fa-ban me-2"></i> Réservation annulée</h5>
                                <p class="mb-0">Cette réservation a été annulée.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0"><i class="fas fa-question-circle me-2"></i> Besoin d'aide ?</h4>
                </div>
                <div class="card-body">
                    <p>Pour toute question concernant votre réservation, n'hésitez pas à nous contacter :</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> +225 07 07 07 07 07</li>
                        <li><i class="fas fa-envelope me-2"></i> contact@motors.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour modifier le lieu de départ -->
<div class="modal fade" id="editDepartureModal" tabindex="-1" aria-labelledby="editDepartureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartureModalLabel">Modifier le lieu de départ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateDepartureForm" action="{{ route('location.update_departure', $locationRequest->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-group">
                        <label for="lieu_prise" class="form-label"><i class="fas fa-map-marked-alt me-1"></i> <strong>Lieu de départ</strong></label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="lieu_prise" name="point_depart" placeholder="sélectionnez votre adresse de départ" value="{{ $locationRequest->point_depart }}" readonly required>
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
                                <strong>Comment sélectionner votre lieu de départ :</strong>
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
                        <input type="hidden" id="locationName" name="lieu_depart" value="{{ $locationRequest->lieu_depart }}">
                        <small class="text-muted">Cliquez sur l'icône pour sélectionner un lieu précis sur la carte</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveDeparture">Enregistrer</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
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
    
    .location-badge {
        display: inline-block;
        background-color: #e9ecef;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-top: 6px;
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
</style>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    // Variables globales pour la carte
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
            marker.bindPopup("<b>Lieu de départ</b><br>Déplacez ce marqueur pour ajuster").openPopup();
            
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
                updateLocationFields(latlng, result.name, osmUrl);
                
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
                const position = marker.getLatLng();
                const osmUrl = `https://www.openstreetmap.org/?mlat=${position.lat}&mlon=${position.lng}#map=17/${position.lat}/${position.lng}`;
                updateLocationFields(position, null, osmUrl);
                
                // Afficher l'adresse du nouveau lieu
                reverseGeocode(position);
            });
            
            // Ajouter un gestionnaire d'événement pour le début du déplacement du marqueur
            marker.on('dragstart', function() {
                marker._icon.classList.add('dragging');
            });
            
            // Si nous avons déjà un point de départ, placer le marqueur à cet endroit
            const currentPoint = document.getElementById('lieu_prise').value;
            if (currentPoint && currentPoint.includes('openstreetmap.org')) {
                try {
                    // Analyser l'URL pour extraire lat/lng
                    const regex = /mlat=(-?\d+\.\d+)&mlon=(-?\d+\.\d+)/;
                    const match = currentPoint.match(regex);
                    if (match) {
                        const lat = parseFloat(match[1]);
                        const lng = parseFloat(match[2]);
                        const position = L.latLng(lat, lng);
                        marker.setLatLng(position);
                        locationCircle.setLatLng(position);
                        map.setView(position, 15);
                        
                        // Mettre à jour les champs
                        updateLocationFields(position, null, currentPoint);
                        
                        // Afficher l'adresse
                        reverseGeocode(position);
                    }
                } catch (e) {
                    console.error("Erreur lors de l'analyse du point de départ:", e);
                }
            }
            
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
        if (marker && marker._icon) {
            marker._icon.classList.add('marker-pulse');
            setTimeout(() => {
                if (marker && marker._icon) {
                    marker._icon.classList.remove('marker-pulse');
                }
            }, 1500);
        }
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
        
        // Créer l'URL OpenStreetMap avec les coordonnées
        const osmUrl = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}#map=17/${lat}/${lng}`;
        
        // Mettre à jour les champs
        updateLocationFields(position, null, osmUrl);
        
        // Mettre à jour les affichages de coordonnées
        document.getElementById('lat-display').textContent = lat.toFixed(6);
        document.getElementById('lng-display').textContent = lng.toFixed(6);
        
        // Afficher la carte d'adresse si elle était cachée
        document.getElementById('address-display').classList.remove('d-none');
        
        return position;
    }
    
    // Mettre à jour les champs de latitude et longitude
    function updateLocationFields(latlng, addressName = null, osmUrl = null) {
        console.log("Mise à jour des coordonnées:", latlng.lat, latlng.lng);
        
        // Mettre à jour les champs cachés
        document.getElementById("lat").value = latlng.lat;
        document.getElementById("lng").value = latlng.lng;
        
        // Mettre à jour le champ visible
        if (osmUrl) {
            document.getElementById("lieu_prise").value = osmUrl;
            document.getElementById("map-link").href = osmUrl;
            document.getElementById("map-link-container").classList.remove("d-none");
        }
        
        // Si une adresse est fournie, l'enregistrer dans les champs
        if (addressName) {
            document.getElementById("address_details").value = addressName;
            document.getElementById("locationName").value = addressName;
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
                document.getElementById('locationName').value = formattedAddress;
                
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
            }).bindPopup(`<b>${poi.name}</b><br>À proximité de votre lieu de départ`);
            
            markersLayer.addLayer(poiMarker);
        });
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
                    centerButton.innerHTML = '<i class="fas fa-crosshairs me-2"></i> Ma position';
                    centerButton.classList.add('btn-success');
                    centerButton.classList.remove('btn-outline-primary');
                    
                    // Rétablir l'apparence normale après 2 secondes
                    setTimeout(() => {
                        centerButton.classList.remove('btn-success');
                        centerButton.classList.add('btn-outline-primary');
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
                    centerButton.innerHTML = '<i class="fas fa-crosshairs me-2"></i> Ma position';
                    centerButton.classList.add('btn-danger');
                    centerButton.classList.remove('btn-outline-primary');
                    
                    // Rétablir l'apparence normale après 2 secondes
                    setTimeout(() => {
                        centerButton.classList.remove('btn-danger');
                        centerButton.classList.add('btn-outline-primary');
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
    
    // Initialisation de l'interface quand la page est chargée
    document.addEventListener('DOMContentLoaded', function() {
        // Ouvrir la carte quand le bouton est cliqué
        const openMapBtn = document.getElementById('open-map-btn');
        if (openMapBtn) {
            openMapBtn.addEventListener('click', function() {
                document.getElementById('map-container').classList.remove('d-none');
                
                // Initialiser la carte si ce n'est pas déjà fait
                if (typeof map === 'undefined') {
                    initMap();
                } else {
                    // Redimensionner la carte si elle existe déjà
                    map.invalidateSize();
                }
            });
        }
        
        // Fermer la carte
        const closeMapBtn = document.getElementById('close-map-btn');
        if (closeMapBtn) {
            closeMapBtn.addEventListener('click', function() {
                document.getElementById('map-container').classList.add('d-none');
            });
        }
        
        // Confirmer la position
        const confirmLocationBtn = document.getElementById('confirm-location-btn');
        if (confirmLocationBtn) {
            confirmLocationBtn.addEventListener('click', function() {
                if (marker) {
                    const position = marker.getLatLng();
                    const osmUrl = `https://www.openstreetmap.org/?mlat=${position.lat}&mlon=${position.lng}#map=17/${position.lat}/${position.lng}`;
                    
                    // Mettre à jour le champ input avec l'URL OpenStreetMap
                    document.getElementById('lieu_prise').value = osmUrl;
                    document.getElementById('map-link').href = osmUrl;
                    document.getElementById('map-link-container').classList.remove('d-none');
                    
                    // Fermer la carte
                    document.getElementById('map-container').classList.add('d-none');
                }
            });
        }
        
        // Géolocalisation
        const centerMapBtn = document.getElementById('center-map-btn');
        if (centerMapBtn) {
            centerMapBtn.addEventListener('click', function() {
                getCurrentPosition();
            });
        }
        
        // Mode satellite
        const toggleSatelliteBtn = document.getElementById('toggle-satellite-btn');
        if (toggleSatelliteBtn) {
            toggleSatelliteBtn.addEventListener('click', function() {
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
        }
        
        // Mode trafic
        const toggleTrafficBtn = document.getElementById('toggle-traffic-btn');
        if (toggleTrafficBtn) {
            toggleTrafficBtn.addEventListener('click', function() {
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
        }
        
        // Points d'intérêt
        const pointsOfInterestBtn = document.getElementById('points-of-interest-btn');
        if (pointsOfInterestBtn) {
            pointsOfInterestBtn.addEventListener('click', function() {
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
        }
        
        // Enregistrer les modifications
        const saveDepartureBtn = document.getElementById('saveDeparture');
        if (saveDepartureBtn) {
            saveDepartureBtn.addEventListener('click', function() {
                document.getElementById('updateDepartureForm').submit();
            });
        }
    });
</script>
@endsection 