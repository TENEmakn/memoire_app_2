<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Connexion - CGV MOTORS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="{{ asset('css/register.css') }}" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }
  </style>
</head>
<body>
  
  <div class="loader-container" id="loader">
    <div class="d-flex flex-column align-items-center">
      <div class="loader"></div>
      <div class="loading-text">Connexion en cours...</div>
    </div>
  </div>

  <div class="container-fluid d-flex justify-content-center align-items-center p-0" style="height: 100%;">
    <div class="card">
      <a href="{{ route('index') }}" class="close-button">×</a>
      <div class="brand-title">
        <div class="top">CGV</div>
        <div class="bottom">MOTORS</div>
      </div>
      <h5 class="text-center mb-3">Connexion</h5>
      
      @if ($errors->any())
      <div class="alert alert-danger py-1 mb-2">
          <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif
      
      <form action="{{ route('auth.login.submit') }}" method="POST" id="loginForm">
        @csrf
        <h6 class="step-title"><i class="bi bi-shield-lock"></i> Identifiez-vous</h6>
        
        <div class="mb-2">
          <label for="email" class="form-label">Email</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Votre adresse email" required>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="password" class="form-label">Mot de Passe</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        
        <div class="d-grid mb-2">
          <button type="submit" class="btn btn-primary">Se connecter <i class="bi bi-box-arrow-in-right"></i></button>
        </div>
        
        <div class="text-center mb-2">
          <a href="#" class="small text-decoration-none">Mot de passe oublié ?</a>
        </div>
      </form>
      
      <div class="small-text">
        Vous n'avez pas de compte ?
        <a href="{{ route('auth.register') }}">Créez un compte</a>
      </div>
    </div>
  </div>

  <script>
    // Fonction pour ajuster la hauteur pour les appareils mobiles
    function adjustHeight() {
      const vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);
      
      // Forcer le recentrage après un court délai pour permettre au navigateur de s'adapter
      setTimeout(function() {
        window.scrollTo(0, 0);
      }, 50);
    }
    
    // Exécuter l'ajustement au chargement et au redimensionnement
    window.addEventListener('load', adjustHeight);
    window.addEventListener('resize', adjustHeight);
    window.addEventListener('orientationchange', function() {
      // Attendre que l'orientation soit complètement changée
      setTimeout(adjustHeight, 200);
    });
    
    // Désactiver le défilement sur les appareils mobiles
    document.body.addEventListener('touchmove', function(e) {
      if (e.target.tagName !== 'INPUT') {
        e.preventDefault();
      }
    }, { passive: false });
    
    // Gestion du loader lors de la soumission du formulaire
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      // Validation basique du formulaire
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      
      if (!email || !password) {
        alert('Veuillez remplir tous les champs obligatoires');
        e.preventDefault();
        return;
      }
      
      // Validation basique de l'email
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        alert('Veuillez entrer une adresse email valide');
        e.preventDefault();
        return;
      }
      
      // Afficher le loader
      document.getElementById('loader').style.display = 'flex';
    });
    
    // Afficher/masquer le mot de passe
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', function() {
        const input = this.closest('.input-group').querySelector('input');
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('bi-eye');
          icon.classList.add('bi-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('bi-eye-slash');
          icon.classList.add('bi-eye');
        }
      });
    });
  </script>
</body>
</html>
