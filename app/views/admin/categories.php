<?php
$categoriesList = isset($categories) ? $categories : [];
$count = count($categoriesList);
?>
<main class="col-12 col-md-10 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Catégories <span class="badge bg-primary"><?= $count ?></span></h1>
        <div>
            <button id="addCategoryBtn" class="btn btn-sm btn-success me-2">
                <i class="bi bi-plus-circle me-1"></i>Ajouter
            </button>
            <a href="/admin/categories" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i>Rafraîchir
            </a>
        </div>
    </div>

    <!-- Stat card -->
    <div class="card text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, var(--tk-dark-red), var(--tk-red));">
        <div class="card-body d-flex align-items-center justify-content-between p-4">
            <div>
                <div class="h1 mb-0 display-4 fw-bold"><?= $count ?></div>
                <div class="text-uppercase small" style="opacity:0.8;">Catégories enregistrées</div>
            </div>
            <div class="text-end">
                <i class="bi bi-tags-fill" style="font-size:64px; line-height:1; opacity:0.6;"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nom</th>
                            <th style="width:140px">Objets liés</th>
                            <th style="width:140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
                        <?php $i = 1; foreach ($categoriesList as $cat): ?>
                            <tr id="cat-row-<?= (int)$cat['id'] ?>">
                                <td><?= $i++ ?></td>
                                <td class="libelle-cell"><?= htmlspecialchars($cat['libelle']) ?></td>
                                <td>
                                    <span class="badge <?= ($cat['nb_objets'] > 0) ? 'bg-warning text-dark' : 'bg-secondary' ?>">
                                        <?= (int)$cat['nb_objets'] ?> objet(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary btn-edit-cat"
                                                data-id="<?= (int)$cat['id'] ?>" title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete-cat"
                                                data-id="<?= (int)$cat['id'] ?>"
                                                data-libelle="<?= htmlspecialchars($cat['libelle']) ?>"
                                                data-objets="<?= (int)$cat['nb_objets'] ?>" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($categoriesList)): ?>
                            <tr id="emptyRow">
                                <td colspan="4" class="text-center text-muted py-3">Aucune catégorie trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajout / Modification -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalCategoryForm">
                    <input type="hidden" name="id" id="catId" value="">
                    <div class="mb-3">
                        <label class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" name="libelle" id="catLibelle" class="form-control" 
                               placeholder="Ex: Vêtements, Livres, Électronique..." required>
                        <div class="invalid-feedback" id="catLibelleError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="modalCatSaveBtn" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Suppression avec migration -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Supprimer la catégorie</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteCatId" value="">
                
                <!-- Message pour catégorie sans objets -->
                <div id="deleteSimpleMsg" class="d-none">
                    <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong id="deleteCatName"></strong> ?</p>
                    <p class="text-muted small">Cette action est irréversible.</p>
                </div>

                <!-- Message pour catégorie avec objets -->
                <div id="deleteMigrateMsg" class="d-none">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        La catégorie <strong id="deleteCatNameMigrate"></strong> est utilisée par 
                        <strong id="deleteCatObjets"></strong> objet(s).
                    </div>
                    <p>Choisissez la catégorie de remplacement pour ces objets :</p>
                    <select id="targetCategorySelect" class="form-select">
                        <option value="">-- Sélectionner une catégorie --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
