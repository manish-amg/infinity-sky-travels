# INFINITY SKY TRAVELS — MASTER BUILD PROMPT FOR CLAUDE CODE
# Paste this ENTIRE prompt into Claude Code to start the build.
# Version: 1.0 | Built for: infinityskytravels.com

---

You are a **senior WordPress developer**, **Nepal travel industry expert**, and **technical SEO specialist**. Your task is to build a complete, production-ready WordPress travel booking website for **Infinity Sky Travels** — a licensed travel agency based in Thamel, Kathmandu, Nepal.

Build everything completely. Do not truncate any file. Do not skip any section. Follow the priority order at the end.

---

## 1. BRAND IDENTITY

| Property | Value |
|---|---|
| Brand Name | Infinity Sky Travels |
| Tagline | "Your Sky. Your Nepal. Your Adventure." |
| Primary Orange | `#E8751A` |
| Orange Dark | `#C45E0E` |
| Sky Blue | `#29ABE2` |
| Blue Dark | `#1A8BB8` |
| Near Black | `#0D0D0D` |
| Dark Navy | `#1A1A2E` |
| Light BG | `#F8F9FA` |
| White | `#FFFFFF` |
| Body Text | `#333333` |
| Muted Text | `#666666` |
| Heading Font | Montserrat (Google Fonts) |
| Body Font | Open Sans (Google Fonts) |
| Border Radius | 12px |
| Tone | Adventurous, trustworthy, premium yet accessible |
| Audience | FIT (Fully Independent) international travellers — USA, UK, Australia, Israel, Germany, Europe, Japan |

---

## 2. BUSINESS INFORMATION

- **Domain:** https://infinityskytravels.com
- **Address:** Thamel, Kathmandu, Nepal 44600
- **WhatsApp:** +977 9810597893
- **Booking Email:** infinityskytravels8@gmail.com
- **Instagram:** https://www.instagram.com/infinityskytvl
- **Facebook:** https://www.facebook.com/profile.php?id=61581195998301

---

## 3. EXACT TECH STACK — DO NOT DEVIATE

