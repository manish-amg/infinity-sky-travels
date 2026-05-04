<?php
/**
 * Plan My Trip — hero section.
 */
?>
<div class="ist-plan-hero jarallax" data-jarallax data-speed="0.5"
     style="background-image:url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1920&q=80&auto=format&fit=crop');">
    <div class="ist-plan-hero__overlay" aria-hidden="true"></div>
    <div class="ist-container" style="position:relative;z-index:1;">
        <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
        <h1 class="ist-text-white"><?php esc_html_e( 'Plan My Custom Trip', 'infinity-sky' ); ?></h1>
        <p class="ist-plan-hero__sub">
            <?php esc_html_e( 'Tell us your dream. We\'ll handle every detail — no extra charge for customisation.', 'infinity-sky' ); ?>
        </p>
        <div class="ist-plan-hero__pills">
            <span><?php esc_html_e( '✓ Free custom itinerary', 'infinity-sky' ); ?></span>
            <span><?php esc_html_e( '✓ 24-hour response', 'infinity-sky' ); ?></span>
            <span><?php esc_html_e( '✓ Best price guarantee', 'infinity-sky' ); ?></span>
            <span><?php esc_html_e( '✓ Licensed guides', 'infinity-sky' ); ?></span>
        </div>
    </div>
</div>
