<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un compte - Pneumatique Aqabli</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* الفيديو الخلفية */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Overlay داكن */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .company-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus, .form-select:focus {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 0.3rem rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-select option {
            background: #2c3e50;
            color: white;
        }

        .form-label {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-text {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 0.85rem;
        }

        .btn-register {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-register:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.2));
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .login-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: white;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            backdrop-filter: blur(10px);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: white;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: white;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .password-strength {
            height: 5px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.2);
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .password-weak { background: #e74a3b; width: 25%; }
        .password-medium { background: #f6c23e; width: 50%; }
        .password-strong { background: #1cc88a; width: 75%; }
        .password-very-strong { background: #1cc88a; width: 100%; }

        .input-group-text {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        /* تحسين للجوال */
        @media (max-width: 768px) {
            .video-background {
                display: none;
            }
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .register-card {
                margin: 1rem;
                padding: 1.5rem !important;
            }
        }
    </style>
</head>

<body>
    <!-- فيديو الخلفية -->
    <video class="video-background" autoplay muted loop playsinline>
        <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
    </video>

    <div class="overlay"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="register-card p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="company-logo">
                            <i class="fas fa-tire"></i>
                        </div>
                        <h3 class="mb-2">Pneumatique Aqabli</h3>
                        <p class="text-muted mb-0">Créez votre compte</p>
                    </div>

                    <!-- Messages d'erreur -->
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                            <h6 class="mb-0">Veuillez corriger les erreurs suivantes:</h6>
                        </div>
                        <ul class="mb-0 ps-4">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Message de succès -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row">
                            <!-- Nom complet -->
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">
                                    Nom complet <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}" 
                                           placeholder="Votre nom complet"
                                           required 
                                           autofocus>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Adresse email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="votre@email.com"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" 
                                           name="telephone" 
                                           value="{{ old('telephone') }}"
                                           placeholder="06 XX XX XX XX">
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Optionnel
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mot de passe -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Mot de passe <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="••••••••"
                                           required>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="password-strength-bar"></div>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Minimum 8 caractères avec majuscules et chiffres
                                </div>
                            </div>

                            <!-- Confirmation -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirmation <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="••••••••"
                                           required>
                                </div>
                                <div class="form-text" id="password-match">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Confirmez votre mot de passe
                                </div>
                            </div>
                        </div>

                        <!-- Bouton d'inscription -->
                        <div class="d-grid mb-4 mt-3">
                            <button type="submit" class="btn btn-register btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Créer le compte
                            </button>
                        </div>

                        <!-- Lien de connexion -->
                        <div class="text-center">
                            <p class="mb-0">
                                Déjà un compte? 
                                <a href="{{ route('login') }}" class="login-link">
                                    <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="text-center mt-4 pt-3 border-top border-white border-opacity-25">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Système de gestion intégré - Stock & Crédits
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('password-match');
        const strengthBar = document.querySelector('.password-strength-bar');

        // Vérification du mot de passe
        function validatePassword() {
            if (confirmPassword.value === '') {
                passwordMatch.innerHTML = '<i class="fas fa-info-circle me-1"></i>Confirmez votre mot de passe';
                passwordMatch.className = 'form-text';
                confirmPassword.style.borderColor = 'rgba(255, 255, 255, 0.3)';
            } else if (password.value === confirmPassword.value) {
                passwordMatch.innerHTML = '<i class="fas fa-check-circle me-1"></i>Les mots de passe correspondent';
                passwordMatch.style.color = '#1cc88a';
                confirmPassword.style.borderColor = 'rgba(28, 200, 138, 0.6)';
                confirmPassword.setCustomValidity('');
            } else {
                passwordMatch.innerHTML = '<i class="fas fa-times-circle me-1"></i>Les mots de passe ne correspondent pas';
                passwordMatch.style.color = '#e74a3b';
                confirmPassword.style.borderColor = 'rgba(231, 74, 59, 0.6)';
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            }
        }

        // Force du mot de passe
        password.addEventListener('input', function(e) {
            const pwd = e.target.value;
            let strength = 0;
            
            if (pwd.length >= 8) strength++;
            if (pwd.match(/[a-z]/)) strength++;
            if (pwd.match(/[A-Z]/)) strength++;
            if (pwd.match(/[0-9]/)) strength++;
            if (pwd.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar ';
            if (strength <= 2) {
                strengthBar.classList.add('password-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('password-medium');
            } else if (strength <= 4) {
                strengthBar.classList.add('password-strong');
            } else {
                strengthBar.classList.add('password-very-strong');
            }
            
            validatePassword();
        });

        confirmPassword.addEventListener('input', validatePassword);

        // Format téléphone
        const telephone = document.getElementById('telephone');
        telephone.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+\s-]/g, '');
        });

        // Contrôle vidéo pour mobile
        const video = document.querySelector('.video-background');
        if (window.innerWidth <= 768 && video) {
            video.style.display = 'none';
        }

        // Validation du formulaire
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.focus();
                return false;
            }
        });
    });
    </script>
</body>

</html>