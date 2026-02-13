<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Takalo-takalo</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/bootstrap-icons/bootstrap-icons.css">
    <!-- Login Styles (réutilisé pour l'inscription) -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/style/login.css">
</head>
<body class="login-page">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-container w-100">
            <div class="login-card card">
                <div class="login-header">
                    <div class="logo-icon">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h2>Takalo-takalo</h2>
                    <p>Créez votre compte pour commencer à échanger</p>
                </div>
                
                <div class="login-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger login-alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
                        <div class="alert alert-danger login-alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($_SESSION['errors'] as $err): ?>
                                    <?php if ($err !== ''): ?>
                                        <li><?= htmlspecialchars($err) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                            <?php unset($_SESSION['errors']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success login-alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= BASE_PATH ?>/register" id="registerForm" enctype="multipart/form-data">
                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label login-label">
                                <i class="bi bi-person-fill me-1"></i> Nom d'utilisateur <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text login-input-icon">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       name="username" 
                                       class="form-control login-input" 
                                       placeholder="Choisissez un nom d'utilisateur"
                                       value="<?= htmlspecialchars($_SESSION['old']['username'] ?? '') ?>"
                                       required
                                       autocomplete="username"
                                       minlength="3">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label login-label">
                                <i class="bi bi-lock-fill me-1"></i> Mot de passe <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text login-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       name="password" 
                                       class="form-control login-input" 
                                       placeholder="Minimum 4 caractères"
                                       required
                                       autocomplete="new-password"
                                       minlength="4">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label login-label">
                                <i class="bi bi-lock-fill me-1"></i> Confirmer le mot de passe <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text login-input-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password" 
                                       name="password_confirm" 
                                       class="form-control login-input" 
                                       placeholder="Retapez votre mot de passe"
                                       required
                                       autocomplete="new-password"
                                       minlength="4">
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div class="mb-4">
                            <label class="form-label login-label">
                                <i class="bi bi-camera-fill me-1"></i> Photo de profil <small class="text-muted">(optionnel)</small>
                            </label>
                            <input type="file" 
                                   name="pdp" 
                                   class="form-control login-input" 
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-login" id="btnRegister">
                                <i class="bi bi-person-plus-fill me-2"></i>
                                <span id="btnText">Créer mon compte</span>
                                <span id="btnSpinner" class="d-none">
                                    <span class="spinner-border login-spinner me-2"></span>
                                    Inscription...
                                </span>
                            </button>
                        </div>
                    </form>

                    <?php if (isset($_SESSION['old'])) unset($_SESSION['old']); ?>
                    
                    <div class="login-footer">
                        <small>
                            Vous avez déjà un compte ?
                            <a href="<?= BASE_PATH ?>/login" class="login-link">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Se connecter
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= BASE_PATH ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnRegister');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
        });
    </script>
</body>
</html>
