<?php
/**
 * Section 3: Popular domestic routes — driven by the ist_route CPT
 * (wp-admin → Domestic Routes) so content editors can add/reorder/edit
 * routes and photos without touching code.
 */

$routes_query = new WP_Query( [
    'post_type'      => 'ist_route',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );
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

        <?php if ( $routes_query->have_posts() ) : ?>
        <div class="ist-routes-grid" data-stagger>
            <?php while ( $routes_query->have_posts() ) : $routes_query->the_post();
                $route_id    = get_the_ID();
                $from_code   = get_field( 'ist_route_from_code', $route_id );
                $to_code     = get_field( 'ist_route_to_code',   $route_id );
                $from_name   = get_field( 'ist_route_from_name', $route_id ) ?: 'Kathmandu';
                $to_name     = get_field( 'ist_route_to_name',   $route_id ) ?: get_the_title();
                $duration    = get_field( 'ist_route_duration',  $route_id );
                $price       = get_field( 'ist_route_price',     $route_id );
                $airlines    = get_field( 'ist_route_airlines',  $route_id );
                $airline_list= $airlines ? array_map( 'trim', explode( ',', $airlines ) ) : [];
                $image       = ist_image_or_logo( $route_id, 'ist-card' );
                ?>

            <a href="<?php echo esc_url( home_url( '/flights?from=' . urlencode( $from_code ) . '&to=' . urlencode( $to_code ) ) ); ?>"
               class="ist-route-card"
               data-fade
               aria-label="<?php printf( esc_attr__( 'Search flights from %1$s to %2$s', 'infinity-sky' ), $from_name, $to_name ); ?>">

                <div class="ist-route-card__image<?php echo $image['is_logo'] ? ' ist-route-card__image--fallback' : ''; ?>" aria-hidden="true">
                    <img src="<?php echo esc_url( $image['url'] ); ?>"
                         alt="<?php echo esc_attr( $image['alt'] ); ?>"
                         loading="lazy"
                         width="600" height="400">
                </div>
                <div class="ist-route-card__overlay" aria-hidden="true"></div>

                <div class="ist-route-card__content">
                    <?php if ( $airline_list ) : ?>
                    <div class="ist-route-card__airlines" aria-label="<?php esc_attr_e( 'Airlines', 'infinity-sky' ); ?>">
                        <?php foreach ( $airline_list as $airline ) : ?>
                            <span class="ist-route-card__airline"><?php echo esc_html( $airline ); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="ist-route-card__route"><?php echo esc_html( strtoupper( $from_code . ' → ' . $to_code ) ); ?></div>
                    <div class="ist-route-card__airports">
                        <?php echo esc_html( $from_name ); ?> &rarr; <?php echo esc_html( $to_name ); ?>
                    </div>

                    <div class="ist-route-card__meta">
                        <?php if ( $price ) : ?>
                        <span class="ist-route-card__price">
                            <?php esc_html_e( 'from', 'infinity-sky' ); ?>
                            <strong><?php echo esc_html( ist_format_price( $price ) ); ?></strong>
                        </span>
                        <?php endif; ?>
                        <?php if ( $duration ) : ?>
                        <span class="ist-route-card__duration">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?php echo esc_html( $duration ); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

            </a>

            <?php endwhile; wp_reset_postdata(); ?>
        </div><!-- /.ist-routes-grid -->
        <?php endif; ?>

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/flights' ) ); ?>" class="btn-outline--dark btn-outline">
                <?php esc_html_e( 'Search All Routes →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>
