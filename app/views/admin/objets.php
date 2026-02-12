<?php
// Données fournies par le controller
$objetsList = isset($objets) ? $objets : [];
$categories = isset($categories) ? $categories : [];
$count = count($objetsList);
$base = Flight::get('base_path') ?? '';

// Filtre actif
$filterCategory = isset($filterCategory) ? $filterCategory : null;
$filterUser = isset($filterUser) ? $filterUser : null;
$searchQuery = isset($searchQuery) ? $searchQuery : '';

$basephoto ="/uploads/photos/"; // Chemin de base pour les photos des objets

// var_dump($base);
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-box-seam me-2"></i>Objets <span class="badge bg-primary"><?= $count ?></span>
    </h1>
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
                        <option value="<?= (int) $cat['id'] ?>" <?= ($filterCategory == $cat['id']) ? 'selected' : '' ?>>
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
            <td>
                <?php
                // Images are stored in public/uploads/photos
                $photoUrl = $obj['main_photo'] ?? null;
                $displayPhoto = null;
                $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');

                if ($photoUrl) {
                    // If stored as full path (/uploads/photos/xxx) use it
                    $candidate1 = $photoUrl;
                    // If stored as filename or different path, normalize to /uploads/photos/{basename}
                    $candidate2 = '/uploads/photos/' . basename($photoUrl);

                    if ($docRoot && file_exists($docRoot . $candidate1)) {
                        $displayPhoto = $candidate1;
                    } elseif ($docRoot && file_exists($docRoot . $candidate2)) {
                        $displayPhoto = $candidate2;
                    }
                }

                if ($displayPhoto): ?>
                    <img src="<?= $basephoto ?><?= htmlspecialchars($displayPhoto) ?>" alt="photo" class="rounded"
                        style="width:45px;height:45px;object-fit:cover;">
                <?php else: ?>
                    <div class="rounded d-flex align-items-center justify-content-center"
                        style="width:45px;height:45px;background:var(--tk-bg-input);">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                <?php endif; ?>
            </td>
            <!-- Objects Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Libellé</th>
                                    <th>Description</th>
                                    <th>Catégorie</th>
                                    <th>Propriétaire</th>
                                    <th>Prix (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($objetsList)): ?>
                                    <?php foreach ($objetsList as $obj): ?>
                                        <tr id="objet-row-<?= (int) $obj['id'] ?>">
                                            <td>
                                                <strong><?= htmlspecialchars($obj['libelle'] ?? '') ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?= (int) $obj['id'] ?></small>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars(mb_strimwidth($obj['description'] ?? '', 0, 60, '...')) ?></small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary"><?= htmlspecialchars($obj['category_name'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="<?= $base ?>/profil/<?= (int) $obj['owner_id'] ?>/admin">
                                                        <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($obj['owner_pdp'] ?? 'default.png') ?>"
                                                            alt="pdp" class="rounded-circle me-2"
                                                            style="width:28px;height:28px;object-fit:cover;">
                                                    </a>

                                                    <small><?= htmlspecialchars($obj['owner_name'] ?? 'N/A') ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong><?= number_format((float) ($obj['prix_estimatif'] ?? 0), 0, ',', ' ') ?></strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
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
                            <li class="page-item <?= ((int) ($currentPage ?? 1) === $p) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $base ?>/admin/objets?<?= $qs ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <script>
                (function () {
                    // Delete object
                    document.querySelectorAll('.btn-delete-objet').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const id = this.dataset.id;
                            if (!confirm('Supprimer cet objet (ID: ' + id + ') ? Cette action est irréversible.')) return;
                            fetch('<?= $base ?>/admin/objet/' + id + '/delete')
                                .then(r => r.json())
                                .then(resp => {
                                    if (resp.success) {
                                        const row = document.getElementById('objet-row-' + id);
                                        if (row) row.remove();
                                        // Update count badge
                                        const badge = document.querySelector('h1 .badge');
                                        if (badge) {
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