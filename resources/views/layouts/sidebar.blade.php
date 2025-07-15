<!-- Sidebar -->
<div class="sidebar-container">
    <!-- Mobile Navbar -->
    <div class="mobile-navbar d-lg-none d-flex justify-content-between align-items-center bg-white p-3 shadow-sm">
        <div class="mobile-logo">
            <img src="{{ asset('images/cgvmotors-logo.png') }}" alt="CGV Motors" class="logo-img-mobile" style="max-height: 40px;">
            <a href="{{ route('index') }}" class="text-decoration-none text-dark">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
        <div class="d-flex align-items-center">
            <div class="position-relative me-3">
                <i class="fas fa-bell text-muted"></i>
                @php
                    $usersWithUnverifiedPieces = \App\Models\User::whereNotNull('image_piece_recto')
                        ->whereNotNull('image_piece_verso')
                        ->where('piece_verifie', false)
                        ->count();
                    $locationEnAttenteCount = \App\Models\LocationRequest::where('statut', 'en_attente')->count();
                    $rdvEnAttenteCount = \App\Models\RendezVousVente::where('statut', 'en_attente')->count();
                    $messageNonLuCount = \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count();
                    $totalNotifications = $usersWithUnverifiedPieces + $locationEnAttenteCount + $rdvEnAttenteCount + $messageNonLuCount;
                @endphp
                @if($totalNotifications > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger pulse-animation">
                    {{ $totalNotifications }}
                </span>
                @endif
            </div>
            <button class="btn border-0 menu-toggle-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Desktop Sidebar -->
    <div class="sidebar bg-white position-fixed h-100 shadow-sm d-none d-lg-block">
        <!-- Logo -->
        <div class="p-4 text-center border-bottom logo-container">
            <img src="{{ asset('images/cgvmotors-logo.png') }}" alt="CGV Motors" class="logo-img-sidebar" style="max-height: 40px;">
            <a href="{{ route('index') }}" class="text-decoration-none text-dark">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-4 sidebar-nav">
            <ul class="nav flex-column">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="#" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('dashboard*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#dashboardSubMenu" aria-expanded="{{ request()->routeIs('dashboard*') ? 'true' : 'false' }}">
                        <div class="sidebar-icon-wrapper me-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span>Tableau de bord</span>
                        <i class="fas fa-chevron-down ms-auto transition-transform"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('dashboard*') ? 'show' : '' }}" id="dashboardSubMenu">
                        <ul class="nav flex-column ms-4 mt-2 submenu-items">
                            <li class="nav-item">
                                <a href="{{ route('admin.index') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.overview') ? 'active' : '' }}">
                                    <i class="fas fa-home me-2 submenu-icon"></i>
                                    Vue générale
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.benefices') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line me-2 submenu-icon"></i>
                                    Entrées
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.depense') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                                    <i class="fas fa-money-bill me-2 submenu-icon"></i>
                                    Dépenses
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.stats') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar me-2 submenu-icon"></i>
                                    Statistiques
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Véhicules -->
                <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('users.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#usersSubMenu" aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}">
                        <div class="sidebar-icon-wrapper me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <span>Utilisateurs</span>
                        @php
                            $usersWithUnverifiedPieces = \App\Models\User::whereNotNull('image_piece_recto')
                                ->whereNotNull('image_piece_verso')
                                ->where('piece_verifie', false)
                                ->count();
                        @endphp
                        @if($usersWithUnverifiedPieces > 0)
                            <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $usersWithUnverifiedPieces }}</span>
                        @endif
                        
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="usersSubMenu">
                        <ul class="nav flex-column ms-4 mt-2 submenu-items">
                            <li class="nav-item">
                                <a href="{{ route('admin.users') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('users.clients') ? 'active' : '' }}">
                                    <i class="fas fa-user-tie me-2 submenu-icon"></i>
                                    Clients
                                    @php
                                        $usersWithUnverifiedPieces = \App\Models\User::whereNotNull('image_piece_recto')
                                            ->whereNotNull('image_piece_verso')
                                            ->where('piece_verifie', false)
                                            ->count();
                                    @endphp
                                    @if($usersWithUnverifiedPieces > 0)
                                        <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $usersWithUnverifiedPieces }}</span>
                                    @endif
                                </a>
                            </li>
                            @if(Auth::user()->status == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('admin.gestionnaire') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('users.managers') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield me-2 submenu-icon"></i>
                                    Gestionnaires
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('admin.chauffeur') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('users.drivers') ? 'active' : '' }}">
                                    <i class="fas fa-user-astronaut me-2 submenu-icon"></i>
                                    Chauffeurs
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Bookings -->
                <li class="nav-item">
                    <a href="{{ route('admin.reservations') }}" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                        <div class="sidebar-icon-wrapper me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span>Locations</span>
                        @php
                            $locationEnAttenteCount = \App\Models\LocationRequest::where('statut', 'en_attente')->count();
                        @endphp
                        @if($locationEnAttenteCount > 0)
                        <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $locationEnAttenteCount }}</span>
                        @endif
                    </a>
                </li>

                <!-- Appointments -->
                <li class="nav-item">
                    <a href="{{ route('admin.rdv') }}" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <div class="sidebar-icon-wrapper me-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span>Rendez-vous</span>
                        @php
                            $rdvEnAttenteCount = \App\Models\RendezVousVente::where('statut', 'en_attente')->count();
                        @endphp
                        @if($rdvEnAttenteCount > 0)
                        <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $rdvEnAttenteCount }}</span>
                        @endif
                    </a>
                </li>

                 <!-- Users -->
                 <li class="nav-item">
                    <a href="{{ route('admin.vehicules') }}" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                        <div class="sidebar-icon-wrapper me-3">
                            <i class="fas fa-car"></i>
                        </div>
                        <span>Véhicules</span>
                    </a>
                </li>

                <!-- Assistance -->
                @if(Auth::user()->status == 'admin')
                <li class="nav-item">
                    <a href="#" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('assistance*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#assistanceSubMenu" aria-expanded="{{ request()->routeIs('assistance*') ? 'true' : 'false' }}">
                        <div class="sidebar-icon-wrapper me-3">
                            <i class="fas fa-headset"></i>
                        </div>
                        <span>Assistances</span>
                        @if(\App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count() > 0)
                            <span class="position-absolute end-0 me-3 badge rounded-pill bg-danger">{{ \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count() }}</span>
                        @endif
                    </a>
                    <div class="collapse {{ request()->routeIs('assistance*') ? 'show' : '' }}" id="assistanceSubMenu">
                        <ul class="nav flex-column ms-4 mt-2 submenu-items">
                            <li class="nav-item">
                                <a href="{{ route('admin.messages') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                                    <i class="fas fa-envelope me-2 submenu-icon"></i>
                                    Messages
                                    @php
                                        $unreadMessagesCount = \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count();
                                    @endphp
                                    @if($unreadMessagesCount > 0)
                                        <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadMessagesCount }}</span>
                                    @endif
                                </a>
                            <!-- </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('admin.annulations.*') ? 'active' : '' }}">
                                    <i class="fas fa-ban me-2 submenu-icon"></i>
                                    Annulations
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item mt-2">
                    <hr class="dropdown-divider mx-3">
                </li>
            </ul>
        </nav>

        <!-- User Profile Section -->
        @auth
        <div class="bottom-0 w-100 p-3 border-top">
            <div class="d-flex align-items-center">
                <a class="user-circle-sidebar me-3" href="#" id="sidebarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
                <div class="user-info">
                    <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
                <div class="dropdown ms-auto">
                    <button class="btn btn-sm btn-light rounded-circle" type="button" id="userOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userOptionsDropdown">
                        <li><a class="dropdown-item py-2" href="{{ route('auth.profil') }}"><i class="fas fa-user me-2 text-primary"></i>Mon profil</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('mes_reservations') }}"><i class="fas fa-calendar-alt me-2 text-primary"></i>Mes Réservations</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('mes_rdv') }}"><i class="fas fa-calendar-alt me-2 text-primary"></i>Mes Rendez-vous</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('auth.logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger py-2" type="submit">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endauth
    </div>
    
    <!-- Offcanvas Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start modern-offcanvas" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold brand-text" id="sidebarOffcanvasLabel">CGV Motors</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="mt-2 sidebar-nav">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('dashboard*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileDashboardSubMenu" aria-expanded="{{ request()->routeIs('dashboard*') ? 'true' : 'false' }}">
                            <div class="sidebar-icon-wrapper me-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span>Tableau de bord</span>
                            <i class="fas fa-chevron-down ms-auto transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('dashboard*') ? 'show' : '' }}" id="mobileDashboardSubMenu">
                            <ul class="nav flex-column ms-4 mt-2 submenu-items">
                                <li class="nav-item">
                                    <a href="{{ route('admin.index') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.overview') ? 'active' : '' }}">
                                        <i class="fas fa-home me-2 submenu-icon"></i>
                                        Vue générale
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.benefices') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                                        <i class="fas fa-chart-line me-2 submenu-icon"></i>
                                        Entrées
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.depense') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill me-2 submenu-icon"></i>
                                        Dépenses
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.stats') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('dashboard.analytics') ? 'active' : '' }}">
                                        <i class="fas fa-chart-bar me-2 submenu-icon"></i>
                                        Statistiques
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Utilisateurs -->
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('users.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileUsersSubMenu" aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}">
                            <div class="sidebar-icon-wrapper me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <span>Utilisateurs</span>
                            @php
                                $usersWithUnverifiedPieces = \App\Models\User::whereNotNull('image_piece_recto')
                                    ->whereNotNull('image_piece_verso')
                                    ->where('piece_verifie', false)
                                    ->count();
                            @endphp
                            @if($usersWithUnverifiedPieces > 0)
                                <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $usersWithUnverifiedPieces }}</span>
                            @endif
                            <i class="fas fa-chevron-down ms-auto transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="mobileUsersSubMenu">
                            <ul class="nav flex-column ms-4 mt-2 submenu-items">
                                <li class="nav-item">
                                    <a href="{{ route('admin.users') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('users.clients') ? 'active' : '' }}">
                                        <i class="fas fa-user-tie me-2 submenu-icon"></i>
                                        Clients
                                        @php
                                            $usersWithUnverifiedPieces = \App\Models\User::whereNotNull('image_piece_recto')
                                                ->whereNotNull('image_piece_verso')
                                                ->where('piece_verifie', false)
                                                ->count();
                                        @endphp
                                        @if($usersWithUnverifiedPieces > 0)
                                            <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $usersWithUnverifiedPieces }}</span>
                                        @endif
                                    </a>
                                </li>
                                @if(Auth::user()->status == 'admin')
                                <li class="nav-item">
                                    <a href="{{ route('admin.gestionnaire') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('users.managers') ? 'active' : '' }}">
                                        <i class="fas fa-user-shield me-2 submenu-icon"></i>
                                        Gestionnaires
                                    </a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('admin.chauffeur') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('users.drivers') ? 'active' : '' }}">
                                        <i class="fas fa-user-astronaut me-2 submenu-icon"></i>
                                        Chauffeurs
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Bookings -->
                    <li class="nav-item">
                        <a href="{{ route('admin.reservations') }}" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                            <div class="sidebar-icon-wrapper me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span>Locations</span>
                            @php
                                $locationEnAttenteCount = \App\Models\LocationRequest::where('statut', 'en_attente')->count();
                            @endphp
                            @if($locationEnAttenteCount > 0)
                            <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $locationEnAttenteCount }}</span>
                            @endif
                        </a>
                    </li>

                    <!-- Appointments -->
                    <li class="nav-item">
                        <a href="{{ route('admin.rdv') }}" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <div class="sidebar-icon-wrapper me-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>Rendez-vous</span>
                            @php
                                $rdvEnAttenteCount = \App\Models\RendezVousVente::where('statut', 'en_attente')->count();
                            @endphp
                            @if($rdvEnAttenteCount > 0)
                            <span class="position-absolute end-0 me-3 badge rounded-pill bg-warning text-dark">{{ $rdvEnAttenteCount }}</span>
                            @endif
                        </a>
                    </li>

                    <!-- Véhicules -->
                    <li class="nav-item">
                        <a href="{{ route('admin.vehicules') }}" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                            <div class="sidebar-icon-wrapper me-3">
                                <i class="fas fa-car"></i>
                            </div>
                            <span>Véhicules</span>
                        </a>
                    </li>

                    <!-- Assistance -->
                    @if(Auth::user()->status == 'admin')
                    <li class="nav-item">
                        <a href="#" class="nav-link d-flex align-items-center px-4 py-3 text-dark hover-bg-light hover-text-primary position-relative {{ request()->routeIs('assistance*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileAssistanceSubMenu" aria-expanded="{{ request()->routeIs('assistance*') ? 'true' : 'false' }}">
                            <div class="sidebar-icon-wrapper me-3">
                                <i class="fas fa-headset"></i>
                            </div>
                            <span>Assistances</span>
                            @if(\App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count() > 0)
                                <span class="position-absolute end-0 me-3 badge rounded-pill bg-danger">{{ \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count() }}</span>
                            @endif
                            <i class="fas fa-chevron-down ms-auto transition-transform"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('assistance*') ? 'show' : '' }}" id="mobileAssistanceSubMenu">
                            <ul class="nav flex-column ms-4 mt-2 submenu-items">
                                <li class="nav-item">
                                    <a href="{{ route('admin.messages') }}" class="nav-link text-dark py-2 ps-4 d-flex align-items-center {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                                        <i class="fas fa-envelope me-2 submenu-icon"></i>
                                        Messages
                                        @php
                                            $unreadMessagesCount = \App\Models\Message::where('statut', 'non_lu')->where('receiver_id', Auth::id())->count();
                                        @endphp
                                        @if($unreadMessagesCount > 0)
                                            <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadMessagesCount }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    <li class="nav-item mt-2">
                        <hr class="dropdown-divider mx-3">
                    </li>
                </ul>
            </nav>
            
            <!-- User Profile Section -->
            @auth
            <div class="border-top mt-auto bottom-0 w-100 p-3">
                <div class="d-flex align-items-center">
                    <a class="user-circle-sidebar me-3" href="#" id="mobileUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </a>
                    <div class="user-info">
                        <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="dropdown ms-auto">
                        <button class="btn btn-sm btn-light rounded-circle" type="button" id="mobileUserOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="mobileUserOptionsDropdown">
                            <li><a class="dropdown-item py-2" href="{{ route('auth.profil') }}"><i class="fas fa-user me-2 text-primary"></i>Mon profil</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('mes_reservations') }}"><i class="fas fa-calendar-alt me-2 text-primary"></i>Mes Réservations</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('mes_rdv') }}"><i class="fas fa-calendar-alt me-2 text-primary"></i>Mes Rendez-vous</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2" type="submit">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </div>
