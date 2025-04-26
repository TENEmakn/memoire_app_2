@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Clients</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">TOTAL CLIENTS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $users->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">NOUVEAUX CLIENT DE CE MOIS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-info text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">ClIENTS ACTIFS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
            </div>
        </div>

        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-dark text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">CLIENTS INACTIFS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2 text-dark">
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-dark">{{ $users->where('created_at', '<', now()->startOfMonth())->count() }}</h3>
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
                        Clients les plus actifs
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Utilisateur de l'année -->
                        <div class="col-md-6 border-end">
                            <div class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-badge rounded-pill bg-primary text-white px-3 py-1 me-3">
                                        <i class="fas fa-calendar-check"></i> Meilleur de l'année
                                    </div>
                                    @if($topUserOfYearData)
                                    <div class="badge bg-success rounded-pill ms-auto">
                                        <i class="fas fa-car me-1"></i>
                                        {{ DB::table('location_requests')->where('user_id', $topUserOfYearData->id)->where('statut', 'terminee')->whereYear('created_at', date('Y'))->count() }} locations
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="d-flex align-items-center mt-4">
                                    <div class="avatar-container">
                                        <div class="avatar-circle bg-primary text-white">
                                            @if($topUserOfYearData)
                                                {{ strtoupper(substr($topUserOfYearData->email, 0, 1)) }}
                                            @else
                                                ?
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3 user-details">
                                        @if($topUserOfYearData)
                                            <h5 class="fw-bold mb-1">{{ $topUserOfYearData->name ?? '' }} {{ $topUserOfYearData->prenom ?? '' }}</h5>
                                            <p class="text-primary mb-0">{{ $topUserOfYearData->email }}</p>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-phone me-1"></i> {{ $topUserOfYearData->telephone ?? 'Non disponible' }}
                                            </p>
                                        @else
                                            <h5 class="fw-bold mb-1">Aucun utilisateur</h5>
                                            <p class="text-muted mb-0">Pas de données disponibles</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Utilisateur du mois -->
                        <div class="col-md-6">
                            <div class="p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stats-badge rounded-pill bg-info text-white px-3 py-1 me-3">
                                        <i class="fas fa-calendar-day"></i> Meilleur du mois
                                    </div>
                                    @if($topUserOfMonthData)
                                    <div class="badge bg-success rounded-pill ms-auto">
                                        <i class="fas fa-car me-1"></i>
                                        {{ DB::table('location_requests')->where('user_id', $topUserOfMonthData->id)->where('statut', 'terminee')->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->count() }} locations
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="d-flex align-items-center mt-4">
                                    <div class="avatar-container">
                                        <div class="avatar-circle bg-info text-white">
                                            @if($topUserOfMonthData)
                                                {{ strtoupper(substr($topUserOfMonthData->email, 0, 1)) }}
                                            @else
                                                ?
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ms-3 user-details">
                                        @if($topUserOfMonthData)
                                            <h5 class="fw-bold mb-1">{{ $topUserOfMonthData->name ?? '' }} {{ $topUserOfMonthData->prenom ?? '' }}</h5>
                                            <p class="text-info mb-0">{{ $topUserOfMonthData->email }}</p>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-phone me-1"></i> {{ $topUserOfMonthData->telephone ?? 'Non disponible' }}
                                            </p>
                                        @else
                                            <h5 class="fw-bold mb-1">Aucun utilisateur</h5>
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
    
    <div class="row mb-3 mt-4">
        <div class="col-12 text-end">
            <button class="btn btn-primary py-2 px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus-circle me-2"></i> Ajouter un nouveau client
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Rechercher un utilisateur</h5>
                </div>
                <div class="card-body p-4">
                    <div class="search-container">
                        <form action="{{ route('simple.user.search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="email" placeholder="Entrez l'email" class="form-control" value="{{ request('email') }}">
                                <input type="hidden" name="status" value="user">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                            </div>
                           
                            <div class="mt-2 text-end">
                                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Réinitialiser la recherche
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Période d'analyse</h5>
                </div>
                <div class="card-body p-4">
                    <div class="period-buttons">
                        <button class="btn btn-primary active me-2">Annuel</button>
                        <button class="btn btn-outline-primary me-2">Mensuel</button>
                        <button class="btn btn-outline-primary">Hebdomadaire</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="text-center mb-0 fw-bold text-dark">Activité des utilisateurs</h5>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3 mb-3">
        <label class="form-label fw-bold">Filtrer par état des pièces d'identité :</label>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.users', ['filter' => 'verified']) }}" class="btn btn-sm {{ request('filter') == 'verified' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="fas fa-check-circle me-1"></i> Pièces vérifiées
                @php
                    $verifiedUsersCount = \App\Models\User::where('status', 'user')
                        ->whereNotNull('image_piece_recto')
                        ->whereNotNull('image_piece_verso')
                        ->where('piece_verifie', true)
                        ->count();
                @endphp
                <span class="badge rounded-pill bg-light text-success ms-1">{{ $verifiedUsersCount }}</span>
            </a>
            <a href="{{ route('admin.users', ['filter' => 'unverified']) }}" class="btn btn-sm {{ request('filter') == 'unverified' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="fas fa-exclamation-circle me-1"></i> Pièces non vérifiées
                @php
                    $unverifiedUsersCount = \App\Models\User::where('status', 'user')
                        ->whereNotNull('image_piece_recto')
                        ->whereNotNull('image_piece_verso')
                        ->where('piece_verifie', false)
                        ->count();
                @endphp
                <span class="badge rounded-pill bg-light text-warning ms-1">{{ $unverifiedUsersCount }}</span>
            </a>
            <a href="{{ route('admin.users', ['filter' => 'missing']) }}" class="btn btn-sm {{ request('filter') == 'missing' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="fas fa-times-circle me-1"></i> Pièces non fournies
                @php
                    $missingDocsUsersCount = \App\Models\User::where('status', 'user')
                        ->where(function($q) {
                            $q->whereNull('image_piece_recto')
                              ->orWhereNull('image_piece_verso');
                        })
                        ->count();
                @endphp
                <span class="badge rounded-pill bg-light text-danger ms-1">{{ $missingDocsUsersCount }}</span>
            </a>
            <a href="{{ route('admin.users') }}" class="btn btn-sm {{ !request('filter') ? 'btn-dark' : 'btn-outline-dark' }}">
                <i class="fas fa-users me-1"></i> Tous les clients
                @php
                    $totalUsersCount = \App\Models\User::where('status', 'user')->count();
                @endphp
                <span class="badge rounded-pill bg-light text-dark ms-1">{{ $totalUsersCount }}</span>
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card user-info-card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Liste des clients</h5>
                </div>
                <div class="card-body p-3 position-relative">
                    @php
                        // Séparer les utilisateurs avec notifications et sans notifications
                        $usersWithNotifications = $users->filter(function($user) {
                            return $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie;
                        });
                        
                        $usersWithoutNotifications = $users->filter(function($user) {
                            return !($user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie);
                        });
                        
                        // Combiner les deux collections, avec les utilisateurs avec notifications en premier
                        $sortedUsers = $usersWithNotifications->merge($usersWithoutNotifications);
                    @endphp
                    
                    @forelse($sortedUsers as $user)
                    <div class="new-user-registration {{ ($user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie) ? 'bg-light-warning' : 'bg-light' }} p-2 rounded shadow-sm mb-3 position-relative">
                        @if($user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie)
                            <span class="position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-danger mt-2 me-2" 
                                  data-bs-toggle="tooltip" data-bs-placement="left" 
                                  title="Document à vérifier">
                                1
                                <span class="visually-hidden">Document non vérifié</span>
                            </span>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="date-time">
                                <div class="date-badge bg-white py-1 px-2 d-inline-block rounded shadow-sm small">
                                    <i class="far fa-calendar-alt me-1 text-primary"></i>
                                    <span class="fw-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="time-badge bg-white py-1 px-2 d-inline-block ms-1 rounded shadow-sm small">
                                    <i class="far fa-clock me-1 text-primary"></i>
                                    <span class="fw-bold">{{ $user->created_at->format('H\hi') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="user-details-container d-flex flex-wrap gap-3">
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="detail-label small text-muted">Nom</div>
                                <div class="detail-value fw-bold small">{{ $user->name ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div class="detail-label small text-muted">Prénom</div>
                                <div class="detail-value fw-bold small">{{ $user->prenom ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-label small text-muted">Email</div>
                                <div class="detail-value fw-bold small">{{ $user->email ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 text-primary">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="detail-label small text-muted">Téléphone</div>
                                <div class="detail-value fw-bold small">{{ $user->telephone ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-card shadow-sm bg-white p-2 rounded">
                                <div class="detail-icon mb-1 {{ (!$user->image_piece_recto || !$user->image_piece_verso) ? 'text-danger' : (!$user->piece_verifie ? 'text-warning' : 'text-success') }}">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="detail-label small text-muted">Documents</div>
                                <div class="detail-value fw-bold small">
                                    @if(!$user->image_piece_recto || !$user->image_piece_verso)
                                        <span class="text-danger">Non fournis</span>
                                    @elseif(!$user->piece_verifie)
                                        <span class="text-warning">Non vérifiés</span>
                                    @else
                                        <span class="text-success">Vérifiés</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="action-buttons mt-3 d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                <i class="fas fa-info-circle me-1"></i> Détails
                            </button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                <i class="fas fa-trash-alt me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> Aucun client enregistré
                    </div>
                    @endforelse
                </div>
                <div class="card-footer text-center p-3">
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les détails utilisateurs -->
@foreach($users as $user)
<div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="userModalLabel{{ $user->id }}">
                    <i class="fas fa-user-circle me-2"></i>Détails du client
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="row mb-4">
                    <div class="col-md-12 text-center mb-4">
                        <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($user->email, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->name ?? 'N/A' }} {{ $user->prenom ?? '' }}</h4>
                        <p class="text-primary mb-0">
                            <i class="fas fa-envelope me-1"></i> {{ $user->email }}
                        </p>
                        <div class="badge bg-success mt-2">
                            <i class="fas fa-calendar-alt me-1"></i> Inscrit le {{ $user->created_at->format('d/m/Y à H\hi') }}
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
                                    <div class="value">{{ $user->name ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Prénom</div>
                                    <div class="value">{{ $user->prenom ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Téléphone</div>
                                    <div class="value">{{ $user->telephone ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Date de naissance</div>
                                    <div class="value">{{ $user->date_naissance ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Commune</div>
                                    <div class="value">{{ $user->adresse ?? 'Non renseignée' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Ville</div>
                                    <div class="value">{{ $user->ville ?? 'Non renseignée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Informations du compte</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="label">Email</div>
                                    <div class="value">{{ $user->email }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Statut</div>
                                    <div class="value">
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Vérifié</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Créé le</div>
                                    <div class="value">{{ $user->created_at->format('d/m/Y') }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Dernière mise à jour</div>
                                    <div class="value">{{ $user->updated_at->format('d/m/Y') }}</div>
                                </div>
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
                                        @if(!$user->image_piece_recto || !$user->image_piece_verso)
                                            <span class="badge bg-danger">Non fournies</span>
                                        @elseif(!$user->piece_verifie)
                                            <span class="badge bg-warning">Non vérifiées</span>
                                        @else
                                            <span class="badge bg-success">Vérifiées</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($user->image_piece_recto || $user->image_piece_verso)
                                    <div class="documents-preview mt-3">
                                        <div class="row">
                                            @if($user->image_piece_recto)
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small text-muted">Recto</label>
                                                <div class="document-thumbnail">
                                                    <a href="{{ asset('storage/' . $user->image_piece_recto) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $user->image_piece_recto) }}" class="img-thumbnail" alt="Recto de la pièce">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($user->image_piece_verso)
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label small text-muted">Verso</label>
                                                <div class="document-thumbnail">
                                                    <a href="{{ asset('storage/' . $user->image_piece_verso) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $user->image_piece_verso) }}" class="img-thumbnail" alt="Verso de la pièce">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie)
                                    <div class="mt-3 text-center">
                                        <form action="{{ route('admin.users.verify_pieces', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check-circle me-1"></i> Vérifier les pièces
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.refuse_pieces', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times-circle me-1"></i> Refuser les pièces
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
                        
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-car me-2 text-primary"></i>Activité de location</h6>
                            </div>
                            <div class="card-body">
                                <div class="info-row">
                                    <div class="label">Locations</div>
                                    <div class="value">
                                        {{ DB::table('location_requests')->where('user_id', $user->id)->count() }} location(s)
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Ce mois</div>
                                    <div class="value">
                                        {{ DB::table('location_requests')->where('user_id', $user->id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count() }} location(s)
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Dernière location</div>
                                    <div class="value">
                                        @php
                                            $lastRequest = DB::table('location_requests')->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                                        @endphp
                                        @if($lastRequest)
                                            {{ \Carbon\Carbon::parse($lastRequest->created_at)->format('d/m/Y') }}
                                        @else
                                            Aucune location
                                        @endif
                                    </div>
                                </div>
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

<!-- Modals pour la suppression des utilisateurs -->
@foreach($users as $user)
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $user->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <div class="avatar-circle bg-danger text-white mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Êtes-vous sûr de vouloir supprimer ce client?</h4>
                    <p class="text-muted">
                        Vous êtes sur le point de supprimer le compte de <strong>{{ $user->name }} {{ $user->prenom }}</strong>.<br>
                        Cette action est irréversible et toutes les données associées seront perdues.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display: inline;" class="form-loading">
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

<!-- Modal pour l'ajout d'un nouvel utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Ajouter un nouveau client
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <form action="{{ route('admin.users.store') }}" method="POST" id="addUserForm" class="form-loading" enctype="multipart/form-data">
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
                                <label for="date_naissance" class="form-label fw-bold">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="telephone" class="form-label fw-bold">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label fw-bold">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="adresse" class="form-label fw-bold">Commune</label>
                                <input type="text" class="form-control" id="adresse" name="adresse">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="ville" class="form-label fw-bold">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville">
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3 border-bottom pb-2"><i class="fas fa-id-card me-2"></i>Pièces d'identité</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image_piece_recto" class="form-label fw-bold">Image recto</label>
                                <input type="file" class="form-control" id="image_piece_recto" name="image_piece_recto" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image_piece_verso" class="form-label fw-bold">Image verso</label>
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

                    <input type="hidden" name="status" value="user">

                    <div class="form-text mb-3">
                        <span class="text-danger">*</span> Champs obligatoires
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="addUserForm" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
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
    }
    
    .bg-light-warning {
        background-color: #fff8e1 !important;
        border-left: 4px solid #ffc107;
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
    
    /**
     * Chase - Loader Animation
     */
    .chase {
        position: relative; }
        .chase:after, .chase:before {
        content: '';
        height: 30px;
        width: 30px;
        display: block;
        -webkit-animation: out .5s backwards, spin 1.25s .5s infinite ease;
                animation: out .5s backwards, spin 1.25s .5s infinite ease;
        border: 5px solid #00bcd4;
        border-radius: 100%;
        -webkit-box-shadow: 0 -40px 0 -5px #00bcd4;
                box-shadow: 0 -40px 0 -5px #00bcd4;
        position: absolute; }
        .chase:after {
        -webkit-animation-delay: 0s, 1.25s;
                animation-delay: 0s, 1.25s; }

    @-webkit-keyframes out {
        from {
        -webkit-box-shadow: 0 0 0 -5px #00bcd4;
                box-shadow: 0 0 0 -5px #00bcd4; } }

    @keyframes out {
        from {
        -webkit-box-shadow: 0 0 0 -5px #00bcd4;
                box-shadow: 0 0 0 -5px #00bcd4; } }

    @-webkit-keyframes spin {
        to {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg); } }

    @keyframes spin {
        to {
        -webkit-transform: rotate(360deg);
                transform: rotate(360deg); } }
    
    .form-loading {
        position: relative;
    }
    
    .form-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.8);
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        border-radius: 0.375rem;
        display: none;
    }
    
    .btn-loader {
        position: relative;
        display: inline-block;
        width: 25px;
        height: 25px;
        margin-right: 8px;
        display: none;
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('usersChart').getContext('2d');
        let myChart;
        
        // Données initiales pour le graphique
        const yearsRange = generateYearsRange();
        const monthLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        const currentWeeksLabels = generateWeeksLabels();
        
        // Chargement initial (Annuel par défaut)
        loadChartData('annuel');
        
        // Gestion des boutons de période
        const periodBtns = document.querySelectorAll('.period-buttons .btn');
        periodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                periodBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
                periodBtns.forEach(b => b.classList.add('btn-outline-primary'));
                this.classList.remove('btn-outline-primary');
                this.classList.add('active', 'btn-primary');
                
                // Logique pour mettre à jour le graphique en fonction de la période
                const period = this.textContent.trim().toLowerCase();
                loadChartData(period);
            });
        });

        function loadChartData(period) {
            // Route pour récupérer les données appropriées selon la période
            fetch(`/admin/users-stats?period=${period}&status=user`)
                .then(response => response.json())
                .then(data => {
                    updateChart(period, data);
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données:', error);
                    // En cas d'erreur, on utilise des données fictives pour la démo
                    let mockData;
                    
                    if (period === 'annuel') {
                        mockData = {
                            newUsers: generateRandomData(yearsRange.length, 10, 50),
                            activeUsers: generateRandomData(yearsRange.length, 50, 200)
                        };
                    } else if (period === 'mensuel') {
                        mockData = {
                            newUsers: generateRandomData(12, 5, 20),
                            activeUsers: generateRandomData(12, 20, 60)
                        };
                    } else if (period === 'hebdomadaire') {
                        mockData = {
                            newUsers: generateRandomData(currentWeeksLabels.length, 1, 8),
                            activeUsers: generateRandomData(currentWeeksLabels.length, 10, 30)
                        };
                    }
                    
                    updateChart(period, mockData);
                });
        }

        function updateChart(period, data) {
            // Destruction du graphique existant s'il existe
            if (myChart) {
                myChart.destroy();
            }
            
            let labels, xAxisTitle;
            
            if (period === 'annuel') {
                labels = yearsRange;
                xAxisTitle = 'Années';
            } else if (period === 'mensuel') {
                labels = monthLabels;
                xAxisTitle = 'Mois de l\'année';
            } else if (period === 'hebdomadaire') {
                labels = currentWeeksLabels;
                xAxisTitle = 'Semaines du mois en cours';
            }
            
            // Création du nouveau graphique
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nouveaux utilisateurs',
                        data: data.newUsers,
                        backgroundColor: 'rgba(0, 188, 212, 0.2)',
                        borderColor: 'rgba(0, 188, 212, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(0, 188, 212, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        fill: true
                    },
                    {
                        label: 'Utilisateurs actifs',
                        data: data.activeUsers,
                        backgroundColor: 'rgba(76, 175, 80, 0.2)',
                        borderColor: 'rgba(76, 175, 80, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(76, 175, 80, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItem) {
                                    return (period === 'annuel' ? 'Année: ' : 
                                           period === 'mensuel' ? 'Mois: ' : 
                                           'Semaine: ') + tooltipItem[0].label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            title: {
                                display: true,
                                text: 'Nombre d\'utilisateurs'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            title: {
                                display: true,
                                text: xAxisTitle
                            }
                        }
                    }
                }
            });
        }
        
        // Fonction utilitaire pour générer une plage d'années de 2025 à 2037
        function generateYearsRange() {
            const years = [];
            for (let i = 2025; i <= 2037; i++) {
                years.push(i.toString());
            }
            return years;
        }
        
        // Fonction utilitaire pour générer les labels des semaines du mois en cours
        function generateWeeksLabels() {
            const now = new Date();
            const currentMonth = now.getMonth();
            const currentYear = now.getFullYear();
            
            // Déterminer le nombre de semaines dans le mois actuel
            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            
            // Calcul approximatif du nombre de semaines
            const numWeeks = Math.ceil((lastDay.getDate() + firstDay.getDay()) / 7);
            
            const weekLabels = [];
            for (let i = 1; i <= numWeeks; i++) {
                weekLabels.push('Sem ' + i);
            }
            
            return weekLabels;
        }
        
        // Fonction utilitaire pour générer des données aléatoires pour la démo
        function generateRandomData(length, min, max) {
            return Array.from({length}, () => 
                Math.floor(Math.random() * (max - min + 1) + min)
            );
        }
        
        // Gestion du loader pour le formulaire d'ajout d'utilisateur
        const addUserForm = document.getElementById('addUserForm');
        if (addUserForm) {
            addUserForm.addEventListener('submit', function(e) {
                const modalBody = this.closest('.modal-body');
                const submitBtn = document.querySelector('button[form="addUserForm"]');
                const submitBtnText = submitBtn.innerHTML;
                
                // Créer et ajouter le loader
                const loader = document.createElement('div');
                loader.className = 'form-loader';
                loader.innerHTML = '<div class="chase"></div>';
                modalBody.appendChild(loader);
                
                // Afficher le loader
                loader.style.display = 'flex';
                
                // Changer le texte du bouton
                submitBtn.innerHTML = '<div class="btn-loader chase"></div> Traitement en cours...';
                submitBtn.disabled = true;
            });
        }
        
        // Gestion du loader pour les formulaires de suppression
        const deleteForms = document.querySelectorAll('form[action*="users.delete"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const submitBtnText = submitBtn.innerHTML;
                
                // Changer le texte du bouton
                submitBtn.innerHTML = '<div class="btn-loader chase" style="display: inline-block;"></div> Suppression...';
                submitBtn.disabled = true;
            });
        });
    });
</script>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser tous les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Code JavaScript existant...
    });
</script>
@endsection
