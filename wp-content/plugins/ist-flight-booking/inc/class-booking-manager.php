<?php
/**
 * Booking Manager — create, retrieve, update, cancel ist_booking posts.
 */

defined( 'ABSPATH' ) || exit;

class IST_Booking_Manager {

    // ── Booking statuses ─────────────────────────────────────────────────────
    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED  = 'refunded';
    const STATUS_FAILED    = 'failed';

    // ── Create booking post from Duffel order ────────────────────────────────
    public static function create( array $duffel_order, array $form_data ): int|WP_Error {
        $order_id = $duffel_order['data']['id']        ?? '';
        $ref      = $duffel_order['data']['booking_reference'] ?? strtoupper( substr( md5( uniqid() ), 0, 6 ) );

        $slice      = $duffel_order['data']['slices'][0]           ?? [];
        $segment    = $slice['segments'][0]                         ?? [];
        $dep_airport= $segment['origin']['iata_code']              ?? '';
        $arr_airport= $segment['destination']['iata_code']         ?? '';
        $dep_time   = $segment['departing_at']                     ?? '';
        $carrier    = $segment['operating_carrier']['name']        ?? '';
        $total      = $duffel_order['data']['total_amount']        ?? '0.00';
        $currency   = $duffel_order['data']['total_currency']      ?? 'USD';

        $lead_pax   = $form_data['passengers'][0]                  ?? [];
        $lead_name  = trim( ( $lead_pax['given_name'] ?? '' ) . ' ' . ( $lead_pax['family_name'] ?? '' ) );
        $title      = "$ref — $dep_airport → $arr_airport — $lead_name";

        $post_id = wp_insert_post( [
            'post_type'   => 'ist_booking',
            'post_title'  => sanitize_text_field( $title ),
            'post_status' => 'publish',
        ], true );

        if ( is_wp_error( $post_id ) ) return $post_id;

        // Core meta
        $meta = [
            '_fb_reference'    => $ref,
            '_fb_duffel_order' => $order_id,
            '_fb_status'       => self::STATUS_CONFIRMED,
            '_fb_dep_airport'  => $dep_airport,
            '_fb_arr_airport'  => $arr_airport,
            '_fb_dep_time'     => $dep_time,
            '_fb_carrier'      => $carrier,
            '_fb_total'        => $total,
            '_fb_currency'     => $currency,
            '_fb_lead_name'    => $lead_name,
            '_fb_lead_email'   => sanitize_email( $form_data['email'] ?? '' ),
            '_fb_lead_phone'   => sanitize_text_field( $form_data['phone'] ?? '' ),
            '_fb_passengers'   => wp_json_encode( $form_data['passengers'] ?? [] ),
            '_fb_addons'       => wp_json_encode( $form_data['addons'] ?? [] ),
            '_fb_payment_method'=> sanitize_text_field( $form_data['payment_method'] ?? '' ),
            '_fb_created'      => current_time( 'mysql' ),
            '_fb_raw_order'    => wp_json_encode( $duffel_order ),
        ];

        foreach ( $meta as $key => $val ) {
            update_post_meta( $post_id, $key, $val );
        }

        // Fire hook for email sending etc.
        do_action( 'ist_fb_booking_created', $post_id, $duffel_order, $form_data );

        return $post_id;
    }

    // ── Create fallback booking (no Duffel — manual/demo) ───────────────────
    public static function create_manual( array $form_data ): int|WP_Error {
        $ref   = 'IST' . strtoupper( substr( md5( uniqid() ), 0, 6 ) );
        $from  = strtoupper( $form_data['from']  ?? 'KTM' );
        $to    = strtoupper( $form_data['to']    ?? '' );
        $name  = sanitize_text_field( $form_data['passengers'][0]['given_name'] ?? 'Guest' );
        $title = "$ref — $from → $to — $name";

        $post_id = wp_insert_post( [
            'post_type'   => 'ist_booking',
            'post_title'  => sanitize_text_field( $title ),
            'post_status' => 'publish',
        ], true );

        if ( is_wp_error( $post_id ) ) return $post_id;

        update_post_meta( $post_id, '_fb_reference',     $ref );
        update_post_meta( $post_id, '_fb_status',        self::STATUS_PENDING );
        update_post_meta( $post_id, '_fb_dep_airport',   $from );
        update_post_meta( $post_id, '_fb_arr_airport',   $to );
        update_post_meta( $post_id, '_fb_dep_time',      $form_data['date'] ?? '' );
        update_post_meta( $post_id, '_fb_total',         $form_data['total'] ?? '0.00' );
        update_post_meta( $post_id, '_fb_currency',      'USD' );
        update_post_meta( $post_id, '_fb_lead_name',     $name );
        update_post_meta( $post_id, '_fb_lead_email',    sanitize_email( $form_data['email'] ?? '' ) );
        update_post_meta( $post_id, '_fb_passengers',    wp_json_encode( $form_data['passengers'] ?? [] ) );
        update_post_meta( $post_id, '_fb_addons',        wp_json_encode( $form_data['addons'] ?? [] ) );
        update_post_meta( $post_id, '_fb_payment_method',sanitize_text_field( $form_data['payment_method'] ?? 'manual' ) );
        update_post_meta( $post_id, '_fb_created',       current_time( 'mysql' ) );
        update_post_meta( $post_id, '_fb_manual',        1 );

        do_action( 'ist_fb_booking_created_manual', $post_id, $form_data );

        return $post_id;
    }

