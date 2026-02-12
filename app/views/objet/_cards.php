<?php 
$base = Flight::get('base_path') ?? '';
$scope = $scope ?? 'public'; 
?>

<style>
/* Card Styles */
.tk-object-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    background: var(--tk-bg-card, #1a1a1a);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}
.tk-object-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(149, 1, 1, 0.25);
}
.tk-card-img-wrap {
    position: relative;
    height: 180px;
    overflow: hidden;
    background: linear-gradient(135deg, #2a0a0a, #1a0000);
}
.tk-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.tk-object-card:hover .tk-card-img-wrap img {
    transform: scale(1.08);
}
.tk-card-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #2a0a0a, #1a0000);
}
.tk-card-img-placeholder i {
    font-size: 3rem;
    opacity: 0.3;
    color: #fff;
}
.tk-price-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, var(--tk-dark-red, #950101), var(--tk-red, #c30000));
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.tk-card-body {
    padding: 1.25rem;
}
.tk-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--tk-text-heading, #fff);
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tk-card-category {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--tk-text-muted, #aaa);
    margin-bottom: 0.75rem;
}
.tk-card-category i {
    color: var(--tk-red, #c30000);
}
.tk-card-desc {
    font-size: 0.875rem;
    color: var(--tk-text-secondary, #ccc);
    line-height: 1.5;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.tk-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.tk-card-actions {
    display: flex;
    gap: 8px;
}
.tk-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.05);
    color: var(--tk-text-muted, #aaa);
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid rgba(255,255,255,0.1);
}
.tk-action-btn:hover {
    background: var(--tk-red, #c30000);
    color: #fff;
    transform: scale(1.1);
}
.tk-action-btn.danger:hover {
    background: #dc3545;
}
.tk-action-btn i {
    font-size: 0.9rem;
}
.tk-owner-info {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.tk-owner-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.1);
}
.tk-owner-name {
    font-size: 0.8rem;
    color: var(--tk-text-muted, #aaa);
    transition: color 0.2s;
}
.tk-owner-info:hover .tk-owner-name {
    color: var(--tk-red, #c30000);
}
</style>

<?php if (empty($objets)): ?>
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size: 4rem; opacity: 0.3; color: var(--tk-red);"></i>
            <h5 class="mt-3 text-muted">Aucun objet trouvé</h5>
            <p class="text-muted small">Essayez de modifier vos critères de recherche</p>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($objets as $objet): ?>
        <div class="col">
            <div class="card tk-object-card">
                <!-- Image -->
                <div class="tk-card-img-wrap">
                    <?php 
                    $photo = $objet['photo'] ?? $objet['main_photo'] ?? null;
                    if ($photo): ?>
                        <img src="<?= $base ?>/uploads/photos/<?= htmlspecialchars($photo) ?>" 
                             alt="<?= htmlspecialchars($objet['libelle']) ?>">
                    <?php else: ?>
                        <div class="tk-card-img-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Price Badge -->
                    <span class="tk-price-badge">
                        <?= number_format($objet['prix_estimatif'] ?? 0, 0, ',', ' ') ?> Ar
                    </span>
                </div>
                
                <!-- Body -->
                <div class="tk-card-body">
                    <h5 class="tk-card-title" title="<?= htmlspecialchars($objet['libelle']) ?>">
                        <?= htmlspecialchars($objet['libelle']) ?>
                    </h5>
                    
                    <div class="tk-card-category">
                        <i class="bi bi-tag-fill"></i>
                        <?= htmlspecialchars($objet['category_name'] ?? 'Non catégorisé') ?>
                    </div>
                    
                    <p class="tk-card-desc">
                        <?= htmlspecialchars($objet['description'] ?? 'Aucune description') ?>
                    </p>
                    
                    <!-- Footer -->
                    <div class="tk-card-footer">
                        <div class="tk-card-actions">
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>" class="tk-action-btn" title="Voir détails">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            <?php if ($scope === 'mine'): ?>
                                <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/edit" class="tk-action-btn" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="#" class="tk-action-btn danger" title="Supprimer"
                                   onclick="event.preventDefault(); if(confirm('Supprimer cet objet ?')) window.location.href='<?= $base ?>/objet/<?= $objet['id'] ?>/delete'">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= $base ?>/objet/<?= $objet['id'] ?>/history" class="tk-action-btn" title="Historique">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </div>
                        
                        <?php if ($scope !== 'mine' && !empty($objet['owner_name'])): ?>
                            <a href="<?= $base ?>/profil/<?= $objet['user_id'] ?? $objet['owner_id'] ?? 0 ?>/user" class="tk-owner-info">
                                <img src="<?= $base ?>/assets/images/pdp/<?= htmlspecialchars($objet['owner_pdp'] ?? 'default.png') ?>" 
                                     alt="<?= htmlspecialchars($objet['owner_name']) ?>" 
                                     class="tk-owner-avatar">
                                <span class="tk-owner-name"><?= htmlspecialchars($objet['owner_name']) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>