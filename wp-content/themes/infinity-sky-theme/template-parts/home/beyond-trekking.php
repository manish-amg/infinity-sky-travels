<?php
/**
 * Beyond Trekking — Activities in Nepal.
 * Driven by the ist_activity CPT (wp-admin → Activities) so content
 * editors can add/reorder/edit activities and photos without code changes.
 * Cards are shared with the /activities/ archive via activity-card.php.
 */

$activities_query = new WP_Query( [
    'post_type'      => 'ist_activity',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );

if ( ! $activities_query->have_posts() ) return;
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
            <?php while ( $activities_query->have_posts() ) : $activities_query->the_post(); ?>
                <?php get_template_part( 'template-parts/global/activity-card' ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </div><!-- /.ist-activity-grid -->

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary btn-lg">
                <?php esc_html_e( 'Combine With Your Trek →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>
