<?php
/**
 * Template Name: Booking Confirmed
 * Shown after successful flight booking or trip request.
 */

get_header();

$type    = sanitize_key( $_GET['type'] ?? 'flight' );
$ref     = strtoupper( sanitize_text_field( $_GET['ref'] ?? '' ) );
$package = sanitize_text_field( $_GET['package'] ?? '' );

$is_flight = ( $type === 'flight' );
$is_trip   = ( $type === 'trip' );
?>

<div class="ist-confirmed-page">

    <div class="ist-container" style="max-width:780px;padding-block:var(--space-2xl);">

        <!-- Tick animation -->
        <div class="ist-confirmed-icon" aria-hidden="true">
            <svg class="ist-confirmed-checkmark" viewBox="0 0 52 52" width="80" height="80">
                <circle class="ist-confirmed-checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path   class="ist-confirmed-checkmark__check"  fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <!-- Heading -->
        <h1 class="ist-confirmed-title">
            <?php if ( $is_trip ) :
                esc_html_e( 'Trip Request Received!', 'infinity-sky' );
            else :
                esc_html_e( 'Booking Confirmed!', 'infinity-sky' );
            endif; ?>
        </h1>

        <p class="ist-confirmed-sub">
            <?php if ( $is_trip ) :
                esc_html_e( "We've received your custom trip request and will have a personalised itinerary in your inbox within 24 hours.", 'infinity-sky' );
            else :
                esc_html_e( "Your booking is confirmed. A confirmation email with e-tickets and travel notes has been sent to your inbox.", 'infinity-sky' );
            endif; ?>
        </p>

        <!-- Reference number -->
        <?php if ( $ref ) : ?>
        <div class="ist-confirmed-ref">
            <span class="ist-confirmed-ref__label"><?php esc_html_e( 'Reference Number', 'infinity-sky' ); ?></span>
            <span class="ist-confirmed-ref__value"><?php echo esc_html( $ref ); ?></span>
        </div>
        <?php endif; ?>

        <!-- What happens next -->
        <div class="ist-confirmed-steps">
            <h2><?php esc_html_e( 'What Happens Next', 'infinity-sky' ); ?></h2>
            <div class="ist-confirmed-steps__grid">
                <?php
                if ( $is_trip ) {
                    $next_steps = [
                        [ 'icon' => '📧', 'title' => __( 'Check Your Email',     'infinity-sky' ), 'desc' => __( 'A confirmation email has been sent. Our team will follow up with a custom itinerary within 24 hours.', 'infinity-sky' ) ],
                        [ 'icon' => '📋', 'title' => __( 'Review Your Proposal', 'infinity-sky' ), 'desc' => __( "You'll receive a day-by-day itinerary with pricing. Request any changes — no extra cost.", 'infinity-sky' ) ],
                        [ 'icon' => '✅', 'title' => __( 'Confirm & Book',        'infinity-sky' ), 'desc' => __( 'Happy with the plan? Pay a small deposit to lock in your dates and we\'ll handle everything else.', 'infinity-sky' ) ],
                    ];
                } else {
                    $next_steps = [
                        [ 'icon' => '📧', 'title' => __( 'Check Your Email',       'infinity-sky' ), 'desc' => __( 'Your e-ticket and booking summary have been sent. Check your spam folder if not received within 5 minutes.', 'infinity-sky' ) ],
                        [ 'icon' => '📱', 'title' => __( 'Save Your Reference',    'infinity-sky' ), 'desc' => __( 'Keep your reference number handy. You\'ll need it at the airport and for any changes.', 'infinity-sky' ) ],
                        [ 'icon' => '✈',  'title' => __( 'Arrive Ready to Trek',   'infinity-sky' ), 'desc' => __( 'Arrive at the domestic terminal 45 minutes before your flight. Mountain flights are weather-dependent.', 'infinity-sky' ) ],
                    ];
                }
                foreach ( $next_steps as $step ) : ?>
                <div class="ist-confirmed-step">
                    <div class="ist-confirmed-step__icon"><?php echo $step['icon']; ?></div>
                    <h3><?php echo esc_html( $step['title'] ); ?></h3>
                    <p><?php echo esc_html( $step['desc'] ); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Important info box (flights only) -->
        <?php if ( $is_flight ) : ?>
        <div class="ist-confirmed-notice">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                <strong><?php esc_html_e( 'Mountain Flight Advisory', 'infinity-sky' ); ?></strong>
                <p><?php esc_html_e( 'Flights to mountain airports (Lukla, Jomsom, Phaplu, etc.) are subject to weather cancellation. We recommend building one buffer day into your itinerary. Our team will contact you if your flight is disrupted.', 'infinity-sky' ); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- CTA actions -->
        <div class="ist-confirmed-actions">
            <?php if ( $is_trip ) : ?>
            <a href="https://wa.me/9779810597893?text=<?php echo rawurlencode( "Hi! I just submitted a custom trip request (ref: $ref). Looking forward to your itinerary!" ); ?>"
               class="btn-primary" target="_blank" rel="noopener noreferrer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                <?php esc_html_e( 'Message Us on WhatsApp', 'infinity-sky' ); ?>
            </a>
            <?php endif; ?>
            <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-outline--dark btn-outline">
                <?php esc_html_e( 'Browse More Packages', 'infinity-sky' ); ?>
            </a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-outline--dark btn-outline">
                <?php esc_html_e( 'Return to Home', 'infinity-sky' ); ?>
            </a>
        </div>

    </div><!-- /.ist-container -->

