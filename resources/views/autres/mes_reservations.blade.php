@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Mes réservations</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="mb-4">
        <div class="row g-2">
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_reservations') }}" class="btn btn-outline-primary w-100 {{ !request()->has('statut') ? 'active' : '' }}">Toutes</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_reservations', ['statut' => 'en_attente']) }}" class="btn btn-outline-warning w-100 {{ request('statut') == 'en_attente' ? 'active' : '' }}">En attente</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_reservations', ['statut' => 'approuvee']) }}" class="btn btn-outline-success w-100 {{ request('statut') == 'approuvee' ? 'active' : '' }}">Validées</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_reservations', ['statut' => 'terminee']) }}" class="btn btn-outline-info w-100 {{ request('statut') == 'terminee' ? 'active' : '' }}">Terminées</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_reservations', ['statut' => 'refusee']) }}" class="btn btn-outline-danger w-100 {{ request('statut') == 'refusee' ? 'active' : '' }}">Refusées</a>
            </div>
        </div>
    </div>
    
    @if($locationRequests->isEmpty())
        <div class="alert alert-info">
            Vous n'avez pas encore de réservations.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Véhicule</th>
                        <th>Dates</th>
                        <th>Heure de départ</th>
                        <th>Trajet</th>
                        <th>Chauffeur</th>
                        <th>Montant</th>
                        <th>Mode de paiement</th>
                        <th>Statut</th>
                        <th>Référence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locationRequests as $reservation)
                        <tr>
                            <td>
                                <strong>{{ $reservation->marque_vehicule }} {{ $reservation->serie_vehicule }}</strong>
                            </td>
                            <td>
                                Du {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}<br>
                                Au {{ \Carbon\Carbon::parse($reservation->date_fin)->format('d/m/Y') }}
                            </td>
                            <td>
                                <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($reservation->heure_depart)->format('H:i') }}
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt"></i> {{ $reservation->lieu_depart }}<br>
                                <i class="fas fa-location-arrow"></i> {{ $reservation->lieu_arrivee }}
                            </td>
                            
                            <td>
                                <i class="fas fa-user"></i> {{ $reservation->nom_chauffeur }} {{ $reservation->prenom_chauffeur }}<br>
                                <i class="fas fa-phone"></i> {{ $reservation->numero_telephone_chauffeur }}
                            </td>
                            <td>{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <i class="fas fa-credit-card"></i> {{ str_replace('_', ' ', $reservation->mode_paiement) }}
                            </td>
                            <td>
                                @switch($reservation->statut)
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
                                    <!-- @case('annulee')
                                        <span class="badge bg-secondary">Annulée</span>
                                        @break -->
                                    @default
                                        <span class="badge bg-dark">{{ $reservation->statut }}</span>
                                @endswitch
                            </td>
                            <td>{{ $reservation->reference_paiement }}</td>
                            <td>
                                <a href="{{ route('location.show', $reservation->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection