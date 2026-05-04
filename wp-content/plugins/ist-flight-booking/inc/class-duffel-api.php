<?php
/**
 * Duffel API v2 wrapper.
 * Expects DUFFEL_API_KEY defined in wp-config.php.
 */

defined( 'ABSPATH' ) || exit;

class IST_Duffel_API {

    const BASE_URL = 'https://api.duffel.com/air';
    const VERSION  = 'v2';

    private static function headers(): array {
        $key = defined( 'DUFFEL_API_KEY' ) ? DUFFEL_API_KEY : '';
        return [
            'Authorization'  => 'Bearer ' . $key,
            'Duffel-Version' => self::VERSION,
            'Content-Type'   => 'application/json',
            'Accept'         => 'application/json',
        ];
    }

    // ── POST /offer_requests ─────────────────────────────────────────────────
    public static function search_flights( array $params ): array|WP_Error {
        $from    = strtoupper( $params['from']     ?? 'KTM' );
        $to      = strtoupper( $params['to']       ?? '' );
        $date    = $params['date']    ?? '';
        $adults  = absint( $params['adults']  ?? 1 );
        $children= absint( $params['children'] ?? 0 );
        $infants = absint( $params['infants']  ?? 0 );
        $cabin   = $params['cabin_class'] ?? 'economy';

        if ( ! $from || ! $to || ! $date ) {
            return new WP_Error( 'missing_params', 'from, to and date are required.' );
        }

        $passengers = [];
        for ( $i = 0; $i < $adults;   $i++ ) $passengers[] = [ 'type' => 'adult' ];
        for ( $i = 0; $i < $children; $i++ ) $passengers[] = [ 'type' => 'child' ];
        for ( $i = 0; $i < $infants;  $i++ ) $passengers[] = [ 'type' => 'infant_without_seat' ];

        $body = [
            'data' => [
                'slices'          => [[ 'origin' => $from, 'destination' => $to, 'departure_date' => $date ]],
                'passengers'      => $passengers,
                'cabin_class'     => $cabin,
                'return_offers'   => false,
            ],
        ];

        $response = wp_remote_post(
            self::BASE_URL . '/offer_requests?return_offers=true',
            [
                'headers' => self::headers(),
                'body'    => wp_json_encode( $body ),
                'timeout' => 20,
            ]
        );

        return self::parse( $response );
    }

    // ── GET /offers/{id} ─────────────────────────────────────────────────────
    public static function get_offer( string $offer_id ): array|WP_Error {
        $response = wp_remote_get(
            self::BASE_URL . '/offers/' . $offer_id . '?return_available_services=true',
            [ 'headers' => self::headers(), 'timeout' => 15 ]
        );
        return self::parse( $response );
    }

    // ── POST /orders ─────────────────────────────────────────────────────────
    public static function create_order( array $offer_ids, array $passengers, array $metadata = [] ): array|WP_Error {
        $body = [
            'data' => [
                'type'           => 'instant',
                'selected_offers'=> $offer_ids,
                'passengers'     => $passengers,
                'metadata'       => $metadata,
                'payments'       => [[
                    'type'     => 'balance',
                    'amount'   => $metadata['total_amount'] ?? '0.00',
                    'currency' => $metadata['currency']     ?? 'USD',
                ]],
            ],
        ];

        $response = wp_remote_post(
            self::BASE_URL . '/orders',
            [
                'headers' => self::headers(),
                'body'    => wp_json_encode( $body ),
                'timeout' => 30,
            ]
        );

        return self::parse( $response );
    }

    // ── GET /orders/{id} ─────────────────────────────────────────────────────
    public static function get_order( string $order_id ): array|WP_Error {
        $response = wp_remote_get(
            self::BASE_URL . '/orders/' . $order_id,
            [ 'headers' => self::headers(), 'timeout' => 15 ]
        );
        return self::parse( $response );
    }

    // ── POST /order_cancellations ────────────────────────────────────────────
    public static function cancel_order( string $order_id ): array|WP_Error {
        $body = [ 'data' => [ 'order_id' => $order_id ] ];
        $response = wp_remote_post(
            self::BASE_URL . '/order_cancellations',
            [
                'headers' => self::headers(),
                'body'    => wp_json_encode( $body ),
                'timeout' => 20,
            ]
        );
        return self::parse( $response );
    }

    // ── GET /seat_maps ───────────────────────────────────────────────────────
    public static function get_seat_map( string $offer_id ): array|WP_Error {
        $response = wp_remote_get(
            self::BASE_URL . '/seat_maps?offer_id=' . $offer_id,
            [ 'headers' => self::headers(), 'timeout' => 15 ]
        );
        return self::parse( $response );
    }

    // ── Parse response ───────────────────────────────────────────────────────
    private static function parse( $response ): array|WP_Error {
        if ( is_wp_error( $response ) ) return $response;

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code >= 400 ) {
            $msg = $body['errors'][0]['message'] ?? "Duffel API error $code";
            return new WP_Error( 'duffel_error', $msg, [ 'status' => $code ] );
        }

        return $body ?? [];
    }
}
