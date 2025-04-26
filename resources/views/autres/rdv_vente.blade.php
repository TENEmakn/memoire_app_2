@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(!$vehicule->disponibilite || $vehicule->etat_vehicule !== 'vente')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Ce véhicule n'est actuellement pas disponible à la vente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="text-center font-weight-bold">Rendez-vous pour l'achat d'un véhicule</h2>
            <p class="text-center text-muted">Complétez le formulaire ci-dessous pour planifier un rendez-vous d'achat</p>
            <hr class="my-4 w-50 mx-auto">
        </div>
    </div>
    
    <div class="row">
        <!-- Vehicle Information Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100 border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0 text-center">{{ $vehicule->marque }} {{ $vehicule->serie }}</h4>
                    @if(!$vehicule->disponibilite || $vehicule->etat_vehicule !== 'vente')
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
                    <h5 class="border-bottom pb-2 mb-3">Prix de vente</h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="price-card text-center mb-0">
                                <div class="price-header bg-dark text-white py-1 rounded-top">
                                    <h6 class="mb-0"><i class="fas fa-tag me-1"></i> Prix</h6>
                                </div>
                                <div class="price-body bg-primary text-white py-2 rounded-bottom">
                                    <h5 class="mb-0">{{ number_format($vehicule->prix_vente, 0, ',', '.') }} FCFA</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sales Appointment Form Section -->
        <div class="col-md-8 mb-4">
            <div class="card shadow h-100 border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h4 class="mb-0 text-center">Lieu du RDV</h4>
                </div>
                <div class="card-body">
                    <!-- Google Maps iframe -->
                    <div class="map-container mb-4" style="height: 300px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1022.1521846517113!2d-3.9938909234955275!3d5.384313916584896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc19500410b406b%3A0x9d93687280782bb!2sCGV%20MOTORS!5e0!3m2!1sfr!2sci!4v1744737605489!5m2!1sfr!2sci"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                    
                    <!-- Location Information -->
                    <div>
                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt me-2"></i>Notre showroom</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="location-details mb-4">
                                    <p class="fw-bold mb-1">Adresse</p>
                                    <p class="mb-3">123 Boulevard Principal, Cocody<br>Abidjan, Côte d'Ivoire</p>
                                    
                                    <p class="fw-bold mb-1">Heures d'ouverture</p>
                                    <ul class="list-unstyled mb-0">
                                        <li>Lundi - Vendredi: 8h - 18h</li>
                                        <li>Samedi: 9h - 16h</li>
                                        <li>Dimanche: Fermé</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="contact-details">
                                    <p class="fw-bold mb-1">Contact</p>
                                    <p class="mb-3">
                                        <i class="fas fa-phone-alt me-2"></i> +225 07 01 02 48 87 57<br>
                                        <i class="fas fa-envelope me-2"></i> contact@motors.ci
                                    </p>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rdvModal">
                                            <i class="fas fa-calendar-check me-2"></i> Prendre rendez-vous
                                        </a>
                                        <a href="https://maps.app.goo.gl/GPDe4d5jKcfkTYtw5" target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-directions me-2"></i> Itinéraire
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informations importantes</h6>
                            <ul class="mb-0">
                                <li>Vous pouvez planifier un rendez-vous directement en cliquant sur le bouton "Prendre rendez-vous" ci-dessus.</li>
                                <li>Notre équipe commerciale sera ravie de vous accueillir et de vous présenter ce véhicule en détail.</li>
                                <li>Veuillez vous présenter à l'heure à notre showroom avec une pièce d'identité valide.</li>
                                <li>Possibilité d'essai routier lors du rendez-vous (prévoir permis de conduire).</li>
                                <li>Pour tout changement ou annulation, merci de nous prévenir au moins 24h à l'avance.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for terms and conditions -->
