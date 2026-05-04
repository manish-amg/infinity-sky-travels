<?php
/**
 * Package Sidebar — sticky booking widget.
 */

$price          = $args['price']          ?? 950;
$duration_days  = $args['duration_days']  ?? 12;
$duration_nights= $args['duration_nights'] ?? 11;
$difficulty     = $args['difficulty']     ?? 'moderate';
$group_min      = $args['group_min']      ?? 1;
$group_max      = $args['group_max']      ?? 12;
$best_season    = $args['best_season']    ?? 'March–May, Sep–Nov';
$start_location = $args['start_location'] ?? 'Kathmandu';
$end_location   = $args['end_location']   ?? 'Kathmandu';
$flight_from    = $args['flight_from']    ?? 'KTM';
$flight_to      = $args['flight_to']      ?? '';
$traveller_type = $args['traveller_type'] ?? [];

$wa_text = rawurlencode( 'Hi! I\'m interested in the ' . get_the_title() . ' package. Please share availability and pricing.' );

$type_icons = [
    'backpacker' => '🎒',
    'mid-range'  => '🧭',
    'luxury'     => '⭐',
];
?>

<aside class="ist-pkg-sidebar" aria-label="<?php esc_attr_e( 'Book this package', 'infinity-sky' ); ?>">

    <!-- Price widget -->
    <div class="ist-pkg-sidebar__widget ist-pkg-sidebar__widget--price">
        <div class="ist-pkg-sidebar__price-row">
            <div>
                <p class="ist-pkg-sidebar__price-label"><?php esc_html_e( 'Price from', 'infinity-sky' ); ?></p>
                <p class="ist-pkg-sidebar__price">$<?php echo number_format( $price ); ?></p>
                <p class="ist-pkg-sidebar__price-sub"><?php esc_html_e( 'per person, all inclusive', 'infinity-sky' ); ?></p>
            </div>
            <?php if ( $traveller_type ) : ?>
            <div class="ist-pkg-sidebar__types">
                <?php foreach ( (array) $traveller_type as $type ) :
                    $icon = $type_icons[ $type ] ?? '✦'; ?>
                <span class="ist-pkg-sidebar__type-pill" title="<?php echo esc_attr( ucfirst( $type ) ); ?>">
                    <?php echo esc_html( $icon ); ?>
                </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pax selector -->
        <div class="ist-pkg-sidebar__pax">
            <label class="ist-pkg-sidebar__field-label" for="sidebar-pax"><?php esc_html_e( 'No. of Travellers', 'infinity-sky' ); ?></label>
            <div class="ist-pkg-sidebar__stepper">
                <button type="button" class="ist-pkg-sidebar__step-btn" id="sidebar-pax-minus" aria-label="<?php esc_attr_e( 'Decrease', 'infinity-sky' ); ?>">−</button>
                <input type="number" id="sidebar-pax" class="ist-pkg-sidebar__step-val" value="2"
                       min="<?php echo esc_attr( $group_min ); ?>" max="<?php echo esc_attr( $group_max ); ?>"
                       aria-label="<?php esc_attr_e( 'Number of travellers', 'infinity-sky' ); ?>">
                <button type="button" class="ist-pkg-sidebar__step-btn" id="sidebar-pax-plus" aria-label="<?php esc_attr_e( 'Increase', 'infinity-sky' ); ?>">+</button>
            </div>
        </div>

        <!-- Live total -->
        <div class="ist-pkg-sidebar__total">
            <span><?php esc_html_e( 'Estimated total:', 'infinity-sky' ); ?></span>
            <strong id="sidebar-total">$<?php echo number_format( $price * 2 ); ?></strong>
        </div>

        <!-- Date -->
        <div class="ist-pkg-sidebar__field">
            <label class="ist-pkg-sidebar__field-label" for="sidebar-date"><?php esc_html_e( 'Preferred Start Date', 'infinity-sky' ); ?></label>
            <input type="text" id="sidebar-date" class="ist-input" placeholder="<?php esc_attr_e( 'Select date…', 'infinity-sky' ); ?>"
                   data-flatpickr data-min-date="today" autocomplete="off">
        </div>

        <a href="<?php echo esc_url( home_url( '/plan-my-trip?package=' . get_the_ID() ) ); ?>"
           class="btn-primary btn-lg ist-pkg-sidebar__cta" id="ist-sidebar-book-btn">
            <?php esc_html_e( 'Book This Trek', 'infinity-sky' ); ?>
        </a>

        <a href="https://wa.me/9779810597893?text=<?php echo $wa_text; ?>"
           class="btn-outline ist-pkg-sidebar__wa" target="_blank" rel="noopener noreferrer">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            <?php esc_html_e( 'Ask via WhatsApp', 'infinity-sky' ); ?>
        </a>

        <p class="ist-pkg-sidebar__guarantee">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--ist-success)" stroke-width="2.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <?php esc_html_e( 'Best price guarantee. No hidden fees.', 'infinity-sky' ); ?>
        </p>
    </div>

    <!-- Quick info widget -->
    <div class="ist-pkg-sidebar__widget ist-pkg-sidebar__widget--info">
        <h4 class="ist-pkg-sidebar__info-title"><?php esc_html_e( 'Trip Summary', 'infinity-sky' ); ?></h4>

        <dl class="ist-pkg-sidebar__dl">

            <div class="ist-pkg-sidebar__dl-row">
                <dt><?php esc_html_e( 'Duration', 'infinity-sky' ); ?></dt>
                <dd><?php echo esc_html( "$duration_days days / $duration_nights nights" ); ?></dd>
            </div>

            <div class="ist-pkg-sidebar__dl-row">
                <dt><?php esc_html_e( 'Difficulty', 'infinity-sky' ); ?></dt>
                <dd><?php echo ist_difficulty_badge( $difficulty ); ?></dd>
            </div>

            <div class="ist-pkg-sidebar__dl-row">
                <dt><?php esc_html_e( 'Best Season', 'infinity-sky' ); ?></dt>
                <dd><?php echo esc_html( $best_season ); ?></dd>
            </div>

            <div class="ist-pkg-sidebar__dl-row">
                <dt><?php esc_html_e( 'Group Size', 'infinity-sky' ); ?></dt>
                <dd><?php echo esc_html( "$group_min – $group_max pax" ); ?></dd>
            </div>

            <div class="ist-pkg-sidebar__dl-row">
                <dt><?php esc_html_e( 'Start / End', 'infinity-sky' ); ?></dt>
                <dd><?php echo esc_html( $start_location . ' / ' . $end_location ); ?></dd>
            </div>

            <?php if ( $flight_to ) : ?>
            <div class="ist-pkg-sidebar__dl-row">
                <dt><?php esc_html_e( 'Domestic Flights', 'infinity-sky' ); ?></dt>
                <dd><?php echo esc_html( $flight_from . ' ↔ ' . $flight_to ); ?></dd>
            </div>
            <?php endif; ?>

        </dl>
    </div>

    <!-- Trust badges -->
    <div class="ist-pkg-sidebar__widget ist-pkg-sidebar__widget--trust">
        <div class="ist-pkg-sidebar__trust-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span><?php esc_html_e( 'Licensed by Nepal Tourism Board', 'infinity-sky' ); ?></span>
        </div>
        <div class="ist-pkg-sidebar__trust-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            <span><?php esc_html_e( 'Free cancellation up to 60 days', 'infinity-sky' ); ?></span>
        </div>
        <div class="ist-pkg-sidebar__trust-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span><?php esc_html_e( '24/7 in-trek emergency support', 'infinity-sky' ); ?></span>
        </div>
    </div>

