<?php
/**
 * Reusable activity card — used on the homepage Beyond Trekking section,
 * the Activities archive, and single activity "related" grids.
 * Must be called within a WP_Query loop or with global $post set.
 */

$ist_activity_category_icons = [
    'adventure' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>',
    'culture'   => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/></svg>',
    'wildlife'  => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12c0-3 1.5-5 4-5s4 2 4 5-1.5 5-4 5-4-2-4-5z"/></svg>',
];

$activity_id = get_the_ID();
$location    = get_field( 'ist_activity_location', $activity_id );
$duration    = get_field( 'ist_activity_duration', $activity_id );
$price       = get_field( 'ist_activity_price',    $activity_id );
$custom_link = get_field( 'ist_activity_link',     $activity_id );
$link        = $custom_link ?: get_permalink();
$image       = ist_image_or_logo( $activity_id, 'ist-card' );

$terms        = get_the_terms( $activity_id, 'ist_activity_category' );
$category     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
$category_key = strtolower( $category );
$icon         = $ist_activity_category_icons[ $category_key ] ?? $ist_activity_category_icons['adventure'];
?>
<article class="ist-activity-card">
    <a href="<?php echo esc_url( $link ); ?>" class="ist-activity-card__media<?php echo $image['is_logo'] ? ' ist-activity-card__media--fallback' : ''; ?>">
        <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" width="400" height="220">

        <?php if ( $category ) : ?>
        <span class="ist-activity-card__category">
            <?php echo $icon; ?>
            <?php echo esc_html( $category ); ?>
        </span>
        <?php endif; ?>
    </a>

    <div class="ist-activity-card__body">
        <h3 class="ist-activity-card__title">
            <a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="ist-activity-card__meta">
            <?php if ( $location ) : ?>
            <span class="ist-activity-card__meta-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html( $location ); ?>
            </span>
            <?php endif; ?>
            <?php if ( $duration ) : ?>
            <span class="ist-activity-card__meta-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo esc_html( $duration ); ?>
            </span>
            <?php endif; ?>
        </div>

        <?php if ( has_excerpt() ) : ?>
            <p class="ist-activity-card__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
        <?php endif; ?>

        <div class="ist-activity-card__row">
            <?php if ( $price ) : ?>
            <span class="ist-activity-card__price">
                <?php esc_html_e( 'from', 'infinity-sky' ); ?> <strong><?php echo esc_html( ist_format_price( $price ) ); ?></strong>
            </span>
            <?php endif; ?>
            <a href="<?php echo esc_url( $link ); ?>" class="ist-activity-card__link">
                <?php esc_html_e( 'Details', 'infinity-sky' ); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</article>
