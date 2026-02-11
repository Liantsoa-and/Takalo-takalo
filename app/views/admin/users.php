<!-- Main content -->
<?php
$usersList = isset($utilisateur) ? $utilisateur : (isset($users) ? $users : []);
$count = count($usersList);
?>
<main class="col-12 col-md-10 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Utilisateurs <span class="badge bg-primary"><?= $count ?></span></h1>
        <div>
            <button id="addUserBtn" class="btn btn-sm btn-success me-2"><i
                    class="bi bi-person-plus me-1"></i>Ajouter</button>
            <a href="/admin_users" class="btn btn-sm btn-outline-secondary"><i
                    class="bi bi-arrow-clockwise me-1"></i>Rafraîchir</a>
        </div>
    </div>

    <!-- Large yellow card for user count -->
    <div class="card text-white bg-warning mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between p-4">
            <div>
                <div class="h1 mb-0 display-4 fw-bold"><?= $count ?></div>
                <div class="text-uppercase small text-white-25">Utilisateurs inscrits</div>
            </div>
            <div class="text-end">
                <i class="bi bi-people-fill" style="font-size:64px; line-height:1; color:inherit;"></i>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nom d'utilisateur</th>
                            <th style="width:160px">Rôle</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($usersList as $user):
                            $uid = $user['id'] ?? ($user['user_id'] ?? ''); ?>
                            <tr id="user-row-<?= htmlspecialchars($uid) ?>">
                                <td><?= $i++ ?></td>
                                <td class="username-cell">
                                    <?= htmlspecialchars($user['username'] ?? ($user['user'] ?? '')) ?></td>
                                <td class="role-cell"><span
                                        class="badge bg-secondary"><?= htmlspecialchars($user['role'] ?? '') ?></span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary btn-edit"
                                            data-id="<?= htmlspecialchars($uid) ?>" title="Modifier"><i
                                                class="bi bi-pencil-square"></i></button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete"
                                            data-id="<?= htmlspecialchars($uid) ?>" title="Supprimer"><i
                                                class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($usersList)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Aucun utilisateur trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal for Create/Edit -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalUserForm">
                    <input type="hidden" name="id" value="">
                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe (laisser vide si inchangé)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-select">
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" id="modalSaveBtn" class="btn btn-primary">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Page-specific script is loaded from /assets/js/users.js via modele.php -->