@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Véhicules</h1>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">TOTAL VÉHICULES</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-car fa-2x text-primary"></i>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $totalVehicules - $vehiculesVendu }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-info text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES EN LOCATION</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-key fa-2x text-info"></i>
                    </div>
                    <h3 class="fw-bold text-info">{{ $vehiculesLocation }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-danger text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES EN VENTE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-tag fa-2x text-danger"></i>
                    </div>
                    <h3 class="fw-bold text-danger">{{ $vehiculesVente }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-warning text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">EN MISSION</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-route fa-2x text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-warning">{{ $vehiculesMission }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES ACTIFS POUR LA LOCATION</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ $vehiculesActifs }}</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-secondary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES NON ACTIFS POUR LA LOCATION</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-times-circle fa-2x text-secondary"></i>
                    </div>
                    <h3 class="fw-bold text-secondary">{{ $vehiculesNonActifs }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES ACTIFS POUR LA VENTE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ $vehiculesActifsvente }}</h3>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-secondary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES NON ACTIFS POUR LA VENTE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-times-circle fa-2x text-secondary"></i>
                    </div>
                    <h3 class="fw-bold text-secondary">{{ $vehiculesNonActifsvente }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">VÉHICULES VENDUS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="vehicle-icon mb-2">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success">{{ $vehiculesVendu }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par type et marque -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light text-dark py-3">
                    <h5 class="mb-0 fw-bold">Véhicules par type</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th class="text-center">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehiculesByType as $type => $count)
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td class="text-center">{{ $count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light text-dark py-3">
                    <h5 class="mb-0 fw-bold">Véhicules par marque</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Marque</th>
                                    <th class="text-center">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehiculesByMarque as $marque => $count)
                                <tr>
                                    <td>{{ $marque }}</td>
                                    <td class="text-center">{{ $count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recherche et gestion des véhicules -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-light py-3">
            <h5 class="mb-0 fw-bold">Gestion des véhicules</h5>
            <a href="{{ route('admin.vehicules.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Ajouter un véhicule
            </a>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('admin.vehicules') }}" method="GET" class="mb-4 row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($vehiculeTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="marque" class="form-select">
                        <option value="">Toutes les marques</option>
                        @foreach($vehiculeMarques as $marque)
                            <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>{{ $marque }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('admin.vehicules') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
            
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <h6 class="fw-bold mt-2 me-2">Filtrer par statut :</h6>
                    <a href="{{ route('admin.vehicules', ['statut' => 'location']) }}" class="btn btn-outline-success">
                        <i class="fas fa-key me-1"></i>En location
                        <span class="badge bg-success ms-1">{{ $vehiculesActifsBadge }}</span>
                    </a>
                    <a href="{{ route('admin.vehicules', ['statut' => 'vente']) }}" class="btn btn-outline-danger">
                        <i class="fas fa-tag me-1"></i>En vente
                        <span class="badge bg-danger ms-1">{{ $vehiculesActifsvente}}</span>
                    </a>
                    <a href="{{ route('admin.vehicules', ['statut' => 'mission']) }}" class="btn btn-outline-warning">
                        <i class="fas fa-route me-1"></i>En mission
                        <span class="badge bg-warning ms-1">{{ $vehiculesMission }}</span>
                    </a>
                    <a href="{{ route('admin.vehicules', ['statut' => 'vendu']) }}" class="btn btn-outline-info">
                        <i class="fas fa-check-circle me-1"></i>Vendu
                        <span class="badge bg-info ms-1">{{ $vehiculesVendu }}</span>
                    </a>
                    <a href="{{ route('admin.vehicules', ['statut' => 'nonactif']) }}" class="btn btn-outline-dark">
                        <i class="fas fa-times-circle me-1"></i>Non actif
                        <span class="badge bg-dark ms-1">{{ $vehiculesNonActifsBadge }}</span>
                    </a>
                    <a href="{{ route('admin.vehicules') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i>Tous
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Marque/Modèle</th>
                            <th>Type</th>
                            <th>Immatriculation</th>
                            <th>Prix Location intérieur</th>
                            <th>Prix Location Abidjan</th>
                            <th>Prix Vente</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicules as $vehicule)
                        <tr>
                            <td>{{ $vehicule->id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $vehicule->image_principale) }}" alt="{{ $vehicule->marque }}" class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                            </td>
                            <td>{{ $vehicule->marque }} {{ $vehicule->serie }}</td>
                            <td>{{ $vehicule->type_vehicule }}</td>
                            <td>{{ $vehicule->immatriculation }}</td>
                            <td>{{ number_format($vehicule->prix_location_interieur, 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($vehicule->prix_location_abidjan, 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($vehicule->prix_vente, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($vehicule->disponibilite)
                                    @if($vehicule->etat_vehicule == 'vente')
                                        @if($vehicule->visibilite)
                                            <span class="badge bg-danger">En Vente</span>
                                        @else
                                            <span class="badge bg-dark">Non actif</span>
                                        @endif
                                    @else
                                        @if($vehicule->visibilite)
                                            <span class="badge bg-success">En location</span>
                                        @else
                                            <span class="badge bg-dark">Non actif</span>
                                        @endif
                                    @endif
                                @else
                                @if($vehicule->etat_vehicule == 'vente')
                                        @if($vehicule->fin_vehicule == 'vendu')
                                            <span class="badge bg-info">Vendu</span>
                                        @else
                                            <span class="badge bg-danger">En vente</span>
                                        @endif
                                    @else
                                        <span class="badge bg-warning">En mission</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $vehicule->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!($vehicule->etat_vehicule == 'vente' && $vehicule->fin_vehicule == 'vendu') && !(!$vehicule->disponibilite && $vehicule->etat_vehicule != 'vente'))
                                    <a href="#" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $vehicule->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(!(!$vehicule->disponibilite && $vehicule->etat_vehicule != 'vente'))
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $vehicule->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal{{ $vehicule->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $vehicule->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $vehicule->id }}">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer le véhicule "{{ $vehicule->marque }} {{ $vehicule->serie }}" ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form action="{{ route('admin.vehicules.delete', $vehicule->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal de détails du véhicule -->
                                <div class="modal fade" id="detailsModal{{ $vehicule->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $vehicule->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="detailsModalLabel{{ $vehicule->id }}">Détails du véhicule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 text-center mb-4">
                                                        <img src="{{ asset('storage/' . $vehicule->image_principale) }}" alt="{{ $vehicule->marque }}" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 class="fw-bold text-primary">{{ $vehicule->marque }} {{ $vehicule->serie }}</h4>
                                                        <p class="badge bg-secondary mb-3">{{ $vehicule->type_vehicule }}</p>
                                                        
                                                        <div class="mb-3">
                                                            <p><strong>Statut:</strong> 
                                                                @if($vehicule->disponibilite)
                                                                    @if($vehicule->etat_vehicule == 'vente')
                                                                        <span class="badge bg-danger">En vente</span>
                                                                    @else
                                                                        <span class="badge bg-success">En location</span>
                                                                    @endif
                                                                @else
                                                                    @if($vehicule->etat_vehicule == 'vente')
                                                                        @if($vehicule->fin_vehicule == 'vendu')
                                                                            <span class="badge bg-info">Vendu</span>
                                                                        @else
                                                                            <span class="badge bg-danger">En vente</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="badge bg-warning">En mission</span>
                                                                    @endif
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h5 class="border-bottom pb-2 mb-3">Informations générales</h5>
                                                        <p><strong>Immatriculation:</strong> {{ $vehicule->immatriculation }}</p>
                                                        <p><strong>Année:</strong> {{ $vehicule->annee }}</p>
                                                        <p><strong>Nombre de places:</strong> {{ $vehicule->nb_places }}</p>
                                                        <p><strong>Carburant:</strong> {{ $vehicule->carburant }}</p>
                                                        <p><strong>Boîte de vitesse:</strong> {{ $vehicule->transmission }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5 class="border-bottom pb-2 mb-3">Tarifs</h5>
                                                        @if($vehicule->etat_vehicule != 'vente')
                                                        <p><strong>Prix location intérieur:</strong> {{ number_format($vehicule->prix_location_interieur, 0, ',', ' ') }} FCFA</p>
                                                        <p><strong>Prix location Abidjan:</strong> {{ number_format($vehicule->prix_location_abidjan, 0, ',', ' ') }} FCFA</p>
                                                        @endif
                                                        @if($vehicule->etat_vehicule != 'location')
                                                        <p><strong>Prix vente:</strong> {{ number_format($vehicule->prix_vente, 0, ',', ' ') }} FCFA</p>
                                                        @endif
                                                        
                                                        <h5 class="border-bottom pb-2 mb-3 mt-4">Informations complémentaires</h5>
                                                        @if($vehicule->etat_vehicule != 'vente')
                                                        @php 
                                                        $chauffeur = DB::table('users')->where('id', $vehicule->user_id)->first();
                                                        @endphp
                                                        @if($chauffeur)
                                                        <p><strong>Chauffeur:</strong> {{ $chauffeur->name }} {{ $chauffeur->prenom }}</p>
                                                        <p><strong>contact Chauffeur:</strong> {{ $chauffeur->telephone }}</p>
                                                        @else
                                                        <p><strong>Chauffeur:</strong> Non assigné</p>
                                                        @endif
                                                        @endif
                                                        <p><strong>Ajouté le:</strong> {{ date('d/m/Y', strtotime($vehicule->created_at)) }}</p>
                                                        <p><strong>Dernière mise à jour:</strong> {{ date('d/m/Y', strtotime($vehicule->updated_at)) }}</p>
                                                    </div>
                                                </div>
                                                
                                                @if($vehicule->description)
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h5 class="border-bottom pb-2 mb-3">Description</h5>
                                                        <p>{{ $vehicule->description }}</p>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal de modification du véhicule -->
                                <div class="modal fade" id="editModal{{ $vehicule->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $vehicule->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title" id="editModalLabel{{ $vehicule->id }}">Modifier le véhicule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.vehicules.update', $vehicule->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="marque" class="form-label">Marque</label>
                                                                <input type="text" class="form-control" id="marque" name="marque" value="{{ $vehicule->marque }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="serie" class="form-label">Modèle</label>
                                                                <input type="text" class="form-control" id="serie" name="serie" value="{{ $vehicule->serie }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="type_vehicule" class="form-label">Type de véhicule</label>
                                                                <select class="form-select" id="type_vehicule" name="type_vehicule" required>
                                                                    @foreach($vehiculeTypes as $type)
                                                                        <option value="{{ $type }}" {{ $vehicule->type_vehicule == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="immatriculation" class="form-label">Immatriculation</label>
                                                                <input type="text" class="form-control" id="immatriculation" name="immatriculation" value="{{ $vehicule->immatriculation }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="annee" class="form-label">Année</label>
                                                                <input type="number" class="form-control" id="annee" name="annee" value="{{ $vehicule->annee }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="nb_places" class="form-label">Nombre de places</label>
                                                                <input type="number" class="form-control" id="nb_places" name="nb_places" value="{{ $vehicule->nb_places }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="carburant" class="form-label">Carburant</label>
                                                                <select class="form-select" id="carburant" name="carburant" required>
                                                                    <option value="Essence" {{ $vehicule->carburant == 'Essence' ? 'selected' : '' }}>Essence</option>
                                                                    <option value="Diesel" {{ $vehicule->carburant == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                                                    <option value="Hybride" {{ $vehicule->carburant == 'Hybride' ? 'selected' : '' }}>Hybride</option>
                                                                    <option value="Électrique" {{ $vehicule->carburant == 'Électrique' ? 'selected' : '' }}>Électrique</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="transmission" class="form-label">Boîte de vitesse</label>
                                                                <select class="form-select" id="transmission" name="transmission" required>
                                                                    <option value="Manuelle" {{ $vehicule->transmission == 'Manuelle' ? 'selected' : '' }}>Manuelle</option>
                                                                    <option value="Automatique" {{ $vehicule->transmission == 'Automatique' ? 'selected' : '' }}>Automatique</option>
                                                                    <option value="Semi-automatique" {{ $vehicule->transmission == 'Semi-automatique' ? 'selected' : '' }}>Semi-automatique</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="prix_location_interieur" class="form-label">Prix location intérieur (FCFA)</label>
                                                                <input type="number" class="form-control" id="prix_location_interieur" name="prix_location_interieur" value="{{ $vehicule->prix_location_interieur }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="prix_location_abidjan" class="form-label">Prix location Abidjan (FCFA)</label>
                                                                <input type="number" class="form-control" id="prix_location_abidjan" name="prix_location_abidjan" value="{{ $vehicule->prix_location_abidjan }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="prix_vente" class="form-label">Prix vente (FCFA)</label>
                                                                <input type="number" class="form-control" id="prix_vente" name="prix_vente" value="{{ $vehicule->prix_vente }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="etat_vehicule" class="form-label">État du véhicule</label>
                                                                <select class="form-select" id="etat_vehicule" name="etat_vehicule" required>
                                                                    <option value="location" {{ $vehicule->etat_vehicule == 'location' ? 'selected' : '' }}>En location</option>
                                                                    <option value="vente" {{ $vehicule->etat_vehicule == 'vente' ? 'selected' : '' }}>En vente</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="disponibilite" class="form-label">Disponibilité</label>
                                                                <select class="form-select" id="disponibilite" name="disponibilite" disabled>
                                                                    <option value="1" {{ $vehicule->disponibilite ? 'selected' : '' }}>Disponible</option>
                                                                    <option value="0" {{ !$vehicule->disponibilite ? 'selected' : '' }}>Non disponible</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="visibilite" class="form-label">Visibilité</label>
                                                                <select class="form-select" id="visibilite" name="visibilite" required>
                                                                    <option value="1" {{ $vehicule->visibilite ? 'selected' : '' }}>Visible</option>
                                                                    <option value="0" {{ !$vehicule->visibilite ? 'selected' : '' }}>Non visible</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @if($vehicule->etat_vehicule == 'vente')
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="fin_vehicule" class="form-label">Statut de vente</label>
                                                                <select class="form-select" id="fin_vehicule" name="fin_vehicule">
                                                                    <option value="non_vendu" {{ $vehicule->fin_vehicule == 'non_vendu' ? 'selected' : '' }}>Non vendu</option>
                                                                    <option value="vendu" {{ $vehicule->fin_vehicule == 'vendu' ? 'selected' : '' }}>Vendu</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="user_id" class="form-label">Chauffeur (pour les véhicules en location)</label>
                                                        @php 
                                                            // Récupérer le chauffeur actuel du véhicule
                                                            $chauffeurActuel = null;
                                                            if($vehicule->user_id) {
                                                                $chauffeurActuel = DB::table('users')->where('id', $vehicule->user_id)->first();
                                                            }
                                                            
                                                            // Récupérer les chauffeurs disponibles (qui ne sont pas associés à un véhicule)
                                                            $chauffeursDisponibles = DB::table('users')
                                                                ->where('status', 'chauffeur')
                                                                ->whereNotIn('id', function($query) use ($vehicule) {
                                                                    $query->select('user_id')
                                                                        ->from('vehicules')
                                                                        ->where('user_id', '!=', null)
                                                                        ->where('id', '!=', $vehicule->id);
                                                                })
                                                                ->get();
                                                        @endphp
                                                        
                                                        @if($chauffeurActuel)
                                                        <div class="mb-3 p-3 border rounded bg-light">
                                                            <h6 class="fw-bold">Chauffeur actuel:</h6>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="mb-0 fw-bold">{{ $chauffeurActuel->name }} {{ $chauffeurActuel->prenom }}</p>
                                                                    <p class="mb-0 small text-muted">{{ $chauffeurActuel->telephone }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        
                                                        <select class="form-select" id="user_id" name="user_id">
                                                            <option value="">{{ $chauffeurActuel ? 'Changer de chauffeur' : 'Aucun chauffeur' }}</option>
                                                            @if($chauffeurActuel)
                                                            <option value="{{ $chauffeurActuel->id }}" selected>Conserver le chauffeur actuel</option>
                                                            <option value="null">Dissocier ce chauffeur du véhicule</option>
                                                            @endif
                                                            @foreach($chauffeursDisponibles as $chauffeur)
                                                                @if(!$chauffeurActuel || $chauffeur->id != $chauffeurActuel->id)
                                                                <option value="{{ $chauffeur->id }}">
                                                                    {{ $chauffeur->name }} {{ $chauffeur->prenom }} - {{ $chauffeur->telephone }}
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        <small class="form-text text-muted">Seuls les chauffeurs non associés à d'autres véhicules sont affichés.</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Images du véhicule</label>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label for="image_principale" class="form-label">Image principale</label>
                                                                    <input type="file" class="form-control" id="image_principale" name="image_principale">
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/' . $vehicule->image_principale) }}" alt="Image principale" class="img-thumbnail" style="height: 120px; width: 100%; object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label for="image_secondaire" class="form-label">Image secondaire</label>
                                                                    <input type="file" class="form-control" id="image_secondaire" name="image_secondaire">
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/' . $vehicule->image_secondaire) }}" alt="Image secondaire" class="img-thumbnail" style="height: 120px; width: 100%; object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label for="image_tertiaire" class="form-label">Image tertiaire</label>
                                                                    <input type="file" class="form-control" id="image_tertiaire" name="image_tertiaire">
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/' . $vehicule->image_tertiaire) }}" alt="Image tertiaire" class="img-thumbnail" style="height: 120px; width: 100%; object-fit: cover;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <small class="form-text text-muted">Laissez les champs vides pour conserver les images actuelles.</small>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-warning text-white">Enregistrer les modifications</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
