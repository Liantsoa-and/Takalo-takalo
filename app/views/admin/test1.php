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
    <style>
        .profile-placeholder { width:40px; height:40px; border-radius:50%; }
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
                    <li class="nav-item"><a class="nav-link" href="/objets">Objets</a></li>
                </ul>
            </nav>

            <!-- Main content -->
            <main class="col-12 col-md-10 py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h4 mb-0">Utilisateurs <span class="badge bg-primary"><?= $count ?></span></h1>
                    <div>
                        <a href="/admin_users" class="btn btn-sm btn-outline-secondary">Rafraîchir</a>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>Nom d'utilisateur</th>
                                        <th style="width:160px">Rôle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($usersList as $user): ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($user['username'] ?? ($user['user'] ?? '')) ?></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role'] ?? '') ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($usersList)): ?>
                                        <tr><td colspan="3" class="text-center text-muted py-3">Aucun utilisateur trouvé.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <footer class="mt-4 py-3 bg-white border-top">
        <div class="container text-muted small text-center">&copy; <?= date('Y') ?> Takalo-takalo</div>
    </footer>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>