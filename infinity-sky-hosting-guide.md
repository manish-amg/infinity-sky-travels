# INFINITY SKY TRAVELS — HOSTING & GO-LIVE GUIDE
# A2 Hosting + GoDaddy Domain + WordPress
# For: infinityskytravels.com
# Non-technical step-by-step instructions

---

## OVERVIEW OF WHAT YOU HAVE

| Item | Status | Provider |
|---|---|---|
| Domain | ✅ Registered | GoDaddy |
| Hosting | ✅ Active | A2 Hosting (via GoDaddy) |
| cPanel | ✅ Available | A2 Hosting |
| SSL Certificate | Check — likely available free | A2 Hosting |
| WordPress | ⬜ To be installed | You will do this |

Your cPanel URL: https://infinityskytravels.com/cpanel

---

## PART 1: BEFORE ANYTHING — CHECK YOUR A2 HOSTING SPECS

### Step 1: Log into cPanel
1. Go to: https://infinityskytravels.com/cpanel
2. Enter your A2 Hosting username and password
3. You should see the cPanel dashboard

### Step 2: Check PHP Version (IMPORTANT)
1. In cPanel, scroll down to find **"MultiPHP Manager"** or **"Select PHP Version"**
2. Click it
3. Make sure PHP version is set to **8.1 or higher** (8.2 is ideal)
4. If it's 7.x — change it to 8.1 before installing WordPress

### Step 3: Check MySQL (should be automatic)
- A2 Hosting provides MySQL automatically. Nothing to do here.

### Step 4: Check Memory Limit
1. In cPanel → **PHP Settings** or **MultiPHP INI Editor**
2. Find `memory_limit`
3. Set to `256M` if not already
4. Find `max_execution_time` → set to `300`
5. Find `upload_max_filesize` → set to `64M`
6. Find `post_max_size` → set to `64M`
7. Click **Apply**

---

## PART 2: POINT YOUR DOMAIN TO A2 HOSTING

If your domain (GoDaddy) is NOT yet showing your A2 Hosting site, you need to update the nameservers.

### Step 1: Get A2 Hosting Nameservers
1. Log into your A2 Hosting account panel (not cPanel)
2. Go to your hosting account details
3. Find your nameservers — they look like: `ns1.a2hosting.com` and `ns2.a2hosting.com`
   (Common A2 nameservers: ns1.a2hosting.com, ns2.a2hosting.com, ns3.a2hosting.com, ns4.a2hosting.com)

### Step 2: Update at GoDaddy
1. Log into GoDaddy → My Products → Domains
2. Click on `infinityskytravels.com`
3. Scroll to **Nameservers** section
4. Click **Change**
5. Select **"I'll use my own nameservers"**
6. Enter the A2 nameservers from Step 1
7. Save

### Step 3: Wait
- DNS propagation takes 1–48 hours
- You can check propagation at: https://dnschecker.org → enter your domain
- When green checkmarks show globally → you're ready to proceed

---

## PART 3: INSTALL WORDPRESS

### Method: Use A2 Hosting's Softaculous (Easiest — recommended)

