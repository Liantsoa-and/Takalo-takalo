<?php $base = Flight::get('base_path') ?? ''; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
 
            <h2 class="mb-1"><i class="bi bi-clock-history me-2"></i>Historique d'appartenance</h2>
            <p class="text-muted">Objet : <strong><?= htmlspecialchars($objet['libelle'] ?? 'Objet') ?></strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <?php if (empty($timeline)) : ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Aucun historique disponible pour cet objet.</p>
                        <small class="text-muted">Cet objet n'a jamais changé de propriétaire.</small>
                    </div>
                </div>
            <?php else : ?>
                <div class="timeline-container">
                    <?php foreach ($timeline as $index => $entry) : ?>
                        <?php 
                        $isLast = ($index === count($timeline) - 1);
                        $isFirst = ($index === 0);
                        ?>
                        <div class="timeline-item d-flex mb-3">
                            <!-- Timeline line -->
                            <div class="timeline-marker me-3 d-flex flex-column align-items-center">
                                <div class="timeline-dot <?= $isLast ? 'current' : '' ?>"></div>
                                <?php if (!$isLast): ?>
                                    <div class="timeline-line flex-grow-1"></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Content -->
                            <div class="card shadow-sm flex-grow-1 <?= $isLast ? 'border-success' : '' ?>">
                                <div class="card-body d-flex align-items-center">
                                    <a href="<?= $base ?>/profil/<?= (int)$entry['user_id'] ?>/user" class="text-decoration-none">
                                        <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($entry['pdp'] ?? 'default.png') ?>" 
                                             alt="<?= htmlspecialchars($entry['username']) ?>" 
                                             class="rounded-circle me-3" 
                                             style="width: 56px; height: 56px; object-fit: cover; border: 3px solid <?= $isLast ? 'var(--tk-red, #c30000)' : 'rgba(255,255,255,0.1)' ?>;">
                                    </a>
                                    <div class="flex-grow-1">
                                        <a href="<?= $base ?>/profil/<?= (int)$entry['user_id'] ?>/user" class="text-decoration-none">
                                            <h6 class="mb-0" style="color: var(--tk-text-heading, #fff);">
                                                <?= htmlspecialchars($entry['username'] ?? 'Utilisateur') ?>
                                            </h6>
                                        </a>
                                        <small class="text-muted">
                                            <?php if ($isLast): ?>
                                                <span class="badge bg-success me-1">Actuel</span>
                                            <?php endif; ?>
                                            <?php if (!empty($entry['date'])): ?>
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= date('d/m/Y à H:i', strtotime($entry['date'])) ?>
                                            <?php else: ?>
                                                <i class="bi bi-check-circle me-1"></i>
                                                <?= htmlspecialchars($entry['note'] ?? '') ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <a href="<?= $base ?>/profil/<?= (int)$entry['user_id'] ?>/user" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       title="Voir le profil">
                                        <i class="bi bi-person"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar info -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        <strong>Nombre de transferts :</strong> <?= max(0, count($timeline ?? []) - 1) ?>
                    </p>
                    <p class="small text-muted mb-0">
                        L'historique montre tous les propriétaires successifs de cet objet depuis sa création.
                    </p>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <a href="<?= $base ?>/objet/<?= $objet['id'] ?? '' ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à l'objet
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--tk-bg-input, #2a2a2a);
    border: 3px solid var(--tk-red, #c30000);
}
.timeline-dot.current {
    background: var(--tk-red, #c30000);
    box-shadow: 0 0 0 4px rgba(195, 0, 0, 0.2);
}
.timeline-line {
    width: 2px;
    background: linear-gradient(to bottom, var(--tk-red, #c30000), rgba(255,255,255,0.1));
    min-height: 40px;
}
.timeline-item .card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.timeline-item .card:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
</style>
