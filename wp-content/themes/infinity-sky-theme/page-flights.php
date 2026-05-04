<?php
/**
 * Template Name: Flights Search
 * Flights search results page — compact search bar + sidebar filters + results list.
 */

get_header();

// Read URL params passed from homepage search bar
$from      = strtoupper( sanitize_text_field( $_GET['from']        ?? 'KTM' ) );
$to        = strtoupper( sanitize_text_field( $_GET['to']          ?? '' ) );
$date      = sanitize_text_field( $_GET['date']        ?? '' );
$ret_date  = sanitize_text_field( $_GET['return_date'] ?? '' );
$adults    = absint( $_GET['adults']    ?? 1 );
$children  = absint( $_GET['children']  ?? 0 );
$infants   = absint( $_GET['infants']   ?? 0 );
$trip_type = sanitize_key( $_GET['trip_type'] ?? 'oneway' );
$airports  = ist_get_nepal_airports();
$airlines  = ist_get_nepal_airlines();

$from_name = $airports[ $from ] ?? $from;
$to_name   = $to ? ( $airports[ $to ] ?? $to ) : '';
?>

<div class="ist-flights-page">

    <!-- ── Hero / compact search bar ─────────────────────────── -->
    <div class="ist-flights-hero">
        <div class="ist-container">
            <div class="ist-flights-hero__content">
                <h1>
                    <?php if ( $from && $to ) : ?>
                        <?php echo esc_html( $from_name ); ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;color:var(--ist-orange)" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        <?php echo esc_html( $to_name ); ?>
                        <?php if ( $date ) echo ' · ' . esc_html( date( 'D, M j, Y', strtotime( $date ) ) ); ?>
                    <?php else : ?>
                        <?php esc_html_e( 'Search Nepal Domestic Flights', 'infinity-sky' ); ?>
                    <?php endif; ?>
                </h1>
                <?php get_template_part( 'template-parts/global/flight-search-bar', null, [ 'style' => 'compact' ] ); ?>
            </div>
        </div>
    </div>

    <!-- ── Main layout: sidebar + results ────────────────────── -->
    <div class="ist-container">
        <div class="ist-flights-layout">

            <!-- ── LEFT: Filters sidebar ─────────────────────── -->
            <aside class="ist-flights-sidebar" aria-label="<?php esc_attr_e( 'Filter flights', 'infinity-sky' ); ?>">
                <div class="ist-filter-heading">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true" style="vertical-align:middle;margin-right:6px;"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                    <?php esc_html_e( 'Filter Results', 'infinity-sky' ); ?>
                </div>

                <!-- Airline filter -->
                <div class="ist-filter-group">
                    <div class="ist-filter-group__title"><?php esc_html_e( 'Airline', 'infinity-sky' ); ?></div>
                    <?php foreach ( $airlines as $code => $name ) : ?>
                    <label class="ist-filter-check">
                        <input type="checkbox" class="ist-filter-airline" value="<?php echo esc_attr( $code ); ?>" checked>
                        <?php echo esc_html( $name ); ?>
                    </label>
                    <?php endforeach; ?>
                </div>

                <!-- Price range -->
                <div class="ist-filter-group">
                    <div class="ist-filter-group__title"><?php esc_html_e( 'Max Price (USD)', 'infinity-sky' ); ?></div>
                    <input type="range" class="ist-price-range" id="ist-price-range"
                           min="50" max="500" value="500" step="10"
                           aria-label="<?php esc_attr_e( 'Maximum price', 'infinity-sky' ); ?>">
                    <div class="ist-price-labels">
                        <span>$50</span>
                        <span id="ist-price-label"><strong>$500</strong></span>
                    </div>
                </div>

                <!-- Time of day -->
                <div class="ist-filter-group">
                    <div class="ist-filter-group__title"><?php esc_html_e( 'Departure Time', 'infinity-sky' ); ?></div>
                    <label class="ist-filter-check">
                        <input type="checkbox" class="ist-filter-time" value="morning" checked>
                        🌅 <?php esc_html_e( 'Morning (6am–12pm)', 'infinity-sky' ); ?>
                    </label>
                    <label class="ist-filter-check">
                        <input type="checkbox" class="ist-filter-time" value="afternoon" checked>
                        ☀️ <?php esc_html_e( 'Afternoon (12pm–6pm)', 'infinity-sky' ); ?>
                    </label>
                    <label class="ist-filter-check">
                        <input type="checkbox" class="ist-filter-time" value="evening" checked>
                        🌇 <?php esc_html_e( 'Evening (6pm+)', 'infinity-sky' ); ?>
                    </label>
                </div>

                <!-- Stops -->
                <div class="ist-filter-group">
                    <div class="ist-filter-group__title"><?php esc_html_e( 'Stops', 'infinity-sky' ); ?></div>
                    <label class="ist-filter-check">
                        <input type="checkbox" class="ist-filter-stops" value="direct" checked>
                        <?php esc_html_e( 'Direct only', 'infinity-sky' ); ?>
                    </label>
                    <label class="ist-filter-check">
                        <input type="checkbox" class="ist-filter-stops" value="1stop" checked>
                        <?php esc_html_e( '1 Stop', 'infinity-sky' ); ?>
                    </label>
                </div>

                <!-- Reset -->
                <button type="button" id="ist-reset-filters" class="btn-outline--dark btn-outline btn-sm" style="width:100%;justify-content:center;margin-top:8px;">
                    <?php esc_html_e( 'Reset Filters', 'infinity-sky' ); ?>
                </button>

            </aside><!-- /.ist-flights-sidebar -->

            <!-- ── RIGHT: Results ─────────────────────────────── -->
            <main class="ist-flights-results" aria-label="<?php esc_attr_e( 'Flight results', 'infinity-sky' ); ?>">

                <!-- Results bar -->
                <div class="ist-results-bar">
                    <div class="ist-results-count" id="ist-results-count" aria-live="polite">
                        <?php if ( $from && $to && $date ) : ?>
                            <?php esc_html_e( 'Searching…', 'infinity-sky' ); ?>
                        <?php else : ?>
                            <?php esc_html_e( 'Enter a route above to search flights.', 'infinity-sky' ); ?>
                        <?php endif; ?>
                    </div>
                    <select class="ist-input ist-sort-select" id="ist-sort-results" aria-label="<?php esc_attr_e( 'Sort results', 'infinity-sky' ); ?>">
                        <option value="price_asc"><?php  esc_html_e( 'Price: Low → High', 'infinity-sky' ); ?></option>
                        <option value="price_desc"><?php esc_html_e( 'Price: High → Low', 'infinity-sky' ); ?></option>
                        <option value="time_asc"><?php   esc_html_e( 'Departs: Earliest', 'infinity-sky' ); ?></option>
                        <option value="duration"><?php   esc_html_e( 'Duration: Shortest', 'infinity-sky' ); ?></option>
                    </select>
                </div>

                <!-- Results container — populated by flight-results.js -->
                <div id="ist-results-container">
                    <?php if ( $from && $to && $date ) : ?>
                    <div class="ist-flights-loading" id="ist-loading-state">
                        <div class="ist-spinner"></div>
                        <p><?php esc_html_e( 'Searching all airlines for the best rates…', 'infinity-sky' ); ?></p>
                    </div>
                    <?php else : ?>
                    <div class="ist-flights-empty">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="1.5" style="margin-bottom:16px;" aria-hidden="true">
                            <path d="M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z"/>
                        </svg>
                        <h3><?php esc_html_e( 'Where would you like to fly?', 'infinity-sky' ); ?></h3>
                        <p><?php esc_html_e( 'Use the search bar above to find available flights on all Nepal domestic airlines.', 'infinity-sky' ); ?></p>
                        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                            <a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=LUA&date=' . date('Y-m-d', strtotime('+7 days')) . '&adults=1' ) ); ?>" class="btn-primary">
                                KTM → Lukla (EBC)
                            </a>
                            <a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=PKR&date=' . date('Y-m-d', strtotime('+7 days')) . '&adults=1' ) ); ?>" class="btn-outline--dark btn-outline">
                                KTM → Pokhara
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div><!-- /#ist-results-container -->

                <!-- Load more -->
                <div id="ist-load-more-wrap" style="text-align:center;margin-top:var(--space-lg);display:none;">
                    <button type="button" id="ist-load-more" class="btn-outline--dark btn-outline">
                        <?php esc_html_e( 'Load More Flights', 'infinity-sky' ); ?>
                    </button>
                </div>

            </main><!-- /.ist-flights-results -->

        </div><!-- /.ist-flights-layout -->
    </div><!-- /.ist-container -->

    <!-- ── Manual Quote + WhatsApp CTA strip ─────────────────── -->
    <div class="ist-flights-cta-strip">
        <div class="ist-container">
            <div class="ist-flights-cta-strip__inner">
                <div>
                    <h3><?php esc_html_e( "Can't find your flight?", 'infinity-sky' ); ?></h3>
                    <p><?php esc_html_e( 'Our team can manually check availability and book mountain flights not always available online.', 'infinity-sky' ); ?></p>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <button type="button" id="ist-open-manual-quote" class="btn-primary">
                        <?php esc_html_e( 'Request Manual Quote', 'infinity-sky' ); ?>
                    </button>
                    <a href="https://wa.me/9779810597893?text=<?php echo rawurlencode( 'Hi! I need help booking a domestic flight in Nepal.' ); ?>"
                       class="btn-outline" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'WhatsApp Us', 'infinity-sky' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div><!-- /.ist-flights-page -->

<style>
/* Page-level overrides */
.ist-flights-cta-strip {
    background: var(--ist-dark-2);
    padding-block: var(--space-xl);
    margin-top: var(--space-2xl);
}
.ist-flights-cta-strip__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-lg);
    flex-wrap: wrap;
}
.ist-flights-cta-strip h3 { color: var(--ist-white); font-size: 1.2rem; margin-bottom: 6px; }
.ist-flights-cta-strip p  { color: rgba(255,255,255,0.65); font-size: 14px; }

/* Inject search params for JS */
</style>

<!-- Pass PHP URL params to JS -->
<script>
window.istFlightParams = {
    from:       '<?php echo esc_js( $from ); ?>',
    to:         '<?php echo esc_js( $to ); ?>',
    date:       '<?php echo esc_js( $date ); ?>',
    return_date:'<?php echo esc_js( $ret_date ); ?>',
    adults:     <?php echo (int) $adults; ?>,
    children:   <?php echo (int) $children; ?>,
    infants:    <?php echo (int) $infants; ?>,
    trip_type:  '<?php echo esc_js( $trip_type ); ?>',
    autoSearch: <?php echo ( $from && $to && $date ) ? 'true' : 'false'; ?>,
};
</script>

<?php get_footer(); ?>
