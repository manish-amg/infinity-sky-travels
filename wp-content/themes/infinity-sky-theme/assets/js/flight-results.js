/**
 * Infinity Sky Travels — flight-results.js
 * Renders Duffel API results, handles client-side filters/sort,
 * "load more", and triggers the booking modal.
 * Depends on: flight-search.js (istSearchFlights, istShowFallbackModal)
 */
(function () {
  'use strict';

  var params    = window.istFlightParams || {};
  var container = document.getElementById('ist-results-container');
  var countEl   = document.getElementById('ist-results-count');
  var loadWrap  = document.getElementById('ist-load-more-wrap');
  var loadBtn   = document.getElementById('ist-load-more');
  var sortSel   = document.getElementById('ist-sort-results');
  var priceRange= document.getElementById('ist-price-range');
  var priceLabel= document.getElementById('ist-price-label');
  var resetBtn  = document.getElementById('ist-reset-filters');
  var manualBtn = document.getElementById('ist-open-manual-quote');

  if (!container) return;

  // All raw offers from API (or demo data)
  var allOffers = [];
  var PAGE_SIZE  = 10;
  var shownCount = 0;

  // ── Auto-search on page load if params are present ───────────
  if (params.autoSearch) {
    runSearch();
  }

  // Re-run search when homepage form dispatches the event
  window.addEventListener('ist:flight-search', function (e) {
    Object.assign(params, e.detail);
    runSearch();
  });

  function runSearch() {
    showLoading();
    window.istSearchFlights(
      {
        from:        params.from,
        to:          params.to,
        date:        params.date,
        return_date: params.return_date || '',
        adults:      params.adults    || 1,
        children:    params.children  || 0,
        infants:     params.infants   || 0,
        trip_type:   params.trip_type || 'oneway',
      },
      onSearchSuccess,
      onSearchError
    );
  }

  function onSearchSuccess(data) {
    // Duffel returns { offers: [...] }
    var offers = Array.isArray(data) ? data : (data.offers || []);
    if (!offers.length) {
      showEmpty('No flights found for this route and date. Try different dates or request a manual quote.');
      return;
    }
    allOffers  = normaliseOffers(offers);
    shownCount = 0;
    renderResults();
  }

  function onSearchError(data, showFallback) {
    var msg = (data && data.message) || 'Could not connect to flight search.';
    if (showFallback) {
      showEmpty(msg, true);
    } else {
      showEmpty(msg);
    }
  }

  // ── Normalise Duffel offer objects ────────────────────────────
  function normaliseOffers(raw) {
    return raw.map(function (offer) {
      var slice      = offer.slices && offer.slices[0];
      var segment    = slice && slice.segments && slice.segments[0];
      var dep        = segment && segment.departing_at;
      var arr        = segment && segment.arriving_at;
      var airline    = segment && segment.operating_carrier;
      var price      = parseFloat(offer.total_amount || offer.base_amount || 0);
      var currency   = offer.total_currency || 'USD';
      var baggage    = segment && segment.passengers && segment.passengers[0]
                        && segment.passengers[0].baggages;

      return {
        id:          offer.id,
        airline_code:airline ? airline.iata_code : '??',
        airline_name:airline ? airline.name       : 'Unknown Airline',
        airline_logo:airline && airline.logo_symbol_url ? airline.logo_symbol_url : '',
        dep_time:    dep ? dep.slice(11, 16) : '--:--',
        arr_time:    arr ? arr.slice(11, 16) : '--:--',
        dep_code:    slice ? slice.origin.iata_code      : params.from,
        arr_code:    slice ? slice.destination.iata_code : params.to,
        dep_city:    slice ? slice.origin.city_name      : '',
        arr_city:    slice ? slice.destination.city_name : '',
        duration:    slice ? formatDuration(slice.duration) : '--',
        stops:       (slice && slice.segments) ? slice.segments.length - 1 : 0,
        price:       price,
        currency:    currency,
        cabin:       (offer.cabin_class || 'Economy').replace(/^\w/, function(c){ return c.toUpperCase(); }),
        baggage_kg:  baggage && baggage[0] ? (baggage[0].quantity + ' × ' + (baggage[0].type || 'bag')) : '1 carry-on',
        expires_at:  offer.expires_at || '',
      };
    });
  }

  function formatDuration(iso) {
    if (!iso) return '--';
    var m = iso.match(/PT(?:(\d+)H)?(?:(\d+)M)?/);
    if (!m) return iso;
    var h = m[1] ? m[1] + 'h ' : '';
    var min = m[2] ? m[2] + 'm' : '';
    return h + min;
  }

  // ── Demo data — used when Duffel key not yet configured ──────
  function getDemoOffers() {
    var from = params.from || 'KTM';
    var to   = params.to   || 'LUA';
    var dateStr = params.date || new Date().toISOString().slice(0, 10);
    return [
      { id:'demo-1', airline_code:'U4', airline_name:'Buddha Air',    airline_logo:'', dep_time:'06:00', arr_time:'06:35', dep_code:from, arr_code:to, dep_city:'Kathmandu', arr_city:'Lukla', duration:'35m', stops:0, price:89,  currency:'USD', cabin:'Economy', baggage_kg:'10 kg', expires_at:'' },
      { id:'demo-2', airline_code:'TA', airline_name:'Tara Air',      airline_logo:'', dep_time:'07:15', arr_time:'07:50', dep_code:from, arr_code:to, dep_city:'Kathmandu', arr_city:'Lukla', duration:'35m', stops:0, price:95,  currency:'USD', cabin:'Economy', baggage_kg:'10 kg', expires_at:'' },
      { id:'demo-3', airline_code:'YT', airline_name:'Yeti Airlines', airline_logo:'', dep_time:'08:30', arr_time:'09:05', dep_code:from, arr_code:to, dep_city:'Kathmandu', arr_city:'Lukla', duration:'35m', stops:0, price:92,  currency:'USD', cabin:'Economy', baggage_kg:'10 kg', expires_at:'' },
      { id:'demo-4', airline_code:'S7', airline_name:'Summit Air',    airline_logo:'', dep_time:'09:45', arr_time:'10:20', dep_code:from, arr_code:to, dep_city:'Kathmandu', arr_city:'Lukla', duration:'35m', stops:0, price:85,  currency:'USD', cabin:'Economy', baggage_kg:'10 kg', expires_at:'' },
      { id:'demo-5', airline_code:'U4', airline_name:'Buddha Air',    airline_logo:'', dep_time:'11:00', arr_time:'11:35', dep_code:from, arr_code:to, dep_city:'Kathmandu', arr_city:'Lukla', duration:'35m', stops:0, price:89,  currency:'USD', cabin:'Economy', baggage_kg:'10 kg', expires_at:'' },
      { id:'demo-6', airline_code:'TA', airline_name:'Tara Air',      airline_logo:'', dep_time:'13:30', arr_time:'14:05', dep_code:from, arr_code:to, dep_city:'Kathmandu', arr_city:'Lukla', duration:'35m', stops:0, price:102, currency:'USD', cabin:'Economy', baggage_kg:'10 kg', expires_at:'' },
    ];
  }

  // ── Render ────────────────────────────────────────────────────
  function renderResults() {
    var filtered = applyFilters(allOffers);
    var sorted   = applySort(filtered);
    var toShow   = sorted.slice(0, shownCount + PAGE_SIZE);
    shownCount   = toShow.length;

    if (countEl) {
      countEl.innerHTML = '<span>' + filtered.length + '</span> flight' + (filtered.length !== 1 ? 's' : '') + ' found';
    }

    if (!filtered.length) {
      showEmpty('No flights match your current filters. Try resetting them.');
      return;
    }

    var html = toShow.map(renderCard).join('');
    container.innerHTML = html;

    // Load more button
    if (loadWrap) loadWrap.style.display = shownCount < sorted.length ? 'block' : 'none';

    // Attach card event listeners
    attachCardListeners();
  }

  function renderCard(offer) {
    var logoHtml = offer.airline_logo
      ? '<img src="' + offer.airline_logo + '" alt="' + escHtml(offer.airline_name) + ' logo" class="ist-flight-card__airline-logo" loading="lazy">'
      : '<div class="ist-flight-card__airline-logo" style="background:var(--ist-light);display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-weight:800;font-size:14px;color:var(--ist-orange);">' + escHtml(offer.airline_code) + '</div>';

    var stopsLabel = offer.stops === 0 ? '<span class="ist-flight-card__direct">Direct</span>' : offer.stops + ' Stop' + (offer.stops > 1 ? 's' : '');
    var isEBC = offer.arr_code === 'LUA' || offer.dep_code === 'LUA';

    return [
      '<div class="ist-flight-card" data-offer-id="' + escHtml(offer.id) + '" data-price="' + offer.price + '" data-airline="' + escHtml(offer.airline_code) + '" data-dep-time="' + escHtml(offer.dep_time) + '">',
        '<div class="ist-flight-card__main">',

          // Airline
          '<div class="ist-flight-card__airline">',
            logoHtml,
            '<span class="ist-flight-card__airline-name">' + escHtml(offer.airline_name) + '</span>',
          '</div>',

          // Route
          '<div class="ist-flight-card__route">',
            '<div class="ist-flight-card__time">',
              '<div class="ist-flight-card__time-value">' + escHtml(offer.dep_time) + '</div>',
              '<div class="ist-flight-card__time-code">' + escHtml(offer.dep_code) + '</div>',
              '<div class="ist-flight-card__time-city">' + escHtml(offer.dep_city) + '</div>',
            '</div>',
            '<div class="ist-flight-card__arrow">',
              '<div class="ist-flight-card__duration-line">',
                '<div class="ist-flight-card__line"></div>',
                '<span class="ist-flight-card__duration-label">' + escHtml(offer.duration) + '</span>',
                '<div class="ist-flight-card__line" style="transform:scaleX(-1);"></div>',
              '</div>',
              stopsLabel,
              '<div class="ist-flight-card__class">' + escHtml(offer.cabin) + '</div>',
            '</div>',
            '<div class="ist-flight-card__time">',
              '<div class="ist-flight-card__time-value">' + escHtml(offer.arr_time) + '</div>',
              '<div class="ist-flight-card__time-code">' + escHtml(offer.arr_code) + '</div>',
              '<div class="ist-flight-card__time-city">' + escHtml(offer.arr_city) + '</div>',
            '</div>',
          '</div>',

          // Price
          '<div class="ist-flight-card__price-box">',
            '<div class="ist-flight-card__price">$' + offer.price.toFixed(0) + '</div>',
            '<div class="ist-flight-card__price-per">per person · ' + escHtml(offer.currency) + '</div>',
            isEBC ? '<div style="font-size:11px;color:var(--ist-orange);font-weight:700;margin-top:4px;">⛰ EBC Route</div>' : '',
          '</div>',

          // Actions
          '<div class="ist-flight-card__actions">',
            '<button type="button" class="btn-primary btn-sm ist-flight-card__book" data-offer-id="' + escHtml(offer.id) + '">',
              'Book Now',
            '</button>',
            '<button type="button" class="ist-flight-card__details-btn" data-offer-id="' + escHtml(offer.id) + '">',
              'Details ▾',
            '</button>',
          '</div>',

        '</div>',

        // Expandable details panel
        '<div class="ist-flight-card__details" id="details-' + escHtml(offer.id) + '">',
          '<div class="ist-flight-card__details-inner">',
            '<div>',
              '<div class="ist-flight-detail-item__label">Baggage</div>',
              '<div class="ist-flight-detail-item__value">' + escHtml(offer.baggage_kg) + '</div>',
            '</div>',
            '<div>',
              '<div class="ist-flight-detail-item__label">Cabin Class</div>',
              '<div class="ist-flight-detail-item__value">' + escHtml(offer.cabin) + '</div>',
            '</div>',
            '<div>',
              '<div class="ist-flight-detail-item__label">Cancellation</div>',
              '<div class="ist-flight-detail-item__value" style="color:var(--ist-error);">Non-refundable</div>',
            '</div>',
          '</div>',
        '</div>',

      '</div>',
    ].join('');
  }

  function attachCardListeners() {
    // Book Now buttons
    container.querySelectorAll('.ist-flight-card__book').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var offerId = this.dataset.offerId;
        var offer   = allOffers.find(function (o) { return o.id === offerId; });
        if (offer && window.istOpenBookingModal) {
          window.istOpenBookingModal(offer, params);
        }
      });
    });

    // Details toggle
    container.querySelectorAll('.ist-flight-card__details-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var offerId = this.dataset.offerId;
        var panel   = document.getElementById('details-' + offerId);
        if (!panel) return;
        var isOpen  = panel.classList.toggle('open');
        this.textContent = isOpen ? 'Details ▴' : 'Details ▾';
      });
    });
  }

  // ── Filter logic ──────────────────────────────────────────────
  function applyFilters(offers) {
    var selectedAirlines = Array.from(document.querySelectorAll('.ist-filter-airline:checked')).map(function (el) { return el.value; });
    var selectedTimes    = Array.from(document.querySelectorAll('.ist-filter-time:checked')).map(function (el) { return el.value; });
    var selectedStops    = Array.from(document.querySelectorAll('.ist-filter-stops:checked')).map(function (el) { return el.value; });
    var maxPrice         = priceRange ? parseInt(priceRange.value) : 9999;

    return offers.filter(function (o) {
      // Airline
      if (selectedAirlines.length && !selectedAirlines.includes(o.airline_code)) return false;
      // Price
      if (o.price > maxPrice) return false;
      // Time
      if (selectedTimes.length) {
        var hour = parseInt(o.dep_time.split(':')[0]);
        var period = hour < 12 ? 'morning' : hour < 18 ? 'afternoon' : 'evening';
        if (!selectedTimes.includes(period)) return false;
      }
      // Stops
      if (selectedStops.length) {
        var stopKey = o.stops === 0 ? 'direct' : '1stop';
        if (!selectedStops.includes(stopKey)) return false;
      }
      return true;
    });
  }

  function applySort(offers) {
    var val = sortSel ? sortSel.value : 'price_asc';
    return offers.slice().sort(function (a, b) {
      if (val === 'price_asc')  return a.price - b.price;
      if (val === 'price_desc') return b.price - a.price;
      if (val === 'time_asc')   return a.dep_time.localeCompare(b.dep_time);
      if (val === 'duration')   return (a.duration || '').localeCompare(b.duration || '');
      return 0;
    });
  }

  // ── UI helpers ────────────────────────────────────────────────
  function showLoading() {
    container.innerHTML = '<div class="ist-flights-loading"><div class="ist-spinner"></div><p>Searching all airlines for the best rates…</p></div>';
    if (loadWrap) loadWrap.style.display = 'none';
    if (countEl) countEl.textContent = 'Searching…';
  }

  function showEmpty(msg, withFallback) {
    // If API key not set, show demo data
    if (withFallback) {
      allOffers = getDemoOffers();
      shownCount = 0;
      renderResults();
      if (window.istToast) window.istToast('Showing demo results. Connect your Duffel API key for live pricing.', 'info');
      return;
    }
    container.innerHTML = [
      '<div class="ist-flights-empty">',
        '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="1.5" style="margin-bottom:16px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        '<h3>' + escHtml(msg) + '</h3>',
        '<p>Try different dates, or let us find the flight for you.</p>',
        '<div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:16px;">',
          '<button type="button" onclick="window.istShowFallbackModal && window.istShowFallbackModal(window.istFlightParams)" class="btn-primary">Request Manual Quote</button>',
          '<a href="https://wa.me/9779810597893" class="btn-outline--dark btn-outline" target="_blank" rel="noopener noreferrer">WhatsApp Us</a>',
        '</div>',
      '</div>',
    ].join('');
    if (loadWrap) loadWrap.style.display = 'none';
    if (countEl) countEl.textContent = '0 flights found';
  }

  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // ── Event listeners: filters, sort, load more, manual quote ──
  if (sortSel)   sortSel.addEventListener('change', function () { shownCount = 0; renderResults(); });
  if (priceRange) {
    priceRange.addEventListener('input', function () {
      if (priceLabel) priceLabel.innerHTML = '<strong>$' + this.value + '</strong>';
      shownCount = 0;
      renderResults();
    });
  }
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      document.querySelectorAll('.ist-filter-airline, .ist-filter-time, .ist-filter-stops').forEach(function (cb) { cb.checked = true; });
      if (priceRange) { priceRange.value = 500; if (priceLabel) priceLabel.innerHTML = '<strong>$500</strong>'; }
      shownCount = 0;
      renderResults();
    });
  }
  document.querySelectorAll('.ist-filter-airline, .ist-filter-time, .ist-filter-stops').forEach(function (cb) {
    cb.addEventListener('change', function () { shownCount = 0; renderResults(); });
  });
  if (loadBtn) {
    loadBtn.addEventListener('click', function () { renderResults(); });
  }
  if (manualBtn) {
    manualBtn.addEventListener('click', function () {
      window.istShowFallbackModal && window.istShowFallbackModal(params);
    });
  }

})();
