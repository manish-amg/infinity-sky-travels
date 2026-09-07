/**
 * Infinity Sky Travels — booking-modal.js
 * Full booking flow: passenger details, add-ons, payment method selection.
 * Depends on: flight-results.js (provides offer data), flight-search.js (config)
 */
(function () {
  'use strict';

  var config  = window.istConfig || {};
  var ajaxUrl = config.ajaxUrl || '/wp-admin/admin-ajax.php';
  var nonce   = config.nonce   || '';

  // Track current offer and totals
  var currentOffer  = null;
  var currentParams = null;
  var addonTotal    = 0;

  // Add-on definitions (prices configurable via WP Admin → Infinity Sky)
  var ADDONS = [
    {
      id:    'airport-pickup',
      label: 'KTM Airport Pickup',
      price: 15,
      desc:  'Private taxi from Tribhuvan Airport to your Thamel hotel.',
      show:  function (offer) { return true; },
    },
    {
      id:    'hotel-night',
      label: 'KTM Hotel (1 night pre-trek)',
      price: 45,
      desc:  '3-star Thamel hotel, breakfast included. Ideal before early morning Lukla flight.',
      show:  function (offer) { return true; },
    },
    {
      id:    'ebc-package',
      label: 'Add EBC Trek Package',
      price: 0,
      desc:  'Start your Everest Base Camp trek directly from Lukla. Click to view the package.',
      show:  function (offer) { return offer && (offer.arr_code === 'LUA' || offer.dep_code === 'LUA'); },
      isLink:true,
      link:  (config.siteUrl || '') + '/packages/everest-base-camp-trek',
    },
    {
      id:    'porter-lukla',
      label: 'Porter from Lukla (+$25/day)',
      price: 25,
      desc:  'Experienced local porter arranged to meet you at Lukla. Price per day.',
      show:  function (offer) { return offer && (offer.arr_code === 'LUA' || offer.dep_code === 'LUA'); },
    },
  ];

  // ── Public: open the booking modal ───────────────────────────
  window.istOpenBookingModal = function (offer, searchParams) {
    currentOffer  = offer;
    currentParams = searchParams || {};
    addonTotal    = 0;
    renderModal(offer, searchParams);
  };

  function renderModal(offer, sp) {
    var existing = document.getElementById('ist-booking-modal-overlay');
    if (existing) existing.remove();

    var adults   = parseInt(sp.adults   || 1);
    var children = parseInt(sp.children || 0);
    var infants  = parseInt(sp.infants  || 0);
    var totalPax = adults + children + infants;
    var baseTotal= offer.price * totalPax;
    var isEBC    = offer.arr_code === 'LUA' || offer.dep_code === 'LUA';

    // Build passenger forms
    var passengerForms = '';
    for (var p = 0; p < totalPax; p++) {
      var type = p < adults ? 'Adult' : (p < adults + children ? 'Child' : 'Infant');
      passengerForms += passengerForm(p + 1, type);
    }

    // Build add-on list
    var addonsHtml = '<div class="ist-addons-checklist">';
    ADDONS.forEach(function (addon) {
      if (!addon.show(offer)) return;
      if (addon.isLink) {
        addonsHtml += [
          '<div class="ist-addon-check">',
            '<div class="ist-addon-check__info">',
              '<div class="ist-addon-check__label">' + escHtml(addon.label) + '</div>',
              '<div class="ist-addon-check__desc">' + escHtml(addon.desc) + '</div>',
              '<a href="' + escHtml(addon.link) + '" class="btn-primary btn-sm" style="margin-top:8px;display:inline-flex;" target="_blank" rel="noopener noreferrer">View Package →</a>',
            '</div>',
          '</div>',
        ].join('');
      } else {
        addonsHtml += [
          '<label class="ist-addon-check">',
            '<input type="checkbox" class="ist-addon-cb" data-price="' + addon.price + '" data-id="' + escHtml(addon.id) + '" style="margin-top:4px;">',
            '<div class="ist-addon-check__info">',
              '<div class="ist-addon-check__label">' + escHtml(addon.label) + ' <span class="ist-addon-check__price">+$' + addon.price + '</span></div>',
              '<div class="ist-addon-check__desc">' + escHtml(addon.desc) + '</div>',
            '</div>',
          '</label>',
        ].join('');
      }
    });
    addonsHtml += '</div>';

    var overlay = document.createElement('div');
    overlay.id  = 'ist-booking-modal-overlay';
    overlay.className = 'ist-modal-overlay';
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    overlay.setAttribute('aria-labelledby', 'booking-modal-title');

    overlay.innerHTML = [
      '<div class="ist-modal" style="max-width:720px;">',

        '<button class="ist-modal__close" id="ist-booking-modal-close" aria-label="Close booking form">✕</button>',

        // Header
        '<div style="background:var(--ist-dark-2);margin:-var(--space-xl);padding:var(--space-lg) var(--space-xl);border-radius:var(--radius-lg) var(--radius-lg) 0 0;margin:-28px -28px 24px;">',
          '<h2 class="ist-modal__title ist-text-white" id="booking-modal-title" style="margin-bottom:8px;">Book Your Flight</h2>',
          '<div style="display:flex;align-items:center;gap:var(--space-md);flex-wrap:wrap;">',
            '<div style="font-family:var(--font-heading);font-size:1.1rem;color:var(--ist-white);font-weight:800;">' + escHtml(offer.dep_code) + ' → ' + escHtml(offer.arr_code) + '</div>',
            '<div style="color:rgba(255,255,255,0.6);font-size:14px;">' + escHtml(offer.airline_name) + ' · ' + escHtml(offer.dep_time) + '–' + escHtml(offer.arr_time) + ' · ' + escHtml(offer.duration) + '</div>',
            '<div style="margin-left:auto;font-family:var(--font-heading);font-weight:900;font-size:1.3rem;color:var(--ist-orange);">$' + offer.price.toFixed(0) + '<span style="font-size:13px;font-weight:500;color:rgba(255,255,255,0.5);">/person</span></div>',
          '</div>',
        '</div>',

        '<form id="ist-booking-form" novalidate>',

          // Step tabs
          '<div class="ist-tabs">',
            '<div class="ist-tabs__nav" role="tablist">',
              '<button type="button" class="ist-tab-btn active" data-tab="passengers" role="tab" aria-selected="true">1. Passengers</button>',
              '<button type="button" class="ist-tab-btn" data-tab="addons"     role="tab" aria-selected="false">2. Add-Ons</button>',
              '<button type="button" class="ist-tab-btn" data-tab="payment"    role="tab" aria-selected="false">3. Payment</button>',
            '</div>',

            // Tab 1: Passengers
            '<div class="ist-tab-content active" data-tab-content="passengers">',
              '<p style="color:var(--ist-text-light);font-size:14px;margin-bottom:var(--space-md);">',
                totalPax + ' passenger' + (totalPax > 1 ? 's' : '') + ' · ' + escHtml(sp.date || '') + (sp.return_date ? ' → ' + escHtml(sp.return_date) : ''),
              '</p>',
              passengerForms,
            '</div>',

            // Tab 2: Add-ons
            '<div class="ist-tab-content" data-tab-content="addons">',
              '<p style="color:var(--ist-text-light);font-size:14px;margin-bottom:var(--space-md);">Enhance your trip with these popular add-ons:</p>',
              addonsHtml,
            '</div>',

            // Tab 3: Payment
            '<div class="ist-tab-content" data-tab-content="payment">',

              // Order summary
              '<div class="ist-order-summary" id="ist-order-summary">',
                '<div class="ist-order-summary__row"><span>Flight (' + totalPax + ' pax × $' + offer.price.toFixed(0) + ')</span><span>$' + baseTotal.toFixed(0) + '</span></div>',
                '<div class="ist-order-summary__row" id="ist-addon-summary-row" style="display:none;"><span>Add-ons</span><span id="ist-addon-summary-amount"></span></div>',
                '<div class="ist-order-summary__row" id="ist-total-row"><span>Total</span><span id="ist-grand-total">$' + baseTotal.toFixed(0) + '</span></div>',
              '</div>',

              '<p class="ist-label" style="margin-bottom:12px;">Select payment method:</p>',
              '<div class="ist-payment-methods">',
                '<label class="ist-payment-method">',
                  '<input type="radio" name="payment_method" value="stripe" checked>',
                  '<svg width="18" height="18" viewBox="0 0 24 24" fill="var(--ist-blue)" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10" stroke="#fff" stroke-width="2"/></svg>',
                  'Card (Stripe) <span style="font-size:11px;color:var(--ist-text-light);">USD</span>',
                '</label>',
                '<label class="ist-payment-method">',
                  '<input type="radio" name="payment_method" value="esewa">',
                  '🟢 eSewa <span style="font-size:11px;color:var(--ist-text-light);">NPR</span>',
                '</label>',
                '<label class="ist-payment-method">',
                  '<input type="radio" name="payment_method" value="khalti">',
                  '🟣 Khalti <span style="font-size:11px;color:var(--ist-text-light);">NPR</span>',
                '</label>',
              '</div>',

              '<div id="ist-payment-note" style="margin-top:12px;padding:10px 14px;background:var(--ist-light);border-radius:var(--radius-md);font-size:13px;color:var(--ist-text-light);">',
                '🔒 Payments are processed securely. For eSewa/Khalti, NPR amount will be calculated at current exchange rate.',
              '</div>',

              '<button type="submit" class="btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:var(--space-lg);" id="ist-booking-submit">',
                'Confirm Booking →',
              '</button>',

              '<p id="ist-booking-error" style="color:var(--ist-error);font-size:14px;margin-top:10px;display:none;"></p>',

            '</div>',

          '</div>',

          // Next / Back buttons
          '<div style="display:flex;justify-content:space-between;margin-top:var(--space-lg);gap:12px;">',
            '<button type="button" id="ist-modal-back" class="btn-outline--dark btn-outline" style="display:none;">← Back</button>',
            '<button type="button" id="ist-modal-next" class="btn-primary" style="margin-left:auto;">Next: Add-Ons →</button>',
          '</div>',

        '</form>',

      '</div>',
    ].join('');

    document.body.appendChild(overlay);
    requestAnimationFrame(function () { overlay.classList.add('open'); });
    document.body.style.overflow = 'hidden';

    // Focus management
    overlay.querySelector('#ist-booking-modal-close').focus();

    // Wire up events
    wireModal(overlay, offer, baseTotal);
  }

  function passengerForm(num, type) {
    var prefix = 'pax_' + num;
    return [
      '<div class="ist-passenger-form" style="border:1px solid var(--ist-border);border-radius:var(--radius-md);padding:var(--space-md);margin-bottom:var(--space-sm);">',
        '<h4 style="font-size:14px;margin-bottom:var(--space-sm);color:var(--ist-text-light);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">',
          'Passenger ' + num + ' <span style="color:var(--ist-orange);">(' + type + ')</span>',
        '</h4>',
        '<div class="ist-booking-modal-fields">',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="' + prefix + '_first">First Name *</label>',
            '<input type="text" id="' + prefix + '_first" name="' + prefix + '_first" class="ist-input" required autocomplete="given-name">',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="' + prefix + '_last">Last Name *</label>',
            '<input type="text" id="' + prefix + '_last" name="' + prefix + '_last" class="ist-input" required autocomplete="family-name">',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="' + prefix + '_passport">Passport Number *</label>',
            '<input type="text" id="' + prefix + '_passport" name="' + prefix + '_passport" class="ist-input" required>',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="' + prefix + '_dob">Date of Birth *</label>',
            '<input type="date" id="' + prefix + '_dob" name="' + prefix + '_dob" class="ist-input" required>',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="' + prefix + '_nationality">Nationality *</label>',
            '<input type="text" id="' + prefix + '_nationality" name="' + prefix + '_nationality" class="ist-input" required placeholder="e.g. American, British">',
          '</div>',
          '<div class="ist-form-group">',
            '<label class="ist-label" for="' + prefix + '_email">Email ' + (num === 1 ? '*' : '') + '</label>',
            '<input type="email" id="' + prefix + '_email" name="' + prefix + '_email" class="ist-input" ' + (num === 1 ? 'required' : '') + ' autocomplete="email">',
          '</div>',
        '</div>',
      '</div>',
    ].join('');
  }

  function wireModal(overlay, offer, baseTotal) {
    var closeBtn   = document.getElementById('ist-booking-modal-close');
    var nextBtn    = document.getElementById('ist-modal-next');
    var backBtn    = document.getElementById('ist-modal-back');
    var form       = document.getElementById('ist-booking-form');
    var tabs       = form.querySelectorAll('.ist-tab-btn');
    var contents   = form.querySelectorAll('.ist-tab-content');
    var tabOrder   = ['passengers', 'addons', 'payment'];
    var activeTab  = 0;

    function goToTab(idx) {
      tabs.forEach(function (t, i) {
        t.classList.toggle('active', i === idx);
        t.setAttribute('aria-selected', i === idx ? 'true' : 'false');
      });
      contents.forEach(function (c, i) { c.classList.toggle('active', i === idx); });
      activeTab = idx;
      backBtn.style.display = idx > 0 ? 'inline-flex' : 'none';
      nextBtn.style.display = idx < tabOrder.length - 1 ? 'inline-flex' : 'none';
      nextBtn.textContent = idx === tabOrder.length - 2 ? 'Next: Payment →' : 'Next: Add-Ons →';
    }

    tabs.forEach(function (btn, i) {
      btn.addEventListener('click', function () { goToTab(i); });
    });
    nextBtn.addEventListener('click', function () {
      if (activeTab === 0 && !validatePassengers(form)) return;
      goToTab(Math.min(activeTab + 1, tabOrder.length - 1));
    });
    backBtn.addEventListener('click', function () { goToTab(Math.max(activeTab - 1, 0)); });

    // Payment method visual toggle
    overlay.querySelectorAll('.ist-payment-method input[type="radio"]').forEach(function (radio) {
      radio.addEventListener('change', function () {
        overlay.querySelectorAll('.ist-payment-method').forEach(function (el) { el.classList.remove('selected'); });
        this.closest('.ist-payment-method').classList.add('selected');
      });
    });
    var firstPayment = overlay.querySelector('.ist-payment-method');
    if (firstPayment) firstPayment.classList.add('selected');

    // Add-on price tracking
    overlay.querySelectorAll('.ist-addon-cb').forEach(function (cb) {
      cb.addEventListener('change', function () {
        addonTotal = 0;
        overlay.querySelectorAll('.ist-addon-cb:checked').forEach(function (c) {
          addonTotal += parseFloat(c.dataset.price || 0);
        });
        var grandTotal = baseTotal + addonTotal;
        var grandEl = document.getElementById('ist-grand-total');
        var addonRow= document.getElementById('ist-addon-summary-row');
        var addonAmt= document.getElementById('ist-addon-summary-amount');
        if (grandEl) grandEl.textContent = '$' + grandTotal.toFixed(0);
        if (addonRow && addonAmt) {
          addonRow.style.display = addonTotal > 0 ? 'flex' : 'none';
          addonAmt.textContent = '+$' + addonTotal.toFixed(0);
        }
      });
    });

    // Close handlers
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', function escHandler(e) {
      if (e.key === 'Escape') { closeModal(); document.removeEventListener('keydown', escHandler); }
    });

    // Form submit
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      submitBooking(form, offer);
    });

    function closeModal() {
      overlay.classList.remove('open');
      document.body.style.overflow = '';
      setTimeout(function () { overlay.remove(); }, 300);
    }
  }

  function validatePassengers(form) {
    var required = form.querySelectorAll('.ist-passenger-form [required]');
    var valid    = true;
    required.forEach(function (input) {
      if (!input.value.trim()) {
        input.style.borderColor = 'var(--ist-error)';
        if (valid) input.focus();
        valid = false;
      } else {
        input.style.borderColor = '';
      }
    });
    if (!valid && window.istToast) window.istToast('Please fill in all required passenger fields.', 'error');
    return valid;
  }

  function submitBooking(form, offer) {
    var btn   = document.getElementById('ist-booking-submit');
    var errEl = document.getElementById('ist-booking-error');
    btn.disabled = true;
    btn.textContent = 'Processing…';

    var formData = new FormData(form);
    formData.append('action', 'ist_create_booking');
    formData.append('nonce',  nonce);
    formData.append('offer_id', offer.id);
    formData.append('base_price', offer.price);
    formData.append('addon_total', addonTotal);
    formData.append('from', offer.dep_code);
    formData.append('to',   offer.arr_code);
    formData.append('date', currentParams.date || '');
    formData.append('adults',   currentParams.adults   || 1);
    formData.append('children', currentParams.children || 0);
    formData.append('infants',  currentParams.infants  || 0);

    // Gather selected add-ons
    var addons = [];
    document.querySelectorAll('.ist-addon-cb:checked').forEach(function (cb) { addons.push(cb.dataset.id); });
    formData.append('addons', JSON.stringify(addons));

    fetch(ajaxUrl, { method: 'POST', body: formData })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          var conf = data.data;
          var method = form.querySelector('[name="payment_method"]:checked')?.value || 'stripe';

          if (method === 'esewa' || method === 'khalti') {
            // Redirect to payment gateway
            window.location.href = conf.payment_url || home_url + '/booking-confirmed?type=flight&ref=' + (conf.booking_ref || '');
          } else {
            // Stripe: redirect to WooCommerce checkout or confirmation
            window.location.href = (config.siteUrl || '') + '/booking-confirmed?type=flight&ref=' + encodeURIComponent(conf.booking_ref || 'DEMO');
          }
        } else {
          var msg = data.data && data.data.message ? data.data.message : 'Booking failed. Please WhatsApp us.';
          if (errEl) { errEl.textContent = msg; errEl.style.display = 'block'; }
          btn.disabled = false;
          btn.textContent = 'Try Again';
        }
      })
      .catch(function () {
        if (errEl) { errEl.textContent = 'Network error. Please WhatsApp us directly.'; errEl.style.display = 'block'; }
        btn.disabled = false;
        btn.textContent = 'Try Again';
      });
  }

  function escHtml(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

})();
