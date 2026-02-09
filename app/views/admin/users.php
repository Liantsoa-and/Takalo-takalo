<!-- Main content -->
<main class="col-12 col-md-10 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Utilisateurs <span class="badge bg-primary"><?= $count ?></span></h1>
        <div>
            <a href="/admin_users" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Rafraîchir</a>
        </div>
    </div>

    <!-- Large yellow card for user count -->
    <div class="card text-white bg-warning mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between p-4">
            <div>
                <div class="h1 mb-0 display-4 fw-bold"><?= $count ?></div>
                <div class="text-uppercase small text-white-50">Utilisateurs inscrits</div>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($usersList as $user): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($user['username'] ?? ($user['user'] ?? '')) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role'] ?? '') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($usersList)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Aucun utilisateur trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>