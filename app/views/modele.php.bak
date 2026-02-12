<?php $base = Flight::get('base_path') ?? ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Objets - Takalo-takalo') ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/bootstrap/css/bootstrap.min.css">
    <style>
        body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .sidebar { min-height: 100vh; border-right: 1px solid #eee; }
        .footer { border-top: 1px solid #eee; padding: 1rem 0; }
        .icon-action { color: inherit; font-size: 1rem; text-decoration: none; display:inline-flex; align-items:center; }
        .icon-action:hover { color: inherit; text-decoration: none; }
        /* Couleurs par action */
        .icon-view { color: #0d6efd; }       /* bleu */
        .icon-edit { color: #fd7e14; }       /* orange */
        .icon-delete { color: #dc3545; }     /* rouge */
        .icon-history { color: #6c757d; }    /* gris */
        .icon-tag { color: #0dcaf0; }        /* cyan pour tag */
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
                           <?php /* Historique moved to card actions; no sidebar link here anymore */ ?>
                           <?php if (!empty($objet) && !empty($objet['id'])): ?>
                           <?php endif; ?>

                    </ul>
                </div>
            </aside>

            <main class="col-12 col-md-10 py-4">
                <?php include __DIR__ . '/' . ($pagename ); ?>
            </main>
        </div>
    </div>

    <footer class="footer bg-white mt-4">
        <div class="container text-center text-muted">
            <small>&copy; <?= date('Y') ?> Takalo-takalo — Tous droits réservés : ETU4042 - ETU3940 - ETU4199</small>
        </div>
    </footer>

    <script src="<?= $base ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $base ?>/assets/js/search.js"></script>
</body>
</html>