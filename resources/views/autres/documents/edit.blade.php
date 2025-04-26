@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white position-relative">
                    <h4 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>{{ $title }}
                        @if($type === 'piece' && (!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso))
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                1
                                <span class="visually-hidden">Documents manquants</span>
                            </span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    @if($type === 'piece' && $user->piece_verifie)
                    <div class="alert alert-success mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt me-3 fs-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Documents vérifiés !</h5>
                                <p class="mb-0">Vos pièces d'identité ont été vérifiées par notre équipe. Pour des raisons de sécurité, vous ne pouvez plus les modifier. Pour toute question, veuillez contacter notre service client.</p>
                            </div>
                        </div>
                    </div>
                    @elseif($type === 'piece' && $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie)
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-hourglass-half me-3 fs-3"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Documents en attente de vérification</h5>
                                <p class="mb-0">Vos pièces d'identité ont été soumises et sont en cours de vérification par notre équipe. Vous ne pouvez pas les modifier pendant cette période. Merci de votre patience.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <form action="{{ route('documents.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Recto</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($type === 'piece' && $user->image_piece_recto)
                                            <div class="text-center mb-3">
                                                <img src="{{ asset('storage/' . $user->image_piece_recto) }}" alt="Pièce d'identité recto" class="img-fluid mb-2" style="max-height: 200px;">
                                                <div class="text-success"><i class="fas fa-check-circle me-1"></i>Image actuelle</div>
                                            </div>
                                        @elseif($type === 'permis' && $user->image_permis_recto)
                                            <div class="text-center mb-3">
                                                <img src="{{ asset('storage/' . $user->image_permis_recto) }}" alt="Permis recto" class="img-fluid mb-2" style="max-height: 200px;">
                                                <div class="text-success"><i class="fas fa-check-circle me-1"></i>Image actuelle</div>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <label for="document_recto" class="form-label">Télécharger l'image recto</label>
                                            <input type="file" class="form-control @error('document_recto') is-invalid @enderror" id="document_recto" name="document_recto" 
                                            {{ ($type === 'piece' && $user->piece_verifie) || ($type === 'piece' && $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie) ? 'disabled' : '' }}>
                                            @error('document_recto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                                            @if($type === 'piece' && $user->piece_verifie)
                                                <div class="text-success mt-2">
                                                    <i class="fas fa-lock me-1"></i> Vos documents ont été vérifiés et ne peuvent plus être modifiés.
                                                </div>
                                            @elseif($type === 'piece' && $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie)
                                                <div class="text-warning mt-2">
                                                    <i class="fas fa-clock me-1"></i> Documents en attente de vérification par CGV Motors.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Verso</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($type === 'piece' && $user->image_piece_verso)
                                            <div class="text-center mb-3">
                                                <img src="{{ asset('storage/' . $user->image_piece_verso) }}" alt="Pièce d'identité verso" class="img-fluid mb-2" style="max-height: 200px;">
                                                <div class="text-success"><i class="fas fa-check-circle me-1"></i>Image actuelle</div>
                                            </div>
                                        @elseif($type === 'permis' && $user->image_permis_verso)
                                            <div class="text-center mb-3">
                                                <img src="{{ asset('storage/' . $user->image_permis_verso) }}" alt="Permis verso" class="img-fluid mb-2" style="max-height: 200px;">
                                                <div class="text-success"><i class="fas fa-check-circle me-1"></i>Image actuelle</div>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <label for="document_verso" class="form-label">Télécharger l'image verso</label>
                                            <input type="file" class="form-control @error('document_verso') is-invalid @enderror" id="document_verso" name="document_verso" 
                                            {{ ($type === 'piece' && $user->piece_verifie) || ($type === 'piece' && $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie) ? 'disabled' : '' }}>
                                            @error('document_verso')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Format: JPEG, PNG, JPG. Taille max: 2 Mo.</div>
                                            @if($type === 'piece' && $user->piece_verifie)
                                                <div class="text-success mt-2">
                                                    <i class="fas fa-lock me-1"></i> Vos documents ont été vérifiés et ne peuvent plus être modifiés.
                                                </div>
                                            @elseif($type === 'piece' && $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie)
                                                <div class="text-warning mt-2">
                                                    <i class="fas fa-clock me-1"></i> Documents en attente de vérification par CGV Motors.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Les documents téléchargés sont utilisés uniquement pour vérifier votre identité et ne seront pas partagés avec des tiers.
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('auth.profil') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour au profil
                            </a>
                            <button type="submit" class="btn btn-primary" 
                            {{ ($type === 'piece' && $user->piece_verifie) || ($type === 'piece' && $user->image_piece_recto && $user->image_piece_verso && !$user->piece_verifie) ? 'disabled' : '' }}>
                                <i class="fas fa-save me-1"></i>Enregistrer les documents
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 