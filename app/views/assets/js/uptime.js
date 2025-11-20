(function() {
  function pad(n){ return n < 10 ? '0'+n : n; }

  function formatDuration(ms){
    const total = Math.floor(ms/1000);
    const days = Math.floor(total / 86400);
    let rem = total % 86400;
    const hours = Math.floor(rem / 3600); rem = rem % 3600;
    const minutes = Math.floor(rem / 60);
    const seconds = rem % 60;
    return (days > 0 ? days + 'd ' : '') + pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);
  }

  function startUptime() {
    const el = document.getElementById('uptimeCounter');
    if (!el) return;
    const startAttr = el.getAttribute('data-start');
    let startDate = new Date(startAttr);
    if (isNaN(startDate.getTime())) {
      // try parsing without timezone
      startDate = new Date(startAttr + 'Z');
    }
    if (isNaN(startDate.getTime())) {
      el.textContent = 'Invalid start date';
      return;
    }

    function tick(){
      const now = new Date();
      const diff = now - startDate;
      if (diff < 0) {
        el.textContent = 'Starts in ' + formatDuration(-diff);
      } else {
        el.textContent = formatDuration(diff);
      }
    }

    tick();
    setInterval(tick, 1000);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startUptime);
  } else {
    startUptime();
  }

})();
