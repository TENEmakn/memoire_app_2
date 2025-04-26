@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Gestionnaires</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">TOTAL GESTIONNAIRES</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $gestionnaires->count() }}</h3>
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
                    <h3 class="fw-bold text-success">{{ $gestionnaires->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-info text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">GESTIONNAIRES ACTIFS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="user-icon mb-2">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ $gestionnaires->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

   
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary w-100 py-3" style="border-radius: 25px;" data-bs-toggle="modal" data-bs-target="#ajouterGestionnaireModal">
                <i class="fas fa-plus-circle me-2"></i> Ajouter gestionnaire
            </button>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Rechercher un gestionnaire</h5>
                </div>
                <div class="card-body p-4">
                    <div class="search-container">
                        <form action="{{ route('admin.gestionnaire.search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" placeholder="Entrez l'email, le nom ou le prénom" class="form-control" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                                <a href="{{ route('admin.gestionnaire') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-sync-alt me-1"></i> Réinitialiser
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card user-info-card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Liste des gestionnaires</h5>
                </div>
                <div class="card-body p-3 position-relative">
                    @if($gestionnaires->count() > 0)
                        @foreach($gestionnaires as $gestionnaire)
                        <div class="new-user-registration bg-light p-2 rounded shadow-sm mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="date-time">
                                    <div class="date-badge bg-white py-1 px-2 d-inline-block rounded shadow-sm small">
                                        <i class="far fa-calendar-alt me-1 text-primary"></i>
                                        <span class="fw-bold">{{ $gestionnaire->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="time-badge bg-white py-1 px-2 d-inline-block ms-1 rounded shadow-sm small">
                                        <i class="far fa-clock me-1 text-primary"></i>
                                        <span class="fw-bold">{{ $gestionnaire->created_at->format('H\hi') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="user-details-container d-flex flex-wrap gap-3">
                                <div class="detail-card shadow-sm bg-white p-2 rounded">
                                    <div class="detail-icon mb-1 text-primary">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="detail-label small text-muted">Nom</div>
                                    <div class="detail-value fw-bold small">{{ $gestionnaire->name }}</div>
                                </div>
                                <div class="detail-card shadow-sm bg-white p-2 rounded">
                                    <div class="detail-icon mb-1 text-primary">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div class="detail-label small text-muted">Prénom</div>
                                    <div class="detail-value fw-bold small">{{ $gestionnaire->prenom }}</div>
                                </div>
                                <div class="detail-card shadow-sm bg-white p-2 rounded">
                                    <div class="detail-icon mb-1 text-primary">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="detail-label small text-muted">Email</div>
                                    <div class="detail-value fw-bold small">{{ $gestionnaire->email }}</div>
                                </div>
                                <div class="detail-card shadow-sm bg-white p-2 rounded">
                                    <div class="detail-icon mb-1 text-primary">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="detail-label small text-muted">Téléphone</div>
                                    <div class="detail-value fw-bold small">{{ $gestionnaire->telephone }}</div>
                                </div>
                            </div>
                            
                            <div class="action-buttons mt-3 d-flex justify-content-end">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gestionnaireModal{{ $gestionnaire->id }}">
                                    <i class="fas fa-eye me-1"></i> Details
                                </button>
                                @if(Auth::user()->status === 'admin')
                                <button class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#deleteGestionnaireModal{{ $gestionnaire->id }}">
                                    <i class="fas fa-trash-alt me-1"></i> Supprimer
                                </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            Aucun gestionnaire trouvé dans la base de données.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les détails des gestionnaires -->
@foreach($gestionnaires as $gestionnaire)
<div class="modal fade" id="gestionnaireModal{{ $gestionnaire->id }}" tabindex="-1" aria-labelledby="gestionnaireModalLabel{{ $gestionnaire->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="gestionnaireModalLabel{{ $gestionnaire->id }}">
                    <i class="fas fa-user-circle me-2"></i>Détails du gestionnaire
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="row mb-4">
                    <div class="col-md-12 text-center mb-4">
                        <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr($gestionnaire->email, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $gestionnaire->name ?? 'N/A' }} {{ $gestionnaire->prenom ?? '' }}</h4>
                        <p class="text-primary mb-0">
                            <i class="fas fa-envelope me-1"></i> {{ $gestionnaire->email }}
                        </p>
                        <div class="badge bg-success mt-2">
                            <i class="fas fa-calendar-alt me-1"></i> Inscrit le {{ $gestionnaire->created_at->format('d/m/Y à H\hi') }}
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
                                    <div class="value">{{ $gestionnaire->name ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Prénom</div>
                                    <div class="value">{{ $gestionnaire->prenom ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Téléphone</div>
                                    <div class="value">{{ $gestionnaire->telephone ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Date de naissance</div>
                                    <div class="value">{{ $gestionnaire->date_naissance ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Commune</div>
                                    <div class="value">{{ $gestionnaire->commune ?? 'Non renseignée' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Ville</div>
                                    <div class="value">{{ $gestionnaire->ville ?? 'Non renseignée' }}</div>
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
                                    <div class="value">{{ $gestionnaire->email }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Statut</div>
                                    <div class="value">
                                        @if($gestionnaire->email_verified_at)
                                            <span class="badge bg-success">Vérifié</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Créé le</div>
                                    <div class="value">{{ $gestionnaire->created_at->format('d/m/Y') }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="label">Dernière mise à jour</div>
                                    <div class="value">{{ $gestionnaire->updated_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm mb-3 rounded">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-id-badge me-2 text-primary"></i>Pièce d'identité</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted mb-2">Recto</h6>
                                        @if($gestionnaire->image_piece_recto)
                                            <div class="id-card-container">
                                                <img src="{{ asset('storage/'.$gestionnaire->image_piece_recto) }}" class="img-fluid rounded id-card-image" alt="Pièce d'identité (recto)">
                                                <a href="{{ asset('storage/'.$gestionnaire->image_piece_recto) }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                                    <i class="fas fa-search-plus me-1"></i> Voir en plein écran
                                                </a>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i> Aucune image recto disponible
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted mb-2">Verso</h6>
                                        @if($gestionnaire->image_piece_verso)
                                            <div class="id-card-container">
                                                <img src="{{ asset('storage/'.$gestionnaire->image_piece_verso) }}" class="img-fluid rounded id-card-image" alt="Pièce d'identité (verso)">
                                                <a href="{{ asset('storage/'.$gestionnaire->image_piece_verso) }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                                    <i class="fas fa-search-plus me-1"></i> Voir en plein écran
                                                </a>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i> Aucune image verso disponible
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">Statut de vérification:</span>
                                        @if($gestionnaire->piece_verifie)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Vérifié</span>
                                        @else
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i> En attente de vérification</span>
                                        @endif
                                    </div>
                                    
                                    <form action="{{ route('admin.gestionnaire.update-verification', $gestionnaire->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="piece_verifie_{{ $gestionnaire->id }}" 
                                                  name="piece_verifie" value="1" {{ $gestionnaire->piece_verifie ? 'checked' : '' }}>
                                            <label class="form-check-label" for="piece_verifie_{{ $gestionnaire->id }}">
                                                Marquer comme vérifié
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-save me-1"></i> Enregistrer le statut
                                        </button>
                                    </form>
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

<!-- Modals pour la suppression des gestionnaires -->
@foreach($gestionnaires as $gestionnaire)
<div class="modal fade" id="deleteGestionnaireModal{{ $gestionnaire->id }}" tabindex="-1" aria-labelledby="deleteGestionnaireModalLabel{{ $gestionnaire->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteGestionnaireModalLabel{{ $gestionnaire->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <div class="avatar-circle bg-danger text-white mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Êtes-vous sûr de vouloir supprimer ce gestionnaire?</h4>
                    <p class="text-muted">
                        Vous êtes sur le point de supprimer le compte de <strong>{{ $gestionnaire->name }} {{ $gestionnaire->prenom }}</strong>.<br>
                        Cette action est irréversible et toutes les données associées seront perdues.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.gestionnaire.delete', $gestionnaire->id) }}" method="POST" style="display: inline;" class="form-loading">
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

<!-- Modal Ajouter Gestionnaire -->
<div class="modal fade" id="ajouterGestionnaireModal" tabindex="-1" aria-labelledby="ajouterGestionnaireModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ajouterGestionnaireModalLabel">Ajouter un nouveau gestionnaire</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.gestionnaire.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-bold">Nom</label>
                            <input type="text" class="form-control" id="nom" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label fw-bold">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label fw-bold">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label fw-bold">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="commune" class="form-label fw-bold">Commune</label>
                            <input type="text" class="form-control" id="commune" name="commune" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label fw-bold">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image_piece_recto" class="form-label fw-bold">Pièce d'identité (Recto)</label>
                            <input type="file" class="form-control" id="image_piece_recto" name="image_piece_recto" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image_piece_verso" class="form-label fw-bold">Pièce d'identité (Verso)</label>
                            <input type="file" class="form-control" id="image_piece_verso" name="image_piece_verso" accept="image/jpeg,image/png,image/jpg">
                            <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="piece_verifie" name="piece_verifie" value="1" checked>
                        <label class="form-check-label" for="piece_verifie">
                            Marquer les pièces comme vérifiées
                        </label>
                    </div>
                    
                    <input type="hidden" name="status" value="gestionnaire">
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="fas fa-save me-2"></i> Enregistrer
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
    
    .id-card-container {
        position: relative;
        border: 1px solid #f0f0f0;
        padding: 5px;
        border-radius: 8px;
        background-color: #f8f9fa;
        text-align: center;
    }
    
    .id-card-image {
        max-height: 200px;
        object-fit: contain;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>

@endsection
