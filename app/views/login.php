<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Takalo-takalo</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/bootstrap-icons/bootstrap-icons.css">
    <!-- Login Styles -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/style/login.css">
</head>
<body class="login-page">
    <div class="container">
        <div class="login-container">
            <div class="login-card card">
                <div class="login-header">
                    <div class="logo-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h2>Takalo-takalo</h2>
                    <p>Connectez-vous pour échanger vos objets</p>
                </div>
                
                <div class="login-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger login-alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success login-alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= BASE_PATH ?>/login" id="loginForm">
                        <!-- Username -->
                        <div class="mb-4">
                            <label class="form-label login-label">
                                <i class="bi bi-person-fill me-1"></i> Nom d'utilisateur
                            </label>
                            <div class="input-group">
                                <span class="input-group-text login-input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       name="username" 
                                       class="form-control login-form-control" 
                                       placeholder="Entrez votre nom d'utilisateur"
                                       required
                                       autocomplete="username">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label login-label">
                                <i class="bi bi-lock-fill me-1"></i> Mot de passe
                            </label>
                            <div class="input-group">
                                <span class="input-group-text login-input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       name="password" 
                                       class="form-control login-form-control" 
                                       placeholder="Entrez votre mot de passe"
                                       required
                                       autocomplete="current-password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-login" id="btnLogin">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                <span id="btnText">Se connecter</span>
                                <span id="btnSpinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Connexion...
                                </span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="login-footer">
                        <small>
                            <i class="bi bi-shield-check me-1"></i>
                            Connexion sécurisée
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= BASE_PATH ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animation du bouton de submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
        });
    </script>
</body>
</html>
