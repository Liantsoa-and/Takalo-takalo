<?php
    // Normalize incoming data
    $echangesList = isset($echanges) ? $echanges : [];
    $count = count($echangesList);
?>

<!-- Main content -->
<main class="col-12 col-md-10 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Échanges <span class="badge bg-primary"><?= $count ?></span></h1>
        <div>
            <a href="/admin_echange" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Rafraîchir</a>
        </div>
    </div>

    <!-- Large blue card for exchanges -->
    <div class="card text-white bg-info mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between p-4">
            <div>
                <div class="h1 mb-0 display-4 fw-bold"><?= $count ?></div>
                <div class="text-uppercase small text-white-25">Échanges enregistrés</div>
            </div>
            <div class="text-end">
                <i class="bi bi-arrow-left-right" style="font-size:64px; color:inherit;"></i>
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
                            <th>Utilisateur 1</th>
                            <th>Objet 1</th>
                            <th>Utilisateur 2</th>
                            <th>Objet 2</th>
                            <th style="width:140px">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($echangesList)): ?>
                            <?php $i = 1; foreach ($echangesList as $echange): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($echange['user1'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($echange['objet1'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($echange['user2'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($echange['objet2'] ?? '') ?></td>
                                    <?php
                                        $statusRaw = $echange['status'] ?? '';
                                        $statusKey = strtolower(str_replace(' ', '_', trim($statusRaw)));
                                        $badgeClass = 'secondary';
                                        if ($statusKey === 'en_attente' || $statusKey === 'en_attente') {
                                            $badgeClass = 'warning';
                                        } elseif ($statusKey === 'confirme' || $statusKey === 'accepté' || $statusKey === 'accepted') {
                                            $badgeClass = 'success';
                                        } elseif ($statusKey === 'refuser' || $statusKey === 'refusé' || $statusKey === 'rejected') {
                                            $badgeClass = 'danger';
                                        } elseif ($statusKey === 'libre') {
                                            $badgeClass = 'info';
                                        }
                                    ?>
                                    <td><span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($statusRaw) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Aucun échange trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>