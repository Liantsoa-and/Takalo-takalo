<?php
$objets = $objets ?? [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes objets - Takalo-takalo</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Mes objets</h1>
            </div>
            <div class="col-md-4 text-end">
                <a href="/objet/formulaire" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Ajouter un objet
                </a>
            </div>
        </div>

        <?php if (empty($objets)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore d'objets. <a href="/objet/formulaire">Créez votre premier objet</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($objets as $objet): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($objet['libelle']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($objet['description'], 0, 100)); ?>...
                                </p>
                                <p class="text-muted small">
                                    Catégorie: #<?php echo $objet['category_id']; ?> |
                                    Prix estimé: <?php echo number_format($objet['prix_estimatif'], 2, ',', ' '); ?> €
                                </p>
                                <div class="btn-group w-100" role="group">
                                    <a href="/objet/<?php echo $objet['id']; ?>" class="btn btn-sm btn-info">Voir</a>
                                    <a href="/objet/<?php echo $objet['id']; ?>/edit" class="btn btn-sm btn-warning">Éditer</a>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="confirmDelete(<?php echo $objet['id']; ?>)">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')) {
                // À implémenter
                console.log('Suppression de l\'objet ' + id);
            }
        }
    </script>
</body>

</html>