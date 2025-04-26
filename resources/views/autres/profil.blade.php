@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="rounded-circle mx-auto bg-primary d-flex justify-content-center align-items-center" style="width: 120px; height: 120px; font-size: 3rem; color: white;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ Auth::user()->name }} {{ Auth::user()->prenom }}</h4>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-phone me-2"></i>{{ Auth::user()->telephone ?? 'Non renseigné' }}
                    </p>
                    <div class="mt-3">
                        <span class="badge bg-{{ Auth::user()->status == 'admin' ? 'danger' : 'primary' }} rounded-pill px-3 py-2">
                            {{ Auth::user()->status == 'admin' ? 'Administrateur' : 'Utilisateur' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mt-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-link me-2"></i>Liens rapides</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('mes_rdv') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-alt me-2"></i>Mes rendez-vous
                        </a>
                        <a href="{{ route('mes_reservations') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-car me-2"></i>Mes réservations
                        </a>

                        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i>Changer mon mot de passe
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Informations personnelles</h4>
                </div>
                <div class="card-body">
                    @if(session('profile_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('profile_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom', Auth::user()->prenom) }}">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="number" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', Auth::user()->telephone) }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', Auth::user()->date_naissance) }}">
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('ville') is-invalid @enderror" id="ville" name="ville" value="{{ old('ville', Auth::user()->ville) }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="commune" class="form-label">Commune</label>
                                <input type="text" class="form-control @error('commune') is-invalid @enderror" id="commune" name="commune" value="{{ old('commune', Auth::user()->commune) }}">
                                @error('commune')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow mt-4">
                <div class="card-header bg-white position-relative">
                    <h4 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>Documents 
                        @if(!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso)
                            <span class="text-danger ms-2 small">(Pièce d'identité manquante)</span>
                        @endif
                        @if(!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                1
                                <span class="visually-hidden">Documents manquants</span>
                            </span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Pièce d'identité</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if(Auth::user()->image_piece_recto && Auth::user()->image_piece_verso)
                                                <span class="text-success"><i class="fas fa-check-circle me-2"></i>Téléchargé</span>
                                            @else
                                                <span class="text-danger"><i class="fas fa-times-circle me-2"></i>Non téléchargé</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('documents.edit', ['type' => 'piece']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i>Gérer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

<!-- Modal Changement de mot de passe -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel"><i class="fas fa-key me-2"></i>Changement de mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('password_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('password_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('password_error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('password_error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required value="{{ old('current_password') }}">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si le modal doit être ouvert (en cas d'erreur ou après succès)
        @if(session('open_password_modal'))
            var passwordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            passwordModal.show();
        @endif
    });
</script>
@endpush 