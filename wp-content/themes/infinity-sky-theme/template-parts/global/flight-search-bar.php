<?php
/**
 * Flight search bar — homepage hero dock + compact reuse on /flights.
 * Accepts $args['style'] = 'hero' | 'compact' | 'inline'.
 */

$style    = $args['style'] ?? 'hero';
$airports = ist_get_nepal_airports();

$from_val = isset( $_GET['from'] ) ? strtoupper( sanitize_text_field( $_GET['from'] ) ) : 'KTM';
$to_val   = isset( $_GET['to']   ) ? strtoupper( sanitize_text_field( $_GET['to']   ) ) : 'LUA';
$date_val = isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '';
$ret_date = isset( $_GET['return_date'] ) ? sanitize_text_field( $_GET['return_date'] ) : '';
$adults   = isset( $_GET['adults'] )   ? absint( $_GET['adults'] )   : 1;
$children = isset( $_GET['children'] ) ? absint( $_GET['children'] ) : 0;
$infants  = isset( $_GET['infants'] )  ? absint( $_GET['infants'] )  : 0;
$trip_type= isset( $_GET['trip_type'] )? sanitize_key( $_GET['trip_type'] ) : 'oneway';
?>

<div class="ist-search-bar ist-search-bar--<?php echo esc_attr( $style ); ?>" id="ist-flight-search-bar" role="search" aria-label="<?php esc_attr_e( 'Flight Search', 'infinity-sky' ); ?>">
    <div class="ist-search-bar__inner">

        <!-- Trip type toggle -->
        <div class="ist-search-bar__trip-type" role="group" aria-label="<?php esc_attr_e( 'Trip type', 'infinity-sky' ); ?>">
            <button type="button" class="ist-trip-toggle <?php echo 'oneway' === $trip_type ? 'active' : ''; ?>" data-trip="oneway">
                <?php esc_html_e( 'One Way', 'infinity-sky' ); ?>
            </button>
            <button type="button" class="ist-trip-toggle <?php echo 'roundtrip' === $trip_type ? 'active' : ''; ?>" data-trip="roundtrip">
                <?php esc_html_e( 'Round Trip', 'infinity-sky' ); ?>
            </button>
        </div>

        <form class="ist-search-bar__form" id="ist-flight-form" novalidate>
            <?php wp_nonce_field( 'ist_nonce', 'ist_nonce' ); ?>
            <input type="hidden" name="trip_type" id="ist-trip-type" value="<?php echo esc_attr( $trip_type ); ?>">

            <div class="ist-search-bar__fields">

                <!-- FROM -->
                <div class="ist-form-group ist-search-bar__from">
                    <label class="ist-label" for="ist-from">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <?php esc_html_e( 'From', 'infinity-sky' ); ?>
                    </label>
                    <select id="ist-from" name="from" class="ist-input ist-select" required>
                        <?php foreach ( $airports as $code => $name ) : ?>
                            <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $from_val, $code ); ?>>
                                <?php echo esc_html( $code . ' — ' . $name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- SWAP -->
                <button type="button" class="ist-search-bar__swap" id="ist-swap-airports" aria-label="<?php esc_attr_e( 'Swap origin and destination', 'infinity-sky' ); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M7 16V4m0 0L3 8m4-4l4 4"/><path d="M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                </button>

                <!-- TO -->
                <div class="ist-form-group ist-search-bar__to">
                    <label class="ist-label" for="ist-to">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <?php esc_html_e( 'To', 'infinity-sky' ); ?>
                    </label>
                    <select id="ist-to" name="to" class="ist-input ist-select" required>
                        <?php foreach ( $airports as $code => $name ) : ?>
                            <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $to_val, $code ); ?>>
                                <?php echo esc_html( $code . ' — ' . $name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- DEPART DATE -->
                <div class="ist-form-group ist-search-bar__date">
                    <label class="ist-label" for="ist-depart-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?php esc_html_e( 'Depart', 'infinity-sky' ); ?>
                    </label>
                    <input type="text" id="ist-depart-date" name="date" class="ist-input" placeholder="<?php esc_attr_e( 'Select date', 'infinity-sky' ); ?>" value="<?php echo esc_attr( $date_val ); ?>" data-flatpickr required autocomplete="off">
                </div>

                <!-- RETURN DATE (shown for round trip) -->
                <div class="ist-form-group ist-search-bar__return-date <?php echo 'roundtrip' !== $trip_type ? 'ist-hidden' : ''; ?>" id="ist-return-date-wrap">
                    <label class="ist-label" for="ist-return-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?php esc_html_e( 'Return', 'infinity-sky' ); ?>
                    </label>
                    <input type="text" id="ist-return-date" name="return_date" class="ist-input" placeholder="<?php esc_attr_e( 'Select date', 'infinity-sky' ); ?>" value="<?php echo esc_attr( $ret_date ); ?>" data-flatpickr autocomplete="off">
                </div>

                <!-- PASSENGERS -->
                <div class="ist-form-group ist-search-bar__passengers" style="position:relative;">
                    <label class="ist-label" for="ist-passengers-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                        <?php esc_html_e( 'Passengers', 'infinity-sky' ); ?>
                    </label>
                    <button type="button" id="ist-passengers-btn" class="ist-input ist-passengers-btn" aria-haspopup="true" aria-expanded="false">
                        <span id="ist-pax-summary"><?php printf( esc_html__( '%d Adult', 'infinity-sky' ), $adults ); ?></span>
                        <svg width="12" height="8" viewBox="0 0 12 8" aria-hidden="true"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </button>
                    <div class="ist-passengers-dropdown" id="ist-passengers-dropdown" hidden>
                        <?php
                        $pax_types = [
                            'adults'   => [ __( 'Adults', 'infinity-sky' ),   __( 'Age 12+', 'infinity-sky' ),   $adults,   1, 9 ],
                            'children' => [ __( 'Children', 'infinity-sky' ), __( 'Age 2–11', 'infinity-sky' ),  $children, 0, 9 ],
                            'infants'  => [ __( 'Infants', 'infinity-sky' ),  __( 'Under 2', 'infinity-sky' ),   $infants,  0, 9 ],
                        ];
                        foreach ( $pax_types as $type => $data ) :
                        ?>
                        <div class="ist-pax-row">
                            <div class="ist-pax-row__labels">
                                <span class="ist-pax-row__type"><?php echo esc_html( $data[0] ); ?></span>
                                <span class="ist-pax-row__age"><?php echo esc_html( $data[1] ); ?></span>
                            </div>
                            <div class="ist-stepper" data-pax-type="<?php echo esc_attr( $type ); ?>">
                                <button type="button" class="ist-stepper__btn" data-step="-1" aria-label="<?php printf( esc_attr__( 'Remove %s', 'infinity-sky' ), $data[0] ); ?>">−</button>
                                <input type="number" class="ist-stepper__input" name="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $data[2] ); ?>" min="<?php echo esc_attr( $data[3] ); ?>" max="<?php echo esc_attr( $data[4] ); ?>" readonly>
                                <button type="button" class="ist-stepper__btn" data-step="1"  aria-label="<?php printf( esc_attr__( 'Add %s', 'infinity-sky' ), $data[0] ); ?>">+</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <button type="button" class="btn-primary btn-sm" id="ist-pax-done" style="width:100%;margin-top:8px;">
                            <?php esc_html_e( 'Done', 'infinity-sky' ); ?>
                        </button>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="ist-form-group ist-search-bar__submit">
                    <button type="submit" class="btn-primary ist-search-submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        <?php esc_html_e( 'Search Flights', 'infinity-sky' ); ?>
                    </button>
                </div>

            </div><!-- /.ist-search-bar__fields -->
        </form>

    </div><!-- /.ist-search-bar__inner -->
</div>
<style>
.ist-hidden { display: none !important; }
.ist-search-bar--hero {
    background: var(--ist-white);
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    padding: 28px 28px 24px;
    max-width: 1100px;
    margin-inline: auto;
}
.ist-search-bar--compact {
    background: var(--ist-white);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-md);
    padding: 20px 20px 16px;
}
.ist-search-bar__trip-type {
    display: flex;
    gap: 0;
    margin-bottom: 18px;
    border-bottom: 2px solid var(--ist-light);
}
.ist-trip-toggle {
    padding: 8px 20px;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 0.5px;
    color: var(--ist-text-light);
    background: none;
    border: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: color 0.2s, border-color 0.2s;
}
.ist-trip-toggle.active { color: var(--ist-orange); border-bottom-color: var(--ist-orange); }
.ist-search-bar__fields {
    display: grid;
    grid-template-columns: 1fr auto 1fr 1fr 1fr 1.2fr;
    gap: 12px;
    align-items: end;
}
.ist-search-bar__swap {
    background: var(--ist-light);
    border: none;
    border-radius: 50%;
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--ist-orange);
    transition: var(--transition-fast);
    margin-bottom: 4px;
    align-self: end;
}
.ist-search-bar__swap:hover { background: var(--ist-orange); color: var(--ist-white); }
.ist-search-submit { width: 100%; justify-content: center; }
.ist-passengers-btn {
    display: flex; align-items: center; justify-content: space-between;
    cursor: pointer; text-align: left; background: var(--ist-white);
}
.ist-passengers-dropdown {
    position: absolute; top: 100%; left: 0; right: 0;
    background: var(--ist-white);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    padding: var(--space-md);
    z-index: 50;
    min-width: 260px;
    margin-top: 6px;
}
.ist-pax-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--ist-border);
}
.ist-pax-row:last-of-type { border-bottom: none; }
.ist-pax-row__type { font-family: var(--font-heading); font-weight: 600; font-size: 14px; color: var(--ist-dark); }
.ist-pax-row__age  { font-size: 12px; color: var(--ist-text-light); }
.ist-stepper { display: flex; align-items: center; gap: 8px; }
.ist-stepper__btn {
    width: 30px; height: 30px;
    background: var(--ist-light); border: none; border-radius: 50%;
    font-size: 18px; font-weight: 700; display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--ist-dark); transition: background 0.2s;
    line-height: 1;
}
.ist-stepper__btn:hover { background: var(--ist-orange); color: var(--ist-white); }
.ist-stepper__input {
    width: 36px; text-align: center; border: none; background: none;
    font-family: var(--font-heading); font-weight: 700; font-size: 16px;
    pointer-events: none;
}
</style>
