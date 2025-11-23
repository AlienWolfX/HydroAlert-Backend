(function () {
  try {
    const s = sessionStorage.getItem('hydroalert_scroll');
    if (s !== null) {
      const y = parseInt(s, 10);
      if (!isNaN(y)) window.scrollTo(0, y);
      sessionStorage.removeItem('hydroalert_scroll');
    }
  } catch (e) {
    // ignore
  }

  document.addEventListener('click', function (ev) {
    const link = ev.target.closest && ev.target.closest('a[href*="?url=auth/logout"]');
    if (!link) return;
    ev.preventDefault();
    try {
      if (window.Swal) {
        Swal.fire({
          title: 'Log out?',
          text: 'Are you sure you want to log out?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, log out',
          cancelButtonText: 'Cancel'
        }).then(result => {
          if (result.isConfirmed) {
            try { sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0)); } catch(e){}
            window.location.href = link.href;
          }
        });
      } else {
        throw new Error('Swal not available');
      }
    } catch (e) {
      if (confirm('Are you sure you want to log out?')) {
        try { sessionStorage.setItem('hydroalert_scroll', String(window.scrollY || window.pageYOffset || 0)); } catch(e){}
        window.location.href = link.href;
      }
    }
  });
})();
