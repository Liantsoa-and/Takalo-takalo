<?php
    // Définitions par défaut pour éviter Undefined variable
    $usersList = isset($utilisateur) ? $utilisateur : (isset($users) ? $users : []);
    $count = count($usersList);
    $adminName = isset($adminName) ? $adminName : 'Admin Demo';
    $adminInitials = strtoupper(substr($adminName, 0, 1));
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Modern font */
        body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-weight: 600; }
        .profile-placeholder { width:40px; height:40px; border-radius:50%; }
        .bi { vertical-align: -.125em; }
        #sidebar { position:sticky; top:0; height:100vh; }
    </style>
</head>
<body class="bg-light">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between py-2">
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-primary d-md-none me-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">☰</button>
                    <a class="navbar-brand fw-bold mb-0" href="/">Takalo-takalo</a>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-end d-none d-sm-block">
                        <small class="d-block text-muted">Connecté en tant que</small>
                        <strong><?= htmlspecialchars($adminName) ?></strong>
                    </div>
                    <div class="profile-placeholder bg-secondary text-white d-flex align-items-center justify-content-center" title="Photo de profil admin">
                        <?= htmlspecialchars($adminInitials) ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Offcanvas sidebar for small screens -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="list-group list-group-flush">
                <a href="/admin_users" class="list-group-item list-group-item-action">Utilisateurs</a>
                <a href="/objets" class="list-group-item list-group-item-action">Objets</a>
            </nav>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar for md+ -->
            <nav id="sidebar" class="col-md-2 d-none d-md-block bg-white border-end p-3">
                <h6 class="text-muted">Menu</h6>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/admin_users">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin_echange">Echange</a></li>
                </ul>
            </nav>

            <!-- Main content -->
            <?php  require_once __DIR__ . '/' . $pagename; ?>
            <!-- fin Main -->
        </div>
    </div>

    <footer class="mt-4 py-3 bg-white border-top">
        <div class="container text-muted small">&copy; <?= date('Y') ?> Takalo-takalo:ETU4042-ETU3940-ETU4199</div>
    </footer>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>