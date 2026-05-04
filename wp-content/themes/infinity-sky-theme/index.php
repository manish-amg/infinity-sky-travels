<?php
/**
 * Fallback template — WordPress requires this file.
 * Real templates: front-page.php, page-*.php, single-ist_package.php, etc.
 */

get_header();
?>

<main class="ist-main" style="margin-top: var(--nav-height); padding-block: var(--space-2xl);">
    <div class="ist-container">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h1><?php the_title(); ?></h1>
                    <div class="ist-content"><?php the_content(); ?></div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No content found.', 'infinity-sky' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
