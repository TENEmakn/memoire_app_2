@extends('layouts.app')

@section('content')
<div class="about-page">
    <!-- Hero Section -->
    <div class="hero-section position-relative overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-info mb-4 animate__animated animate__fadeInLeft">Notre Histoire</h1>
                    <p class="lead text-dark mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                        CGV Motors, votre partenaire de confiance dans le monde de l'automobile depuis plus de 10 ans.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="values-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center text-info mb-5">Nos Valeurs</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-handshake fa-3x text-info mb-3"></i>
                            <h3 class="h5 mb-3">Confiance</h3>
                            <p class="text-muted">Nous construisons des relations durables basées sur la transparence et l'honnêteté.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-star fa-3x text-info mb-3"></i>
                            <h3 class="h5 mb-3">Excellence</h3>
                            <p class="text-muted">Nous visons l'excellence dans chaque aspect de notre service.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h3 class="h5 mb-3">Service Client</h3>
                            <p class="text-muted">Votre satisfaction est notre priorité absolue.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section py-5">
        <div class="container">
            <h2 class="text-center text-info mb-5">Notre Équipe</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="team-member text-center">
                        <div class="position-relative mb-4">
                            <img src="{{ asset('images/tene.jpg') }}" alt="Team Member" class="rounded-circle img-fluid" style="width: 200px; height: 200px;">
                            <div class="social-overlay">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="#" class="btn btn-info btn-sm rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#" class="btn btn-info btn-sm rounded-circle"><i class="fab fa-twitter"></i></a>
                                </div>
                            </div>
                        </div>
                        <h4 class="h5 mb-1">Kone Tenemakan</h4>
                        <p class="text-muted">Concepteur et developpeur de l'application web</p>
                    </div>
                </div>
                <!-- Add more team members as needed -->
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section py-5 bg-info bg-opacity-10">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold text-info mb-2">10+</h3>
                        <p class="text-muted">Années d'expérience</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold text-info mb-2">1000+</h3>
                        <p class="text-muted">Clients satisfaits</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold text-info mb-2">500+</h3>
                        <p class="text-muted">Véhicules vendus</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold text-info mb-2">50+</h3>
                        <p class="text-muted">Employés dévoués</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%);
    }

    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .team-member .social-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(13, 202, 240, 0.9);
        padding: 1rem;
        opacity: 0;
        transition: all 0.3s ease;
        border-radius: 0 0 50% 50%;
    }

    .team-member:hover .social-overlay {
        opacity: 1;
    }

    .stat-item {
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: scale(1.05);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate__animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }

    .animate__fadeInLeft {
        animation-name: fadeInLeft;
    }

    .animate__fadeInRight {
        animation-name: fadeInRight;
    }

    .animate__delay-1s {
        animation-delay: 1s;
    }
</style>

<!-- Add Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection 