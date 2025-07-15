<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}">
            <div class="logo">
                <img src="{{ asset('images/cgvmotors-logo.png') }}" alt="CGV Motors" class="logo-img">
            </div>
            @Auth
            @if(Auth::user()->status == 'admin' || Auth::user()->status == 'gestionnaire')
            <a href="{{ route('admin.index') }}" class="text-decoration-none text-dark d-none d-lg-inline-block">
                <i class="fas fa-user-shield"></i> Dashboard
            </a>
            @endif
            @endAuth
        </a>
        
        <div class="d-flex align-items-center">
            @auth
            <div class="d-flex position-relative d-lg-none me-2">
                <a class="notification-bell position-relative">
                    <i class="fas fa-bell fs-5"></i>
                    @php
                        $notificationCount = 0;
                        
                        // 1. Vérifier les documents manquants
                        if(Auth::user()->status != 'admin' && (!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso || !Auth::user()->piece_verifie)) {
                            $notificationCount++;
                        }
                        
                        // 2. Vérifier les messages non lus
                        $unreadMessagesCount = \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count();
                        $notificationCount += $unreadMessagesCount;
                    @endphp
                    
                    @if($notificationCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" style="font-size: 0.65rem; padding: 0.2rem 0.35rem; scale(0.85);">
                            {{ $notificationCount }}
                            <span class="visually-hidden">notifications non lues</span>
                        </span>
                    @endif
                </a>
            </div>
            @endauth
            
            <button class="navbar-toggler position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                @auth
                    
                    <!-- // 3. Ajouter ici d'autres vérifications selon les besoins
                    // Exemples :
                    // - Demandes de réservation en attente
                    // - Rappels de rendez-vous
                    // - Notifications de système -->
                
                @endauth
            </button>
        </div>
        
        <div class="collapse navbar-collapse offcanvas-collapse" id="navbarNav">
            <ul class="navbar-nav mx-5">
                <li class="nav-item">
                    <a class="nav-link py-2 my-1" href="{{ route('index') }}#">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 my-1" href="{{ route('index') }}#vehicules-vente">Vente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 my-1" href="{{ route('index') }}#vehicules-location">Location</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 my-1" href="{{ route('contact.show') }}">Assistance</a>
                </li>
                @Auth
                @if(Auth::user()->status == 'admin' || Auth::user()->status == 'gestionnaire')
                <li class="nav-item d-lg-none">
                    <a class="nav-link py-2 my-1" href="{{ route('admin.index') }}">
                        <i class="fas fa-user-shield"></i> Dashboard
                    </a>
                </li>
                @endif
                @endAuth
            </ul>
            
            <div class="navbar-right">
                @guest
                <ul class="navbar-nav d-flex flex-column flex-lg-row">
                    <li class="nav-item">
                        <a class="nav-link auth-link login w-99 text-center my-1 py-2" href="{{ route('auth.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link auth-link register w-99 text-center my-1 py-2" href="{{ route('auth.register') }}">
                            <i class="fas fa-user-plus me-1"></i>Inscription
                        </a>
                    </li>
                </ul>
                @endguest
                
                @auth
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="user-circle dropdown-toggle d-flex align-items-center justify-content-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @if($notificationCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" style="font-size: 0.65rem; padding: 0.2rem 0.35rem; transform: translate(-50%, -50%) scale(0.85);">
                                    {{ $notificationCount }}
                                    <span class="visually-hidden">notifications non lues</span>
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item position-relative" href="{{ route('auth.profil') }}">
                                    <i class="fas fa-user me-2"></i>Mon profil
                                    @if(Auth::user()->status != 'admin' && (!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso || !Auth::user()->piece_verifie))
                                        <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger">
                                            {{ (Auth::user()->status != 'admin' && (!Auth::user()->image_piece_recto || !Auth::user()->image_piece_verso || !Auth::user()->piece_verifie)) ? '1' : '1' }}
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
                            <li><a class="dropdown-item" href="{{ route('client.messages') }}"><i class="fas fa-envelope me-2"></i>Mes Messages 
                            @php
                                        $user = Auth::user();
                                        $unreadMessagesCount = \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', $user->id)->count();
                                    @endphp
                                    @if($unreadMessagesCount > 0)
                                        <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadMessagesCount }}</span>
                                    @endif
                            </a></li>
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

