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
                        <div class="d-flex align-items-center">
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="icon-action icon-view me-2" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" fill="#fff"/></svg>
                            </a>
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/edit" class="icon-action icon-edit me-2" title="Éditer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M12.146.708a1 1 0 0 1 1.415 0l1.731 1.73a1 1 0 0 1 0 1.415L5.854 13.29a.5.5 0 0 1-.168.11l-4 1.5A.5.5 0 0 1 1.5 13.5l1.5-4a.5.5 0 0 1 .11-.168L12.146.708z"/></svg>
                            </a>
                            <a href="#" class="icon-action icon-delete me-2" title="Supprimer" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')){ window.location.href='<?= $base ?>/objet/<?= $objet['id'] ?>/delete' }">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M5.5 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5zM8 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6A.5.5 0 0 1 8 5.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 1 1 0-2h3.086a1 1 0 0 1 .707.293L7 2h2l.707-.707A1 1 0 0 1 10.414 1H13.5a1 1 0 0 1 1 1z"/></svg>
                            </a>
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history" class="icon-action icon-history ms-auto" title="Historique">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M8.515 3.5a.5.5 0 0 1 .485.379l.5 2a.5.5 0 0 1-.97.242L8 4.5 7.47 6.121A3.5 3.5 0 1 0 8.515 3.5z"/><path d="M8 1a7 7 0 1 0 4.95 11.95.5.5 0 0 0-.866-.5A6 6 0 1 1 8 2z"/></svg>
                            </a>
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
                        <p class="card-text text-muted mb-2"><small>
                            <svg class="icon-tag" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true" style="vertical-align: -2px; margin-right: 6px;"><path d="M2 2v4l6 6 6-6-6-6H2zm2 2h1v1H4V4z"/></svg>
                            <?= htmlspecialchars($objet['category_name'] ?? '') ?></small></p>
                        <p class="card-text"><?= htmlspecialchars(substr($objet['description'] ?? '',0,100)) ?>...</p>
                        <div class="d-flex align-items-center">
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="icon-action icon-view me-2" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" fill="#fff"/></svg>
                            </a>
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history" class="icon-action icon-history me-2" title="Historique">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M8.515 3.5a.5.5 0 0 1 .485.379l.5 2a.5.5 0 0 1-.97.242L8 4.5 7.47 6.121A3.5 3.5 0 1 0 8.515 3.5z"/><path d="M8 1a7 7 0 1 0 4.95 11.95.5.5 0 0 0-.866-.5A6 6 0 1 1 8 2z"/></svg>
                            </a>
                            <div class="ms-auto text-end"><span class="badge bg-success"><?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0,0,',',' ')) ?> Ar</span><br><small class="text-muted">Par <?= htmlspecialchars($objet['owner_name'] ?? '') ?></small></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
