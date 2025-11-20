document.addEventListener('DOMContentLoaded', function () {
  const addBtn = document.getElementById('addCenterBtn');
  const evacForm = document.getElementById('evacForm');
  const centerId = document.getElementById('centerId');
  const centerName = document.getElementById('centerName');
  const centerAddress = document.getElementById('centerAddress');
  const centerStatus = document.getElementById('centerStatus');

  // Open modal for add
    if (addBtn) addBtn.addEventListener('click', function () {
    centerId.value = '';
    centerName.value = '';
    centerAddress.value = '';
    centerStatus.value = 'active';
    const modalEl = document.getElementById('evacModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  });

  // Edit buttons
  document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      fetch('?url=info/find&id=' + encodeURIComponent(id))
        .then(r => r.json())
        .then(json => {
          if (!json.success) return alert('Record not found');
          const row = json.row;
          centerId.value = row.id;
          centerName.value = row.name || '';
          centerAddress.value = row.address || '';
          centerStatus.value = row.status || 'active';
          const modalEl = document.getElementById('evacModal');
          const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
          modal.show();
        });
    });
  });

  // Status toggle (AJAX)
  document.querySelectorAll('.status-toggle').forEach(input => {
    input.addEventListener('change', function () {
      const id = this.getAttribute('data-id');
      const status = this.checked ? 'active' : 'inactive';
      fetch('?url=info/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
      })
      .then(r => r.json())
      .then(json => {
        if (!json.success) {
          alert('Unable to update status');
          // revert
          this.checked = !this.checked;
        } else {
          // update label text nearby
          const label = this.parentElement.querySelector('.form-check-label');
          if (label) label.textContent = json.row.status;
        }
      });
    });
  });
});
