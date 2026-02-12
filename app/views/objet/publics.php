<?php $base = Flight::get('base_path') ?? ''; ?>
<?php $categories = $categories ?? [];
$scope = 'public'; ?>

<main class="col-12 col-md-10 py-4">
    <?php include __DIR__ . '/search.php'; ?>

    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Objets disponibles pour échange</h1>
        </div>
        <div class="col-md-4 text-end">
            <!-- Optional: add something here if needed -->
        </div>
    </div>

    <?php if (empty($objets)): ?>
        <div class="alert alert-info">
            Aucun objet disponible pour l'échange pour le moment.
        </div>
    <?php else: ?>
        <div id="objects-list-public" class="objects-list">
            <?php include __DIR__ . '/_cards.php'; ?>
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
    <?php endif; ?>
</main>