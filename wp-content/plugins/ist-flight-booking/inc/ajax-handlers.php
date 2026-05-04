<?php
/**
 * AJAX handlers for the IST Flight Booking plugin.
 * Covers: flight search, booking creation, status update, cancellation.
 */

defined( 'ABSPATH' ) || exit;

// ── Flight search (Duffel offer_requests) ────────────────────────────────────
add_action( 'wp_ajax_ist_ajax_flight_search',        'ist_fb_ajax_flight_search' );
add_action( 'wp_ajax_nopriv_ist_ajax_flight_search', 'ist_fb_ajax_flight_search' );
function ist_fb_ajax_flight_search(): void {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $params = [
        'from'         => strtoupper( sanitize_text_field( $_POST['from']         ?? 'KTM' ) ),
        'to'           => strtoupper( sanitize_text_field( $_POST['to']           ?? '' ) ),
        'date'         => sanitize_text_field( $_POST['date']         ?? '' ),
        'adults'       => absint( $_POST['adults']       ?? 1 ),
        'children'     => absint( $_POST['children']     ?? 0 ),
        'infants'      => absint( $_POST['infants']      ?? 0 ),
        'cabin_class'  => sanitize_text_field( $_POST['cabin_class']  ?? 'economy' ),
    ];

    if ( ! $params['to'] || ! $params['date'] ) {
        wp_send_json_error( 'Destination and departure date are required.' );
    }

    if ( ! defined( 'DUFFEL_API_KEY' ) || empty( DUFFEL_API_KEY ) ) {
        wp_send_json_success( [ 'offers' => ist_fb_demo_offers( $params ), 'demo' => true ] );
    }

    $result = IST_Duffel_API::search_flights( $params );
    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }

    $offers = $result['data']['offers'] ?? [];
    usort( $offers, fn( $a, $b ) => (float) $a['total_amount'] <=> (float) $b['total_amount'] );
    $offers = array_slice( $offers, 0, 20 );

    wp_send_json_success( [ 'offers' => $offers, 'demo' => false ] );
}

function ist_fb_demo_offers( array $params ): array {
    $airlines = [
        [ 'name' => 'Buddha Air',   'iata' => 'U4' ],
        [ 'name' => 'Yeti Airlines', 'iata' => 'YT' ],
        [ 'name' => 'Tara Air',     'iata' => 'TA' ],
    ];
    $demo = [];
    $base = 80;
    foreach ( $airlines as $i => $al ) {
        $price = $base + ( $i * 25 ) + rand( 0, 20 );
        $dep   = date( 'H:i', mktime( 6 + $i * 2, rand( 0, 59 ) ) );
        $arr   = date( 'H:i', mktime( 6 + $i * 2 + 1, rand( 0, 59 ) ) );
        $demo[] = [
            'id'           => 'demo-' . $i,
            'total_amount' => (string) $price,
            'total_currency' => 'USD',
            'slices' => [[
                'segments' => [[
                    'operating_carrier' => [ 'name' => $al['name'], 'iata_code' => $al['iata'] ],
                    'origin'            => [ 'iata_code' => $params['from'], 'name' => $params['from'] ],
                    'destination'       => [ 'iata_code' => $params['to'],   'name' => $params['to'] ],
                    'departing_at'      => $params['date'] . 'T' . $dep . ':00',
                    'arriving_at'       => $params['date'] . 'T' . $arr . ':00',
                    'duration'          => 'PT1H',
                ]]
            ]],
            'passengers'   => [ [ 'type' => 'adult' ] ],
            'cabin_class'  => 'economy',
            'demo'         => true,
        ];
    }
    return $demo;
}

