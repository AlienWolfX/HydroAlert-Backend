(function(){
  function pad(n){ return n < 10 ? '0'+n : n; }

  const elTime = document.getElementById('phTime');
  const elDate = document.getElementById('phDate');
  const btn = document.getElementById('toggleClock');
  if (!elTime || !elDate) return;

  let use24 = true;

  function update(){
    const now = new Date();
    const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: !use24, timeZone: 'Asia/Manila' };
    const dateOptions = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', timeZone: 'Asia/Manila' };
    try{
      elTime.textContent = new Intl.DateTimeFormat('en-US', timeOptions).format(now);
      elDate.textContent = new Intl.DateTimeFormat('en-US', dateOptions).format(now) + ' (PHT)';
    } catch(e){
      // fallback: build manually
      const manilaNow = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
      const hh = use24 ? pad(manilaNow.getHours()) : ((manilaNow.getHours()%12)||12);
      const mm = pad(manilaNow.getMinutes());
      const ss = pad(manilaNow.getSeconds());
      elTime.textContent = hh + ':' + mm + ':' + ss;
      elDate.textContent = manilaNow.toDateString() + ' (PHT)';
    }
  }

  if (btn) {
    btn.addEventListener('click', function(){
      use24 = !use24;
      btn.textContent = use24 ? '24h' : '12h';
      update();
    });
  }

  update();
  setInterval(update, 1000);

})();