1. WordPress 6.x (latest stable)
2. **Custom WordPress Theme** — build from scratch, no pre-built theme. Name: `infinity-sky-theme`
3. WP Travel Engine plugin (free version + custom code to extend)
4. WooCommerce (for payment processing)
5. Advanced Custom Fields (ACF) for custom fields
6. RankMath SEO plugin
7. Contact Form 7 for inquiry forms
8. WP Mail SMTP (Gmail SMTP)
9. LiteSpeed Cache (performance)
10. Wordfence Security
11. Really Simple SSL
12. Smush (image optimization)
13. UpdraftPlus (backups)
14. WPS Hide Login
15. **Duffel API** (https://duffel.com) for live domestic flight search
16. **Stripe** via WooCommerce Stripe Gateway (international payments)
17. **eSewa** custom WooCommerce payment gateway (Nepal)
18. **Khalti** custom WooCommerce payment gateway (Nepal)

---

## 4. COMPLETE SITE ARCHITECTURE

### PAGE LIST
| Page | Slug | Template |
|---|---|---|
| Home | `/` | `front-page.php` |
| Flights | `/flights` | `page-flights.php` |
| Packages | `/packages` | `page-packages.php` |
| Individual Package | `/packages/{slug}` | `single-ist_package.php` |
| Plan My Trip | `/plan-my-trip` | `page-plan-my-trip.php` |
| Blog | `/blog` | `archive.php` |
| Single Post | `/blog/{slug}` | `single.php` |
| About | `/about` | `page-about.php` |
| Contact | `/contact` | `page-contact.php` |
| Booking Confirmation | `/booking-confirmed` | `page-booking-confirmed.php` |
| Privacy Policy | `/privacy-policy` | default |
| Terms & Conditions | `/terms` | default |
| Cancellation Policy | `/cancellation-policy` | default |

---

## 5. HOME PAGE — COMPLETE SECTION BREAKDOWN

### Section 1: HERO (full-screen parallax)
- Full-viewport height parallax hero using **Jarallax.js**
- Background: Use a high-quality Nepal mountain/trekking image (use Unsplash placeholder: https://source.unsplash.com/1920x1080/?everest,nepal,mountains)
- Dark gradient overlay: `linear-gradient(135deg, rgba(13,13,13,0.7) 0%, rgba(26,26,46,0.5) 100%)`
- Hero text centered:
  - Pre-heading: `"THAMEL, KATHMANDU"` — spaced caps, sky blue, small
  - Main H1: `"Your Sky. Your Nepal. Your Adventure."` — Montserrat 900, white, 64px desktop / 36px mobile
  - Sub: `"Book Domestic Flights + Trekking Packages for Independent Travellers"` — Open Sans, white 70% opacity, 20px
  - Two CTA buttons: `[Search Flights →]` (orange filled) + `[Browse Packages]` (white outline)
- **Floating Flight Search Bar** docked at the bottom of the hero:
  - White card with shadow, border-radius 16px
  - Toggle: ONE WAY | ROUND TRIP (orange underline active state)
  - FROM dropdown + swap icon + TO dropdown (all Nepal airports)
  - Date picker (depart + return)
  - Passengers stepper (Adults, Children, Infants)
  - Orange `SEARCH FLIGHTS` button
  - On search: calls Duffel API → redirects to `/flights?from=KTM&to=LUA&date=...&passengers=1`
  - Fallback: if Duffel unavailable → show "Request Manual Quote" modal

### Section 2: TRUST BAR (below hero)
- Dark `#1A1A2E` background strip
- 4 items with icons: ✓ TAAN Licensed Agency | ✓ All Airlines | ✓ 24/7 WhatsApp Support | ✓ Best Price Guarantee
- Animated counter on scroll: 500+ Routes | 2000+ Happy Travellers | 10+ Years | 6 Airlines

### Section 3: POPULAR DOMESTIC ROUTES
- Section heading: "Popular Domestic Routes"
- 6 route cards in a 3-column grid (2-col tablet, 1-col mobile)
- Each card has:
  - Background image with gradient overlay
  - Route: e.g. "KTM → LUKLA"
  - Airline logos (small)
  - Price "from $89" in orange
  - Duration "35 min"
  - Hover: card lifts (translateY -8px), orange border, image zooms 1.05x
  - Click → pre-filled search on /flights page

Routes to include:
1. Kathmandu → Lukla (Tenzing-Hillary Airport) | 35 min | from $89 | Tara Air, Summit Air
2. Kathmandu → Pokhara | 25 min | from $79 | Buddha Air, Yeti Airlines
3. Kathmandu → Bharatpur (Chitwan) | 20 min | from $69 | Buddha Air
4. Kathmandu → Biratnagar | 40 min | from $95 | Buddha Air, Yeti Airlines
5. Kathmandu → Nepalgunj | 55 min | from $110 | Buddha Air, Yeti Airlines
6. Kathmandu → Janakpur | 35 min | from $85 | Yeti Airlines

### Section 4: FEATURED TREK PACKAGES
- Heading: "Iconic Trekking Packages"
- Subheading: "Handpicked adventures for independent travellers"
- Horizontal scrollable cards on mobile, 3-col grid on desktop
- Each package card:
  - Tall card (portrait ratio)
  - Hero image with parallax effect inside card
  - Difficulty badge: EASY/MODERATE/CHALLENGING/STRENUOUS (color coded: green/yellow/orange/red)
  - Duration chip
  - Package name (Montserrat Bold, white)
  - Key highlight (1 line, italic)
  - Price "from $950/person" (orange, bold)
  - Two buttons: `[View Package]` + `[Quick Enquiry]`
  - Hover: full card overlay slides up with highlights list

### Section 5: ADD-ON SERVICES
- Dark section `#1A1A2E`
- Heading: "Complete Nepal Travel Services"
- 6 icon cards in grid (3x2 desktop):
  1. ✈️ Domestic Flight Booking
  2. 🚗 KTM Airport Transfer
  3. 🏨 Hotel & Accommodation
  4. 📄 Trek Permits & TIMS
  5. 🧭 Porter & Guide Service
  6. 🎒 Gear Rental Kathmandu

### Section 6: TRAVELLER TYPES
- 3 full-height cards side-by-side
- Each with distinct imagery and personality:
  1. **The Backpacker** — "Adventure without breaking the bank" — Budget picks under $700
  2. **The Explorer** — "Balanced comfort and authenticity" — Mid-range $700–$1,500
  3. **The Luxury Trekker** — "Premium lodges, private guides" — From $2,500
- Hover: card expands, reveals top 3 suggested packages

### Section 7: WHY INFINITY SKY
- Split layout: left = image grid (2x2 Nepal photos), right = text content
- Points with animated check icons:
  - All Domestic Airlines in One Place
  - Real-Time Flight Search + Manual Backup
  - Trek Permit & Visa Assistance
  - Airport-to-Hotel-to-Trailhead Service
  - Thamel Office for In-Person Support
  - Emergency 24/7 WhatsApp Line

### Section 8: TESTIMONIALS
- Dark `#0D0D0D` section
- Slider (Swiper.js, autoplay 5s)
- 4 testimonials with: photo placeholder, name, nationality flag emoji, rating stars, quote, trek name
- Sample data:
  1. "Sarah M." 🇺🇸 — EBC Classic — ★★★★★ — "Seamless from KTM airport pickup to Lukla flight. Best agency in Thamel."
  2. "James T." 🇦🇺 — ABC Trek — ★★★★★ — "They handled everything. The add-on hotel booking saved us so much hassle."
  3. "Miriam K." 🇮🇱 — Upper Mustang — ★★★★★ — "Restricted area permits sorted in one day. Incredible trip."
  4. "Tom & Lisa W." 🇬🇧 — Rara Lake — ★★★★★ — "The custom itinerary builder matched us with exactly the right package."

### Section 9: LATEST BLOG POSTS
- 3 cards in a row
- Category tag (colored), date, title, excerpt, read more link
- Hover: title color shifts to orange

### Section 10: FOOTER
- 4-column layout:
  - Col 1: Logo + about blurb + social icons (Instagram, Facebook, WhatsApp)
  - Col 2: Quick Links (Home, Flights, Packages, Blog, About, Contact)
  - Col 3: Top Packages (EBC, ABC, Langtang, Upper Mustang, Rara Lake, Manaslu)
  - Col 4: Contact (address, phone, email, office hours Mon-Sat 9am-6pm)
- Bottom bar: copyright + Privacy Policy | Terms | Cancellation Policy
- Footer background: `#0D0D0D`

### FLOATING ELEMENTS (always visible)
- WhatsApp button bottom-right: green circle, pulse animation, links to `https://wa.me/9779810597893`
- Scroll-to-top button (appears after 400px scroll)

---

## 6. FLIGHTS PAGE (/flights)

- Search results page, pre-populated from homepage search params
- Re-show search bar at top (compact version)
- Results layout:
  - Left sidebar: filters (airline, price range, time of day, stops)
  - Right: results list

### Each Flight Result Card:
```
[Airline Logo] Buddha Air
Departs: 06:30 → Arrives: 07:05
KTM                    LUA
Kathmandu       Lukla
Duration: 35 min | Direct | Economy
──────────────────────────────
                        $89 USD
                  [Book Now]  [Details]
```
- Expand "Details" → shows baggage allowance, cancellation policy
- "Book Now" → opens booking modal:
  - Passenger details form (name, passport, DOB, nationality)
  - ADD-ONS section:
    - [ ] KTM Airport Pickup (+$15)
    - [ ] KTM Hotel (1 night pre-trek) → link to package
    - [ ] EBC Package Add-On (shown ONLY if route is KTM→LUA) — "Start your EBC trek directly!"
    - [ ] Porter arrangement in Lukla (+$25/day)
  - Payment: Stripe (USD) or eSewa/Khalti (NPR)
  - Submit → booking confirmation email

### Fallback Mode:
- If Duffel API returns no results: show "Request Manual Quote" form
- Fields: Route, Date, Passengers, Name, Email, WhatsApp, Special requests
- Submit → email to infinityskytravels8@gmail.com

---

## 7. PACKAGES PAGE (/packages)

- Hero: parallax mountain image, heading "Trekking Packages Nepal"
- Filter bar (sticky):
  - Duration: All | Under 7 days | 7–10 days | 11–14 days | 15+ days
  - Difficulty: All | Easy | Moderate | Challenging | Strenuous
  - Region: All | Everest | Annapurna | Langtang | Mustang | Western Nepal
  - Budget: All | Under $800 | $800–$1500 | $1500–$2500 | $2500+
  - Traveller: All | Backpacker | Mid-Range | Luxury
- Masonry card grid (3-col desktop, 2-col tablet, 1-col mobile)
- Filter uses JS (AJAX) to filter without page reload

---

## 8. INDIVIDUAL PACKAGE PAGE TEMPLATE

Dynamic template for all packages. Fields pulled via ACF:

**Above-fold:**
- Full-width parallax hero image (1920x600)
- Breadcrumb: Home > Packages > EBC Classic Trek
- H1: Package name
- Meta row: Duration | Difficulty | Max Altitude | Best Season | Group Size | Price

**Sticky sidebar (desktop right):**
```
┌─────────────────────────┐
│ From $1,350 per person  │
│ ─────────────────────── │
│ Select Start Date: [  ] │
│ Group Size: [1] [2] [+] │
│                         │
│ [BOOK NOW]              │
│ [REQUEST CUSTOM QUOTE]  │
│                         │
│ 💬 WhatsApp Us          │
│ ☎ +977 9810597893      │
└─────────────────────────┘
```

**Tabs (smooth scroll):**
1. **Overview** — paragraph description, highlights bullet list, photo gallery (lightbox, 8 images)
2. **Day-by-Day Itinerary** — accordion, each day: title, altitude, description, distance, accommodation
3. **Includes / Excludes** — two columns, checkmarks and x marks
4. **Map** — embedded Google Maps or static image of route
5. **Reviews** — star rating aggregate + individual reviews
6. **FAQ** — accordion, 6–8 questions (with FAQPage schema)

**Flight Integration block:**
"Need flights to start this trek? Book KTM→Lukla directly:"
→ Mini flight search widget pre-filled for this package

**Schema Markup:** TouristTrip JSON-LD on every package page

---

## 9. PLAN MY TRIP — ITINERARY BUILDER (/plan-my-trip)

Multi-step form (5 steps). Progress bar at top. No page reloads — all JS driven.

**Step 1: Who's Travelling?**
- [ ] Solo Traveller
- [ ] Couple (2 people)
- [ ] Small Group (3–6)
- [ ] Large Group (7+)
- [ ] Family with Kids
Number of people: stepper input

**Step 2: Trip Duration**
- [ ] Short Break (3–5 days)
- [ ] One Week (6–8 days)
- [ ] Two Weeks (9–14 days)
- [ ] Extended (15+ days)
- [ ] Flexible — surprise me!

**Step 3: What Interests You?**
(Multi-select, icon cards)
- 🏔️ High Altitude Trekking
- 🕌 Cultural & Heritage
- 🦏 Wildlife & National Parks
- 🏄 Adventure Sports
- 📸 Photography
- 🧘 Spiritual & Wellness
- 🍻 Food & Local Life
- 🛩️ Scenic Flights

**Step 4: Budget Per Person (USD)**
- [ ] Budget (Under $500)
- [ ] Mid-Range ($500–$1,200)
- [ ] Premium ($1,200–$2,500)
- [ ] Luxury ($2,500+)

**Step 5: Contact Details**
- Full Name
- Email Address
- WhatsApp Number (with country code)
- Nationality / Country
- Preferred Start Date (date picker)
- Additional Notes (textarea)
- [ ] I agree to privacy policy

**Submit Action:**
1. Send formatted email to infinityskytravels8@gmail.com with all preferences
2. Send auto-reply to traveller: "We've received your trip request! Our expert will WhatsApp you within 2 hours."
3. Redirect to `/booking-confirmed?type=itinerary`
4. Confirmation page shows: summary of their preferences + WhatsApp button + "Our team will contact you within 2 hours"

---

## 10. BLOG SYSTEM

- Categories: Trekking Tips | Flight Guide | Nepal Travel Guide | Permits & Visas | Seasonal Guides | Gear & Packing
- Each post: hero image, author box, reading time, social share buttons, related posts, comment section
- RankMath SEO fields on every post
- Article JSON-LD schema on every post
- Internal linking encouraged in template (related posts by category)

**Create these 10 SEO-optimized blog posts as drafts** (full content, 800–1200 words each):

1. Title: "How to Book Domestic Flights in Nepal: Complete Guide for Foreigners 2025"
   Focus KW: "book domestic flights Nepal"
   
2. Title: "Everest Base Camp Trek: Ultimate Guide for First-Timers from USA, UK, Australia"
   Focus KW: "Everest Base Camp trek guide"

3. Title: "KTM to Lukla Flight: Pricing, Airlines, Tips and How to Book"
   Focus KW: "KTM Lukla flight"

4. Title: "Nepal Trekking Permits 2025: TIMS Card, National Park Fees, Restricted Areas"
   Focus KW: "Nepal trekking permits 2025"

5. Title: "Best Time to Visit Nepal for Trekking: Month-by-Month Weather Guide"
   Focus KW: "best time to visit Nepal trekking"

6. Title: "Annapurna Base Camp vs Everest Base Camp Trek: Which Should You Choose?"
   Focus KW: "Annapurna vs Everest Base Camp"

7. Title: "Nepal Tourist Visa: How to Get It for US, UK, EU, Australian Travellers 2025"
   Focus KW: "Nepal tourist visa for foreigners"

8. Title: "Luxury Trekking Nepal: Premium Lodges, Helicopter Returns and What to Expect"
   Focus KW: "luxury trekking Nepal"

9. Title: "Nepal Budget Trekking: How to Do Everest or Annapurna for Under $800"
   Focus KW: "budget trekking Nepal"

10. Title: "Kathmandu Airport Transfer: TIA to Thamel & Your Trek Startpoint Guide"
    Focus KW: "Kathmandu airport transfer Thamel"

---

## 11. PACKAGES DATA — CREATE ALL 8 AS CPT ENTRIES

Use ACF fields for all data. Build full content for each:

### Package 1: Everest Base Camp Classic Trek
- Duration: 14 days / 13 nights
- Difficulty: Challenging
- Max Altitude: 5,545m (Kala Patthar)
- Best Season: March–May, Sept–Nov
- Price: From $1,350/person
- Includes: KTM–Lukla–KTM flights, teahouse accommodation, experienced guide, 1 porter per 2 trekkers, all meals on trek, national park permits, TIMS card, first aid kit, airport transfers
- Excludes: International flights, travel insurance, personal expenses, tips, extra night accommodation in KTM
- Day 1: Fly KTM to Lukla (2,860m) → Trek to Phakding (2,610m) | 3–4 hrs
- Day 2: Phakding → Namche Bazaar (3,440m) | 5–6 hrs
- Day 3: Acclimatization day in Namche — hike to Everest View Hotel
- Day 4: Namche → Tengboche (3,870m) | 5 hrs
- Day 5: Tengboche → Dingboche (4,410m) | 5 hrs
- Day 6: Acclimatization day in Dingboche — hike to Nangkartshang Peak
- Day 7: Dingboche → Lobuche (4,940m) | 5 hrs
- Day 8: Lobuche → Gorak Shep (5,140m) → EBC (5,364m) → Gorak Shep | 7–8 hrs
- Day 9: Gorak Shep → Kala Patthar (5,545m) → Pheriche (4,280m) | 7–8 hrs
- Day 10: Pheriche → Namche (3,440m) | 6 hrs
- Day 11: Namche → Lukla (2,860m) | 6 hrs
- Day 12: Fly Lukla to KTM | Buffer day
- Day 13: KTM buffer (in case of flight delay — common!)
- Day 14: Departure

### Package 2: Annapurna Base Camp Trek
- Duration: 12 days
- Difficulty: Moderate
- Max Altitude: 4,130m (ABC)
- Price: From $950/person
- Best Season: Oct–Dec, March–May
- Key highlights: Poon Hill sunrise (3,210m), Jhinu hot springs, Modi Khola gorge, ABC glacier amphitheatre
- Days: KTM → PKR flight (Day 1) → Nayapul → Ghandruk → Tadapani → Chhomrong → Dovan → Himalaya Hotel → Deurali → ABC (Day 8) → Descent via same route → Jhinu Danda hot spring → PKR → KTM

### Package 3: Langtang Valley Trek
- Duration: 10 days
- Difficulty: Moderate
- Max Altitude: 4,773m (Tserko Ri)
- Price: From $750/person
- Key highlights: Kyanjin Gompa monastery, yak cheese factory, Tserko Ri panorama, Langtang Village (rebuilt post-2015 earthquake)
- Road drive from KTM to Syabrubesi (7–8 hrs) then trek

### Package 4: Upper Mustang Forbidden Kingdom
- Duration: 12 days
- Difficulty: Moderate
- Max Altitude: 3,840m (Lo Manthang)
- Price: From $2,100/person (includes $500 Restricted Area Permit)
- Unique selling: Ancient walled city Lo Manthang, Tibetan Buddhist culture, unique arid Himalayan landscape
- Access: KTM → PKR flight → drive/fly to Jomsom → trek north

### Package 5: Manaslu Circuit Trek
- Duration: 14 days
- Difficulty: Strenuous
- Max Altitude: 5,106m (Larkya La Pass)
- Price: From $1,450/person
- Note: Restricted area permit required ($100 + MCAP $30)
- Off the beaten path, fewer crowds than EBC/ABC

### Package 6: Rara Lake Trek
- Duration: 10 days
- Difficulty: Moderate
- Max Altitude: 2,990m (Rara Lake)
- Price: From $1,200/person
- Includes: KTM–Nepalgunj–Talcha flights (domestic x2)
- Unique: Remote, Nepal's largest lake, stunning alpine scenery, very few tourists

### Package 7: EBC Luxury Trek
- Duration: 16 days
- Difficulty: Challenging
- Max Altitude: 5,545m
- Price: From $3,500/person
- Luxury lodges: Yeti Mountain Home lodges (Lukla, Namche, Kongde, Phortse, Dingboche)
- Helicopter return from Gorak Shep (optional add-on +$450)
- Private licensed guide, single rooms throughout
- Airport transfers in private SUV

### Package 8: Nepal Cultural Heritage & Nagarkot
- Duration: 7 days
- Difficulty: Easy
- Max Altitude: 2,175m (Nagarkot)
- Price: From $550/person
- Perfect for: Cultural travellers, photographers, families
- Itinerary: Pashupatinath, Boudhanath, Swayambhunath, Bhaktapur Durbar Square, Patan, Nagarkot sunrise, Pokhara day trip

---

## 12. DOMESTIC AIRPORT LIST (use in all dropdowns)

```php
$nepal_airports = [
  'KTM' => 'Kathmandu (Tribhuvan International)',
  'PKR' => 'Pokhara International',
  'LUA' => 'Lukla (Tenzing-Hillary)',
  'BHR' => 'Bhadrapur',
  'BIR' => 'Biratnagar',
  'BJH' => 'Bajhang',
  'BIT' => 'Baitadi',
  'BGL' => 'Baglung',
  'DNP' => 'Dang (Tulsipur)',
  'GKH' => 'Gorkha',
  'HRJ' => 'Chaurjhari',
  'IMK' => 'Simikot',
  'JKR' => 'Janakpur',
  'JMO' => 'Jomsom',
  'KEP' => 'Nepalgunj',
  'MEY' => 'Meghauli (Chitwan)',
  'NGX' => 'Manang',
  'PPL' => 'Phaplu',
  'RHP' => 'Ramechhap (RMIA)',
  'RJB' => 'Rajbiraj',
  'RUM' => 'Rumjatar',
  'RUK' => 'Rukumkot',
  'SIF' => 'Simara (Birgunj)',
  'SKH' => 'Surkhet',
  'TMI' => 'Tumlingtar',
  'TPJ' => 'Taplejung',
];
```

Airlines:
- Buddha Air (U4)
- Yeti Airlines (YT)
- Tara Air (TA)
- Summit Air (S7)
- Shree Airlines (SHA)
- Nepal Airlines (RA)

---

## 13. CUSTOM WORDPRESS THEME FILE STRUCTURE

Build ALL of these files completely:

```
wp-content/themes/infinity-sky-theme/
├── style.css                          ← Theme declaration header
├── functions.php                      ← All hooks, enqueues, CPTs, widgets
├── index.php                          ← Fallback template
├── header.php                         ← Navigation + mega menu
├── footer.php                         ← Full footer
├── front-page.php                     ← Homepage
├── page.php                           ← Default page template
├── page-flights.php                   ← Flight search + results
├── page-packages.php                  ← Package listing with filters
├── single-ist_package.php             ← Individual package
├── page-plan-my-trip.php              ← Itinerary builder
├── page-about.php                     ← About page
├── page-contact.php                   ← Contact page
├── page-booking-confirmed.php         ← Post-booking confirmation
├── single.php                         ← Blog post single
├── archive.php                        ← Blog archive / category
├── search.php                         ← Search results
├── 404.php                            ← Error page
├── sidebar.php                        ← Sidebar template
├── comments.php                       ← Comments template
│
├── assets/
│   ├── css/
│   │   ├── main.css                   ← All base styles
│   │   ├── animations.css             ← Parallax, hover, scroll effects
│   │   ├── flight-search.css          ← Search bar + results styles
│   │   ├── packages.css               ← Package cards + single
│   │   ├── itinerary-builder.css      ← Multi-step form
│   │   └── responsive.css             ← Mobile-first breakpoints
│   │
│   ├── js/
│   │   ├── main.js                    ← Global JS, WhatsApp button, scroll effects
│   │   ├── flight-search.js           ← Duffel API integration
│   │   ├── flight-results.js          ← Display + filter flight results
│   │   ├── package-filter.js          ← AJAX package filtering
│   │   ├── itinerary-builder.js       ← Multi-step form logic
│   │   ├── booking-modal.js           ← Booking modal + add-ons
│   │   └── animations.js              ← Jarallax, Intersection Observer, Swiper
│   │
│   └── images/
│       └── placeholder.jpg            ← 1px placeholder
│
├── template-parts/
│   ├── home/
│   │   ├── hero.php
│   │   ├── trust-bar.php
│   │   ├── popular-routes.php
│   │   ├── featured-packages.php
│   │   ├── addons-strip.php
│   │   ├── traveller-types.php
│   │   ├── why-infinity-sky.php
│   │   ├── testimonials.php
│   │   └── blog-preview.php
│   ├── global/
│   │   ├── flight-search-bar.php
│   │   ├── package-card.php
│   │   ├── breadcrumb.php
│   │   ├── whatsapp-float.php
│   │   └── scroll-top.php
│   └── package/
│       ├── package-hero.php
│       ├── package-tabs.php
│       ├── package-sidebar.php
│       └── package-itinerary.php
│
└── inc/
    ├── custom-post-types.php          ← ist_package CPT + add-ons
    ├── acf-fields.php                 ← ACF field group registration
    ├── menus.php                      ← Nav menu registration
    ├── widgets.php                    ← Widget areas
    ├── schema.php                     ← JSON-LD schema output
    ├── api-helpers.php                ← Duffel API helper functions
    ├── shortcodes.php                 ← All shortcode definitions
    └── ajax-handlers.php             ← AJAX handlers for flight search, filters
```

---

## 14. CUSTOM PLUGINS — BUILD BOTH

### Plugin 1: ist-flight-booking
Location: `wp-content/plugins/ist-flight-booking/`

Files:
- `ist-flight-booking.php` — main plugin file, hooks
- `includes/class-duffel-api.php` — Duffel API wrapper
- `includes/class-booking.php` — booking management
- `includes/class-payment.php` — Stripe + eSewa + Khalti
- `includes/class-email.php` — booking confirmation emails
- `includes/class-addons.php` — add-on services management
- `admin/bookings-dashboard.php` — WP admin bookings list
- `admin/settings-page.php` — API keys, email settings
- `assets/css/booking.css`
- `assets/js/booking.js`

**Duffel API Setup:**
```php
// Base URL: https://api.duffel.com/air
// Required headers: 
//   Authorization: Bearer {DUFFEL_ACCESS_TOKEN}
//   Duffel-Version: v2
//   Content-Type: application/json

// Endpoints to implement:
// POST /offer_requests — search for flights
// GET /offers/{id} — get specific offer
// POST /orders — create booking
// GET /orders/{id} — get order details
```

Add settings page in WP Admin → Infinity Sky → Flight Settings:
- Duffel API Key (test/live toggle)
- Manual Fallback Email
- Add-on prices (configurable)
- Airline logos upload

**Booking Database Table:**
```sql
CREATE TABLE {prefix}ist_bookings (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  booking_ref varchar(20) NOT NULL,
  type enum('flight','package','inquiry') NOT NULL,
  status enum('pending','confirmed','cancelled','refunded') DEFAULT 'pending',
  traveller_name varchar(100) NOT NULL,
  traveller_email varchar(100) NOT NULL,
  traveller_whatsapp varchar(30),
  traveller_nationality varchar(50),
  flight_from varchar(10),
  flight_to varchar(10),
  travel_date date,
  passengers int(3),
  addons longtext,
  amount_usd decimal(10,2),
  amount_npr decimal(12,2),
  payment_method enum('stripe','esewa','khalti','bank','pending'),
  payment_status enum('unpaid','paid','partial','refunded') DEFAULT 'unpaid',
  duffel_order_id varchar(100),
  notes longtext,
  created_at datetime DEFAULT CURRENT_TIMESTAMP,
  updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);
```

### Plugin 2: ist-itinerary-builder
Location: `wp-content/plugins/ist-itinerary-builder/`

Files:
- `ist-itinerary-builder.php` — main plugin
- `includes/class-form-handler.php` — form processing + email
- `includes/class-recommendation.php` — suggests packages based on preferences
- `assets/css/builder.css`
- `assets/js/builder.js`

Shortcode: `[ist_itinerary_builder]`

After form submit, send this email to admin:
```
Subject: New Itinerary Request - {Name} ({Nationality}) via InfinitySkytravels.com

TRAVELLER PROFILE:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Name: {name}
Email: {email}
WhatsApp: {whatsapp}
Nationality: {nationality}
Preferred Date: {start_date}

TRIP PREFERENCES:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Group: {group_type} ({group_size} people)
Duration: {duration}
Interests: {interests_list}
Budget: {budget_range}

SUGGESTED PACKAGES:
Based on preferences, consider: {auto_suggested_packages}

NOTES: {additional_notes}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Reply directly to traveller: {email}
WhatsApp: {whatsapp}
```

---

## 15. PAYMENT INTEGRATION

### Stripe (WooCommerce Stripe Plugin)
- Install: WooCommerce Stripe Payment Gateway (official)
- Enable: Card payments in USD
- Also enable: iDEAL, SEPA for European customers
- Set webhook endpoint: `https://infinityskytravels.com/wp-json/ist/v1/stripe-webhook`

### eSewa (Custom Gateway)
Build `wp-content/plugins/ist-esewa-gateway/` with WooCommerce payment gateway class.
- eSewa test URL: `https://uat.esewa.com.np/epay/main`
- eSewa production URL: `https://esewa.com.np/epay/main`
- Settings: Merchant Code (provided by client), Success URL, Failure URL
- Verify payment via eSewa verification endpoint

### Khalti (Custom Gateway)
Build `wp-content/plugins/ist-khalti-gateway/` 
- Khalti API v2: `https://khalti.com/api/v2/payment/initiate/`
- Test key: provided by client
- Khalti Checkout.js integration

---

## 16. CSS DESIGN SYSTEM — IMPLEMENT EXACTLY

```css
/* ============================================
   INFINITY SKY TRAVELS — DESIGN SYSTEM
   ============================================ */

:root {
  /* Colors */
  --ist-orange: #E8751A;
  --ist-orange-dark: #C45E0E;
  --ist-orange-light: #F4A261;
  --ist-blue: #29ABE2;
  --ist-blue-dark: #1A8BB8;
  --ist-blue-light: #87CEEB;
  --ist-dark: #0D0D0D;
  --ist-dark-2: #1A1A2E;
  --ist-dark-3: #252540;
  --ist-light: #F8F9FA;
  --ist-light-2: #EEF2F7;
  --ist-white: #FFFFFF;
  --ist-text: #333333;
  --ist-text-light: #666666;
  --ist-border: #E0E7EF;
  --ist-success: #2ECC71;
  --ist-warning: #F39C12;
  --ist-error: #E74C3C;
  
  /* Typography */
  --font-heading: 'Montserrat', sans-serif;
  --font-body: 'Open Sans', sans-serif;
  
  /* Spacing scale */
  --space-xs: 0.5rem;
  --space-sm: 1rem;
  --space-md: 1.5rem;
  --space-lg: 2rem;
  --space-xl: 3rem;
  --space-2xl: 5rem;
  --space-3xl: 8rem;
  
  /* Border radius */
  --radius-sm: 6px;
  --radius-md: 12px;
  --radius-lg: 20px;
  --radius-xl: 32px;
  --radius-full: 9999px;
  
  /* Shadows */
  --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
  --shadow-md: 0 8px 32px rgba(0,0,0,0.12);
  --shadow-lg: 0 16px 48px rgba(0,0,0,0.18);
  --shadow-orange: 0 8px 24px rgba(232,117,26,0.35);
  --shadow-blue: 0 8px 24px rgba(41,171,226,0.30);
  
  /* Transitions */
  --transition-fast: all 0.15s ease;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  
  /* Z-index scale */
  --z-base: 1;
  --z-above: 10;
  --z-modal: 100;
  --z-nav: 200;
  --z-toast: 300;
}

/* Gradient utilities */
.ist-gradient-orange { background: linear-gradient(135deg, var(--ist-orange) 0%, var(--ist-orange-dark) 100%); }
.ist-gradient-blue { background: linear-gradient(135deg, var(--ist-blue) 0%, var(--ist-blue-dark) 100%); }
.ist-gradient-dark { background: linear-gradient(135deg, var(--ist-dark-2) 0%, var(--ist-dark) 100%); }
.ist-gradient-hero { background: linear-gradient(135deg, rgba(13,13,13,0.75) 0%, rgba(26,26,46,0.55) 100%); }

/* Button system */
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 28px;
  background: var(--ist-orange);
  color: var(--ist-white);
  font-family: var(--font-heading);
  font-weight: 700;
  font-size: 15px;
  letter-spacing: 0.5px;
  border: 2px solid var(--ist-orange);
  border-radius: var(--radius-full);
  cursor: pointer;
  transition: var(--transition);
  text-decoration: none;
  position: relative;
  overflow: hidden;
}
.btn-primary::before {
  content: '';
  position: absolute;
  top: 0; left: -100%;
  width: 100%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
  transition: left 0.4s ease;
}
.btn-primary:hover::before { left: 100%; }
.btn-primary:hover {
  background: var(--ist-orange-dark);
  border-color: var(--ist-orange-dark);
  transform: translateY(-2px);
  box-shadow: var(--shadow-orange);
}

.btn-outline {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 28px;
  background: transparent;
  color: var(--ist-white);
  font-family: var(--font-heading);
  font-weight: 700;
  font-size: 15px;
  border: 2px solid rgba(255,255,255,0.7);
  border-radius: var(--radius-full);
  cursor: pointer;
  transition: var(--transition);
  text-decoration: none;
}
.btn-outline:hover {
  background: rgba(255,255,255,0.12);
  border-color: var(--ist-white);
  transform: translateY(-2px);
}

/* Card hover effect */
.ist-card {
  border-radius: var(--radius-md);
  overflow: hidden;
  transition: var(--transition);
  background: var(--ist-white);
  box-shadow: var(--shadow-sm);
}
.ist-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-lg);
}
.ist-card:hover .ist-card-image img {
  transform: scale(1.06);
}
.ist-card-image {
  overflow: hidden;
}
.ist-card-image img {
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Difficulty badges */
.badge-easy { background: #2ECC71; color: #fff; }
.badge-moderate { background: #F39C12; color: #fff; }
.badge-challenging { background: var(--ist-orange); color: #fff; }
.badge-strenuous { background: #E74C3C; color: #fff; }
.badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: var(--radius-full);
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* WhatsApp float button */
.whatsapp-float {
  position: fixed;
  bottom: 28px;
  right: 28px;
  width: 60px;
  height: 60px;
  background: #25D366;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(37,211,102,0.45);
  z-index: var(--z-modal);
  animation: pulse-whatsapp 2s infinite;
  transition: var(--transition);
}
.whatsapp-float:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 28px rgba(37,211,102,0.6);
}
@keyframes pulse-whatsapp {
  0%, 100% { box-shadow: 0 4px 20px rgba(37,211,102,0.45), 0 0 0 0 rgba(37,211,102,0.4); }
  50% { box-shadow: 0 4px 20px rgba(37,211,102,0.45), 0 0 0 12px rgba(37,211,102,0); }
}
```

---

## 17. ANIMATIONS — IMPLEMENT IN animations.js

```javascript
// 1. Parallax via Jarallax
import jarallax from 'jarallax';
document.querySelectorAll('[data-jarallax]').forEach(el => {
  jarallax(el, { speed: 0.6 });
});

// 2. Scroll-triggered fade-in (Intersection Observer)
const fadeElements = document.querySelectorAll('[data-fade]');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('ist-visible');
    }
  });
}, { threshold: 0.15 });
fadeElements.forEach(el => observer.observe(el));

// CSS for data-fade:
// [data-fade] { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
// [data-fade].ist-visible { opacity: 1; transform: translateY(0); }
// Stagger: [data-fade-delay="1"] { transition-delay: 0.1s; }

// 3. Animated counters
const counters = document.querySelectorAll('[data-counter]');
counters.forEach(counter => {
  const target = parseInt(counter.getAttribute('data-counter'));
  const duration = 2000;
  const step = target / (duration / 16);
  let current = 0;
  const counterObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      const timer = setInterval(() => {
        current += step;
        if (current >= target) {
          counter.textContent = target + (counter.dataset.suffix || '');
          clearInterval(timer);
        } else {
          counter.textContent = Math.floor(current) + (counter.dataset.suffix || '');
        }
      }, 16);
      counterObserver.disconnect();
    }
  });
  counterObserver.observe(counter);
});

// 4. Swiper testimonials
import Swiper from 'swiper';
new Swiper('.testimonials-swiper', {
  slidesPerView: 1,
  spaceBetween: 30,
  autoplay: { delay: 5000, disableOnInteraction: false },
  pagination: { el: '.swiper-pagination', clickable: true },
  breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
});

// 5. Sticky nav color change on scroll
window.addEventListener('scroll', () => {
  const nav = document.querySelector('.ist-nav');
  if (window.scrollY > 80) {
    nav.classList.add('ist-nav--scrolled'); // add background + shadow
  } else {
    nav.classList.remove('ist-nav--scrolled');
  }
});
```

---

## 18. NAVIGATION STRUCTURE

**Primary Nav:**
- Logo (left)
- Main menu (center):
  - Flights ↓ (mega menu: Popular Routes, Search Flights, All Airlines)
  - Packages ↓ (mega menu: By Region, By Duration, By Budget, By Traveller Type)
  - Plan My Trip
  - Blog ↓ (categories)
  - About
  - Contact
- Right side: WhatsApp button icon + `[Book Now]` orange CTA button

**Mobile Nav:**
- Hamburger → full-screen overlay menu
- Accordion sub-menus
- WhatsApp + Call buttons at bottom of mobile menu

---

## 19. SEO CONFIGURATION

### RankMath Settings to configure programmatically:
```php
// Home page
update_post_meta($home_id, 'rank_math_title', 'Book Nepal Domestic Flights & Trekking Packages | Infinity Sky Travels');
update_post_meta($home_id, 'rank_math_description', 'Book domestic flights in Nepal (KTM-Lukla, Pokhara, Chitwan) + EBC, ABC, Mustang trek packages. Best rates for independent travellers. Thamel, Kathmandu.');
update_post_meta($home_id, 'rank_math_focus_keyword', 'Nepal domestic flights, Nepal trekking packages foreigners');
```

### Schema JSON-LD (output in wp_head via functions.php):
```json
// TravelAgency schema on every page:
{
  "@context": "https://schema.org",
  "@type": "TravelAgency",
  "name": "Infinity Sky Travels",
  "url": "https://infinityskytravels.com",
  "logo": "https://infinityskytravels.com/wp-content/themes/infinity-sky-theme/assets/images/logo.png",
  "description": "Nepal domestic flight booking and trekking package agency for independent travellers. Based in Thamel, Kathmandu.",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Thamel",
    "addressLocality": "Kathmandu",
    "postalCode": "44600",
    "addressCountry": "NP"
  },
  "telephone": "+977-9810597893",
  "email": "infinityskytravels8@gmail.com",
  "sameAs": [
    "https://www.instagram.com/infinityskytvl",
    "https://www.facebook.com/profile.php?id=61581195998301"
  ],
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
    "opens": "09:00",
    "closes": "18:00"
  }
}
```

### robots.txt:
```
User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-login.php
Disallow: /wp-includes/
Disallow: /?s=
Sitemap: https://infinityskytravels.com/sitemap.xml
```

### .htaccess additions (A2 Hosting LiteSpeed):
```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# GZIP compression
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/css application/json application/javascript text/xml
</IfModule>

# Browser caching
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

---

## 20. wp-config.php ADDITIONS

Add these after the database settings:
```php
// Performance
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Security
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true);
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);

