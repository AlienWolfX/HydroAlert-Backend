(function () {
  const addBtn = document.getElementById('addContactBtn');
  const contactModalHtml = `
    <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content" id="contactForm" method="post" action="?url=contact/store">
          <div class="modal-header">
            <h5 class="modal-title">Add Contact</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="contactId">
            <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" id="contactName" required></div>
            <div class="mb-3"><label class="form-label">Phone</label><input class="form-control" name="phone" id="contactPhone" required></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>`;

  // append modal to body when needed
  let contactModalAdded = false;
  function ensureContactModal() {
    if (contactModalAdded) return;
    document.body.insertAdjacentHTML('beforeend', contactModalHtml);
    contactModalAdded = true;
  }

    if (addBtn) addBtn.addEventListener('click', function () {
    ensureContactModal();
    document.getElementById('contactId').value = '';
    document.getElementById('contactName').value = '';
    document.getElementById('contactPhone').value = '';
    const modalEl = document.getElementById('contactModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  });

  // Edit buttons
  document.addEventListener('click', function (ev) {
    const btn = ev.target.closest && ev.target.closest('.contact-editBtn');
    if (!btn) return;
    ev.preventDefault();
    const id = btn.getAttribute('data-id');
    ensureContactModal();
    fetch('?url=contact/find&id=' + encodeURIComponent(id))
      .then(r => r.json())
      .then(json => {
        if (!json.success) return alert('Contact not found');
        const row = json.row;
        document.getElementById('contactId').value = row.id || '';
        document.getElementById('contactName').value = row.name || '';
        document.getElementById('contactPhone').value = row.phone || '';
        const modalEl = document.getElementById('contactModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
      }).catch(err => {
        console.error(err);
        alert('Error fetching contact: ' + String(err));
      });
  });

  document.addEventListener('click', function (ev) {
    const target = ev.target.closest && ev.target.closest('.contact-deleteBtn');
    if (!target) return;
    ev.preventDefault();
    const href = target.getAttribute('data-href') || target.getAttribute('href');
    try {
      Swal.fire({
        title: 'Delete contact?',
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
      if (confirm('Delete contact? This action cannot be undone.')) {
        try { sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0)); } catch(e){}
        window.location.href = href;
      }
    }
  });

  document.addEventListener('submit', function (ev) {
    try {
      const form = ev.target;
      if (form && form.id === 'contactForm') {
        sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0));
      }
    } catch (e) {}
  });

})();
