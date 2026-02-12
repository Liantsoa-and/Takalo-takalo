<?php $base = Flight::get('base_path') ?? '';
$scope = $scope ?? 'public'; ?>

<?php if (empty($objets)): ?>
    <div class="col-12">
        <div class="alert alert-info">Aucun objet trouvé.</div>
    </div>
<?php else: ?>
    <?php if ($scope === 'mine'): ?>
        <?php foreach ($objets as $objet): ?>
            <div class="card object-card">
                <?php if (!empty($objet['photo']) || !empty($objet['main_photo'])): ?>
                    <img src="/uploads/photos/<?= htmlspecialchars($objet['photo'] ?? $objet['main_photo']) ?>"
                        class="card-img-top object-img" alt="<?= htmlspecialchars($objet['libelle']) ?>">
                <?php else: ?>
                    <div class="card-img-top object-img bg-gradient d-flex align-items-center justify-content-center">
                        <i class="bi bi-image text-white" style="font-size:2rem;opacity:0.5"></i>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($objet['libelle']) ?></h5>

                    <p class="object-meta">
                        <i class="bi bi-tag icon-tag"></i>
                        <?= htmlspecialchars($objet['category_name'] ?? 'Catégorie #' . ($objet['category_id'] ?? '')) ?>
                    </p>

                    <p class="card-text"><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 100)) ?>...</p>

                    <div class="card-actions">
                        <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="icon-action icon-view" title="Voir">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/edit" class="icon-action icon-edit" title="Éditer">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="#" class="icon-action icon-delete" title="Supprimer"
                            onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')){ window.location.href='<?= $base ?>/objet/<?= $objet['id'] ?>/delete' }">
                            <i class="bi bi-trash"></i>
                        </a>
                        <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history" class="icon-action icon-history"
                            title="Historique">
                            <i class="bi bi-clock-history"></i>
                        </a>

                        <div class="owner-info">
                            <span
                                class="price-badge"><?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0, 0, ',', ' ')) ?>
                                Ar</span>
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
                        <img src="<?= $base ?>/<?= htmlspecialchars($objet['main_photo']) ?>" class="card-img-top object-img"
                            alt="<?= htmlspecialchars($objet['libelle']) ?>">
                    <?php else: ?>
                        <div class="card-img-top object-img bg-gradient d-flex align-items-center justify-content-center"><i
                                class="bi bi-image text-white" style="font-size:2rem;opacity:0.5"></i></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($objet['libelle']) ?></h5>
                        <p class="card-text text-muted mb-2"><small>
                                <svg class="icon-tag" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 16 16" aria-hidden="true" style="vertical-align: -2px; margin-right: 6px;">
                                    <path d="M2 2v4l6 6 6-6-6-6H2zm2 2h1v1H4V4z" />
                                </svg>
                                <?= htmlspecialchars($objet['category_name'] ?? '') ?></small></p>
                        <p class="card-text"><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 100)) ?>...</p>
                        <div class="d-flex align-items-center">
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="icon-action icon-view me-2" title="Voir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" fill="#fff" />
                                </svg>
                            </a>
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history" class="icon-action icon-history me-2"
                                title="Historique">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M8.515 3.5a.5.5 0 0 1 .485.379l.5 2a.5.5 0 0 1-.97.242L8 4.5 7.47 6.121A3.5 3.5 0 1 0 8.515 3.5z" />
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95.5.5 0 0 0-.866-.5A6 6 0 1 1 8 2z" />
                                </svg>
                            </a>
                            <div class="ms-auto text-end"><span
                                    class="badge bg-success"><?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0, 0, ',', ' ')) ?>
                                    Ar</span><br><small class="text-muted">Par
                                    <?= htmlspecialchars($objet['owner_name'] ?? '') ?></small></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>