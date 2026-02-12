<?php
$adminName = isset($adminName) ? $adminName : 'Admin Demo';
$adminInitials = strtoupper(substr($adminName, 0, 1));
$base = Flight::get('base_path') ?? '';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Takalo-takalo - Administration">
    <title><?= htmlspecialchars($title ?? 'Administration - Takalo-takalo') ?></title>

    <!-- Bootstrap CSS -->
    <link href="<?= $base ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (local) -->
    <link href="<?= $base ?>/assets/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="<?= $base ?>/assets/style/main.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $base ?>/assets/images/logo.png">

    <style>
        /* Styles spécifiques admin */
        .admin-sidebar {
            background: linear-gradient(180deg, #0a0000 0%, #1a0000 100%) !important;
            color: #fff;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6) !important;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: #fff !important;
            background: rgba(149, 1, 1, 0.2) !important;
            border-left-color: var(--tk-bright-red) !important;
        }

        .admin-sidebar .nav-link i {
            width: 24px;
        }

        .admin-header {
            background: var(--tk-bg-header) !important;
            border-bottom: 1px solid var(--tk-border);
        }

        .profile-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--tk-dark-red), var(--tk-red));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }

        .stat-widget {
            color: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s;
            border: 1px solid var(--tk-border);
        }

        .stat-widget:hover {
            transform: translateY(-5px);
        }

        .stat-widget.blue {
            background: linear-gradient(135deg, var(--tk-dark-red), var(--tk-red));
        }

        .stat-widget.orange {
            background: linear-gradient(135deg, #950101, #ff0000);
        }

        .stat-widget.green {
            background: linear-gradient(135deg, #1a3a1a, #28a745);
        }

        .stat-widget.default {
            background: linear-gradient(135deg, var(--tk-dark-red), var(--tk-red));
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="admin-header sticky-top shadow-sm">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between py-3">
                <div class="d-flex align-items-center">
                    <!-- Mobile Sidebar Toggle -->
                    <button class="btn btn-sm btn-outline-primary d-md-none me-3 sidebar-toggle" type="button"
                        aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </button>

                    <a class="navbar-brand fw-bold mb-0 d-flex align-items-center" href="<?= $base ?>/">
                        <i class="bi bi-arrow-left-right me-2"></i>
                        <span class="d-none d-sm-inline">Takalo-takalo</span>
                        <span class="badge bg-danger ms-2">Admin</span>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary position-relative" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.6rem;">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">Notifications</h6>
                            </li>
                            <li><a id="addUserBtn" class="dropdown-item" href="#"><i class="bi bi-person-plus me-2"></i> Nouvel
                                    utilisateur</a></li>
                            
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center small" href="<?= $base ?>/admin/dashboard">Voir tout</a></li>
                        </ul>
                    </div>

                    <!-- User Profile -->
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-end d-none d-sm-block">
                            <small class="d-block text-muted" style="font-size: 0.75rem;">Connecté en tant que</small>
                            <strong class="d-block"
                                style="font-size: 0.875rem; color: var(--tk-text-heading);"><?= htmlspecialchars($adminName) ?></strong>
                        </div>
                        <div class="profile-circle" title="Photo de profil admin">
                            <?= htmlspecialchars($adminInitials) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Overlay pour sidebar mobile -->
    <div class="sidebar-overlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 admin-sidebar sidebar p-0 d-none d-md-block">
                <div class="p-3">
                    <h6 class="text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 1px; opacity: 0.7;">
                        Navigation</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'dashboard' ? 'active' : '' ?>"
                                href="<?= $base ?>/admin/dashboard">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'users' ? 'active' : '' ?>"
                                href="<?= $base ?>/admin/users">
                                <i class="bi bi-people"></i> Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'objets' ? 'active' : '' ?>"
                                href="<?= $base ?>/admin/objets">
                                <i class="bi bi-box-seam"></i> Objets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'echanges' ? 'active' : '' ?>"
                                href="<?= $base ?>/admin/echanges">
                                <i class="bi bi-arrow-left-right"></i> Échanges
                            </a>
                        </li>
                    </ul>

                    <h6 class="text-uppercase mb-3 mt-4" style="font-size: 0.75rem; letter-spacing: 1px; opacity: 0.7;">
                        Paramètres</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base ?>/admin/categories">
                                <i class="bi bi-tags"></i> Catégories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base ?>/admin/settings">
                                <i class="bi bi-gear"></i> Configuration
                            </a>
                        </li>
                    </ul>

                    <hr style="border-color: rgba(255,255,255,0.1);">

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="<?= $base ?>/logout">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-12 col-md-9 col-lg-10 py-4">
                <?php require_once __DIR__ . '/' . $pagename; ?>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-4 py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small mb-0">&copy; <?= date('Y') ?> Takalo-takalo</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">ETU4042 - ETU3940 - ETU4199</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="<?= $base ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= $base ?>/assets/js/app.js"></script>

    <!-- Page-specific scripts -->
    <?php if (isset($pagename) && (strpos($pagename, 'users.php') !== false || strpos($pagename, 'admin/users.php') !== false)): ?>
        <script src="<?= $base ?>/assets/js/users.js"></script>
    <?php endif; ?>
</body>

</html>