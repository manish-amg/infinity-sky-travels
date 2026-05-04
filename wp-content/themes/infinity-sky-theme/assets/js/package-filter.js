/**
 * Infinity Sky Travels — Package Filter + Load More
 * Handles client-side AJAX filtering, active chips, sort, and pagination.
 */

(function () {
  'use strict';

  const grid       = document.getElementById('ist-packages-grid');
  const countEl    = document.getElementById('ist-packages-count');
  const chipsEl    = document.getElementById('ist-filter-chips');
  const sortSelect = document.getElementById('ist-packages-sort');
  const loadMoreWrap = document.getElementById('ist-packages-pagination');
  let loadMoreBtn  = document.getElementById('ist-load-more-packages');

  if (!grid) return;

  const selects = document.querySelectorAll('.ist-filter-bar__select[data-filter]');
  const resetBtn = document.getElementById('ist-filter-reset');

  let currentPage    = 1;
  let maxPages       = loadMoreBtn ? parseInt(loadMoreBtn.dataset.max, 10) : 1;
  let isLoading      = false;
  let activeFilters  = {};

  // ── Read current filter values ─────────────────────────────────
  function readFilters() {
    activeFilters = {};
    selects.forEach(sel => {
      if (sel.value) activeFilters[sel.dataset.filter] = sel.value;
    });
  }

  // ── Render active filter chips ─────────────────────────────────
  const chipLabels = {
    duration:   { label: 'Duration',   values: { 'under-7': 'Under 7 days', '7-10': '7–10 days', '11-14': '11–14 days', '15-plus': '15+ days' } },
    difficulty: { label: 'Difficulty', values: { easy: 'Easy', moderate: 'Moderate', challenging: 'Challenging', strenuous: 'Strenuous' } },
    region:     { label: 'Region',     values: { everest: 'Everest', annapurna: 'Annapurna', langtang: 'Langtang', mustang: 'Mustang', 'western-nepal': 'Western Nepal' } },
    budget:     { label: 'Budget',     values: { 'under-800': 'Under $800', '800-1500': '$800–$1,500', '1500-2500': '$1,500–$2,500', '2500-plus': '$2,500+' } },
    type:       { label: 'Traveller',  values: { backpacker: '🎒 Backpacker', 'mid-range': '🧭 Explorer', luxury: '⭐ Luxury' } },
  };

  function renderChips() {
    if (!chipsEl) return;
    chipsEl.innerHTML = '';
    Object.entries(activeFilters).forEach(([key, val]) => {
      const meta  = chipLabels[key] || { label: key, values: {} };
      const label = meta.values[val] || val;
      const chip  = document.createElement('button');
      chip.type = 'button';
      chip.className = 'ist-filter-chip';
      chip.innerHTML = `<span>${meta.label}: ${label}</span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
      chip.setAttribute('aria-label', `Remove ${meta.label} filter`);
      chip.addEventListener('click', () => {
        const sel = document.querySelector(`.ist-filter-bar__select[data-filter="${key}"]`);
        if (sel) sel.value = '';
        fetchPackages(true);
      });
      chipsEl.appendChild(chip);
    });
  }

  // ── Build AJAX params ──────────────────────────────────────────
  function buildParams(page) {
    const data = new URLSearchParams({
      action:  'ist_filter_packages',
      nonce:   (window.istConfig && window.istConfig.nonce) || '',
      page:    page,
      sort:    sortSelect ? sortSelect.value : 'default',
    });
    Object.entries(activeFilters).forEach(([k, v]) => data.append(k, v));
    return data;
  }

  // ── Update count label ─────────────────────────────────────────
  function updateCount(count) {
    if (!countEl) return;
    countEl.textContent = count === 0
      ? 'No packages match your filters.'
      : `${count} package${count === 1 ? '' : 's'} found`;
  }

  // ── Fetch packages via AJAX ────────────────────────────────────
  function fetchPackages(reset) {
    if (isLoading) return;
    isLoading = true;

    readFilters();
    renderChips();

    if (reset) {
      currentPage = 1;
      grid.innerHTML = '<div class="ist-package-loading" style="grid-column:1/-1;text-align:center;padding:60px 0;"><div class="ist-spinner"></div></div>';
    }

    const ajaxUrl = (window.istConfig && window.istConfig.ajaxUrl) || '/wp-admin/admin-ajax.php';

    fetch(ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body:    buildParams(currentPage),
    })
    .then(r => r.json())
    .then(data => {
      isLoading = false;

      if (reset) {
        grid.innerHTML = '';
      }

      if (!data.success || !data.data) {
        if (reset) {
          grid.innerHTML = '<div class="ist-no-results" style="grid-column:1/-1;"><p>No packages match your filters. Try resetting.</p></div>';
        }
        updateCount(0);
        hideLoadMore();
        return;
      }

      const { html, total, max_pages } = data.data;
      maxPages = parseInt(max_pages, 10) || 1;
      updateCount(parseInt(total, 10) || 0);

      if (html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        Array.from(tmp.children).forEach(card => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(20px)';
          grid.appendChild(card);
          requestAnimationFrame(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity   = '1';
            card.style.transform = 'translateY(0)';
          });
        });
      }

      if (currentPage >= maxPages) {
        hideLoadMore();
      } else {
        showLoadMore();
      }
    })
    .catch(() => {
      isLoading = false;
      if (reset) {
        grid.innerHTML = '<div class="ist-no-results" style="grid-column:1/-1;"><p>An error occurred. Please try again.</p></div>';
      }
    });
  }

  function hideLoadMore() {
    if (loadMoreWrap) loadMoreWrap.style.display = 'none';
  }
  function showLoadMore() {
    if (loadMoreWrap) loadMoreWrap.style.display = 'block';
    if (!loadMoreBtn) {
      loadMoreBtn = document.createElement('button');
      loadMoreBtn.type = 'button';
      loadMoreBtn.id = 'ist-load-more-packages';
      loadMoreBtn.className = 'btn-outline--dark btn-outline';
      loadMoreBtn.textContent = 'Load More Packages';
      if (loadMoreWrap) loadMoreWrap.appendChild(loadMoreBtn);
      loadMoreBtn.addEventListener('click', loadMore);
    }
  }

  function loadMore() {
    currentPage++;
    fetchPackages(false);
  }

  // ── Event listeners ────────────────────────────────────────────
  selects.forEach(sel => sel.addEventListener('change', () => fetchPackages(true)));

  if (sortSelect) sortSelect.addEventListener('change', () => fetchPackages(true));

  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      selects.forEach(sel => { sel.value = ''; });
      if (sortSelect) sortSelect.value = 'default';
      fetchPackages(true);
    });
  }

  if (loadMoreBtn) loadMoreBtn.addEventListener('click', loadMore);

  // ── Sticky filter bar offset for smooth scroll ─────────────────
  const filterBar = document.getElementById('ist-filter-bar');
  if (filterBar) {
    const stickyTop = () => {
      const navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 70;
      filterBar.style.top = navH + 'px';
    };
    stickyTop();
    window.addEventListener('resize', stickyTop);
  }

  // ── Initial count from pre-rendered HTML ───────────────────────
  const initialCards = grid ? grid.querySelectorAll('.ist-package-card, .ist-card') : [];
  if (initialCards.length) {
    updateCount(initialCards.length);
  }

})();

// ── Single package: tab switching ────────────────────────────────
(function () {
  'use strict';
  const tabBtns   = document.querySelectorAll('.ist-package-tabs .ist-tab-btn');
  const tabPanels = document.querySelectorAll('.ist-tab-panel');
  if (!tabBtns.length) return;

  tabBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      const targetId = 'tab-' + this.dataset.tab;

      tabBtns.forEach(b => {
        b.classList.remove('ist-tab-btn--active');
        b.setAttribute('aria-selected', 'false');
      });
      tabPanels.forEach(p => p.classList.remove('ist-tab-panel--active'));

      this.classList.add('ist-tab-btn--active');
      this.setAttribute('aria-selected', 'true');
      const panel = document.getElementById(targetId);
      if (panel) panel.classList.add('ist-tab-panel--active');

      // Lazy-load flight results when flights tab is clicked
      if (this.dataset.tab === 'flights') {
        loadPackageFlights();
      }
    });
  });

  // ── Itinerary accordion ──────────────────────────────────────
  document.querySelectorAll('.ist-itinerary-trigger').forEach(trigger => {
    trigger.addEventListener('click', function () {
      const item = this.closest('.ist-itinerary-item');
      const body = document.getElementById(this.getAttribute('aria-controls'));
      const open = item.classList.contains('ist-itinerary-item--open');

      if (open) {
        item.classList.remove('ist-itinerary-item--open');
        body.classList.remove('ist-itinerary-body--open');
        this.setAttribute('aria-expanded', 'false');
      } else {
        item.classList.add('ist-itinerary-item--open');
        body.classList.add('ist-itinerary-body--open');
        this.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // ── Lazy-load flight results on flights tab ──────────────────
  let flightsFetched = false;
  function loadPackageFlights() {
    if (flightsFetched) return;
    const pkg = window.istPackageData;
    if (!pkg || !pkg.flightFrom || !pkg.flightTo) return;
    flightsFetched = true;

    const container = document.getElementById('ist-package-flight-results');
    if (!container) return;

    if (window.istSearchFlights) {
      window.istSearchFlights(
        { from: pkg.flightFrom, to: pkg.flightTo, date: '', adults: 1 },
        function (offers) {
          if (!offers || !offers.length) {
            container.innerHTML = '<p style="color:var(--ist-text-light);text-align:center;padding:40px 0;">No live flights found. <a href="/flights?from=' + pkg.flightFrom + '&to=' + pkg.flightTo + '" class="ist-link">Search all flights →</a></p>';
            return;
          }
          container.innerHTML = offers.slice(0, 3).map(offer => {
            return `<div class="ist-flight-card">
              <div class="ist-flight-card__main">
                <div class="ist-flight-card__airline">
                  <span class="ist-flight-card__airline-name">${offer.airline || ''}</span>
                </div>
                <div class="ist-flight-card__route">
                  <div class="ist-flight-card__time">
                    <div class="ist-flight-card__time-value">${offer.departs || '--'}</div>
                    <div class="ist-flight-card__time-code">${offer.from || pkg.flightFrom}</div>
                  </div>
                  <div class="ist-flight-card__arrow">
                    <div class="ist-flight-card__duration-line">
                      <div class="ist-flight-card__line"></div>
                    </div>
                    <span class="ist-flight-card__direct">Direct</span>
                  </div>
                  <div class="ist-flight-card__time">
                    <div class="ist-flight-card__time-value">${offer.arrives || '--'}</div>
                    <div class="ist-flight-card__time-code">${offer.to || pkg.flightTo}</div>
                  </div>
                </div>
                <div class="ist-flight-card__price-box">
                  <div class="ist-flight-card__price">$${offer.price || '--'}</div>
                  <div class="ist-flight-card__price-per">per person</div>
                </div>
              </div>
            </div>`;
          }).join('');
        },
        function () {
          container.innerHTML = '<p style="color:var(--ist-text-light);text-align:center;padding:40px 0;">Could not load flights. <a href="/flights?from=' + pkg.flightFrom + '&to=' + pkg.flightTo + '" class="ist-link">Search all flights →</a></p>';
        }
      );
    } else {
      container.innerHTML = '<p style="text-align:center;padding:40px 0;"><a href="/flights?from=' + (pkg.flightFrom || 'KTM') + '&to=' + (pkg.flightTo || '') + '" class="btn-primary">Search Available Flights →</a></p>';
    }
  }
})();
