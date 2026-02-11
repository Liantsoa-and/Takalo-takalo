<?php
$echanges = $echanges ?? [];
$onglet = $onglet ?? 'recues';
$base = Flight::get('base_path') ?? '';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Échanges - Takalo-takalo</title>
    <link href="<?= $base ?>/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .echange-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .echange-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .objet-img {
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge-status {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mes Échanges</h1>
            <div>
                <a href="<?= $base ?>/objets_publics" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Parcourir les objets
                </a>
                <a href="<?= $base ?>/objets" class="btn btn-outline-secondary">
                    <i class="bi bi-box"></i> Mes objets
                </a>
            </div>
        </div>

        <!-- Messages de succès/erreur -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php if ($_GET['success'] === 'accepte'): ?>
                    <strong>Succès !</strong> L'échange a été accepté. Les objets ont changé de propriétaire.
                <?php elseif ($_GET['success'] === 'refuse'): ?>
                    <strong>Refusé !</strong> La proposition d'échange a été refusée.
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur !</strong> Une erreur s'est produite lors du traitement de votre demande.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= $onglet === 'recues' ? 'active' : '' ?>"
                    href="<?= $base ?>/echanges?onglet=recues">
                    <i class="bi bi-inbox"></i> Propositions reçues
                    <?php
                    $countRecues = 0;
                    if ($onglet === 'recues') {
                        $countRecues = count(array_filter($echanges, fn($e) => $e['status_id'] == 1));
                        if ($countRecues > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?= $countRecues ?></span>
                        <?php endif;
                    }
                    ?>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= $onglet === 'envoyees' ? 'active' : '' ?>"
                    href="<?= $base ?>/echanges?onglet=envoyees">
                    <i class="bi bi-send"></i> Propositions envoyées
                </a>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content">
            <?php if (empty($echanges)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <?php if ($onglet === 'recues'): ?>
                        Vous n'avez aucune proposition d'échange reçue pour le moment.
                    <?php else: ?>
                        Vous n'avez envoyé aucune proposition d'échange pour le moment.
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($echanges as $echange): ?>
                        <div class="col-md-6">
                            <div class="card echange-card h-100">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">
                                            <?= date('d/m/Y à H:i', strtotime($echange['date_echange'])) ?>
                                        </span>
                                        <span
                                            class="badge badge-status bg-<?= $echange['status'] == 'en attente' ? 'warning' : ($echange['status'] == 'confirme' ? 'success' : 'danger') ?>">
                                            <?= htmlspecialchars($echange['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if ($onglet === 'recues'): ?>
                                        <!-- Proposition REÇUE -->
                                        <h6 class="text-primary mb-3">
                                            <i class="bi bi-person"></i>
                                            Proposition de <strong><?= htmlspecialchars($echange['proposant']) ?></strong>
                                        </h6>

                                        <div class="row mb-3">
                                            <!-- Objet proposé -->
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <p class="text-muted small mb-2">Il propose :</p>
                                                    <?php if ($echange['photo_propose']): ?>
                                                        <img src="<?= htmlspecialchars($echange['photo_propose']) ?>"
                                                            class="objet-img w-100 mb-2"
                                                            alt="<?= htmlspecialchars($echange['objet_propose']) ?>">
                                                    <?php else: ?>
                                                        <div
                                                            class="objet-img w-100 mb-2 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                            Pas d'image
                                                        </div>
                                                    <?php endif; ?>
                                                    <h6><?= htmlspecialchars($echange['objet_propose']) ?></h6>
                                                    <span class="badge bg-success">
                                                        <?= number_format($echange['prix_propose'], 0, ',', ' ') ?> Ar
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="text-center">
                                                    <p class="text-muted small mb-2">Contre votre :</p>
                                                    <?php if ($echange['ma_photo']): ?>
                                                        <img src="<?= htmlspecialchars($echange['ma_photo']) ?>"
                                                            class="objet-img w-100 mb-2"
                                                            alt="<?= htmlspecialchars($echange['mon_objet']) ?>">
                                                    <?php else: ?>
                                                        <div
                                                            class="objet-img w-100 mb-2 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                            Pas d'image
                                                        </div>
                                                    <?php endif; ?>
                                                    <h6><?= htmlspecialchars($echange['mon_objet']) ?></h6>
                                                    <span class="badge bg-success">
                                                        <?= number_format($echange['mon_prix'], 0, ',', ' ') ?> Ar
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($echange['status_id'] == 1): ?>
                                            <!-- Boutons d'action pour proposition en attente -->
                                            <div class="d-flex gap-2">
                                                <form method="POST" action="<?= $base ?>/echange/<?= $echange['id'] ?>/accepter"
                                                    class="flex-fill"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cet échange ? Les objets changeront de propriétaire.');">
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="bi bi-check-circle"></i> Accepter
                                                    </button>
                                                </form>
                                                <form method="POST" action="<?= $base ?>/echange/<?= $echange['id'] ?>/refuser"
                                                    class="flex-fill"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette proposition ?');">
                                                    <button type="submit" class="btn btn-danger w-100">
                                                        <i class="bi bi-x-circle"></i> Refuser
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <!-- Proposition ENVOYÉE -->
                                        <h6 class="text-primary mb-3">
                                            <i class="bi bi-person"></i>
                                            Envoyée à <strong><?= htmlspecialchars($echange['destinataire']) ?></strong>
                                        </h6>

                                        <div class="row mb-3">
                                            <!-- Mon objet -->
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <p class="text-muted small mb-2">Vous proposez :</p>
                                                    <?php if ($echange['ma_photo']): ?>
                                                        <img src="<?= htmlspecialchars($echange['ma_photo']) ?>"
                                                            class="objet-img w-100 mb-2"
                                                            alt="<?= htmlspecialchars($echange['mon_objet']) ?>">
                                                    <?php else: ?>
                                                        <div
                                                            class="objet-img w-100 mb-2 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                            Pas d'image
                                                        </div>
                                                    <?php endif; ?>
                                                    <h6><?= htmlspecialchars($echange['mon_objet']) ?></h6>
                                                    <span class="badge bg-success">
                                                        <?= number_format($echange['mon_prix'], 0, ',', ' ') ?> Ar
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="text-center">
                                                    <p class="text-muted small mb-2">Contre :</p>
                                                    <?php if ($echange['photo_cible']): ?>
                                                        <img src="<?= htmlspecialchars($echange['photo_cible']) ?>"
                                                            class="objet-img w-100 mb-2"
                                                            alt="<?= htmlspecialchars($echange['objet_cible']) ?>">
                                                    <?php else: ?>
                                                        <div
                                                            class="objet-img w-100 mb-2 bg-secondary d-flex align-items-center justify-content-center text-white">
                                                            Pas d'image
                                                        </div>
                                                    <?php endif; ?>
                                                    <h6><?= htmlspecialchars($echange['objet_cible']) ?></h6>
                                                    <span class="badge bg-success">
                                                        <?= number_format($echange['prix_cible'], 0, ',', ' ') ?> Ar
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($echange['status_id'] == 1): ?>
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-hourglass-split"></i> En attente de réponse
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="<?= $base ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>