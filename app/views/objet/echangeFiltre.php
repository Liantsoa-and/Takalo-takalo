<?php
$base = Flight::get('base_path') ?? '';
$objet = $objet ?? [];
$objetsSimilaires = $objetsSimilaires ?? [];
$percentage = $percentage ?? 10;
$mesObjets = $mesObjets ?? [];
$currentUserId = $currentUserId ?? 0;

$prixRef = (float) ($objet['prix_estimatif'] ?? 0);
$prixMin = $prixRef * (1 - $percentage / 100);
$prixMax = $prixRef * (1 + $percentage / 100);
?>

<main class="col-12 col-md-10 py-4">
    <div class="mb-4">
        <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Retour à l'objet
        </a>
    </div>

    <!-- En-tête avec infos de l'objet de référence -->
    <div class="card mb-4" style="border-left: 4px solid var(--tk-red, #c30000);">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-1" style="color:var(--tk-text-heading)">
                        <i class="bi bi-gem me-2" style="color:var(--tk-red, #c30000)"></i>
                        Objets au prix similaire à <span class="text-danger"><?= htmlspecialchars($objet['libelle']) ?></span>
                    </h3>
                    <p class="text-muted mb-0">
                        Prix de référence : <strong class="text-primary"><?= number_format($prixRef, 0, ',', ' ') ?> Ar</strong>
                        &mdash; Fourchette : <strong><?= number_format($prixMin, 0, ',', ' ') ?> Ar</strong> à <strong><?= number_format($prixMax, 0, ',', ' ') ?> Ar</strong>
                        (±<?= $percentage ?>%)
                    </p>
                </div>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/filtre/10" 
                       class="btn <?= $percentage == 10 ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="bi bi-search"></i> ±10%
                    </a>
                    <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/filtre/20" 
                       class="btn <?= $percentage == 20 ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="bi bi-search"></i> ±20%
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats -->
    <?php if (empty($objetsSimilaires)): ?>
        <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size: 4rem; opacity: 0.3; color: var(--tk-red);"></i>
            <h5 class="mt-3 text-muted">Aucun objet trouvé dans cette fourchette de prix</h5>
            <p class="text-muted small">Essayez avec une fourchette plus large (±20%)</p>
        </div>
    <?php else: ?>
        <p class="text-muted mb-3">
            <strong><?= count($objetsSimilaires) ?></strong> objet(s) trouvé(s) entre 
            <?= number_format($prixMin, 0, ',', ' ') ?> Ar et <?= number_format($prixMax, 0, ',', ' ') ?> Ar
        </p>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            <?php foreach ($objetsSimilaires as $obj): ?>
                <div class="col">
                    <div class="card h-100" style="border-radius:16px; overflow:hidden; background:var(--tk-bg-card, #1a1a1a); box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                        <!-- Image -->
                        <div style="position:relative; height:180px; overflow:hidden; background: linear-gradient(135deg, #2a0a0a, #1a0000);">
                            <?php if (!empty($obj['main_photo'])): ?>
                                <img src="<?= $base ?>/uploads/photos/<?= htmlspecialchars($obj['main_photo']) ?>" 
                                     alt="<?= htmlspecialchars($obj['libelle']) ?>"
                                     style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-image" style="font-size:3rem; opacity:0.3; color:#fff;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Badge Prix -->
                            <span style="position:absolute; top:12px; right:12px; background:linear-gradient(135deg, #950101, #c30000); color:#fff; padding:6px 12px; border-radius:20px; font-size:0.8rem; font-weight:700;">
                                <?= number_format($obj['prix_estimatif'], 0, ',', ' ') ?> Ar
                            </span>

                            <!-- Badge Différence % -->
                            <?php 
                            $diff = $obj['diff_percent'] ?? 0;
                            $diffClass = $diff > 0 ? 'bg-warning text-dark' : ($diff < 0 ? 'bg-info text-dark' : 'bg-success');
                            $diffSign = $diff > 0 ? '+' : '';
                            ?>
                            <span class="badge <?= $diffClass ?>" style="position:absolute; top:12px; left:12px; font-size:0.85rem; padding:6px 10px; border-radius:20px;">
                                <?= $diffSign ?><?= $diff ?>%
                            </span>
                        </div>

                        <!-- Body -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title" style="color:var(--tk-text-heading, #fff); font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" 
                                title="<?= htmlspecialchars($obj['libelle']) ?>">
                                <?= htmlspecialchars($obj['libelle']) ?>
                            </h5>
                            
                            <div class="mb-2">
                                <small style="color:var(--tk-text-muted, #aaa);">
                                    <i class="bi bi-tag-fill" style="color:var(--tk-red, #c30000);"></i>
                                    <?= htmlspecialchars($obj['category_name'] ?? 'Non catégorisé') ?>
                                </small>
                            </div>

                            <p class="card-text" style="color:var(--tk-text-secondary, #ccc); font-size:0.875rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                <?= htmlspecialchars($obj['description'] ?? 'Aucune description') ?>
                            </p>

                            <?php if (!empty($obj['owner_name'])): ?>
                                <div class="mb-2">
                                    <small style="color:var(--tk-text-muted, #aaa);">
                                        <i class="bi bi-person"></i> <?= htmlspecialchars($obj['owner_name']) ?>
                                    </small>
                                </div>
                            <?php endif; ?>

                            <div class="mt-auto d-flex gap-2 pt-2" style="border-top:1px solid rgba(255,255,255,0.08);">
                                <a href="<?= $base ?>/objet/<?= $obj['id'] ?>" class="btn btn-sm btn-outline-light flex-fill">
                                    <i class="bi bi-eye"></i> Détails
                                </a>

                                <?php if ($objet['user_id'] == $currentUserId): ?>
                                    <!-- Lien direct : proposer l'échange avec votre objet de référence -->
                                    <form method="POST" action="<?= $base ?>/echange/propose" class="flex-fill"
                                          onsubmit="return confirm('Proposer un échange ?\n\nVotre objet : <?= htmlspecialchars(addslashes($objet['libelle'])) ?> (<?= number_format($prixRef, 0, ',', ' ') ?> Ar)\nContre : <?= htmlspecialchars(addslashes($obj['libelle'])) ?> (<?= number_format($obj['prix_estimatif'], 0, ',', ' ') ?> Ar, <?= $diffSign ?><?= $diff ?>%)');">
                                        <input type="hidden" name="objet1_id" value="<?= $objet['id'] ?>">
                                        <input type="hidden" name="objet2_id" value="<?= $obj['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                            <i class="bi bi-arrow-left-right"></i> Échanger
                                            <span class="badge <?= $diffClass ?> ms-1"><?= $diffSign ?><?= $diff ?>%</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
