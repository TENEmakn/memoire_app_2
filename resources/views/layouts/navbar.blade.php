

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}">
            <div class="logo">
                <span class="cgv">CGV</span>
                <span class="motors">MOTORS</span>
            </div>
            @Auth
            @if(Auth::user()->status == 'admin' || Auth::user()->status == 'gestionnaire')
            <a href="{{ route('admin.index') }}" class="text-decoration-none text-dark">
                <i class="fas fa-user-shield"></i> Dashboard
            </a>
            @endif
            @endAuth
        </a>
        
        <button class="navbar-toggler position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            @auth
                @php
                    $notificationCount = 0;
                    
                    // 1. Vérifier les documents manquants
                    if(!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso || !Auth::user()->piece_verifie) {
                        $notificationCount++;
                    }
                    
                    // 3. Ajouter ici d'autres vérifications selon les besoins
                    // Exemples :
                    // - Demandes de réservation en attente
                    // - Messages non lus
                    // - Rappels de rendez-vous
                    // - Notifications de système
                @endphp
                
                @if($notificationCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $notificationCount }}
                        <span class="visually-hidden">notifications non lues</span>
                    </span>
                @endif
            @endauth
        </button>
        <div class="mobile-separator d-lg-none"></div>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}#">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('index') }}#vehicules-vente">Vente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}#vehicules-location">Location</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact.show') }}">Assistance</a>
                </li>
            </ul>
            
            <div class="navbar-right">
                @guest
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link auth-link login" href="{{ route('auth.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link auth-link register" href="{{ route('auth.register') }}">
                            <i class="fas fa-user-plus me-1"></i>Inscription
                        </a>
                    </li>
                </ul>
                @endguest
                
                @auth
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="user-circle dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @if($notificationCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $notificationCount }}
                                    <span class="visually-hidden">notifications non lues</span>
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item position-relative" href="{{ route('auth.profil') }}">
                                    <i class="fas fa-user me-2"></i>Mon profil
                                    @if(!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso || !Auth::user()->piece_verifie)
                                        <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger">
                                            {{ (!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso || !Auth::user()->piece_verifie) ? '1' : '1' }}
                                            <span class="visually-hidden">Documents manquants</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @if(Auth::user()->status == 'chauffeur')
                                <li><a class="dropdown-item" href="{{ route('mes_missions') }}"><i class="fas fa-car me-2"></i>Mes Missions</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('mes_reservations') }}"><i class="fas fa-calendar-alt me-2"></i>Mes Reservations</a></li>
                            <li><a class="dropdown-item" href="{{ route('mes_rdv') }}"><i class="fas fa-calendar-alt me-2"></i>Mes Rendez-vous</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Mes Messages</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endauth
            </div>
        </div>
    </div>
</nav>
