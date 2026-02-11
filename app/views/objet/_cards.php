<?php $base = Flight::get('base_path') ?? ''; $scope = $scope ?? 'public'; ?>

<?php if (empty($objets)): ?>
    <div class="col-12"><div class="alert alert-info">Aucun objet trouvé.</div></div>
<?php else: ?>
    <?php if ($scope === 'mine'): ?>
        <?php foreach ($objets as $objet): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($objet['photo']) || !empty($objet['main_photo'])): ?>
                        <img src="<?= htmlspecialchars($objet['photo'] ?? $objet['main_photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($objet['libelle']) ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;"><span class="text-white">Pas de photo</span></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($objet['libelle']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 100)) ?>...</p>
                        <p class="text-muted small">
                            Catégorie: #<?= htmlspecialchars($objet['category_id'] ?? '') ?> |
                            Prix estimé: <?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0, 2, ',', ' ')) ?> €
                        </p>
                        <div class="btn-group w-100" role="group">
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="btn btn-sm btn-info">Voir</a>
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/edit" class="btn btn-sm btn-warning">Éditer</a>
                            <button class="btn btn-sm btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')){ window.location.href='<?= $base ?>/objet/<?= $objet['id'] ?>/delete' }">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($objets as $objet): ?>
            <div class="col">
                <div class="card object-card">
                    <?php if (!empty($objet['main_photo'])): ?>
                        <img src="<?= htmlspecialchars($objet['main_photo']) ?>" class="card-img-top object-img" alt="<?= htmlspecialchars($objet['libelle']) ?>">
                    <?php else: ?>
                        <div class="card-img-top object-img bg-secondary d-flex align-items-center justify-content-center"><span class="text-white">Pas d'image</span></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($objet['libelle']) ?></h5>
                        <p class="card-text text-muted mb-2"><small><i class="bi bi-tag"></i> <?= htmlspecialchars($objet['category_name'] ?? '') ?></small></p>
                        <p class="card-text"><?= htmlspecialchars(substr($objet['description'] ?? '',0,100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center"><span class="badge bg-success"><?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0,0,',',' ')) ?> Ar</span><small class="text-muted">Par <?= htmlspecialchars($objet['owner_name'] ?? '') ?></small></div>
                    </div>
                    <div class="card-footer"><a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="btn btn-primary w-100">Voir détails</a></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
