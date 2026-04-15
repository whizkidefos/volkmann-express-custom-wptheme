/**
 * Volkmann Express — main.js
 * THREE.js hero canvas, GSAP scroll animations, dark/light toggle,
 * responsive nav, count-up, AJAX contact form.
 */

(function () {
  'use strict';

  /* ─── Constants ──────────────────────────────────────── */
  const ROOT  = document.documentElement;
  const EASES = { out: 'power3.out', inOut: 'power2.inOut', smooth: 'power4.out' };

  /* ─── 1. THEME (dark / light) ────────────────────────── */
  const ThemeManager = (() => {
    const KEY      = 've_theme';
    const TOGGLE   = document.getElementById('ve-theme-toggle');
    const stored   = localStorage.getItem(KEY);
    const prefDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    let isDark     = stored ? stored === 'dark' : prefDark !== false;

    function apply(dark) {
      if (dark) {
        ROOT.classList.add('dark');
      } else {
        ROOT.classList.remove('dark');
      }
      isDark = dark;
      localStorage.setItem(KEY, dark ? 'dark' : 'light');
      if (TOGGLE) TOGGLE.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
    }

    function init() {
      apply(isDark);
      if (TOGGLE) {
        TOGGLE.addEventListener('click', () => apply(!isDark));
      }
    }

    return { init };
  })();

  /* ─── 2. HEADER SCROLL ───────────────────────────────── */
  const HeaderManager = (() => {
    const HEADER = document.getElementById('ve-header');
    let  lastY   = 0;
    let  ticking = false;

    function update() {
      const y = window.scrollY;
      if (HEADER) {
        HEADER.classList.toggle('scrolled', y > 20);
      }
      lastY    = y;
      ticking  = false;
    }

    function init() {
      window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
      }, { passive: true });
    }

    return { init };
  })();

  /* ─── 3. MOBILE NAV ──────────────────────────────────── */
  const MobileNav = (() => {
    const BURGER = document.getElementById('ve-hamburger');
    const NAV    = document.getElementById('ve-mobile-nav');
    let   open   = false;

    function toggle(force) {
      open = (force !== undefined) ? force : !open;
      if (NAV)    NAV.classList.toggle('open', open);
      if (BURGER) {
        BURGER.classList.toggle('open', open);
        BURGER.setAttribute('aria-expanded', open);
      }
      document.body.style.overflow = open ? 'hidden' : '';
    }

    function init() {
      if (!BURGER) return;
      BURGER.addEventListener('click', () => toggle());
      // Close on overlay click
      if (NAV) {
        NAV.addEventListener('click', e => {
          if (e.target === NAV) toggle(false);
        });
      }
      // Close on nav link click
      document.querySelectorAll('#ve-mobile-nav a').forEach(a => {
        a.addEventListener('click', () => toggle(false));
      });
      // Escape key
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && open) toggle(false);
      });
    }

    return { init };
  })();

  /* ─── 4. THREE.JS HERO CANVAS ────────────────────────── */
  const HeroCanvas = (() => {
    function init(canvasId) {
      const canvas = document.getElementById(canvasId);
      if (!canvas || typeof THREE === 'undefined') return;

      const W = canvas.offsetWidth  || window.innerWidth;
      const H = canvas.offsetHeight || window.innerHeight;

      // Renderer
      const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
      renderer.setSize(W, H);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
      renderer.setClearColor(0x000000, 0);

      // Scene + camera
      const scene  = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(60, W / H, 0.1, 1000);
      camera.position.set(0, 0, 6);

      // Particles
      const COUNT   = 320;
      const geom    = new THREE.BufferGeometry();
      const positions  = new Float32Array(COUNT * 3);
      const velocities = [];
      const sizes      = new Float32Array(COUNT);

      for (let i = 0; i < COUNT; i++) {
        positions[i * 3]     = (Math.random() - 0.5) * 20;
        positions[i * 3 + 1] = (Math.random() - 0.5) * 14;
        positions[i * 3 + 2] = (Math.random() - 0.5) * 8;
        velocities.push({
          x: (Math.random() - 0.5) * 0.002,
          y: (Math.random() - 0.5) * 0.002,
          z: (Math.random() - 0.5) * 0.001,
        });
        sizes[i] = Math.random() * 2.5 + 0.5;
      }

      geom.setAttribute('position', new THREE.BufferAttribute(positions, 3));
      geom.setAttribute('size',     new THREE.BufferAttribute(sizes, 1));

      const mat = new THREE.PointsMaterial({
        size:         0.06,
        color:        0xF97316,
        transparent:  true,
        opacity:      0.55,
        sizeAttenuation: true,
        blending:     THREE.AdditiveBlending,
        depthWrite:   false,
      });

      const particles = new THREE.Points(geom, mat);
      scene.add(particles);

      // Lines connecting nearby particles
      const lineMat = new THREE.LineBasicMaterial({
        color:       0x2563EB,
        transparent: true,
        opacity:     0.12,
        blending:    THREE.AdditiveBlending,
        depthWrite:  false,
      });

      let linesMesh = null;

      function buildLines() {
        const threshold = 3.5;
        const verts = [];
        for (let i = 0; i < COUNT; i++) {
          for (let j = i + 1; j < COUNT; j++) {
            const dx = positions[i * 3] - positions[j * 3];
            const dy = positions[i * 3 + 1] - positions[j * 3 + 1];
            const dz = positions[i * 3 + 2] - positions[j * 3 + 2];
            const dist = Math.sqrt(dx * dx + dy * dy + dz * dz);
            if (dist < threshold) {
              verts.push(
                positions[i * 3], positions[i * 3 + 1], positions[i * 3 + 2],
                positions[j * 3], positions[j * 3 + 1], positions[j * 3 + 2]
              );
            }
          }
        }
        const lineGeom = new THREE.BufferGeometry();
        lineGeom.setAttribute('position', new THREE.BufferAttribute(new Float32Array(verts), 3));
        if (linesMesh) { scene.remove(linesMesh); linesMesh.geometry.dispose(); }
        linesMesh = new THREE.LineSegments(lineGeom, lineMat);
        scene.add(linesMesh);
      }

      buildLines();

      // Mouse parallax
      let mouseX = 0, mouseY = 0;
      document.addEventListener('mousemove', e => {
        mouseX = (e.clientX / window.innerWidth  - 0.5) * 0.3;
        mouseY = (e.clientY / window.innerHeight - 0.5) * 0.2;
      });

      // Resize
      window.addEventListener('resize', () => {
        const nW = canvas.offsetWidth  || window.innerWidth;
        const nH = canvas.offsetHeight || window.innerHeight;
        renderer.setSize(nW, nH);
        camera.aspect = nW / nH;
        camera.updateProjectionMatrix();
      });

      let lineTimer = 0;
      // Animate
      (function animate(t) {
        requestAnimationFrame(animate);

        // Move particles
        for (let i = 0; i < COUNT; i++) {
          positions[i * 3]     += velocities[i].x;
          positions[i * 3 + 1] += velocities[i].y;
          positions[i * 3 + 2] += velocities[i].z;
          // Wrap
          if (positions[i * 3] >  10) positions[i * 3] = -10;
          if (positions[i * 3] < -10) positions[i * 3] =  10;
          if (positions[i * 3 + 1] >  7) positions[i * 3 + 1] = -7;
          if (positions[i * 3 + 1] < -7) positions[i * 3 + 1] =  7;
        }
        geom.attributes.position.needsUpdate = true;

        // Rebuild lines every 12 frames
        lineTimer++;
        if (lineTimer % 12 === 0) buildLines();

        // Camera parallax
        camera.position.x += (mouseX - camera.position.x) * 0.03;
        camera.position.y += (-mouseY - camera.position.y) * 0.03;
        camera.lookAt(scene.position);

        // Slow rotation
        particles.rotation.y = t * 0.00005;

        renderer.render(scene, camera);
      })(0);
    }

    return { init };
  })();

  /* ─── 5. ABOUT PAGE CANVAS (orbiting spheres) ─────────── */
  const AboutCanvas = (() => {
    function init() {
      const canvas = document.getElementById('ve-about-canvas');
      if (!canvas || typeof THREE === 'undefined') return;

      const W = canvas.offsetWidth;
      const H = canvas.offsetHeight;

      const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
      renderer.setSize(W, H);
      renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

      const scene  = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(50, W / H, 0.1, 100);
      camera.position.set(0, 0, 5);

      // Core sphere
      const coreGeo = new THREE.SphereGeometry(0.9, 64, 64);
      const coreMat = new THREE.MeshStandardMaterial({
        color:     0x0A0A0F,
        metalness: 0.8,
        roughness: 0.2,
        wireframe: false,
      });
      const core = new THREE.Mesh(coreGeo, coreMat);
      scene.add(core);

      // Wireframe overlay
      const wireGeo = new THREE.SphereGeometry(0.92, 20, 20);
      const wireMat = new THREE.MeshBasicMaterial({ color: 0xF97316, wireframe: true, opacity: 0.2, transparent: true });
      scene.add(new THREE.Mesh(wireGeo, wireMat));

      // Orbit rings
      function makeRing(radius, color, tilt) {
        const geo = new THREE.TorusGeometry(radius, 0.012, 8, 90);
        const mat = new THREE.MeshBasicMaterial({ color, opacity: 0.4, transparent: true });
        const ring = new THREE.Mesh(geo, mat);
        ring.rotation.x = tilt;
        scene.add(ring);
        return ring;
      }
      const ring1 = makeRing(1.6, 0xF97316, Math.PI / 5);
      const ring2 = makeRing(2.2, 0x2563EB, Math.PI / 3);
      const ring3 = makeRing(1.9, 0x06B6D4, -Math.PI / 6);

      // Orbiting dots
      function makeDot(orbitR, color, speed, phaseOffset) {
        const geo = new THREE.SphereGeometry(0.07, 12, 12);
        const mat = new THREE.MeshStandardMaterial({ color, emissive: color, emissiveIntensity: .8 });
        const dot = new THREE.Mesh(geo, mat);
        scene.add(dot);
        return { dot, orbitR, speed, phase: phaseOffset };
      }
      const dots = [
        makeDot(1.6, 0xF97316, 0.8, 0),
        makeDot(1.6, 0xF97316, 0.8, Math.PI),
        makeDot(2.2, 0x2563EB, 0.5, 0),
        makeDot(2.2, 0x2563EB, 0.5, Math.PI * 0.66),
        makeDot(1.9, 0x06B6D4, 0.65, 1.2),
      ];

      // Lights
      scene.add(new THREE.AmbientLight(0xffffff, 0.4));
      const ptLight = new THREE.PointLight(0xF97316, 3, 10);
      ptLight.position.set(3, 2, 2);
      scene.add(ptLight);
      const ptBlue = new THREE.PointLight(0x2563EB, 2, 10);
      ptBlue.position.set(-3, -2, 1);
      scene.add(ptBlue);

      window.addEventListener('resize', () => {
        const nW = canvas.offsetWidth;
        const nH = canvas.offsetHeight;
        renderer.setSize(nW, nH);
        camera.aspect = nW / nH;
        camera.updateProjectionMatrix();
      });

      (function animate(t) {
        requestAnimationFrame(animate);
        const elapsed = t * 0.001;

        core.rotation.y = elapsed * 0.2;
        ring1.rotation.z = elapsed * 0.3;
        ring2.rotation.z = -elapsed * 0.2;
        ring3.rotation.x = elapsed * 0.15;

        dots.forEach(d => {
          d.dot.position.x = Math.cos(elapsed * d.speed + d.phase) * d.orbitR;
          d.dot.position.z = Math.sin(elapsed * d.speed + d.phase) * d.orbitR * 0.4;
          d.dot.position.y = Math.sin(elapsed * d.speed * 1.3 + d.phase) * 0.5;
        });

        ptLight.position.x = Math.sin(elapsed * 0.5) * 4;
        ptLight.position.y = Math.cos(elapsed * 0.3) * 2;

        renderer.render(scene, camera);
      })(0);
    }

    return { init };
  })();

  /* ─── 6. GSAP SCROLL ANIMATIONS ─────────────────────── */
  const Animations = (() => {
    function initFadeUps() {
      const els = document.querySelectorAll('.ve-fade-up');
      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            e.target.classList.add('in-view');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
      els.forEach(el => io.observe(el));
    }

    function initReveals() {
      const els = document.querySelectorAll('.ve-reveal');
      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) {
            // Stagger children if a grid
            const children = e.target.querySelectorAll('.ve-service-card, .ve-solution-card, .ve-industry-card, .ve-cap-card, .ve-value-card, .ve-achievement, .ve-proof-metric');
            if (children.length > 0) {
              children.forEach((child, i) => {
                setTimeout(() => child.classList.add('in-view'), i * 80);
              });
            }
            e.target.classList.add('in-view');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
      els.forEach(el => io.observe(el));
    }

    function initGSAP() {
      if (typeof gsap === 'undefined') return;
      gsap.registerPlugin(ScrollTrigger);

      // Hero title word split
      const heroTitle = document.querySelector('.ve-hero__title');
      if (heroTitle) {
        gsap.from(heroTitle, {
          y: 40, opacity: 0, duration: 1,
          ease: EASES.smooth, delay: .1
        });
      }

      // Process step numbers count up via GSAP
      gsap.utils.toArray('.ve-process-step__num').forEach((el, i) => {
        gsap.from(el, {
          scrollTrigger: { trigger: el, start: 'top 85%' },
          textContent: 0,
          duration: 1.2,
          snap: { textContent: 1 },
          ease: 'power1.inOut',
          delay: i * 0.1,
        });
      });

      // Stat card shimmer on scroll
      gsap.utils.toArray('.ve-stat-card').forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: { trigger: card, start: 'top 88%' },
          y: 30, opacity: 0,
          duration: .7,
          ease: EASES.out,
          delay: i * 0.1,
        });
      });

      // Proof block numbers
      gsap.utils.toArray('.ve-result-stat').forEach((el, i) => {
        gsap.from(el, {
          scrollTrigger: { trigger: el, start: 'top 85%' },
          x: -20, opacity: 0,
          duration: .6, ease: EASES.out,
          delay: i * 0.12,
        });
      });
    }

    function init() {
      initFadeUps();
      initReveals();
      initGSAP();
    }

    return { init };
  })();

  /* ─── 7. COUNT-UP NUMBERS ────────────────────────────── */
  const CountUp = (() => {
    function animateValue(el, start, end, duration, suffix) {
      const startTime = performance.now();
      const isFloat   = String(end).includes('.');

      function update(now) {
        const elapsed  = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        // Ease out cubic
        const ease     = 1 - Math.pow(1 - progress, 3);
        const current  = start + (end - start) * ease;
        el.textContent = isFloat ? current.toFixed(1) + suffix : Math.round(current) + suffix;
        if (progress < 1) requestAnimationFrame(update);
      }
      requestAnimationFrame(update);
    }

    function init() {
      const els = document.querySelectorAll('[data-countup]');
      const io  = new IntersectionObserver((entries) => {
        entries.forEach(e => {
          if (!e.isIntersecting) return;
          const rawVal = e.target.getAttribute('data-countup');
          const suffix = e.target.getAttribute('data-suffix') || '';
          const numEl  = e.target.querySelector('.ve-countup-num') || e.target.querySelector('.ve-achievement__value') || e.target;
          const end    = parseFloat(rawVal) || 0;
          animateValue(numEl, 0, end, 1800, suffix);
          io.unobserve(e.target);
        });
      }, { threshold: 0.5 });
      els.forEach(el => io.observe(el));
    }

    return { init };
  })();

  /* ─── 8. AJAX CONTACT FORM ───────────────────────────── */
  const ContactForm = (() => {
    function init() {
      const form    = document.getElementById('ve-contact-form');
      const submit  = document.getElementById('ve-contact-submit');
      const success = document.getElementById('ve-form-success');
      const errGlob = document.getElementById('ve-form-error-global');

      if (!form || typeof VE === 'undefined') return;

      function setLoading(loading) {
        const text    = submit.querySelector('.ve-btn__text');
        const spinner = submit.querySelector('.ve-btn__spinner');
        const arrow   = submit.querySelector('.ve-btn__arrow');
        submit.disabled = loading;
        if (loading) {
          text.textContent = 'Sending…';
          spinner && spinner.classList.remove('hidden');
          arrow   && arrow.classList.add('hidden');
        } else {
          text.textContent = 'Send Message';
          spinner && spinner.classList.add('hidden');
          arrow   && arrow.classList.remove('hidden');
        }
      }

      function clearErrors() {
        form.querySelectorAll('.ve-form-error').forEach(el => el.textContent = '');
        form.querySelectorAll('.ve-form-input, .ve-form-select, .ve-form-textarea').forEach(el => el.classList.remove('error'));
        errGlob && errGlob.classList.add('hidden');
      }

      function validate() {
        let valid = true;
        const name    = form.querySelector('[name="name"]');
        const email   = form.querySelector('[name="email"]');
        const subject = form.querySelector('[name="subject"]');
        const message = form.querySelector('[name="message"]');

        if (!name.value.trim()) {
          document.getElementById('ve-name-error').textContent = 'Please enter your name.';
          name.classList.add('error'); valid = false;
        }
        if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
          document.getElementById('ve-email-error').textContent = 'Please enter a valid email address.';
          email.classList.add('error'); valid = false;
        }
        if (!subject.value) {
          document.getElementById('ve-subject-error').textContent = 'Please select a subject.';
          subject.classList.add('error'); valid = false;
        }
        if (!message.value.trim() || message.value.trim().length < 10) {
          document.getElementById('ve-message-error').textContent = 'Please enter a message (at least 10 characters).';
          message.classList.add('error'); valid = false;
        }
        return valid;
      }

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        if (!validate()) return;

        // Honeypot check
        if (form.querySelector('[name="ve_pot"]')?.value) return;

        setLoading(true);

        const data = new FormData(form);
        data.append('action', 've_contact');
        data.append('nonce',  VE.nonce);

        try {
          const res  = await fetch(VE.ajaxUrl, { method: 'POST', body: data });
          const json = await res.json();

          if (json.success) {
            form.reset();
            form.style.display = 'none';
            success && success.classList.remove('hidden');
          } else {
            errGlob && (errGlob.textContent = json.data?.message || 'Something went wrong. Please try again.');
            errGlob && errGlob.classList.remove('hidden');
            setLoading(false);
          }
        } catch (err) {
          errGlob && (errGlob.textContent = 'Network error. Please check your connection and try again.');
          errGlob && errGlob.classList.remove('hidden');
          setLoading(false);
        }
      });

      // Live validation feedback
      form.querySelectorAll('.ve-form-input, .ve-form-select, .ve-form-textarea').forEach(input => {
        input.addEventListener('input', () => {
          if (input.classList.contains('error') && input.value.trim()) {
            input.classList.remove('error');
            const errEl = document.getElementById(`ve-${input.name}-error`);
            if (errEl) errEl.textContent = '';
          }
        });
      });
    }

    return { init };
  })();

  /* ─── 9. SMOOTH SCROLL FOR ANCHOR LINKS ─────────────── */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) {
          e.preventDefault();
          const offset = 88; // header height + gap
          const top    = target.getBoundingClientRect().top + window.scrollY - offset;
          window.scrollTo({ top, behavior: 'smooth' });
        }
      });
    });
  }

  /* ─── 10. ACTIVE NAV LINK HIGHLIGHT ─────────────────── */
  function initActiveNav() {
    const path = window.location.pathname;
    document.querySelectorAll('.ve-nav__link, .ve-nav__sublink').forEach(a => {
      const href = new URL(a.href, window.location.origin).pathname;
      if (href === path || (href !== '/' && path.startsWith(href))) {
        a.classList.add('active');
        const parent = a.closest('.ve-nav__item');
        if (parent) parent.classList.add('ve-nav__item--active');
      }
    });
  }

  /* ─── 11. SERVICE CARDS HOVER EFFECT ────────────────── */
  function initCardHover() {
    document.querySelectorAll('.ve-service-card, .ve-solution-card, .ve-industry-card, .ve-cap-card').forEach(card => {
      card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        const x    = ((e.clientX - rect.left) / rect.width  - 0.5) * 10;
        const y    = ((e.clientY - rect.top)  / rect.height - 0.5) * -10;
        card.style.transform = `translateY(-4px) rotateX(${y}deg) rotateY(${x}deg)`;
        card.style.transition = 'transform .1s ease';
      });
      card.addEventListener('mouseleave', () => {
        card.style.transform = '';
        card.style.transition = 'transform .5s var(--ve-ease), border-color .25s, box-shadow .25s';
      });
    });
  }

  /* ─── 12. CHART.JS (service detail page) ────────────── */
  const ChartManager = (() => {
    function initResultsChart() {
      const ctx = document.getElementById('ve-results-chart');
      if (!ctx || typeof Chart === 'undefined') return;

      const isDark = ROOT.classList.contains('dark');
      const gridColor = isDark ? 'rgba(255,255,255,.06)' : 'rgba(0,0,0,.06)';
      const textColor = isDark ? '#94A3B8' : '#475569';

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Before', 'After'],
          datasets: [{
            label: 'Efficiency Score',
            data: [100, 140],
            backgroundColor: [
              'rgba(37, 99, 235, 0.3)',
              'rgba(249, 115, 22, 0.7)',
            ],
            borderColor: ['rgba(37, 99, 235, 0.8)', 'rgba(249, 115, 22, 1)'],
            borderWidth: 2,
            borderRadius: 8,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: isDark ? '#16161F' : '#fff',
              titleColor: isDark ? '#F1F5F9' : '#0F172A',
              bodyColor: '#94A3B8',
              borderColor: 'rgba(249,115,22,.3)',
              borderWidth: 1,
            }
          },
          scales: {
            x: { grid: { color: gridColor }, ticks: { color: textColor } },
            y: { grid: { color: gridColor }, ticks: { color: textColor } },
          }
        }
      });
    }

    return { init: initResultsChart };
  })();

  /* ─── 13. GSAP TEXT PLUGIN TITLE TYPEWRITER ─────────── */
  function initTypewriter() {
    if (typeof gsap === 'undefined') return;
    try { gsap.registerPlugin(TextPlugin); } catch(e) { return; }
    const el = document.querySelector('.ve-hero__title .ve-text-gradient');
    if (!el) return;
    // preserve the static text; the gradient span gets a subtle pulse
    gsap.to(el, {
      opacity: 0.7, duration: 1.5, yoyo: true, repeat: -1,
      ease: 'sine.inOut',
    });
  }

  /* ─── 14. INIT ───────────────────────────────────────── */
  function init() {
    ThemeManager.init();
    HeaderManager.init();
    MobileNav.init();
    Animations.init();
    CountUp.init();
    ContactForm.init();
    initSmoothScroll();
    initActiveNav();

    // Defer heavier visual things
    window.addEventListener('load', () => {
      HeroCanvas.init('ve-hero-canvas');
      AboutCanvas.init();
      initCardHover();
      ChartManager.init();
      initTypewriter();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();

/* ─── FAQ ACCORDION ──────────────────────────────────────── */
(function initFAQ() {
  document.querySelectorAll('.ve-faq-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const item   = btn.closest('.ve-faq-item');
      const answer = item.querySelector('.ve-faq-answer');
      const isOpen = item.classList.contains('open');

      // Close all siblings
      item.closest('.ve-faq-list').querySelectorAll('.ve-faq-item').forEach(i => {
        i.classList.remove('open');
        i.querySelector('.ve-faq-btn').setAttribute('aria-expanded', 'false');
        i.querySelector('.ve-faq-answer').hidden = true;
      });

      if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
        answer.hidden = false;
        // Smooth scroll into view if needed
        const rect = item.getBoundingClientRect();
        if (rect.top < 80) item.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
})();

/* ─── SEARCH TRIGGER (header) ────────────────────────────── */
(function initSearch() {
  const trigger = document.getElementById('ve-search-trigger');
  if (!trigger) return;
  trigger.addEventListener('click', () => {
    const url = new URL(window.location.origin);
    url.pathname = '/';
    url.searchParams.set('s', '');
    window.location.href = url.toString();
  });
})();

/* ─── INDUSTRY VISUAL PARALLAX ───────────────────────────── */
(function initIndustryVisual() {
  const visual = document.querySelector('.ve-industry-visual');
  if (!visual) return;
  document.addEventListener('mousemove', e => {
    const x = ((e.clientX / window.innerWidth)  - 0.5) * 12;
    const y = ((e.clientY / window.innerHeight) - 0.5) * 8;
    visual.style.transform = `rotateX(${-y}deg) rotateY(${x}deg)`;
    visual.style.transition = 'transform .1s ease';
  });
  document.addEventListener('mouseleave', () => {
    visual.style.transform = '';
    visual.style.transition = 'transform .6s var(--ve-ease)';
  });
})();

/* ─── BACK TO TOP ────────────────────────────────────────── */
(function initBackToTop() {
  const btn = document.getElementById('ve-back-top');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 600);
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();

/* ─── COOKIE CONSENT ─────────────────────────────────────── */
(function initCookies() {
  const banner  = document.getElementById('ve-cookie-banner');
  const accept  = document.getElementById('ve-cookie-accept');
  const decline = document.getElementById('ve-cookie-decline');
  if (!banner) return;

  const COOKIE_KEY = 've_cookie_consent';
  const stored = localStorage.getItem(COOKIE_KEY);

  if (!stored) {
    setTimeout(() => banner.classList.add('visible'), 1800);
  }

  function dismiss(value) {
    localStorage.setItem(COOKIE_KEY, value);
    banner.classList.remove('visible');
  }

  accept  && accept.addEventListener('click',  () => dismiss('accepted'));
  decline && decline.addEventListener('click', () => dismiss('declined'));
})();

/* ─── HEADER SEARCH SHORTCUT (Cmd/Ctrl+K) ───────────────── */
(function initSearchShortcut() {
  document.addEventListener('keydown', e => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
      e.preventDefault();
      const searchUrl = new URL('/', window.location.origin);
      searchUrl.searchParams.set('s', '');
      window.location.href = searchUrl.toString();
    }
  });
})();

/* ─── GSAP PAGE TRANSITION ───────────────────────────────── */
(function initPageTransitions() {
  if (typeof gsap === 'undefined') return;
  // Fade out on internal link click
  document.querySelectorAll('a[href]').forEach(a => {
    try {
      const url = new URL(a.href);
      if (url.origin !== window.location.origin) return;
      if (url.hash && url.pathname === window.location.pathname) return;
      if (a.target === '_blank') return;

      a.addEventListener('click', e => {
        const href = a.href;
        e.preventDefault();
        gsap.to('body', {
          opacity: 0, duration: .25, ease: 'power2.in',
          onComplete: () => { window.location.href = href; }
        });
      });
    } catch(_) {}
  });

  // Fade in on load
  gsap.from('body', { opacity: 0, duration: .35, ease: 'power2.out' });
})();

/* ─── HERO CANVAS THEME SYNC ─────────────────────────────── */
(function syncCanvasTheme() {
  // When theme toggles, update THREE.js material colours if canvas exists
  const toggle = document.getElementById('ve-theme-toggle');
  if (!toggle) return;
  toggle.addEventListener('click', () => {
    // CSS handles visual; nothing needed in THREE here as canvas
    // uses additive blending that adapts naturally to bg change.
  });
})();

/* ─── SMOOTH COUNT-UP ON HERO STATS ─────────────────────── */
(function initHeroNumbers() {
  // Numbers in the hero section trigger immediately without scroll
  const heroStats = document.querySelectorAll('.ve-hero [data-countup]');
  heroStats.forEach(el => {
    const val    = parseFloat(el.getAttribute('data-countup')) || 0;
    const suffix = el.getAttribute('data-suffix') || '';
    const numEl  = el.querySelector('.ve-countup-num') || el;
    let start    = performance.now();
    const dur    = 1400;
    (function tick(now) {
      const p = Math.min((now - start) / dur, 1);
      const e = 1 - Math.pow(1 - p, 3);
      numEl.textContent = Math.round(val * e) + suffix;
      if (p < 1) requestAnimationFrame(tick);
    })(start);
  });
})();

/* ─── NAV DROPDOWN KEYBOARD SUPPORT ─────────────────────── */
(function initNavKeyboard() {
  document.querySelectorAll('.ve-nav__item--has-children').forEach(item => {
    const link     = item.querySelector('.ve-nav__link');
    const dropdown = item.querySelector('.ve-nav__dropdown');
    if (!link || !dropdown) return;

    link.setAttribute('aria-haspopup', 'true');
    link.setAttribute('aria-expanded', 'false');

    link.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        const expanded = link.getAttribute('aria-expanded') === 'true';
        link.setAttribute('aria-expanded', !expanded);
        dropdown.style.pointerEvents = expanded ? 'none' : 'auto';
        dropdown.style.opacity       = expanded ? '0' : '1';
      }
      if (e.key === 'Escape') {
        link.setAttribute('aria-expanded', 'false');
        dropdown.style.opacity = '0';
        dropdown.style.pointerEvents = 'none';
      }
    });

    // Tab trap within dropdown
    const subLinks = dropdown.querySelectorAll('.ve-nav__sublink');
    subLinks.forEach((sub, idx) => {
      sub.addEventListener('keydown', e => {
        if (e.key === 'ArrowDown') { e.preventDefault(); subLinks[idx + 1]?.focus(); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); (idx === 0 ? link : subLinks[idx - 1]).focus(); }
        if (e.key === 'Escape')    { link.focus(); link.setAttribute('aria-expanded', 'false'); }
      });
    });
  });
})();

