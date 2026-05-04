/**
 * Infinity Sky Travels — animations.js
 * Jarallax parallax, Intersection Observer fade-ins, counters, Swiper.
 */
(function () {
  'use strict';

  // ── 1. Jarallax parallax ──────────────────────────────────────
  if (typeof jarallax === 'function') {
    document.querySelectorAll('[data-jarallax]').forEach(function (el) {
      jarallax(el, { speed: 0.6 });
    });
  }

  // ── 2. Scroll-triggered fade-in (Intersection Observer) ──────
  var fadeElements = document.querySelectorAll('[data-fade]');
  if (fadeElements.length && 'IntersectionObserver' in window) {
    var fadeObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('ist-visible');
          fadeObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    fadeElements.forEach(function (el) { fadeObserver.observe(el); });
  } else {
    // Fallback: show all immediately
    fadeElements.forEach(function (el) { el.classList.add('ist-visible'); });
  }

  // ── 3. Animated counters ──────────────────────────────────────
  function animateCounter(el) {
    var target   = parseFloat(el.getAttribute('data-counter'));
    var suffix   = el.dataset.suffix || '';
    var prefix   = el.dataset.prefix || '';
    var duration = 2000;
    var start    = null;
    var from     = parseFloat(el.dataset.from || 0);

    function step(timestamp) {
      if (!start) start = timestamp;
      var progress = Math.min((timestamp - start) / duration, 1);
      // ease-out
      var eased = 1 - Math.pow(1 - progress, 3);
      var current = from + (target - from) * eased;
      var display = Number.isInteger(target) ? Math.round(current) : current.toFixed(1);
      el.textContent = prefix + display + suffix;
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  var counterEls = document.querySelectorAll('[data-counter]');
  if (counterEls.length && 'IntersectionObserver' in window) {
    var counterObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counterEls.forEach(function (el) { counterObserver.observe(el); });
  }

  // ── 4. Swiper — testimonials ──────────────────────────────────
  if (typeof Swiper === 'function' && document.querySelector('.testimonials-swiper')) {
    new Swiper('.testimonials-swiper', {
      slidesPerView: 1,
      spaceBetween: 24,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        640:  { slidesPerView: 1 },
        768:  { slidesPerView: 2, spaceBetween: 20 },
        1024: { slidesPerView: 3, spaceBetween: 24 },
      },
      a11y: {
        prevSlideMessage: 'Previous testimonial',
        nextSlideMessage: 'Next testimonial',
      },
    });
  }

  // ── 5. Swiper — featured packages (mobile scroll) ────────────
  if (typeof Swiper === 'function' && document.querySelector('.packages-swiper')) {
    new Swiper('.packages-swiper', {
      slidesPerView: 1.15,
      spaceBetween: 16,
      grabCursor: true,
      breakpoints: {
        480: { slidesPerView: 1.5 },
        640: { slidesPerView: 2.2 },
        768: { slidesPerView: 2.8 },
        1024: { slidesPerView: 3, spaceBetween: 24 },
      },
    });
  }

  // ── 6. Sticky nav background on scroll ───────────────────────
  var nav = document.getElementById('ist-nav');
  if (nav) {
    function handleNavScroll() {
      if (window.scrollY > 80) {
        nav.classList.add('ist-nav--scrolled');
      } else {
        nav.classList.remove('ist-nav--scrolled');
      }
    }
    window.addEventListener('scroll', handleNavScroll, { passive: true });
    handleNavScroll(); // run once on load
  }

  // ── 7. Accordion (itinerary, FAQ) ────────────────────────────
  document.querySelectorAll('.ist-accordion__header').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = this.closest('.ist-accordion__item');
      var body = item.querySelector('.ist-accordion__body');
      var inner = item.querySelector('.ist-accordion__body-inner');
      var isOpen = item.classList.contains('open');

      // Close siblings
      var parent = item.parentElement;
      parent.querySelectorAll('.ist-accordion__item.open').forEach(function (openItem) {
        openItem.classList.remove('open');
        openItem.querySelector('.ist-accordion__body').style.maxHeight = '0';
        openItem.querySelector('.ist-accordion__header').setAttribute('aria-expanded', 'false');
      });

      if (!isOpen) {
        item.classList.add('open');
        body.style.maxHeight = inner.scrollHeight + 'px';
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // ── 8. Tabs ──────────────────────────────────────────────────
  document.querySelectorAll('.ist-tabs__nav').forEach(function (nav) {
    nav.querySelectorAll('.ist-tab-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var tabId = this.dataset.tab;
        var container = this.closest('.ist-tabs');
        container.querySelectorAll('.ist-tab-btn').forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        container.querySelectorAll('.ist-tab-content').forEach(function (c) { c.classList.remove('active'); });
        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');
        var content = container.querySelector('[data-tab-content="' + tabId + '"]');
        if (content) content.classList.add('active');
      });
    });
  });

  // ── 9. Package card hover overlay (mobile tap) ───────────────
  document.querySelectorAll('.ist-package-card').forEach(function (card) {
    card.addEventListener('click', function (e) {
      if (window.innerWidth <= 768) {
        var link = this.querySelector('a[href]');
        if (link && !e.target.closest('button') && !e.target.closest('a')) {
          window.location.href = link.href;
        }
      }
    });
  });

})();
