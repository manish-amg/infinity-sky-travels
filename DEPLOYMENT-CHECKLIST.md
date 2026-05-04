# Infinity Sky Travels — Deployment Checklist

> Work through this list top-to-bottom on every deployment. Tick each item before going live.

---

## 1. Server & Hosting

- [ ] PHP 8.1+ confirmed (`php -v` on server)
- [ ] MySQL 8.0+ or MariaDB 10.6+ confirmed
- [ ] Apache 2.4+ with `mod_rewrite`, `mod_headers`, `mod_deflate`, `mod_expires` enabled
- [ ] SSL/TLS certificate installed and auto-renewing (Let's Encrypt or equivalent)
- [ ] HTTPS redirect active (uncomment `.htaccess` HTTPS block)
- [ ] www → non-www (or vice versa) redirect active (uncomment `.htaccess` block)
- [ ] Server timezone set to `Asia/Kathmandu`
- [ ] PHP `memory_limit` ≥ 256M (`upload_max_filesize` 64M, `post_max_size` 64M, `max_execution_time` 300)
- [ ] Daily automated backups configured (database + files, offsite)
- [ ] Server-level firewall: block all ports except 80, 443, and SSH

---

## 2. WordPress Installation

- [ ] WordPress 6.x installed
- [ ] `wp-config.php` configured with correct DB credentials
- [ ] `DUFFEL_API_KEY` defined in `wp-config.php` (live key, not sandbox)
- [ ] `DISALLOW_FILE_EDIT` set to `true` in `wp-config.php`
- [ ] `WP_DEBUG` set to `false` in `wp-config.php`
- [ ] `WP_DEBUG_LOG` set to `false`
- [ ] Auth keys and salts generated fresh from `https://api.wordpress.org/secret-key/1.1/salt/`
- [ ] Table prefix changed from `wp_` to something unique
- [ ] Admin username is NOT `admin` or `administrator`
- [ ] Strong admin password (16+ chars, mixed case, numbers, symbols)

---

## 3. Plugins — Install & Activate

### Required
- [ ] **Advanced Custom Fields Pro** (or ACF Free 6.x) — activate, configure field groups
- [ ] **WooCommerce** — activate, run setup wizard (currency: USD, store: Nepal)
- [ ] **RankMath SEO** (free) — activate

### Custom (this project)
- [ ] **IST Flight Booking** (`ist-flight-booking`) — activate
- [ ] **IST eSewa Gateway** (`ist-esewa-gateway`) — activate, enter merchant ID
- [ ] **IST Khalti Gateway** (`ist-khalti-gateway`) — activate, enter live secret key
- [ ] **IST Security Hardening** (`ist-security-hardening`) — activate
- [ ] **IST Itinerary Builder** (`ist-itinerary-builder`) — activate
- [ ] **IST Sample Content** (`ist-sample-content`) — activate → Install → **deactivate and delete**
- [ ] **IST SEO Setup** (`ist-seo-setup`) — activate → Apply → **deactivate and delete**

### Recommended Third-Party
- [ ] **WP Super Cache** or **W3 Total Cache** — activate and configure
- [ ] **Wordfence Security** — activate, run initial scan
- [ ] **UpdraftPlus** — activate, configure cloud backup (Google Drive / S3)
- [ ] **WP Smush** or **EWWW Image Optimizer** — activate for image compression
- [ ] **Contact Form 7** (optional — custom form already built into theme)

---

## 4. Theme Setup

- [ ] `infinity-sky-theme` uploaded to `wp-content/themes/`
- [ ] Theme activated in **Appearance → Themes**
- [ ] **Customizer** settings:
  - [ ] Site logo uploaded (SVG or PNG, min 300px wide)
  - [ ] Site icon (favicon) uploaded (512×512 PNG)
  - [ ] WhatsApp number set (`ist_whatsapp_number` option)
  - [ ] Primary colour confirmed (#f97316 orange)
- [ ] All CSS / JS assets compiling without errors (check browser console)
- [ ] Jarallax, Swiper, Flatpickr loading on correct pages

---

## 5. ACF Field Groups

- [ ] Import ACF field group JSON from `/acf-json/` folder (or recreate manually)
- [ ] Confirm all 6 tab groups appear on `ist_package` edit screen:
  - [ ] Basic Info (duration, difficulty, altitude, group size, airports)
  - [ ] Pricing (price, currency)
  - [ ] Gallery (repeater of images)
  - [ ] Highlights (repeater)
  - [ ] Itinerary (repeater: day/title/desc/altitude/meals/accommodation)
  - [ ] Inclusions / Exclusions (textarea each)
  - [ ] FAQ (repeater: question/answer)

---

## 6. Content

- [ ] All 8 packages published and visible at `/packages/`
- [ ] All 10 blog posts published and visible at `/blog/`
- [ ] Pages created: Home, About, Contact, Plan My Trip, Booking Confirmed, Privacy Policy, Blog
- [ ] **Menus** configured (Appearance → Menus):
  - [ ] Primary navigation: Home, Packages, About, Blog, Contact, Plan My Trip
  - [ ] Footer navigation: Privacy Policy, Terms, Contact
- [ ] Homepage set to static page (`Settings → Reading`)
- [ ] Blog page set to `/blog/`

---

## 7. WooCommerce Configuration

- [ ] **General**: Store country = Nepal, Currency = USD, Currency position = before
- [ ] **Products**: disabled weight/dimensions (flights don't need them)
- [ ] **Payments**:
  - [ ] eSewa gateway: enabled, Merchant ID entered
  - [ ] Khalti gateway: enabled, Live Secret Key entered
  - [ ] PayPal or Stripe (optional, for international guests)
  - [ ] Cash on delivery: disabled
- [ ] **Emails**: From name = "Infinity Sky Travels", From address = infinityskytravels8@gmail.com
- [ ] Test checkout flow with eSewa sandbox before going live

---

## 8. Duffel API

- [ ] Live API key entered in `wp-config.php`: `define('DUFFEL_API_KEY', 'duffel_live_...');`
- [ ] Test flight search: KTM → PKR, tomorrow's date, 1 adult
- [ ] Confirm offers returned and display correctly in flight results panel
- [ ] Test booking creation with a sandbox order
- [ ] Confirm booking confirmation email arrives (check spam folder)
- [ ] Admin notification email arrives at `admin_email`
- [ ] Booking appears in WP Admin → Flight Bookings → Dashboard

---

## 9. SEO

- [ ] `robots.txt` uploaded to domain root (check: `https://infinityskytravels.com/robots.txt`)
- [ ] `.htaccess` uploaded and active (check: permalinks working)
- [ ] RankMath sitemap generated: `https://infinityskytravels.com/sitemap_index.xml`
- [ ] Sitemap submitted to Google Search Console
- [ ] Sitemap submitted to Bing Webmaster Tools
- [ ] Google Search Console property verified
- [ ] Google Analytics 4 tag installed (via GTM or direct in `functions.php`)
- [ ] JSON-LD TravelAgency schema visible in page source (`<script type="application/ld+json">`)
- [ ] Test schema with Google Rich Results Test: `https://search.google.com/test/rich-results`
- [ ] All package pages have: unique title tag, meta description, OG image
- [ ] No `noindex` on live pages that should be indexed
- [ ] Canonical URLs confirmed on all pages

---

## 10. Performance

- [ ] Google PageSpeed score ≥ 85 on mobile (target 90+)
- [ ] Core Web Vitals in green:
  - [ ] LCP (Largest Contentful Paint) < 2.5s
  - [ ] CLS (Cumulative Layout Shift) < 0.1
  - [ ] INP (Interaction to Next Paint) < 200ms
- [ ] Hero images: WebP format, preloaded with `<link rel="preload">`
- [ ] All images have `loading="lazy"` except above-the-fold
- [ ] CSS is minified and combined (via caching plugin)
- [ ] JS is deferred or async where possible
- [ ] Server response time < 200ms (check with GTmetrix or WebPageTest)
- [ ] CloudFlare CDN active (free tier sufficient for images and static assets)

---

## 11. Security

- [ ] `ist-security-hardening` plugin active
- [ ] Wordfence firewall enabled and in "learning mode" for first week
- [ ] Wordfence scan: zero high-severity issues
- [ ] `wp-login.php` rate limiting confirmed (5 failed attempts → 15-minute lockout)
- [ ] XML-RPC disabled (test: `curl -d "" https://infinityskytravels.com/xmlrpc.php` → should fail)
- [ ] User enumeration blocked: `https://infinityskytravels.com/?author=1` → no username revealed
- [ ] File editing disabled (Appearance → Editor should be absent from menu)
- [ ] SSL Labs score A or A+: `https://www.ssllabs.com/ssltest/`
- [ ] Security headers score A: `https://securityheaders.com/`
- [ ] All admin users have 2FA enabled (via Wordfence 2FA or WP 2FA plugin)

---

## 12. Email Delivery

- [ ] SMTP configured (Postmark, SendGrid, or Mailgun — not PHP mail)
  - Recommended: **Postmark** (best delivery rate for transactional email)
  - Plugin: **WP Mail SMTP** configured with SMTP credentials
- [ ] Send test booking confirmation email — arrives in inbox (not spam)
- [ ] Send test admin notification — arrives in inbox
- [ ] DKIM and SPF records set on domain DNS
- [ ] Email "From" address matches authenticated domain

---

## 13. Responsive / Accessibility Audit

- [ ] Tested on real iPhone (Safari) — all pages
- [ ] Tested on Android Chrome — all pages
- [ ] Tested on iPad — all pages
- [ ] Navigation hamburger menu opens and closes correctly
- [ ] Flight search form works on mobile (date picker, dropdowns)
- [ ] Multi-step Plan My Trip form works on mobile
- [ ] All images have `alt` text
- [ ] All interactive elements keyboard-accessible (tab order logical)
- [ ] Colour contrast ratio ≥ 4.5:1 on all body text
- [ ] WCAG 2.1 AA audit with `axe` browser extension — zero critical errors

---

## 14. Legal & Compliance

- [ ] Privacy Policy page published and linked in footer
- [ ] Cookie consent banner active (recommend **Cookie Notice & Compliance** plugin)
- [ ] GDPR consent checkbox on all data-collection forms (Plan My Trip, Contact, Booking)
- [ ] Terms and Conditions page published (if accepting online payments)
- [ ] Company registration number and registered address in footer
- [ ] Nepal Tourism Board licence number displayed

---

## 15. Final Pre-Launch Tests

- [ ] Complete a test booking end-to-end (search → select → fill form → "pay" → confirmation page)
- [ ] Complete a test Plan My Trip submission → admin email arrives
- [ ] Complete a test Contact form → admin email arrives
- [ ] 404 page displays correctly (test: `https://infinityskytravels.com/this-does-not-exist`)
- [ ] All internal links working (use **Broken Link Checker** plugin)
- [ ] Google Analytics recording live sessions (check Real-Time view)
- [ ] Backup taken immediately before going live
- [ ] DNS TTL lowered to 300s (5 minutes) 24 hours before DNS cutover

---

## 16. Post-Launch (First Week)

- [ ] Monitor Wordfence for any security alerts
- [ ] Monitor Google Search Console for crawl errors
- [ ] Check server error logs daily for PHP warnings/errors
- [ ] Verify first real booking completes and triggers correct emails
- [ ] Verify Duffel balance is sufficient for anticipated order volume
- [ ] Submit any remaining pages to Google for indexing via GSC
- [ ] Share launch on social media (Instagram, Facebook)
- [ ] Request Google Business Profile review from first customers

---

*Last updated: May 2025 | Infinity Sky Travels Development Team*
