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
        // reset preview to default
        const previewAdd = document.getElementById('modalPdpPreview'); if(previewAdd) previewAdd.src = '/assets/images/pdp/default.png';
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
        // set preview image to current pdp
        const preview = document.getElementById('modalPdpPreview');
        if(preview) preview.src = `/assets/images/pdp/${u.pdp || 'default.png'}`;
        modalSaveBtn.dataset.mode = 'edit';
        const m = ensureBsModal(); if(m) m.show();
      }).catch(()=>alert('Erreur réseau'));
    }

    if(modalSaveBtn){
      modalSaveBtn.addEventListener('click', ()=>{
        const formData = new FormData(modalForm);
        const mode = modalSaveBtn.dataset.mode;
        let url = '/admin/user/create';
        if(mode !== 'create'){
          url = `/admin/user/${formData.get('id')}/update`;
        }
        fetch(url, { method: 'POST', body: formData }).then(r=>r.json()).then(resp=>{
          if(!resp) return alert('Erreur réseau');
          if(resp.success){
            const m = ensureBsModal(); if(m) m.hide();
            const pdp = resp.pdp || 'default.png';
            if(mode === 'create') appendRow(resp.id, formData.get('username'), formData.get('role'), pdp);
            else updateRow(formData.get('id'), formData.get('username'), formData.get('role'));
            // update modal preview if response contains pdp
            const previewAfter = document.getElementById('modalPdpPreview'); if(previewAfter && resp.pdp) previewAfter.src = `/assets/images/pdp/${resp.pdp}`;
          } else {
            alert(resp.message || 'Erreur');
          }
        }).catch(()=>alert('Erreur réseau'));
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

    function appendRow(id, username, role, pdp){
      pdp = pdp || 'default.png';
      const tbody = document.querySelector('table.table tbody');
      const tr = document.createElement('tr'); tr.id = 'user-row-'+id;
      tr.innerHTML = `
        <td class="pdp-cell"><img src="/assets/images/pdp/${escapeHtml(pdp)}" alt="pdp" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;"></td>
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

    // preview selected file in modal
    const pdpInput = modalForm ? modalForm.querySelector('input[name="pdp"]') : null;
    if(pdpInput){
      pdpInput.addEventListener('change', function(e){
        const f = this.files && this.files[0];
        const preview = document.getElementById('modalPdpPreview');
        if(f && preview){
          const reader = new FileReader();
          reader.onload = function(ev){ preview.src = ev.target.result; };
          reader.readAsDataURL(f);
        } else if(preview){ preview.src = '/assets/images/pdp/default.png'; }
      });
    }

  })();
});
