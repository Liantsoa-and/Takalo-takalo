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
            <?php include __DIR__ . '/_cards.php'; ?>
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