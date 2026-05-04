<?php
/**
 * Single Package Template
 */

get_header();

while ( have_posts() ) : the_post();

// Gather all ACF fields with defaults
$price          = get_field( 'price' )          ?: 950;
$duration_days  = get_field( 'duration_days' )  ?: 12;
$duration_nights= get_field( 'duration_nights' ) ?: 11;
$difficulty     = get_field( 'difficulty' )      ?: 'moderate';
$max_altitude   = get_field( 'max_altitude' )    ?: '';
$best_season    = get_field( 'best_season' )     ?: 'March–May, Sep–Nov';
$group_min      = get_field( 'group_size_min' )  ?: 1;
$group_max      = get_field( 'group_size_max' )  ?: 12;
$start_location = get_field( 'start_location' ) ?: 'Kathmandu';
$end_location   = get_field( 'end_location' )   ?: 'Kathmandu';
$highlights     = get_field( 'highlights' )      ?: [];
$short_desc     = get_field( 'short_description' ) ?: get_the_excerpt();
$gallery        = get_field( 'gallery' )         ?: [];
$includes       = get_field( 'includes' )        ?: [];
$excludes       = get_field( 'excludes' )        ?: [];
$itinerary      = get_field( 'itinerary' )       ?: [];
$faqs           = get_field( 'faqs' )            ?: [];
$flight_from    = get_field( 'flight_from' )     ?: 'KTM';
$flight_to      = get_field( 'flight_to' )       ?: '';
$requires_permit= get_field( 'requires_permit' ) ?: false;
$permit_details = get_field( 'permit_details' )  ?: '';
$rating         = get_field( 'schema_rating' )   ?: 4.9;
$review_count   = get_field( 'review_count' )    ?: 47;
$hero_image     = get_field( 'hero_image' );
$hero_url       = $hero_image['url'] ?? 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1920&q=80&auto=format&fit=crop';
$traveller_type = get_field( 'traveller_type' )  ?: [];
$key_highlight  = get_field( 'key_highlight_line' ) ?: 'An unforgettable Himalayan adventure.';

$regions = wp_get_post_terms( get_the_ID(), 'ist_region' );
$region_name = ! empty( $regions ) ? $regions[0]->name : '';
?>

