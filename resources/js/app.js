import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
window.Alpine = Alpine;
Livewire.start();

const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

function initReveal() {
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal:not(.is-visible)').forEach((el) => io.observe(el));
}

function initCounters() {
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      if (!e.isIntersecting) return;
      io.unobserve(e.target);
      const el = e.target, end = parseInt(el.dataset.count, 10) || 0, t0 = performance.now(), dur = 1400;
      const step = (t) => {
        const p = Math.min((t - t0) / dur, 1);
        el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(step);
      };
      reduced ? (el.textContent = end) : requestAnimationFrame(step);
    });
  }, { threshold: 0.4 });
  document.querySelectorAll('[data-count]').forEach((el) => io.observe(el));
}

function initParticles() {
  const c = document.getElementById('particles');
  if (!c || reduced || c.dataset.on) return;
  c.dataset.on = '1';
  const ctx = c.getContext('2d'), host = c.parentElement;
  let w, h, pts = [], mouse = { x: -999, y: -999 }, visible = true;
  const resize = () => {
    const r = c.getBoundingClientRect(); w = c.width = r.width; h = c.height = r.height;
    const n = Math.min(70, Math.floor((w * h) / 16000));
    pts = Array.from({ length: n }, () => ({ x: Math.random() * w, y: Math.random() * h, vx: (Math.random() - .5) * .35, vy: (Math.random() - .5) * .35 }));
  };
  resize(); addEventListener('resize', resize);
  host.addEventListener('mousemove', (e) => { const r = c.getBoundingClientRect(); mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top; });
  host.addEventListener('mouseleave', () => { mouse.x = mouse.y = -999; });
  new IntersectionObserver(([e]) => { visible = e.isIntersecting; }).observe(c);
  const loop = () => {
    requestAnimationFrame(loop);
    if (!visible) return;
    ctx.clearRect(0, 0, w, h);
    for (const p of pts) {
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
      ctx.beginPath(); ctx.arc(p.x, p.y, 1.6, 0, 6.283); ctx.fillStyle = 'rgba(0,212,255,.8)'; ctx.fill();
    }
    for (let i = 0; i < pts.length; i++) {
      for (let j = i + 1; j < pts.length; j++) {
        const d = Math.hypot(pts[i].x - pts[j].x, pts[i].y - pts[j].y);
        if (d < 120) { ctx.strokeStyle = `rgba(31,107,255,${(1 - d / 120) * .35})`; ctx.beginPath(); ctx.moveTo(pts[i].x, pts[i].y); ctx.lineTo(pts[j].x, pts[j].y); ctx.stroke(); }
      }
      const dm = Math.hypot(pts[i].x - mouse.x, pts[i].y - mouse.y);
      if (dm < 160) { ctx.strokeStyle = `rgba(0,212,255,${(1 - dm / 160) * .6})`; ctx.beginPath(); ctx.moveTo(pts[i].x, pts[i].y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke(); }
    }
  };
  loop();
}

function initPointerFx() {
  const glow = document.querySelector('.cursor-glow');
  document.addEventListener('mousemove', (e) => {
    if (glow) glow.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
    const card = e.target.closest && e.target.closest('.glass');
    if (card) {
      const r = card.getBoundingClientRect();
      card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      card.style.setProperty('--my', (e.clientY - r.top) + 'px');
    }
    if (reduced) return;
    const tilt = e.target.closest && e.target.closest('.tilt');
    if (tilt) {
      const r = tilt.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
      tilt.style.transform = `perspective(900px) rotateX(${(-y * 8).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg)`;
    }
  });
  document.addEventListener('mouseout', (e) => {
    const tilt = e.target.closest && e.target.closest('.tilt');
    if (tilt && !tilt.contains(e.relatedTarget)) tilt.style.transform = '';
  });
}

function initProgress() {
  const bar = document.getElementById('scroll-progress');
  if (!bar) return;
  const update = () => { const h = document.documentElement.scrollHeight - innerHeight; bar.style.width = (h > 0 ? (scrollY / h) * 100 : 0) + '%'; };
  addEventListener('scroll', update, { passive: true }); update();
}

function boot() { initReveal(); initCounters(); initParticles(); }
document.addEventListener('DOMContentLoaded', () => { boot(); initPointerFx(); initProgress(); });
document.addEventListener('livewire:navigated', boot);
