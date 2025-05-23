@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/error-pages.css') }}">
@endsection

@section('content')
<div class="error-container">
    <div class="error-code animate__animated animate__fadeIn">404</div>
    <h1 class="error-message animate__animated animate__fadeIn animate__delay-1s">Page introuvable</h1>
    <div class="error-image animate__animated animate__fadeIn animate__delay-1s">
        <i class="fas fa-car-crash fa-5x text-danger"></i>
    </div>
    <p class="error-description animate__animated animate__fadeIn animate__delay-2s">
        Oups ! La page que vous recherchez semble avoir pris un mauvais virage. 
        Elle n'existe pas ou a été déplacée.
    </p>
    <a href="{{ route('index') }}" class="btn btn-danger btn-home animate__animated animate__fadeIn animate__delay-2s">
        <i class="fas fa-home me-2"></i>Retour à l'accueil
    </a>
</div>
@endsection

@section('scripts')
<script>
    document.title = "404 - Page introuvable | {{ config('app.name', 'Motors') }}";
</script>
@endsection
