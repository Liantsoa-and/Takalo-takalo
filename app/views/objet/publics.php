<?php $base = Flight::get('base_path') ?? ''; ?>
<?php $categories = $categories ?? []; $scope = 'public'; ?>

<main class="col-12 col-md-10 py-4">
    <?php include __DIR__ . '/search.php'; ?>
    <h1 class="mb-4">Objets disponibles pour échange</h1>

    <style>
        .object-card { transition: transform 0.2s; height: 100%; }
        .object-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .object-img { height: 200px; object-fit: cover; }
        .filter-section { background-color: #f8f9fa; padding: 20px; margin-bottom: 30px; border-radius: 8px; }
    </style>

    <div class="filter-section">
        <form method="GET" action="<?= $base ?>/objets_publics" class="row g-3">
            <div class="col-md-8">
                <label for="category_id" class="form-label">Filtrer par catégorie</label>
                <select name="category_id" id="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $currentCategory == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
        <?php if ($currentCategory): ?><div class="mt-2"><a href="<?= $base ?>/objets_publics" class="btn btn-sm btn-outline-secondary">Réinitialiser le filtre</a></div><?php endif; ?>
    </div>

    <p class="text-muted mb-4"><?= $total ?> objet(s) trouvé(s)</p>

    <div id="objects-list-public" class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        <?php if (empty($objets)): ?>
            <div class="col-12"><div class="alert alert-info">Aucun objet disponible pour le moment.</div></div>
        <?php else: foreach ($objets as $objet): ?>
            <div class="col">
                <div class="card object-card">
                    <?php if (!empty($objet['main_photo'])): ?>
                        <img src="<?= htmlspecialchars($objet['main_photo']) ?>" class="card-img-top object-img" alt="<?= htmlspecialchars($objet['libelle']) ?>">
                    <?php else: ?><div class="card-img-top object-img bg-secondary d-flex align-items-center justify-content-center"><span class="text-white">Pas d'image</span></div><?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($objet['libelle']) ?></h5>
                        <p class="card-text text-muted mb-2"><small><i class="bi bi-tag"></i> <?= htmlspecialchars($objet['category_name']) ?></small></p>
                        <p class="card-text"><?= htmlspecialchars(substr($objet['description'] ?? '',0,100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center"><span class="badge bg-success"><?= number_format($objet['prix_estimatif'],0,',',' ') ?> Ar</span><small class="text-muted">Par <?= htmlspecialchars($objet['owner_name']) ?></small></div>
                    </div>
                    <div class="card-footer"><a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="btn btn-primary w-100">Voir détails</a></div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav aria-label="Navigation des pages"><ul class="pagination justify-content-center">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $currentPage-1 ?><?= $currentCategory ? '&category_id='.$currentCategory : '' ?>">Précédent</a></li>
            <?php for($i=1;$i<=$totalPages;$i++): ?><li class="page-item <?= $i==$currentPage?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?><?= $currentCategory ? '&category_id='.$currentCategory : '' ?>"><?= $i ?></a></li><?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $currentPage+1 ?><?= $currentCategory ? '&category_id='.$currentCategory : '' ?>">Suivant</a></li>
        </ul></nav>
    <?php endif; ?>
</main>