@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Chauffeurs</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">TOTAL CHAUFFEURS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $chauffeurs->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">NOUVEAUX CE MOIS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ $chauffeurs->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-info text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">CHAUFFEURS ACTIFS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ $chauffeurs->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-info text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">CHAUFFEURS EN MISSION</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ \App\Models\LocationRequest::whereNotNull('chauffeur_id')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Chauffeurs les plus actifs
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Chauffeur de l'année -->
                        <div class="col-md-6 border-end">
                            <div class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-badge rounded-pill bg-primary text-white px-3 py-1 me-3">
                                        <i class="fas fa-calendar-check"></i> Meilleur de l'année
                                    </div>
                                    @if(isset($topChauffeurOfYearData) && $topChauffeurOfYearData)
                                    <div class="badge bg-success rounded-pill ms-auto">
                                        <i class="fas fa-car me-1"></i>
                                        {{ DB::table('location_requests')->where('chauffeur_id', $topChauffeurOfYearData->id)->whereYear('created_at', date('Y'))->count() }} courses
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="d-flex align-items-center mt-4">
                                    <div class="avatar-container">
                                        <div class="avatar-circle bg-primary text-white">
                                            @if(isset($topChauffeurOfYearData) && $topChauffeurOfYearData)
                                                {{ strtoupper(substr($topChauffeurOfYearData->email, 0, 1)) }}
                                            @else
                                                ?
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3 user-details">
                                        @if(isset($topChauffeurOfYearData) && $topChauffeurOfYearData)
                                            <h5 class="fw-bold mb-1">{{ $topChauffeurOfYearData->name ?? '' }} {{ $topChauffeurOfYearData->prenom ?? '' }}</h5>
                                            <p class="text-primary mb-0">{{ $topChauffeurOfYearData->email }}</p>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-phone me-1"></i> {{ $topChauffeurOfYearData->telephone ?? 'Non disponible' }}
                                            </p>
                                        @else
                                            <h5 class="fw-bold mb-1">Aucun chauffeur</h5>
                                            <p class="text-muted mb-0">Pas de données disponibles</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chauffeur du mois -->
                        <div class="col-md-6">
                            <div class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-badge rounded-pill bg-info text-white px-3 py-1 me-3">
                                        <i class="fas fa-calendar-day"></i> Meilleur du mois
                                    </div>
                                    @if(isset($topChauffeurOfMonthData) && $topChauffeurOfMonthData)
                                    <div class="badge bg-success rounded-pill ms-auto">
                                        <i class="fas fa-car me-1"></i>
                                        {{ DB::table('location_requests')->where('chauffeur_id', $topChauffeurOfMonthData->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->count() }} courses
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="d-flex align-items-center mt-4">
                                    <div class="avatar-container">
                                        <div class="avatar-circle bg-info text-white">
                                            @if(isset($topChauffeurOfMonthData) && $topChauffeurOfMonthData)
                                                {{ strtoupper(substr($topChauffeurOfMonthData->email, 0, 1)) }}
                                            @else
                                                ?
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3 user-details">
                                        @if(isset($topChauffeurOfMonthData) && $topChauffeurOfMonthData)
                                            <h5 class="fw-bold mb-1">{{ $topChauffeurOfMonthData->name ?? '' }} {{ $topChauffeurOfMonthData->prenom ?? '' }}</h5>
                                            <p class="text-info mb-0">{{ $topChauffeurOfMonthData->email }}</p>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-phone me-1"></i> {{ $topChauffeurOfMonthData->telephone ?? 'Non disponible' }}
                                            </p>
                                        @else
                                            <h5 class="fw-bold mb-1">Aucun chauffeur</h5>
                                            <p class="text-muted mb-0">Pas de données disponibles</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Rechercher un chauffeur</h5>
                </div>
                <div class="card-body p-4">
                    <div class="search-container">
                        <form action="{{ route('admin.chauffeur.search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" placeholder="Nom, prénom, email ou téléphone" class="form-control" value="{{ $search ?? '' }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                            </div>
                        </form>
                        @if(isset($search))
                        <div class="mt-2 text-muted">
                            <small>Résultats de recherche pour: <strong>{{ $search }}</strong></small>
                            <a href="{{ route('admin.chauffeur') }}" class="ms-2 text-primary">
                                <i class="fas fa-times"></i> Effacer
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <button class="btn btn-primary w-100 py-3" style="border-radius: 25px;" data-bs-toggle="modal" data-bs-target="#addChauffeurModal">
                <i class="fas fa-plus-circle me-2"></i> Ajouter chauffeur
            </button>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card user-info-card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Liste des chauffeurs</h5>
                </div>
                <div class="card-body p-3 position-relative">
                    @forelse ($chauffeurs as $chauffeur)
                    <div class="new-user-registration bg-light p-2 rounded shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="date-time">
                                <div class="date-badge bg-white py-1 px-2 d-inline-block rounded shadow-sm small">
                                    <i class="far fa-calendar-alt me-1 text-primary"></i>
                                    <span class="fw-bold">{{ $chauffeur->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="time-badge bg-white py-1 px-2 d-inline-block ms-1 rounded shadow-sm small">
                                    <i class="far fa-clock me-1 text-primary"></i>
                                    <span class="fw-bold">{{ $chauffeur->created_at->format('H\hi') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="user-details-container d-flex flex-wrap gap-3">
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="detail-label small text-muted">Nom</div>
                                <div class="detail-value fw-bold small">{{ $chauffeur->name }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="detail-label small text-muted">Prénom</div>
                                <div class="detail-value fw-bold small">{{ $chauffeur->prenom }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-label small text-muted">Email</div>
                                <div class="detail-value fw-bold small">{{ $chauffeur->email }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="detail-label small text-muted">Permis</div>
                                <div class="detail-value fw-bold small">{{ $chauffeur->categorie_permis ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="detail-label small text-muted">Téléphone</div>
                                <div class="detail-value fw-bold small">{{ $chauffeur->telephone ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="action-buttons mt-3 d-flex justify-content-end">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#chauffeurModal{{ $chauffeur->id }}">
                                <i class="fas fa-eye me-1"></i> Détails
                            </button>
                            <button class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $chauffeur->id }}">
                                <i class="fas fa-trash-alt me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Aucun chauffeur trouvé dans la base de données.
                    </div>
                    @endforelse
                </div>
                <div class="card-footer text-center p-3">
                    @if(isset($search))
                    <a href="{{ route('admin.chauffeur') }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Voir tous les chauffeurs
                    </a>
                    @else
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-check me-1"></i> Vous voyez tous les chauffeurs
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les détails des chauffeurs -->
@foreach($chauffeurs as $chauffeur)
<div class="modal fade" id="chauffeurModal{{ $chauffeur->id }}" tabindex="-1" aria-labelledby="chauffeurModalLabel{{ $chauffeur->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="chauffeurModalLabel{{ $chauffeur->id }}">
                    <i class="fas fa-user-circle me-2"></i>Détails du chauffeur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="row mb-4">
                    <div class="col-md-12 text-center mb-4">
                        <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($chauffeur->email, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $chauffeur->name ?? 'N/A' }} {{ $chauffeur->prenom ?? '' }}</h4>
                        <p class="text-primary mb-0">
                            <i class="fas fa-envelope me-1"></i> {{ $chauffeur->email }}
                        </p>
                        <div class="badge bg-success mt-2">
                            <i class="fas fa-calendar-alt me-1"></i> Inscrit le {{ $chauffeur->created_at->format('d/m/Y à H\hi') }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-id-card me-2 text-primary"></i>Informations personnelles</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="label">Nom</div>
                                    <div class="value">{{ $chauffeur->name ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Prénom</div>
                                    <div class="value">{{ $chauffeur->prenom ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Téléphone</div>
                                    <div class="value">{{ $chauffeur->telephone ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Date de naissance</div>
                                    <div class="value">{{ $chauffeur->date_naissance ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Commune</div>
                                    <div class="value">{{ $chauffeur->commune ?? 'Non renseignée' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Ville</div>
                                    <div class="value">{{ $chauffeur->ville ?? 'Non renseignée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-id-card-alt me-2 text-primary"></i>Permis de conduire</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="label">Catégorie</div>
                                    <div class="value">{{ $chauffeur->categorie_permis ?? 'Non renseigné' }}</div>
                                </div>
                                @if($chauffeur->image_permis_recto)
                                <div class="info-row">
                                    <div class="label">Permis recto</div>
                                    <div class="value">
                                        <a href="{{ asset('storage/'.$chauffeur->image_permis_recto) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a>
                                    </div>
                                </div>
                                @endif
                                @if($chauffeur->image_permis_verso)
                                <div class="info-row">
                                    <div class="label">Permis verso</div>
                                    <div class="value">
                                        <a href="{{ asset('storage/'.$chauffeur->image_permis_verso) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-car me-2 text-primary"></i>Activité de chauffeur</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="label">Missions</div>
                                    <div class="value">
                                        {{ DB::table('location_requests')->where('chauffeur_id', $chauffeur->id)->count() }} mission(s)
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Ce mois</div>
                                    <div class="value">
                                        {{ DB::table('location_requests')->where('chauffeur_id', $chauffeur->id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count() }} mission(s)
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Dernière mission</div>
                                    <div class="value">
                                        @php
                                            $lastRequest = DB::table('location_requests')->where('chauffeur_id', $chauffeur->id)->orderBy('created_at', 'desc')->first();
                                        @endphp
                                        @if($lastRequest)
                                            {{ \Carbon\Carbon::parse($lastRequest->created_at)->format('d/m/Y') }}
                                        @else
                                            Aucune mission
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-car me-2 text-primary"></i>Véhicule attribué</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $lastMission = DB::table('location_requests')
                                        ->where('chauffeur_id', $chauffeur->id)
                                        ->whereNotNull('vehicule_id')
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                    
                                    $vehicule = null;
                                    if ($lastMission && $lastMission->vehicule_id) {
                                        $vehicule = DB::table('vehicules')->find($lastMission->vehicule_id);
                                    }else{
                                        $vehicule = DB::table('vehicules')->where('user_id', $chauffeur->id)->first();
                                    }
                                @endphp
                                
                                @if($vehicule)
                                    <div class="vehicle-info">
                                        <div class="mb-3">
                                            <div class="vehicle-image-container text-center mb-3">
                                                @if($vehicule->image_principale)
                                                    <img src="{{ asset('storage/' . $vehicule->image_principale) }}" 
                                                         class="img-fluid rounded" alt="Image du véhicule" 
                                                         style="max-height: 120px; object-fit: contain;">
                                                @else
                                                    <div class="border rounded p-3 text-center bg-light">
                                                        <i class="fas fa-car fa-3x text-muted"></i>
                                                        <p class="mt-2 mb-0 small text-muted">Aucune image</p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="info-row">
                                                <div class="label">Véhicule</div>
                                                <div class="value fw-bold">{{ $vehicule->marque }} {{ $vehicule->serie }}</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="label">Immatriculation</div>
                                                <div class="value">{{ $vehicule->immatriculation }}</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="label">Année</div>
                                                <div class="value">{{ $vehicule->annee }}</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="label">Type</div>
                                                <div class="value">{{ $vehicule->type_vehicule }}</div>
                                            </div>
                                            @if($lastMission)
                                            <div class="info-row">
                                                <div class="label">Dernière mission</div>
                                                <div class="value">{{ \Carbon\Carbon::parse($lastMission->created_at)->format('d/m/Y') }}</div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-car-slash fa-3x text-muted mb-3"></i>
                                        <p class="mb-0">Aucun véhicule attribué à ce chauffeur pour le moment.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-id-card me-2 text-primary"></i>Pièces d'identité</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="label">Statut</div>
                                    <div class="value">
                                        @if(!$chauffeur->image_piece_recto || !$chauffeur->image_piece_verso)
                                            <span class="badge bg-danger">Non fournies</span>
                                        @elseif(!$chauffeur->piece_verifie)
                                            <span class="badge bg-warning">Non vérifiées</span>
                                        @else
                                            <span class="badge bg-success">Vérifiées</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($chauffeur->image_piece_recto || $chauffeur->image_piece_verso)
                                    <div class="documents-preview mt-3">
                                        <div class="row">
                                            @if($chauffeur->image_piece_recto)
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small text-muted">Recto</label>
                                                <div class="document-thumbnail">
                                                    <a href="{{ asset('storage/' . $chauffeur->image_piece_recto) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $chauffeur->image_piece_recto) }}" class="img-thumbnail" alt="Recto de la pièce">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($chauffeur->image_piece_verso)
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small text-muted">Verso</label>
                                                <div class="document-thumbnail">
                                                    <a href="{{ asset('storage/' . $chauffeur->image_piece_verso) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $chauffeur->image_piece_verso) }}" class="img-thumbnail" alt="Verso de la pièce">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($chauffeur->image_piece_recto && $chauffeur->image_piece_verso && !$chauffeur->piece_verifie)
                                    <div class="mt-3 text-center">
                                        <form action="{{ route('admin.users.verify_pieces', $chauffeur->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check-circle me-1"></i> Vérifier les pièces
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center mt-3">
                                        <span class="text-muted">Aucune pièce d'identité fournie</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modals pour la suppression des chauffeurs -->
@foreach($chauffeurs as $chauffeur)
<div class="modal fade" id="deleteModal{{ $chauffeur->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $chauffeur->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $chauffeur->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <div class="avatar-circle bg-danger text-white mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Êtes-vous sûr de vouloir supprimer ce chauffeur?</h4>
                    <p class="text-muted">
                        Vous êtes sur le point de supprimer le compte de <strong>{{ $chauffeur->name }} {{ $chauffeur->prenom }}</strong>.<br>
                        Cette action est irréversible et toutes les données associées seront perdues.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.chauffeur.delete', $chauffeur->id) }}" method="POST" style="display: inline;" class="form-loading">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal pour l'ajout d'un chauffeur -->
<div class="modal fade" id="addChauffeurModal" tabindex="-1" aria-labelledby="addChauffeurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addChauffeurModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un chauffeur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{ route('admin.chauffeur.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="prenom" class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="telephone" class="form-label fw-bold">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="telephone" name="telephone" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_naissance" class="form-label fw-bold">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="categorie_permis" class="form-label fw-bold">Catégorie de permis <span class="text-danger">*</span></label>
                                <select class="form-select" id="categorie_permis" name="categorie_permis[]" multiple required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                                <small class="form-text text-muted">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs catégories</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="commune" class="form-label fw-bold">Commune</label>
                                <input type="text" class="form-control" id="commune" name="commune">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="ville" class="form-label fw-bold">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label fw-bold">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image_permis_recto" class="form-label fw-bold">Image permis recto</label>
                                <input type="file" class="form-control" id="image_permis_recto" name="image_permis_recto" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image_permis_verso" class="form-label fw-bold">Image permis verso</label>
                                <input type="file" class="form-control" id="image_permis_verso" name="image_permis_verso" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="fas fa-id-card me-2"></i>Pièces d'identité</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image_piece_recto" class="form-label fw-bold">Image pièce d'identité recto</label>
                                <input type="file" class="form-control" id="image_piece_recto" name="image_piece_recto" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image_piece_verso" class="form-label fw-bold">Image pièce d'identité verso</label>
                                <input type="file" class="form-control" id="image_piece_verso" name="image_piece_verso" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="piece_verifie" name="piece_verifie" value="1" checked>
                        <label class="form-check-label" for="piece_verifie">
                            Marquer les pièces comme vérifiées
                        </label>
                    </div>

                    <input type="hidden" name="statut" value="chauffeur">

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="fas fa-save me-2"></i>Enregistrer le chauffeur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .transition {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .user-icon {
        color: #00BCD4;
        font-size: 1.2rem;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 8px;
    }
    
    .label {
        font-weight: bold;
        width: 100px;
        color: #6c757d;
    }
    
    .value {
        flex-grow: 1;
        font-weight: 500;
    }
    
    .action-buttons {
        display: flex;
        justify-content: flex-end;
    }
    
    .user-card {
        padding: 15px;
        flex: 1;
        border-radius: 10px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .user-card:hover {
        background-color: #e9ecef;
    }
    
    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .btn.active {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .detail-card {
        width: 45%;
        transition: all 0.3s ease;
    }
    
    .detail-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    .detail-icon {
        font-size: 1.2rem;
    }
    
    .detail-value {
        word-break: break-word;
    }
    
    .avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .stats-badge {
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .user-details {
        width: calc(100% - 80px);
    }
    
    .avatar-container {
        position: relative;
    }
    
    .avatar-container:after {
        content: '';
        position: absolute;
        top: 3px;
        right: 3px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #4CAF50;
        border: 2px solid white;
    }
    
    /* Styles pour les miniatures des pièces d'identité */
    .document-thumbnail {
        position: relative;
        overflow: hidden;
        border-radius: 0.375rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .document-thumbnail:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .document-thumbnail img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        object-position: center;
        cursor: pointer;
    }
    
    .document-thumbnail::after {
        content: "Agrandir";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 12px;
        text-align: center;
        padding: 3px 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .document-thumbnail:hover::after {
        opacity: 1;
    }
</style>

@endsection
