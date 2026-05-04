<?php
/**
 * Package Overview tab — description, highlights, photo gallery, quick facts.
 */

$short_desc     = $args['short_desc']     ?? '';
$highlights     = $args['highlights']     ?? [];
$gallery        = $args['gallery']        ?? [];
$max_altitude   = $args['max_altitude']   ?? '';
$best_season    = $args['best_season']    ?? 'March–May, Sep–Nov';
$group_min      = $args['group_min']      ?? 1;
$group_max      = $args['group_max']      ?? 12;
$start_location = $args['start_location'] ?? 'Kathmandu';
$end_location   = $args['end_location']   ?? 'Kathmandu';
$duration_days  = $args['duration_days']  ?? 12;
$duration_nights= $args['duration_nights'] ?? 11;
$difficulty     = $args['difficulty']     ?? 'moderate';
$price          = $args['price']          ?? 950;
$requires_permit= $args['requires_permit'] ?? false;
$permit_details = $args['permit_details'] ?? '';

$default_highlights = [
    'Stand at iconic high-altitude viewpoints',
    'Teahouse experience with local families',
    'Flora and fauna of the Himalayas',
    'Acclimatisation walks through authentic villages',
    'Expert licensed guide and porter support',
    'Sunrise mountain vistas',
];
if ( ! $highlights ) {
    $highlights = array_map( fn( $h ) => [ 'highlight' => $h ], $default_highlights );
}
?>

<!-- ── Description ─────────────────────────────────────────── -->
<div class="ist-pkg-overview">

    <div class="ist-pkg-overview__description">
        <?php if ( $short_desc ) : ?>
            <p><?php echo wp_kses_post( $short_desc ); ?></p>
        <?php else : ?>
            <?php the_content(); ?>
        <?php endif; ?>
    </div>

    <!-- Highlights grid -->
    <?php if ( $highlights ) : ?>
    <div class="ist-pkg-overview__highlights">
        <h3><?php esc_html_e( 'Trek Highlights', 'infinity-sky' ); ?></h3>
        <ul class="ist-pkg-highlights-list">
            <?php foreach ( $highlights as $hl ) :
                $text = is_array( $hl ) ? ( $hl['highlight'] ?? '' ) : $hl;
                if ( ! $text ) continue; ?>
            <li class="ist-pkg-highlights-list__item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                <?php echo esc_html( $text ); ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Quick facts table -->
    <div class="ist-pkg-quick-facts">
        <h3><?php esc_html_e( 'Quick Facts', 'infinity-sky' ); ?></h3>
        <div class="ist-pkg-facts-grid">

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Duration', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value"><?php echo esc_html( "$duration_days days / $duration_nights nights" ); ?></span>
            </div>

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Difficulty', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value"><?php echo ist_difficulty_badge( $difficulty ); ?></span>
            </div>

            <?php if ( $max_altitude ) : ?>
            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Max Altitude', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value"><?php echo esc_html( $max_altitude ); ?> m</span>
            </div>
            <?php endif; ?>

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Best Season', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value"><?php echo esc_html( $best_season ); ?></span>
            </div>

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Group Size', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value"><?php echo esc_html( "$group_min – $group_max" ); ?> <?php esc_html_e( 'people', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Start / End', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value"><?php echo esc_html( $start_location ); ?> / <?php echo esc_html( $end_location ); ?></span>
            </div>

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Price', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value ist-pkg-fact__value--price">$<?php echo number_format( $price ); ?> <?php esc_html_e( 'per person', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-pkg-fact">
                <span class="ist-pkg-fact__label"><?php esc_html_e( 'Permits', 'infinity-sky' ); ?></span>
                <span class="ist-pkg-fact__value">
                    <?php if ( $requires_permit ) :
                        echo esc_html( $permit_details ?: __( 'Required — included in price', 'infinity-sky' ) );
                    else :
                        esc_html_e( 'All included', 'infinity-sky' );
                    endif; ?>
                </span>
            </div>

        </div><!-- /.ist-pkg-facts-grid -->
    </div>

    <!-- Gallery grid -->
    <?php if ( $gallery ) : ?>
    <div class="ist-pkg-gallery">
        <h3><?php esc_html_e( 'Photo Gallery', 'infinity-sky' ); ?></h3>
        <div class="ist-pkg-gallery__grid">
            <?php foreach ( array_slice( $gallery, 0, 6 ) as $img ) :
                $src   = is_array( $img ) ? ( $img['url'] ?? '' ) : $img;
                $alt   = is_array( $img ) ? ( $img['alt'] ?? get_the_title() ) : get_the_title();
                if ( ! $src ) continue; ?>
            <a href="<?php echo esc_url( $src ); ?>" class="ist-pkg-gallery__item" data-lightbox="package-gallery">
                <img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /.ist-pkg-overview -->
