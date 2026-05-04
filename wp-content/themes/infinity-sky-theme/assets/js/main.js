/**
 * Infinity Sky Travels — main.js
 * Global JS: mobile nav, WhatsApp float, scroll-to-top, toast, utilities.
 */
(function () {
  'use strict';

  // ── Mobile navigation ────────────────────────────────────────
  var hamburger    = document.getElementById('ist-hamburger');
  var mobileNav    = document.getElementById('ist-mobile-nav');
  var mobileOverlay= document.getElementById('ist-mobile-overlay');
  var mobileClose  = document.getElementById('ist-mobile-close');

  function openMobileNav() {
    mobileNav.hidden    = false;
    mobileOverlay.hidden = false;
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    // Trigger transition
    requestAnimationFrame(function () {
      mobileNav.removeAttribute('hidden');
      mobileOverlay.removeAttribute('hidden');
    });
  }

  function closeMobileNav() {
    mobileNav.hidden     = true;
    mobileOverlay.hidden = true;
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', function () {
      var isOpen = hamburger.getAttribute('aria-expanded') === 'true';
      isOpen ? closeMobileNav() : openMobileNav();
    });
  }
  if (mobileClose)   mobileClose.addEventListener('click', closeMobileNav);
  if (mobileOverlay) mobileOverlay.addEventListener('click', closeMobileNav);

  // Mobile accordion sub-menus
  document.querySelectorAll('.ist-mobile-nav__toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var sub      = this.closest('.ist-mobile-nav__item').querySelector('.ist-mobile-nav__sub');
      var isOpen   = sub && sub.classList.contains('open');
      var chevron  = this.querySelector('.ist-mobile-nav__chevron');

      // Close all subs
      document.querySelectorAll('.ist-mobile-nav__sub.open').forEach(function (s) { s.classList.remove('open'); });
      document.querySelectorAll('.ist-mobile-nav__toggle').forEach(function (b) {
        var c = b.querySelector('.ist-mobile-nav__chevron');
        if (c) c.textContent = '+';
        b.setAttribute('aria-expanded', 'false');
      });

      if (!isOpen && sub) {
        sub.classList.add('open');
        if (chevron) chevron.textContent = '−';
        this.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // Close nav on ESC key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMobileNav();
  });

  // ── Scroll-to-top button ─────────────────────────────────────
  var scrollTopBtn = document.querySelector('.ist-scroll-top');
  if (scrollTopBtn) {
    window.addEventListener('scroll', function () {
      scrollTopBtn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    scrollTopBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Smooth anchor links ──────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = this.getAttribute('href').slice(1);
      var target = document.getElementById(id);
      if (target) {
        e.preventDefault();
        var navHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 80;
        var top = target.getBoundingClientRect().top + window.scrollY - navHeight - 16;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  // ── Toast notification system ────────────────────────────────
  window.istToast = function (message, type) {
    var existing = document.querySelector('.ist-toast');
    if (existing) existing.remove();

    var toast = document.createElement('div');
    toast.className = 'ist-toast ist-toast--' + (type || 'info');
    toast.textContent = message;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'polite');
    document.body.appendChild(toast);

    requestAnimationFrame(function () {
      toast.classList.add('show');
      setTimeout(function () {
        toast.classList.remove('show');
        setTimeout(function () { toast.remove(); }, 300);
      }, 4000);
    });
  };

  // ── Lazy load images (native) ────────────────────────────────
  document.querySelectorAll('img[data-src]').forEach(function (img) {
    img.setAttribute('loading', 'lazy');
    img.setAttribute('src', img.dataset.src);
  });

  // ── Flatpickr date pickers (init if available) ───────────────
  if (typeof flatpickr === 'function') {
    document.querySelectorAll('[data-flatpickr]').forEach(function (el) {
      var opts = {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D, M j, Y',
        disableMobile: false,
      };
      if (el.dataset.flatpickrReturn) {
        opts.minDate = el.dataset.flatpickrMin || 'today';
      }
      flatpickr(el, opts);
    });
  }

  // ── Passenger stepper ────────────────────────────────────────
  document.querySelectorAll('.ist-stepper').forEach(function (stepper) {
    var input = stepper.querySelector('.ist-stepper__input');
    var minusBtn = stepper.querySelector('[data-step="-1"]');
    var plusBtn  = stepper.querySelector('[data-step="1"]');
    var min = parseInt(input.min) || 0;
    var max = parseInt(input.max) || 99;

    if (minusBtn) minusBtn.addEventListener('click', function () {
      var val = parseInt(input.value) || 0;
      if (val > min) { input.value = val - 1; input.dispatchEvent(new Event('change')); }
    });
    if (plusBtn) plusBtn.addEventListener('click', function () {
      var val = parseInt(input.value) || 0;
      if (val < max) { input.value = val + 1; input.dispatchEvent(new Event('change')); }
    });
  });

  // ── Active nav link highlighting ─────────────────────────────
  var currentPath = window.location.pathname;
  document.querySelectorAll('.ist-nav__link[href]').forEach(function (link) {
    if (link.getAttribute('href') === currentPath || (currentPath !== '/' && currentPath.startsWith(link.getAttribute('href')))) {
      link.closest('.ist-nav__item').classList.add('ist-nav__item--active');
    }
  });

  // ── Image lightbox (basic click-to-enlarge for galleries) ────
  document.querySelectorAll('.ist-gallery [data-lightbox]').forEach(function (img) {
    img.style.cursor = 'zoom-in';
    img.addEventListener('click', function () {
      var overlay = document.createElement('div');
      overlay.className = 'ist-lightbox';
      overlay.innerHTML = '<div class="ist-lightbox__inner"><img src="' + (this.dataset.full || this.src) + '" alt="' + (this.alt || '') + '"><button class="ist-lightbox__close" aria-label="Close">&times;</button></div>';
      overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;cursor:zoom-out;';
      overlay.querySelector('img').style.cssText = 'max-width:100%;max-height:90vh;border-radius:8px;object-fit:contain;';
      overlay.querySelector('button').style.cssText = 'position:absolute;top:20px;right:20px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;';
      overlay.addEventListener('click', function () { this.remove(); });
      document.body.appendChild(overlay);
    });
  });

  // ── Booking confirmed page: display preferences from URL ──────
  if (window.location.pathname.includes('booking-confirmed')) {
    var params = new URLSearchParams(window.location.search);
    var confirmType = params.get('type');
    var summaryEl = document.getElementById('ist-booking-summary');
    if (summaryEl && confirmType) {
      summaryEl.dataset.type = confirmType;
    }
  }

})();
