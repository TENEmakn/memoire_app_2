@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Mes missions</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="mb-4">
        <div class="btn-group" role="group" aria-label="Filtrer les missions">
            <a href="{{ route('mes_missions') }}" class="btn btn-outline-primary {{ !request()->has('statut') ? 'active' : '' }}">Toutes</a>
            <a href="{{ route('mes_missions', ['statut' => 'en_attente']) }}" class="btn btn-outline-warning {{ request('statut') == 'en_attente' ? 'active' : '' }}">En attente</a>
            <a href="{{ route('mes_missions', ['statut' => 'approuvee']) }}" class="btn btn-outline-success {{ request('statut') == 'approuvee' ? 'active' : '' }}">Validées</a>
            <a href="{{ route('mes_missions', ['statut' => 'terminee']) }}" class="btn btn-outline-info {{ request('statut') == 'terminee' ? 'active' : '' }}">Terminées</a>
            <a href="{{ route('mes_missions', ['statut' => 'refusee']) }}" class="btn btn-outline-danger {{ request('statut') == 'rejetee' ? 'active' : '' }}">Refusées</a>
        </div>
    </div>
    
    @if(request()->has('statut'))
        <div class="alert alert-info mb-4">
            <i class="fas fa-filter me-2"></i> 
            Missions filtrées par statut : 
            @switch(request('statut'))
                @case('en_attente')
                    <strong>En attente</strong>
                    @break
                @case('approuvee')
                    <strong>Validées</strong>
                    @break
                @case('terminee')
                    <strong>Terminées</strong>
                    @break
                @case('refusee')
                    <strong>Refusées</strong>
                    @break
                @default
                    <strong>{{ request('statut') }}</strong>
            @endswitch
            <a href="{{ route('mes_missions') }}" class="ms-2"><i class="fas fa-times-circle"></i> Supprimer le filtre</a>
        </div>
    @endif
    
    @if($reservations->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Vous n'avez pas encore de missions attribuées.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Véhicule</th>
                        <th>Dates</th>
                        <th>Heure de départ</th>
                        <th>Lieu de départ</th>
                        <th>Trajet</th>
                        <th>Client</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
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
                                <i class="fas fa-map-marker-alt"></i> <a href="{{ $reservation->point_depart }}" target="_blank" rel="noopener noreferrer">Lieu de départ</a> 
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt"></i> {{ $reservation->lieu_depart }}<br>
                                <i class="fas fa-location-arrow"></i> {{ $reservation->lieu_arrivee }}
                            </td>
                            <td>
                                <i class="fas fa-user"></i> {{ $reservation->nom_client ?? $reservation->user->name ?? 'Client' }}<br>
                                <i class="fas fa-phone"></i> {{ $reservation->numero_telephone ?? $reservation->user->phone ?? 'N/A' }}
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
                                    @default
                                        <span class="badge bg-dark">{{ $reservation->statut }}</span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 