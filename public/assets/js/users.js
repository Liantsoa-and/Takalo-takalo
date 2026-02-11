document.addEventListener('DOMContentLoaded', function(){
  (function(){
    let modalEl = document.getElementById('userModal');
    let bsModal = null;
    const modalForm = document.getElementById('modalUserForm');
    const modalSaveBtn = document.getElementById('modalSaveBtn');

    function ensureBsModal(){
      modalEl = modalEl || document.getElementById('userModal');
      if(!modalEl){ console.warn('Modal element not found'); return null; }
      if(bsModal) return bsModal;
      if(typeof bootstrap === 'undefined' || !bootstrap.Modal){ console.warn('Bootstrap not ready'); return null; }
      bsModal = new bootstrap.Modal(modalEl);
      return bsModal;
    }

    function postJson(url, data){
      return fetch(url, {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data)})
        .then(r=>r.json());
    }

    function bindRowActions(row){
      row.querySelectorAll('.btn-edit').forEach(btn=>{
        btn.onclick = ()=>{ openEdit(btn.dataset.id); };
      });
      row.querySelectorAll('.btn-delete').forEach(btn=>{
        btn.onclick = ()=>{ doDelete(btn.dataset.id); };
      });
    }

    document.querySelectorAll('tr[id^="user-row-"]').forEach(bindRowActions);

    const addBtn = document.getElementById('addUserBtn');
    if(addBtn){
      addBtn.addEventListener('click', ()=>{
        if(!modalForm) return alert('Formulaire modal introuvable');
        modalForm.reset(); modalForm.id.value = '';
        document.getElementById('userModalLabel').textContent = 'Créer un utilisateur';
        modalSaveBtn.dataset.mode = 'create';
        const m = ensureBsModal(); if(m) m.show();
      });
    }

    function openEdit(id){
      fetch(`/admin/user/${id}`).then(r=>r.json()).then(data=>{
        if(!data.success){ alert('Impossible de charger l\'utilisateur'); return; }
        const u = data.user;
        modalForm.id.value = u.id || '';
        modalForm.username.value = u.username || '';
        modalForm.password.value = '';
        modalForm.role.value = u.role || 'user';
        document.getElementById('userModalLabel').textContent = 'Modifier utilisateur';
        modalSaveBtn.dataset.mode = 'edit';
        const m = ensureBsModal(); if(m) m.show();
      }).catch(()=>alert('Erreur réseau'));
    }

    if(modalSaveBtn){
      modalSaveBtn.addEventListener('click', ()=>{
        const data = Object.fromEntries(new FormData(modalForm).entries());
        const mode = modalSaveBtn.dataset.mode;
        if(mode === 'create'){
          postJson('/admin/user/create', data).then(resp=>{
            if(resp.success){
              const m = ensureBsModal(); if(m) m.hide();
              appendRow(resp.id, data.username, data.role);
            } else alert('Erreur création');
          });
        } else {
          const id = data.id;
          postJson(`/admin/user/${id}/update`, data).then(resp=>{
            if(resp.success){ const m = ensureBsModal(); if(m) m.hide(); updateRow(id, data.username, data.role); }
            else alert('Erreur mise à jour');
          });
        }
      });
    }

    function doDelete(id){
      if(!confirm('Supprimer cet utilisateur ?')) return;
      fetch(`/admin/user/${id}/delete`).then(r=>r.json()).then(resp=>{
        if(resp.success){ removeRow(id); }
        else alert('Erreur suppression');
      });
    }

    function updateRow(id, username, role){
      const row = document.getElementById('user-row-'+id);
      if(!row) return;
      row.querySelector('.username-cell').textContent = username;
      row.querySelector('.role-cell .badge').textContent = role;
    }

    function removeRow(id){
      const row = document.getElementById('user-row-'+id);
      if(row) row.remove();
    }

    function appendRow(id, username, role){
      const tbody = document.querySelector('table.table tbody');
      const tr = document.createElement('tr'); tr.id = 'user-row-'+id;
      tr.innerHTML = `
        <td>--</td>
        <td class="username-cell">${escapeHtml(username)}</td>
        <td class="role-cell"><span class="badge bg-secondary">${escapeHtml(role)}</span></td>
        <td>
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${id}" title="Modifier"><i class="bi bi-pencil-square"></i></button>
            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${id}" title="Supprimer"><i class="bi bi-trash"></i></button>
          </div>
        </td>
      `;
      tbody.prepend(tr);
      bindRowActions(tr);
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];}); }

  })();
});
