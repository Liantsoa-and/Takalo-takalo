<?php
// Variables fournies par le controller
$nbUsers       = isset($nbUsers) ? $nbUsers : 0;
$nbObjets      = isset($nbObjets) ? $nbObjets : 0;
$nbEchanges    = isset($nbEchanges) ? $nbEchanges : 0;
$nbCategories  = isset($nbCategories) ? $nbCategories : 0;
$nbEnAttente   = isset($nbEnAttente) ? $nbEnAttente : 0;
$nbConfirmes   = isset($nbConfirmes) ? $nbConfirmes : 0;
$nbRefuses     = isset($nbRefuses) ? $nbRefuses : 0;
$recentEchanges = isset($recentEchanges) ? $recentEchanges : [];
$recentObjets   = isset($recentObjets) ? $recentObjets : [];
$topUsers       = isset($topUsers) ? $topUsers : [];
$base = Flight::get('base_path') ?? '';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</h1>
        <p class="text-muted small mb-0">Vue d'ensemble de la plateforme Takalo-takalo</p>
    </div>
    <div>
        <a href="<?= $base ?>/admin/dashboard" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-clockwise me-1"></i>Actualiser
        </a>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-3 mb-4">
    <!-- Users -->
    <div class="col-6 col-lg-3">
        <div class="stat-widget blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number"><?= $nbUsers ?></div>
                    <div class="stat-label">Utilisateurs</div>
                </div>
                <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.7;"></i>
            </div>
            <div class="mt-2">
                <a href="<?= $base ?>/admin/users" class="text-white text-decoration-none small">
                    Voir tout <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Objets -->
    <div class="col-6 col-lg-3">
        <div class="stat-widget orange">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number"><?= $nbObjets ?></div>
                    <div class="stat-label">Objets</div>
                </div>
                <i class="bi bi-box-seam-fill" style="font-size: 2.5rem; opacity: 0.7;"></i>
            </div>
            <div class="mt-2">
                <a href="<?= $base ?>/admin/objets" class="text-white text-decoration-none small">
                    Voir tout <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Échanges -->
    <div class="col-6 col-lg-3">
        <div class="stat-widget green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number"><?= $nbEchanges ?></div>
                    <div class="stat-label">Échanges</div>
                </div>
                <i class="bi bi-arrow-left-right" style="font-size: 2.5rem; opacity: 0.7;"></i>
            </div>
            <div class="mt-2">
                <a href="<?= $base ?>/admin/echanges" class="text-white text-decoration-none small">
                    Voir tout <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Catégories -->
    <div class="col-6 col-lg-3">
        <div class="stat-widget default">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-number"><?= $nbCategories ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
                <i class="bi bi-tags-fill" style="font-size: 2.5rem; opacity: 0.7;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Echanges Status Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:50px;height:50px;background:rgba(255,193,7,0.15);">
                    <i class="bi bi-hourglass-split text-warning" style="font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="text-white h4 mb-0"><?= $nbEnAttente ?></div>
                    <small class="text-muted">En attente</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:50px;height:50px;background:rgba(40,167,69,0.15);">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="text-white h4 mb-0"><?= $nbConfirmes ?></div>
                    <small class="text-muted">Confirmés</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:50px;height:50px;background:rgba(255,0,0,0.15);">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="text-white  h4 mb-0"><?= $nbRefuses ?></div>
                    <small class="text-muted">Refusés</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Two columns: Recent Exchanges + Top Users -->
<div class="row g-3 mb-4">
    <!-- Recent Exchanges -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Échanges récents</h6>
                <a href="<?= $base ?>/admin/echanges" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Proposant</th>
                                <th>Objet proposé</th>
                                <th>Objet ciblé</th>
                                <th>Destinataire</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentEchanges)): ?>
                                <?php $i = 1; foreach ($recentEchanges as $e): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($e['user1'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($e['objet1'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($e['objet2'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($e['user2'] ?? '') ?></td>
                                        <?php
                                            $statusRaw = $e['status'] ?? '';
                                            $statusKey = strtolower(str_replace(' ', '_', trim($statusRaw)));
                                            $badgeClass = 'secondary';
                                            if ($statusKey === 'en_attente') $badgeClass = 'warning';
                                            elseif (in_array($statusKey, ['confirme','accepté','accepted'])) $badgeClass = 'success';
                                            elseif (in_array($statusKey, ['refuser','refusé','rejected'])) $badgeClass = 'danger';
                                            elseif ($statusKey === 'libre') $badgeClass = 'info';
                                        ?>
                                        <td><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($statusRaw) ?></span></td>
                                        <td><small class="text-muted"><?= htmlspecialchars($e['date_echange'] ?? '') ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">Aucun échange récent.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Users (most objects) -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>Utilisateurs les plus actifs</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (!empty($topUsers)): ?>
                        <?php foreach ($topUsers as $tu): ?>
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($tu['pdp'] ?? 'default.png') ?>"
                                         alt="pdp" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($tu['username'] ?? '') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($tu['role'] ?? 'user') ?></small>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?= (int)($tu['nb_objets'] ?? 0) ?> objets</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center text-muted py-3">Aucun utilisateur.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Recent Objects -->
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Derniers objets ajoutés</h6>
                <a href="<?= $base ?>/admin/objets" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">Photo</th>
                                <th>Libellé</th>
                                <th>Catégorie</th>
                                <th>Propriétaire</th>
                                <th>Prix estimatif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentObjets)): ?>
                                <?php foreach ($recentObjets as $obj): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($obj['main_photo'])): ?>
                                                <img src="<?= $base ?><?= htmlspecialchars($obj['main_photo']) ?>" alt="photo"
                                                     class="rounded" style="width:45px;height:45px;object-fit:cover;">
                                            <?php else: ?>
                                                <div class="rounded d-flex align-items-center justify-content-center"
                                                     style="width:45px;height:45px;background:var(--tk-bg-input);">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($obj['libelle'] ?? '') ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($obj['category_name'] ?? '') ?></span></td>
                                        <td><?= htmlspecialchars($obj['owner_name'] ?? '') ?></td>
                                        <td><strong><?= number_format((float)($obj['prix_estimatif'] ?? 0), 0, ',', ' ') ?> Ar</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">Aucun objet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
