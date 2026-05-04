<?php
/**
 * Section 3: Popular domestic routes — 6 cards in 3-col grid.
 */

$routes = [
    [
        'from'       => 'KTM',
        'to'         => 'LUA',
        'label'      => 'KTM → LUKLA',
        'from_name'  => 'Kathmandu',
        'to_name'    => 'Lukla',
        'duration'   => '35 min',
        'price'      => '$89',
        'airlines'   => [ 'Tara Air', 'Summit Air' ],
        'image'      => 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=800&q=75&auto=format&fit=crop',
        'image_alt'  => 'Tenzing-Hillary Airport Lukla Nepal',
    ],
    [
        'from'       => 'KTM',
        'to'         => 'PKR',
        'label'      => 'KTM → POKHARA',
        'from_name'  => 'Kathmandu',
        'to_name'    => 'Pokhara',
        'duration'   => '25 min',
        'price'      => '$79',
        'airlines'   => [ 'Buddha Air', 'Yeti Airlines' ],
        'image'      => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=75&auto=format&fit=crop',
        'image_alt'  => 'Pokhara Nepal Phewa Lake mountains',
    ],
    [
        'from'       => 'KTM',
        'to'         => 'MEY',
        'label'      => 'KTM → CHITWAN',
        'from_name'  => 'Kathmandu',
        'to_name'    => 'Bharatpur (Chitwan)',
        'duration'   => '20 min',
        'price'      => '$69',
        'airlines'   => [ 'Buddha Air' ],
        'image'      => 'https://images.unsplash.com/photo-1530289753786-a8a28a6c4de1?w=800&q=75&auto=format&fit=crop',
        'image_alt'  => 'Chitwan National Park Nepal rhino',
    ],
    [
        'from'       => 'KTM',
        'to'         => 'BIR',
        'label'      => 'KTM → BIRATNAGAR',
        'from_name'  => 'Kathmandu',
        'to_name'    => 'Biratnagar',
        'duration'   => '40 min',
        'price'      => '$95',
        'airlines'   => [ 'Buddha Air', 'Yeti Airlines' ],
        'image'      => 'https://images.unsplash.com/photo-1469521669194-babb45599def?w=800&q=75&auto=format&fit=crop',
        'image_alt'  => 'Eastern Nepal Himalayas aerial',
    ],
    [
        'from'       => 'KTM',
        'to'         => 'KEP',
        'label'      => 'KTM → NEPALGUNJ',
        'from_name'  => 'Kathmandu',
        'to_name'    => 'Nepalgunj',
        'duration'   => '55 min',
        'price'      => '$110',
        'airlines'   => [ 'Buddha Air', 'Yeti Airlines' ],
        'image'      => 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=800&q=75&auto=format&fit=crop',
        'image_alt'  => 'Western Nepal landscape',
    ],
    [
        'from'       => 'KTM',
        'to'         => 'JKR',
        'label'      => 'KTM → JANAKPUR',
        'from_name'  => 'Kathmandu',
        'to_name'    => 'Janakpur',
        'duration'   => '35 min',
        'price'      => '$85',
        'airlines'   => [ 'Yeti Airlines' ],
        'image'      => 'https://images.unsplash.com/photo-1562778612-e1e0cda9915c?w=800&q=75&auto=format&fit=crop',
        'image_alt'  => 'Janakpur Dham temple Nepal',
    ],
];
?>

<section class="ist-section ist-section--light" id="popular-routes" aria-labelledby="routes-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <h2 class="ist-section-heading" id="routes-heading">
                <?php esc_html_e( 'Popular Domestic Routes', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading">
                <?php esc_html_e( 'Real-time flight search across all Nepal domestic airlines. Book in minutes.', 'infinity-sky' ); ?>
            </p>
        </div>

        <div class="ist-routes-grid" data-stagger>
            <?php foreach ( $routes as $route ) : ?>

            <a href="<?php echo esc_url( home_url( '/flights?from=' . $route['from'] . '&to=' . $route['to'] ) ); ?>"
               class="ist-route-card"
               data-fade
               aria-label="<?php printf( esc_attr__( 'Search flights from %s to %s', 'infinity-sky' ), $route['from_name'], $route['to_name'] ); ?>">

                <div class="ist-route-card__image" aria-hidden="true">
                    <img src="<?php echo esc_url( $route['image'] ); ?>"
                         alt="<?php echo esc_attr( $route['image_alt'] ); ?>"
                         loading="lazy"
                         width="600" height="400">
                </div>
                <div class="ist-route-card__overlay" aria-hidden="true"></div>

                <div class="ist-route-card__content">
                    <div class="ist-route-card__airlines" aria-label="<?php esc_attr_e( 'Airlines', 'infinity-sky' ); ?>">
                        <?php foreach ( $route['airlines'] as $airline ) : ?>
                            <span class="ist-route-card__airline"><?php echo esc_html( $airline ); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="ist-route-card__route"><?php echo esc_html( $route['label'] ); ?></div>
                    <div class="ist-route-card__airports">
                        <?php echo esc_html( $route['from_name'] ); ?> &rarr; <?php echo esc_html( $route['to_name'] ); ?>
                    </div>

                    <div class="ist-route-card__meta">
                        <span class="ist-route-card__price">
                            <?php esc_html_e( 'from', 'infinity-sky' ); ?>
                            <strong><?php echo esc_html( $route['price'] ); ?></strong>
                        </span>
                        <span class="ist-route-card__duration">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php echo esc_html( $route['duration'] ); ?>
                        </span>
                    </div>
                </div>

            </a>

            <?php endforeach; ?>
        </div><!-- /.ist-routes-grid -->

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/flights' ) ); ?>" class="btn-outline--dark btn-outline">
                <?php esc_html_e( 'Search All Routes →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>