</div><!-- /.ist-confirmed-page -->

<style>
/* ── Booking Confirmed Page ──────────────────────────────────────────── */
.ist-confirmed-page {
    margin-top: var(--nav-height);
    min-height: 80vh;
    background: var(--ist-light);
}
.ist-confirmed-page .ist-container { text-align: center; }

.ist-confirmed-icon { margin-bottom: var(--space-lg); }
.ist-confirmed-checkmark {
    animation: scalein 0.5s ease-out both;
}
@keyframes scalein { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.ist-confirmed-checkmark__circle {
    stroke: var(--ist-success);
    stroke-width: 2;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}
.ist-confirmed-checkmark__check {
    stroke: var(--ist-success);
    stroke-width: 3;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.4s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
}
@keyframes stroke { 100% { stroke-dashoffset: 0; } }

.ist-confirmed-title { font-size: clamp(1.8rem, 4vw, 2.5rem); margin-bottom: 12px; }
.ist-confirmed-sub   { font-size: 1.05rem; color: var(--ist-text-light); max-width: 540px; margin: 0 auto var(--space-lg); line-height: 1.7; }

.ist-confirmed-ref {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    background: var(--ist-white);
    border: 2px solid var(--ist-orange);
    border-radius: var(--radius-md);
    padding: var(--space-md) var(--space-xl);
    margin-bottom: var(--space-xl);
}
.ist-confirmed-ref__label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--ist-text-light); }
.ist-confirmed-ref__value { font-family: var(--font-heading); font-size: 1.6rem; font-weight: 900; color: var(--ist-orange); letter-spacing: 2px; }

.ist-confirmed-steps { margin-bottom: var(--space-xl); text-align: left; background: var(--ist-white); border-radius: var(--radius-lg); padding: var(--space-xl); }
.ist-confirmed-steps h2 { text-align: center; margin-bottom: var(--space-lg); }
.ist-confirmed-steps__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-lg); }
@media (max-width: 767px) { .ist-confirmed-steps__grid { grid-template-columns: 1fr; } }
.ist-confirmed-step { text-align: center; }
.ist-confirmed-step__icon { font-size: 36px; margin-bottom: 12px; }
.ist-confirmed-step h3 { font-size: 1rem; margin-bottom: 8px; }
.ist-confirmed-step p  { font-size: 13px; color: var(--ist-text-light); line-height: 1.6; }

.ist-confirmed-notice {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: rgba(232,117,26,0.08);
    border-left: 4px solid var(--ist-orange);
    border-radius: var(--radius-md);
    padding: var(--space-md);
    text-align: left;
    margin-bottom: var(--space-xl);
}
.ist-confirmed-notice svg { flex-shrink: 0; color: var(--ist-orange); margin-top: 2px; }
.ist-confirmed-notice strong { display: block; margin-bottom: 6px; }
.ist-confirmed-notice p { font-size: 13px; color: var(--ist-text-light); line-height: 1.6; margin: 0; }

.ist-confirmed-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
</style>

<?php get_footer(); ?>
