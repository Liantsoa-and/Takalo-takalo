<?php $base = Flight::get('base_path') ?? ''; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Takalo-takalo - Plateforme d'échange d'objets">
    <title><?= htmlspecialchars($title ?? 'Objets - Takalo-takalo') ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= $base ?>/assets/bootstrap/css/bootstrap.min.css">

    <!-- Bootstrap Icons (local) -->
    <link rel="stylesheet" href="<?= $base ?>/assets/bootstrap-icons/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= $base ?>/assets/style/main.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/style/objects.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $base ?>/assets/images/logo.png">
</head>

<body>
    <!-- Header -->
    <header class="navbar-header">
        <nav class="navbar navbar-expand-lg container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="<?= $base ?>/">
                    <i class="bi bi-arrow-left-right me-2"></i>Takalo-takalo
                </a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= isset($page) && $page === 'objets' ? 'active' : '' ?>"
                            href="<?= $base ?>/objets">
                            <i class="bi bi-box me-1"></i> Mes objets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($page) && $page === 'objets_publics' ? 'active' : '' ?>"
                            href="<?= $base ?>/objets_publics">
                            <i class="bi bi-grid-3x3 me-1"></i> Objets publics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isset($page) && $page === 'echanges' ? 'active' : '' ?>"
                            href="<?= $base ?>/echanges">
                            <i class="bi bi-arrow-left-right me-1"></i> Échanges
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> Mon compte
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= $base ?>/profil"><i class="bi bi-person me-2"></i>
                                    Mon profil</a></li>
                            <li><a class="dropdown-item" href="<?= $base ?>/parametres"><i class="bi bi-gear me-2"></i>
                                    Paramètres</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?= $base ?>/logout"><i
                                        class="bi bi-box-arrow-right me-2"></i> Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Overlay pour sidebar mobile -->
    <div class="sidebar-overlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 col-lg-2 sidebar d-none d-md-block">
                <div class="px-3">
                    <h6>Menu</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'objets' ? 'active' : '' ?>"
                                href="<?= $base ?>/objets">
                                <i class="bi bi-box"></i> Mes objets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'objets_publics' ? 'active' : '' ?>"
                                href="<?= $base ?>/objets_publics">
                                <i class="bi bi-grid-3x3"></i> Objets publics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($page) && $page === 'echanges' ? 'active' : '' ?>"
                                href="<?= $base ?>/echanges">
                                <i class="bi bi-arrow-left-right"></i> Échanges
                            </a>
                        </li>

                        <li class="nav-item mt-4">
                            <h6>Actions rapides</h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base ?>/objet/formulaire">
                                <i class="bi bi-plus-circle"></i> Ajouter un objet
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="col-12 col-md-9 col-lg-10 py-4">
                <?php include __DIR__ . '/' . $pagename; ?>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <h5>Takalo-takalo</h5>
                    <p class="text-muted small">Plateforme d'échange d'objets entre particuliers</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        &copy; <?= date('Y') ?> Takalo-takalo – Tous droits réservés
                    </p>
                    <p class="text-muted small">
                        ETU4042 - ETU3940 - ETU4199
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="<?= $base ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= $base ?>/assets/js/app.js"></script>
    <script src="<?= $base ?>/assets/js/search.js"></script>

    <!-- Page-specific scripts -->
    <?php if (isset($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?= $base ?>/assets/js/<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>