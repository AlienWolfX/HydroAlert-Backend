(function () {
  const addBtn = document.getElementById('addMeasureBtn');
  const modalHtml = `
    <div class="modal fade" id="measureModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" id="measureForm" method="post" action="?url=measure/store">
          <div class="modal-header">
            <h5 class="modal-title">Add Measure</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="measureId">
            <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" id="measureTitle" required></div>
            <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" id="measureDescription"></textarea></div>
            <div class="mb-3"><label class="form-label">Status</label>
              <select class="form-select" name="status" id="measureStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>`;

  let modalAdded = false;
  function ensureModal() {
    if (modalAdded) return;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    modalAdded = true;
  }

  if (addBtn) addBtn.addEventListener('click', function () {
    ensureModal();
    document.getElementById('measureId').value = '';
    document.getElementById('measureTitle').value = '';
    document.getElementById('measureDescription').value = '';
    document.getElementById('measureStatus').value = 'active';
    const modalEl = document.getElementById('measureModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  });

  document.addEventListener('click', function (ev) {
    const btn = ev.target.closest && ev.target.closest('.measure-editBtn');
    if (!btn) return;
    ev.preventDefault();
    const id = btn.getAttribute('data-id');
    ensureModal();
    fetch('?url=measure/find&id=' + encodeURIComponent(id))
      .then(r => r.json())
      .then(json => {
        if (!json.success) return alert('Measure not found');
        const row = json.row;
        document.getElementById('measureId').value = row.id || '';
        document.getElementById('measureTitle').value = row.title || '';
        document.getElementById('measureDescription').value = row.description || '';
        document.getElementById('measureStatus').value = row.status || 'active';
        const modalEl = document.getElementById('measureModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
      }).catch(err => {
        console.error(err);
        alert('Error fetching measure: ' + String(err));
      });
  });

  document.addEventListener('click', function (ev) {
    const target = ev.target.closest && ev.target.closest('.measure-deleteBtn');
    if (!target) return;
    ev.preventDefault();
    const href = target.getAttribute('data-href') || target.getAttribute('href');
    try {
      Swal.fire({
        title: 'Delete measure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
      }).then(result => {
        if (result.isConfirmed) {
          try { sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0)); } catch(e){}
          window.location.href = href;
        }
      });
    } catch (e) {
      if (confirm('Delete measure? This action cannot be undone.')) {
        try { sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0)); } catch(e){}
        window.location.href = href;
      }
    }
  });

  document.addEventListener('submit', function (ev) {
    try {
      const form = ev.target;
      if (form && form.id === 'measureForm') {
        sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0));
      }
    } catch (e) {}
  });

})();
