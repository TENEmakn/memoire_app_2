@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Mes Rendez-vous</h2>
    
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
        <div class="row g-2">
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_rdv') }}" class="btn btn-outline-primary w-100 {{ !request()->has('statut') ? 'active' : '' }}">Tous</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_rdv', ['statut' => 'en_attente']) }}" class="btn btn-outline-warning w-100 {{ request('statut') == 'en_attente' ? 'active' : '' }}">En attente</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_rdv', ['statut' => 'approuve']) }}" class="btn btn-outline-success w-100 {{ request('statut') == 'approuve' ? 'active' : '' }}">Approuvés</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_rdv', ['statut' => 'en_negociation']) }}" class="btn btn-outline-warning w-100 {{ request('statut') == 'en_negociation' ? 'active' : '' }}">En négociation</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_rdv', ['statut' => 'termine']) }}" class="btn btn-outline-info w-100 {{ request('statut') == 'termine' ? 'active' : '' }}">Terminés</a>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <a href="{{ route('mes_rdv', ['statut' => 'annule']) }}" class="btn btn-outline-danger w-100 {{ request('statut') == 'annule' ? 'active' : '' }}">Annulés</a>
            </div>
        </div>
    </div>
    
    @if($rendezVous->isEmpty())
        <div class="alert alert-info">
            Vous n'avez pas encore de rendez-vous.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Véhicule</th>
                        <th>Date et heure</th>
                        <th>Informations</th>
                        <th>Lieu du RDV</th>
                        <th>Prix du véhicule</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rendezVous as $rdv)
                        <tr>
                            <td>
                                <strong>{{ $rdv->marque_vehicule }} {{ $rdv->serie_vehicule }}</strong>
                                <br>
                                <small>{{ $rdv->annee_vehicule }} | {{ $rdv->transmission_vehicule }} | {{ $rdv->carburant_vehicule }}</small>
                            </td>
                            <td>
                                <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y') }}<br>
                                <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}
                            </td>
                            <td>
                                <i class="fas fa-user"></i> {{ $rdv->nom_complet }}<br>
                                <i class="fas fa-phone"></i> {{ $rdv->telephone }}
                                @if($rdv->email)
                                    <br><i class="fas fa-envelope"></i> {{ $rdv->email }}
                                @endif
                            </td>
                            <td><a href="https://maps.app.goo.gl/xxnVRVPJMAs4a3ag6" target="_blank" rel="noopener noreferrer">Trajet vers CGV MOTORS</a></td>
                            <td>{{ number_format($rdv->prix_vehicule, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @switch($rdv->statut)
                                    @case('en_attente')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                        @break
                                    @case('confirme')
                                        <span class="badge bg-success">Confirmée</span>
                                        @break
                                    @case('en_negociation')
                                        <span class="badge bg-warning text-dark">En négociation</span>
                                        @break
                                    @case('annule')
                                        <span class="badge bg-danger">Annulé</span>
                                        @break
                                    @case('termine')
                                        <span class="badge bg-info">Terminé</span>
                                        @break
                                    @default
                                        <span class="badge bg-success">{{ $rdv->statut }}</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('rdv.show', $rdv->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                @if($rdv->statut == 'en_attente')
                                    <form action="{{ route('vente.rdv.cancel', $rdv->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')">
                                            <i class="fas fa-times"></i> Annuler
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection


