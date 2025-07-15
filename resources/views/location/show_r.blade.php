@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails du rendez-vous #{{ $rendezVous->id }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('index') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('mes_rdv') }}">Mes réservations</a></li>
        <li class="breadcrumb-item active">Détails</li>
    </ol>

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

    <div class="row">
        <!-- Colonne principale - Informations -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations de la réservation
                </div>
                <div class="card-body">
                    <!-- Statut de la réservation -->
                    <div class="mb-4">
                        <h5><i class="fas fa-tag me-2"></i>Statut actuel</h5>
                        @switch($rendezVous->statut)
                            @case('en_attente')
                                <span class="badge bg-warning text-dark fs-6"><i class="fas fa-clock me-1"></i> En attente</span>
                                @break
                            @case('confirme')
                                <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i> Confirmée</span>
                                @break
                            @case('termine')
                                <span class="badge bg-info fs-6"><i class="fas fa-flag-checkered me-1"></i> Terminée</span>
                                @break
                            @case('en_negociation')
                                <span class="badge bg-warning fs-6 text-dark"><i class="fas fa-flag-checkered me-1"></i> En négociation</span>
                                @break

                            @case('annule')
                                <span class="badge bg-danger fs-6"><i class="fas fa-ban me-1"></i> Annulée</span>
                                @break
                            @default
                                <span class="badge bg-dark fs-6">{{ $rendezVous->statut }}</span>
                        @endswitch
                    </div>

                    <!-- Informations client et dates -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user me-2"></i>Vos informations</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nom complet</th>
                                    <td>{{ $rendezVous->nom_complet }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $rendezVous->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $rendezVous->telephone }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar-alt me-2"></i>Date et heure du Rendez-vous</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Date du Rendez-vous</th>
                                    <td>{{ \Carbon\Carbon::parse($rendezVous->date_rdv)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Heure du Rendez-vous</th>
                                    <td>{{ \Carbon\Carbon::parse($rendezVous->heure_rdv)->format('H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Lieu du Rendez-vous</th>
                                    <td><a href="https://maps.app.goo.gl/xxnVRVPJMAs4a3ag6" target="_blank" rel="noopener noreferrer">Trajet vers CGV Motors</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informations véhicule -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="fas fa-car me-2"></i>Véhicule réservé</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Marque et Série</th>
                                    <td>{{ $rendezVous->marque_vehicule }} {{ $rendezVous->serie_vehicule }}</td>
                                </tr>
                                <tr>
                                    <th>Année</th>
                                    <td>{{ $rendezVous->annee_vehicule }}</td>
                                </tr>
                                <tr>
                                    <th>Transmission</th>
                                    <td>{{ $rendezVous->transmission_vehicule }}</td>
                                </tr>
                                <tr>
                                    <th>Carburant</th>
                                    <td>{{ $rendezVous->carburant_vehicule }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informations de paiement -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="fas fa-money-bill-wave me-2"></i>Informations de paiement</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Montant total</th>
                                    <td>{{ number_format($rendezVous->prix_vehicule, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($rendezVous->notes)
                    <div class="mt-4">
                        <h5><i class="fas fa-sticky-note me-2"></i>Notes</h5>
                        <div class="p-3 bg-light border rounded">
                            {{ $rendezVous->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale - Actions -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-1"></i>
                    Actions disponibles
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('mes_rdv') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour à mes rendez-vous
                        </a>
                        
                        @if($rendezVous->statut == 'approuvee' || $rendezVous->statut == 'en_attente')
                            <form action="{{ route('vente.rdv.cancel', $rendezVous->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times me-2"></i> Annuler la réservation
                                </button>
                            </form>
                        @endif
                        
                        @if($rendezVous->statut == 'terminee')
                            <a href="{{ route('vente.rdv.review', $rendezVous->id) }}" class="btn btn-primary w-100">
                                <i class="fas fa-star me-2"></i> Laisser un avis
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
