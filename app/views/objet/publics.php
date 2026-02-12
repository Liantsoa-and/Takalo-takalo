<?php $base = Flight::get('base_path') ?? ''; ?>
<?php $categories = $categories ?? [];
$scope = 'public'; ?>

<main class="col-12 col-md-9 col-lg-10 py-4">
    <!-- Header de la page -->
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="mb-2">Objets disponibles pour échange</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-grid-3x3 me-1"></i>
                <?= $total ?? 0 ?> objet(s) trouvé(s)
            </p>
        </div>

        <!-- View Toggle -->
        <div class="view-toggle">
            <button type="button" class="active" data-view="grid" title="Vue grille">
                <i class="bi bi-grid-3x3"></i>
            </button>
            <button type="button" data-view="list" title="Vue liste">
                <i class="bi bi-list-ul"></i>
            </button>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="filter-section mb-4">
        <form id="searchForm-<?= htmlspecialchars($scope) ?>" class="search-form"
            data-scope="<?= htmlspecialchars($scope) ?>" data-target="#objects-list-public" data-base="<?= $base ?>">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" name="q" class="form-control border-start-0"
                            placeholder="Rechercher un objet..." value="<?= htmlspecialchars($q ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="category_id" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['id']) ?>" <?= (isset($currentCategory) && $currentCategory == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>
                        Rechercher
                    </button>
                </div>
            </div>
        </form>

        <!-- Filtres actifs -->
        <?php if (!empty($q) || !empty($currentCategory)): ?>
            <div class="filter-chips">
                <?php if (!empty($q)): ?>
                    <span class="filter-chip active">
                        <i class="bi bi-search me-1"></i> "<?= htmlspecialchars($q) ?>"
                        <span class="remove-chip"
                            onclick="document.querySelector('input[name=q]').value=''; this.closest('form').submit();">×</span>
                    </span>
                <?php endif; ?>

                <?php if (!empty($currentCategory)): ?>
                    <span class="filter-chip active">
                        <i class="bi bi-tag me-1"></i> Catégorie
                        <span class="remove-chip"
                            onclick="document.querySelector('select[name=category_id]').value=''; this.closest('form').submit();">×</span>
                    </span>
                <?php endif; ?>

                <a href="<?= $base ?>/objets_publics" class="filter-chip">
                    <i class="bi bi-x-circle me-1"></i> Tout effacer
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Liste des objets -->
    <div id="objects-list-public" class="objects-list fade-in">
        <?php if (empty($objets)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 class="empty-state-title">Aucun objet trouvé</h3>
                <p class="empty-state-text">
                    <?php if (!empty($q) || !empty($currentCategory)): ?>
                        Essayez de modifier vos critères de recherche
                    <?php else: ?>
                        Il n'y a pas encore d'objets disponibles pour l'échange
                    <?php endif; ?>
                </p>
                <a href="<?= $base ?>/objets_publics" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i>
                    Actualiser
                </a>
            </div>
        <?php else: ?>
            <!-- Grid View -->
            <div class="grid-view">
                <?php foreach ($objets as $objet): ?>
                    <div class="col">
                        <div class="card object-card">
                            <!-- Image avec badges -->
                            <div class="position-relative">
                                <?php if (!empty($objet['main_photo'])): ?>
                                    <img src="/uploads/photos/<?= htmlspecialchars($objet['main_photo']) ?>"
                                        class="card-img-top object-img" alt="<?= htmlspecialchars($objet['libelle']) ?>"
                                        loading="lazy">
                                <?php else: ?>
                                    <div
                                        class="card-img-top object-img bg-gradient d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Badge catégorie -->
                                <?php if (!empty($objet['category_name'])): ?>
                                    <span class="category-badge">
                                        <i class="bi bi-tag me-1"></i>
                                        <?= htmlspecialchars($objet['category_name']) ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Badge prix -->
                                <span class="price-badge">
                                    <?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0, 0, ',', ' ')) ?> Ar
                                </span>
                            </div>

                            <!-- Corps de la card -->
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($objet['libelle']) ?>
                                </h5>

                                <p class="card-text text-muted">
                                    <?= htmlspecialchars(substr($objet['description'] ?? '', 0, 120)) ?>
                                    <?= strlen($objet['description'] ?? '') > 120 ? '...' : '' ?>
                                </p>

                                <!-- Actions -->
                                <div class="card-actions">
                                    <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="icon-action icon-view"
                                        title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history" class="icon-action icon-history"
                                        title="Historique">
                                        <i class="bi bi-clock-history"></i>
                                    </a>

                                    <!-- Info propriétaire -->
                                    <div class="owner-info">
                                        <span class="badge bg-secondary">
                                            Par <?= htmlspecialchars($objet['owner_name'] ?? 'Inconnu') ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- List View (hidden by default) -->
            <div class="list-view d-none">
                <?php foreach ($objets as $objet): ?>
                    <div class="object-list-item">
                        <!-- Thumbnail -->
                        <?php if (!empty($objet['main_photo'])): ?>
                            <img src="/uploads/photos/<?= htmlspecialchars($objet['main_photo']) ?>" class="object-thumbnail"
                                alt="<?= htmlspecialchars($objet['libelle']) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="object-thumbnail bg-gradient d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-white" style="font-size: 2rem; opacity: 0.5;"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Details -->
                        <div class="object-details">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="object-title mb-0">
                                    <?= htmlspecialchars($objet['libelle']) ?>
                                </h5>
                                <span class="badge bg-success ms-2">
                                    <?= htmlspecialchars(number_format($objet['prix_estimatif'] ?? 0, 0, ',', ' ')) ?> Ar
                                </span>
                            </div>

                            <p class="object-meta mb-2">
                                <i class="bi bi-tag me-1"></i>
                                <?= htmlspecialchars($objet['category_name'] ?? '') ?>
                                <span class="mx-2">•</span>
                                <i class="bi bi-person me-1"></i>
                                Par <?= htmlspecialchars($objet['owner_name'] ?? 'Inconnu') ?>
                            </p>

                            <p class="object-description">
                                <?= htmlspecialchars($objet['description'] ?? '') ?>
                            </p>

                            <div class="object-footer">
                                <div class="d-flex gap-2">
                                    <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i> Voir
                                    </a>
                                    <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-clock-history me-1"></i> Historique
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (($totalPages ?? 1) > 1): ?>
        <nav aria-label="Navigation des pages" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Précédent -->
                <li class="page-item <?= ($currentPage ?? 1) <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?page=<?= ($currentPage ?? 1) - 1 ?><?= !empty($currentCategory) ? '&category_id=' . $currentCategory : '' ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?>">
                        <i class="bi bi-chevron-left"></i> Précédent
                    </a>
                </li>

                <!-- Pages -->
                <?php
                $start = max(1, ($currentPage ?? 1) - 2);
                $end = min($totalPages ?? 1, ($currentPage ?? 1) + 2);

                if ($start > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=1<?= !empty($currentCategory) ? '&category_id=' . $currentCategory : '' ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?>">1</a>
                    </li>
                    <?php if ($start > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                        <a class="page-link"
                            href="?page=<?= $i ?><?= !empty($currentCategory) ? '&category_id=' . $currentCategory : '' ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($end < ($totalPages ?? 1)): ?>
                    <?php if ($end < ($totalPages ?? 1) - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=<?= $totalPages ?><?= !empty($currentCategory) ? '&category_id=' . $currentCategory : '' ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?>"><?= $totalPages ?></a>
                    </li>
                <?php endif; ?>

                <!-- Suivant -->
                <li class="page-item <?= ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                        href="?page=<?= ($currentPage ?? 1) + 1 ?><?= !empty($currentCategory) ? '&category_id=' . $currentCategory : '' ?><?= !empty($q) ? '&q=' . urlencode($q) : '' ?>">
                        Suivant <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</main>

<script>
    // Toggle entre vue grille et vue liste
    document.addEventListener('DOMContentLoaded', function () {
        const viewButtons = document.querySelectorAll('.view-toggle button');
        const gridView = document.querySelector('.grid-view');
        const listView = document.querySelector('.list-view');

        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const view = this.dataset.view;

                // Update active state
                viewButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Toggle views
                if (view === 'grid') {
                    gridView?.classList.remove('d-none');
                    listView?.classList.add('d-none');
                } else {
                    gridView?.classList.add('d-none');
                    listView?.classList.remove('d-none');
                }

                // Save preference
                localStorage.setItem('preferredView', view);
            });
        });

        // Load saved preference
        const savedView = localStorage.getItem('preferredView');
        if (savedView) {
            const button = document.querySelector(`[data-view="${savedView}"]`);
            if (button) button.click();
        }
    });
</script>