<style>
/* Styles généraux de la navbar */
.navbar .navbar-nav .nav-link {
    padding: 0.5rem 1rem;
    transition: color 0.2s ease;
}

.navbar .navbar-nav .nav-link:hover {
    color: #0d6efd;
}

.user-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background-color: #f8f9fa;
    color: #212529;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    text-decoration: none;
    position: relative;
}

/* Styles pour le comportement de scroll de la navbar */
.navbar-scroll-transition {
    transition: transform 0.3s ease;
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: white;
}

.navbar-hidden {
    transform: translateY(-100%);
}

/* Corrections pour desktop */
@media (min-width: 992px) {
    .navbar-collapse.offcanvas-collapse {
        display: flex !important;
        position: static;
        visibility: visible;
        width: auto;
        background-color: transparent;
        box-shadow: none;
        transform: none;
        transition: none;
        padding: 0;
    }
    
    .offcanvas-header, .btn-close-menu {
        display: none;
    }
    
    .navbar-collapse .navbar-nav {
        flex-direction: row;
    }
    
    .navbar-collapse .offcanvas-body {
        padding: 0;
        display: flex;
        flex-grow: 1;
    }
}

/* Styles pour mobile */
@media (max-width: 991.98px) {
    /* Navbar mobile plus fine */
    .navbar {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .navbar .navbar-brand {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    
    .navbar .nav-link {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
    }
    
    .navbar .navbar-toggler {
        padding: 0.25rem 0.5rem;
        font-size: 0.9rem;
    }
    
    .navbar-collapse.offcanvas-collapse {
        position: fixed;
        top: 0;
        bottom: 0;
        left: -100%;
        width: 80%;
        padding: 0;
        overflow-y: auto;
        visibility: hidden;
        background-color: white;
        transition: all .3s ease-in-out;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1045;
    }
    
    .navbar-collapse.offcanvas-collapse.show {
        visibility: visible;
        left: 0;
    }
    
    .offcanvas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .offcanvas-title {
        margin: 0;
        font-weight: 600;
    }
    
    .offcanvas-body {
        padding: 1rem;
    }
    
    .offcanvas-collapse .navbar-nav .nav-item {
        margin: 0.5rem 0;
    }
    
    .offcanvas-collapse .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        transition: background-color 0.2s ease;
    }
    
    .offcanvas-collapse .navbar-nav .nav-link:hover {
        background-color: #f8f9fa;
    }
    
    .btn-close-menu {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        color: #6c757d;
        transition: color 0.2s ease;
    }
    
    .btn-close-menu:hover {
        color: #343a40;
    }
    
    .auth-link {
        margin: 5px 0;
        text-align: center;
        width: 100%;
    }
}

.auth-link {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin: 0 5px;
    padding: 8px 20px !important;
    letter-spacing: 0.3px;
    position: relative;
    overflow: hidden;
}

.auth-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.auth-link:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.auth-link i {
    transition: transform 0.3s ease;
}

.auth-link:hover i {
    transform: translateX(3px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter le header et réorganiser le contenu du menu
    const navbarCollapse = document.querySelector('.offcanvas-collapse');
    if (navbarCollapse) {
        // Créer le header
        const header = document.createElement('div');
        header.className = 'offcanvas-header';
        header.innerHTML = `
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close-menu" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Créer le body
        const body = document.createElement('div');
        body.className = 'offcanvas-body';
        
        // Déplacer le contenu existant dans le body
        while (navbarCollapse.firstChild) {
            body.appendChild(navbarCollapse.firstChild);
        }
        
        // Ajouter le header et le body au menu
        navbarCollapse.appendChild(header);
        navbarCollapse.appendChild(body);
    }
    
    // Script pour cacher/afficher la navbar lors du scroll sur mobile
    if (window.innerWidth < 992) {
        const navbar = document.querySelector('.navbar');
        let lastScrollTop = 0;
        
        // Ajouter une classe pour la transition
        navbar.classList.add('navbar-scroll-transition');
        
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Si on scroll vers le bas et qu'on a dépassé 50px, on cache la navbar
            if (scrollTop > lastScrollTop && scrollTop > 50) {
                navbar.classList.add('navbar-hidden');
            } 
            // Si on scroll vers le haut, on affiche la navbar
            else if (scrollTop < lastScrollTop) {
                navbar.classList.remove('navbar-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }
});
</script>
