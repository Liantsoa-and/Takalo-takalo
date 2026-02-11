<?php $base = Flight::get('base_path') ?? ''; ?>
<?php if (empty($objets)): ?>
    <div class="col-12"><div class="alert alert-info">Aucun objet trouvé.</div></div>
<?php else: foreach ($objets as $objet): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if (!empty($objet['main_photo']) || !empty($objet['photo'])): ?>
                <img src="<?= htmlspecialchars($objet['main_photo'] ?? $objet['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($objet['libelle']) ?>" style="height:180px;object-fit:cover;">
            <?php else: ?><div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height:180px;"><span class="text-white">Pas d'image</span></div><?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($objet['libelle']) ?></h5>
                <p class="card-text text-muted"><?= htmlspecialchars(substr($objet['description'] ?? '',0,120)) ?>...</p>
                <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="btn btn-primary">Voir</a>
            </div>
        </div>
    </div>
<?php endforeach; endif; ?>