</div>

<!-- CSS Styles -->
<style>
.sidebar-container {
    position: relative;
}

.mobile-navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1030;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
    transition: all 0.3s ease;
}

.mobile-navbar .menu-toggle-btn {
    transition: transform 0.3s ease;
}

.mobile-navbar .menu-toggle-btn:hover {
    transform: rotate(90deg);
}

.sidebar {
    width: 280px;
    z-index: 1040;
    overflow-y: auto;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08) !important;
}

.logo-container {
    transition: all 0.3s ease;
}

.brand-text {
    background: linear-gradient(45deg, #0d6efd, #198754);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    transition: all 0.3s ease;
}

@media (min-width: 992px) {
    .sidebar {
        left: 0;
        top: 0;
        width: 280px;
    }
    
    .content-wrapper {
        margin-left: 280px;
        transition: margin-left 0.3s ease;
    }
    
    body {
        overflow-x: hidden;
    }
}

@media (max-width: 991.98px) {
    body {
        padding-top: 56px;
    }
}

.sidebar-icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background-color: rgba(13, 110, 253, 0.05);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.nav-link {
    transition: all 0.3s ease;
    border-radius: 10px;
    margin: 0.3rem 0.5rem;
    position: relative;
    font-weight: 500;
    z-index: 1;
}

.nav-link.active {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd !important;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.nav-link.active .sidebar-icon-wrapper {
    background-color: rgba(13, 110, 253, 0.2);
    color: #0d6efd;
}

.hover-bg-light:hover {
    background-color: rgba(248, 249, 250, 0.8);
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.nav-link:hover .sidebar-icon-wrapper i {
    animation: iconPop 0.4s ease forwards;
}

@keyframes iconPop {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1.1); }
}

.hover-text-primary:hover {
    color: #0d6efd !important;
}

.nav-link:hover .sidebar-icon-wrapper {
    background-color: rgba(13, 110, 253, 0.1);
    transform: rotate(5deg) scale(1.05);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.1);
}