/* ═══════════════════════════════════════════════════════════
   THREE.JS GLOBE CANVAS (home differentiators section)
   ═══════════════════════════════════════════════════════════ */
(function initGlobe() {
  const canvas = document.getElementById('ve-globe-canvas');
  if (!canvas || typeof THREE === 'undefined') return;

  const W = canvas.offsetWidth  || 340;
  const H = canvas.offsetHeight || 340;

  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  renderer.setSize(W, H);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(45, W / H, 0.1, 100);
  camera.position.set(0, 0, 4.5);

  // Globe sphere — wireframe
  const globeGeo = new THREE.SphereGeometry(1.4, 32, 32);
  const globeMat = new THREE.MeshBasicMaterial({
    color: 0xF97316, wireframe: true, opacity: 0.12, transparent: true,
  });
  const globe = new THREE.Mesh(globeGeo, globeMat);
  scene.add(globe);

  // Solid inner globe
  const innerGeo = new THREE.SphereGeometry(1.35, 64, 64);
  const innerMat = new THREE.MeshStandardMaterial({
    color: 0x0A0A0F, metalness: 0.9, roughness: 0.2,
  });
  scene.add(new THREE.Mesh(innerGeo, innerMat));

  // Latitude/longitude lines
  function addLatLon(latCount, lonCount) {
    const mat = new THREE.LineBasicMaterial({ color: 0x2563EB, opacity: 0.18, transparent: true });
    for (let i = 0; i < latCount; i++) {
      const lat = (i / latCount) * Math.PI - Math.PI / 2;
      const pts = [];
      for (let j = 0; j <= 64; j++) {
        const lon = (j / 64) * Math.PI * 2;
        pts.push(new THREE.Vector3(
          1.42 * Math.cos(lat) * Math.cos(lon),
          1.42 * Math.sin(lat),
          1.42 * Math.cos(lat) * Math.sin(lon)
        ));
      }
      scene.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints(pts), mat));
    }
    for (let i = 0; i < lonCount; i++) {
      const lon = (i / lonCount) * Math.PI * 2;
      const pts = [];
      for (let j = 0; j <= 64; j++) {
        const lat = (j / 64) * Math.PI - Math.PI / 2;
        pts.push(new THREE.Vector3(
          1.42 * Math.cos(lat) * Math.cos(lon),
          1.42 * Math.sin(lat),
          1.42 * Math.cos(lat) * Math.sin(lon)
        ));
      }
      scene.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints(pts), mat));
    }
  }
  addLatLon(8, 12);

  // Glowing dots at US city-like positions
  const dotPositions = [
    [38.9, -77.0],  // Bowie MD / DC area
    [40.7, -74.0],  // New York
    [34.0, -118.2], // Los Angeles
    [41.8, -87.6],  // Chicago
    [29.7, -95.3],  // Houston
    [33.7, -84.3],  // Atlanta
    [47.6, -122.3], // Seattle
    [30.3, -81.6],  // Jacksonville
  ];
  dotPositions.forEach(([lat, lon]) => {
    const phi   = (90 - lat) * (Math.PI / 180);
    const theta = (lon + 180) * (Math.PI / 180);
    const r = 1.47;
    const x = -r * Math.sin(phi) * Math.cos(theta);
    const y =  r * Math.cos(phi);
    const z =  r * Math.sin(phi) * Math.sin(theta);
    const dot = new THREE.Mesh(
      new THREE.SphereGeometry(0.035, 8, 8),
      new THREE.MeshBasicMaterial({ color: 0xF97316 })
    );
    dot.position.set(x, y, z);
    scene.add(dot);
  });

  // Orbiting ring
  const ringGeo = new THREE.TorusGeometry(1.9, 0.012, 8, 100);
  const ringMat = new THREE.MeshBasicMaterial({ color: 0xF97316, opacity: 0.35, transparent: true });
  const ring = new THREE.Mesh(ringGeo, ringMat);
  ring.rotation.x = Math.PI / 4;
  scene.add(ring);

  // Lights
  scene.add(new THREE.AmbientLight(0xffffff, 0.5));
  const ptLight = new THREE.PointLight(0xF97316, 4, 12);
  ptLight.position.set(3, 2, 3);
  scene.add(ptLight);
  const ptBlue = new THREE.PointLight(0x2563EB, 2, 10);
  ptBlue.position.set(-3, -2, -2);
  scene.add(ptBlue);

  let mouseX = 0, mouseY = 0;
  document.addEventListener('mousemove', e => {
    mouseX = (e.clientX / window.innerWidth  - 0.5) * 2;
    mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
  });

  window.addEventListener('resize', () => {
    const nW = canvas.offsetWidth;
    const nH = canvas.offsetHeight;
    if (!nW || !nH) return;
    renderer.setSize(nW, nH);
    camera.aspect = nW / nH;
    camera.updateProjectionMatrix();
  });

  (function animate(t) {
    requestAnimationFrame(animate);
    const elapsed = t * 0.001;
    globe.rotation.y = elapsed * 0.15;
    ring.rotation.z  = elapsed * 0.2;
    camera.position.x += (mouseX * 0.3 - camera.position.x) * 0.04;
    camera.position.y += (-mouseY * 0.2 - camera.position.y) * 0.04;
    camera.lookAt(scene.position);
    ptLight.position.x = Math.sin(elapsed * 0.4) * 4;
    renderer.render(scene, camera);
  })(0);
})();

