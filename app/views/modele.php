<?php $base = Flight::get('base_path') ?? ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Objets - Takalo-takalo') ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/bootstrap/css/bootstrap-icons.css">
    <style>
        body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .sidebar { min-height: 100vh; border-right: 1px solid #eee; }
        .footer { border-top: 1px solid #eee; padding: 1rem 0; }
    </style>
</head>
<body>
    <header class="bg-white shadow-sm">
        <nav class="navbar navbar-expand-md navbar-light container">
            <a class="navbar-brand" href="<?= $base ?>">Takalo-takalo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= $base ?>/objets">Mes objets</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base ?>/objets_publics">Objets publics</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $base ?>/echanges">Échanges</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-12 col-md-2 sidebar bg-light py-4 d-none d-md-block">
                <div class="px-3">
                    <h6>Menu</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="<?= $base ?>/objets">Mes objets</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $base ?>/objets_publics">Objets publics</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $base ?>/echanges">Échanges</a></li>

                    </ul>
                </div>
            </aside>

            <?php include __DIR__ . '/' . ($pagename ); ?>
        </div>
    </div>

    <footer class="footer bg-white mt-4">
        <div class="container text-center text-muted">
            <small>&copy; <?= date('Y') ?> Takalo-takalo — Tous droits réservés</small>
        </div>
    </footer>

    <script src="<?= $base ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $base ?>/assets/js/search.js"></script>
</body>
</html>