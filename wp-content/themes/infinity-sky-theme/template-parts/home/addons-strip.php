<?php
/**
 * Section 5: Add-on services — 6 icon cards on dark background.
 */

$addons = [
    [
        'icon'  => '✈️',
        'title' => __( 'Domestic Flight Booking', 'infinity-sky' ),
        'desc'  => __( 'All 6 Nepal airlines in one place. Real-time search with manual backup.', 'infinity-sky' ),
        'link'  => home_url( '/flights' ),
    ],
    [
        'icon'  => '🚗',
        'title' => __( 'KTM Airport Transfer', 'infinity-sky' ),
        'desc'  => __( 'Private taxi from TIA to Thamel, your hotel, or trek startpoint.', 'infinity-sky' ),
        'link'  => home_url( '/contact' ),
    ],
    [
        'icon'  => '🏨',
        'title' => __( 'Hotel & Accommodation', 'infinity-sky' ),
        'desc'  => __( 'Pre-trek and post-trek hotels in Kathmandu, Pokhara, and beyond.', 'infinity-sky' ),
        'link'  => home_url( '/contact' ),
    ],
    [
        'icon'  => '📄',
        'title' => __( 'Trek Permits & TIMS', 'infinity-sky' ),
        'desc'  => __( 'We handle TIMS cards, national park fees, and restricted area permits.', 'infinity-sky' ),
        'link'  => home_url( '/plan-my-trip' ),
    ],
    [
        'icon'  => '🧭',
        'title' => __( 'Porter & Guide Service', 'infinity-sky' ),
        'desc'  => __( 'Licensed, experienced guides and porters for every route and difficulty.', 'infinity-sky' ),
        'link'  => home_url( '/plan-my-trip' ),
    ],
    [
        'icon'  => '🎒',
        'title' => __( 'Gear Rental Kathmandu', 'infinity-sky' ),
        'desc'  => __( 'Quality trekking gear hire from our Thamel office — sleeping bags, poles, jackets.', 'infinity-sky' ),
        'link'  => home_url( '/contact' ),
    ],
];
?>

<section class="ist-section ist-section--dark ist-addons" id="services" aria-labelledby="addons-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <h2 class="ist-section-heading ist-text-white" id="addons-heading">
                <?php esc_html_e( 'Complete Nepal Travel Services', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading" style="color:rgba(255,255,255,0.6);">
                <?php esc_html_e( 'Everything you need from landing at TIA to reaching your trailhead.', 'infinity-sky' ); ?>
            </p>
        </div>

        <div class="ist-addons-grid" data-stagger>
            <?php foreach ( $addons as $addon ) : ?>
            <a href="<?php echo esc_url( $addon['link'] ); ?>" class="ist-addon-card" data-fade style="text-decoration:none;">
                <div class="ist-addon-card__icon" aria-hidden="true"><?php echo $addon['icon']; ?></div>
                <h3 class="ist-addon-card__title"><?php echo esc_html( $addon['title'] ); ?></h3>
                <p class="ist-addon-card__desc"><?php echo esc_html( $addon['desc'] ); ?></p>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary">
                <?php esc_html_e( 'Plan My Full Trip →', 'infinity-sky' ); ?>
            </a>
            <a href="https://wa.me/9779810597893" class="btn-outline" target="_blank" rel="noopener noreferrer" style="margin-left:12px;">
                <?php esc_html_e( 'WhatsApp for Custom Quote', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>
