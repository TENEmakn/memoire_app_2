@extends('layouts.appadmin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails de la réservation #{{ $reservation->id }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reservations') }}">Réservations</a></li>
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
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations de la réservation
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Statut actuel</h5>
                        @switch($reservation->statut)
                            @case('en_attente')
                                <span class="badge bg-warning text-dark fs-6">En attente</span>
                                @break
                            @case('approuvee')
                                <span class="badge bg-success fs-6">Approuvée</span>
                                @break
                            @case('rejetee')
                                <span class="badge bg-danger fs-6">Rejetée</span>
                                @break
                            @case('terminee')
                                <span class="badge bg-info fs-6">Terminée</span>
                                @break
                            @case('annulee')
                                <span class="badge bg-secondary fs-6">Annulée</span>
                                @break
                            @default
                                <span class="badge bg-dark fs-6">{{ $reservation->statut }}</span>
                        @endswitch
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations du client</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nom complet</th>
                                    <td>{{ $reservation->nom }} {{ $reservation->prenom }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $reservation->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $reservation->numero_telephone }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Détails du trajet</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Date de début</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Date de fin</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Heure de départ</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->heure_depart)->format('H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Lieu de départ</th>
                                    <td>{{ $reservation->lieu_depart }}</td>
                                </tr>
                                <tr>
                                    <th>Lieu d'arrivée</th>
                                    <td>{{ $reservation->lieu_arrivee }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Véhicule réservé</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Marque et Série</th>
                                    <td>{{ $reservation->marque_vehicule }} {{ $reservation->serie_vehicule }}</td>
                                </tr>
                                <tr>
                                    <th>ID du véhicule</th>
                                    <td>{{ $reservation->vehicule_id }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Chauffeur assigné</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nom complet</th>
                                    <td>{{ $reservation->nom_chauffeur }} {{ $reservation->prenom_chauffeur }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $reservation->numero_telephone_chauffeur }}</td>
                                </tr>
                                <tr>
                                    <th>ID du chauffeur</th>
                                    <td>{{ $reservation->chauffeur_id }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informations de paiement</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Montant total</th>
                                    <td>{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                <tr>
                                    <th>Mode de paiement</th>
                                    <td>{{ str_replace('_', ' ', $reservation->mode_paiement) }}</td>
                                </tr>
                                <tr>
                                    <th>Référence</th>
                                    <td>{{ $reservation->reference_paiement ?: 'Non renseigné' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Dates importantes</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Réservation créée le</th>
                                    <td>{{ $reservation->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière mise à jour</th>
                                    <td>{{ $reservation->updated_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($reservation->notes)
                    <div class="mt-4">
                        <h5>Notes</h5>
                        <div class="p-3 bg-light border rounded">
                            {{ $reservation->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-1"></i>
                    Actions disponibles
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.reservations') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                        </a>
                        
                        @if($reservation->statut == 'en_attente')
                            <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-2"></i> Approuver la réservation
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-ban me-2"></i> Rejeter la réservation
                                </button>
                            </form>
                        @endif
                        
                        @if($reservation->statut == 'approuvee')
                            <form action="{{ route('admin.reservations.complete', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-check-double me-2"></i> Marquer comme terminée
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 