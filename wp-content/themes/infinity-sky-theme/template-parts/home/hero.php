<?php
/**
 * Section 1: Full-screen parallax hero with floating flight search dock.
 */
?>

<section class="ist-hero jarallax"
         data-jarallax
         data-speed="0.6"
         data-video-src=""
         aria-label="<?php esc_attr_e( 'Hero — Infinity Sky Travels', 'infinity-sky' ); ?>"
         style="background-image: url('https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1920&q=80&auto=format&fit=crop'); background-size: cover; background-position: center;">

    <div class="ist-hero__overlay" aria-hidden="true"></div>

    <div class="ist-hero__content">

        <p class="ist-hero__pre" data-fade>
            <?php esc_html_e( 'THAMEL, KATHMANDU', 'infinity-sky' ); ?>
        </p>

        <h1 class="ist-hero__title" data-fade data-fade-delay="1">
            <?php esc_html_e( 'Your Sky. Your Nepal. Your Adventure.', 'infinity-sky' ); ?>
        </h1>

        <p class="ist-hero__subtitle" data-fade data-fade-delay="2">
            <?php esc_html_e( 'Book Domestic Flights + Trekking Packages for Independent Travellers', 'infinity-sky' ); ?>
        </p>

        <div class="ist-hero__actions" data-fade data-fade-delay="3">
            <a href="<?php echo esc_url( home_url( '/flights' ) ); ?>" class="btn-primary btn-lg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path d="M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z"/>
                </svg>
                <?php esc_html_e( 'Search Flights', 'infinity-sky' ); ?> &rarr;
            </a>
            <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-outline btn-lg">
                <?php esc_html_e( 'Browse Packages', 'infinity-sky' ); ?>
            </a>
        </div>

    </div><!-- /.ist-hero__content -->

    <!-- Floating flight search bar docked at hero bottom -->
    <div class="ist-hero__search-dock" data-fade data-fade-delay="4">
        <?php get_template_part( 'template-parts/global/flight-search-bar', null, [ 'style' => 'hero' ] ); ?>
    </div>

</section>
