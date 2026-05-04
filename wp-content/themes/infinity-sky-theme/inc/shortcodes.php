<?php
/**
 * Shortcodes.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// [ist_flight_search] — embeds the compact flight search bar
add_shortcode( 'ist_flight_search', function( $atts ) {
    $atts = shortcode_atts( [ 'style' => 'compact' ], $atts, 'ist_flight_search' );
    ob_start();
    get_template_part( 'template-parts/global/flight-search-bar', null, [ 'style' => $atts['style'] ] );
    return ob_get_clean();
} );

// [ist_package_cards count="3" region="everest"] — shows package cards
add_shortcode( 'ist_package_cards', function( $atts ) {
    $atts = shortcode_atts( [ 'count' => 3, 'region' => '', 'difficulty' => '' ], $atts, 'ist_package_cards' );
    $args = [
        'post_type'      => 'ist_package',
        'posts_per_page' => (int) $atts['count'],
        'post_status'    => 'publish',
    ];
    if ( $atts['region'] ) {
        $args['tax_query'] = [ [ 'taxonomy' => 'ist_region', 'field' => 'slug', 'terms' => $atts['region'] ] ];
    }
    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) return '';

    ob_start();
    echo '<div class="ist-package-grid ist-package-grid--shortcode">';
    while ( $query->have_posts() ) {
        $query->the_post();
        get_template_part( 'template-parts/global/package-card' );
    }
    wp_reset_postdata();
    echo '</div>';
    return ob_get_clean();
} );

// [ist_whatsapp_button text="WhatsApp Us"] — inline WhatsApp button
add_shortcode( 'ist_whatsapp_button', function( $atts ) {
    $atts = shortcode_atts( [ 'text' => 'WhatsApp Us' ], $atts );
    return '<a href="https://wa.me/9779810597893" class="btn-primary" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['text'] ) . '</a>';
} );

// [ist_booking_form package_id="123"] — booking inquiry form
add_shortcode( 'ist_booking_form', function( $atts ) {
    $atts = shortcode_atts( [ 'package_id' => '' ], $atts );
    ob_start();
    ?>
    <div class="ist-booking-form-wrap">
        <?php echo do_shortcode( '[contact-form-7 id="booking-inquiry" title="Booking Inquiry"]' ); ?>
    </div>
    <?php
    return ob_get_clean();
} );