<div class="modal fade" id="conditionsModal" tabindex="-1" aria-labelledby="conditionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="conditionsModalLabel">Conditions générales de vente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Objet</h6>
                <p>Les présentes conditions générales de vente régissent les relations contractuelles entre le vendeur et l'acheteur du véhicule. Toute prise de rendez-vous implique l'acceptation sans réserve des présentes conditions.</p>
                
                <h6>2. Véhicule</h6>
                <p>Le véhicule est vendu en l'état, tel que présenté lors du rendez-vous. Un procès-verbal de réception sera établi au moment de la livraison mentionnant les éventuelles réserves.</p>
                
                <h6>3. Prix et modalités de paiement</h6>
                <p>Le prix indiqué correspond au prix total du véhicule. Un acompte pourra être demandé pour confirmer la commande. Le solde devra être réglé intégralement avant la livraison du véhicule.</p>
                
                <h6>4. Livraison</h6>
                <p>Le délai de livraison est donné à titre indicatif et peut varier en fonction des disponibilités. Le vendeur ne pourra être tenu responsable des retards de livraison indépendants de sa volonté.</p>
                
                <h6>5. Garantie</h6>
                <p>Le véhicule bénéficie de la garantie légale de conformité et de la garantie contre les vices cachés. Une extension de garantie peut être proposée lors de la vente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="acceptTerms">J'accepte</button>
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

