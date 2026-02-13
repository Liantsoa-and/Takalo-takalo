/**
 * categories.js — CRUD AJAX pour les catégories admin
 */
document.addEventListener('DOMContentLoaded', function () {

    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));

    const catIdInput = document.getElementById('catId');
    const catLibelleInput = document.getElementById('catLibelle');
    const catLibelleError = document.getElementById('catLibelleError');
    const modalLabel = document.getElementById('categoryModalLabel');
    const saveBtn = document.getElementById('modalCatSaveBtn');

    const deleteCatId = document.getElementById('deleteCatId');
    const deleteSimpleMsg = document.getElementById('deleteSimpleMsg');
    const deleteMigrateMsg = document.getElementById('deleteMigrateMsg');
    const deleteCatName = document.getElementById('deleteCatName');
    const deleteCatNameMigrate = document.getElementById('deleteCatNameMigrate');
    const deleteCatObjets = document.getElementById('deleteCatObjets');
    const targetCategorySelect = document.getElementById('targetCategorySelect');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // ============ AJOUTER ============
    document.getElementById('addCategoryBtn').addEventListener('click', function () {
        catIdInput.value = '';
        catLibelleInput.value = '';
        catLibelleInput.classList.remove('is-invalid');
        modalLabel.textContent = 'Nouvelle catégorie';
        categoryModal.show();
    });

    // ============ MODIFIER (ouvrir modal) ============
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit-cat');
        if (!btn) return;
        const id = btn.dataset.id;

        fetch('/admin/category/' + id)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    catIdInput.value = data.category.id;
                    catLibelleInput.value = data.category.libelle;
                    catLibelleInput.classList.remove('is-invalid');
                    modalLabel.textContent = 'Modifier la catégorie';
                    categoryModal.show();
                } else {
                    alert(data.message || 'Erreur');
                }
            })
            .catch(() => alert('Erreur réseau'));
    });

    // ============ ENREGISTRER (create / update) ============
    saveBtn.addEventListener('click', function () {
        const libelle = catLibelleInput.value.trim();
        if (!libelle) {
            catLibelleInput.classList.add('is-invalid');
            catLibelleError.textContent = 'Le nom est obligatoire.';
            return;
        }
        catLibelleInput.classList.remove('is-invalid');

        const id = catIdInput.value;
        const isEdit = id !== '';
        const url = isEdit ? '/admin/category/' + id + '/update' : '/admin/category/create';

        const formData = new FormData();
        formData.append('libelle', libelle);

        fetch(url, { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    categoryModal.hide();
                    location.reload();
                } else {
                    catLibelleInput.classList.add('is-invalid');
                    catLibelleError.textContent = data.message || 'Erreur';
                }
            })
            .catch(() => alert('Erreur réseau'));
    });

    // Enter key in modal
    catLibelleInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveBtn.click();
        }
    });

    // ============ SUPPRIMER ============
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete-cat');
        if (!btn) return;

        const id = btn.dataset.id;
        const libelle = btn.dataset.libelle;
        const nbObjets = parseInt(btn.dataset.objets || '0', 10);

        deleteCatId.value = id;

        if (nbObjets > 0) {
            // Catégorie utilisée : afficher migration
            deleteSimpleMsg.classList.add('d-none');
            deleteMigrateMsg.classList.remove('d-none');
            deleteCatNameMigrate.textContent = libelle;
            deleteCatObjets.textContent = nbObjets;

            // Remplir le select avec les autres catégories
            populateTargetSelect(id);
        } else {
            // Catégorie vide : suppression simple
            deleteMigrateMsg.classList.add('d-none');
            deleteSimpleMsg.classList.remove('d-none');
            deleteCatName.textContent = libelle;
        }

        deleteModal.show();
    });

    // Récupérer les catégories pour le select de migration
    function populateTargetSelect(excludeId) {
        targetCategorySelect.innerHTML = '<option value="">-- Sélectionner une catégorie --</option>';
        const rows = document.querySelectorAll('[id^="cat-row-"]');
        rows.forEach(row => {
            const rowId = row.id.replace('cat-row-', '');
            if (rowId === excludeId) return;
            const libelle = row.querySelector('.libelle-cell').textContent.trim();
            const option = document.createElement('option');
            option.value = rowId;
            option.textContent = libelle;
            targetCategorySelect.appendChild(option);
        });
    }

    // ============ CONFIRMER SUPPRESSION ============
    confirmDeleteBtn.addEventListener('click', function () {
        const id = deleteCatId.value;
        const isMigrate = !deleteMigrateMsg.classList.contains('d-none');

        if (isMigrate) {
            const targetId = targetCategorySelect.value;
            if (!targetId) {
                targetCategorySelect.classList.add('is-invalid');
                return;
            }
            targetCategorySelect.classList.remove('is-invalid');

            // Migration puis suppression
            const formData = new FormData();
            formData.append('target_category_id', targetId);

            fetch('/admin/category/' + id + '/migrate-delete', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        deleteModal.hide();
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur');
                    }
                })
                .catch(() => alert('Erreur réseau'));
        } else {
            // Suppression simple
            fetch('/admin/category/' + id + '/delete')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        deleteModal.hide();
                        const row = document.getElementById('cat-row-' + id);
                        if (row) row.remove();
                        // Vérifier si la table est vide
                        const tbody = document.getElementById('categoriesTableBody');
                        if (tbody && tbody.querySelectorAll('tr').length === 0) {
                            tbody.innerHTML = '<tr id="emptyRow"><td colspan="4" class="text-center text-muted py-3">Aucune catégorie trouvée.</td></tr>';
                        }
                    } else if (data.used) {
                        // La catégorie a des objets (cas de sécurité)
                        alert(data.message);
                    } else {
                        alert(data.message || 'Erreur');
                    }
                })
                .catch(() => alert('Erreur réseau'));
        }
    });
});
