<?php
/**
 * Homepage template — all 10 sections loaded via template-parts.
 * No margin-top: header is transparent and overlaps the hero.
 */

get_header();
?>

<main id="ist-home" role="main">

    <?php get_template_part( 'template-parts/home/hero' ); ?>

    <?php get_template_part( 'template-parts/home/trust-bar' ); ?>

    <?php get_template_part( 'template-parts/home/popular-routes' ); ?>

    <?php get_template_part( 'template-parts/home/featured-packages' ); ?>

    <?php get_template_part( 'template-parts/home/addons-strip' ); ?>

    <?php get_template_part( 'template-parts/home/traveller-types' ); ?>

    <?php get_template_part( 'template-parts/home/why-infinity-sky' ); ?>

    <?php get_template_part( 'template-parts/home/testimonials' ); ?>

    <?php get_template_part( 'template-parts/home/blog-preview' ); ?>

</main>

<?php get_footer(); ?>
