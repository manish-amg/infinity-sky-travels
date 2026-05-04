<?php
/**
 * Reusable package card — used in homepage, packages page, and shortcodes.
 * Must be called within a WP_Query loop or with global $post set.
 */

$post_id     = get_the_ID();
$price       = get_field( 'ist_price', $post_id );
$duration    = get_field( 'ist_duration_days', $post_id );
$nights      = get_field( 'ist_duration_nights', $post_id );
$difficulty  = get_field( 'ist_difficulty_level', $post_id );
$highlight   = get_field( 'ist_key_highlight_line', $post_id );
$altitude    = get_field( 'ist_max_altitude', $post_id );
$season      = get_field( 'ist_best_season', $post_id );
$highlights  = get_field( 'ist_highlights', $post_id );

$img_id  = get_post_thumbnail_id( $post_id );
$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'ist-portrait' ) : 'https://source.unsplash.com/400x540/?nepal,trekking,mountains';
$img_alt = $img_id ? get_post_field( 'post_excerpt', $img_id ) : get_the_title();
$link    = get_permalink();
?>
<article class="ist-package-card" itemscope itemtype="https://schema.org/TouristTrip">
    <div class="ist-package-card__image">
        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ?: get_the_title() ); ?>" loading="lazy" itemprop="image">
    </div>
    <div class="ist-package-card__gradient"></div>

    <div class="ist-package-card__content">
        <div class="ist-package-card__meta">
            <?php if ( $difficulty ) echo ist_difficulty_badge( $difficulty ); ?>
            <?php if ( $duration ) : ?>
                <span class="ist-package-card__duration"><?php echo esc_html( $duration ); ?> Days<?php echo $nights ? ' / ' . esc_html( $nights ) . ' Nights' : ''; ?></span>
            <?php endif; ?>
        </div>

        <h3 class="ist-package-card__title" itemprop="name">
            <a href="<?php echo esc_url( $link ); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
        </h3>

        <?php if ( $highlight ) : ?>
            <p class="ist-package-card__highlight"><?php echo esc_html( $highlight ); ?></p>
        <?php endif; ?>

        <?php if ( $price ) : ?>
            <div class="ist-package-card__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <meta itemprop="priceCurrency" content="USD">
                <meta itemprop="price" content="<?php echo esc_attr( $price ); ?>">
                from <?php echo esc_html( ist_format_price( $price ) ); ?><span>/person</span>
            </div>
        <?php endif; ?>

        <div class="ist-package-card__actions">
            <a href="<?php echo esc_url( $link ); ?>" class="btn-primary btn-sm"><?php esc_html_e( 'View Package', 'infinity-sky' ); ?></a>
            <a href="https://wa.me/9779810597893?text=<?php echo rawurlencode( 'Hi! I\'m interested in the ' . get_the_title() . ' package.' ); ?>" class="btn-outline btn-sm" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Quick Enquiry', 'infinity-sky' ); ?></a>
        </div>
    </div>

    <?php if ( ! empty( $highlights ) ) : ?>
    <div class="ist-package-card__hover-overlay">
        <ul class="ist-package-card__highlights-list">
            <?php foreach ( array_slice( $highlights, 0, 5 ) as $h ) : ?>
                <li><?php echo esc_html( $h['highlight'] ); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</article>
