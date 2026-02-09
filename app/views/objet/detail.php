<?php
$objet = $objet ?? [];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($objet['libelle'] ?? 'Détails de l\'objet'); ?> - Takalo-takalo</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="mb-4">
            <a href="/objets" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <?php if (empty($objet)): ?>
            <div class="alert alert-danger">
                Objet non trouvé.
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h1 class="card-title"><?php echo htmlspecialchars($objet['libelle']); ?></h1>

                            <div class="mb-3">
                                <h5>Description</h5>
                                <p><?php echo nl2br(htmlspecialchars($objet['description'])); ?></p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6>Prix estimé</h6>
                                    <p class="h5 text-primary">
                                        <?php echo number_format($objet['prix_estimatif'], 2, ',', ' '); ?> €
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Catégorie</h6>
                                    <p class="badge bg-info text-dark">
                                        #<?php echo htmlspecialchars($objet['category_id']); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6>ID Objet</h6>
                                <p class="text-muted small"><?php echo htmlspecialchars($objet['id']); ?></p>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="/objet/<?php echo $objet['id']; ?>/edit" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Éditer
                                </a>
                                <button class="btn btn-danger" onclick="confirmDelete(<?php echo $objet['id']; ?>)">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                                <a href="/exchange/propose/<?php echo $objet['id']; ?>" class="btn btn-success">
                                    <i class="bi bi-arrow-left-right"></i> Proposer un échange
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Photos</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($objet['photos'])): ?>
                                <div id="carouselPhotos" class="carousel slide mb-3" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach ($objet['photos'] as $index => $photo): ?>
                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <img src="<?php echo htmlspecialchars($photo['url']); ?>"
                                                    class="d-block w-100 rounded" alt="Photo de l'objet"
                                                    style="max-height: 400px; object-fit: cover;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (count($objet['photos']) > 1): ?>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselPhotos"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Précédent</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselPhotos"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Suivant</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="row gap-2">
                                    <?php foreach ($objet['photos'] as $photo): ?>
                                        <div class="col-md-3">
                                            <img src="<?php echo htmlspecialchars($photo['url']); ?>"
                                                class="img-fluid rounded cursor-pointer" alt="Photo"
                                                onclick="document.querySelector('#carouselPhotos').style.display='block'"
                                                style="cursor: pointer;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Aucune photo disponible pour le moment</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) {
                window.location.href = '/objet/' + id + '/delete';
            }
        }
    </script>
</body>

</html>