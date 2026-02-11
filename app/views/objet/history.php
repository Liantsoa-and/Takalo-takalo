<div class="container py-3">
  <div class="row">
    <div class="col-12">
      <h1>Historique d'appartenance</h1>
      <h4 class="text-muted"><?php echo htmlspecialchars($objet['libelle'] ?? 'Objet'); ?></h4>
      <hr>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <?php if (empty($timeline)) : ?>
        <p class="text-muted">Aucun historique disponible pour cet objet.</p>
      <?php else : ?>
        <ul class="list-group">
          <?php foreach ($timeline as $entry) : ?>
            <li class="list-group-item d-flex align-items-center">
              <img src="/public/assets/images/pdp/<?php echo htmlspecialchars($entry['pdp'] ?? 'default.png'); ?>" alt="avatar" class="rounded-circle me-3" width="48" height="48">
              <div>
                <strong><?php echo htmlspecialchars($entry['username'] ?? 'Utilisateur'); ?></strong>
                <div class="text-muted small">
                  <?php if (!empty($entry['date'])): ?>
                    <?php echo date('d/m/Y', strtotime($entry['date'])); ?> — <?php echo htmlspecialchars($entry['note'] ?? ''); ?>
                  <?php else: ?>
                    Actuel
                  <?php endif; ?>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
