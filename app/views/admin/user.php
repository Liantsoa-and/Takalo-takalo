<?php
// $user may be provided by controller
$u = isset($user) ? $user : null;
$id = $u['id'] ?? '';
?>

<div class="col-12">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Modifier utilisateur</h2>
    <div>
      <?php if ($id): ?>
        <button id="deleteBtn" class="btn btn-sm btn-danger">Supprimer</button>
      <?php endif; ?>
    </div>
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form id="userForm">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <div class="mb-3">
          <label class="form-label">Nom d'utilisateur</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u['username'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
          <input type="password" name="password" class="form-control" value="">
        </div>
        <div class="mb-3">
          <label class="form-label">Rôle</label>
          <select name="role" class="form-select">
            <option value="user" <?= (isset($u['role']) && $u['role']==='user')?'selected':'' ?>>user</option>
            <option value="admin" <?= (isset($u['role']) && $u['role']==='admin')?'selected':'' ?>>admin</option>
          </select>
        </div>
        <div class="d-flex gap-2">
          <?php if ($id): ?>
            <button type="button" id="saveBtn" class="btn btn-primary">Enregistrer</button>
          <?php else: ?>
            <button type="button" id="createBtn" class="btn btn-success">Créer</button>
          <?php endif; ?>
          <a href="/admin/users" class="btn btn-outline-secondary">Retour</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
(function(){
  const id = <?= json_encode($id) ?>;
  const apiCreate = '/admin/user/create';
  const apiGet = (id)=>`/admin/user/${id}`;
  const apiUpdate = (id)=>`/admin/user/${id}/update`;
  const apiDelete = (id)=>`/admin/user/${id}/delete`;

  function postJson(url, data){
    return fetch(url, {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data)})
      .then(r=>r.json());
  }

  document.getElementById('saveBtn')?.addEventListener('click', function(){
    const form = document.getElementById('userForm');
    const fd = Object.fromEntries(new FormData(form).entries());
    postJson(apiUpdate(id), fd).then(resp=>{
      if(resp.success){ alert('Mis à jour'); location.reload(); } else alert('Erreur');
    });
  });

  document.getElementById('createBtn')?.addEventListener('click', function(){
    const form = document.getElementById('userForm');
    const fd = Object.fromEntries(new FormData(form).entries());
    postJson(apiCreate, fd).then(resp=>{
      if(resp.success){ alert('Créé'); window.location.href = '/admin/'+resp.id+'/users'; } else alert('Erreur');
    });
  });

  document.getElementById('deleteBtn')?.addEventListener('click', function(){
    if(!confirm('Supprimer cet utilisateur ?')) return;
    fetch(apiDelete(id)).then(r=>r.json()).then(resp=>{
      if(resp.success){ alert('Supprimé'); window.location.href = '/admin/users'; } else alert('Erreur');
    });
  });
})();
</script>