// API Keys (fill in after getting from services)
define('DUFFEL_API_KEY', 'YOUR_DUFFEL_KEY_HERE');
define('STRIPE_PUBLIC_KEY', 'YOUR_STRIPE_PK_HERE');
define('STRIPE_SECRET_KEY', 'YOUR_STRIPE_SK_HERE');
define('ESEWA_MERCHANT_CODE', 'YOUR_ESEWA_CODE_HERE');
define('KHALTI_PUBLIC_KEY', 'YOUR_KHALTI_KEY_HERE');
define('KHALTI_SECRET_KEY', 'YOUR_KHALTI_SECRET_HERE');

// Revisions
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 300);

// Salt keys — generate from https://api.wordpress.org/secret-key/1.1/salt/
```

---

## 21. EMAIL TEMPLATES

Build HTML email templates for:

1. **Flight Booking Confirmation**
   - Subject: "Your Flight is Booked! ✈️ {Route} on {Date} — Infinity Sky Travels"
   - Content: booking ref, flight details, passenger names, add-ons, total paid, WhatsApp contact

2. **Package Booking Confirmation**
   - Subject: "Trek Package Booked! 🏔️ {Package Name} — Infinity Sky Travels"
   - Content: package details, start date, group size, inclusions, next steps

3. **Itinerary Request Received**
   - Subject: "We Got Your Trip Request! 🇳🇵 — Infinity Sky Travels"
   - Content: preferences summary, promise of contact within 2 hours, WhatsApp CTA

4. **Payment Receipt**
   - Subject: "Payment Confirmed — INF-{booking_ref}"
   - Content: amount, payment method, booking reference, invoice link

All emails: use brand colors, logo header, social links in footer, mobile-responsive HTML.

---

## 22. ABOUT PAGE CONTENT

**Hero:** Split layout — left: team photo placeholder, right: story

**Company Story:**
"Infinity Sky Travels was born in the heart of Thamel — the crossroads of Kathmandu where every Nepal adventure begins. We are a licensed travel agency (TAAN member) specialising in one thing: getting independent travellers to the most extraordinary places in Nepal, seamlessly.

From booking your Kathmandu–Lukla flight at the best rate, to arranging your Everest Base Camp permits, your Thamel hotel, your porter, and your return transfer — we handle every detail so you can focus on the adventure.

We work with every domestic airline in Nepal, offering real-time flight booking backed by a dedicated team who've trekked every trail we sell."

**Stats (animated on scroll):**
- 500+ Routes Covered
- 2,000+ Travellers Served
- 6 Partner Airlines
- 8 Trek Regions

**Affiliations:** TAAN | NTB | IATA (Pending) — with logos

**Team section:** 4 placeholder team members with names and roles (CEO, Operations, Trek Expert, Flights Specialist)

---

## 23. CONTACT PAGE CONTENT

- Google Maps embed: `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.0!2d85.3089!3d27.7153!` (Thamel, KTM)
- Office Hours: Monday–Saturday, 9:00 AM – 6:00 PM (Nepal Time, UTC+5:45)
- WhatsApp: `https://wa.me/9779810597893`
- Contact Form 7: Name, Email, Phone, Inquiry Type (dropdown: Flight Booking / Package / Custom Trip / General), Message

