<?php
/**
 * Package Hero — full-bleed image, key stats overlay, breadcrumb.
 * Passed via get_template_part $args.
 */

$hero_url       = $args['hero_url']       ?? 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1920&q=80&auto=format&fit=crop';
$price          = $args['price']          ?? 950;
$duration_days  = $args['duration_days']  ?? 12;
$duration_nights= $args['duration_nights'] ?? 11;
$difficulty     = $args['difficulty']     ?? 'moderate';
$rating         = $args['rating']         ?? 4.9;
$review_count   = $args['review_count']   ?? 47;
$region_name    = $args['region_name']    ?? '';
$key_highlight  = $args['key_highlight']  ?? '';
$flight_from    = $args['flight_from']    ?? 'KTM';
$flight_to      = $args['flight_to']      ?? '';

$badge_labels = [
    'easy'        => __( 'Easy',        'infinity-sky' ),
    'moderate'    => __( 'Moderate',    'infinity-sky' ),
    'challenging' => __( 'Challenging', 'infinity-sky' ),
    'strenuous'   => __( 'Strenuous',   'infinity-sky' ),
];
$difficulty_label = $badge_labels[ $difficulty ] ?? ucfirst( $difficulty );

$wa_text = rawurlencode( 'Hi! I\'d like to book the ' . get_the_title() . ' package (ID: ' . get_the_ID() . '). Please send me details and availability.' );
?>

<div class="ist-pkg-hero jarallax" data-jarallax data-speed="0.5"
     style="background-image:url('<?php echo esc_url( $hero_url ); ?>');"
     aria-label="<?php echo esc_attr( get_the_title() ); ?>">

    <div class="ist-pkg-hero__overlay" aria-hidden="true"></div>

    <div class="ist-container ist-pkg-hero__content">

        <!-- Breadcrumb -->
        <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>

        <!-- Region tag -->
        <?php if ( $region_name ) : ?>
        <div class="ist-pkg-hero__region">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <?php echo esc_html( $region_name ); ?>
        </div>
        <?php endif; ?>

        <h1 class="ist-pkg-hero__title"><?php the_title(); ?></h1>

        <?php if ( $key_highlight ) : ?>
        <p class="ist-pkg-hero__subtitle"><?php echo esc_html( $key_highlight ); ?></p>
        <?php endif; ?>

        <!-- Rating row -->
        <div class="ist-pkg-hero__meta">
            <div class="ist-pkg-hero__stars" aria-label="<?php echo esc_attr( number_format( $rating, 1 ) . ' out of 5 stars' ); ?>">
                <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $s <= floor( $rating ) ? 'var(--ist-orange)' : ( $s - 0.5 <= $rating ? 'var(--ist-orange)' : 'rgba(255,255,255,0.3)' ); ?>" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <?php endfor; ?>
                <span><?php echo esc_html( number_format( $rating, 1 ) ); ?> (<?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'infinity-sky' ); ?>)</span>
            </div>
            <span class="ist-pkg-hero__difficulty-badge badge-<?php echo esc_attr( $difficulty ); ?>">
                <?php echo esc_html( $difficulty_label ); ?>
            </span>
        </div>

        <!-- Key stats strip -->
        <div class="ist-pkg-hero__stats">
            <div class="ist-pkg-hero__stat">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <div>
                    <strong><?php echo esc_html( $duration_days ); ?> <?php esc_html_e( 'Days', 'infinity-sky' ); ?></strong>
                    <span><?php echo esc_html( $duration_nights ); ?> <?php esc_html_e( 'Nights', 'infinity-sky' ); ?></span>
                </div>
            </div>
            <div class="ist-pkg-hero__stat">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <div>
                    <strong><?php esc_html_e( 'From', 'infinity-sky' ); ?> <?php echo esc_html( $flight_from ); ?></strong>
                    <span><?php esc_html_e( 'Start/End', 'infinity-sky' ); ?></span>
                </div>
            </div>
            <?php if ( $flight_to ) : ?>
            <div class="ist-pkg-hero__stat">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z"/></svg>
                <div>
                    <strong><?php echo esc_html( $flight_from . ' → ' . $flight_to ); ?></strong>
                    <span><?php esc_html_e( 'Domestic flight', 'infinity-sky' ); ?></span>
                </div>
            </div>
            <?php endif; ?>
            <div class="ist-pkg-hero__stat ist-pkg-hero__stat--price">
                <div>
                    <strong class="ist-pkg-hero__price">$<?php echo number_format( $price ); ?></strong>
                    <span><?php esc_html_e( 'per person', 'infinity-sky' ); ?></span>
                </div>
            </div>
        </div>

        <!-- CTAs -->
        <div class="ist-pkg-hero__ctas">
            <a href="<?php echo esc_url( home_url( '/plan-my-trip?package=' . get_the_ID() ) ); ?>" class="btn-primary btn-lg">
                <?php esc_html_e( 'Book This Trek', 'infinity-sky' ); ?>
            </a>
            <a href="https://wa.me/9779810597893?text=<?php echo $wa_text; ?>"
               class="btn-outline btn-lg" target="_blank" rel="noopener noreferrer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                <?php esc_html_e( 'WhatsApp Us', 'infinity-sky' ); ?>
            </a>
        </div>

    </div><!-- /.ist-pkg-hero__content -->
</div><!-- /.ist-pkg-hero -->
