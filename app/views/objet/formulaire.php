<?php
$objet = $objet ?? null;
$categories = $categories ?? [];
$isEdit = !empty($objet);
$title = $isEdit ? 'Modifier un objet' : 'Ajouter un objet';
$base = Flight::get('base_path') ?? '';
?>

<main class="col-12 col-md-10 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4 class="mb-0"><?= $title ?></h4></div>
                <div class="card-body">
                    <form id="objetForm" method="POST" enctype="multipart/form-data" novalidate action="<?= $isEdit ? $base . '/objet/' . $objet['id'] . '/update' : $base . '/objet/create' ?>">

                        <!-- Titre de l'objet -->
                        <div class="mb-3">
                            <label for="libelle" class="form-label"><span class="text-danger">*</span> Titre de l'objet</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" value="<?= $isEdit ? htmlspecialchars($objet['libelle']) : '' ?>" placeholder="Ex: Veste en cuir" required minlength="3" maxlength="255">
                            <div class="invalid-feedback">Le titre est obligatoire (minimum 3 caractères)</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label"><span class="text-danger">*</span> Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Décrivez votre objet en détail..." required minlength="10"><?= $isEdit ? htmlspecialchars($objet['description']) : '' ?></textarea>
                            <small class="form-text text-muted">Minimum 10 caractères</small>
                            <div class="invalid-feedback">La description est obligatoire (minimum 10 caractères)</div>
                        </div>

                        <div class="row">
                            <!-- Catégorie -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label"><span class="text-danger">*</span> Catégorie</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= $isEdit && $objet['category_id'] == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner une catégorie</div>
                            </div>

                            <!-- Prix estimatif -->
                            <div class="col-md-6 mb-3">
                                <label for="prix_estimatif" class="form-label"><span class="text-danger">*</span> Prix estimatif (€)</label>
                                <input type="number" class="form-control" id="prix_estimatif" name="prix_estimatif" value="<?= $isEdit ? htmlspecialchars($objet['prix_estimatif']) : '' ?>" placeholder="0.00" step="0.01" min="0" max="9999.99" required>
                                <div class="invalid-feedback">Le prix est obligatoire et doit être un nombre positif</div>
                            </div>
                        </div>

                        <!-- Photos -->
                        <div class="mb-3">
                            <label for="photos" class="form-label">Photos de l'objet</label>
                            <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                            <small class="form-text text-muted">Vous pouvez télécharger plusieurs photos (JPG, PNG, etc.)</small>
                        </div>

                        <!-- Photos existantes (en édition) -->
                        <?php if ($isEdit && !empty($objet['photos'])): ?>
                            <div class="mb-3">
                                <label>Photos existantes:</label>
                                <div class="row gap-2">
                                    <?php foreach ($objet['photos'] as $photo): ?>
                                        <div class="col-md-3 position-relative">
                                            <div class="position-relative">
                                                <img src="<?= htmlspecialchars($photo['url']) ?>" class="img-fluid rounded" alt="Photo de l'objet">
                                                <a href="<?= $base ?>/photo/<?= $photo['id'] ?>/delete" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette photo ?');" style="margin:5px;">✕</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr>
                            </div>
                        <?php endif; ?>

                        <!-- Aperçu des photos -->
                        <div id="photoPreview" class="mb-3 d-none">
                            <label>Aperçu des nouvelles photos:</label>
                            <div id="previewContainer" class="row gap-2"></div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Modifier' : 'Ajouter' ?> l'objet</button>
                            <a href="<?= $base ?>/objets" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            const form = document.getElementById('objetForm');
            if (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) { event.preventDefault(); event.stopPropagation(); }
                    form.classList.add('was-validated');
                }, false);
            }
        }, false);
    })();

    // Aperçu des photos
    document.addEventListener('DOMContentLoaded', function(){
        const photosInput = document.getElementById('photos');
        if (!photosInput) return;
        photosInput.addEventListener('change', function (e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('previewContainer');
            const photoPreview = document.getElementById('photoPreview');
            previewContainer.innerHTML = '';
            if (files.length > 0) {
                photoPreview.classList.remove('d-none');
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const div = document.createElement('div');
                        div.className = 'col-md-3';
                        div.innerHTML = `<div class="position-relative"><img src="${event.target.result}" class="img-fluid rounded" alt="Aperçu"><small class="text-muted d-block mt-1">${file.name}</small></div>`;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else { photoPreview.classList.add('d-none'); }
        });
    });
    </script>
</main>