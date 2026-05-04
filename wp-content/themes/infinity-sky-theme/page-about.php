<?php
/**
 * Template Name: About Us
 */

get_header();

$team = [
    [ 'name' => 'Rajesh Tamang',   'role' => 'Founder & Lead Guide',       'bio' => '20+ years guiding in the Himalayas. Everest summiteer, certified by Nepal Mountaineering Association. Rajesh\'s passion is connecting travellers with the real Nepal.', 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80&auto=format&fit=crop&face', 'exp' => '20+ years' ],
    [ 'name' => 'Sunita Sherpa',   'role' => 'Operations Manager',          'bio' => 'Born in Namche Bazaar, Sunita manages all on-ground logistics, permits, and teahouse bookings to ensure your trek runs flawlessly.', 'img' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=400&q=80&auto=format&fit=crop&face', 'exp' => '12 years' ],
    [ 'name' => 'Bikash Gurung',   'role' => 'Senior Trek Guide',           'bio' => 'Annapurna Circuit specialist and certified Wilderness First Responder. Bikash has led 200+ successful treks across the Annapurna and Manaslu regions.', 'img' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80&auto=format&fit=crop&face', 'exp' => '15 years' ],
    [ 'name' => 'Priya Rai',       'role' => 'Customer Experience Lead',    'bio' => 'Priya handles all pre-trip enquiries and custom itinerary planning. Fluent in English, Hindi, and Nepali — she\'s your first point of contact.', 'img' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&q=80&auto=format&fit=crop&face', 'exp' => '8 years' ],
    [ 'name' => 'Dawa Lama',       'role' => 'Everest Region Specialist',   'bio' => 'High-altitude porter turned licensed guide, Dawa has been to Everest Base Camp over 60 times. His knowledge of the Khumbu is unparalleled.', 'img' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80&auto=format&fit=crop&face', 'exp' => '18 years' ],
    [ 'name' => 'Anjali Thapa',    'role' => 'Cultural & Heritage Guide',   'bio' => 'Art historian and certified heritage guide, Anjali leads our cultural tours through Kathmandu Valley\'s temples, monasteries, and living traditions.', 'img' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&q=80&auto=format&fit=crop&face', 'exp' => '10 years' ],
];

$milestones = [
    [ 'year' => '2008', 'event' => 'Founded in Thamel, Kathmandu with a single guide and a dream.' ],
    [ 'year' => '2011', 'event' => 'Licensed by Nepal Tourism Board. First 100 international clients.' ],
    [ 'year' => '2014', 'event' => 'Expanded to Annapurna and Langtang regions. Team grew to 12 guides.' ],
    [ 'year' => '2015', 'event' => 'Post-earthquake relief volunteering. Helped rebuild 3 teahouse lodges on EBC route.' ],
    [ 'year' => '2018', 'event' => 'Launched domestic flight booking service. Reached 1,000 satisfied clients.' ],
    [ 'year' => '2021', 'event' => 'TripAdvisor Certificate of Excellence. Expanded to Upper Mustang and Dolpo.' ],
    [ 'year' => '2024', 'event' => 'Celebrated 2,000+ successful treks. New digital booking platform launched.' ],
];

$values = [
    [ 'icon' => '🏔', 'title' => 'Safety First',       'desc' => 'Every guide is wilderness first-aid certified. We carry emergency oxygen above 4,000 m and maintain 24/7 emergency contact lines.' ],
    [ 'icon' => '🌱', 'title' => 'Responsible Tourism', 'desc' => 'We follow Leave No Trace principles, pay guides and porters above minimum wage, and support local teahouse economies.' ],
    [ 'icon' => '🤝', 'title' => 'Authentic Connections','desc' => 'Small groups, local guides, genuine cultural exchange — we show you Nepal the way locals experience it, not from behind a tour bus.' ],
    [ 'icon' => '💯', 'title' => 'Transparent Pricing', 'desc' => 'Every item in our price breakdown is explained. No hidden surcharges. What you see is exactly what you pay.' ],
];
?>

<div class="ist-about-page">

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <div class="ist-page-hero jarallax" data-jarallax data-speed="0.5"
         style="background-image:url('https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?w=1920&q=80&auto=format&fit=crop');">
        <div class="ist-page-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;padding-bottom:var(--space-xl);">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
            <h1 class="ist-text-white"><?php esc_html_e( 'About Infinity Sky Travels', 'infinity-sky' ); ?></h1>
            <p style="color:rgba(255,255,255,0.75);font-size:1.1rem;max-width:580px;margin-top:8px;">
                <?php esc_html_e( 'A family of passionate Nepali mountaineers, guides, and travel professionals — built on trust since 2008.', 'infinity-sky' ); ?>
            </p>
        </div>
    </div>

    <!-- ── Stats bar ─────────────────────────────────────────────── -->
    <div class="ist-about-stats">
        <div class="ist-container">
            <div class="ist-about-stats__grid">
                <?php
                $stats = [
                    [ 'n' => '2000', 'suffix' => '+', 'label' => __( 'Happy Trekkers', 'infinity-sky' ) ],
                    [ 'n' => '16',   'suffix' => '+', 'label' => __( 'Years in Business', 'infinity-sky' ) ],
                    [ 'n' => '30',   'suffix' => '+', 'label' => __( 'Expert Guides', 'infinity-sky' ) ],
                    [ 'n' => '4.9',  'suffix' => '★', 'label' => __( 'Average Rating', 'infinity-sky' ) ],
                ];
                foreach ( $stats as $s ) : ?>
                <div class="ist-about-stat" data-fade>
                    <span class="ist-about-stat__num" data-counter="<?php echo esc_attr( $s['n'] ); ?>"
                          data-suffix="<?php echo esc_attr( $s['suffix'] ); ?>"><?php echo esc_html( $s['n'] . $s['suffix'] ); ?></span>
                    <span class="ist-about-stat__label"><?php echo esc_html( $s['label'] ); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Story section ─────────────────────────────────────────── -->
    <div class="ist-section">
        <div class="ist-container">
            <div class="ist-about-story">
                <div class="ist-about-story__text" data-fade>
                    <span class="ist-section-eyebrow"><?php esc_html_e( 'Our Story', 'infinity-sky' ); ?></span>
                    <h2><?php esc_html_e( 'Born in the Shadow of Everest', 'infinity-sky' ); ?></h2>
                    <p><?php esc_html_e( 'Infinity Sky Travels was founded in 2008 by Rajesh Tamang, a Sherpa guide who had spent two decades leading expeditions but noticed a gap: independent travellers were either paying inflated agency rates or risking their safety with unlicensed operators.', 'infinity-sky' ); ?></p>
                    <p><?php esc_html_e( 'Rajesh started with one guiding partner, a laptop, and an office above a tea shop in Thamel. The mission was simple: offer the same quality of experienced, licensed guiding that luxury operators charged a premium for — but at prices that work for all budgets.', 'infinity-sky' ); ?></p>
                    <p><?php esc_html_e( 'Today we\'re a team of 30+ guides and support staff, all Nepali nationals, all licensed, and many — like Rajesh — born and raised in the very mountains you\'ll trek through. Every booking funds local employment, supports teahouse families along the route, and contributes to our ongoing conservation efforts.', 'infinity-sky' ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary btn-lg" style="margin-top:var(--space-md);">
                        <?php esc_html_e( 'Trek with Us', 'infinity-sky' ); ?>
                    </a>
                </div>
                <div class="ist-about-story__images" data-fade data-fade-direction="right">
                    <div class="ist-about-story__img-grid">
                        <img src="https://images.unsplash.com/photo-1521150932951-303a95503ed3?w=600&q=80&auto=format&fit=crop" alt="Guide on ridge" loading="lazy">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80&auto=format&fit=crop" alt="Himalayan vista" loading="lazy">
                        <img src="https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=600&q=80&auto=format&fit=crop" alt="Trekkers in valley" loading="lazy">
                        <img src="https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?w=600&q=80&auto=format&fit=crop" alt="Monastery Himalayas" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Values ────────────────────────────────────────────────── -->
    <div class="ist-section ist-section--light">
        <div class="ist-container">
            <div class="ist-section-header" style="text-align:center;margin-bottom:var(--space-xl);">
                <span class="ist-section-eyebrow"><?php esc_html_e( 'What Drives Us', 'infinity-sky' ); ?></span>
                <h2><?php esc_html_e( 'Our Values', 'infinity-sky' ); ?></h2>
            </div>
            <div class="ist-about-values-grid" data-stagger>
                <?php foreach ( $values as $v ) : ?>
                <div class="ist-about-value-card" data-fade>
                    <div class="ist-about-value-card__icon"><?php echo $v['icon']; ?></div>
                    <h3><?php echo esc_html( $v['title'] ); ?></h3>
                    <p><?php echo esc_html( $v['desc'] ); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Timeline ──────────────────────────────────────────────── -->
    <div class="ist-section">
        <div class="ist-container" style="max-width:860px;">
            <div class="ist-section-header" style="text-align:center;margin-bottom:var(--space-xl);">
                <span class="ist-section-eyebrow"><?php esc_html_e( 'Since 2008', 'infinity-sky' ); ?></span>
                <h2><?php esc_html_e( 'Our Journey', 'infinity-sky' ); ?></h2>
            </div>
            <div class="ist-timeline">
                <?php foreach ( $milestones as $m ) : ?>
                <div class="ist-timeline__item" data-fade>
                    <div class="ist-timeline__year"><?php echo esc_html( $m['year'] ); ?></div>
                    <div class="ist-timeline__dot" aria-hidden="true"></div>
                    <div class="ist-timeline__body"><?php echo esc_html( $m['event'] ); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Team ──────────────────────────────────────────────────── -->
    <div class="ist-section ist-section--light">
        <div class="ist-container">
            <div class="ist-section-header" style="text-align:center;margin-bottom:var(--space-xl);">
                <span class="ist-section-eyebrow"><?php esc_html_e( 'The People', 'infinity-sky' ); ?></span>
                <h2><?php esc_html_e( 'Meet Your Guides & Team', 'infinity-sky' ); ?></h2>
                <p style="max-width:560px;margin:12px auto 0;color:var(--ist-text-light);">
                    <?php esc_html_e( 'Every member of our team is Nepali, licensed, and personally passionate about sharing their homeland with you.', 'infinity-sky' ); ?>
                </p>
            </div>
            <div class="ist-team-grid" data-stagger>
                <?php foreach ( $team as $member ) : ?>
                <div class="ist-team-card" data-fade>
                    <div class="ist-team-card__img-wrap">
                        <img src="<?php echo esc_url( $member['img'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>"
                             loading="lazy" width="300" height="300">
                        <span class="ist-team-card__exp"><?php echo esc_html( $member['exp'] ); ?></span>
                    </div>
                    <div class="ist-team-card__info">
                        <h3><?php echo esc_html( $member['name'] ); ?></h3>
                        <p class="ist-team-card__role"><?php echo esc_html( $member['role'] ); ?></p>
                        <p class="ist-team-card__bio"><?php echo esc_html( $member['bio'] ); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Certifications ────────────────────────────────────────── -->
    <div class="ist-section">
        <div class="ist-container" style="text-align:center;">
            <h2 style="margin-bottom:var(--space-lg);"><?php esc_html_e( 'Licensed, Certified & Trusted', 'infinity-sky' ); ?></h2>
            <div class="ist-about-certs">
                <?php
                $certs = [
                    [ 'icon' => '🏛', 'label' => 'Nepal Tourism Board', 'sub' => 'Licensed Tour Operator' ],
                    [ 'icon' => '🧗', 'label' => 'Nepal Mountaineering Assoc.', 'sub' => 'NMA Affiliated' ],
                    [ 'icon' => '🏔', 'label' => 'TAAN Member', 'sub' => 'Trekking Agencies Assoc.' ],
                    [ 'icon' => '⭐', 'label' => 'TripAdvisor', 'sub' => 'Certificate of Excellence 2024' ],
                    [ 'icon' => '🛡', 'label' => 'CIWEC Certified', 'sub' => 'Wilderness Medical Training' ],
                ];
                foreach ( $certs as $c ) : ?>
                <div class="ist-about-cert">
                    <span class="ist-about-cert__icon"><?php echo $c['icon']; ?></span>
                    <strong><?php echo esc_html( $c['label'] ); ?></strong>
                    <span><?php echo esc_html( $c['sub'] ); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── CTA ───────────────────────────────────────────────────── -->
    <div class="ist-section ist-section--dark" style="text-align:center;">
        <div class="ist-container" style="max-width:600px;">
            <h2><?php esc_html_e( "Ready to Trek with Nepal's Best?", 'infinity-sky' ); ?></h2>
            <p style="color:rgba(255,255,255,0.7);margin-block:var(--space-sm) var(--space-lg);">
                <?php esc_html_e( 'Tell us your dream trek and we\'ll build a custom itinerary — free, within 24 hours.', 'infinity-sky' ); ?>
            </p>
            <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
                <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary btn-lg">
                    <?php esc_html_e( 'Plan My Trip', 'infinity-sky' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-outline btn-lg">
                    <?php esc_html_e( 'Browse Packages', 'infinity-sky' ); ?>
                </a>
            </div>
        </div>
    </div>

</div><!-- /.ist-about-page -->

<?php get_footer(); ?>
