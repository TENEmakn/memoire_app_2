@extends('layouts.app')

<!-- Ajout de Font Awesome pour les icônes -->
@section('styles')
<link rel="stylesheet" href="{{ asset('css/banner_new.css') }}">
<link rel="stylesheet" href="{{ asset('css/advantages.css') }}">
<link rel="stylesheet" href="{{ asset('css/brands.css') }}">
<link rel="stylesheet" href="{{ asset('css/badges.css') }}">
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* Style pour le loader dans les selects */
    .select-wrapper {
        position: relative;
        width: 100%;
    }
    
    .loader-container {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    
    .select-loader {
        width: 15px;
        height: 15px;
        border: 2px solid rgba(0, 0, 0, 0.1);
        border-top: 2px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loader-visible {
        display: block;
    }
    
    /* Style pour désactiver les selects pendant le chargement */
    .form-select.disabled {
        opacity: 0.6;
        pointer-events: none;
    }
    
    /* Style pour le bouton de réinitialisation */
    .search-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .btn-reset-search {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 15px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        font-size: 0.9em;
        text-decoration: none;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .btn-reset-search:hover {
        background-color: #5a6268;
        color: white;
        text-decoration: none;
    }
    
    .btn-reset-search i {
        margin-right: 6px;
    }
    
    /* Assurer que les boutons ont la même hauteur */
    .search-actions .btn-search,
    .search-actions .btn-reset-search {
        height: 44px;
        box-sizing: border-box;
    }
    
    /* Style pour la bannière */
    .banner-bg {
        background-image: url('{{ asset('images/v2.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

<!-- Style spécifique pour égaliser les boutons -->
<style>
    .search-actions {
        align-items: stretch;
    }
    
    .search-actions .btn-search,
    .search-actions .btn-reset-search {
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 15px;
        font-size: 16px;
    }
</style>
@endsection

@section('content')
<!-- Banner Section -->
<section class="banner banner-bg">
    <div class="banner-overlay"></div>
    <div class="motion-box">
        <div class="motion-line"></div>
        <div class="motion-line"></div>
        <div class="motion-line"></div>
    </div>
    <div class="floating-elements">
        <div class="floating-icon" style="top: 15%; left: 10%;">
            <i class="fas fa-car"></i>
        </div>
        <div class="floating-icon" style="top: 25%; right: 15%;">
            <i class="fas fa-key"></i>
        </div>
        <div class="floating-icon" style="bottom: 30%; left: 18%;">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="floating-icon" style="bottom: 20%; right: 10%;">
            <i class="fas fa-tag"></i>
        </div>
    </div>
    <div class="container">
        <div class="trust-badges">
            <div class="trust-badge">
                <i class="fas fa-shield-alt"></i>
                <span>100% Sécurisé</span>
            </div>
            <div class="trust-badge">
                <i class="fas fa-certificate"></i>
                <span>Qualité Premium</span>
            </div>
            <div class="trust-badge">
                <i class="fas fa-check-circle"></i>
                <span>Véhicules Vérifiés</span>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="banner-content">
                    <div class="banner-badge">
                        <i class="fas fa-award"></i> Service Premium
                    </div>
                    <h1 class="banner-title">Trouvez un <span class="banner-accent">véhicule</span> idéal pour vos Déplacements</h1>
                    <p class="banner-subtitle">Des véhicules vérifiés et approuvés par nos experts</p>
                    <div class="banner-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Large sélection de véhicules</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Prix compétitifs garantis</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Service client 24/7</span>
                        </div>
                    </div>
                    <a href="#" class="banner-cta">Voir nos véhicules <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="banner-search-container">
                    <div class="quick-filters">
                        <span class="filter-label">Recherche rapide:</span>
                        <button class="quick-filter-btn" data-type="car"><i class="fas fa-car"></i>Location</button>
                        <button class="quick-filter-btn" data-type="moto"><i class="fas fa-car"></i>Vente</button>
                        <a href="{{ route('index') }}" class="btn-reset-search" style="height: 44px; padding: 0 20px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-sync-alt"></i> Réinitialiser</a>
                    </div>
                    <h3 class="search-form-title"><i class="fas fa-search"></i> Trouver votre véhicule</h3>
                    <form action="{{ route('index') }}" method="GET" id="search-form">
                        <div class="search-grid">
                            <div class="form-group">
                                <div class="select-wrapper">
                                    <select name="marque" id="brand" class="form-select">
                                        <option value="" selected disabled>Marque</option>
                                        @foreach($marques as $marque)
                                            <option value="{{ $marque }}" {{ request('marque') == $marque ? 'selected' : '' }}>{{ ucfirst($marque) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="loader-container" id="loader-brand">
                                        <div class="select-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="select-wrapper">
                                    <select name="type" id="type" class="form-select">
                                        <option value="" selected disabled>Type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="loader-container" id="loader-type">
                                        <div class="select-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="select-wrapper">
                                    <select name="serie" id="serie" class="form-select">
                                        <option value="" selected disabled>Série</option>
                                        @foreach($series as $serie)
                                            <option value="{{ $serie }}" {{ request('serie') == $serie ? 'selected' : '' }}>{{ ucfirst($serie) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="loader-container" id="loader-serie">
                                        <div class="select-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group wide-field">
                                <div class="select-wrapper">
                                    <select name="annee" id="year" class="form-select">
                                        <option value="" selected disabled>Année</option>
                                        @foreach($annees as $annee)
                                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                                        @endforeach
                                    </select>
                                    <div class="loader-container" id="loader-year">
                                        <div class="select-loader"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group wide-field">
                                <div class="select-wrapper">
                                    <select name="type_annonce" id="transaction" class="form-select">
                                        <option value="" selected disabled>Transaction</option>
                                        <option value="vente" {{ request('type_annonce') == 'vente' ? 'selected' : '' }}>Vente</option>
                                        <option value="location" {{ request('type_annonce') == 'location' ? 'selected' : '' }}>Location</option>
                                    </select>
                                    <div class="loader-container" id="loader-transaction">
                                        <div class="select-loader"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="search-actions" style="align-items: stretch;">
                            <button type="submit" class="btn-search" style="height: 44px; padding: 0 20px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-search"></i> Rechercher</button>
                            
                        </div>
                    </form>
                    <div class="search-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Plus de 500 véhicules disponibles</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Section -->

<!----------------------------------------------------------------------------------------------------------------->

<!-- Section des voitures en location -->
<section id="vehicules-location" class="vehicles-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nos Véhicules en Location</h2>
            <p class="section-subtitle">Découvrez notre sélection de véhicules disponibles pour la location</p>
        </div>
        <div class="vehicles-grid">
            @if($vehiculesLocation->count() > 0)
                @foreach($vehiculesLocation as $vehicule)
                <div class="vehicle-card" data-disponible="{{ $vehicule->disponibilite ? 'true' : 'false' }}">
                        <div class="vehicle-image">
                            <img src="{{ asset('storage/' . $vehicule->image_principale) }}" alt="{{ $vehicule->marque }} {{ $vehicule->serie }}">
                            <span class="{{ $vehicule->disponibilite ? 'location-badge' : 'mission-badge' }}">{{ $vehicule->disponibilite ? 'Location' : 'En Mission' }}</span>
                        </div>
                        <div class="vehicle-info">
                            <h3 class="vehicle-title">{{ $vehicule->marque }} {{ $vehicule->serie }}</h3>
                            <div class="vehicle-details">
                                <span class="detail"><i class="fas fa-car"></i> {{ $vehicule->type_vehicule }}</span>
                                <span class="detail"><i class="fas fa-tag"></i> {{ $vehicule->serie }}</span>
                                <span class="detail"><i class="fas fa-calendar-alt"></i> {{ $vehicule->annee }}</span>
                            </div>
                            <div class="vehicle-specs">
                                <span class="spec"><i class="fas fa-gas-pump"></i> {{ $vehicule->carburant }}</span>
                                <span class="spec"><i class="fas fa-cog"></i> {{ $vehicule->transmission }}</span>
                                <span class="spec"><i class="fas fa-users"></i> {{ $vehicule->nb_places }} places</span>
                            </div>
                            <div class="vehicle-pricing">
                                <div class="price-location">
                                    <div class="location">
                                        <span class="city"><i class="fas fa-map-marker-alt"></i> Abidjan</span>
                                        <span class="price"><i class="fas fa-money-bill-wave"></i> {{ number_format($vehicule->prix_location_abidjan, 0, ',', '.') }}fr /Jr</span>
                                    </div>
                                    <div class="location">
                                        <span class="city"><i class="fas fa-map-marker-alt"></i> Interieur</span>
                                        <span class="price"><i class="fas fa-money-bill-wave"></i> {{ number_format($vehicule->prix_location_interieur, 0, ',', '.') }}fr /Jr</span>
                                    </div>
                                </div>
                                @auth
                                        <a href="{{ $vehicule->disponibilite ? route('location.create', $vehicule->id) : '#' }}" class="btn-louer {{ !$vehicule->disponibilite ? 'disabled' : '' }}">
                                            <i class="fas fa-key"></i> {{ $vehicule->disponibilite ? 'Louer' : 'Indisponible' }}
                                        </a>
                                    @else
                                        <a href="{{ route('auth.login') }}" class="btn-louer">
                                            <i class="fas fa-sign-in-alt"></i> Se connecter
                                        </a>
                                 @endauth

                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>Aucun véhicule en location ne correspond à vos critères</p>
                    <a href="{{ route('index') }}" class="btn-reset">Réinitialiser les filtres</a>
                </div>
            @endif
        </div>
        <div class="pagination-container">
            @if(method_exists($vehiculesLocation, 'hasPages') && $vehiculesLocation->hasPages())
                <div class="pagination">
                    @if($vehiculesLocation->onFirstPage())
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $vehiculesLocation->previousPageUrl() }}#vehicules-location" class="pagination-link">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif
                    
                    @foreach($vehiculesLocation->getUrlRange(1, $vehiculesLocation->lastPage()) as $page => $url)
                        <a href="{{ $url }}#vehicules-location" 
                           class="pagination-link {{ $page == $vehiculesLocation->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                    
                    @if($vehiculesLocation->hasMorePages())
                        <a href="{{ $vehiculesLocation->nextPageUrl() }}#vehicules-location" class="pagination-link">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
<!-- Fin section des voitures en location -->

<!-- Section des voitures en vente -->
<section id="vehicules-vente" class="vehicles-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nos Véhicules en Vente</h2>
            <p class="section-subtitle">Découvrez notre sélection de véhicules disponibles à l'achat</p>
        </div>
        <div class="vehicles-grid">
            @if($vehiculesVente->count() > 0)
                @foreach($vehiculesVente as $vehicule)
                    <div class="vehicle-card">
                        <div class="vehicle-image">
                            <img src="{{ asset('storage/' . $vehicule->image_principale) }}" alt="{{ $vehicule->marque }} {{ $vehicule->serie }}">
                            <span class="vente-badge">Vente</span>
                        </div>
                        <div class="vehicle-info">
                            <h3 class="vehicle-title">{{ $vehicule->marque }} {{ $vehicule->serie }}</h3>
                            <div class="vehicle-details">
                                <span class="detail"><i class="fas fa-car"></i> {{ $vehicule->type_vehicule }}</span>
                                <span class="detail"><i class="fas fa-tag"></i> {{ $vehicule->serie }}</span>
                                <span class="detail"><i class="fas fa-calendar-alt"></i> {{ $vehicule->annee }}</span>
                            </div> 
                            <div class="vehicle-specs">
                                <span class="spec"><i class="fas fa-gas-pump"></i> {{ $vehicule->carburant }}</span>
                                <span class="spec"><i class="fas fa-cog"></i> {{ $vehicule->transmission }}</span>
                                <span class="spec"><i class="fas fa-users"></i> {{ $vehicule->nb_places }} places</span>
                            </div>
                            <div class="vehicle-pricing">
                                <div class="price-sale">
                                    <span class="price"><i class="fas fa-money-bill-wave"></i> {{ number_format($vehicule->prix_vente, 0, ',', '.') }}fr</span>
                                </div>
                                @guest
                                    <a href="{{ route('auth.login') }}" class="btn-acheter"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
                                @else
                                    <a href="{{ route('vente.show', $vehicule->id) }}" class="btn-acheter"><i class="fas fa-shopping-cart"></i> Acheter</a>
                                @endguest
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>Aucun véhicule en vente ne correspond à vos critères</p>
                    <a href="{{ route('index') }}" class="btn-reset">Réinitialiser les filtres</a>
                </div>
            @endif
        </div>
        <div class="pagination-container">
            @if(method_exists($vehiculesVente, 'hasPages') && $vehiculesVente->hasPages())
                <div class="pagination">
                    @if($vehiculesVente->onFirstPage())
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $vehiculesVente->previousPageUrl() }}#vehicules-vente" class="pagination-link">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif
                    
                    @foreach($vehiculesVente->getUrlRange(1, $vehiculesVente->lastPage()) as $page => $url)
                        <a href="{{ $url }}#vehicules-vente" 
                           class="pagination-link {{ $page == $vehiculesVente->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                    
                    @if($vehiculesVente->hasMorePages())
                        <a href="{{ $vehiculesVente->nextPageUrl() }}#vehicules-vente" class="pagination-link">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
<!-- Fin section des voitures en vente -->

<!-- Section des avantages -->
<section class="advantages-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title text-white">Nos Avantages</h2>
            <p class="section-subtitle">Découvrez pourquoi nous sommes votre meilleur choix</p>
        </div>
        <div class="advantages-grid">
            <!-- Qualité et expérience -->
            <div class="advantage-card">
                <div class="advantage-icon-wrapper">
                    <div class="advantage-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="advantage-badge">10+ ans</div>
                </div>
                <div class="advantage-content">
                    <h3>Qualité et expérience</h3>
                    <p>Expert depuis plus de 10 ans pour aider nos clients à louer et à acheter une voiture.</p>
                    <ul class="advantage-features">
                        <li><i class="fas fa-check"></i> Service professionnel</li>
                        <li><i class="fas fa-check"></i> Expertise reconnue</li>
                        <li><i class="fas fa-check"></i> Satisfaction client</li>
                    </ul>
                </div>
            </div>

            <!-- Économies -->
            <div class="advantage-card featured">
                <div class="advantage-icon-wrapper">
                    <div class="advantage-icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <div class="advantage-badge">Économies</div>
                </div>
                <div class="advantage-content">
                    <h3>Louez Malin, Payez Moins !</h3>
                    <p>Économisez plus en profitant de nos offres exceptionnelles pour la location de véhicule.</p>
                    <ul class="advantage-features">
                        <li><i class="fas fa-check"></i> Meilleurs prix garantis</li>
                        <li><i class="fas fa-check"></i> Offres spéciales régulières</li>
                        <li><i class="fas fa-check"></i> Programme de fidélité</li>
                    </ul>
                </div>
            </div>

            <!-- Boss -->
            <div class="advantage-card">
                <div class="advantage-icon-wrapper">
                    <div class="advantage-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="advantage-badge">VIP</div>
                </div>
                <div class="advantage-content">
                    <h3>Devenez le Boss que vous pensez être</h3>
                    <p>Louez et devenez le boss dont vous rêvez ! Un chauffeur qualifié et expérimenté à votre service.</p>
                    <ul class="advantage-features">
                        <li><i class="fas fa-check"></i> Chauffeur personnel</li>
                        <li><i class="fas fa-check"></i> Service premium</li>
                        <li><i class="fas fa-check"></i> Confort maximal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Fin section des avantages -->

<!-- Section des marques -->
<section class="brands-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">NOS MARQUES</h2>
            <p class="section-subtitle">Découvrez notre sélection de marques prestigieuses</p>
        </div>
        <div class="brands-slider">
            <div class="brands-track">
                <!-- Marque 1 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/toyota.png') }}" alt="Toyota">
                    </div>
                    <h3>Toyota</h3>
                    <p class="brand-slogan">En Route vers l'Aventure</p>
                </div>

                <!-- Marque 2 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/toyota.png') }}" alt="Mercedes">
                    </div>
                    <h3>Mercedes</h3>
                    <p class="brand-slogan">Le Meilleur ou Rien</p>
                </div>

                <!-- Marque 3 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/toyota.png') }}" alt="BMW">
                    </div>
                    <h3>BMW</h3>
                    <p class="brand-slogan">Le Plaisir de Conduire</p>
                </div>

                <!-- Marque 4 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/audi.png') }}" alt="Audi">
                    </div>
                    <h3>Audi</h3>
                    <p class="brand-slogan">L'Avance par la Technologie</p>
                </div>

                <!-- Marque 5 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/honda.png') }}" alt="Honda">
                    </div>
                    <h3>Honda</h3>
                    <p class="brand-slogan">La Puissance des Rêves</p>
                </div>

                <!-- Marque 6 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/toyota.png') }}" alt="Peugeot">
                    </div>
                    <h3>Peugeot</h3>
                    <p class="brand-slogan">Motion & Émotion</p>
                </div>

                <!-- Marque 7 -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/renault.png') }}" alt="Renault">
                    </div>
                    <h3>Renault</h3>
                    <p class="brand-slogan">La Passion pour la Vie</p>
                </div>

                <!-- Répétition des marques pour l'effet de défilement infini -->
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/toyota.png') }}" alt="Toyota">
                    </div>
                    <h3>Toyota</h3>
                    <p class="brand-slogan">En Route vers l'Aventure</p>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="{{ asset('images/toyota.png') }}" alt="Mercedes">
                    </div>
                    <h3>Mercedes</h3>
                    <p class="brand-slogan">Le Meilleur ou Rien</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Fin section des marques -->

@section('scripts')
<!-- Script pour l'animation de la bannière -->
<script src="{{ asset('js/banner.js') }}"></script>

<!-- Script pour les fonctionnalités du formulaire de recherche dynamique -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Référence aux éléments select
        const marqueSelect = document.getElementById('brand');
        const typeSelect = document.getElementById('type');
        const serieSelect = document.getElementById('serie');
        const anneeSelect = document.getElementById('year');
        const transactionSelect = document.getElementById('transaction');
        
        // Référence aux loaders
        const loaderBrand = document.getElementById('loader-brand');
        const loaderType = document.getElementById('loader-type');
        const loaderSerie = document.getElementById('loader-serie');
        const loaderYear = document.getElementById('loader-year');
        const loaderTransaction = document.getElementById('loader-transaction');
        
        // Tableau de tous les selects et loaders
        const allSelects = [marqueSelect, typeSelect, serieSelect, anneeSelect, transactionSelect];
        const allLoaders = [loaderBrand, loaderType, loaderSerie, loaderYear, loaderTransaction];
        
        // Fonction pour afficher tous les loaders
        function showAllLoaders() {
            allLoaders.forEach(loader => {
                loader.classList.add('loader-visible');
            });
        }
        
        // Fonction pour cacher tous les loaders
        function hideAllLoaders() {
            allLoaders.forEach(loader => {
                loader.classList.remove('loader-visible');
            });
        }
        
        // Fonction pour désactiver tous les selects
        function disableAllSelects() {
            allSelects.forEach(select => {
                select.classList.add('disabled');
            });
        }
        
        // Fonction pour activer tous les selects
        function enableAllSelects() {
            allSelects.forEach(select => {
                select.classList.remove('disabled');
            });
        }
        
        // Associer les event listeners aux selects
        marqueSelect.addEventListener('change', function() { 
            showAllLoaders();
            disableAllSelects();
            updateOptions('marque', this.value); 
        });
        
        typeSelect.addEventListener('change', function() { 
            showAllLoaders();
            disableAllSelects();
            updateOptions('type', this.value); 
        });
        
        serieSelect.addEventListener('change', function() { 
            showAllLoaders();
            disableAllSelects();
            updateOptions('serie', this.value); 
        });
        
        anneeSelect.addEventListener('change', function() { 
            showAllLoaders();
            disableAllSelects();
            updateOptions('annee', this.value); 
        });
        
        transactionSelect.addEventListener('change', function() { 
            showAllLoaders();
            disableAllSelects();
            updateOptions('type_annonce', this.value); 
        });
        
        // Réinitialisation des champs sur clic du bouton reset
        document.querySelector('.btn-reset-search').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Réinitialiser tous les selects
            [marqueSelect, typeSelect, serieSelect, anneeSelect, transactionSelect].forEach(select => {
                select.value = '';
                select.selectedIndex = 0;
            });
            
            // Rediriger vers la page d'accueil
            window.location.href = this.getAttribute('href');
        });
        
        // Modifier le formulaire de recherche pour ajouter le scroll automatique
        document.getElementById('search-form').addEventListener('submit', function(e) {
            // Si un type d'annonce est sélectionné, ajoutez-le comme fragment d'URL
            if (transactionSelect.value) {
                const targetSection = transactionSelect.value === 'location' ? 'vehicules-location' : 'vehicules-vente';
                this.action = "{{ route('index') }}#" + targetSection;
            } else {
                // Par défaut, faire défiler jusqu'à la section de location
                this.action = "{{ route('index') }}#vehicules-location";
            }
        });
        
        // Fonction pour faire défiler la page jusqu'à la section appropriée après le chargement
        function scrollToTargetSection() {
            // Récupérer le fragment d'URL (partie après #)
            const hash = window.location.hash;
            
            // Si un fragment existe, faire défiler jusqu'à cette section
            if (hash) {
                // Petite temporisation pour s'assurer que la page est complètement chargée
                setTimeout(() => {
                    const targetElement = document.querySelector(hash);
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }
                }, 100);
            } else {
                // Par défaut, faire défiler jusqu'à la section de location
                const defaultSection = document.getElementById('vehicules-location');
                if (defaultSection) {
                    setTimeout(() => {
                        defaultSection.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
            }
        }
        
        // Exécuter le défilement après le chargement de la page
        scrollToTargetSection();
        
        // Fonction pour mettre à jour les options des autres champs
        function updateOptions(changedField, selectedValue) {
            // Afficher un indicateur de chargement
            console.log('Mise à jour des options après changement du champ:', changedField);
            
            // Préparer les données pour la requête
            const formData = new FormData();
            formData.append('field', changedField);
            formData.append('value', selectedValue);
            
            // Ajouter les valeurs des autres champs
            if (marqueSelect.value && marqueSelect.value !== "") formData.append('marque', marqueSelect.value);
            if (typeSelect.value && typeSelect.value !== "") formData.append('type', typeSelect.value);
            if (serieSelect.value && serieSelect.value !== "") formData.append('serie', serieSelect.value);
            if (anneeSelect.value && anneeSelect.value !== "") formData.append('annee', anneeSelect.value);
            if (transactionSelect.value && transactionSelect.value !== "") formData.append('type_annonce', transactionSelect.value);
            
            // Envoyer la requête AJAX
            fetch('{{ route('filter.options') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                
                // Mettre à jour chaque champ, sauf celui qui a été changé
                if (changedField !== 'marque' && data.data.marque) {
                    updateSelectOptions(marqueSelect, data.data.marque);
                }
                if (changedField !== 'type' && data.data.type) {
                    updateSelectOptions(typeSelect, data.data.type);
                }
                if (changedField !== 'serie' && data.data.serie) {
                    updateSelectOptions(serieSelect, data.data.serie);
                }
                if (changedField !== 'annee' && data.data.annee) {
                    updateSelectOptions(anneeSelect, data.data.annee);
                }
                
                // Cacher tous les loaders et réactiver tous les selects
                hideAllLoaders();
                enableAllSelects();
            })
            .catch(error => {
                console.error('Erreur:', error);
                // Cacher tous les loaders et réactiver tous les selects en cas d'erreur
                hideAllLoaders();
                enableAllSelects();
            });
        }
        
        // Fonction pour mettre à jour les options d'un select
        function updateSelectOptions(selectElement, options) {
            if (!options || !Array.isArray(options) || options.length === 0) {
                console.warn('Aucune option à mettre à jour pour:', selectElement.id);
                return;
            }
            
            console.log('Mise à jour des options pour:', selectElement.id, options);
            
            // Sauvegarder la valeur actuelle
            const currentValue = selectElement.value;
            
            // Conserver la première option (placeholder)
            const placeholder = selectElement.options[0].cloneNode(true);
            
            // Vider le select
            selectElement.innerHTML = '';
            
            // Remettre le placeholder
            selectElement.appendChild(placeholder);
            
            // Ajouter les nouvelles options
            options.forEach(option => {
                if (option) {  // Vérifier que l'option n'est pas null ou undefined
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = typeof option === 'string' ? 
                        (option.charAt(0).toUpperCase() + option.slice(1)) : option;
                    selectElement.appendChild(optionElement);
                }
            });
            
            // Restaurer la valeur si elle existe toujours dans les options
            if (options.includes(currentValue)) {
                selectElement.value = currentValue;
            }
        }
        
        // Ajuster la hauteur du bouton de réinitialisation pour qu'elle corresponde au bouton de recherche
        function adjustResetButtonHeight() {
            const searchButton = document.querySelector('.btn-search');
            const resetButton = document.querySelector('.btn-reset-search');
            
            if (searchButton && resetButton) {
                const searchButtonHeight = searchButton.offsetHeight;
                resetButton.style.height = searchButtonHeight + 'px';
                
                // Ajuster également les styles internes pour une meilleure apparence
                resetButton.style.display = 'inline-flex';
                resetButton.style.alignItems = 'center';
                resetButton.style.justifyContent = 'center';
                resetButton.style.padding = '0 15px';
                resetButton.style.boxSizing = 'border-box';
            }
        }
        
        // Exécuter l'ajustement une fois que la page est chargée
        adjustResetButtonHeight();
        
        // Réexécuter en cas de redimensionnement de la fenêtre
        window.addEventListener('resize', adjustResetButtonHeight);
    });
</script>

<!-- Script pour le slider des marques -->
<script src="{{ asset('js/index.js') }}"></script>
@endsection
@endsection
