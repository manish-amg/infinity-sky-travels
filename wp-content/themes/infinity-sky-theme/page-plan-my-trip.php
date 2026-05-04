<?php
/**
 * Template Name: Plan My Trip
 * Multi-step custom itinerary request form.
 */

get_header();

// Pre-fill from URL when arriving from a package page
$prefill_package = absint( $_GET['package'] ?? 0 );
$prefill_pax     = absint( $_GET['pax']     ?? 2 );
$prefill_date    = sanitize_text_field( $_GET['date'] ?? '' );

// Fetch packages for the interest selector
$packages_q = new WP_Query([
    'post_type'      => 'ist_package',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);
$packages = $packages_q->posts;
wp_reset_postdata();
?>

<div class="ist-plan-page">

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <?php get_template_part( 'template-parts/plan/plan-hero' ); ?>

    <!-- ── How It Works ─────────────────────────────────────────── -->
    <div class="ist-plan-how">
        <div class="ist-container">
            <div class="ist-plan-how__grid">
                <?php
                $steps = [
                    [ 'n' => '1', 'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>', 'title' => __( 'Tell Us Your Vision', 'infinity-sky' ), 'desc' => __( 'Fill in the form below — destinations, dates, budget, group size, and any special requests.', 'infinity-sky' ) ],
                    [ 'n' => '2', 'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', 'title' => __( 'We Craft Your Itinerary', 'infinity-sky' ), 'desc' => __( 'Within 24 hours our team designs a bespoke day-by-day itinerary tailored exactly to you.', 'infinity-sky' ) ],
                    [ 'n' => '3', 'icon' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>', 'title' => __( 'Confirm & Go', 'infinity-sky' ), 'desc' => __( 'Review your custom quote, make changes, pay a deposit, and your adventure is confirmed.', 'infinity-sky' ) ],
                ];
                foreach ( $steps as $s ) : ?>
                <div class="ist-plan-how__step" data-fade>
                    <div class="ist-plan-how__icon"><?php echo $s['icon']; ?></div>
                    <div class="ist-plan-how__num"><?php echo esc_html( $s['n'] ); ?></div>
                    <h3><?php echo esc_html( $s['title'] ); ?></h3>
                    <p><?php echo esc_html( $s['desc'] ); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Main: Form + Sidebar ─────────────────────────────────── -->
    <div class="ist-plan-body">
        <div class="ist-container">
            <div class="ist-plan-layout">

                <!-- ── LEFT: Multi-step form ─────────────────── -->
                <?php get_template_part( 'template-parts/plan/plan-form', null, compact(
                    'packages', 'prefill_package', 'prefill_pax', 'prefill_date'
                ) ); ?>

                <!-- ── RIGHT: Sidebar ────────────────────────── -->
                <aside class="ist-plan-sidebar">

                    <!-- Why custom? -->
                    <div class="ist-plan-sidebar__widget">
                        <h3 class="ist-plan-sidebar__title"><?php esc_html_e( 'Why Go Custom?', 'infinity-sky' ); ?></h3>
                        <?php
                        $reasons = [
                            __( 'Choose your own start date', 'infinity-sky' ),
                            __( 'Set your own pace and rest days', 'infinity-sky' ),
                            __( 'Select accommodation standard (teahouse / lodge / luxury)', 'infinity-sky' ),
                            __( 'Add or remove side trips', 'infinity-sky' ),
                            __( 'Private guide — no strangers in your group', 'infinity-sky' ),
                            __( 'Mix trekking with cultural sightseeing', 'infinity-sky' ),
                        ];
                        foreach ( $reasons as $r ) : ?>
                        <div class="ist-plan-sidebar__reason">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            <span><?php echo esc_html( $r ); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Response time pledge -->
                    <div class="ist-plan-sidebar__widget ist-plan-sidebar__widget--pledge">
                        <div class="ist-plan-sidebar__pledge-icon">⏱</div>
                        <h4><?php esc_html_e( '24-Hour Response', 'infinity-sky' ); ?></h4>
                        <p><?php esc_html_e( 'We reply to every custom trip request within 24 hours — usually much faster. You\'ll get a full proposal, not a generic brochure.', 'infinity-sky' ); ?></p>
                    </div>

                    <!-- WhatsApp instant -->
                    <div class="ist-plan-sidebar__widget ist-plan-sidebar__widget--wa">
                        <p class="ist-plan-sidebar__wa-label"><?php esc_html_e( 'Prefer to chat?', 'infinity-sky' ); ?></p>
                        <a href="https://wa.me/9779810597893?text=<?php echo rawurlencode( "Hi! I'd like to plan a custom trek with Infinity Sky Travels." ); ?>"
                           class="btn-primary" target="_blank" rel="noopener noreferrer" style="width:100%;justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            <?php esc_html_e( 'WhatsApp Us Now', 'infinity-sky' ); ?>
                        </a>
                        <p class="ist-plan-sidebar__wa-sub"><?php esc_html_e( 'Typically replies in under 15 minutes during business hours.', 'infinity-sky' ); ?></p>
                    </div>

                    <!-- Trust logos -->
                    <div class="ist-plan-sidebar__widget ist-plan-sidebar__widget--trust">
                        <p class="ist-plan-sidebar__trust-label"><?php esc_html_e( 'Certified & Trusted', 'infinity-sky' ); ?></p>
                        <div class="ist-plan-sidebar__trust-badges">
                            <span class="ist-plan-trust-badge">🏔 Nepal Tourism Board</span>
                            <span class="ist-plan-trust-badge">🧭 TAAN Member</span>
                            <span class="ist-plan-trust-badge">🛡 NMA Affiliated</span>
                            <span class="ist-plan-trust-badge">⭐ TripAdvisor 2024</span>
                        </div>
                    </div>

                </aside>

            </div><!-- /.ist-plan-layout -->
        </div>
    </div>

</div><!-- /.ist-plan-page -->

<?php get_footer(); ?>
