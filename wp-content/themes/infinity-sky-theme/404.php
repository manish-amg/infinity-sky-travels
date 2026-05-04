<?php
/**
 * 404 Not Found Template
 */

get_header();

// Popular packages for suggested content
$popular_packages = new WP_Query([
    'post_type'      => 'ist_package',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>

<div class="ist-404-page">
    <div class="ist-container">

        <!-- Mountain illustration -->
        <div class="ist-404-visual" aria-hidden="true">
            <svg viewBox="0 0 400 200" class="ist-404-mountains" xmlns="http://www.w3.org/2000/svg">
                <!-- Background peaks -->
                <polygon points="0,200 80,60 160,200"  fill="var(--ist-border)" opacity="0.6"/>
                <polygon points="60,200 160,30 260,200" fill="var(--ist-border)" opacity="0.8"/>
                <polygon points="200,200 310,10 400,200" fill="var(--ist-border)"/>
                <!-- Snow caps -->
                <polygon points="80,60 95,85 65,85"   fill="white"/>
                <polygon points="160,30 178,65 142,65" fill="white"/>
                <polygon points="310,10 332,52 288,52" fill="white"/>
                <!-- Clouds -->
                <ellipse cx="60"  cy="40" rx="25" ry="12" fill="white" opacity="0.7"/>
                <ellipse cx="340" cy="30" rx="30" ry="13" fill="white" opacity="0.7"/>
            </svg>
            <div class="ist-404-num">404</div>
        </div>

        <!-- Copy -->
        <h1 class="ist-404-title"><?php esc_html_e( 'Lost in the Himalayas?', 'infinity-sky' ); ?></h1>
        <p class="ist-404-desc">
            <?php esc_html_e( "The page you're looking for has wandered off the trail. Let's get you back on track.", 'infinity-sky' ); ?>
        </p>

        <!-- Quick links -->
        <div class="ist-404-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary btn-lg">
                <?php esc_html_e( '← Back to Home', 'infinity-sky' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-outline--dark btn-outline btn-lg">
                <?php esc_html_e( 'Browse Packages', 'infinity-sky' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-outline--dark btn-outline btn-lg">
                <?php esc_html_e( 'Plan My Trip', 'infinity-sky' ); ?>
            </a>
        </div>

        <!-- Search bar -->
        <div class="ist-404-search">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="ist-404-search__label" for="ist-404-s"><?php esc_html_e( 'Or search the site:', 'infinity-sky' ); ?></label>
                <div class="ist-404-search__row">
                    <input type="search" id="ist-404-s" name="s" class="ist-input"
                           placeholder="<?php esc_attr_e( 'e.g. Everest Base Camp, Annapurna…', 'infinity-sky' ); ?>"
                           value="<?php echo esc_attr( get_search_query() ); ?>"
                           aria-label="<?php esc_attr_e( 'Search', 'infinity-sky' ); ?>">
                    <button type="submit" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <?php esc_html_e( 'Search', 'infinity-sky' ); ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Suggested packages -->
        <?php if ( $popular_packages->have_posts() ) : ?>
        <div class="ist-404-packages">
            <h2><?php esc_html_e( 'Popular Treks You Might Like', 'infinity-sky' ); ?></h2>
            <div class="ist-package-grid">
                <?php while ( $popular_packages->have_posts() ) : $popular_packages->the_post();
                    get_template_part( 'template-parts/global/package-card' );
                endwhile;
                wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<style>
.ist-404-page {
    margin-top: var(--nav-height);
    padding-block: var(--space-2xl);
    text-align: center;
}
.ist-404-visual { position: relative; margin-bottom: var(--space-lg); }
.ist-404-mountains { width: 100%; max-width: 400px; height: auto; margin: 0 auto; display: block; }
.ist-404-num {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    font-family: var(--font-heading);
    font-size: clamp(5rem, 15vw, 9rem);
    font-weight: 900;
    color: var(--ist-orange);
    opacity: 0.85;
    line-height: 1;
    text-shadow: 4px 4px 0 rgba(0,0,0,0.06);
    pointer-events: none;
}
.ist-404-title { font-size: clamp(1.6rem, 4vw, 2.4rem); margin-bottom: var(--space-sm); }
.ist-404-desc  { color: var(--ist-text-light); font-size: 1.05rem; max-width: 480px; margin: 0 auto var(--space-lg); line-height: 1.7; }
.ist-404-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: var(--space-xl);
}
.ist-404-search {
    max-width: 560px;
    margin: 0 auto var(--space-2xl);
}
.ist-404-search__label {
    display: block;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 14px;
    color: var(--ist-text-light);
    margin-bottom: 10px;
}
.ist-404-search__row {
    display: flex;
    gap: 10px;
}
.ist-404-search__row .ist-input { flex: 1; }
.ist-404-packages { text-align: left; }
.ist-404-packages h2 { text-align: center; margin-bottom: var(--space-xl); }
</style>

<?php get_footer(); ?>
