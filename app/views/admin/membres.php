<?php
$membres = $membres ?? [];
$base = Flight::get('base_path') ?? '';
?>

<style>
/* Forcer le texte en blanc pour lisibilité sur fond sombre */
.membre-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); }
.admin-membre-text, .admin-membre-text h5, .admin-membre-text p, .admin-membre-text .small, .admin-membre-text .text-muted {
  color: #ffffff !important;
}
</style>

<style>
/* Avatars mis en valeur */
.membre-avatar { width: 200px; height: 200px; object-fit: cover; border-radius:50%; border:4px solid rgba(255,255,255,0.12); box-shadow: 0 12px 30px rgba(0,0,0,0.6); transition: transform .18s ease, box-shadow .18s ease; }
.membre-avatar:hover { transform: translateY(-8px) scale(1.03); box-shadow: 0 18px 40px rgba(0,0,0,0.65); }
.membre-initials { width: 200px; height: 200px; font-size: 40px; border-radius:50%; border:4px solid rgba(255,255,255,0.12); box-shadow: 0 12px 30px rgba(0,0,0,0.6); display:inline-flex; align-items:center; justify-content:center; transition: transform .18s ease, box-shadow .18s ease; }
.membre-initials:hover { transform: translateY(-8px) scale(1.03); box-shadow: 0 18px 40px rgba(0,0,0,0.65); }
@media (max-width: 991px) {
  .membre-avatar, .membre-initials { width: 160px; height: 160px; font-size: 32px; }
}
@media (max-width: 575px) {
  .membre-avatar, .membre-initials { width: 120px; height: 120px; font-size: 22px; }
}
</style>
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Membres du projet</h1>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddMembre">
            <i class="bi bi-person-plus"></i> Ajouter un membre
        </button>
    </div>
</div>

<?php if (empty($membres)): ?>
    <div class="alert alert-info">Aucun membre enregistré.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($membres as $m): ?>
            <div class="col-md-4">
              <div class="card h-100 membre-card">
                <div class="card-body text-center admin-membre-text">
                        <?php if (!empty($m['photo'])): ?>
                          <img src="/assets/images/membre/<?= htmlspecialchars($m['photo']) ?>" alt="<?= htmlspecialchars($m['nom']) ?>" class="rounded-circle mb-3 membre-avatar">
                        <?php else: ?>
                          <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3 membre-initials" style="font-weight:700;">
                            <?= htmlspecialchars(substr($m['prenom'],0,1) . substr($m['nom'],0,1)) ?>
                          </div>
                        <?php endif; ?>
                        <h5 class="card-title mb-1"><?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></h5>
                        <div class="text-muted small mb-2"><?= htmlspecialchars($m['etu']) ?></div>
                        <p class="small"><?= nl2br(htmlspecialchars($m['bio'] ?? '')) ?></p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="<?= $base ?>/admin/membre/<?= $m['id'] ?>/delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce membre ?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal: ajouter membre -->
<div class="modal fade" id="modalAddMembre" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="<?= $base ?>/admin/membre/create" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter un membre</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Prénom</label>
            <input name="prenom" type="text" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Nom</label>
            <input name="nom" type="text" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Numéro ETU</label>
            <input name="etu" type="text" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Photo (fichier)</label>
            <input name="photo" type="file" class="form-control" accept="image/*">
          </div>
          <div class="mb-2">
            <label class="form-label">Bio</label>
            <textarea name="bio" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>
