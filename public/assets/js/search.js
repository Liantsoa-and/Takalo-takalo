(function(){
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.search-form').forEach(form => {
      form.addEventListener('submit', async function(e){
        e.preventDefault();
        const scope = form.dataset.scope || 'public';
        const base = form.dataset.base || '';
        const targetSelector = form.dataset.target;
        const q = form.querySelector('input[name=q]')?.value || '';
        const category = form.querySelector('select[name=category_id]')?.value || '';

        const endpoint = scope === 'mine' ? (base + '/objets/search') : (base + '/objets_publics/search');
        const url = endpoint + '?q=' + encodeURIComponent(q) + '&category_id=' + encodeURIComponent(category) + '&ajax=1';

        try {
          const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          if (!res.ok) throw new Error('Network response was not ok');
          const html = await res.text();
          const target = document.querySelector(targetSelector);
          if (target) {
            target.innerHTML = html;
          } else {
            console.warn('Search: target not found', targetSelector);
          }
        } catch (err) {
          console.error('Search error', err);
        }
      });
    });
  });
})();
