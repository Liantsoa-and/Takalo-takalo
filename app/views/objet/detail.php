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
                        <div class="card-body text-center">
                            <p class="text-muted">Aucune photo disponible pour le moment</p>
                            <!-- Les photos seront affichées ici via la table tt_photos -->
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