---

## 24. LEGAL PAGES — CREATE WITH FULL CONTENT

### Privacy Policy: Cover data collection, storage, third-party APIs (Stripe, Duffel, eSewa, Khalti), cookies, GDPR compliance for EU travellers.

### Terms & Conditions: Booking terms, payment, cancellation per policy below.

### Cancellation Policy:
- Flights: Per airline policy (non-refundable on mountain routes like Lukla)
- Packages: 
  - 30+ days before: Full refund minus 10% admin fee
  - 15–29 days: 50% refund
  - 7–14 days: 25% refund
  - Under 7 days: No refund
  - Force Majeure (weather, strikes, govt): 100% credit voucher

---

## 25. WP-CLI INSTALL COMMANDS

After WordPress is installed on the server, run these WP-CLI commands:

```bash
# Install and activate all plugins
wp plugin install woocommerce --activate
wp plugin install wp-travel-engine --activate
wp plugin install seo-by-rank-math --activate
wp plugin install contact-form-7 --activate
wp plugin install wp-mail-smtp --activate
wp plugin install smush --activate
wp plugin install litespeed-cache --activate
wp plugin install wordfence --activate
wp plugin install really-simple-ssl --activate
wp plugin install advanced-custom-fields --activate
wp plugin install updraftplus --activate
wp plugin install wps-hide-login --activate
wp plugin install regenerate-thumbnails --activate
wp plugin install woocommerce-gateway-stripe --activate

# Set permalink structure
wp rewrite structure '/%postname%/' --hard

# Create pages
wp post create --post_type=page --post_title='Home' --post_status=publish --post_name='home'
wp post create --post_type=page --post_title='Flights' --post_status=publish --post_name='flights'
wp post create --post_type=page --post_title='Packages' --post_status=publish --post_name='packages'
wp post create --post_type=page --post_title='Plan My Trip' --post_status=publish --post_name='plan-my-trip'
wp post create --post_type=page --post_title='Blog' --post_status=publish --post_name='blog'
wp post create --post_type=page --post_title='About' --post_status=publish --post_name='about'
wp post create --post_type=page --post_title='Contact' --post_status=publish --post_name='contact'
wp post create --post_type=page --post_title='Booking Confirmed' --post_status=publish --post_name='booking-confirmed'
wp post create --post_type=page --post_title='Privacy Policy' --post_status=publish --post_name='privacy-policy'
wp post create --post_type=page --post_title='Terms & Conditions' --post_status=publish --post_name='terms'
wp post create --post_type=page --post_title='Cancellation Policy' --post_status=publish --post_name='cancellation-policy'

# Set static homepage
wp option update show_on_front 'page'
wp option update page_on_front $(wp post list --post_type=page --name=home --format=ids)
wp option update page_for_posts $(wp post list --post_type=page --name=blog --format=ids)

# Activate theme
wp theme activate infinity-sky-theme

# Set site title and tagline
wp option update blogname 'Infinity Sky Travels'
wp option update blogdescription 'Nepal Domestic Flights & Trekking Packages for Independent Travellers'
wp option update admin_email 'infinityskytravels8@gmail.com'
```

