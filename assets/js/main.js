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
    const BURGER   = document.getElementById('ve-hamburger');
    const NAV      = document.getElementById('ve-mobile-nav');
    const PANEL    = NAV?.querySelector('.ve-mobile-nav__panel');
    const BACKDROP = NAV?.querySelector('.ve-mobile-nav__backdrop');
    const CLOSE    = document.getElementById('ve-mobile-close');
    let   open     = false;

    function toggle(force) {
      open = (force !== undefined) ? force : !open;
      
      if (NAV) {
        NAV.classList.toggle('open', open);
        NAV.setAttribute('aria-hidden', !open);
      }
      if (BURGER) {
        BURGER.classList.toggle('open', open);
        BURGER.setAttribute('aria-expanded', open);
        BURGER.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
      }
      
      // Prevent body scroll when open
      document.body.style.overflow = open ? 'hidden' : '';

      // Focus management
      if (open && CLOSE) {
        setTimeout(() => CLOSE.focus(), 100);
      } else if (!open && BURGER) {
        BURGER.focus();
      }
    }

    function initDropdowns() {
      const parentItems = NAV?.querySelectorAll('.menu-item-has-children, .ve-nav__item--has-children');
      if (!parentItems) return;

      parentItems.forEach(item => {
        const link = item.querySelector(':scope > a');
        const submenu = item.querySelector(':scope > .sub-menu, :scope > .ve-nav__dropdown');
        if (!link || !submenu) return;

        // Make the link toggle the dropdown
        link.addEventListener('click', e => {
          const href = link.getAttribute('href');
          // If no real href, just toggle
          if (!href || href === '#' || href === '' || href === window.location.href) {
            e.preventDefault();
            toggleDropdown(item, parentItems);
          } else {
            // Has real href — toggle dropdown, don't navigate immediately
            e.preventDefault();
            toggleDropdown(item, parentItems);
          }
        });
      });
    }

    function toggleDropdown(item, allItems) {
      const isExpanded = item.classList.contains('is-expanded');
      
      // Close all other dropdowns
      allItems.forEach(other => {
        if (other !== item) {
          other.classList.remove('is-expanded');
        }
      });

      // Toggle this one
      item.classList.toggle('is-expanded', !isExpanded);
    }

    function initThemeToggle() {
      const themeBtns = NAV?.querySelectorAll('.ve-mobile-theme-btn');
      if (!themeBtns) return;

      themeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const theme = btn.dataset.theme;
          const html = document.documentElement;
          
          if (theme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('ve_theme', 'dark');
          } else {
            html.classList.remove('dark');
            localStorage.setItem('ve_theme', 'light');
          }
        });
      });
    }

    function init() {
      if (!NAV) return;

      // Set initial aria state
      NAV.setAttribute('aria-hidden', 'true');

      // Hamburger opens nav
      BURGER?.addEventListener('click', () => toggle(true));

      // Close button closes nav
      CLOSE?.addEventListener('click', () => toggle(false));

      // Backdrop click closes nav
      BACKDROP?.addEventListener('click', () => toggle(false));

      // Close on nav link click (submenu items)
      NAV.querySelectorAll('.sub-menu a, .ve-nav__sublink').forEach(a => {
        a.addEventListener('click', () => {
          setTimeout(() => toggle(false), 100);
        });
      });

      // Close on CTA button click
      NAV.querySelector('.ve-btn--primary')?.addEventListener('click', () => {
        setTimeout(() => toggle(false), 100);
      });

      // Logo click closes nav
      NAV.querySelector('.ve-mobile-nav__header .ve-logo')?.addEventListener('click', () => {
        setTimeout(() => toggle(false), 100);
      });

      // Escape key closes nav
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && open) toggle(false);
      });

      // Close on resize to desktop
      let resizeTimer;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
          if (window.innerWidth >= 961 && open) toggle(false);
        }, 100);
      });

      // Initialize dropdown accordions
      initDropdowns();

      // Initialize mobile theme toggle
      initThemeToggle();
    }

    return { init, toggle, isOpen: () => open };
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
   Modernized: Holographic globe with data streams & pulse effects
   ═══════════════════════════════════════════════════════════ */