/* ═══════════════════════════════════════════════════════════
   THREE.JS SOLUTION PAGE CANVAS (per-service 3D visual)
   ═══════════════════════════════════════════════════════════ */
(function initSolutionCanvas() {
  const canvas = document.getElementById('ve-solution-canvas');
  if (!canvas || typeof THREE === 'undefined') return;

  const slug = canvas.getAttribute('data-service') || '';
  const W = canvas.offsetWidth  || 400;
  const H = canvas.offsetHeight || 400;

  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  renderer.setSize(W, H);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(50, W / H, 0.1, 100);
  camera.position.set(0, 0, 5);

  scene.add(new THREE.AmbientLight(0xffffff, 0.6));
  const pt1 = new THREE.PointLight(0xF97316, 4, 15);
  pt1.position.set(3, 3, 3);
  scene.add(pt1);
  const pt2 = new THREE.PointLight(0x2563EB, 2, 12);
  pt2.position.set(-3, -2, 1);
  scene.add(pt2);

  // Different geometry per service type
  let mainMesh;
  const color1 = 0xF97316;
  const color2 = 0x2563EB;

  if (slug.includes('ai') || slug.includes('data')) {
    // Icosahedron (AI/brain-like)
    const geo = new THREE.IcosahedronGeometry(1.2, 1);
    mainMesh = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({ color: 0x0A0A0F, metalness: .85, roughness: .15, wireframe: false }));
    scene.add(mainMesh);
    const wire = new THREE.Mesh(new THREE.IcosahedronGeometry(1.25, 1), new THREE.MeshBasicMaterial({ color: color1, wireframe: true, opacity: .25, transparent: true }));
    scene.add(wire);
  } else if (slug.includes('cloud')) {
    // Torus knot (flow/interconnection)
    const geo = new THREE.TorusKnotGeometry(.9, .28, 100, 16);
    mainMesh = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({ color: color2, metalness: .8, roughness: .2 }));
    scene.add(mainMesh);
  } else if (slug.includes('cyber') || slug.includes('security')) {
    // Octahedron (shield-like, sharp)
    const geo = new THREE.OctahedronGeometry(1.3, 0);
    mainMesh = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({ color: 0x0A0A0F, metalness: .9, roughness: .1 }));
    scene.add(mainMesh);
    const wire = new THREE.Mesh(new THREE.OctahedronGeometry(1.35, 0), new THREE.MeshBasicMaterial({ color: color1, wireframe: true, opacity: .4, transparent: true }));
    scene.add(wire);
  } else if (slug.includes('iot') || slug.includes('automation')) {
    // Dodecahedron (network nodes)
    const geo = new THREE.DodecahedronGeometry(1.1, 0);
    mainMesh = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({ color: 0x0A0A0F, metalness: .8, roughness: .2 }));
    scene.add(mainMesh);
    const wire = new THREE.Mesh(new THREE.DodecahedronGeometry(1.15, 0), new THREE.MeshBasicMaterial({ color: 0x06B6D4, wireframe: true, opacity: .3, transparent: true }));
    scene.add(wire);
  } else {
    // Default sphere
    const geo = new THREE.SphereGeometry(1.2, 48, 48);
    mainMesh = new THREE.Mesh(geo, new THREE.MeshStandardMaterial({ color: 0x0A0A0F, metalness: .9, roughness: .15 }));
    scene.add(mainMesh);
    const wire = new THREE.Mesh(new THREE.SphereGeometry(1.25, 18, 18), new THREE.MeshBasicMaterial({ color: color1, wireframe: true, opacity: .2, transparent: true }));
    scene.add(wire);
  }

  // Orbiting particles
  const COUNT = 80;
  const pGeo = new THREE.BufferGeometry();
  const pos  = new Float32Array(COUNT * 3);
  for (let i = 0; i < COUNT; i++) {
    const r = 2.2 + Math.random() * .8;
    const theta = Math.random() * Math.PI * 2;
    const phi   = Math.acos(2 * Math.random() - 1);
    pos[i*3]   = r * Math.sin(phi) * Math.cos(theta);
    pos[i*3+1] = r * Math.sin(phi) * Math.sin(theta);
    pos[i*3+2] = r * Math.cos(phi);
  }
  pGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
  scene.add(new THREE.Points(pGeo, new THREE.PointsMaterial({ size: .04, color: color1, transparent: true, opacity: .6, blending: THREE.AdditiveBlending })));

  window.addEventListener('resize', () => {
    const nW = canvas.offsetWidth;
    const nH = canvas.offsetHeight;
    if (!nW || !nH) return;
    renderer.setSize(nW, nH);
    camera.aspect = nW / nH;
    camera.updateProjectionMatrix();
  });

  (function animate(t) {
    requestAnimationFrame(animate);
    const elapsed = t * 0.001;
    if (mainMesh) {
      mainMesh.rotation.y = elapsed * 0.3;
      mainMesh.rotation.x = elapsed * 0.15;
    }
    renderer.render(scene, camera);
  })(0);
})();


