(function(){
 const modal=document.getElementById('adModal');
 if(modal){ const close=()=>{ if(document.getElementById('dontShow')?.checked) document.cookie='hideYouxPopup=1;path=/;max-age='+(60*60*24*30); modal.remove();}; modal.addEventListener('click',e=>{if(e.target===modal)close()}); modal.querySelector('[data-close]')?.addEventListener('click',close); }
 document.querySelectorAll('[data-slider]').forEach(slider=>{ const slides=[...slider.querySelectorAll('.hero-slide')]; if(!slides.length)return; let i=0; slides[0].classList.add('active'); const go=n=>{slides[i].classList.remove('active'); i=(n+slides.length)%slides.length; slides[i].classList.add('active')}; slider.querySelector('[data-prev]')?.addEventListener('click',e=>{e.preventDefault();go(i-1)}); slider.querySelector('[data-next]')?.addEventListener('click',e=>{e.preventDefault();go(i+1)}); setInterval(()=>go(i+1),4500); });
})();