(function initGlobe() {
  const canvas = document.getElementById('ve-globe-canvas');
  if (!canvas || typeof THREE === 'undefined') return;

  const W = canvas.offsetWidth  || 420;
  const H = canvas.offsetHeight || 420;

  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  renderer.setSize(W, H);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(40, W / H, 0.1, 100);
  camera.position.set(0, 0, 5);

  // Colors
  const ORANGE = 0xF97316;
  const BLUE   = 0x2563EB;
  const CYAN   = 0x06B6D4;
  const DARK   = 0x0A0A0F;

  // ─── Core globe with gradient-like effect ─────────────────
  const coreGeo = new THREE.SphereGeometry(1.3, 64, 64);
  const coreMat = new THREE.MeshStandardMaterial({
    color: DARK, metalness: 0.95, roughness: 0.05,
  });
  const core = new THREE.Mesh(coreGeo, coreMat);
  scene.add(core);

  // ─── Holographic wireframe shell ──────────────────────────
  const shellGeo = new THREE.IcosahedronGeometry(1.45, 2);
  const shellMat = new THREE.MeshBasicMaterial({
    color: ORANGE, wireframe: true, opacity: 0.15, transparent: true,
  });
  const shell = new THREE.Mesh(shellGeo, shellMat);
  scene.add(shell);

  // ─── Outer glow ring ──────────────────────────────────────
  const glowRingGeo = new THREE.TorusGeometry(1.6, 0.008, 8, 128);
  const glowRingMat = new THREE.MeshBasicMaterial({ color: CYAN, opacity: 0.4, transparent: true });
  const glowRing = new THREE.Mesh(glowRingGeo, glowRingMat);
  glowRing.rotation.x = Math.PI / 2;
  scene.add(glowRing);

  // ─── Latitude arcs (partial, modern look) ─────────────────
  const arcMat = new THREE.LineBasicMaterial({ color: BLUE, opacity: 0.25, transparent: true });
  [0.3, 0.6, -0.3, -0.6].forEach(yPos => {
    const radius = Math.sqrt(1.35 * 1.35 - yPos * yPos);
    const pts = [];
    for (let i = 0; i <= 64; i++) {
      const angle = (i / 64) * Math.PI * 1.5 - Math.PI * 0.25; // Partial arc
      pts.push(new THREE.Vector3(Math.cos(angle) * radius, yPos, Math.sin(angle) * radius));
    }
    scene.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints(pts), arcMat));
  });

  // ─── US city nodes with pulse effect ──────────────────────
  const cityData = [
    { lat: 38.9, lon: -77.0, name: 'DC' },
    { lat: 40.7, lon: -74.0, name: 'NYC' },
    { lat: 34.0, lon: -118.2, name: 'LA' },
    { lat: 41.8, lon: -87.6, name: 'Chicago' },
    { lat: 29.7, lon: -95.3, name: 'Houston' },
    { lat: 33.7, lon: -84.3, name: 'Atlanta' },
    { lat: 47.6, lon: -122.3, name: 'Seattle' },
    { lat: 25.7, lon: -80.2, name: 'Miami' },
    { lat: 42.3, lon: -71.0, name: 'Boston' },
    { lat: 39.7, lon: -104.9, name: 'Denver' },
  ];

  const cityNodes = [];
  cityData.forEach(({ lat, lon }, i) => {
    const phi   = (90 - lat) * (Math.PI / 180);
    const theta = (lon + 180) * (Math.PI / 180);
    const r = 1.38;
    const x = -r * Math.sin(phi) * Math.cos(theta);
    const y =  r * Math.cos(phi);
    const z =  r * Math.sin(phi) * Math.sin(theta);

    // Node dot
    const node = new THREE.Mesh(
      new THREE.SphereGeometry(0.04, 12, 12),
      new THREE.MeshBasicMaterial({ color: ORANGE })
    );
    node.position.set(x, y, z);
    node.userData = { baseScale: 1, phase: i * 0.5 };
    scene.add(node);
    cityNodes.push(node);

    // Pulse ring around node
    const pulse = new THREE.Mesh(
      new THREE.RingGeometry(0.06, 0.08, 16),
      new THREE.MeshBasicMaterial({ color: ORANGE, opacity: 0.5, transparent: true, side: THREE.DoubleSide })
    );
    pulse.position.set(x, y, z);
    pulse.lookAt(0, 0, 0);
    pulse.userData = { phase: i * 0.5 };
    scene.add(pulse);
    cityNodes.push(pulse);
  });

  // ─── Data stream particles orbiting ───────────────────────
  const streamCount = 60;
  const streamGeo = new THREE.BufferGeometry();
  const streamPos = new Float32Array(streamCount * 3);
  const streamData = [];
  for (let i = 0; i < streamCount; i++) {
    const orbitRadius = 1.7 + Math.random() * 0.4;
    const angle = Math.random() * Math.PI * 2;
    const tilt = (Math.random() - 0.5) * 0.8;
    streamPos[i * 3] = Math.cos(angle) * orbitRadius;
    streamPos[i * 3 + 1] = tilt + Math.sin(angle) * 0.3;
    streamPos[i * 3 + 2] = Math.sin(angle) * orbitRadius;
    streamData.push({ angle, orbitRadius, tilt, speed: 0.3 + Math.random() * 0.4 });
  }
  streamGeo.setAttribute('position', new THREE.BufferAttribute(streamPos, 3));
  const streamMat = new THREE.PointsMaterial({
    size: 0.035, color: CYAN, transparent: true, opacity: 0.7,
    blending: THREE.AdditiveBlending, depthWrite: false,
  });
  const streams = new THREE.Points(streamGeo, streamMat);
  scene.add(streams);

  // ─── Orbiting satellite rings ─────────────────────────────
  const rings = [];
  [
    { radius: 1.9, color: ORANGE, tiltX: Math.PI / 5, tiltY: 0.2 },
    { radius: 2.1, color: BLUE, tiltX: -Math.PI / 4, tiltY: -0.3 },
    { radius: 2.3, color: CYAN, tiltX: Math.PI / 6, tiltY: 0.5 },
  ].forEach(({ radius, color, tiltX, tiltY }) => {
    const ring = new THREE.Mesh(
      new THREE.TorusGeometry(radius, 0.012, 8, 100),
      new THREE.MeshBasicMaterial({ color, opacity: 0.25, transparent: true })
    );
    ring.rotation.x = tiltX;
    ring.rotation.y = tiltY;
    ring.userData = { speedX: 0.1 + Math.random() * 0.1, speedY: 0.05 + Math.random() * 0.1 };
    scene.add(ring);
    rings.push(ring);
  });

  // ─── Lighting ─────────────────────────────────────────────
  scene.add(new THREE.AmbientLight(0xffffff, 0.4));
  const keyLight = new THREE.PointLight(ORANGE, 4, 15);
  keyLight.position.set(4, 3, 3);
  scene.add(keyLight);
  const fillLight = new THREE.PointLight(BLUE, 2.5, 12);
  fillLight.position.set(-4, -2, 2);
  scene.add(fillLight);
  const rimLight = new THREE.PointLight(CYAN, 2, 10);
  rimLight.position.set(0, 4, -3);
  scene.add(rimLight);

  // ─── Mouse interaction ────────────────────────────────────
  let mouseX = 0, mouseY = 0;
  document.addEventListener('mousemove', e => {
    mouseX = (e.clientX / window.innerWidth  - 0.5) * 2;
    mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
  });

  // ─── Resize handler ───────────────────────────────────────
  window.addEventListener('resize', () => {
    const nW = canvas.offsetWidth;
    const nH = canvas.offsetHeight;
    if (!nW || !nH) return;
    renderer.setSize(nW, nH);
    camera.aspect = nW / nH;
    camera.updateProjectionMatrix();
  });

  // ─── Animation loop ───────────────────────────────────────
  (function animate(t) {
    requestAnimationFrame(animate);
    const elapsed = t * 0.001;

    // Rotate core and shell
    core.rotation.y = elapsed * 0.1;
    shell.rotation.y = -elapsed * 0.08;
    shell.rotation.x = Math.sin(elapsed * 0.2) * 0.05;

    // Glow ring pulse
    glowRing.scale.setScalar(1 + Math.sin(elapsed * 2) * 0.03);
    glowRing.material.opacity = 0.3 + Math.sin(elapsed * 2) * 0.15;

    // City node pulses
    cityNodes.forEach((node, i) => {
      if (node.geometry.type === 'SphereGeometry') {
        node.scale.setScalar(1 + Math.sin(elapsed * 3 + node.userData.phase) * 0.2);
      } else {
        // Pulse ring
        const scale = 1 + Math.sin(elapsed * 2 + node.userData.phase) * 0.5;
        node.scale.setScalar(scale);
        node.material.opacity = 0.6 - Math.sin(elapsed * 2 + node.userData.phase) * 0.4;
      }
    });

    // Data streams orbit
    const positions = streams.geometry.attributes.position.array;
    streamData.forEach((d, i) => {
      d.angle += d.speed * 0.01;
      positions[i * 3] = Math.cos(d.angle) * d.orbitRadius;
      positions[i * 3 + 1] = d.tilt + Math.sin(d.angle * 2) * 0.2;
      positions[i * 3 + 2] = Math.sin(d.angle) * d.orbitRadius;
    });
    streams.geometry.attributes.position.needsUpdate = true;

    // Orbiting rings
    rings.forEach(ring => {
      ring.rotation.z += ring.userData.speedX * 0.01;
    });

    // Camera parallax
    camera.position.x += (mouseX * 0.4 - camera.position.x) * 0.03;
    camera.position.y += (-mouseY * 0.25 - camera.position.y) * 0.03;
    camera.lookAt(scene.position);

    // Animate key light
    keyLight.position.x = 4 + Math.sin(elapsed * 0.5) * 1;
    keyLight.position.y = 3 + Math.cos(elapsed * 0.3) * 0.5;

    renderer.render(scene, camera);
  })(0);
})();