<div class="ist-package-single">

    <?php get_template_part( 'template-parts/package/package-hero', null, compact(
        'hero_url','price','duration_days','duration_nights','difficulty',
        'rating','review_count','region_name','key_highlight','flight_from','flight_to'
    ) ); ?>

    <!-- ── Tab navigation ─────────────────────────────────────── -->
    <div class="ist-package-tabs-wrap" id="ist-tabs-wrap">
        <div class="ist-container">
            <nav class="ist-package-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Package sections', 'infinity-sky' ); ?>">
                <button class="ist-tab-btn ist-tab-btn--active" role="tab" data-tab="overview"    aria-selected="true"  aria-controls="tab-overview">    <?php esc_html_e( 'Overview',   'infinity-sky' ); ?></button>
                <button class="ist-tab-btn"                     role="tab" data-tab="itinerary"  aria-selected="false" aria-controls="tab-itinerary">  <?php esc_html_e( 'Itinerary',  'infinity-sky' ); ?></button>
                <button class="ist-tab-btn"                     role="tab" data-tab="inclusions" aria-selected="false" aria-controls="tab-inclusions"> <?php esc_html_e( 'Inclusions', 'infinity-sky' ); ?></button>
                <button class="ist-tab-btn"                     role="tab" data-tab="faq"        aria-selected="false" aria-controls="tab-faq">        <?php esc_html_e( 'FAQ',        'infinity-sky' ); ?></button>
                <?php if ( $flight_to ) : ?>
                <button class="ist-tab-btn"                     role="tab" data-tab="flights"    aria-selected="false" aria-controls="tab-flights">    <?php esc_html_e( 'Flights',    'infinity-sky' ); ?></button>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- ── Body: content + sidebar ────────────────────────────── -->
    <div class="ist-container">
        <div class="ist-package-body">

            <!-- ── LEFT: tab panels ─────────────────────────── -->
            <div class="ist-package-content">

                <!-- Overview -->
                <div id="tab-overview" class="ist-tab-panel ist-tab-panel--active" role="tabpanel">
                    <?php get_template_part( 'template-parts/package/package-tabs', null, compact(
                        'short_desc','highlights','gallery','max_altitude','best_season',
                        'group_min','group_max','start_location','end_location',
                        'duration_days','duration_nights','difficulty','price',
                        'requires_permit','permit_details'
                    ) ); ?>
                </div>

                <!-- Itinerary -->
                <div id="tab-itinerary" class="ist-tab-panel" role="tabpanel">
                    <?php get_template_part( 'template-parts/package/package-itinerary', null, compact( 'itinerary', 'duration_days' ) ); ?>
                </div>

                <!-- Inclusions / Exclusions -->
                <div id="tab-inclusions" class="ist-tab-panel" role="tabpanel">
                    <div class="ist-inclusions-grid">
                        <div class="ist-inclusions-col">
                            <h3 class="ist-inclusions-title ist-inclusions-title--yes">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <?php esc_html_e( "What's Included", 'infinity-sky' ); ?>
                            </h3>
                            <?php if ( $includes ) :
                                foreach ( $includes as $item ) : ?>
                                <div class="ist-inclusion-item ist-inclusion-item--yes">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span><?php echo esc_html( $item['item'] ?? $item ); ?></span>
                                </div>
                                <?php endforeach;
                            else :
                                $defaults = [ 'Airport/hotel transfers','Licensed English-speaking guide','All teahouse accommodation','Full board meals (B+L+D)','All required permits (TIMS, NP)','Domestic flights where noted','Emergency evacuation insurance','Duffel bag and sleeping bag' ];
                                foreach ( $defaults as $d ) : ?>
                                <div class="ist-inclusion-item ist-inclusion-item--yes">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span><?php echo esc_html( $d ); ?></span>
                                </div>
                                <?php endforeach;
                            endif; ?>
                        </div>

                        <div class="ist-inclusions-col">
                            <h3 class="ist-inclusions-title ist-inclusions-title--no">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <?php esc_html_e( "What's Excluded", 'infinity-sky' ); ?>
                            </h3>
                            <?php if ( $excludes ) :
                                foreach ( $excludes as $item ) : ?>
                                <div class="ist-inclusion-item ist-inclusion-item--no">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    <span><?php echo esc_html( $item['item'] ?? $item ); ?></span>
                                </div>
                                <?php endforeach;
                            else :
                                $defaults = [ 'International flights','Nepal visa fee ($30–$50)','Travel insurance (mandatory)','Personal trekking gear','Tips for guides and porters','Extra nights in Kathmandu','Alcoholic beverages','Personal shopping' ];
                                foreach ( $defaults as $d ) : ?>
                                <div class="ist-inclusion-item ist-inclusion-item--no">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    <span><?php echo esc_html( $d ); ?></span>
                                </div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                </div>

                <!-- FAQ -->
                <div id="tab-faq" class="ist-tab-panel" role="tabpanel">
                    <div class="ist-faq-list">
                        <?php if ( $faqs ) :
                            foreach ( $faqs as $i => $faq ) :
                                $q = $faq['question'] ?? '';
                                $a = $faq['answer']   ?? '';
                                if ( ! $q ) continue; ?>
                            <div class="ist-accordion-item" id="faq-<?php echo $i; ?>">
                                <button class="ist-accordion-trigger" type="button" aria-expanded="false" aria-controls="faq-body-<?php echo $i; ?>">
                                    <?php echo esc_html( $q ); ?>
                                    <svg class="ist-accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="ist-accordion-body" id="faq-body-<?php echo $i; ?>" role="region" aria-labelledby="faq-<?php echo $i; ?>">
                                    <div class="ist-accordion-body__inner"><?php echo wp_kses_post( $a ); ?></div>
                                </div>
                            </div>
                            <?php endforeach;
                        else :
                            $default_faqs = [
                                [ 'question' => 'Do I need prior trekking experience?', 'answer' => 'Moderate fitness is sufficient for most of our standard treks. We recommend training with day hikes carrying a 5–7 kg pack in the 2–3 months before departure. Our guides adjust pace to your group.' ],
                                [ 'question' => 'Is altitude sickness a concern?', 'answer' => 'Acclimatisation days are built into all itineraries above 3,500 m. Our guides carry emergency oxygen and are trained in altitude illness recognition. We always follow the "climb high, sleep low" principle.' ],
                                [ 'question' => 'What permits are required?', 'answer' => 'Most treks require a TIMS card and a National Park/Conservation Area permit. Restricted areas (Upper Mustang, Dolpo) need additional special permits. All required permits are included in your package price unless noted.' ],
                                [ 'question' => 'What is the cancellation policy?', 'answer' => '60+ days before: full refund minus admin fee. 30–59 days: 25% cancellation charge. 15–29 days: 50% charge. Under 15 days: no refund. We strongly recommend comprehensive travel insurance.' ],
                                [ 'question' => 'Can I customise this itinerary?', 'answer' => 'Absolutely — all packages can be extended, shortened, or combined. Use the Plan My Trip form or WhatsApp us to get a custom quote within 24 hours at no extra cost.' ],
                            ];
                            foreach ( $default_faqs as $i => $faq ) : ?>
                            <div class="ist-accordion-item" id="faq-<?php echo $i; ?>">
                                <button class="ist-accordion-trigger" type="button" aria-expanded="false" aria-controls="faq-body-<?php echo $i; ?>">
                                    <?php echo esc_html( $faq['question'] ); ?>
                                    <svg class="ist-accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="ist-accordion-body" id="faq-body-<?php echo $i; ?>" role="region" aria-labelledby="faq-<?php echo $i; ?>">
                                    <div class="ist-accordion-body__inner"><?php echo esc_html( $faq['answer'] ); ?></div>
                                </div>
                            </div>
                            <?php endforeach;
                        endif; ?>
                    </div>
                </div>

                <!-- Flights (optional) -->
                <?php if ( $flight_to ) : ?>
                <div id="tab-flights" class="ist-tab-panel" role="tabpanel">
                    <div class="ist-package-flights">
                        <h3><?php esc_html_e( 'Recommended Flights for This Trek', 'infinity-sky' ); ?></h3>
                        <p class="ist-package-flights__sub">
                            <?php printf(
                                esc_html__( 'The flights below cover the %1$s → %2$s leg typically included or recommended for this package.', 'infinity-sky' ),
                                '<strong>' . esc_html( $flight_from ) . '</strong>',
                                '<strong>' . esc_html( $flight_to ) . '</strong>'
                            ); ?>
                        </p>
                        <div id="ist-package-flight-results" class="ist-package-flight-results">
                            <div class="ist-flights-loading">
                                <div class="ist-spinner"></div>
                                <p><?php esc_html_e( 'Loading available flights…', 'infinity-sky' ); ?></p>
                            </div>
                        </div>
                        <div style="text-align:center;margin-top:var(--space-md);">
                            <a href="<?php echo esc_url( home_url( '/flights?from=' . $flight_from . '&to=' . $flight_to ) ); ?>" class="btn-outline--dark btn-outline">
                                <?php esc_html_e( 'See All Flights →', 'infinity-sky' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- /.ist-package-content -->

            <!-- ── RIGHT: sticky sidebar ─────────────────────── -->
            <?php get_template_part( 'template-parts/package/package-sidebar', null, compact(
                'price','duration_days','duration_nights','difficulty',
                'group_min','group_max','best_season','start_location','end_location',
                'flight_from','flight_to','traveller_type'
            ) ); ?>

        </div><!-- /.ist-package-body -->
    </div><!-- /.ist-container -->

    <!-- ── Related packages ───────────────────────────────────── -->
    <?php
    $related_args = [
        'post_type'      => 'ist_package',
        'posts_per_page' => 3,
        'post__not_in'   => [ get_the_ID() ],
        'post_status'    => 'publish',
        'orderby'        => 'rand',
    ];
    if ( $regions ) {
        $related_args['tax_query'] = [[
            'taxonomy' => 'ist_region',
            'field'    => 'term_id',
            'terms'    => wp_list_pluck( $regions, 'term_id' ),
        ]];
    }
    $related = new WP_Query( $related_args );
    if ( $related->have_posts() ) : ?>
    <div class="ist-section ist-section--light">
        <div class="ist-container">
            <h2 class="ist-section-title" style="margin-bottom:var(--space-xl);"><?php esc_html_e( 'Similar Treks You May Like', 'infinity-sky' ); ?></h2>
            <div class="ist-package-grid">
                <?php while ( $related->have_posts() ) : $related->the_post();
                    get_template_part( 'template-parts/global/package-card' );
                endwhile;
                wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /.ist-package-single -->

<script>
window.istPackageData = {
    flightFrom: '<?php echo esc_js( $flight_from ); ?>',
    flightTo:   '<?php echo esc_js( $flight_to ); ?>',
    price:      <?php echo (float) $price; ?>,
    title:      '<?php echo esc_js( get_the_title() ); ?>',
    postId:     <?php echo get_the_ID(); ?>,
};
</script>

<?php endwhile; ?>

<?php get_footer(); ?>