    // ── Get booking by reference number ─────────────────────────────────────
    public static function get_by_ref( string $ref ): ?WP_Post {
        $posts = get_posts( [
            'post_type'  => 'ist_booking',
            'meta_key'   => '_fb_reference',
            'meta_value' => strtoupper( $ref ),
            'numberposts'=> 1,
        ] );
        return $posts[0] ?? null;
    }

    // ── Update status ────────────────────────────────────────────────────────
    public static function update_status( int $post_id, string $status ): void {
        update_post_meta( $post_id, '_fb_status', $status );
        update_post_meta( $post_id, '_fb_status_updated', current_time( 'mysql' ) );
        do_action( 'ist_fb_status_changed', $post_id, $status );
    }

    // ── Cancel via Duffel ────────────────────────────────────────────────────
    public static function cancel( int $post_id ): bool|WP_Error {
        $order_id = get_post_meta( $post_id, '_fb_duffel_order', true );
        if ( $order_id ) {
            $result = IST_Duffel_API::cancel_order( $order_id );
            if ( is_wp_error( $result ) ) return $result;
        }
        self::update_status( $post_id, self::STATUS_CANCELLED );
        do_action( 'ist_fb_booking_cancelled', $post_id );
        return true;
    }

    // ── Get all meta for a booking ───────────────────────────────────────────
    public static function get_meta( int $post_id ): array {
        $keys = [
            'reference', 'duffel_order', 'status', 'dep_airport', 'arr_airport',
            'dep_time', 'carrier', 'total', 'currency', 'lead_name', 'lead_email',
            'lead_phone', 'passengers', 'addons', 'payment_method', 'created', 'manual',
        ];
        $meta = [];
        foreach ( $keys as $k ) {
            $raw = get_post_meta( $post_id, "_fb_$k", true );
            $meta[ $k ] = in_array( $k, [ 'passengers', 'addons' ], true )
                ? json_decode( $raw ?: '[]', true )
                : $raw;
        }
        return $meta;
    }
}

// ── Send confirmation email on booking created ───────────────────────────────
add_action( 'ist_fb_booking_created', 'ist_fb_send_confirmation_email', 10, 3 );
add_action( 'ist_fb_booking_created_manual', 'ist_fb_send_confirmation_email_manual', 10, 2 );

function ist_fb_send_confirmation_email( int $post_id, array $duffel_order, array $form_data ): void {
    $meta = IST_Booking_Manager::get_meta( $post_id );
    if ( empty( $meta['lead_email'] ) ) return;

    $template = IST_FB_DIR . 'templates/email-booking-confirmed.php';
    if ( ! file_exists( $template ) ) return;

    ob_start();
    extract( [ 'meta' => $meta, 'post_id' => $post_id ] );
    include $template;
    $html = ob_get_clean();

    add_filter( 'wp_mail_content_type', fn() => 'text/html' );
    wp_mail(
        $meta['lead_email'],
        sprintf( __( 'Your flight booking is confirmed — Ref: %s', 'ist-fb' ), $meta['reference'] ),
        $html,
        [ 'From: Infinity Sky Travels <infinityskytravels8@gmail.com>' ]
    );
    remove_filter( 'wp_mail_content_type', fn() => 'text/html' );

    // Also notify admin
    wp_mail(
        get_option( 'admin_email' ),
        "[New Booking] {$meta['reference']} — {$meta['dep_airport']} → {$meta['arr_airport']}",
        "New flight booking:\n\nRef: {$meta['reference']}\nPassenger: {$meta['lead_name']}\nEmail: {$meta['lead_email']}\nRoute: {$meta['dep_airport']} → {$meta['arr_airport']}\nTotal: {$meta['currency']} {$meta['total']}\n\nView in WP Admin: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' )
    );
}

function ist_fb_send_confirmation_email_manual( int $post_id, array $form_data ): void {
    $meta = IST_Booking_Manager::get_meta( $post_id );
    ist_fb_send_confirmation_email( $post_id, [], $form_data );
}