/* ═══════════════════════════════════════════════════════════
   THREE.JS SOLUTION PAGE CANVAS (per-service 3D visual)
   Each of the 8 services gets a unique, thematic 3D scene.
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

  // Shared colors
  const ORANGE = 0xF97316;
  const BLUE   = 0x2563EB;
  const CYAN   = 0x06B6D4;
  const DARK   = 0x0A0A0F;

  // Animation state
  let animateFn = null;
  let extraObjects = [];

  // ─── 1. AI & MACHINE LEARNING: Neural network brain ───────
  if (slug === 'ai-machine-learning') {
    // Central brain-like icosahedron with pulsing neural connections
    const core = new THREE.Mesh(
      new THREE.IcosahedronGeometry(0.9, 2),
      new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.9, roughness: 0.1 })
    );
    scene.add(core);

    // Outer wireframe shell
    const shell = new THREE.Mesh(
      new THREE.IcosahedronGeometry(1.1, 1),
      new THREE.MeshBasicMaterial({ color: ORANGE, wireframe: true, opacity: 0.3, transparent: true })
    );
    scene.add(shell);

    // Neural nodes orbiting
    const nodes = [];
    for (let i = 0; i < 12; i++) {
      const node = new THREE.Mesh(
        new THREE.SphereGeometry(0.08, 12, 12),
        new THREE.MeshStandardMaterial({ color: ORANGE, emissive: ORANGE, emissiveIntensity: 0.5 })
      );
      const angle = (i / 12) * Math.PI * 2;
      const layer = i % 3;
      node.userData = { angle, radius: 1.6 + layer * 0.3, speed: 0.4 + layer * 0.15, yOffset: (layer - 1) * 0.5 };
      scene.add(node);
      nodes.push(node);
    }

    // Synaptic connections (lines between nearby nodes)
    const lineMat = new THREE.LineBasicMaterial({ color: CYAN, opacity: 0.25, transparent: true });

    animateFn = (t) => {
      core.rotation.y = t * 0.2;
      core.rotation.x = Math.sin(t * 0.3) * 0.1;
      shell.rotation.y = -t * 0.15;
      shell.rotation.z = t * 0.1;

      nodes.forEach((n, i) => {
        const d = n.userData;
        n.position.x = Math.cos(d.angle + t * d.speed) * d.radius;
        n.position.z = Math.sin(d.angle + t * d.speed) * d.radius * 0.6;
        n.position.y = d.yOffset + Math.sin(t * 2 + i) * 0.15;
        n.material.emissiveIntensity = 0.4 + Math.sin(t * 3 + i) * 0.3;
      });
    };
  }

  // ─── 2. CLOUD SOLUTIONS: Floating cloud infrastructure ────
  else if (slug === 'cloud') {
    // Layered horizontal planes representing cloud tiers
    const tiers = [];
    [-0.8, 0, 0.8].forEach((y, i) => {
      const tier = new THREE.Mesh(
        new THREE.BoxGeometry(2.5 - i * 0.3, 0.08, 1.8 - i * 0.2),
        new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.8, roughness: 0.2, transparent: true, opacity: 0.9 })
      );
      tier.position.y = y;
      scene.add(tier);
      tiers.push(tier);

      // Edge glow
      const edges = new THREE.LineSegments(
        new THREE.EdgesGeometry(tier.geometry),
        new THREE.LineBasicMaterial({ color: i === 1 ? ORANGE : BLUE, opacity: 0.6, transparent: true })
      );
      edges.position.copy(tier.position);
      scene.add(edges);
      extraObjects.push(edges);
    });

    // Data packets flowing between tiers
    const packets = [];
    for (let i = 0; i < 15; i++) {
      const packet = new THREE.Mesh(
        new THREE.BoxGeometry(0.1, 0.1, 0.1),
        new THREE.MeshBasicMaterial({ color: i % 2 ? ORANGE : CYAN })
      );
      packet.userData = {
        x: (Math.random() - 0.5) * 2,
        z: (Math.random() - 0.5) * 1.4,
        speed: 0.5 + Math.random() * 0.5,
        phase: Math.random() * Math.PI * 2
      };
      scene.add(packet);
      packets.push(packet);
    }

    animateFn = (t) => {
      tiers.forEach((tier, i) => {
        tier.position.y = [-0.8, 0, 0.8][i] + Math.sin(t * 0.8 + i * 0.5) * 0.05;
        extraObjects[i].position.y = tier.position.y;
      });
      packets.forEach(p => {
        const d = p.userData;
        p.position.x = d.x + Math.sin(t + d.phase) * 0.3;
        p.position.z = d.z;
        p.position.y = Math.sin(t * d.speed + d.phase) * 1.2;
        p.rotation.x = t * 2;
        p.rotation.z = t * 1.5;
      });
    };
  }

  // ─── 3. CYBERSECURITY: Shield with rotating lock rings ────
  else if (slug === 'cybersecurity') {
    // Central shield (octahedron)
    const shield = new THREE.Mesh(
      new THREE.OctahedronGeometry(0.8, 0),
      new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.95, roughness: 0.05 })
    );
    scene.add(shield);

    // Protective rings (like a force field)
    const rings = [];
    [1.3, 1.6, 1.9].forEach((r, i) => {
      const ring = new THREE.Mesh(
        new THREE.TorusGeometry(r, 0.025, 8, 64),
        new THREE.MeshBasicMaterial({ color: i === 1 ? ORANGE : BLUE, opacity: 0.5 - i * 0.1, transparent: true })
      );
      ring.userData = { baseRotX: (i - 1) * 0.4, baseRotY: i * 0.3, speed: 0.3 + i * 0.1 };
      scene.add(ring);
      rings.push(ring);
    });

    // Lock icon particles at center
    const lockDots = [];
    const lockShape = [
      [0, 0.3], [-0.15, 0.15], [0.15, 0.15], [-0.15, -0.1], [0.15, -0.1], [-0.15, -0.25], [0.15, -0.25], [0, -0.25]
    ];
    lockShape.forEach(([x, y]) => {
      const dot = new THREE.Mesh(
        new THREE.SphereGeometry(0.04, 8, 8),
        new THREE.MeshBasicMaterial({ color: ORANGE })
      );
      dot.position.set(x * 1.5, y * 1.5, 0.85);
      scene.add(dot);
      lockDots.push(dot);
    });

    animateFn = (t) => {
      shield.rotation.y = t * 0.4;
      shield.rotation.x = Math.sin(t * 0.5) * 0.2;
      rings.forEach(ring => {
        const d = ring.userData;
        ring.rotation.x = d.baseRotX + t * d.speed;
        ring.rotation.y = d.baseRotY + t * d.speed * 0.7;
      });
      lockDots.forEach((dot, i) => {
        dot.material.opacity = 0.6 + Math.sin(t * 4 + i * 0.5) * 0.4;
      });
    };
  }

  // ─── 4. DATA ANALYTICS: 3D bar chart with floating data ───
  else if (slug === 'data-analytics') {
    // Bar chart columns
    const bars = [];
    const barHeights = [0.6, 1.0, 0.8, 1.4, 1.1, 0.9, 1.3, 0.7];
    barHeights.forEach((h, i) => {
      const bar = new THREE.Mesh(
        new THREE.BoxGeometry(0.25, h, 0.25),
        new THREE.MeshStandardMaterial({ color: i % 2 ? ORANGE : BLUE, metalness: 0.7, roughness: 0.3 })
      );
      bar.position.x = (i - 3.5) * 0.4;
      bar.position.y = h / 2 - 0.7;
      bar.position.z = 0;
      bar.userData = { baseHeight: h, phase: i * 0.3 };
      scene.add(bar);
      bars.push(bar);
    });

    // Floating data points
    const dataPoints = [];
    for (let i = 0; i < 25; i++) {
      const point = new THREE.Mesh(
        new THREE.SphereGeometry(0.04, 8, 8),
        new THREE.MeshBasicMaterial({ color: CYAN, transparent: true, opacity: 0.7 })
      );
      point.userData = {
        baseX: (Math.random() - 0.5) * 3,
        baseY: (Math.random() - 0.5) * 2,
        baseZ: (Math.random() - 0.5) * 2,
        speed: 0.5 + Math.random()
      };
      scene.add(point);
      dataPoints.push(point);
    }

    // Trend line
    const linePoints = [];
    for (let i = 0; i <= 20; i++) {
      linePoints.push(new THREE.Vector3((i / 20 - 0.5) * 3, Math.sin(i * 0.4) * 0.3 + 0.8, -0.5));
    }
    const trendLine = new THREE.Line(
      new THREE.BufferGeometry().setFromPoints(linePoints),
      new THREE.LineBasicMaterial({ color: ORANGE, opacity: 0.8, transparent: true })
    );
    scene.add(trendLine);

    animateFn = (t) => {
      bars.forEach(bar => {
        const d = bar.userData;
        const newH = d.baseHeight + Math.sin(t * 1.5 + d.phase) * 0.15;
        bar.scale.y = newH / d.baseHeight;
        bar.position.y = (newH / 2) - 0.7;
      });
      dataPoints.forEach(p => {
        const d = p.userData;
        p.position.x = d.baseX + Math.sin(t * d.speed) * 0.2;
        p.position.y = d.baseY + Math.cos(t * d.speed * 0.7) * 0.2;
        p.position.z = d.baseZ + Math.sin(t * d.speed * 0.5) * 0.2;
      });
    };
  }

  // ─── 5. DIGITAL TRANSFORMATION: Morphing cube to sphere ───
  else if (slug === 'digital-transformation') {
    // Wireframe cube
    const cube = new THREE.Mesh(
      new THREE.BoxGeometry(1.4, 1.4, 1.4),
      new THREE.MeshBasicMaterial({ color: BLUE, wireframe: true, opacity: 0.4, transparent: true })
    );
    scene.add(cube);

    // Wireframe sphere (same position, will morph visually via rotation)
    const sphere = new THREE.Mesh(
      new THREE.SphereGeometry(1, 16, 16),
      new THREE.MeshBasicMaterial({ color: ORANGE, wireframe: true, opacity: 0.4, transparent: true })
    );
    scene.add(sphere);

    // Transformation particles spiraling
    const spiralParticles = [];
    for (let i = 0; i < 40; i++) {
      const p = new THREE.Mesh(
        new THREE.SphereGeometry(0.05, 6, 6),
        new THREE.MeshBasicMaterial({ color: i % 2 ? ORANGE : CYAN })
      );
      p.userData = { angle: (i / 40) * Math.PI * 4, height: (i / 40) * 2 - 1, radius: 1.8 };
      scene.add(p);
      spiralParticles.push(p);
    }

    // Arrow indicating transformation
    const arrowGroup = new THREE.Group();
    const arrowShaft = new THREE.Mesh(
      new THREE.CylinderGeometry(0.03, 0.03, 0.8, 8),
      new THREE.MeshBasicMaterial({ color: ORANGE })
    );
    arrowShaft.rotation.z = -Math.PI / 2;
    arrowGroup.add(arrowShaft);
    const arrowHead = new THREE.Mesh(
      new THREE.ConeGeometry(0.08, 0.2, 8),
      new THREE.MeshBasicMaterial({ color: ORANGE })
    );
    arrowHead.position.x = 0.5;
    arrowHead.rotation.z = -Math.PI / 2;
    arrowGroup.add(arrowHead);
    arrowGroup.position.y = -1.5;
    scene.add(arrowGroup);

    animateFn = (t) => {
      const morph = (Math.sin(t * 0.5) + 1) / 2; // 0 to 1
      cube.scale.setScalar(1 - morph * 0.3);
      cube.rotation.y = t * 0.3;
      cube.rotation.x = t * 0.2;
      cube.material.opacity = 0.5 - morph * 0.3;

      sphere.scale.setScalar(0.7 + morph * 0.5);
      sphere.rotation.y = -t * 0.25;
      sphere.material.opacity = 0.2 + morph * 0.4;

      spiralParticles.forEach((p, i) => {
        const d = p.userData;
        const angle = d.angle + t * 0.8;
        p.position.x = Math.cos(angle) * d.radius;
        p.position.z = Math.sin(angle) * d.radius * 0.5;
        p.position.y = d.height + Math.sin(t * 2 + i * 0.2) * 0.1;
      });

      arrowGroup.position.x = Math.sin(t) * 0.1;
    };
  }

  // ─── 6. CUSTOM SOFTWARE: Code blocks / modular stack ──────
  else if (slug === 'custom-software') {
    // Stacked code modules
    const modules = [];
    const moduleData = [
      { w: 1.8, h: 0.3, d: 1.2, y: -0.8, color: BLUE },
      { w: 1.5, h: 0.35, d: 1.0, y: -0.35, color: DARK },
      { w: 1.6, h: 0.3, d: 1.1, y: 0.1, color: ORANGE },
      { w: 1.3, h: 0.35, d: 0.9, y: 0.55, color: DARK },
      { w: 1.0, h: 0.3, d: 0.7, y: 0.95, color: CYAN },
    ];
    moduleData.forEach((m, i) => {
      const mod = new THREE.Mesh(
        new THREE.BoxGeometry(m.w, m.h, m.d),
        new THREE.MeshStandardMaterial({ color: m.color, metalness: 0.8, roughness: 0.2 })
      );
      mod.position.y = m.y;
      mod.userData = { baseY: m.y, phase: i * 0.4 };
      scene.add(mod);
      modules.push(mod);

      // Code line details on each module
      const lines = new THREE.LineSegments(
        new THREE.EdgesGeometry(mod.geometry),
        new THREE.LineBasicMaterial({ color: 0xffffff, opacity: 0.15, transparent: true })
      );
      lines.position.copy(mod.position);
      scene.add(lines);
      extraObjects.push({ line: lines, mod });
    });

    // Floating brackets
    const bracketMat = new THREE.MeshBasicMaterial({ color: ORANGE, transparent: true, opacity: 0.6 });
    const leftBracket = new THREE.Mesh(new THREE.TorusGeometry(0.3, 0.03, 8, 16, Math.PI), bracketMat);
    leftBracket.position.set(-1.5, 0, 0);
    leftBracket.rotation.z = Math.PI / 2;
    scene.add(leftBracket);
    const rightBracket = leftBracket.clone();
    rightBracket.position.x = 1.5;
    rightBracket.rotation.z = -Math.PI / 2;
    scene.add(rightBracket);

    animateFn = (t) => {
      modules.forEach((mod, i) => {
        const d = mod.userData;
        mod.position.y = d.baseY + Math.sin(t * 1.2 + d.phase) * 0.05;
        mod.rotation.y = Math.sin(t * 0.5 + d.phase) * 0.05;
        extraObjects[i].line.position.y = mod.position.y;
        extraObjects[i].line.rotation.y = mod.rotation.y;
      });
      leftBracket.position.y = Math.sin(t * 0.8) * 0.1;
      rightBracket.position.y = Math.sin(t * 0.8 + 1) * 0.1;
    };
  }

  // ─── 7. IOT & AUTOMATION: Connected sensor network ────────
  else if (slug === 'iot-automation') {
    // Central hub
    const hub = new THREE.Mesh(
      new THREE.DodecahedronGeometry(0.5, 0),
      new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.9, roughness: 0.1 })
    );
    scene.add(hub);
    const hubGlow = new THREE.Mesh(
      new THREE.DodecahedronGeometry(0.55, 0),
      new THREE.MeshBasicMaterial({ color: ORANGE, wireframe: true, opacity: 0.4, transparent: true })
    );
    scene.add(hubGlow);

    // Sensor nodes in a network
    const sensors = [];
    const sensorPositions = [
      [-1.5, 0.8, 0], [1.5, 0.8, 0], [-1.2, -0.9, 0.5], [1.2, -0.9, 0.5],
      [0, 1.4, -0.3], [0, -1.3, -0.3], [-0.8, 0, 1.2], [0.8, 0, 1.2]
    ];
    sensorPositions.forEach((pos, i) => {
      const sensor = new THREE.Mesh(
        new THREE.BoxGeometry(0.2, 0.2, 0.2),
        new THREE.MeshStandardMaterial({ color: i % 2 ? CYAN : BLUE, metalness: 0.7, roughness: 0.3 })
      );
      sensor.position.set(...pos);
      sensor.userData = { basePos: pos.slice(), pulsePhase: i * 0.5 };
      scene.add(sensor);
      sensors.push(sensor);

      // Connection line to hub
      const lineGeo = new THREE.BufferGeometry().setFromPoints([
        new THREE.Vector3(0, 0, 0),
        new THREE.Vector3(...pos)
      ]);
      const line = new THREE.Line(lineGeo, new THREE.LineBasicMaterial({ color: ORANGE, opacity: 0.3, transparent: true }));
      scene.add(line);
    });

    // Data pulses traveling along connections
    const pulses = [];
    for (let i = 0; i < 8; i++) {
      const pulse = new THREE.Mesh(
        new THREE.SphereGeometry(0.06, 8, 8),
        new THREE.MeshBasicMaterial({ color: ORANGE })
      );
      pulse.userData = { targetIdx: i, progress: Math.random() };
      scene.add(pulse);
      pulses.push(pulse);
    }

    animateFn = (t) => {
      hub.rotation.y = t * 0.3;
      hub.rotation.x = t * 0.15;
      hubGlow.rotation.y = -t * 0.2;

      sensors.forEach((s, i) => {
        const d = s.userData;
        s.rotation.y = t * 0.5;
        s.scale.setScalar(1 + Math.sin(t * 3 + d.pulsePhase) * 0.1);
      });

      pulses.forEach((p, i) => {
        const d = p.userData;
        d.progress += 0.008;
        if (d.progress > 1) d.progress = 0;
        const target = sensorPositions[d.targetIdx];
        const prog = d.progress < 0.5 ? d.progress * 2 : (1 - d.progress) * 2;
        p.position.x = target[0] * prog;
        p.position.y = target[1] * prog;
        p.position.z = target[2] * prog;
      });
    };
  }

  // ─── 8. MANAGED IT SERVICES: Server rack with monitors ────
  else if (slug === 'managed-it-services') {
    // Server rack frame
    const rack = new THREE.Group();
    const rackFrame = new THREE.Mesh(
      new THREE.BoxGeometry(1.4, 2.2, 0.8),
      new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.85, roughness: 0.15, transparent: true, opacity: 0.9 })
    );
    rack.add(rackFrame);

    // Server units
    const servers = [];
    for (let i = 0; i < 5; i++) {
      const server = new THREE.Mesh(
        new THREE.BoxGeometry(1.2, 0.3, 0.6),
        new THREE.MeshStandardMaterial({ color: 0x1a1a24, metalness: 0.8, roughness: 0.2 })
      );
      server.position.y = -0.8 + i * 0.4;
      server.position.z = 0.05;
      rack.add(server);
      servers.push(server);

      // Status LED
      const led = new THREE.Mesh(
        new THREE.SphereGeometry(0.04, 8, 8),
        new THREE.MeshBasicMaterial({ color: i === 2 ? ORANGE : 0x22C55E })
      );
      led.position.set(0.5, -0.8 + i * 0.4, 0.35);
      led.userData = { phase: i * 0.7 };
      rack.add(led);
      extraObjects.push(led);
    }
    scene.add(rack);

    // Floating monitoring graphs
    const graphs = [];
    [-1.8, 1.8].forEach((x, i) => {
      const graph = new THREE.Group();
      const screen = new THREE.Mesh(
        new THREE.PlaneGeometry(0.8, 0.5),
        new THREE.MeshBasicMaterial({ color: DARK, side: THREE.DoubleSide })
      );
      graph.add(screen);
      const border = new THREE.LineSegments(
        new THREE.EdgesGeometry(screen.geometry),
        new THREE.LineBasicMaterial({ color: i ? CYAN : ORANGE })
      );
      graph.add(border);
      graph.position.set(x, 0.3, 0.5);
      graph.rotation.y = x > 0 ? -0.3 : 0.3;
      scene.add(graph);
      graphs.push(graph);
    });

    animateFn = (t) => {
      rack.rotation.y = Math.sin(t * 0.3) * 0.1;
      extraObjects.forEach((led, i) => {
        led.material.opacity = 0.6 + Math.sin(t * 4 + led.userData.phase) * 0.4;
      });
      graphs.forEach((g, i) => {
        g.position.y = 0.3 + Math.sin(t * 0.8 + i) * 0.1;
      });
    };
  }

  // ─── DEFAULT: Elegant sphere fallback ─────────────────────
  else {
    const core = new THREE.Mesh(
      new THREE.SphereGeometry(1, 64, 64),
      new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.9, roughness: 0.1 })
    );
    scene.add(core);
    const wire = new THREE.Mesh(
      new THREE.SphereGeometry(1.1, 24, 24),
      new THREE.MeshBasicMaterial({ color: ORANGE, wireframe: true, opacity: 0.25, transparent: true })
    );
    scene.add(wire);

    animateFn = (t) => {
      core.rotation.y = t * 0.2;
      wire.rotation.y = -t * 0.15;
      wire.rotation.x = t * 0.1;
    };
  }

  // ─── Lighting ─────────────────────────────────────────────
  scene.add(new THREE.AmbientLight(0xffffff, 0.5));
  const keyLight = new THREE.PointLight(ORANGE, 3, 15);
  keyLight.position.set(3, 2, 3);
  scene.add(keyLight);
  const fillLight = new THREE.PointLight(BLUE, 2, 12);
  fillLight.position.set(-3, -1, 2);
  scene.add(fillLight);
  const rimLight = new THREE.PointLight(CYAN, 1.5, 10);
  rimLight.position.set(0, 3, -3);
  scene.add(rimLight);

  // ─── Mouse interaction ────────────────────────────────────
  let mouseX = 0, mouseY = 0;
  document.addEventListener('mousemove', e => {
    mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
    mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
  });

  // ─── Resize handler ───────────────────────────────────────
  window.addEventListener('resize', () => {
    const nW = canvas.offsetWidth;
    const nH = canvas.offsetHeight;
    if (!nW || !nH) return;
    renderer.setSize(nW, nH);
    camera.aspect = nW / nH;
    camera.updateProjectionMatrix();
  });

  // ─── Animation loop ───────────────────────────────────────
  (function animate(t) {
    requestAnimationFrame(animate);
    const elapsed = t * 0.001;

    // Camera subtle movement based on mouse
    camera.position.x += (mouseX * 0.5 - camera.position.x) * 0.03;
    camera.position.y += (-mouseY * 0.3 - camera.position.y) * 0.03;
    camera.lookAt(scene.position);

    // Service-specific animation
    if (animateFn) animateFn(elapsed);

    // Animate lights subtly
    keyLight.position.x = 3 + Math.sin(elapsed * 0.5) * 0.5;
    keyLight.position.y = 2 + Math.cos(elapsed * 0.3) * 0.3;

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
   NEWSLETTER FORM (AJAX submission)
   ═══════════════════════════════════════════════════════════ */