---

## 26. BUILD PRIORITY ORDER

Execute in this exact sequence. Complete each step fully before moving to the next.

**PHASE 1: Foundation (Days 1–2)**
1. Create theme folder structure with all files
2. `style.css` theme header
3. `functions.php` — enqueues, CPTs, ACF fields, menus
4. `header.php` — full navigation with mega menu
5. `footer.php` — full 4-column footer
6. `inc/custom-post-types.php` — ist_package CPT
7. `inc/acf-fields.php` — all package fields
8. Design system CSS (`main.css`, `animations.css`)
9. Base `animations.js` (Jarallax, Intersection Observer, Swiper)

**PHASE 2: Homepage (Day 3)**
10. `front-page.php` — complete homepage with all 10 sections
11. `template-parts/home/` — all section partials
12. `template-parts/global/flight-search-bar.php`
13. `flight-search.js` (Duffel API integration)
14. `responsive.css` — mobile breakpoints for homepage

**PHASE 3: Flights Page (Day 4)**
15. `page-flights.php` — search results layout
16. `flight-results.js` — results display + filters
17. `booking-modal.js` — booking flow + add-ons
18. `flight-search.css` — all flight page styles

**PHASE 4: Packages (Day 5)**
19. `page-packages.php` — listing with filters
20. `single-ist_package.php` — individual package full template
21. `template-parts/package/` — all package partials
22. `package-filter.js` — AJAX filtering
23. `packages.css`

