<?php
/**
 * Single Activity Template
 * Without this file, WordPress fell back to the generic blog single.php,
 * which showed "1 min read", an author bio box, "Share this article",
 * and a "You might also enjoy" list of unrelated blog posts — none of the
 * activity's own price/location/duration/description were shown at all.
 */

get_header();

while ( have_posts() ) : the_post();

$activity_id = get_the_ID();
$location    = get_field( 'ist_activity_location', $activity_id );
$duration    = get_field( 'ist_activity_duration', $activity_id );
$price       = get_field( 'ist_activity_price',    $activity_id );
$image       = ist_image_or_logo( $activity_id, 'ist-hero' );

$terms    = get_the_terms( $activity_id, 'ist_activity_category' );
$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

$wa_text = rawurlencode( 'Hi! I\'m interested in "' . get_the_title() . '". Please share availability and pricing.' );
?>

<div class="ist-activity-single">

    <div class="ist-page-hero jarallax"
         data-jarallax data-speed="0.5"
         style="background-image:url('<?php echo esc_url( $image['url'] ); ?>');">
        <div class="ist-page-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;padding-bottom:var(--space-xl);">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
            <?php if ( $category ) : ?>
                <span class="ist-section-eyebrow" style="color:var(--ist-orange-light);"><?php echo esc_html( $category ); ?></span>
            <?php endif; ?>
            <h1 class="ist-text-white"><?php the_title(); ?></h1>
            <div class="ist-activity-single__meta">
                <?php if ( $location ) : ?>
                <span class="ist-activity-card__meta-item" style="color:rgba(255,255,255,0.85);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo esc_html( $location ); ?>
                </span>
                <?php endif; ?>
                <?php if ( $duration ) : ?>
                <span class="ist-activity-card__meta-item" style="color:rgba(255,255,255,0.85);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo esc_html( $duration ); ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="ist-section">
        <div class="ist-container">
            <div class="ist-package-body">

                <div class="ist-package-content">
                    <?php if ( has_excerpt() ) : ?>
                        <p class="ist-activity-single__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>

                    <?php if ( get_the_content() ) : ?>
                        <div class="ist-activity-single__content">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="ist-activity-single__cta-strip">
                        <h3><?php esc_html_e( 'Perfect combined with your trek', 'infinity-sky' ); ?></h3>
                        <p><?php esc_html_e( 'Add this to any package, or book it as a standalone trip — we handle the logistics either way.', 'infinity-sky' ); ?></p>
                        <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary">
                            <?php esc_html_e( 'Plan My Trip →', 'infinity-sky' ); ?>
                        </a>
                    </div>
                </div>

                <!-- Sidebar: booking widget -->
                <aside class="ist-pkg-sidebar" aria-label="<?php esc_attr_e( 'Book this activity', 'infinity-sky' ); ?>">
                    <div class="ist-pkg-sidebar__widget ist-pkg-sidebar__widget--price">
                        <?php if ( $price ) : ?>
                        <div class="ist-pkg-sidebar__price-row">
                            <div>
                                <p class="ist-pkg-sidebar__price-label"><?php esc_html_e( 'Price from', 'infinity-sky' ); ?></p>
                                <p class="ist-pkg-sidebar__price">$<?php echo number_format( $price ); ?></p>
                                <p class="ist-pkg-sidebar__price-sub"><?php esc_html_e( 'per person', 'infinity-sky' ); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( home_url( '/plan-my-trip?activity=' . $activity_id ) ); ?>" class="btn-primary btn-lg ist-pkg-sidebar__cta">
                            <?php esc_html_e( 'Book This Activity', 'infinity-sky' ); ?>
                        </a>
                        <a href="https://wa.me/9779810597893?text=<?php echo $wa_text; ?>" class="btn-outline ist-pkg-sidebar__wa" target="_blank" rel="noopener noreferrer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            <?php esc_html_e( 'Ask via WhatsApp', 'infinity-sky' ); ?>
                        </a>
                    </div>
                </aside>

            </div>

            <!-- Related activities -->
            <?php
            $related = new WP_Query( [
                'post_type'      => 'ist_activity',
                'posts_per_page' => 3,
                'post__not_in'   => [ $activity_id ],
                'post_status'    => 'publish',
                'orderby'        => 'rand',
            ] );
            if ( $related->have_posts() ) : ?>
            <div class="ist-activity-single__related">
                <h2 class="ist-section-heading" style="text-align:left;font-size:1.5rem;"><?php esc_html_e( 'Other Activities in Nepal', 'infinity-sky' ); ?></h2>
                <div class="ist-activity-grid">
                    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                        <?php get_template_part( 'template-parts/global/activity-card' ); ?>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php endwhile; ?>

<?php get_footer(); ?>
