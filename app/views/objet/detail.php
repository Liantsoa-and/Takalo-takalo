<?php
$objet = $objet ?? [];
$base = Flight::get('base_path') ?? '';
?>

<main class="col-12 col-md-10 py-4">
    <div class="mb-4">
        <a href="<?= $base ?>/objets" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'echange_propose'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Succès !</strong> Votre proposition d'échange a été envoyée.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($objet)): ?>
        <div class="alert alert-danger">Objet non trouvé.</div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h1 class="card-title text-danger"><?= htmlspecialchars($objet['libelle']) ?></h1>

                        <div class="mb-3">
                            <h5>Description</h5>
                            <p class="text-white"><?= nl2br(htmlspecialchars($objet['description'])) ?></p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Prix estimé</h6>
                                <p class="h5 text-primary"><?= number_format($objet['prix_estimatif'], 2, ',', ' ') ?> €</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Catégorie</h6>
                                <p class="badge bg-info">#<?= htmlspecialchars($objet['category_id']) ?></p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>ID Objet</h6>
                            <p class="text-muted small"><?= htmlspecialchars($objet['id']) ?></p>
                        </div>

                        <div class="d-flex gap-2">
                            <?php if ($objet['user_id'] == ($currentUserId ?? 0)): ?>
                                <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/edit" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Éditer
                                </a>
                                <button class="btn btn-danger" onclick="confirmDelete(<?= $objet['id'] ?>)">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($objet['user_id'] != ($currentUserId ?? 0) && !empty($mesObjets ?? [])): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0" style="color:var(--tk-text-heading)"><i class="bi bi-arrow-left-right me-2"
                                    style="color:var(--tk-success)"></i>Proposer un échange</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Sélectionnez un de vos objets à proposer en échange :</p>
                            <form method="POST" action="<?= $base ?>/echange/propose">
                                <input type="hidden" name="objet2_id" value="<?= $objet['id'] ?>">

                                <div class="mb-3">
                                    <label for="objet1_id" class="form-label">Votre objet :</label>
                                    <select name="objet1_id" id="objet1_id" class="form-select" required>
                                        <option value="">-- Choisissez un objet --</option>
                                        <?php foreach ($mesObjets as $monObjet): ?>
                                            <option value="<?= $monObjet['id'] ?>"><?= htmlspecialchars($monObjet['libelle']) ?>
                                                (<?= number_format($monObjet['prix_estimatif'], 0, ',', ' ') ?> Ar)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success w-100"><i class="bi bi-send"></i> Envoyer la
                                    proposition</button>
                            </form>
                        </div>
                    </div>
                <?php elseif ($objet['user_id'] != ($currentUserId ?? 0) && empty($mesObjets ?? [])): ?>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        Vous devez avoir au moins un objet pour proposer un échange.
                        <a href="<?= $base ?>/objet/formulaire" class="alert-link">Ajouter un objet</a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($echanges ?? [])): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Propositions d'échange</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($echanges as $echange): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($echange['objet1']) ?> <i
                                                    class="bi bi-arrow-left-right"></i> <?= htmlspecialchars($echange['objet2']) ?>
                                            </h6>
                                            <small
                                                class="text-muted"><?= date('d/m/Y', strtotime($echange['date_echange'])) ?></small>
                                        </div>
                                        <p class="mb-1"><small>Proposé par :
                                                <strong><?= htmlspecialchars($echange['user1']) ?></strong></small></p>
                                        <span
                                            class="badge bg-<?= $echange['status'] == 'en attente' ? 'warning' : ($echange['status'] == 'confirme' ? 'success' : 'danger') ?>"><?= htmlspecialchars($echange['status']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Photos</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($objet['photos'])): ?>
                            <div id="carouselPhotos" class="carousel slide mb-3" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($objet['photos'] as $index => $photo): ?>
                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                            <img src="<?= $base ?>/uploads/photos/<?= htmlspecialchars($photo['url']) ?>"
                                                class="d-block w-100 rounded" alt="Photo de l'objet"
                                                style="max-height:400px;object-fit:cover;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($objet['photos']) > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPhotos"
                                        data-bs-slide="prev"><span class="carousel-control-prev-icon"
                                            aria-hidden="true"></span><span class="visually-hidden">Précédent</span></button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselPhotos"
                                        data-bs-slide="next"><span class="carousel-control-next-icon"
                                            aria-hidden="true"></span><span class="visually-hidden">Suivant</span></button>
                                <?php endif; ?>
                            </div>
                            <div class="row g-2">
                                <?php foreach ($objet['photos'] as $index => $photo): ?>
                                    <div class="col-4" style="cursor:pointer;"
                                        onclick="document.getElementById('carouselPhotos').carousel(<?= $index ?>)">
                                        <img src="<?= $base ?>/uploads/photos/<?= htmlspecialchars($photo['url']) ?>"
                                            class="img-fluid rounded" alt="Photo">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Aucune photo disponible pour le moment</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card mt-3 border-primary border-2">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-gem"></i> Objets au prix similaire</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Découvrez des objets avec un prix estimatif proche :</p>
                        <input type="hidden" id="objet_id" value="<?= $objet['id'] ?>">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg fw-bold" onclick="findSimilarObjects(10)">
                                <i class="bi bi-search"></i> ±10% du prix
                            </button>
                            <button class="btn btn-primary btn-lg fw-bold" onclick="findSimilarObjects(20)">
                                <i class="bi bi-search"></i> ±20% du prix
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function confirmDelete(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) window.location.href = '<?= $base ?>/objet/' + id + '/delete';
        }
    </script>
</main>