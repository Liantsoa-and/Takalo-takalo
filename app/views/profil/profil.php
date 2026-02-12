<?php
// Vue de profil utilisateur
// Variables attendues : $user (array)
$u = isset($user) ? $user : null;
$base = Flight::get('base_path') ?? '';
?>
<div class="container">
    <div class="card shadow-sm">
        <div class="card-body d-flex flex-column align-items-center text-center py-4">
            <div class="mb-3">
                <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($u['pdp'] ?? 'default.png') ?>" alt="pdp" class="rounded-circle mx-auto" style="width:220px;height:220px;object-fit:cover;">
            </div>
            <div>
                <h4 class="mb-0"><?= htmlspecialchars($u['username'] ?? 'Utilisateur') ?></h4>
                <small class="text-muted"><?= htmlspecialchars($u['role'] ?? '') ?></small>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Description</h6>
                <p class="text-muted"><?= nl2br(htmlspecialchars($u['bio'] ?? '')) ?></p>
            </div>
        </div>
    </div>

    <?php if (!empty($objets) && is_array($objets)): ?>
    <div class="mt-4">
        <h5>Objets de <?= htmlspecialchars($u['username'] ?? 'Utilisateur') ?></h5>
        <div class="row g-3 mt-2">
            <?php foreach ($objets as $objet): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <?php
                            $photo = !empty($objet['main_photo']) ? $objet['main_photo'] : null;
                            $photoUrl = $photo ? $base . '/uploads/photos/' . $photo : $base . '/assets/images/logo.png';
                        ?>
                        <img src="<?= $photoUrl ?>" class="card-img-top" style="height:160px;object-fit:cover;" alt="<?= htmlspecialchars($objet['libelle']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1" style="font-size:0.95rem"><?= htmlspecialchars($objet['libelle']) ?></h6>
                            <p class="text-muted small mb-2" style="flex:1"><?= htmlspecialchars(substr($objet['description'] ?? '', 0, 100)) ?><?= (strlen($objet['description'] ?? '')>100)?'...':'' ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>