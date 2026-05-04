/**
 * Infinity Sky Travels — flight-search.js
 * Duffel API integration: search bar logic, form submit, fallback modal.
 * Depends on: main.js (istConfig, istToast), flatpickr
 */
(function () {
  'use strict';

  var config  = window.istConfig || {};
  var ajaxUrl = config.ajaxUrl || '/wp-admin/admin-ajax.php';
  var nonce   = config.nonce   || '';

  // ── Passenger dropdown toggle ─────────────────────────────────
  var paxBtn     = document.getElementById('ist-passengers-btn');
  var paxDropdown= document.getElementById('ist-passengers-dropdown');
  var paxDone    = document.getElementById('ist-pax-done');
  var paxSummary = document.getElementById('ist-pax-summary');

  if (paxBtn && paxDropdown) {
    paxBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var isOpen = paxDropdown.hidden === false;
      paxDropdown.hidden = isOpen;
      paxBtn.setAttribute('aria-expanded', String(!isOpen));
    });

    document.addEventListener('click', function (e) {
      if (!paxDropdown.contains(e.target) && e.target !== paxBtn) {
        paxDropdown.hidden = true;
        paxBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  if (paxDone) {
    paxDone.addEventListener('click', function () {
      paxDropdown.hidden = true;
      paxBtn && paxBtn.setAttribute('aria-expanded', 'false');
    });
  }

  // Update passenger summary label
  function updatePaxSummary() {
    if (!paxSummary) return;
    var adults   = parseInt(document.querySelector('[name="adults"]')?.value   || 1);
    var children = parseInt(document.querySelector('[name="children"]')?.value || 0);
    var infants  = parseInt(document.querySelector('[name="infants"]')?.value  || 0);
    var total    = adults + children + infants;
    var parts    = [];
    if (adults)   parts.push(adults   + ' Adult'   + (adults   > 1 ? 's' : ''));
    if (children) parts.push(children + ' Child'   + (children > 1 ? 'ren' : ''));
    if (infants)  parts.push(infants  + ' Infant'  + (infants  > 1 ? 's' : ''));
    paxSummary.textContent = parts.join(', ') || '1 Adult';
  }

  document.querySelectorAll('.ist-stepper').forEach(function (stepper) {
    var input    = stepper.querySelector('.ist-stepper__input');
    var minusBtn = stepper.querySelector('[data-step="-1"]');
    var plusBtn  = stepper.querySelector('[data-step="1"]');
    if (!input) return;
    var min = parseInt(input.min) || 0;
    var max = parseInt(input.max) || 9;

    if (minusBtn) minusBtn.addEventListener('click', function () {
      var v = parseInt(input.value) || 0;
      if (v > min) { input.value = v - 1; updatePaxSummary(); }
    });
    if (plusBtn) plusBtn.addEventListener('click', function () {
      var v = parseInt(input.value) || 0;
      if (v < max) { input.value = v + 1; updatePaxSummary(); }
    });
  });

  // ── Trip type toggle ─────────────────────────────────────────
  var tripTypeInput   = document.getElementById('ist-trip-type');
  var returnDateWrap  = document.getElementById('ist-return-date-wrap');

  document.querySelectorAll('.ist-trip-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.ist-trip-toggle').forEach(function (b) { b.classList.remove('active'); });
      this.classList.add('active');
      var trip = this.dataset.trip;
      if (tripTypeInput) tripTypeInput.value = trip;
      if (returnDateWrap) {
        returnDateWrap.classList.toggle('ist-hidden', trip !== 'roundtrip');
      }
    });
  });

  // ── Swap airports ─────────────────────────────────────────────
  var swapBtn = document.getElementById('ist-swap-airports');
  if (swapBtn) {
    swapBtn.addEventListener('click', function () {
      var fromSel = document.getElementById('ist-from');
      var toSel   = document.getElementById('ist-to');
      if (!fromSel || !toSel) return;
      var tmp = fromSel.value;
      fromSel.value = toSel.value;
      toSel.value   = tmp;
    });
  }

  // ── Flatpickr date pickers ────────────────────────────────────
  var departInput = document.getElementById('ist-depart-date');
  var returnInput = document.getElementById('ist-return-date');
  var departFp, returnFp;

  if (typeof flatpickr === 'function') {
    if (departInput) {
      departFp = flatpickr(departInput, {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D, M j, Y',
        onChange: function (dates) {
          if (returnFp && dates[0]) {
            returnFp.set('minDate', dates[0]);
          }
        },
      });
    }
    if (returnInput) {
      returnFp = flatpickr(returnInput, {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D, M j, Y',
      });
    }
  }

  // ── Form submit — AJAX search then redirect ───────────────────
  var flightForm = document.getElementById('ist-flight-form');
  if (!flightForm) return;

  flightForm.addEventListener('submit', function (e) {
    e.preventDefault();

    var from       = document.getElementById('ist-from')?.value;
    var to         = document.getElementById('ist-to')?.value;
    var date       = document.getElementById('ist-depart-date')?.value;
    var returnDate = document.getElementById('ist-return-date')?.value || '';
    var adults     = document.querySelector('[name="adults"]')?.value   || 1;
    var children   = document.querySelector('[name="children"]')?.value || 0;
    var infants    = document.querySelector('[name="infants"]')?.value  || 0;
    var tripType   = tripTypeInput?.value || 'oneway';

    // Basic validation
    if (!from || !to) {
      window.istToast && window.istToast('Please select origin and destination airports.', 'error');
      return;
    }
    if (from === to) {
      window.istToast && window.istToast('Origin and destination cannot be the same.', 'error');
      return;
    }
    if (!date) {
      window.istToast && window.istToast('Please select a departure date.', 'error');
      return;
    }
    if (tripType === 'roundtrip' && !returnDate) {
      window.istToast && window.istToast('Please select a return date for round trips.', 'error');
      return;
    }

    // Build URL params
    var params = new URLSearchParams({
      from:       from,
      to:         to,
      date:       date,
      adults:     adults,
      children:   children,
      infants:    infants,
      trip_type:  tripType,
    });
    if (returnDate) params.set('return_date', returnDate);

    // If we're already on /flights, trigger the search directly
    if (window.location.pathname.includes('/flights')) {
      window.history.pushState({}, '', '?' + params.toString());
      window.dispatchEvent(new CustomEvent('ist:flight-search', { detail: Object.fromEntries(params) }));
      return;
    }

    // Otherwise redirect to /flights page
    window.location.href = (config.siteUrl || '') + '/flights?' + params.toString();
  });

  // ── AJAX flight search function (called by flight-results.js) ─
  window.istSearchFlights = function (params, onSuccess, onError) {
    var formData = new FormData();
    formData.append('action', 'ist_flight_search');
    formData.append('nonce',  nonce);
    Object.entries(params).forEach(function (_ref) {
      formData.append(_ref[0], _ref[1]);
    });

    var submitBtn = document.querySelector('.ist-search-submit');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="ist-spinner" style="width:18px;height:18px;border-width:2px;margin:0;"></span> Searching…';
    }

    fetch(ajaxUrl, { method: 'POST', body: formData })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg> Search Flights';
        }
        if (data.success) {
          onSuccess && onSuccess(data.data);
        } else {
          // API unavailable — show fallback
          var fallback = data.data && data.data.fallback;
          onError && onError(data.data, fallback);
        }
      })
      .catch(function (err) {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Search Flights';
        }
        onError && onError({ message: 'Network error. Please try again.' }, true);
      });
  };

  // ── Manual quote fallback modal ───────────────────────────────
  window.istShowFallbackModal = function (prefill) {
    var existing = document.getElementById('ist-fallback-modal');
    if (existing) existing.remove();

    var airports = prefill || {};
    var overlay = document.createElement('div');
    overlay.id = 'ist-fallback-modal';
    overlay.className = 'ist-modal-overlay';
    overlay.innerHTML = [
      '<div class="ist-modal" role="dialog" aria-modal="true" aria-labelledby="fallback-modal-title">',
        '<button class="ist-modal__close" id="ist-fallback-close" aria-label="Close">✕</button>',
        '<h2 class="ist-modal__title" id="fallback-modal-title">Request a Manual Flight Quote</h2>',
        '<p style="color:var(--ist-text-light);margin-bottom:var(--space-lg);">Our team will check availability and confirm your booking within 2 hours via WhatsApp or email.</p>',
        '<form id="ist-fallback-form">',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="fb-name">Your Full Name *</label>',
            '<input type="text" id="fb-name" name="name" class="ist-input" required>',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="fb-email">Email Address *</label>',
            '<input type="email" id="fb-email" name="email" class="ist-input" required>',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="fb-whatsapp">WhatsApp Number</label>',
            '<input type="tel" id="fb-whatsapp" name="whatsapp" class="ist-input" placeholder="+1 555 000 0000">',
          '</div>',
          '<div class="ist-form-group" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">',
            '<div>',
              '<label class="ist-label" for="fb-from">From *</label>',
              '<input type="text" id="fb-from" name="from" class="ist-input" value="' + (airports.from || 'KTM') + '" required>',
            '</div>',
            '<div>',
              '<label class="ist-label" for="fb-to">To *</label>',
              '<input type="text" id="fb-to" name="to" class="ist-input" value="' + (airports.to || '') + '" required>',
            '</div>',
          '</div>',
          '<div class="ist-form-group" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">',
            '<div>',
              '<label class="ist-label" for="fb-date">Travel Date *</label>',
              '<input type="text" id="fb-date" name="date" class="ist-input" value="' + (airports.date || '') + '" data-flatpickr required>',
            '</div>',
            '<div>',
              '<label class="ist-label" for="fb-passengers">Passengers</label>',
              '<input type="number" id="fb-passengers" name="passengers" class="ist-input" value="' + (airports.adults || 1) + '" min="1" max="20">',
            '</div>',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="fb-notes">Special Requests</label>',
            '<textarea id="fb-notes" name="notes" class="ist-input" rows="3" placeholder="Dietary requirements, luggage, connecting treks…"></textarea>',
          '</div>',
          '<div style="display:flex;gap:12px;flex-wrap:wrap;">',
            '<button type="submit" class="btn-primary">Send Quote Request</button>',
            '<a href="https://wa.me/9779810597893" class="btn-outline--dark btn-outline" target="_blank" rel="noopener noreferrer">WhatsApp Instead</a>',
          '</div>',
          '<p id="ist-fallback-msg" style="margin-top:12px;font-size:14px;"></p>',
        '</form>',
      '</div>',
    ].join('');

    document.body.appendChild(overlay);
    requestAnimationFrame(function () { overlay.classList.add('open'); });

    // Re-init flatpickr on new input
    var dateInput = document.getElementById('fb-date');
    if (dateInput && typeof flatpickr === 'function') flatpickr(dateInput, { minDate: 'today', dateFormat: 'Y-m-d', altInput: true, altFormat: 'D, M j, Y' });

    // Close handlers
    document.getElementById('ist-fallback-close').addEventListener('click', closeFallback);
    overlay.addEventListener('click', function (e) { if (e.target === overlay) closeFallback(); });
    document.addEventListener('keydown', function handler(e) {
      if (e.key === 'Escape') { closeFallback(); document.removeEventListener('keydown', handler); }
    });

    // Form submit
    document.getElementById('ist-fallback-form').addEventListener('submit', function (e) {
      e.preventDefault();
      var fd = new FormData(this);
      fd.append('action', 'ist_manual_quote');
      fd.append('nonce',  nonce);
      var btn = this.querySelector('button[type="submit"]');
      var msg = document.getElementById('ist-fallback-msg');
      btn.disabled = true;
      btn.textContent = 'Sending…';

      fetch(ajaxUrl, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success) {
            msg.style.color = 'var(--ist-success)';
            msg.textContent = data.data.message;
            btn.textContent = 'Sent ✓';
            setTimeout(closeFallback, 3000);
          } else {
            msg.style.color = 'var(--ist-error)';
            msg.textContent = data.data.message || 'Error. Please WhatsApp us.';
            btn.disabled = false;
            btn.textContent = 'Try Again';
          }
        })
        .catch(function () {
          msg.style.color = 'var(--ist-error)';
          msg.textContent = 'Network error. Please WhatsApp us directly.';
          btn.disabled = false;
          btn.textContent = 'Try Again';
        });
    });

    function closeFallback() {
      overlay.classList.remove('open');
      setTimeout(function () { overlay.remove(); }, 300);
    }
  };

})();