<!-- Modal for appointment booking -->
<div class="modal fade" id="rdvModal" tabindex="-1" aria-labelledby="rdvModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="rdvModalLabel">Prendre rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rdvForm" method="POST" action="{{ route('vente.rdv.store') }}">
                    @csrf
                    <input type="hidden" name="vehicule_id" value="{{ $vehicule->id }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_rdv" class="form-label"><i class="fas fa-calendar-check me-1"></i> <strong>Date du rendez-vous</strong></label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="date_rdv" name="date_rdv" min="{{ date('Y-m-d') }}" required>
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                </div>
                                <small class="text-muted">La date du rendez-vous doit être aujourd'hui ou ultérieure</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="heure_rdv" class="form-label"><i class="fas fa-clock me-1"></i> <strong>Heure du rendez-vous</strong></label>
                                <div class="input-group">
                                    <input type="time" class="form-control" id="heure_rdv" name="heure_rdv" min="08:00" max="18:00" required>
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                                <small class="text-muted">Nos horaires: 8h - 18h</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nom_complet" class="form-label"><i class="fas fa-user me-1"></i> <strong>Nom complet</strong></label>
                                <input type="text" class="form-control" id="nom_complet" name="nom_complet" placeholder="Votre nom complet" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="telephone" class="form-label"><i class="fas fa-phone me-1"></i> <strong>Numéro de téléphone</strong></label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Votre numéro de téléphone" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label"><i class="fas fa-envelope me-1"></i> <strong>Adresse email</strong></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="notes" class="form-label"><i class="fas fa-sticky-note me-1"></i> <strong>Questions ou précisions</strong></label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Avez-vous des questions spécifiques ou des points à préciser avant le rendez-vous?"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="conditions" required>
                        <label class="form-check-label" for="conditions">
                            <i class="fas fa-file-contract me-1"></i> J'ai lu et j'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#conditionsModal">conditions générales de vente</a>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="submitRdv"><i class="fas fa-calendar-check me-2"></i> Confirmer le rendez-vous</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for the sales appointment page */
    .thumbnail-image {
        cursor: pointer;
        opacity: 0.7;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .thumbnail-image.active, .thumbnail-image:hover {
        opacity: 1;
        border-color: #0d6efd;
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
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }
    
    .detail-row {
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
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
</style>

@section('scripts')
<script>
    // Rendez-vous déjà approuvés
    const rdvApprouves = [
        @if(isset($rdvApprouves))
            @foreach($rdvApprouves as $rdv)
                {
                    date: new Date('{{ $rdv->date_rdv }}'),
                    heure: '{{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}'
                },
            @endforeach
        @endif
    ];
    
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
    
    // Mise à jour des heures disponibles en fonction de la date
    function updateHeuresDisponibles() {
        const dateRdvInput = document.getElementById('date_rdv');
        const heureRdvInput = document.getElementById('heure_rdv');
        
        if (!dateRdvInput.value) return;
        
        const dateSelectionnee = new Date(dateRdvInput.value);
        const aujourdhui = new Date();
        
        // Réinitialiser l'heure minimum par défaut à 8h00
        let heureMin = "08:00";
        
        // Si la date sélectionnée est aujourd'hui
        if (dateSelectionnee.getFullYear() === aujourdhui.getFullYear() && 
            dateSelectionnee.getMonth() === aujourdhui.getMonth() && 
            dateSelectionnee.getDate() === aujourdhui.getDate()) {
            
            // Obtenir l'heure actuelle + 1 heure
            const heureActuelle = new Date();
            heureActuelle.setHours(heureActuelle.getHours() + 1);
            
            // Formater l'heure au format HH:MM
            let heures = heureActuelle.getHours();
            let minutes = heureActuelle.getMinutes();
            
            // Si l'heure + 1 est encore dans nos horaires d'ouverture (avant 18h)
            if (heures < 18) {
                heureMin = `${heures.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            } else {
                // Si après 18h, la date d'aujourd'hui n'est plus disponible
                alert('Il n\'est plus possible de prendre rendez-vous aujourd\'hui. Veuillez sélectionner une autre date.');
                dateRdvInput.value = '';
                return;
            }
        }
        
        // Mettre à jour l'attribut min de l'input heure
        heureRdvInput.min = heureMin;
        
        // Vérifier si l'heure actuelle est toujours valide
        if (heureRdvInput.value && heureRdvInput.value < heureMin) {
            heureRdvInput.value = heureMin;
        }
    }
    
    // Vérification de la disponibilité des créneaux
    function verifierDisponibilite() {
        const dateRdv = document.getElementById('date_rdv').value;
        const heureRdv = document.getElementById('heure_rdv').value;
        
        if (!dateRdv || !heureRdv) return;
        
        const dateSelectionnee = new Date(dateRdv);
        
        // Vérifier si la date est un jour ouvrable (lundi-samedi)
        const jourSemaine = dateSelectionnee.getDay(); // 0 = dimanche, 6 = samedi
        if (jourSemaine === 0) {
            alert('Nos bureaux sont fermés le dimanche. Veuillez sélectionner un autre jour.');
            document.getElementById('date_rdv').setCustomValidity('Jour non disponible');
            return;
        } else {
            document.getElementById('date_rdv').setCustomValidity('');
        }
        
        // Vérifier les chevauchements avec les rendez-vous existants
        let chevauchement = false;
        for (const rdv of rdvApprouves) {
            // Comparer les dates (année, mois, jour)
            if (
                rdv.date.getFullYear() === dateSelectionnee.getFullYear() &&
                rdv.date.getMonth() === dateSelectionnee.getMonth() &&
                rdv.date.getDate() === dateSelectionnee.getDate() &&
                rdv.heure === heureRdv
            ) {
                chevauchement = true;
                break;
            }
        }
        
        if (chevauchement) {
            alert('Ce créneau horaire est déjà réservé. Veuillez choisir un autre horaire.');
            document.getElementById('heure_rdv').setCustomValidity('Créneau déjà réservé');
        } else {
            document.getElementById('heure_rdv').setCustomValidity('');
        }
    }
    
    // Écouteurs d'événements
    document.addEventListener('DOMContentLoaded', function() {
        const dateRdvInput = document.getElementById('date_rdv');
        const heureRdvInput = document.getElementById('heure_rdv');
        
        // Initialiser les contraintes lors du chargement
        updateHeuresDisponibles();
        
        // Mettre à jour les heures disponibles quand la date change
        dateRdvInput.addEventListener('change', function() {
            updateHeuresDisponibles();
            verifierDisponibilite();
        });
        
        heureRdvInput.addEventListener('change', verifierDisponibilite);
    });
    
    // Soumission du formulaire de rendez-vous dans la modal
    document.getElementById('submitRdv').addEventListener('click', function() {
        // Vérifie une dernière fois la disponibilité
        verifierDisponibilite();
        
        // Récupère le formulaire
        const form = document.getElementById('rdvForm');
        
        // Vérifie que le formulaire est valide
        if (form.checkValidity()) {
            // Vérifier si l'heure est dans les plages d'ouverture
            const heureRdv = document.getElementById('heure_rdv').value;
            if (heureRdv) {
                const heure = parseInt(heureRdv.split(':')[0]);
                if (heure < 8 || heure >= 18) {
                    alert('Veuillez choisir un horaire entre 8h et 18h.');
                    return;
                }
            }
            
            // Soumet le formulaire si tout est valide
            form.submit();
        } else {
            // Déclenche la validation HTML5 du navigateur
            form.reportValidity();
        }
    });
    
    // Gestion des modales imbriquées (conditions générales depuis la modal de RDV)
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(element) {
        element.addEventListener('click', function(event) {
            // Empêche la propagation si le lien est à l'intérieur d'une autre modal
            if (this.closest('.modal')) {
                event.stopPropagation();
            }
        });
    });
    
    // Quand la modal des conditions est fermée, ramène le focus sur la modal de RDV
    const conditionsModal = document.getElementById('conditionsModal');
    if (conditionsModal) {
        conditionsModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('rdvModal').classList.add('show');
        });
    }
</script>
@endsection
@endsection