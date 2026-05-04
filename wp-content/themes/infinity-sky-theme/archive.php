<?php
/**
 * Archive Template — blog posts, categories, tags.
 */

get_header();

$archive_title = get_the_archive_title();
$archive_desc  = get_the_archive_description();
?>

<div class="ist-archive-page">

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <div class="ist-page-hero jarallax" data-jarallax data-speed="0.5"
         style="background-image:url('https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80&auto=format&fit=crop');">
        <div class="ist-page-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;padding-bottom:var(--space-xl);">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
            <h1 class="ist-text-white"><?php echo wp_kses_post( $archive_title ); ?></h1>
            <?php if ( $archive_desc ) : ?>
            <p style="color:rgba(255,255,255,0.75);font-size:1.05rem;max-width:560px;margin-top:8px;">
                <?php echo wp_kses_post( $archive_desc ); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Category filter pills ─────────────────────────────────── -->
    <?php
    $all_cats = get_categories( [ 'hide_empty' => true ] );
    if ( $all_cats ) :
        $current_cat = is_category() ? get_queried_object() : null;
    ?>
    <div class="ist-archive-cats">
        <div class="ist-container">
            <div class="ist-archive-cats__inner">
                <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"
                   class="ist-archive-cats__pill <?php echo ! $current_cat ? 'ist-archive-cats__pill--active' : ''; ?>">
                    <?php esc_html_e( 'All Posts', 'infinity-sky' ); ?>
                </a>
                <?php foreach ( $all_cats as $c ) : ?>
                <a href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>"
                   class="ist-archive-cats__pill <?php echo ( $current_cat && $current_cat->term_id === $c->term_id ) ? 'ist-archive-cats__pill--active' : ''; ?>">
                    <?php echo esc_html( $c->name ); ?>
                    <span><?php echo esc_html( $c->count ); ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Posts grid ────────────────────────────────────────────── -->
    <div class="ist-section">
        <div class="ist-container">

            <?php if ( have_posts() ) : ?>

            <div class="ist-blog-grid" id="ist-blog-grid">
                <?php while ( have_posts() ) : the_post();
                    get_template_part( 'template-parts/global/blog-card' );
                endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="ist-archive-pagination">
                <?php
                echo paginate_links( [
                    'prev_text' => '&larr; ' . __( 'Newer Posts', 'infinity-sky' ),
                    'next_text' => __( 'Older Posts', 'infinity-sky' ) . ' &rarr;',
                    'type'      => 'list',
                ] );
                ?>
            </div>

            <?php else : ?>
            <div class="ist-no-results" style="text-align:center;padding:var(--space-2xl);">
                <h2><?php esc_html_e( 'No posts found', 'infinity-sky' ); ?></h2>
                <p><?php esc_html_e( 'Try a different category or check back soon.', 'infinity-sky' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary" style="margin-top:var(--space-md);">
                    <?php esc_html_e( '← Back to Home', 'infinity-sky' ); ?>
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- ── Newsletter CTA ─────────────────────────────────────────── -->
    <div class="ist-section ist-section--dark">
        <div class="ist-container" style="text-align:center;max-width:600px;">
            <h2><?php esc_html_e( 'Get Trek Inspiration Delivered', 'infinity-sky' ); ?></h2>
            <p style="color:rgba(255,255,255,0.7);margin-block:var(--space-sm) var(--space-lg);">
                <?php esc_html_e( 'Monthly trekking guides, seasonal tips, and exclusive deals — no spam, ever.', 'infinity-sky' ); ?>
            </p>
            <form class="ist-newsletter-form" id="ist-newsletter-form" novalidate>
                <div class="ist-newsletter-form__row">
                    <input type="email" name="email" class="ist-input" required
                           placeholder="<?php esc_attr_e( 'your@email.com', 'infinity-sky' ); ?>"
                           aria-label="<?php esc_attr_e( 'Email address', 'infinity-sky' ); ?>">
                    <button type="submit" class="btn-primary">
                        <?php esc_html_e( 'Subscribe', 'infinity-sky' ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div><!-- /.ist-archive-page -->

<?php get_footer(); ?>
