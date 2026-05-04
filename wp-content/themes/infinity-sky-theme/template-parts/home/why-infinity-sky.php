<?php
/**
 * Section 7: Why Infinity Sky — split layout, animated check icons.
 */

$points = [
    [
        'icon'  => '✈',
        'title' => __( 'All Domestic Airlines in One Place', 'infinity-sky' ),
        'desc'  => __( 'Buddha Air, Yeti Airlines, Tara Air, Summit Air, Shree Airlines, and Nepal Airlines — search and book them all without switching sites.', 'infinity-sky' ),
    ],
    [
        'icon'  => '🔍',
        'title' => __( 'Real-Time Flight Search + Manual Backup', 'infinity-sky' ),
        'desc'  => __( 'Powered by Duffel API for live availability. If the API is down, our team books manually and confirms within 2 hours.', 'infinity-sky' ),
    ],
    [
        'icon'  => '📋',
        'title' => __( 'Trek Permit & Visa Assistance', 'infinity-sky' ),
        'desc'  => __( 'TIMS cards, national park fees, Restricted Area Permits (Mustang, Manaslu) — we handle the paperwork so you can focus on packing.', 'infinity-sky' ),
    ],
    [
        'icon'  => '🚐',
        'title' => __( 'Airport-to-Hotel-to-Trailhead Service', 'infinity-sky' ),
        'desc'  => __( 'Private transfers from TIA, pre-trek accommodation in Thamel, and onward transport to your trek starting point.', 'infinity-sky' ),
    ],
    [
        'icon'  => '🏢',
        'title' => __( 'Thamel Office for In-Person Support', 'infinity-sky' ),
        'desc'  => __( 'Walk into our Thamel office Mon–Sat 9am–6pm. Meet our team, pick up permits, and get face-to-face advice before you head out.', 'infinity-sky' ),
    ],
    [
        'icon'  => '📱',
        'title' => __( 'Emergency 24/7 WhatsApp Line', 'infinity-sky' ),
        'desc'  => __( 'Flight cancelled? Trail condition changed? We are always reachable on WhatsApp, even at base camp altitude.', 'infinity-sky' ),
    ],
];

$photos = [
    [ 'src' => 'https://images.unsplash.com/photo-1542401886-65d6c61db217?w=600&q=75&auto=format&fit=crop', 'alt' => 'Kathmandu Thamel Nepal travel agency' ],
    [ 'src' => 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=600&q=75&auto=format&fit=crop', 'alt' => 'Lukla airport Nepal mountain flight' ],
    [ 'src' => 'https://images.unsplash.com/photo-1581793745862-99fde7fa73d2?w=600&q=75&auto=format&fit=crop', 'alt' => 'EBC trek trekkers Nepal Himalaya' ],
    [ 'src' => 'https://images.unsplash.com/photo-1531761535209-180857e963b9?w=600&q=75&auto=format&fit=crop', 'alt' => 'Nepal mountain landscape trekking guide' ],
];
?>

<section class="ist-section ist-section--light" id="why-us" aria-labelledby="why-heading">
    <div class="ist-container">

        <div class="ist-why-grid">

            <!-- Left: photo grid -->
            <div class="ist-why-photos" data-fade="left">
                <?php foreach ( $photos as $photo ) : ?>
                    <img src="<?php echo esc_url( $photo['src'] ); ?>"
                         alt="<?php echo esc_attr( $photo['alt'] ); ?>"
                         loading="lazy"
                         width="600" height="450">
                <?php endforeach; ?>
            </div>

            <!-- Right: text content -->
            <div class="ist-why-text" data-fade="right">
                <span class="ist-tag ist-tag--orange" style="margin-bottom:var(--space-sm);display:inline-block;">
                    <?php esc_html_e( 'Why Choose Us', 'infinity-sky' ); ?>
                </span>
                <h2 class="ist-section-heading" id="why-heading" style="text-align:left;">
                    <?php esc_html_e( 'Why Infinity Sky Travels?', 'infinity-sky' ); ?>
                </h2>
                <div class="ist-divider ist-divider--left"></div>
                <p style="color:var(--ist-text-light);margin-bottom:var(--space-lg);">
                    <?php esc_html_e( 'Born in Thamel — where every Nepal adventure begins. We handle every detail from your first flight search to your final transfer home.', 'infinity-sky' ); ?>
                </p>

                <div class="ist-why-points" data-stagger>
                    <?php foreach ( $points as $point ) : ?>
                    <div class="ist-why-point" data-fade>
                        <div class="ist-why-point__icon" aria-hidden="true"><?php echo $point['icon']; ?></div>
                        <div>
                            <h3 class="ist-why-point__title"><?php echo esc_html( $point['title'] ); ?></h3>
                            <p class="ist-why-point__desc"><?php echo esc_html( $point['desc'] ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:var(--space-xl);display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="btn-primary">
                        <?php esc_html_e( 'Our Story →', 'infinity-sky' ); ?>
                    </a>
                    <a href="https://wa.me/9779810597893" class="btn-outline--dark btn-outline" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'WhatsApp Us', 'infinity-sky' ); ?>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>
