<?php
/**
 * Duffel API helper functions.
 * Called by AJAX handlers and the flight booking plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class IST_Duffel_API {

    private static string $base_url = 'https://api.duffel.com/air';
    private static string $version  = 'v2';

    private static function get_headers(): array {
        return [
            'Authorization' => 'Bearer ' . ( defined( 'DUFFEL_API_KEY' ) ? DUFFEL_API_KEY : '' ),
            'Duffel-Version' => self::$version,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Search for flights (POST /offer_requests).
     *
     * @param string $origin       IATA code
     * @param string $destination  IATA code
     * @param string $depart_date  Y-m-d
     * @param int    $adults
     * @param string $return_date  Y-m-d or empty
     * @param int    $children
     * @param int    $infants
     * @return array|WP_Error
     */
    public static function search_flights( string $origin, string $destination, string $depart_date, int $adults = 1, string $return_date = '', int $children = 0, int $infants = 0 ) {

        if ( empty( defined( 'DUFFEL_API_KEY' ) ) || empty( DUFFEL_API_KEY ) || DUFFEL_API_KEY === 'YOUR_DUFFEL_KEY_HERE' ) {
            return new WP_Error( 'no_api_key', __( 'Duffel API key not configured.', 'infinity-sky' ) );
        }

        $passengers = [];
        for ( $i = 0; $i < $adults; $i++ ) {
            $passengers[] = [ 'type' => 'adult' ];
        }
        for ( $i = 0; $i < $children; $i++ ) {
            $passengers[] = [ 'type' => 'child' ];
        }
        for ( $i = 0; $i < $infants; $i++ ) {
            $passengers[] = [ 'type' => 'infant_without_seat' ];
        }

        $slices = [
            [
                'origin'         => strtoupper( $origin ),
                'destination'    => strtoupper( $destination ),
                'departure_date' => $depart_date,
            ],
        ];

        if ( $return_date ) {
            $slices[] = [
                'origin'         => strtoupper( $destination ),
                'destination'    => strtoupper( $origin ),
                'departure_date' => $return_date,
            ];
        }

        $body = [
            'data' => [
                'slices'          => $slices,
                'passengers'      => $passengers,
                'cabin_class'     => 'economy',
            ],
        ];

        $response = wp_remote_post( self::$base_url . '/offer_requests', [
            'headers' => self::get_headers(),
            'body'    => wp_json_encode( $body ),
            'timeout' => 30,
        ] );

        return self::handle_response( $response );
    }

    /**
     * Get a specific offer by ID.
     */
    public static function get_offer( string $offer_id ) {
        $response = wp_remote_get( self::$base_url . '/offers/' . rawurlencode( $offer_id ), [
            'headers' => self::get_headers(),
            'timeout' => 15,
        ] );
        return self::handle_response( $response );
    }

    /**
     * Create a booking order (POST /orders).
     */
    public static function create_order( array $offer_ids, array $passengers, string $payment_type, float $amount, string $currency = 'USD' ): array|WP_Error {
        $body = [
            'data' => [
                'type'     => 'instant',
                'selected_offers' => $offer_ids,
                'passengers' => $passengers,
                'payments'   => [
                    [
                        'type'     => $payment_type,
                        'currency' => $currency,
                        'amount'   => (string) $amount,
                    ],
                ],
            ],
        ];

        $response = wp_remote_post( self::$base_url . '/orders', [
            'headers' => self::get_headers(),
            'body'    => wp_json_encode( $body ),
            'timeout' => 45,
        ] );

        return self::handle_response( $response );
    }

    /**
     * Retrieve an existing order.
     */
    public static function get_order( string $order_id ) {
        $response = wp_remote_get( self::$base_url . '/orders/' . rawurlencode( $order_id ), [
            'headers' => self::get_headers(),
            'timeout' => 15,
        ] );
        return self::handle_response( $response );
    }

    private static function handle_response( $response ): array|WP_Error {
        if ( is_wp_error( $response ) ) {
            return $response;
        }
        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code >= 400 ) {
            $msg = $body['errors'][0]['message'] ?? ( $body['errors'][0]['title'] ?? 'Duffel API error' );
            return new WP_Error( 'duffel_error_' . $code, $msg, $body );
        }

        return $body['data'] ?? $body;
    }
}
