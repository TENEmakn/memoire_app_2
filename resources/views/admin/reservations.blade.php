@extends('layouts.appadmin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Gestion des réservations</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Réservations</li>
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

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-calendar-alt me-1"></i>
                Liste des réservations
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex flex-wrap gap-2 mb-3" role="group" aria-label="Filtrer les réservations">
                        <a href="{{ route('admin.reservations', request()->only(['date_debut', 'date_fin'])) }}" 
                           class="btn btn-outline-primary {{ !request()->has('statut') && !request()->route('statut') ? 'active' : '' }}">
                            Toutes
                        </a>
                        <a href="{{ route('admin.reservations.filter', array_merge(['statut' => 'en_attente'], request()->only(['date_debut', 'date_fin']))) }}" 
                           class="btn btn-outline-warning {{ request('statut') == 'en_attente' || request()->route('statut') == 'en_attente' ? 'active' : '' }}">
                            En attente
                            @php
                                $enAttenteCount = \App\Models\LocationRequest::where('statut', 'en_attente')->count();
                            @endphp
                            @if($enAttenteCount > 0)
                                <span class="badge bg-dark rounded-pill ms-1">{{ $enAttenteCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.reservations.filter', array_merge(['statut' => 'approuvee'], request()->only(['date_debut', 'date_fin']))) }}" 
                           class="btn btn-outline-success {{ request('statut') == 'approuvee' || request()->route('statut') == 'approuvee' ? 'active' : '' }}">
                            Validées
                            @php
                                $approuveeCount = \App\Models\LocationRequest::where('statut', 'approuvee')->count();
                            @endphp
                            @if($approuveeCount > 0)
                                <span class="badge bg-dark rounded-pill ms-1">{{ $approuveeCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.reservations.filter', array_merge(['statut' => 'terminee'], request()->only(['date_debut', 'date_fin']))) }}" 
                           class="btn btn-outline-info {{ request('statut') == 'terminee' || request()->route('statut') == 'terminee' ? 'active' : '' }}">
                            Terminées
                            @php
                                $termineeCount = \App\Models\LocationRequest::where('statut', 'terminee')->count();
                            @endphp
                            @if($termineeCount > 0)
                                <span class="badge bg-dark rounded-pill ms-1">{{ $termineeCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.reservations.filter', array_merge(['statut' => 'refusee'], request()->only(['date_debut', 'date_fin']))) }}" 
                           class="btn btn-outline-danger {{ request('statut') == 'rejetee' || request()->route('statut') == 'rejetee' ? 'active' : '' }}">
                            Refusées
                            @php
                                $refuseeCount = \App\Models\LocationRequest::where('statut', 'refusee')->count();
                            @endphp
                            @if($refuseeCount > 0)
                                <span class="badge bg-dark rounded-pill ms-1">{{ $refuseeCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('admin.reservations') }}" method="GET" class="d-flex flex-wrap gap-2">
                            @if(request()->has('statut'))
                                <input type="hidden" name="statut" value="{{ request('statut') }}">
                            @endif
                            <div class="input-group mb-2 mb-sm-0 flex-grow-1 flex-sm-grow-0">
                                <span class="input-group-text">Du</span>
                                <input type="date" class="form-control" name="date_debut" value="{{ request('date_debut') }}">
                            </div>
                            <div class="input-group mb-2 mb-sm-0 flex-grow-1 flex-sm-grow-0">
                                <span class="input-group-text">Au</span>
                                <input type="date" class="form-control" name="date_fin" value="{{ request('date_fin') }}">
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                @if(request('date_debut') || request('date_fin'))
                                    @if(request()->has('statut') || request()->route('statut'))
                                        @php
                                            $statut = request('statut') ?: request()->route('statut');
                                            $resetUrl = route('admin.reservations.filter', ['statut' => $statut]);
                                        @endphp
                                        <a href="{{ $resetUrl }}" class="btn btn-outline-secondary">Réinitialiser les dates</a>
                                    @else
                                        <a href="{{ route('admin.reservations') }}" class="btn btn-outline-secondary">Réinitialiser les dates</a>
                                    @endif
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="reservationsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Véhicule</th>
                                <th>Chauffeur</th>
                                <th>Dates</th>
                                <th>Heure de départ</th>
                                <th>Trajet</th>
                                <th>Point de départ</th>
                                <th>Montant</th>
                                <th>Mode de paiement</th>
                                <th>Référence</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation['id'] }}</td>
                                    <td>
                                        {{ $reservation['nom'] }} {{ $reservation['prenom'] }}<br>
                                        <small>{{ $reservation['email'] }}</small><br>
                                        <small>{{ $reservation['numero_telephone'] }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $reservation['marque_vehicule'] }} {{ $reservation['serie_vehicule'] }}</strong>
                                    </td>
                                    <td>
                                        {{ $reservation['nom_chauffeur'] }} {{ $reservation['prenom_chauffeur'] }}<br>
                                        <small>{{ $reservation['numero_telephone_chauffeur'] }}</small>
                                    </td>
                                    <td>
                                        Du {{ \Carbon\Carbon::parse($reservation['date_debut'])->format('d/m/Y') }}<br>
                                        Au {{ \Carbon\Carbon::parse($reservation['date_fin'])->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($reservation['heure_depart'])->format('H:i') }}
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt"></i> {{ $reservation['lieu_depart'] }}<br>
                                        <i class="fas fa-location-arrow"></i> {{ $reservation['lieu_arrivee'] }}
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt"></i> <a href="{{ $reservation['point_depart'] }}" target="_blank" rel="noopener noreferrer">Lieu de prise</a>
                                    </td>
                                    <td>{{ number_format($reservation['prix_total'], 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        {{ str_replace('_', ' ', $reservation['mode_paiement']) }}
                                    </td>
                                    <td>{{ $reservation['reference_paiement'] }}</td>
                                    <td>
                                        @switch($reservation['statut'])
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
                                                <span class="badge bg-dark">{{ $reservation['statut'] }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.reservations.show', $reservation['id']) }}">Détails</a></li>
                                                @if($reservation['statut'] == 'en_attente')
                                                    <li>
                                                        <form action="{{ route('admin.reservations.approve', $reservation['id']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">Approuver</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.reservations.reject', $reservation['id']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">Rejeter</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($reservation['statut'] == 'approuvee')
                                                    <li>
                                                        <form action="{{ route('admin.reservations.complete', $reservation['id']) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">Marquer comme terminée</button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#reservationsTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
                }
            });
        });
    </script>
@endsection