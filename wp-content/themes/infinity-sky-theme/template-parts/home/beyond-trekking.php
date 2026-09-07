<?php
/**
 * Beyond Trekking — Activities in Nepal.
 * Driven by the ist_activity CPT (wp-admin → Activities) so content
 * editors can add/reorder/edit activities and photos without code changes.
 */

$activities_query = new WP_Query( [
    'post_type'      => 'ist_activity',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );

if ( ! $activities_query->have_posts() ) return;

$ist_activity_category_icons = [
    'adventure' => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>',
    'culture'   => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/></svg>',
    'wildlife'  => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12c0-3 1.5-5 4-5s4 2 4 5-1.5 5-4 5-4-2-4-5z"/></svg>',
];
?>

<section class="ist-section ist-section--light ist-beyond-trekking" id="beyond-trekking" aria-labelledby="beyond-trekking-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <span class="ist-section-eyebrow"><?php esc_html_e( 'Beyond Trekking', 'infinity-sky' ); ?></span>
            <h2 class="ist-section-heading" id="beyond-trekking-heading">
                <?php esc_html_e( 'Activities in Nepal', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading">
                <?php esc_html_e( 'Not just treks — explore culture, adventure, and wildlife across Nepal. Perfect as a standalone trip or combined with your trek.', 'infinity-sky' ); ?>
            </p>
        </div>

        <div class="ist-activity-grid" data-stagger>
            <?php while ( $activities_query->have_posts() ) : $activities_query->the_post();
                $activity_id = get_the_ID();
                $location    = get_field( 'ist_activity_location', $activity_id );
                $duration    = get_field( 'ist_activity_duration', $activity_id );
                $price       = get_field( 'ist_activity_price',    $activity_id );
                $custom_link = get_field( 'ist_activity_link',     $activity_id );
                $link        = $custom_link ?: get_permalink();
                $image       = ist_image_or_logo( $activity_id, 'ist-card' );

                $terms       = get_the_terms( $activity_id, 'ist_activity_category' );
                $category    = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
                $category_key= strtolower( $category );
                $icon        = $ist_activity_category_icons[ $category_key ] ?? $ist_activity_category_icons['adventure'];
                ?>

            <article class="ist-activity-card">
                <a href="<?php echo esc_url( $link ); ?>" class="ist-activity-card__media<?php echo $image['is_logo'] ? ' ist-activity-card__media--fallback' : ''; ?>">
                    <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy">
                    <span class="ist-activity-card__gradient" aria-hidden="true"></span>

                    <?php if ( $category ) : ?>
                    <span class="ist-activity-card__category">
                        <?php echo $icon; ?>
                        <?php echo esc_html( $category ); ?>
                    </span>
                    <?php endif; ?>

                    <div class="ist-activity-card__overlay-content">
                        <h3 class="ist-activity-card__title"><?php the_title(); ?></h3>
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
                    </div>
                </a>

                <div class="ist-activity-card__footer">
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

            <?php endwhile; wp_reset_postdata(); ?>
        </div><!-- /.ist-activity-grid -->

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary btn-lg">
                <?php esc_html_e( 'Combine With Your Trek →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>
