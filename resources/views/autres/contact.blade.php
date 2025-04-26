@extends('layouts.app')

@section('content')
<section class="contact-hero py-5 mt-1" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.4)), url('{{ asset('images/v10.png') }}'); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="text-white display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Contactez-nous</h1>
                <p class="text-white lead mb-0 animate__animated animate__fadeInUp">Notre équipe est à votre écoute pour vous offrir le meilleur service</p>
                <div class="mt-4 animate__animated animate__fadeIn animate__delay-1s">
                    <a href="#contact-form" class="btn btn-primary btn-lg rounded-pill px-4 py-2 shadow-sm">
                        <i class="fas fa-envelope me-2"></i> Nous écrire
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-form-section py-5" id="contact-form">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="contact-info bg-white p-4 rounded-lg shadow h-100 border-top border-primary border-5">
                    <h3 class="mb-4 fw-bold text-primary">Informations de contact</h3>
                    
                    <div class="mb-4 contact-item">
                        <h5 class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Adresse</h5>
                        <p class="text-muted ms-4">Cocody, Rue des Jardins<br>Abidjan, Côte d'Ivoire</p>
                    </div>
                    
                    <div class="mb-4 contact-item">
                        <h5 class="fw-bold"><i class="fas fa-phone me-2 text-primary"></i> Téléphone</h5>
                        <p class="text-muted ms-4">+225 07 01 02 48 87 57</p>
                    </div>
                    
                    <div class="mb-4 contact-item">
                        <h5 class="fw-bold"><i class="fas fa-envelope me-2 text-primary"></i> Email</h5>
                        <p class="text-muted ms-4">contact@motors.ci</p>
                    </div>
                    
                    <div class="mb-4 contact-item">
                        <h5 class="fw-bold"><i class="fas fa-clock me-2 text-primary"></i> Horaires d'ouverture</h5>
                        <div class="ms-4">
                            <div class="d-flex justify-content-between text-muted">
                                <span>Lundi - Vendredi:</span>
                                <span>8h00 - 18h00</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Samedi:</span>
                                <span>9h00 - 16h00</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Dimanche:</span>
                                <span>Fermé</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-media mb-4">
                        <h5 class="fw-bold"><i class="fas fa-share-alt me-2 text-primary"></i> Réseaux sociaux</h5>
                        <div class="d-flex ms-4 mt-3">
                            <a href="#" class="social-icon me-3" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon me-3" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon me-3" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="car-image text-center d-none d-md-block mt-4">
                        <img src="{{ asset('images/V10.png') }}" alt="Voiture de luxe" class="img-fluid floating-car" style="max-height: 150px;">
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="contact-form bg-white p-4 rounded-lg shadow border-top border-primary border-5">
                    <h3 class="mb-4 fw-bold text-primary">Envoyez-nous un message</h3>
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-pill" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-pill" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 input-group-custom">
                                    <label for="nom" class="form-label">Nom complet</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                                    </div>
                                    @error('nom')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3 input-group-custom">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 input-group-custom">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                    </div>
                                    @error('telephone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3 input-group-custom">
                                    <label for="sujet" class="form-label">Sujet</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control @error('sujet') is-invalid @enderror" id="sujet" name="sujet" value="{{ old('sujet') }}" required>
                                    </div>
                                    @error('sujet')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 input-group-custom">
                            <label for="message" class="form-label">Message</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white h-100"><i class="fas fa-comment"></i></span>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm btn-hover-effect">
                                <i class="fas fa-paper-plane me-2"></i> Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="faq-section mt-4 bg-white p-4 rounded-lg shadow border-top border-primary border-5">
                    <h4 class="fw-bold text-primary mb-3">Questions fréquentes</h4>
                    
                    <div class="accordion" id="accordionFAQ">
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Comment réserver un véhicule ?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Pour réserver un véhicule, connectez-vous à votre compte, sélectionnez le véhicule souhaité et suivez les étapes indiquées pour finaliser votre réservation.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header" id="headingfore">
                                <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefore" aria-expanded="false" aria-controls="collapsefore">
                                    Comment acheter un véhicule ?
                                </button>
                            </h2>
                            <div id="collapsefore" class="accordion-collapse collapse" aria-labelledby="headingfore" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Pour acheter un véhicule, connectez-vous à votre compte, sélectionnez le véhicule souhaité et suivez les étapes indiquées pour prendre un rendez-vous avec un conseiller et finaliser votre achat.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Quels sont les documents requis pour louer un véhicule ?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Vous devez fournir une pièce d'identité valide, Ce document peut être téléchargé dans votre profil dans la section documents.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Comment annuler une réservation ?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Vous pouvez annuler votre réservation en vous rendant dans la section "Mes réservations" de votre compte et en cliquant sur le bouton "Annuler".
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map-section mt-3 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="map-container rounded-lg shadow overflow-hidden">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.213369604695!2d-3.995510525859709!3d5.384412194594557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfc19500410b406b%3A0x9d93687280782bb!2sCGV%20MOTORS!5e0!3m2!1sfr!2sci!4v1745442207131!5m2!1sfr!2sci" 
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styles généraux */
    .rounded-lg {
        border-radius: 15px;
    }
    
    /* Styles du formulaire */
    .contact-form .form-control {
        border-radius: 0 5px 5px 0;
        padding: 12px 15px;
        font-size: 14px;
        border: 1px solid #e0e0e0;
        height: auto;
    }
    
    .contact-form .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-color: #0d6efd;
    }
    
    .contact-form textarea.form-control {
        height: auto;
    }
    
    .input-group-text {
        border-radius: 5px 0 0 5px;
        width: 40px;
        justify-content: center;
    }
    
    /* Animation et effets */
    .contact-info, .contact-form, .faq-section {
        transition: all 0.3s ease;
    }
    
    .contact-info:hover, .contact-form:hover, .faq-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .contact-item {
        transition: all 0.3s ease;
    }
    
    .contact-item:hover {
        transform: translateX(5px);
    }
    
    .floating-car {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    /* Styles de la carte */
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    /* Style du hero */
    .contact-hero {
        position: relative;
        background-attachment: fixed;
    }
    
    /* Style des icônes sociales */
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: #0d6efd;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        background-color: #0d6efd;
        color: white;
        transform: translateY(-3px);
    }
    
    /* Style des accordions */
    .accordion-button {
        padding: 15px;
        background-color: #f8f9fa;
        box-shadow: none !important;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
        color: #0d6efd;
    }
    
    .accordion-body {
        padding: 15px;
        background-color: #f8f9fa;
    }
    
    /* Effet de survol sur le bouton */
    .btn-hover-effect {
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .btn-hover-effect:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.2);
        z-index: -2;
    }
    
    .btn-hover-effect:before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
        z-index: -1;
    }
    
    .btn-hover-effect:hover {
        transform: translateY(-3px);
    }
    
    .btn-hover-effect:hover:before {
        width: 100%;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection 