1. Log into **cPanel** (https://infinityskytravels.com/cpanel)
2. Scroll down to **"Softaculous Apps Installer"** section
3. Click on **WordPress**
4. Click **"Install Now"**
5. Fill in these settings:

```
Choose Protocol:   https://
Choose Domain:     infinityskytravels.com
In Directory:      (leave BLANK — so it installs at root)
Site Name:         Infinity Sky Travels
Site Description:  Nepal Domestic Flights & Trekking Packages
Admin Username:    ist_admin (choose something not "admin")
Admin Password:    [Create a STRONG password — save it safely!]
Admin Email:       infinityskytravels8@gmail.com
Select Language:   English
```

6. Scroll down → click **"Install"**
7. Wait 2–3 minutes
8. You'll see: "Congratulations, the software was installed successfully"
9. Note down the:
   - WordPress URL: https://infinityskytravels.com
   - Admin URL: https://infinityskytravels.com/wp-admin
   - Username and Password you set

### Test Your Installation
1. Go to: https://infinityskytravels.com
2. You should see a default WordPress site
3. Go to: https://infinityskytravels.com/wp-admin
4. Log in with your credentials
5. You're in! ✅

---

## PART 4: INSTALL SSL CERTIFICATE (HTTPS)

### Check if SSL is already active
1. Visit: https://infinityskytravels.com
2. If you see a green padlock in browser → SSL is active, skip this section

### If NOT active:
1. In cPanel → find **"Let's Encrypt SSL"** or **"SSL/TLS"**
2. Click **"Let's Encrypt SSL"**
3. Find your domain `infinityskytravels.com`
4. Click **"Issue"**
5. Wait 5 minutes → SSL is installed FREE

### Force HTTPS in WordPress
1. In WP Admin → Settings → General
2. Change both URLs from `http://` to `https://`
3. Save Changes

---

## PART 5: UPLOAD THE BUILT THEME FILES

After Claude Code builds all the files, you'll have a folder called `infinity-sky-theme`. Here's how to upload it:

### Method 1: Via cPanel File Manager (Easiest)
1. cPanel → File Manager
2. Navigate to: `public_html/wp-content/themes/`
3. Click **Upload** (top menu)
4. Upload the entire `infinity-sky-theme.zip` file
5. After upload, right-click the zip → **Extract**
6. You should now see `infinity-sky-theme` folder in themes

### Method 2: Via FTP (if files are large)
FTP Credentials from cPanel:
1. cPanel → FTP Accounts → find your main FTP account
2. FTP Host: `ftp.infinityskytravels.com`
3. Username: your cPanel username
4. Password: your cPanel password
5. Port: 21

Use FileZilla (free): https://filezilla-project.org/
- Server: `ftp.infinityskytravels.com`
- Upload to: `/public_html/wp-content/themes/infinity-sky-theme/`

### Method 3: Via GitHub (if Claude Code uses GitHub)
1. Connect your GitHub repo to your server
2. SSH into server via cPanel Terminal
3. `cd /home/[username]/public_html/wp-content/themes/`
4. `git clone https://github.com/[your-repo]/infinity-sky-theme.git`

---

## PART 6: UPLOAD CUSTOM PLUGINS

Upload these plugin folders to: `public_html/wp-content/plugins/`
- `ist-flight-booking/`
- `ist-itinerary-builder/`
- `ist-esewa-gateway/`
- `ist-khalti-gateway/`

Same methods as above (File Manager, FTP, or Git)

---

## PART 7: ACTIVATE THEME AND PLUGINS

1. WP Admin → Appearance → Themes
2. Find **Infinity Sky Theme** → click **Activate**

3. WP Admin → Plugins → Installed Plugins
4. Activate these one by one:
   - WooCommerce ✓
   - WP Travel Engine ✓
   - RankMath SEO ✓
   - Contact Form 7 ✓
   - WP Mail SMTP ✓
   - Smush ✓
   - LiteSpeed Cache ✓
   - Wordfence Security ✓
   - Really Simple SSL ✓
   - Advanced Custom Fields ✓
   - UpdraftPlus ✓
   - IST Flight Booking ✓
   - IST Itinerary Builder ✓
   - IST eSewa Gateway ✓
   - IST Khalti Gateway ✓

---

## PART 8: CONFIGURE wp-config.php

1. cPanel → File Manager → `public_html/wp-config.php`
2. Right-click → Edit
3. Add these lines BEFORE the line `/* That's all, stop editing! */`:

```php
// Performance
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Security
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true);
define('WP_DEBUG', false);

// Revisions
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 300);
```

4. Save the file

---

## PART 9: API KEYS — SIGN UP FOR THESE SERVICES

You need accounts and API keys for these services. Do this before launch:

### 1. Duffel API (Flight Search)
- Sign up: https://duffel.com
- Go to Developers → Access Tokens
- Create a TEST token first (for development)
- Create a LIVE token before launching
- Cost: Pay-per-booking (check current pricing at duffel.com)
- Add key to: WP Admin → Infinity Sky → Flight Settings

### 2. Stripe (International Payments)
- Sign up: https://stripe.com
- Complete business verification (will ask for business documents)
- Go to Developers → API Keys
- Copy your Publishable Key and Secret Key
- Install WooCommerce Stripe plugin from WP Admin
- Add keys to: WP Admin → WooCommerce → Settings → Payments → Stripe

### 3. eSewa Merchant Account
- Apply at: https://esewa.com.np/epay/merchantRegistration.action
- Required: Business registration certificate, PAN/VAT number
- Processing time: 3–7 business days
- You'll receive: Merchant Code
- Add to: WP Admin → WooCommerce → Settings → Payments → eSewa

### 4. Khalti Merchant Account
- Apply at: https://khalti.com/merchant-registration/
- Similar requirements to eSewa
- You'll receive: Public Key + Secret Key
- Add to: WP Admin → WooCommerce → Settings → Payments → Khalti

### 5. Gmail SMTP (Email Sending)
1. Go to: https://myaccount.google.com → Security
2. Enable 2-Factor Authentication
3. Go to "App Passwords" (under 2FA settings)
4. Create app password: Select "Mail" + "Other device" → name it "WordPress"
5. Copy the 16-character password
6. In WP Admin → WP Mail SMTP → Settings:
   - Mailer: Gmail
   - Client ID and Secret: from Google Cloud Console (guide at wp-mail-smtp.com)
   - From Email: infinityskytravels8@gmail.com
   - From Name: Infinity Sky Travels

### 6. Google Search Console
- Sign up: https://search.google.com/search-console
- Add property: https://infinityskytravels.com
- Verify via HTML tag (RankMath can do this automatically)
- Submit sitemap: https://infinityskytravels.com/sitemap.xml

### 7. Google Analytics 4
- Sign up: https://analytics.google.com
- Create property → get Measurement ID (G-XXXXXXXXXX)
- Add to RankMath → Analytics section

### 8. Google Maps API (for Contact page embed)
- Go to: https://console.cloud.google.com
- Enable Maps JavaScript API + Embed Maps API
- Create API key → restrict to your domain
- Add to WordPress: WP Admin → Infinity Sky → Map Settings

---

## PART 10: WORDPRESS SETTINGS CONFIGURATION

After activating everything:

### General Settings (WP Admin → Settings → General)
```
Site Title: Infinity Sky Travels
Tagline: Nepal Domestic Flights & Trekking Packages for Independent Travellers
WordPress Address: https://infinityskytravels.com
Site Address: https://infinityskytravels.com
Admin Email: infinityskytravels8@gmail.com
Timezone: Asia/Kathmandu
Date Format: F j, Y
```

### Permalinks (WP Admin → Settings → Permalinks)
- Select: **Post name** (/%postname%/)
- Click Save Changes

### Reading (WP Admin → Settings → Reading)
- Homepage displays: A static page
- Homepage: Home (select the Home page you created)
- Posts page: Blog (select the Blog page)

### Media (WP Admin → Settings → Media)
- Thumbnail: 150 x 150
- Medium: 600 x 400
- Large: 1200 x 800

### Discussion (WP Admin → Settings → Discussion)
- Enable comments on packages and blog posts
- Require name and email for comments

---

## PART 11: WOOCOMMERCE SETUP

WP Admin → WooCommerce → Setup Wizard → Complete all steps:

```
Store Country: Nepal
Address: Thamel, Kathmandu, Nepal 44600
Currency: USD (primary — travelers pay in USD)
Selling: Online only
Industry: Other
Product types: Downloadable (bookings)
```

**Additional WooCommerce Settings:**
- WP Admin → WooCommerce → Settings → General:
  - Enable taxes: Yes (add NPR tax for Nepali customers)
  - Coupon codes: Yes (for promotional codes)

- WP Admin → WooCommerce → Settings → Emails:
  - From Name: Infinity Sky Travels
  - From Address: infinityskytravels8@gmail.com

---

## PART 12: RANKMATH SEO SETUP

WP Admin → RankMath → Setup Wizard:

1. **Connect Google Services** — link your Google Search Console and Analytics
2. **Sitemap Settings:**
   - Enable XML Sitemap: Yes
   - Include post types: Posts, Pages, ist_package
   - Exclude: Booking confirmations, admin pages
3. **Schema Settings:**
   - Default schema: None (we use custom schema per page type)
4. **Image SEO:**
   - Add alt text automatically: Yes
   - Include title attribute: Yes
5. **Local SEO:**
   - Business Type: Travel Agency
   - Business Name: Infinity Sky Travels
   - Phone: +977 9810597893
   - Address: Thamel, Kathmandu, Nepal 44600

**After setup, verify sitemap works:**
- Visit: https://infinityskytravels.com/sitemap.xml
- Should show all your pages and posts
- Submit this URL to Google Search Console

---

## PART 13: SECURITY SETUP

### Wordfence
1. WP Admin → Wordfence → Dashboard
2. Run initial scan — fix any issues found
3. Firewall → Enable Firewall (set to "Learning Mode" for 1 week then "Enabled")
4. Login Security → Enable 2FA for admin account
5. Alerts: Send security emails to infinityskytravels8@gmail.com

### Change Admin Login URL
1. WP Admin → WPS Hide Login → Settings
2. Change login URL to something unique: e.g. `ist-portal-2024` (choose your own)
3. New login URL: https://infinityskytravels.com/ist-portal-2024
4. **SAVE THIS URL — you can't log in without it**

### UpdraftPlus Backups
1. WP Admin → UpdraftPlus → Settings
2. Backup Schedule: Daily (files) + Daily (database)
3. Retain: 14 copies
4. Remote Storage: Google Drive (connect your Google account)
5. Click "Backup Now" to create first manual backup

---

## PART 14: LITESPEED CACHE SETUP

WP Admin → LiteSpeed Cache → General:

```
Enable LiteSpeed Cache: ON
Cache Logged-in Users: OFF
Cache Commenters: OFF
Cache REST API: ON
Cache Login Page: OFF
```

Page Optimization:
```
Minify HTML: ON
Minify CSS: ON
Minify JS: ON
Combine CSS: ON
Combine JS: ON
Load CSS Asynchronously: ON (test this — may break things)
Lazy Load Images: ON
WebP Images: ON (A2 supports this)
```

---

## PART 15: GO-LIVE CHECKLIST

Run through every item before announcing the site:

### Technical
- [ ] SSL certificate active (green padlock in browser)
- [ ] Both www and non-www redirect to same URL
- [ ] Site loads on mobile (test on actual phone)
- [ ] All images loading (no broken images)
- [ ] All forms submit correctly (test itinerary builder)
- [ ] Emails sending (test contact form → check inbox)
- [ ] WhatsApp button works (test on mobile)
- [ ] Flight search loads (even if Duffel in test mode)
- [ ] Payment pages load (Stripe test mode)
- [ ] Admin can log in at new hidden URL
- [ ] Backup created and stored in Google Drive
- [ ] Site speed: test at https://pagespeed.web.dev — target score 70+

### Content
- [ ] Logo uploaded (the Final-Logo.png you provided)
- [ ] All 8 packages entered with full content
- [ ] All 10 blog posts published or scheduled
- [ ] About page complete
- [ ] Contact page with real address/phone
- [ ] Footer social links working (Instagram, Facebook)
- [ ] Privacy Policy page live
- [ ] Terms & Conditions page live
- [ ] Cancellation Policy page live

### SEO
- [ ] Google Search Console verified
- [ ] Sitemap submitted to Google Search Console
- [ ] Google Analytics connected
- [ ] RankMath configured on all main pages
- [ ] Meta title + description set on homepage
- [ ] Schema markup working (test at https://validator.schema.org)

### Business
- [ ] Stripe LIVE mode activated (not test mode)
- [ ] eSewa merchant code added
- [ ] Khalti keys added
- [ ] Duffel LIVE API key added
- [ ] SMTP email working with real Gmail
- [ ] Booking confirmation emails tested

---

## PART 16: POST-LAUNCH SEO (FIRST 30 DAYS)

### Week 1
- [ ] Submit sitemap to Google Search Console
- [ ] Set up Google Business Profile at https://business.google.com
  - Business name: Infinity Sky Travels
  - Category: Travel Agency
  - Address: Thamel, Kathmandu
  - Phone: +977 9810597893
  - Website: https://infinityskytravels.com
  - Add photos of office, team, treks
- [ ] List on TripAdvisor: https://www.tripadvisor.com/GetListedNew
- [ ] List on Lonely Planet: https://www.lonelyplanet.com/thorntree
- [ ] Create Trustpilot profile

### Week 2
- [ ] Publish first 3 blog posts (Nepal flights guide, EBC guide, KTM-Lukla flight)
- [ ] Share on Instagram + Facebook
- [ ] Start building backlinks: submit to Nepal tourism directories
  - Nepal Tourism Board: https://welcomenepal.com
  - TAAN directory: https://www.taan.org.np
  - Himalayan Database mentions

### Week 3
- [ ] Publish next 3 blog posts
- [ ] Research keywords with Google Search Console (see which terms bring impressions)
- [ ] Optimize pages that get impressions but low click-through rate

### Week 4
- [ ] Publish remaining 4 blog posts
- [ ] Set up monthly reporting:
  - Google Search Console: organic impressions + clicks
  - Google Analytics: sessions, source, conversions
  - WooCommerce: bookings and revenue
- [ ] Start Google Ads (optional) — search ads targeting "Nepal flight booking", "EBC trek package"

---

## PART 17: IMPORTANT CREDENTIALS TO SAVE

Create a secure document (password manager or encrypted notes) with all these:

```
WORDPRESS ADMIN
URL: https://infinityskytravels.com/[your-custom-login-slug]
Username: [your admin username]
Password: [your admin password]

CPANEL
URL: https://infinityskytravels.com/cpanel
Username: [cpanel username]
Password: [cpanel password]

FTP
Host: ftp.infinityskytravels.com
Username: [ftp username]
Password: [ftp password]
Port: 21

DATABASE
DB Name: [from Softaculous installation details]
DB Username: [from Softaculous]
DB Password: [from Softaculous]
DB Host: localhost

DUFFEL API
API Key (Test): ___________________
API Key (Live): ___________________

STRIPE
Publishable Key (Test): ___________________
Secret Key (Test): ___________________
Publishable Key (Live): ___________________
Secret Key (Live): ___________________

ESEWA
Merchant Code: ___________________

KHALTI
Public Key: ___________________
Secret Key: ___________________

GMAIL APP PASSWORD
Email: infinityskytravels8@gmail.com
App Password: ___________________

GOOGLE SEARCH CONSOLE
Account: ___________________

GOOGLE ANALYTICS
Measurement ID: G-___________________
```

---

## PART 18: NEED HELP? — QUICK REFERENCE

| Issue | Solution |
|---|---|
| Site not loading | Check DNS propagation at dnschecker.org |
| Can't log in to WP | Go to cPanel → phpMyAdmin → change password in wp_users table |
| White screen (WSoD) | Enable WP_DEBUG in wp-config.php to see error |
| Emails not sending | Check WP Mail SMTP logs → reconnect Gmail |
| Flights not searching | Check Duffel API key in plugin settings |
| Payment not working | Stripe: check webhook URL is set; confirm live mode enabled |
| Site slow | Clear LiteSpeed cache → re-run pagespeed test |
| SSL error | Renew Let's Encrypt certificate in cPanel |
| Hacked | Restore UpdraftPlus backup → run Wordfence scan |

---

## SUPPORT CONTACTS

- **A2 Hosting Support:** https://www.a2hosting.com/help/ (24/7 live chat)
- **Duffel Developer Support:** https://duffel.com/docs + support@duffel.com
- **Stripe Support:** https://support.stripe.com
- **eSewa Support:** https://esewa.com.np/contact/ | +977-1-4444189
- **Khalti Support:** https://khalti.com/contact/ | support@khalti.com
- **WordPress Support:** https://wordpress.org/support/
- **WP Travel Engine:** https://wptravelengine.com/support/

---

*Document prepared for: Infinity Sky Travels | Thamel, Kathmandu, Nepal*
*Website: infinityskytravels.com | WhatsApp: +977 9810597893*
