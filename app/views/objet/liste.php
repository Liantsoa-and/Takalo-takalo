<?php $base = Flight::get('base_path') ?? ''; ?>
<?php $objets = $objets ?? []; ?>

<main class="col-12 col-md-10 py-4">
<?php $categories = $categories ?? []; $scope = 'mine'; include __DIR__ . '/search.php'; ?>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes objets</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= $base ?>/objet/formulaire" class="btn btn-primary">
                <i class="bi bi-plus"></i> Ajouter un objet
            </a>
        </div>
    </div>

    <?php if (empty($objets)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore d'objets. <a href="<?= $base ?>/objet/formulaire">Créez votre premier objet</a>
        </div>
    <?php else: ?>
        <div id="objects-list-mine" class="row objects-list">
            <?php foreach ($objets as $objet): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($objet['photo']) || !empty($objet['main_photo'])): ?>
                            <img src="<?= htmlspecialchars($objet['photo'] ?? $objet['main_photo']) ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($objet['libelle']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                style="height: 200px;">
                                <span class="text-white">Pas de photo</span>
                            </div>
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
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $objet['id'] ?>)">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) {
        window.location.href = '<?= $base ?>/objet/' + id + '/delete';
    }
}
</script>