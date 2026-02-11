<?php
$base = Flight::get('base_path') ?? '';
$q = $q ?? '';
$categories = $categories ?? [];
$scope = $scope ?? 'public'; // 'mine' or 'public'
$target = $scope === 'mine' ? 'objects-list-mine' : 'objects-list-public';
?>

<div class="mb-4">
    <form id="searchForm-<?= htmlspecialchars($scope) ?>" class="row g-2 search-form" data-scope="<?= htmlspecialchars($scope) ?>" data-target="#<?= $target ?>" data-base="<?= $base ?>">
        <div class="col-md-6">
            <input type="search" name="q" class="form-control" placeholder="Mots-clés..." value="<?= htmlspecialchars($q) ?>">
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>
</div>