// ── Create booking (after payment confirmation) ───────────────────────────────
add_action( 'wp_ajax_ist_create_booking',        'ist_fb_ajax_create_booking' );
add_action( 'wp_ajax_nopriv_ist_create_booking', 'ist_fb_ajax_create_booking' );
function ist_fb_ajax_create_booking(): void {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $offer_id   = sanitize_text_field( $_POST['offer_id']      ?? '' );
    $email      = sanitize_email( $_POST['email']              ?? '' );
    $phone      = sanitize_text_field( $_POST['phone']         ?? '' );
    $payment    = sanitize_text_field( $_POST['payment_method'] ?? 'manual' );
    $raw_pax    = json_decode( wp_unslash( $_POST['passengers'] ?? '[]' ), true );
    $raw_addons = json_decode( wp_unslash( $_POST['addons']     ?? '[]' ), true );

    if ( ! $email || ! $offer_id ) {
        wp_send_json_error( 'Email and offer selection are required.' );
    }

    // Sanitize passengers
    $passengers = [];
    foreach ( (array) $raw_pax as $pax ) {
        $passengers[] = [
            'title'           => sanitize_text_field( $pax['title']           ?? 'mr' ),
            'given_name'      => sanitize_text_field( $pax['given_name']      ?? '' ),
            'family_name'     => sanitize_text_field( $pax['family_name']     ?? '' ),
            'born_on'         => sanitize_text_field( $pax['born_on']         ?? '' ),
            'email'           => sanitize_email( $pax['email']               ?? $email ),
            'phone_number'    => sanitize_text_field( $pax['phone_number']    ?? $phone ),
            'gender'          => sanitize_text_field( $pax['gender']          ?? 'male' ),
            'nationality'     => sanitize_text_field( $pax['nationality']     ?? 'NP' ),
            'passport_number' => sanitize_text_field( $pax['passport_number'] ?? '' ),
        ];
    }

    $form_data = [
        'email'          => $email,
        'phone'          => $phone,
        'passengers'     => $passengers,
        'addons'         => (array) $raw_addons,
        'payment_method' => $payment,
    ];

    // Skip Duffel if no API key or demo offer
    if ( ! defined( 'DUFFEL_API_KEY' ) || empty( DUFFEL_API_KEY ) || str_starts_with( $offer_id, 'demo-' ) ) {
        $post_id = IST_Booking_Manager::create_manual( array_merge( $form_data, [
            'from'  => sanitize_text_field( $_POST['from'] ?? 'KTM' ),
            'to'    => sanitize_text_field( $_POST['to']   ?? '' ),
            'date'  => sanitize_text_field( $_POST['date'] ?? '' ),
            'total' => sanitize_text_field( $_POST['total'] ?? '0.00' ),
        ] ) );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( $post_id->get_error_message() );
        }

        $ref = get_post_meta( $post_id, '_fb_reference', true );
        wp_send_json_success( [ 'post_id' => $post_id, 'reference' => $ref, 'redirect' => ist_fb_confirmed_url( $ref, 'flight' ) ] );
    }

    // Real Duffel order
    $total    = sanitize_text_field( $_POST['total']    ?? '0.00' );
    $currency = sanitize_text_field( $_POST['currency'] ?? 'USD' );
    $duffel_order = IST_Duffel_API::create_order(
        [ $offer_id ],
        $passengers,
        [ 'total_amount' => $total, 'currency' => $currency ]
    );

    if ( is_wp_error( $duffel_order ) ) {
        wp_send_json_error( $duffel_order->get_error_message() );
    }

    $post_id = IST_Booking_Manager::create( $duffel_order, $form_data );
    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( $post_id->get_error_message() );
    }

    $ref = get_post_meta( $post_id, '_fb_reference', true );
    wp_send_json_success( [ 'post_id' => $post_id, 'reference' => $ref, 'redirect' => ist_fb_confirmed_url( $ref, 'flight' ) ] );
}

function ist_fb_confirmed_url( string $ref, string $type = 'flight' ): string {
    return add_query_arg( [ 'type' => $type, 'ref' => $ref ], home_url( '/booking-confirmed/' ) );
}

