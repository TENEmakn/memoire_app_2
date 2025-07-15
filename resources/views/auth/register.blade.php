<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Inscription - CGV Motors</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/register.css') }}" rel="stylesheet">
  <meta name="theme-color" content="#00cfff">
</head>
<body>

  <div class="loader-container" id="loader">
    <div class="d-flex flex-column align-items-center">
      <div class="loader"></div> 
      <div class="loading-text">Inscription en cours...</div>
      <div class="loading-subtext mt-2">Veuillez patienter pendant que nous créons votre compte...</div>
    </div>
  </div>

  <div class="card">
    <a href="{{ route('index') }}" class="close-button" title="Retour à l'accueil">×</a>
    <div class="brand-title">
      <img src="{{ asset('images/cgvmotors-logo.png') }}" alt="CGV Motors" class="auth-logo" style="max-height: 40px;">
    </div>
    <h5 class="text-center mb-3"><i class="bi bi-person-plus-fill me-2"></i>Créez votre compte</h5>

    @if ($errors->any())
    <div class="alert alert-danger py-2 mb-3">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Veuillez corriger les erreurs suivantes:</strong>
        </div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('auth.register.submit') }}" method="POST" id="registerForm">
      @csrf
      
      <div class="form-steps mb-3">
        <div class="step active" id="step1-indicator"><i class="bi bi-person-badge me-1"></i>1. Identité</div>
        <div class="step" id="step2-indicator"><i class="bi bi-envelope me-1"></i>2. Contact</div>
        <div class="step" id="step3-indicator"><i class="bi bi-house me-1"></i>3. Domicile</div>
        <div class="step" id="step4-indicator"><i class="bi bi-shield-lock me-1"></i>4. Sécurité</div>
      </div>
      
      <div class="step-content" id="step1">
        <h6 class="step-title"><i class="bi bi-person-badge"></i> Informations personnelles</h6>
        
        <div class="row g-2 mb-2">
          <div class="col-md-6">
            <label for="name" class="form-label">Nom</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Votre nom" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="firstname" class="form-label">Prénom</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text"><i class="bi bi-person-plus"></i></span>
              <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') }}" placeholder="Votre prénom" required>
            </div>
          </div>
        </div>
        
        <div class="mb-2">
          <label for="birthdate" class="form-label">Date de naissance</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
            <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
          </div>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
          <button type="button" class="btn btn-primary next-step" data-next="step2">Suivant <i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
      
      <div class="step-content d-none" id="step2">
        <h6 class="step-title"><i class="bi bi-envelope-check"></i> Informations de contact</h6>
        
        <div class="mb-2">
          <label for="email" class="form-label">Email</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Votre adresse email" required>
          </div>
        </div>
        
        <div class="mb-2">
          <label for="phone" class="form-label">Téléphone</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Votre numéro de téléphone" required>
          </div>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step1"><i class="bi bi-arrow-left me-1"></i> Précédent</button>
          <button type="button" class="btn btn-primary next-step" data-next="step3">Suivant <i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
      
      <div class="step-content d-none" id="step3">
        <h6 class="step-title"><i class="bi bi-house"></i> Domicile</h6>
        
        <div class="mb-2">
          <label for="commune" class="form-label">Commune</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-map"></i></span>
            <input type="text" class="form-control" id="commune" name="commune" value="{{ old('commune') }}" placeholder="Votre commune" required>
          </div>
        </div>
        
        <div class="mb-2">
          <label for="ville" class="form-label">Ville</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-building"></i></span>
            <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville') }}" placeholder="Votre ville" required>
          </div>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step2"><i class="bi bi-arrow-left me-1"></i> Précédent</button>
          <button type="button" class="btn btn-primary next-step" data-next="step4">Suivant <i class="bi bi-arrow-right ms-1"></i></button>
        </div>
      </div>
      
      <div class="step-content d-none" id="step4">
        <h6 class="step-title"><i class="bi bi-shield-lock"></i> Sécurité du compte</h6>
        
        <div class="mb-2">
          <label for="password" class="form-label">Mot de Passe</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1"><i class="bi bi-eye"></i></button>
          </div>
          <div class="password-strength mt-1 d-none" id="passwordStrength">
            <div class="progress" style="height: 5px;">
              <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted">Force du mot de passe: <span id="strengthText">Faible</span></small>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirmer Mot de Passe</label>
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="termsCheck" required>
          <label class="form-check-label small" for="termsCheck">
            J'accepte les <a href="#" class="text-decoration-none">conditions d'utilisation</a> et la <a href="#" class="text-decoration-none">politique de confidentialité</a>
          </label>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
          <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step3"><i class="bi bi-arrow-left me-1"></i> Précédent</button>
          <button type="submit" class="btn btn-primary">S'inscrire <i class="bi bi-check2-circle ms-1"></i></button>
        </div>
      </div>
    </form>
    
    <div class="small-text mt-4">
      <i class="bi bi-question-circle me-1"></i> Vous avez déjà un compte ?
      <a href="{{ route('auth.login') }}" class="ms-1">Connectez-vous ici</a>
    </div>
  </div>

  <script>
    // Ajustement de la hauteur pour les appareils mobiles
    function adjustHeight() {
      const vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    window.addEventListener('load', adjustHeight);
    window.addEventListener('resize', adjustHeight);
    
    // Navigation entre les étapes
    document.querySelectorAll('.next-step').forEach(button => {
      button.addEventListener('click', function() {
        const nextStep = this.getAttribute('data-next');
        const currentStep = this.closest('.step-content').id;
        
        // Validate current step
        if (currentStep === 'step1') {
          const name = document.getElementById('name').value;
          const firstname = document.getElementById('firstname').value;
          const birthdate = document.getElementById('birthdate').value;
          
          if (!name || !firstname || !birthdate) {
            alert('Veuillez remplir tous les champs de cette étape');
            return;
          }
        } else if (currentStep === 'step2') {
          const email = document.getElementById('email').value;
          const phone = document.getElementById('phone').value;
          
          if (!email || !phone) {
            alert('Veuillez remplir tous les champs de cette étape');
            return;
          }
          
          // Basic email validation
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(email)) {
            alert('Veuillez entrer une adresse email valide');
            return;
          }
        } else if (currentStep === 'step3') {
          const commune = document.getElementById('commune').value;
          const ville = document.getElementById('ville').value;
          
          if (!commune || !ville) {
            alert('Veuillez remplir tous les champs de cette étape');
            return;
          }
        }
        
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(step => {
          step.classList.add('d-none');
        });
        
        // Show next step
        document.getElementById(nextStep).classList.remove('d-none');
        
        // Update indicators
        document.querySelectorAll('.step').forEach(indicator => {
          indicator.classList.remove('active');
        });
        document.getElementById(nextStep + '-indicator').classList.add('active');
      });
    });
    
    document.querySelectorAll('.prev-step').forEach(button => {
      button.addEventListener('click', function() {
        const prevStep = this.getAttribute('data-prev');
        
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(step => {
          step.classList.add('d-none');
        });
        
        // Show previous step
        document.getElementById(prevStep).classList.remove('d-none');
        
        // Update indicators
        document.querySelectorAll('.step').forEach(indicator => {
          indicator.classList.remove('active');
        });
        document.getElementById(prevStep + '-indicator').classList.add('active');
      });
    });
    
    // Gestion du loader lors de la soumission du formulaire
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault(); // Prevent default for validation
      
      // Validation basique du formulaire
      const name = document.getElementById('name').value;
      const firstname = document.getElementById('firstname').value;
      const email = document.getElementById('email').value;
      const phone = document.getElementById('phone').value;
      const birthdate = document.getElementById('birthdate').value;
      const commune = document.getElementById('commune').value;
      const ville = document.getElementById('ville').value;
      const password = document.getElementById('password').value;
      const passwordConfirmation = document.getElementById('password_confirmation').value;
      const termsCheck = document.getElementById('termsCheck').checked;
      
      if (!name || !firstname || !email || !phone || !birthdate || !commune || !ville || !password || !passwordConfirmation) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
      }
      
      if (password !== passwordConfirmation) {
        alert('Les mots de passe ne correspondent pas');
        return;
      }
      
      if (!termsCheck) {
        alert('Veuillez accepter les conditions d\'utilisation');
        return;
      }
      
      // Afficher le loader
      document.getElementById('loader').style.display = 'flex';
      
      // Soumettre le formulaire
      this.submit();
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
    
    // Force du mot de passe
    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;
      const strength = calculatePasswordStrength(password);
      
      const strengthDiv = document.getElementById('passwordStrength');
      const progressBar = strengthDiv.querySelector('.progress-bar');
      const strengthText = document.getElementById('strengthText');
      
      if (password.length > 0) {
        strengthDiv.classList.remove('d-none');
        
        progressBar.style.width = strength.score + '%';
        progressBar.className = 'progress-bar';
        
        if (strength.score < 30) {
          progressBar.classList.add('bg-danger');
          strengthText.textContent = 'Faible';
        } else if (strength.score < 60) {
          progressBar.classList.add('bg-warning');
          strengthText.textContent = 'Moyen';
        } else {
          progressBar.classList.add('bg-success');
          strengthText.textContent = 'Fort';
        }
      } else {
        strengthDiv.classList.add('d-none');
      }
    });
    
    function calculatePasswordStrength(password) {
      let score = 0;
      
      // Longueur minimum
      if (password.length >= 8) score += 20;
      
      // Caractères spéciaux
      if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score += 20;
      
      // Lettres minuscules
      if (/[a-z]/.test(password)) score += 15;
      
      // Lettres majuscules
      if (/[A-Z]/.test(password)) score += 15;
      
      // Chiffres
      if (/[0-9]/.test(password)) score += 15;
      
      // Longueur supplémentaire
      if (password.length > 10) score += 15;
      
      return {
        score: Math.min(score, 100)
      };
    }
  </script>
</body>
</html>
