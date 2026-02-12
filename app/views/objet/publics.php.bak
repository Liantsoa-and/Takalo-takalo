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

    

    <p class="text-muted mb-4"><?= $total ?> objet(s) trouvé(s)</p>

    <div id="objects-list-public" class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        <?php include __DIR__ . '/_cards.php'; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav aria-label="Navigation des pages"><ul class="pagination justify-content-center">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $currentPage-1 ?><?= $currentCategory ? '&category_id='.$currentCategory : '' ?>">Précédent</a></li>
            <?php for($i=1;$i<=$totalPages;$i++): ?><li class="page-item <?= $i==$currentPage?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?><?= $currentCategory ? '&category_id='.$currentCategory : '' ?>"><?= $i ?></a></li><?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"><a class="page-link" href="?page=<?= $currentPage+1 ?><?= $currentCategory ? '&category_id='.$currentCategory : '' ?>">Suivant</a></li>
        </ul></nav>
    <?php endif; ?>
</main>