/* Submenu specific hover effects */
.submenu-items .nav-link {
    position: relative;
    transition: all 0.25s ease;
    border-radius: 8px;
    margin: 0.15rem 0.5rem;
}

.submenu-items .nav-link:hover {
    padding-left: 1.8rem !important;
    background-color: rgba(248, 249, 250, 0.8);
}

.submenu-icon {
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.submenu-items .nav-link:hover .submenu-icon {
    transform: scale(1.2);
    color: #0d6efd;
}

/* User section styling */
.user-circle-sidebar {
    position: relative;
    width: 45px;
    height: 45px;
    font-size: 1.1rem;
    background: linear-gradient(135deg, #0d6efd, #0dcaf0);
    color: white;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
    overflow: hidden;
}

.user-circle-sidebar:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    color: white;
    text-decoration: none;
}

.user-info {
    max-width: 150px;
    overflow: hidden;
}

.user-info h6 {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-info small {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.75rem;
    display: block;
}

/* Badge animations and styling */
.pulse-animation {
    animation: pulse 1.5s infinite;
}

.pulse-slow {
    animation: pulse 2.5s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Better collapse animation */
.collapse {
    transition: all 0.3s ease-out !important;
}

.collapse:not(.show) {
    display: block;
    height: 0 !important;
    overflow: hidden;
    padding: 0;
    margin: 0;
}

.collapse.show {
    height: auto !important;
}

.nav-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

.transition-transform {
    transition: transform 0.3s ease;
    font-size: 0.8rem;
}

/* Offcanvas specific styles */
.offcanvas-body {
    display: flex;
    flex-direction: column;
    height: calc(100% - 56px);
}

.modern-offcanvas {
    border-right: none;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease-in-out !important;
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle submenu on click
    const submenuToggleLinks = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
    
    submenuToggleLinks.forEach(link => {
        link.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            
            const chevronIcon = this.querySelector('.fa-chevron-down');
            if (chevronIcon) {
                chevronIcon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });
    
    // Apply active state based on URL
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href !== '#' && currentPath.includes(href)) {
            link.classList.add('active');
            
            // If in a dropdown, expand the parent dropdown
            const parentCollapse = link.closest('.collapse');
            if (parentCollapse) {
                const parentId = parentCollapse.id;
                const parentLink = document.querySelector(`[data-bs-target="#${parentId}"]`);
                if (parentLink) {
                    parentLink.setAttribute('aria-expanded', 'true');
                    parentCollapse.classList.add('show');
                    
                    const chevronIcon = parentLink.querySelector('.fa-chevron-down');
                    if (chevronIcon) {
                        chevronIcon.style.transform = 'rotate(180deg)';
                    }
                }
            }
        }
    });
});</script>
