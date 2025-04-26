@extends('layouts.appadmin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Détails du rendez-vous</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.rdv') }}">Rendez-vous</a></li>
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

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-calendar-check me-1"></i>
                Informations du rendez-vous #{{ $rendezVous->id }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations du client</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Nom complet :</th>
                                <td>{{ $rendezVous->nom_complet }}</td>
                            </tr>
                            <tr>
                                <th>Email :</th>
                                <td>{{ $rendezVous->email ?? 'Non spécifié' }}</td>
                            </tr>
                            <tr>
                                <th>Téléphone :</th>
                                <td>{{ $rendezVous->telephone }}</td>
                            </tr>
                            <tr>
                                <th>Utilisateur lié :</th>
                                <td>
                                    @if($rendezVous->user_id)
                                        Oui (ID: {{ $rendezVous->user_id }})
                                    @else
                                        Non
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Détails du rendez-vous</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Date :</th>
                                <td>{{ \Carbon\Carbon::parse($rendezVous->date_rdv)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Heure :</th>
                                <td>{{ \Carbon\Carbon::parse($rendezVous->heure_rdv)->format('H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Statut :</th>
                                <td>
                                    @switch($rendezVous->statut)
                                        @case('en_attente')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                            @break
                                        @case('confirme')
                                            <span class="badge bg-success">Confirmé</span>
                                            @break
                                        @case('en_negociation')
                                            <span class="badge bg-info">En négociation</span>
                                            @break
                                        @case('termine')
                                            <span class="badge bg-primary">Terminé</span>
                                            @break
                                        @case('annule')
                                            <span class="badge bg-danger">Annulé</span>
                                            @break
                                        @default
                                            <span class="badge bg-dark">{{ $rendezVous->statut }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            @if($rendezVous->fin_rdv)
                            <tr>
                                <th>Décision :</th>
                                <td class="badge bg-danger text-white">{{ $rendezVous->fin_rdv }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Créé le :</th>
                                <td>{{ \Carbon\Carbon::parse($rendezVous->created_at)->format('d/m/Y à H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Véhicule concerné</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        @if($rendezVous->image_vehicule)
                                            <img src="{{ asset('storage/' . $rendezVous->image_vehicule) }}" class="img-fluid rounded" alt="Véhicule">
                                        @else
                                            <div class="text-center p-4 bg-light rounded">
                                                <i class="fas fa-car fa-3x text-muted"></i>
                                                <p class="mt-2 text-muted">Pas d'image disponible</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h4>{{ $rendezVous->marque_vehicule }} {{ $rendezVous->serie_vehicule }} ({{ $rendezVous->annee_vehicule }})</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Transmission :</strong> {{ $rendezVous->transmission_vehicule }}</p>
                                                <p><strong>Carburant :</strong> {{ $rendezVous->carburant_vehicule }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Places :</strong> {{ $rendezVous->nb_places_vehicule }}</p>
                                                <p><strong>Prix :</strong> {{ number_format($rendezVous->prix_vehicule, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($rendezVous->notes)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Notes</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $rendezVous->notes }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Actions</h5>
                        <div class="d-flex gap-2">
                            @if($rendezVous->statut == 'en_attente')
                                <form action="{{ route('admin.rdv.confirm', $rendezVous->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Confirmer</button>
                                </form>
                                <form action="{{ route('admin.rdv.cancel', $rendezVous->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Annuler</button>
                                </form>
                            @endif

                            @if($rendezVous->statut == 'confirme')
                                <form action="{{ route('admin.rdv.negociation', $rendezVous->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-info"><i class="fas fa-handshake"></i> En négociation</button>
                                </form>
                                <form action="{{ route('admin.rdv.cancel', $rendezVous->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Annuler</button>
                                </form>
                            @endif

                            @if($rendezVous->statut == 'en_negociation')
                                <form action="{{ route('admin.rdv.complete', $rendezVous->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-check-double"></i> Terminer</button>
                                </form>
                                <form action="{{ route('admin.rdv.cancel', $rendezVous->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Annuler</button>
                                </form>
                            @endif

                            <a href="{{ route('admin.rdv') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 