**PHASE 5: Itinerary Builder (Day 6)**
24. `page-plan-my-trip.php`
25. `ist-itinerary-builder` plugin (complete)
26. `itinerary-builder.js` — multi-step form
27. `itinerary-builder.css`

**PHASE 6: Blog + Secondary Pages (Day 7)**
28. `single.php` — blog post with schema
29. `archive.php` — blog listing
30. `page-about.php`
31. `page-contact.php`
32. `page-booking-confirmed.php`
33. `404.php`

**PHASE 7: Plugins (Days 8–9)**
34. `ist-flight-booking` plugin — Duffel API + admin dashboard
35. eSewa WooCommerce gateway plugin
36. Khalti WooCommerce gateway plugin
37. Email templates (HTML)

**PHASE 8: Content (Day 10)**
38. Create 8 package CPT entries with full content
39. Create 10 blog post drafts with full SEO content
40. Schema JSON-LD on all relevant pages

**PHASE 9: SEO + Performance (Day 11)**
41. RankMath configuration
42. robots.txt
43. .htaccess optimizations
44. Image lazy loading
45. LiteSpeed Cache configuration

**PHASE 10: Final (Day 12)**
46. Security hardening (Wordfence rules, login URL change)
47. UpdraftPlus backup schedule
48. Final responsive audit
49. **Deployment checklist** — output as final document

---

## FINAL OUTPUT REQUIRED

After building all files, provide:
1. A complete list of all files created with their paths
2. API keys needed (list of services to sign up for)
3. Post-deployment configuration checklist
4. First-month SEO action checklist

Build everything now. Start with Phase 1.