/* ═══════════════════════════════════════════════════════════
   INTERNAL CHATBOT (rule-based, zero API cost)
   ═══════════════════════════════════════════════════════════ */
(function initChatbot() {
  'use strict';

  const widget   = document.getElementById('ve-chat-widget');
  if (!widget || typeof VE_CHAT === 'undefined') return;

  const trigger    = document.getElementById('ve-chat-trigger');
  const chatWindow = document.getElementById('ve-chat-window');
  const closeBtn   = document.getElementById('ve-chat-close');
  const messagesEl = document.getElementById('ve-chat-messages');
  const inputEl    = document.getElementById('ve-chat-input');
  const sendBtn    = document.getElementById('ve-chat-send');
  const badge      = document.getElementById('ve-chat-badge');
  const suggestions= document.getElementById('ve-chat-suggestions');

  let isOpen       = false;
  let isBusy       = false;
  let hasOpened    = false;
  let lastContext  = '';   // tracks last matched intent for follow-up context

  // ── Open / close ──────────────────────────────────────────
  function toggleChat(force) {
    isOpen = (force !== undefined) ? force : !isOpen;
    chatWindow.hidden = !isOpen;
    trigger.classList.toggle('open', isOpen);
    trigger.setAttribute('aria-expanded', String(isOpen));

    const iconOpen  = trigger.querySelector('.ve-chat-trigger__icon--open');
    const iconClose = trigger.querySelector('.ve-chat-trigger__icon--close');
    iconOpen  && iconOpen.classList.toggle('hidden', isOpen);
    iconClose && iconClose.classList.toggle('hidden', !isOpen);

    if (isOpen) {
      badge && badge.classList.add('hidden');
      if (!hasOpened) { hasOpened = true; showWelcome(); }
      setTimeout(() => inputEl && inputEl.focus(), 80);
    }
  }

  // ── Welcome message ───────────────────────────────────────
  function showWelcome() {
    appendBotMessage(
      "Hi there! 👋 I'm the Volkmann Express assistant.\n\nI can answer questions about our services, capabilities, pricing, how we work, and more. What can I help you with today?",
      false,
      ['What services do you offer?', 'How do I get started?', 'Tell me about your company', 'Contact information']
    );
  }

  // ── Append a bot message ──────────────────────────────────
  function appendBotMessage(text, showCTA, followUps = []) {
    const wrap = document.createElement('div');
    wrap.className = 've-msg ve-msg--bot';

    const avatar = document.createElement('div');
    avatar.className = 've-msg__avatar';
    avatar.textContent = 'VE';

    const bubble = document.createElement('div');
    bubble.className = 've-msg__bubble';

    // Convert newlines and **bold** to HTML
    const html = text
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
      .replace(/\n\n/g, '</p><p>')
      .replace(/\n/g, '<br>');
    bubble.innerHTML = `<p>${html}</p>`;

    if (showCTA) {
      const ctaDiv = document.createElement('div');
      ctaDiv.className = 've-chat-contact-cta';
      ctaDiv.innerHTML = `<a href="${VE_CHAT.contactUrl}" target="_self">Contact Our Team →</a>`;
      bubble.appendChild(ctaDiv);
    }

    wrap.appendChild(avatar);
    wrap.appendChild(bubble);
    messagesEl.appendChild(wrap);

    // Follow-up chips
    if (followUps.length) {
      renderFollowUps(followUps);
    }

    scrollBottom();
  }

  // ── Append a user message ─────────────────────────────────
  function appendUserMessage(text) {
    const wrap   = document.createElement('div');
    wrap.className = 've-msg ve-msg--user';
    const avatar = document.createElement('div');
    avatar.className = 've-msg__avatar';
    avatar.textContent = 'You';
    const bubble = document.createElement('div');
    bubble.className = 've-msg__bubble';
    bubble.textContent = text;
    wrap.appendChild(avatar);
    wrap.appendChild(bubble);
    messagesEl.appendChild(wrap);
    scrollBottom();
  }

  // ── Render follow-up suggestion chips ────────────────────
  function renderFollowUps(chips) {
    // Remove old follow-up chips if any
    document.querySelectorAll('.ve-followup-chips').forEach(el => el.remove());
    if (!chips.length) return;

    const bar = document.createElement('div');
    bar.className = 've-followup-chips';
    chips.forEach(label => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 've-chat-suggestion';
      btn.textContent = label;
      btn.addEventListener('click', () => {
        bar.remove();
        sendMessage(label);
      });
      bar.appendChild(btn);
    });
    messagesEl.appendChild(bar);
    scrollBottom();
  }

  // ── Typing indicator ──────────────────────────────────────
  function showTyping() {
    const div = document.createElement('div');
    div.id = 've-typing-indicator';
    div.className = 've-msg ve-msg--bot ve-typing';
    div.innerHTML = `
      <div class="ve-msg__avatar">VE</div>
      <div class="ve-msg__bubble">
        <div class="ve-typing-dot"></div>
        <div class="ve-typing-dot"></div>
        <div class="ve-typing-dot"></div>
      </div>`;
    messagesEl.appendChild(div);
    scrollBottom();
  }

  function removeTyping() {
    const el = document.getElementById('ve-typing-indicator');
    if (el) el.remove();
  }

  function scrollBottom() {
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  // ── Send a message ────────────────────────────────────────
  async function sendMessage(text) {
    text = text.trim();
    if (!text || isBusy) return;

    // Hide initial suggestion chips
    if (suggestions) suggestions.classList.add('hidden');
    // Remove any follow-up chip bars
    document.querySelectorAll('.ve-followup-chips').forEach(el => el.remove());

    appendUserMessage(text);
    inputEl.value = '';
    inputEl.style.height = 'auto';
    sendBtn.disabled = true;
    isBusy = true;

    // Small delay before showing typing indicator (feels more natural)
    await new Promise(r => setTimeout(r, 280));
    showTyping();

    try {
      const fd = new FormData();
      fd.append('action',       've_chatbot');
      fd.append('nonce',        VE_CHAT.nonce);
      fd.append('message',      text);
      fd.append('last_context', lastContext);

      const res  = await fetch(VE_CHAT.ajaxUrl, { method: 'POST', body: fd });
      const json = await res.json();

      // Simulate a brief "reading" delay based on response length
      const replyText = json.success ? (json.data.reply || '') : '';
      const delay     = Math.min(300 + replyText.length * 8, 1400);
      await new Promise(r => setTimeout(r, delay));

      removeTyping();
      isBusy = false;

      if (json.success) {
        const d = json.data;
        lastContext = d.context || '';
        appendBotMessage(d.reply, d.cta, d.follow_ups || []);
      } else {
        appendBotMessage(
          "Sorry, something went wrong on my end. Please try again or contact us directly at " + VE_CHAT.email,
          true, []
        );
      }
    } catch (err) {
      removeTyping();
      isBusy = false;
      appendBotMessage(
        "Network issue — please check your connection, or reach us directly at " + VE_CHAT.email,
        true, []
      );
    }

    sendBtn.disabled = false;
  }

  // ── Event listeners ───────────────────────────────────────
  trigger.addEventListener('click', () => toggleChat());
  closeBtn && closeBtn.addEventListener('click', () => toggleChat(false));

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && isOpen) toggleChat(false);
  });

  // Auto-grow textarea
  inputEl && inputEl.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    sendBtn.disabled = !this.value.trim();
  });

  inputEl && inputEl.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      if (inputEl.value.trim() && !isBusy) sendMessage(inputEl.value);
    }
  });

  sendBtn && sendBtn.addEventListener('click', () => {
    if (inputEl.value.trim() && !isBusy) sendMessage(inputEl.value);
  });

  // Initial suggestion chips
  widget.querySelectorAll('.ve-chat-suggestion').forEach(btn => {
    btn.addEventListener('click', () => sendMessage(btn.getAttribute('data-prompt') || btn.textContent));
  });

  // Attract-attention badge after 4 seconds if chat not yet opened
  setTimeout(() => { if (!isOpen) badge && badge.classList.remove('hidden'); }, 4000);

  // Proactively open after 45s on key pages (optional — respects sessionStorage)
  if (!sessionStorage.getItem('ve_chat_seen')) {
    setTimeout(() => {
      if (!isOpen && !sessionStorage.getItem('ve_chat_seen')) {
        sessionStorage.setItem('ve_chat_seen', '1');
        toggleChat(true);
      }
    }, 45000);
  }

})();

/* ═══════════════════════════════════════════════════════════
   NEWSLETTER FORM
   ═══════════════════════════════════════════════════════════ */
(function initNewsletter() {
  const form = document.getElementById('ve-newsletter-form');
  if (!form) return;
  form.addEventListener('submit', e => {
    e.preventDefault();
    const emailInput = form.querySelector('input[type="email"]');
    if (!emailInput || !emailInput.value) return;
    form.innerHTML = `<div class="ve-form-success" style="display:flex;gap:1rem;align-items:flex-start;"><svg viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" style="width:32px;height:32px;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg><div><strong style="display:block;margin-bottom:.25rem;">You're subscribed!</strong><p style="margin:0;font-size:.875rem;color:var(--ve-text-muted);">We'll send our next insights update to ${emailInput.value}.</p></div></div>`;
  });
})();
