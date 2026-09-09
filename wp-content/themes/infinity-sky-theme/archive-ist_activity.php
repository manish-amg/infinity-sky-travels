<?php
/**
 * Activities archive — /activities/
 * Without this file, WordPress fell back to the generic blog archive.php,
 * which showed blog categories, post dates, and "min read" labels on
 * activities. This gives activities their own proper listing page.
 */

get_header();
?>

<div class="ist-activities-archive">

    <div class="ist-page-hero jarallax"
         data-jarallax data-speed="0.5"
         style="background-image:url('https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1920&q=80&auto=format&fit=crop');">
        <div class="ist-page-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;padding-bottom:var(--space-xl);">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
            <h1 class="ist-text-white">
                <?php esc_html_e( 'Activities in Nepal', 'infinity-sky' ); ?>
            </h1>
            <p style="color:rgba(255,255,255,0.75);font-size:1.1rem;max-width:560px;margin-top:8px;">
                <?php esc_html_e( 'Not just treks — culture, adventure, and wildlife across Nepal. Book standalone or combine with your trek.', 'infinity-sky' ); ?>
            </p>
        </div>
    </div>

    <div class="ist-section ist-section--light">
        <div class="ist-container">

            <?php if ( have_posts() ) : ?>

            <div class="ist-activity-grid" data-stagger>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/global/activity-card' ); ?>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination( [
                'prev_text' => __( '← Previous', 'infinity-sky' ),
                'next_text' => __( 'Next →', 'infinity-sky' ),
            ] ); ?>

            <?php else : ?>
                <div class="ist-no-results">
                    <p><?php esc_html_e( 'No activities published yet — check back soon.', 'infinity-sky' ); ?></p>
                </div>
            <?php endif; ?>

            <div class="text-center mt-lg" data-fade>
                <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary btn-lg">
                    <?php esc_html_e( 'Combine With Your Trek →', 'infinity-sky' ); ?>
                </a>
            </div>

        </div>
    </div>

</div>

<?php get_footer(); ?>
