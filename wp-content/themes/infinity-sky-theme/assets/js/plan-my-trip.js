/**
 * Infinity Sky Travels — Plan My Trip: multi-step form logic.
 * Handles step navigation, validation, steppers, and AJAX submit.
 */

(function () {
  'use strict';

  const form     = document.getElementById('ist-plan-form');
  const success  = document.getElementById('ist-plan-success');
  const stepDots = document.querySelectorAll('.ist-plan-step');
  if (!form) return;

  let currentStep = 1;
  const TOTAL_STEPS = 4;

  // ── Step navigation ────────────────────────────────────────────
  function showStep(n) {
    document.querySelectorAll('.ist-plan-fieldset').forEach(fs => {
      fs.classList.toggle('ist-plan-fieldset--active', parseInt(fs.dataset.step, 10) === n);
    });
    stepDots.forEach(dot => {
      const s = parseInt(dot.dataset.step, 10);
      dot.classList.toggle('ist-plan-step--active',   s === n);
      dot.classList.toggle('ist-plan-step--complete',  s < n);
    });
    const progressBar = document.querySelector('.ist-plan-steps');
    if (progressBar) progressBar.setAttribute('aria-valuenow', n);

    // Scroll to top of form wrapper
    const wrap = document.getElementById('ist-plan-form-wrap');
    if (wrap) {
      const navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 70;
      const top  = wrap.getBoundingClientRect().top + window.scrollY - navH - 20;
      window.scrollTo({ top, behavior: 'smooth' });
    }
    currentStep = n;
  }

  // ── Validation rules per step ──────────────────────────────────
  function validateStep(n) {
    let valid = true;

    function setError(id, msg) {
      const el = document.getElementById('error-' + id);
      if (el) { el.textContent = msg; el.style.display = msg ? 'block' : 'none'; }
      valid = false;
    }
    function clearError(id) {
      const el = document.getElementById('error-' + id);
      if (el) { el.textContent = ''; el.style.display = 'none'; }
    }

    if (n === 1) {
      const region = form.querySelector('input[name="region"]:checked');
      region ? clearError('region') : setError('region', 'Please select a region.');

      const depart = form.querySelector('[name="depart_date"]');
      depart && depart.value ? clearError('depart_date') : setError('depart_date', 'Please pick a departure date.');

      const duration = form.querySelector('input[name="duration"]:checked');
      duration ? clearError('duration') : setError('duration', 'Please select a duration.');
    }

    if (n === 2) {
      const accom = form.querySelector('input[name="accommodation"]:checked');
      accom ? clearError('accommodation') : setError('accommodation', 'Please choose an accommodation style.');

      const budget = form.querySelector('input[name="budget"]:checked');
      budget ? clearError('budget') : setError('budget', 'Please choose a budget range.');
    }

    if (n === 4) {
      const fname = form.querySelector('[name="first_name"]');
      (fname && fname.value.trim()) ? clearError('first_name') : setError('first_name', 'Required.');

      const lname = form.querySelector('[name="last_name"]');
      (lname && lname.value.trim()) ? clearError('last_name') : setError('last_name', 'Required.');

      const email = form.querySelector('[name="email"]');
      const emailOk = email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);
      emailOk ? clearError('email') : setError('email', 'Please enter a valid email address.');

      const consent = form.querySelector('[name="consent"]');
      (consent && consent.checked) ? clearError('consent') : setError('consent', 'You must agree to the privacy policy to continue.');
    }

    return valid;
  }

  // ── Next / Prev buttons ────────────────────────────────────────
  document.querySelectorAll('.ist-plan-next').forEach(btn => {
    btn.addEventListener('click', function () {
      if (!validateStep(currentStep)) return;
      showStep(parseInt(this.dataset.target, 10));
    });
  });

  document.querySelectorAll('.ist-plan-prev').forEach(btn => {
    btn.addEventListener('click', function () {
      showStep(parseInt(this.dataset.target, 10));
    });
  });

  // ── Stepper buttons (pax) ──────────────────────────────────────
  document.querySelectorAll('.ist-plan-stepper__btn').forEach(btn => {
    btn.addEventListener('click', function () {
      const targetId = this.dataset.target;
      const input    = document.getElementById(targetId);
      if (!input) return;
      let val = parseInt(input.value, 10) || 0;
      const min = parseInt(input.min, 10) || 0;
      const max = parseInt(input.max, 10) || 99;
      if (this.dataset.action === 'plus')  val = Math.min(max, val + 1);
      if (this.dataset.action === 'minus') val = Math.max(min, val - 1);
      input.value = val;
    });
  });

  // ── Radio / checkbox card visual selection ─────────────────────
  function bindCardSelects(selector) {
    document.querySelectorAll(selector).forEach(card => {
      const input = card.querySelector('input');
      if (!input) return;
      input.addEventListener('change', () => {
        if (input.type === 'radio') {
          document.querySelectorAll(selector).forEach(c => c.classList.remove('ist-plan-card--selected'));
        }
        card.classList.toggle('ist-plan-card--selected', input.checked);
      });
    });
  }
  bindCardSelects('.ist-plan-region-card');
  bindCardSelects('.ist-plan-accom-card');
  bindCardSelects('.ist-plan-activity-card');
  bindCardSelects('.ist-plan-pill-radio');

  // ── Flatpickr (date field) ─────────────────────────────────────
  const dateInput = form.querySelector('[data-flatpickr]');
  if (dateInput && window.flatpickr) {
    window.flatpickr(dateInput, {
      minDate:    'today',
      dateFormat: 'Y-m-d',
      altInput:   true,
      altFormat:  'D, M j, Y',
    });
  }

  // ── Build summary for email ────────────────────────────────────
  function collectFormData() {
    const fd = new FormData(form);
    fd.append('action', 'ist_plan_trip');
    fd.append('nonce',  form.dataset.nonce || '');
    return fd;
  }

  // ── Submit ─────────────────────────────────────────────────────
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!validateStep(4)) return;

    const submitBtn = document.getElementById('ist-plan-submit');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="ist-spinner ist-spinner--sm"></span> Sending…';
    }

    fetch(form.dataset.ajax || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      body:   collectFormData(),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        form.hidden = true;
        if (success) {
          success.hidden = false;
          success.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        // Push GTM event if available
        if (window.dataLayer) {
          window.dataLayer.push({ event: 'plan_trip_submitted' });
        }
      } else {
        const msg = data.data || 'Something went wrong. Please try again or WhatsApp us directly.';
        if (window.istToast) window.istToast(msg, 'error');
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Send My Trip Request';
        }
      }
    })
    .catch(() => {
      if (window.istToast) window.istToast('Network error. Please check your connection.', 'error');
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Send My Trip Request';
      }
    });
  });

  // ── Init ───────────────────────────────────────────────────────
  showStep(1);

})();
