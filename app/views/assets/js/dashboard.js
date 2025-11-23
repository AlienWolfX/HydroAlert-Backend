(function () {
  'use strict';
  window.waterChart = null;

  async function loadReadings() {
    const tbody = document.getElementById('readingsBody');
    try {
      const apiUrl = new URL('api/getReadings.php?limit=10', window.location.href).href;
      const res = await fetch(apiUrl, { cache: 'no-cache' });
      if (!res.ok) {
        const txt = await res.text().catch(() => '');
        throw new Error('HTTP ' + res.status + ' - ' + txt);
      }
      const json = await res.json().catch(e => { throw new Error('Invalid JSON response: ' + e.message); });
      tbody.innerHTML = '';
      if (json && json.success && Array.isArray(json.data) && json.data.length) {
        const rows = json.data;
        const labels = [];
        const data = [];

        rows.forEach(r => {
          let time = '';
          if (r.created_at) {
            time = r.created_at;
          } else if (r.device_timestamp) {
            const d2 = new Date(r.device_timestamp * 1000);
            if (!isNaN(d2)) time = d2.toISOString();
          }
          const distance = r.distance !== null && r.distance !== undefined ? Number(r.distance).toFixed(2) : '';
          const wl = r.water_level !== null && r.water_level !== undefined ? r.water_level : null;
          const imei = r.imei || '';
          const status = r.status || '';
          const tr = `<tr><td>${time}</td><td>${imei}</td><td>${distance}</td><td>${wl !== null ? wl : ''}</td><td>${status}</td></tr>`;
          tbody.insertAdjacentHTML('beforeend', tr);
        });

        const chartRows = rows.slice().reverse();
        chartRows.forEach(r => {
          let time = '';
          if (r.created_at) {
            time = r.created_at;
          } else if (r.device_timestamp) {
            const d2 = new Date(r.device_timestamp * 1000);
            if (!isNaN(d2)) time = d2.toISOString();
          }
          const wl = r.water_level !== null && r.water_level !== undefined ? r.water_level : null;
          labels.push(String(time !== null && time !== undefined ? time : ''));
          data.push(wl !== null ? Number(wl) : null);
        });

        const canvas = document.getElementById('waterChart');
        if (canvas) {
          if (window.waterChart) {
            window.waterChart.data.labels = labels;
            window.waterChart.data.datasets[0].data = data;
            window.waterChart.update();
          } else {
            const ctx = canvas.getContext('2d');
            window.waterChart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: labels,
                datasets: [{
                  label: 'Water Level (%)',
                  data: data,
                  borderColor: '#0ea5a3',
                  backgroundColor: 'rgba(14,165,163,0.12)',
                  fill: true,
                  tension: 0.35,
                  pointRadius: 3
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                  y: { beginAtZero: true, title: { display: true, text: 'Percent' } },
                  x: { title: { display: true, text: 'Time' } }
                },
                plugins: { legend: { display: true, position: 'top' } }
              }
            });
          }
        }
      } else {
        tbody.innerHTML = '<tr><td colspan="5" class="text-muted text-center">No readings</td></tr>';
      }
    } catch (e) {
      const msg = String(e && e.message ? e.message : e);
      tbody.innerHTML = `<tr><td colspan="5" class="text-danger text-center">Error loading readings: ${msg}</td></tr>`;
      console.error('loadReadings error', e);
    }
  }

  loadReadings();
  setInterval(loadReadings, 5000);

  // Intercept logout links and confirm via SweetAlert2
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
            window.location.href = link.href;
          }
        });
      } else {
        throw new Error('Swal not available');
      }
    } catch (e) {
      if (confirm('Are you sure you want to log out?')) {
        window.location.href = link.href;
      }
    }
  });

  // Ensure chart instance exists or create a minimal placeholder
  (function() {
    const canvas = document.getElementById('waterChart');
    if (!canvas) return;
    try {
      const existing = Chart.getChart(canvas);
      if (existing) {
        window.waterChart = existing;
        return;
      }
    } catch (e) {
      console.error('Error checking existing chart:', e);
    }

    const ctx = canvas.getContext('2d');
    const sampleLabels = [];
    const sampleData = [];
    window.waterChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: sampleLabels,
        datasets: [{
          label: 'Water Level (m)',
          data: sampleData,
          borderColor: '#0ea5a3',
          backgroundColor: 'rgba(14,165,163,0.12)',
          fill: true,
          tension: 0.35,
          pointRadius: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: { beginAtZero: false, title: { display: true, text: 'Meters' } },
          x: { title: { display: true, text: 'Time' } }
        },
        plugins: {
          legend: { display: true, position: 'top' }
        }
      }
    });
  })();

})();
