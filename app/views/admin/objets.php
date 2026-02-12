<?php
// Données fournies par le controller
$objetsList  = isset($objets) ? $objets : [];
$categories  = isset($categories) ? $categories : [];
$count       = count($objetsList);
$base = Flight::get('base_path') ?? '';

// Filtre actif
$filterCategory = isset($filterCategory) ? $filterCategory : null;
$filterUser     = isset($filterUser) ? $filterUser : null;
$searchQuery    = isset($searchQuery) ? $searchQuery : '';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-box-seam me-2"></i>Objets <span class="badge bg-primary"><?= $count ?></span></h1>
    <div>
        <a href="<?= $base ?>/admin/objets" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-clockwise me-1"></i>Rafraîchir
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3">
        <form method="GET" action="<?= $base ?>/admin/objets" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Rechercher</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Libellé ou description..."
                           value="<?= htmlspecialchars($searchQuery) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Catégorie</label>
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['id'] ?>"
                            <?= ($filterCategory == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Propriétaire</label>
                <input type="text" name="owner" class="form-control form-control-sm" placeholder="Nom d'utilisateur..."
                       value="<?= htmlspecialchars($filterUser ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stats mini -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-white bg-warning shadow-sm border-0">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <div class="h3 mb-0 fw-bold"><?= $count ?></div>
                    <small class="text-uppercase">Total objets</small>
                </div>
                <i class="bi bi-box-seam-fill" style="font-size:2rem; opacity:0.7;"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-white bg-info shadow-sm border-0">
            <div class="card-body d-flex align-items-center justify-content-between py-3">
                <div>
                    <div class="h3 mb-0 fw-bold"><?= count($categories) ?></div>
                    <small class="text-uppercase">Catégories</small>
                </div>
                <i class="bi bi-tags-fill" style="font-size:2rem; opacity:0.7;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Objects Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:60px">Photo</th>
                        <th>Libellé</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th>Propriétaire</th>
                        <th>Prix (Ar)</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($objetsList)): ?>
                        <?php foreach ($objetsList as $obj): ?>
                            <tr id="objet-row-<?= (int)$obj['id'] ?>">
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
                                <td>
                                    <strong><?= htmlspecialchars($obj['libelle'] ?? '') ?></strong>
                                    <br>
                                    <small class="text-muted">ID: <?= (int)$obj['id'] ?></small>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars(mb_strimwidth($obj['description'] ?? '', 0, 60, '...')) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($obj['category_name'] ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($obj['owner_pdp'] ?? 'default.png') ?>"
                                             alt="pdp" class="rounded-circle me-2" style="width:28px;height:28px;object-fit:cover;">
                                        <small><?= htmlspecialchars($obj['owner_name'] ?? 'N/A') ?></small>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= number_format((float)($obj['prix_estimatif'] ?? 0), 0, ',', ' ') ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= $base ?>/objet/<?= (int)$obj['id'] ?>"
                                           class="btn btn-sm btn-outline-primary" title="Voir détail" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger btn-delete-objet"
                                                data-id="<?= (int)$obj['id'] ?>" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i><br>
                                Aucun objet trouvé.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-center">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php
                    $params = $_GET;
                    $params['page'] = $p;
                    $qs = http_build_query($params);
                ?>
                <li class="page-item <?= ((int)($currentPage ?? 1) === $p) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $base ?>/admin/objets?<?= $qs ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<script>
(function(){
    // Delete object
    document.querySelectorAll('.btn-delete-objet').forEach(function(btn){
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            if(!confirm('Supprimer cet objet (ID: '+id+') ? Cette action est irréversible.')) return;
            fetch('<?= $base ?>/admin/objet/'+id+'/delete')
                .then(r => r.json())
                .then(resp => {
                    if(resp.success){
                        const row = document.getElementById('objet-row-'+id);
                        if(row) row.remove();
                        // Update count badge
                        const badge = document.querySelector('h1 .badge');
                        if(badge){
                            let n = parseInt(badge.textContent) - 1;
                            badge.textContent = n >= 0 ? n : 0;
                        }
                    } else {
                        alert(resp.message || 'Erreur lors de la suppression');
                    }
                })
                .catch(() => alert('Erreur réseau'));
        });
    });
})();
</script>
