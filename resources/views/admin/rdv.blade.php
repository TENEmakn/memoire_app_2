@extends('layouts.appadmin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Gestion des rendez-vous</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Rendez-vous</li>
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
                <i class="fas fa-calendar me-1"></i>
                Liste des rendez-vous
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row g-2" role="group" aria-label="Filtrer les rendez-vous">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv') }}" class="btn btn-outline-primary w-100 {{ !request()->has('statut') && !request()->has('fin_rdv') && !request()->route('statut') && !request()->route('fin_rdv') && !request()->has('date_debut') && !request()->has('date_fin') ? 'active' : '' }}">Tous</a>
                        </div>
                        
                        <!-- Filtres de statut -->
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.filter', ['statut' => 'en_attente', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-warning w-100 {{ (request('statut') == 'en_attente' || request()->route('statut') == 'en_attente') && empty(request()->route('fin_rdv')) && empty(request('fin_rdv')) ? 'active' : '' }}">
                                En attente <span class="badge bg-warning text-dark">{{ $counts['en_attente'] ?? 0 }}</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.filter', ['statut' => 'confirme', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-success w-100 {{ (request('statut') == 'confirme' || request()->route('statut') == 'confirme') && empty(request()->route('fin_rdv')) && empty(request('fin_rdv')) ? 'active' : '' }}">
                                Confirmés <span class="badge bg-success">{{ $counts['confirme'] ?? 0 }}</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.filter', ['statut' => 'en_negociation', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-warning w-100 {{ (request('statut') == 'en_negociation' || request()->route('statut') == 'en_negociation') && empty(request()->route('fin_rdv')) && empty(request('fin_rdv')) ? 'active' : '' }}">
                                En négociation <span class="badge bg-warning">{{ $counts['en_negociation'] ?? 0 }}</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.filter', ['statut' => 'termine', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-info w-100 {{ (request('statut') == 'termine' || request()->route('statut') == 'termine') && empty(request()->route('fin_rdv')) && empty(request('fin_rdv')) ? 'active' : '' }}">
                                Terminés <span class="badge bg-info text-dark">{{ $counts['termine'] ?? 0 }}</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.filter', ['statut' => 'annule', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-danger w-100 {{ (request('statut') == 'annule' || request()->route('statut') == 'annule') && empty(request()->route('fin_rdv')) && empty(request('fin_rdv')) ? 'active' : '' }}">
                                Annulés <span class="badge bg-danger">{{ $counts['annule'] ?? 0 }}</span>
                            </a>
                        </div>
                        
                        <!-- Filtres de fin_rdv -->
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.combined.filter', ['statut' => 'termine', 'fin_rdv' => 'achete', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-success w-100 {{ (request('fin_rdv') == 'achete' || request()->route('fin_rdv') == 'achete') && empty(request()->route('statut')) && empty(request('statut')) ? 'active' : '' }}">
                                Achetés <span class="badge bg-success">{{ $counts['achete'] ?? 0 }}</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <a href="{{ route('admin.rdv.combined.filter', ['statut' => 'termine', 'fin_rdv' => 'refuse', 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin')]) }}" 
                               class="btn btn-outline-danger w-100 {{ (request('fin_rdv') == 'refuse' || request()->route('fin_rdv') == 'refuse') && empty(request()->route('statut')) && empty(request('statut')) ? 'active' : '' }}">
                                Refusés <span class="badge bg-danger">{{ $counts['refuse'] ?? 0 }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtre par date de création -->
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-filter me-1"></i> Filtrer par date de création
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.rdv') }}" method="GET" class="row g-3">
                                <!-- Conserver les filtres existants -->
                                @if(request('statut'))
                                    <input type="hidden" name="statut" value="{{ request('statut') }}">
                                @endif
                                @if(request('fin_rdv'))
                                    <input type="hidden" name="fin_rdv" value="{{ request('fin_rdv') }}">
                                @endif

                                <div class="col-md-4">
                                    <label for="date_debut" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_fin" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary flex-grow-1">Filtrer</button>
                                        <a href="{{ route('admin.rdv') }}" class="btn btn-outline-secondary flex-grow-1">Effacer filtres</a>
                                    </div>
                                </div>
                            </form>
                            @if(request('date_debut') || request('date_fin'))
                                <div class="mt-3">
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-calendar-alt"></i> 
                                        Filtré par date : 
                                        {{ request('date_debut') ? \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') : 'depuis le début' }} 
                                        - 
                                        {{ request('date_fin') ? \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') : "jusqu'à aujourd'hui" }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="rdvTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Vehicule</th>
                                <th>Date et Heure</th>
                                <th>Notes</th>
                                <th>Statut</th>
                                <th>Décision</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rendezVous as $rdv)
                                <tr>
                                    <td>{{ $rdv->id }}</td>
                                    <td>
                                        {{ $rdv->nom_complet }}<br>
                                        <small>{{ $rdv->email }}</small><br>
                                        <small>{{ $rdv->telephone }}</small>
                                    </td>
                                    <td>{{ $rdv->marque_vehicule }} {{ $rdv->serie_vehicule }} {{ $rdv->annee_vehicule }} <br> {{ $rdv->transmission_vehicule }} <br>{{ $rdv->carburant_vehicule }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y') }} <br> {{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}
                                    </td>
                                    <td>{{ $rdv->notes }}</td>
                                    <td>
                                        @switch($rdv->statut)
                                            @case('en_attente')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                                @break
                                            @case('confirme')
                                                <span class="badge bg-success">Confirmé</span>
                                                @break
                                            @case('en_negociation')
                                                <span class="badge bg-warning">En négociation</span>
                                                @break
                                            @case('annule')
                                                <span class="badge bg-danger">Annulé</span>
                                                @break
                                            @case('termine')
                                                <span class="badge bg-success">Terminé</span>
                                                @break
                                            @default
                                                <span class="badge bg-dark">{{ $rdv->statut }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($rdv->fin_rdv)
                                            @case('achete')
                                                <span class="badge bg-success">Acheté</span>
                                                @break
                                            @case('refuse')
                                                <span class="badge bg-danger">Refusé</span>
                                                @break
                                            @default
                                                <span class="badge bg-dark">{{ $rdv->fin_rdv }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.rdv.show', $rdv->id) }}">Détails</a></li>
                                                @if(!$rdv->fin_rdv)
                                                    @if($rdv->statut == 'en_attente')
                                                        <li>
                                                            <form action="{{ route('admin.rdv.confirm', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Confirmer</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.rdv.cancel', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Annuler</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($rdv->statut == 'confirme')
                                                        <li>
                                                            <form action="{{ route('admin.rdv.negociation', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">En négociation</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.rdv.cancel', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Annuler</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($rdv->statut == 'en_negociation')
                                                        <li>
                                                            <form action="{{ route('admin.rdv.complete', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Terminer</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.rdv.cancel', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Annuler</button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    @if($rdv->statut == 'termine')
                                                        <li>
                                                            <form action="{{ route('admin.rdv.acheter', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Acheter</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.rdv.refuser', $rdv->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">Refuser</button>
                                                            </form>
                                                        </li>
                                                    @endif
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
            $('#rdvTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json'
                }
            });
        });
    </script>
@endsection


