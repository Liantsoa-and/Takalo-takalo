<?php $base = Flight::get('base_path') ?? ''; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-1"><i class="bi bi-arrow-left-right me-2"></i>Historique des échanges</h2>
            <p class="text-muted">Objet : <strong><?= htmlspecialchars($objet['libelle'] ?? 'Objet') ?></strong></p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <?php if (empty($echanges)) : ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Aucun échange pour cet objet.</p>
                        <small class="text-muted">Cet objet n'a jamais été proposé ou reçu dans un échange.</small>
                    </div>
                </div>
            <?php else : ?>
                <?php foreach ($echanges as $echange) : 
                    // Définir les couleurs/badges selon le statut
                    $statusClass = match((int)$echange['status_id']) {
                        1 => 'bg-warning text-dark',   // En attente
                        2 => 'bg-success',             // Accepté
                        3 => 'bg-danger',              // Refusé
                        default => 'bg-secondary'
                    };
                    $statusIcon = match((int)$echange['status_id']) {
                        1 => 'bi-hourglass-split',
                        2 => 'bi-check-circle-fill',
                        3 => 'bi-x-circle-fill',
                        default => 'bi-question-circle'
                    };
                ?>
                    <div class="card shadow-sm mb-3 echange-card">
                        <!-- Header avec date et statut -->
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                <i class="bi bi-calendar3 me-2"></i>
                                <?= date('d/m/Y', strtotime($echange['date_echange'])) ?>
                                à <strong><?= date('H:i', strtotime($echange['date_echange'])) ?></strong>
                            </span>
                            <span class="badge <?= $statusClass ?>">
                                <i class="<?= $statusIcon ?> me-1"></i>
                                <?= htmlspecialchars($echange['status']) ?>
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Objet 1 (proposé) -->
                                <div class="col-5">
                                    <div class="text-center">
                                        <small class="text-muted d-block mb-2">Objet proposé</small>
                                        <a href="<?= $base ?>/objet/<?= (int)$echange['objet1_id'] ?>" class="text-decoration-none">
                                            <img src="<?= $base ?>/uploads/photos/<?= htmlspecialchars($echange['objet1_photo'] ?? 'default.jpg') ?>" 
                                                 alt="<?= htmlspecialchars($echange['objet1_libelle']) ?>"
                                                 class="rounded objet-photo mb-2"
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                            <div class="objet-libelle"><?= htmlspecialchars($echange['objet1_libelle']) ?></div>
                                        </a>
                                        <div class="mt-2">
                                            <a href="<?= $base ?>/profil/<?= (int)$echange['user1_id'] ?>/user" class="text-decoration-none d-flex align-items-center justify-content-center">
                                                <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($echange['user1_pdp'] ?? 'default.png') ?>" 
                                                     alt="<?= htmlspecialchars($echange['user1_name']) ?>"
                                                     class="rounded-circle me-2"
                                                     style="width: 28px; height: 28px; object-fit: cover;">
                                                <span class="user-name small"><?= htmlspecialchars($echange['user1_name']) ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Flèche échange -->
                                <div class="col-2 text-center">
                                    <div class="exchange-arrow">
                                        <i class="bi bi-arrow-left-right" style="font-size: 1.5rem; color: var(--tk-red, #c30000);"></i>
                                    </div>
                                </div>
                                
                                <!-- Objet 2 (ciblé) -->
                                <div class="col-5">
                                    <div class="text-center">
                                        <small class="text-muted d-block mb-2">Objet ciblé</small>
                                        <a href="<?= $base ?>/objet/<?= (int)$echange['objet2_id'] ?>" class="text-decoration-none">
                                            <img src="<?= $base ?>/uploads/photos/<?= htmlspecialchars($echange['objet2_photo'] ?? 'default.jpg') ?>" 
                                                 alt="<?= htmlspecialchars($echange['objet2_libelle']) ?>"
                                                 class="rounded objet-photo mb-2"
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                            <div class="objet-libelle"><?= htmlspecialchars($echange['objet2_libelle']) ?></div>
                                        </a>
                                        <div class="mt-2">
                                            <a href="<?= $base ?>/profil/<?= (int)$echange['user2_id'] ?>/user" class="text-decoration-none d-flex align-items-center justify-content-center">
                                                <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($echange['user2_pdp'] ?? 'default.png') ?>" 
                                                     alt="<?= htmlspecialchars($echange['user2_name']) ?>"
                                                     class="rounded-circle me-2"
                                                     style="width: 28px; height: 28px; object-fit: cover;">
                                                <span class="user-name small"><?= htmlspecialchars($echange['user2_name']) ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar - Liste des propriétaires -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-people me-2"></i>Propriétaires de l'objet</h6>
                </div>
                <div class="card-body p-0">
                    <?php 
                    // Construire la liste des propriétaires à partir des échanges
                    $owners = [];
                    $objetId = (int)($objet['id'] ?? 0);
                    
                    foreach ($echanges as $idx => $ech) {
                        // Si l'objet actuel était objet1 (proposé), user1 était proprio, user2 l'a reçu
                        // Si l'objet actuel était objet2 (ciblé), user2 était proprio, user1 l'a reçu
                        if ((int)$ech['objet1_id'] === $objetId) {
                            // Premier propriétaire connu si c'est le premier échange
                            if ($idx === 0) {
                                $owners[] = [
                                    'user_id' => $ech['user1_id'],
                                    'username' => $ech['user1_name'],
                                    'pdp' => $ech['user1_pdp'],
                                    'date' => null, // propriétaire initial
                                ];
                            }
                            // Nouveau propriétaire après échange
                            $owners[] = [
                                'user_id' => $ech['user2_id'],
                                'username' => $ech['user2_name'],
                                'pdp' => $ech['user2_pdp'],
                                'date' => $ech['date_echange'],
                            ];
                        } else {
                            // objet2 = objet actuel
                            if ($idx === 0) {
                                $owners[] = [
                                    'user_id' => $ech['user2_id'],
                                    'username' => $ech['user2_name'],
                                    'pdp' => $ech['user2_pdp'],
                                    'date' => null,
                                ];
                            }
                            $owners[] = [
                                'user_id' => $ech['user1_id'],
                                'username' => $ech['user1_name'],
                                'pdp' => $ech['user1_pdp'],
                                'date' => $ech['date_echange'],
                            ];
                        }
                    }
                    
                    // Si aucun échange, on affiche le propriétaire actuel
                    if (empty($owners) && !empty($currentOwner)) {
                        $owners[] = [
                            'user_id' => $currentOwner['id'],
                            'username' => $currentOwner['username'] ?? 'Propriétaire',
                            'pdp' => $currentOwner['pdp'] ?? 'default.png',
                            'date' => null,
                        ];
                    }
                    
                    $totalOwners = count($owners);
                    ?>
                    
                    <?php if (empty($owners)): ?>
                        <div class="p-3 text-center text-muted">
                            <small>Aucun propriétaire connu</small>
                        </div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($owners as $idx => $owner): 
                                $isCurrent = ($idx === $totalOwners - 1);
                            ?>
                                <li class="list-group-item d-flex align-items-center <?= $isCurrent ? 'bg-success bg-opacity-10' : '' ?>">
                                    <a href="<?= $base ?>/profil/<?= (int)$owner['user_id'] ?>/user" class="text-decoration-none me-3">
                                        <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($owner['pdp'] ?? 'default.png') ?>" 
                                             alt="<?= htmlspecialchars($owner['username']) ?>"
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover; border: 2px solid <?= $isCurrent ? 'var(--tk-red, #c30000)' : 'rgba(255,255,255,0.1)' ?>;">
                                    </a>
                                    <div class="flex-grow-1">
                                        <a href="<?= $base ?>/profil/<?= (int)$owner['user_id'] ?>/user" class="text-decoration-none">
                                            <strong class="owner-link"><?= htmlspecialchars($owner['username']) ?></strong>
                                        </a>
                                        <?php if ($isCurrent): ?>
                                            <span class="badge bg-success ms-2">Actuel</span>
                                        <?php endif; ?>
                                        <div class="small text-muted">
                                            <?php if ($owner['date']): ?>
                                                <i class="bi bi-arrow-right-circle me-1"></i>
                                                <?= date('d/m/Y à H:i', strtotime($owner['date'])) ?>
                                            <?php else: ?>
                                                <i class="bi bi-star me-1"></i>
                                                Propriétaire initial
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Résumé</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nombre de propriétaires</span>
                        <strong><?= $totalOwners ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Nombre d'échanges</span>
                        <strong><?= count($echanges ?? []) ?></strong>
                    </div>
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
.echange-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.echange-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}
.objet-photo {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 2px solid rgba(255,255,255,0.1);
}
.objet-photo:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(195, 0, 0, 0.3);
    border-color: var(--tk-red, #c30000);
}
.objet-libelle {
    color: var(--tk-text-heading, #fff);
    font-weight: 500;
    font-size: 0.9rem;
    transition: color 0.2s;
}
.objet-libelle:hover {
    color: var(--tk-red, #c30000);
}
.user-name {
    color: var(--tk-text-muted, #999);
    transition: color 0.2s;
}
.user-name:hover {
    color: var(--tk-red, #c30000);
}
.exchange-arrow {
    opacity: 0.7;
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 0.7; }
    50% { opacity: 1; }
}
.owner-link {
    color: var(--tk-text-heading, #fff);
    transition: color 0.2s;
}
.owner-link:hover {
    color: var(--tk-red, #c30000);
}
.list-group-item {
    background: transparent;
    border-color: rgba(255,255,255,0.1);
}
</style>