// ── Manual quote request (no Duffel) ─────────────────────────────────────────
add_action( 'wp_ajax_ist_ajax_manual_quote',        'ist_fb_ajax_manual_quote' );
add_action( 'wp_ajax_nopriv_ist_ajax_manual_quote', 'ist_fb_ajax_manual_quote' );
function ist_fb_ajax_manual_quote(): void {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $from    = strtoupper( sanitize_text_field( $_POST['from'] ?? 'KTM' ) );
    $to      = strtoupper( sanitize_text_field( $_POST['to']   ?? '' ) );
    $date    = sanitize_text_field( $_POST['date']    ?? '' );
    $adults  = absint( $_POST['adults']  ?? 1 );
    $email   = sanitize_email( $_POST['email']   ?? '' );
    $phone   = sanitize_text_field( $_POST['phone']  ?? '' );
    $name    = sanitize_text_field( $_POST['name']   ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( ! $email || ! $to || ! $date ) {
        wp_send_json_error( 'Email, destination and date are required.' );
    }

    $body = "New flight quote request:\n\n"
          . "Name:    $name\n"
          . "Email:   $email\n"
          . "Phone:   $phone\n"
          . "Route:   $from → $to\n"
          . "Date:    $date\n"
          . "Adults:  $adults\n\n"
          . "Message:\n$message";

    wp_mail(
        get_option( 'admin_email' ),
        "[Flight Quote] $from → $to on $date",
        $body
    );

    wp_mail(
        $email,
        'We received your flight quote request — Infinity Sky Travels',
        "Hi $name,\n\nThank you for your flight enquiry ($from → $to on $date).\nOur team will get back to you within 24 hours with the best available fares.\n\nBest regards,\nInfinity Sky Travels\n+977 9851234567"
    );

    wp_send_json_success( 'Quote request sent.' );
}

// ── Admin: update booking status ──────────────────────────────────────────────
add_action( 'wp_ajax_ist_fb_update_status', 'ist_fb_ajax_update_status' );
function ist_fb_ajax_update_status(): void {
    check_ajax_referer( 'ist_fb_update_status', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized.' );

    $post_id = absint( $_POST['post_id'] ?? 0 );
    $status  = sanitize_text_field( $_POST['status'] ?? '' );
    $allowed = [ 'pending', 'confirmed', 'cancelled', 'refunded', 'failed' ];

    if ( ! $post_id || ! in_array( $status, $allowed, true ) ) {
        wp_send_json_error( 'Invalid parameters.' );
    }

    IST_Booking_Manager::update_status( $post_id, $status );
    wp_send_json_success( 'Status updated.' );
}

// ── Admin: cancel booking ─────────────────────────────────────────────────────
add_action( 'wp_ajax_ist_fb_cancel_booking', 'ist_fb_ajax_cancel_booking' );
function ist_fb_ajax_cancel_booking(): void {
    check_ajax_referer( 'ist_fb_cancel', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized.' );

    $post_id = absint( $_POST['post_id'] ?? 0 );
    if ( ! $post_id ) wp_send_json_error( 'Invalid booking ID.' );

    $result = IST_Booking_Manager::cancel( $post_id );
    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }
    wp_send_json_success( 'Booking cancelled.' );
}

// ── Get offer details (seat selection step) ───────────────────────────────────
add_action( 'wp_ajax_ist_get_offer',        'ist_fb_ajax_get_offer' );
add_action( 'wp_ajax_nopriv_ist_get_offer', 'ist_fb_ajax_get_offer' );
function ist_fb_ajax_get_offer(): void {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $offer_id = sanitize_text_field( $_POST['offer_id'] ?? '' );
    if ( ! $offer_id || str_starts_with( $offer_id, 'demo-' ) ) {
        wp_send_json_success( [ 'data' => null, 'demo' => true ] );
    }

    if ( ! defined( 'DUFFEL_API_KEY' ) || empty( DUFFEL_API_KEY ) ) {
        wp_send_json_success( [ 'data' => null, 'demo' => true ] );
    }

    $result = IST_Duffel_API::get_offer( $offer_id );
    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }
    wp_send_json_success( $result );
}