(function initNewsletter() {
  const form = document.getElementById('ve-newsletter-form');
  if (!form || typeof VE === 'undefined') return;

  const emailInput = form.querySelector('input[type="email"]');
  const submitBtn  = form.querySelector('button[type="submit"]');
  let isSubmitting = false;

  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (isSubmitting || !emailInput || !emailInput.value.trim()) return;

    const email = emailInput.value.trim();
    isSubmitting = true;

    // Update button state
    const originalBtnHTML = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
      <svg class="ve-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;animation:spin 1s linear infinite;">
        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
        <path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/>
      </svg>
      Subscribing...
    `;

    try {
      const fd = new FormData();
      fd.append('action', 've_newsletter');
      fd.append('nonce', VE.newsletterNonce);
      fd.append('email', email);
      fd.append('source', window.location.pathname);

      const res  = await fetch(VE.ajaxUrl, { method: 'POST', body: fd });
      const json = await res.json();

      if (json.success) {
        form.innerHTML = `
          <div class="ve-form-success" style="display:flex;gap:1rem;align-items:flex-start;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" style="width:32px;height:32px;flex-shrink:0;">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
            <div>
              <strong style="display:block;margin-bottom:.25rem;">You're subscribed!</strong>
              <p style="margin:0;font-size:.875rem;color:var(--ve-text-muted);">
                ${json.data.message || "We'll send our next insights update to " + email + "."}
              </p>
            </div>
          </div>
        `;
      } else {
        // Show error but keep form functional
        showFormError(json.data?.message || 'Something went wrong. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHTML;
        isSubmitting = false;
      }
    } catch (err) {
      showFormError('Network error. Please check your connection and try again.');
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnHTML;
      isSubmitting = false;
    }
  });

  function showFormError(message) {
    // Remove existing error
    const existingError = form.querySelector('.ve-form-error');
    if (existingError) existingError.remove();

    const errorEl = document.createElement('div');
    errorEl.className = 've-form-error';
    errorEl.style.cssText = 'color:#ef4444;font-size:.875rem;margin-top:.75rem;display:flex;align-items:center;gap:.5rem;';
    errorEl.innerHTML = `
      <svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px;flex-shrink:0;">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
      </svg>
      ${message}
    `;
    form.appendChild(errorEl);

    // Auto-remove after 5s
    setTimeout(() => errorEl.remove(), 5000);
  }
})();