</aside><!-- /.ist-pkg-sidebar -->

<script>
(function(){
    var paxInput  = document.getElementById('sidebar-pax');
    var totalEl   = document.getElementById('sidebar-total');
    var bookBtn   = document.getElementById('ist-sidebar-book-btn');
    var basePrice = <?php echo (float) $price; ?>;

    function updateTotal() {
        var pax   = parseInt( paxInput.value, 10 ) || 1;
        var total = basePrice * pax;
        totalEl.textContent = '$' + total.toLocaleString();
        var url = new URL( bookBtn.href );
        url.searchParams.set( 'pax', pax );
        var date = document.getElementById('sidebar-date');
        if ( date && date.value ) url.searchParams.set( 'date', date.value );
        bookBtn.href = url.toString();
    }

    document.getElementById('sidebar-pax-minus').addEventListener('click', function(){
        var v = parseInt( paxInput.value, 10 ) || 2;
        paxInput.value = Math.max( <?php echo (int) $group_min; ?>, v - 1 );
        updateTotal();
    });
    document.getElementById('sidebar-pax-plus').addEventListener('click', function(){
        var v = parseInt( paxInput.value, 10 ) || 2;
        paxInput.value = Math.min( <?php echo (int) $group_max; ?>, v + 1 );
        updateTotal();
    });
    paxInput.addEventListener('input', updateTotal);
    document.getElementById('sidebar-date').addEventListener('change', updateTotal);
    updateTotal();
})();
</script>
