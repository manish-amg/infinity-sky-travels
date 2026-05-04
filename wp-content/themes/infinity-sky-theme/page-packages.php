<?php
/**
 * Template Name: Packages
 * Package listing with AJAX filter bar.
 */

get_header();
?>

<div class="ist-packages-page">

    <!-- ── Hero ──────────────────────────────────────────────────── -->
    <div class="ist-page-hero jarallax"
         data-jarallax data-speed="0.5"
         style="background-image:url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80&auto=format&fit=crop');">
        <div class="ist-page-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;padding-bottom:var(--space-xl);">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>
            <h1 class="ist-text-white">
                <?php esc_html_e( 'Trekking Packages Nepal', 'infinity-sky' ); ?>
            </h1>
            <p style="color:rgba(255,255,255,0.75);font-size:1.1rem;max-width:560px;margin-top:8px;">
                <?php esc_html_e( 'Every trek. Every region. Every budget. Handpicked for independent travellers.', 'infinity-sky' ); ?>
            </p>
        </div>
    </div>

    <!-- ── Sticky filter bar ──────────────────────────────────────── -->
    <div class="ist-packages-filter-bar" id="ist-filter-bar" role="search" aria-label="<?php esc_attr_e( 'Filter packages', 'infinity-sky' ); ?>">
        <div class="ist-container">
            <div class="ist-filter-bar__inner">

                <!-- Duration -->
                <div class="ist-filter-bar__group">
                    <label class="ist-filter-bar__label" for="filter-duration"><?php esc_html_e( 'Duration', 'infinity-sky' ); ?></label>
                    <select id="filter-duration" class="ist-filter-bar__select" data-filter="duration">
                        <option value=""><?php esc_html_e( 'All Durations', 'infinity-sky' ); ?></option>
                        <option value="under-7"><?php esc_html_e( 'Under 7 days', 'infinity-sky' ); ?></option>
                        <option value="7-10"><?php   esc_html_e( '7–10 days',    'infinity-sky' ); ?></option>
                        <option value="11-14"><?php  esc_html_e( '11–14 days',   'infinity-sky' ); ?></option>
                        <option value="15-plus"><?php esc_html_e( '15+ days',    'infinity-sky' ); ?></option>
                    </select>
                </div>

                <!-- Difficulty -->
                <div class="ist-filter-bar__group">
                    <label class="ist-filter-bar__label" for="filter-difficulty"><?php esc_html_e( 'Difficulty', 'infinity-sky' ); ?></label>
                    <select id="filter-difficulty" class="ist-filter-bar__select" data-filter="difficulty">
                        <option value=""><?php  esc_html_e( 'All Levels',    'infinity-sky' ); ?></option>
                        <option value="easy"><?php        esc_html_e( 'Easy',        'infinity-sky' ); ?></option>
                        <option value="moderate"><?php    esc_html_e( 'Moderate',    'infinity-sky' ); ?></option>
                        <option value="challenging"><?php esc_html_e( 'Challenging', 'infinity-sky' ); ?></option>
                        <option value="strenuous"><?php   esc_html_e( 'Strenuous',   'infinity-sky' ); ?></option>
                    </select>
                </div>

                <!-- Region -->
                <div class="ist-filter-bar__group">
                    <label class="ist-filter-bar__label" for="filter-region"><?php esc_html_e( 'Region', 'infinity-sky' ); ?></label>
                    <select id="filter-region" class="ist-filter-bar__select" data-filter="region">
                        <option value=""><?php        esc_html_e( 'All Regions',   'infinity-sky' ); ?></option>
                        <option value="everest"><?php  esc_html_e( 'Everest',       'infinity-sky' ); ?></option>
                        <option value="annapurna"><?php esc_html_e( 'Annapurna',   'infinity-sky' ); ?></option>
                        <option value="langtang"><?php  esc_html_e( 'Langtang',    'infinity-sky' ); ?></option>
                        <option value="mustang"><?php   esc_html_e( 'Mustang',     'infinity-sky' ); ?></option>
                        <option value="western-nepal"><?php esc_html_e( 'Western Nepal', 'infinity-sky' ); ?></option>
                    </select>
                </div>

                <!-- Budget -->
                <div class="ist-filter-bar__group">
                    <label class="ist-filter-bar__label" for="filter-budget"><?php esc_html_e( 'Budget', 'infinity-sky' ); ?></label>
                    <select id="filter-budget" class="ist-filter-bar__select" data-filter="budget">
                        <option value=""><?php       esc_html_e( 'Any Budget',     'infinity-sky' ); ?></option>
                        <option value="under-800"><?php   esc_html_e( 'Under $800',    'infinity-sky' ); ?></option>
                        <option value="800-1500"><?php    esc_html_e( '$800–$1,500',  'infinity-sky' ); ?></option>
                        <option value="1500-2500"><?php   esc_html_e( '$1,500–$2,500','infinity-sky' ); ?></option>
                        <option value="2500-plus"><?php   esc_html_e( '$2,500+',       'infinity-sky' ); ?></option>
                    </select>
                </div>

                <!-- Traveller type -->
                <div class="ist-filter-bar__group">
                    <label class="ist-filter-bar__label" for="filter-type"><?php esc_html_e( 'Traveller', 'infinity-sky' ); ?></label>
                    <select id="filter-type" class="ist-filter-bar__select" data-filter="type">
                        <option value=""><?php         esc_html_e( 'All Types',   'infinity-sky' ); ?></option>
                        <option value="backpacker"><?php esc_html_e( '🎒 Backpacker', 'infinity-sky' ); ?></option>
                        <option value="mid-range"><?php  esc_html_e( '🧭 Explorer',   'infinity-sky' ); ?></option>
                        <option value="luxury"><?php     esc_html_e( '⭐ Luxury',      'infinity-sky' ); ?></option>
                    </select>
                </div>

                <!-- Reset -->
                <button type="button" id="ist-filter-reset" class="ist-filter-bar__reset">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    <?php esc_html_e( 'Reset', 'infinity-sky' ); ?>
                </button>

            </div><!-- /.ist-filter-bar__inner -->

            <!-- Active filter chips -->
            <div class="ist-filter-chips" id="ist-filter-chips" aria-live="polite"></div>
        </div>
    </div>

    <!-- ── Package grid ───────────────────────────────────────────── -->
    <div class="ist-section">
        <div class="ist-container">

            <div class="ist-packages-results-bar">
                <p class="ist-packages-count" id="ist-packages-count" aria-live="polite"></p>
                <div class="ist-packages-sort">
                    <label class="ist-filter-bar__label" for="ist-packages-sort"><?php esc_html_e( 'Sort by', 'infinity-sky' ); ?></label>
                    <select id="ist-packages-sort" class="ist-filter-bar__select" style="width:auto;">
                        <option value="default"><?php    esc_html_e( 'Featured',         'infinity-sky' ); ?></option>
                        <option value="price_asc"><?php  esc_html_e( 'Price: Low → High', 'infinity-sky' ); ?></option>
                        <option value="price_desc"><?php esc_html_e( 'Price: High → Low', 'infinity-sky' ); ?></option>
                        <option value="duration"><?php   esc_html_e( 'Duration',          'infinity-sky' ); ?></option>
                    </select>
                </div>
            </div>

            <!-- Grid container — refilled by AJAX -->
            <div class="ist-package-grid" id="ist-packages-grid" data-stagger>
                <?php
                $args = [
                    'post_type'      => 'ist_package',
                    'posts_per_page' => 9,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ];
                // Pre-filter from URL params (region, difficulty, type)
                $tax_query = [];
                if ( ! empty( $_GET['region'] ) ) {
                    $tax_query[] = [ 'taxonomy' => 'ist_region', 'field' => 'slug', 'terms' => sanitize_text_field( $_GET['region'] ) ];
                }
                if ( $tax_query ) $args['tax_query'] = $tax_query;

                $q = new WP_Query( $args );
                if ( $q->have_posts() ) :
                    while ( $q->have_posts() ) : $q->the_post();
                        get_template_part( 'template-parts/global/package-card' );
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="ist-no-results" style="grid-column:1/-1;">
                        <p><?php esc_html_e( 'No packages found. Add packages in WP Admin → Packages.', 'infinity-sky' ); ?></p>
                    </div>
                <?php endif; ?>
            </div><!-- /#ist-packages-grid -->

            <!-- Pagination / Load more -->
            <div id="ist-packages-pagination" class="ist-packages-pagination" style="text-align:center;margin-top:var(--space-xl);">
                <?php if ( $q->max_num_pages > 1 ) : ?>
                <button type="button" id="ist-load-more-packages" class="btn-outline--dark btn-outline"
                        data-page="1" data-max="<?php echo esc_attr( $q->max_num_pages ); ?>">
                    <?php esc_html_e( 'Load More Packages', 'infinity-sky' ); ?>
                </button>
                <?php endif; ?>
            </div>

            <!-- Custom trip CTA -->
            <div class="ist-packages-custom-cta" data-fade>
                <div class="ist-packages-custom-cta__inner">
                    <div>
                        <h3><?php esc_html_e( "Don't see exactly what you need?", 'infinity-sky' ); ?></h3>
                        <p><?php esc_html_e( 'Tell us your dates, interests, and budget. We build a custom itinerary just for you — at no extra cost.', 'infinity-sky' ); ?></p>
                    </div>
                    <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary btn-lg">
                        <?php esc_html_e( 'Plan My Custom Trip →', 'infinity-sky' ); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div><!-- /.ist-packages-page -->

<?php get_footer(); ?>
