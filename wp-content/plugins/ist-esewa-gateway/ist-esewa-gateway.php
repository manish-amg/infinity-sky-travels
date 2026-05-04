<?php
/**
 * Plugin Name:  IST eSewa Payment Gateway
 * Description:  eSewa payment gateway for WooCommerce — Nepal's leading digital wallet.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 * Text Domain:  ist-esewa
 * Requires PHP: 8.0
 * WC requires at least: 6.0
 */

defined( 'ABSPATH' ) || exit;

// Guard: WooCommerce must be active
add_action( 'plugins_loaded', 'ist_esewa_init_gateway', 11 );
function ist_esewa_init_gateway(): void {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

    class WC_IST_eSewa_Gateway extends WC_Payment_Gateway {

        const LIVE_URL    = 'https://esewa.com.np/epay/main';
        const SANDBOX_URL = 'https://uat.esewa.com.np/epay/main';
        const VERIFY_LIVE = 'https://esewa.com.np/epay/transrec';
        const VERIFY_SBX  = 'https://uat.esewa.com.np/epay/transrec';

        public function __construct() {
            $this->id                 = 'ist_esewa';
            $this->method_title       = 'eSewa';
            $this->method_description = 'Accept payments via eSewa digital wallet (Nepal).';
            $this->has_fields         = false;
            $this->icon               = plugin_dir_url( __FILE__ ) . 'assets/esewa-logo.svg';

            $this->init_form_fields();
            $this->init_settings();

            $this->title       = $this->get_option( 'title',       'eSewa' );
            $this->description = $this->get_option( 'description', 'Pay securely using your eSewa wallet.' );
            $this->enabled     = $this->get_option( 'enabled' );
            $this->testmode    = 'yes' === $this->get_option( 'testmode' );
            $this->merchant_id = $this->get_option( 'merchant_id' );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
            add_action( 'woocommerce_api_ist_esewa_return', [ $this, 'handle_return' ] );
        }

        public function init_form_fields(): void {
            $this->form_fields = [
                'enabled'     => [
                    'title'   => 'Enable/Disable',
                    'type'    => 'checkbox',
                    'label'   => 'Enable eSewa payment gateway',
                    'default' => 'no',
                ],
                'title'       => [
                    'title'   => 'Title',
                    'type'    => 'text',
                    'default' => 'eSewa',
                ],
                'description' => [
                    'title'   => 'Description',
                    'type'    => 'textarea',
                    'default' => 'Pay securely using your eSewa wallet.',
                ],
                'testmode'    => [
                    'title'   => 'Test Mode',
                    'type'    => 'checkbox',
                    'label'   => 'Enable sandbox/test mode',
                    'default' => 'yes',
                ],
                'merchant_id' => [
                    'title'       => 'eSewa Merchant ID',
                    'type'        => 'text',
                    'description' => 'Your eSewa merchant code (e.g. EPAYTEST in sandbox).',
                    'default'     => 'EPAYTEST',
                ],
            ];
        }

        public function process_payment( $order_id ): array {
            $order = wc_get_order( $order_id );
            if ( ! $order ) return [ 'result' => 'failure' ];

            $order->update_status( 'pending', 'Awaiting eSewa payment.' );

            $return_url = add_query_arg( [
                'wc-api' => 'ist_esewa_return',
                'oid'    => $order_id,
            ], home_url( '/' ) );

            $params = [
                'amt'  => number_format( (float) $order->get_total(), 2, '.', '' ),
                'txAmt'=> '0',
                'psc'  => '0',
                'pdc'  => '0',
                'tAmt' => number_format( (float) $order->get_total(), 2, '.', '' ),
                'pid'  => 'IST-' . $order_id . '-' . time(),
                'scd'  => $this->merchant_id,
                'su'   => $return_url . '&status=success',
                'fu'   => $return_url . '&status=failure',
            ];

            // Store pid in order meta so we can verify
            $order->update_meta_data( '_esewa_pid', $params['pid'] );
            $order->save();

            $gateway_url = $this->testmode ? self::SANDBOX_URL : self::LIVE_URL;
            $redirect    = $gateway_url . '?' . http_build_query( $params );

            return [
                'result'   => 'success',
                'redirect' => $redirect,
            ];
        }

        public function handle_return(): void {
            $status   = sanitize_text_field( $_GET['status'] ?? '' );
            $order_id = absint( $_GET['oid'] ?? 0 );
            $ref_id   = sanitize_text_field( $_GET['refId'] ?? '' );
            $oid      = sanitize_text_field( $_GET['oid']   ?? '' );
            $amt      = sanitize_text_field( $_GET['amt']   ?? '' );

            $order = wc_get_order( $order_id );
            if ( ! $order ) {
                wp_redirect( wc_get_checkout_url() );
                exit;
            }

            if ( $status !== 'success' || ! $ref_id ) {
                $order->update_status( 'failed', 'eSewa payment failed or cancelled.' );
                wp_redirect( wc_get_checkout_url() );
                exit;
            }

            // Verify with eSewa
            $pid      = $order->get_meta( '_esewa_pid' );
            $verified = $this->verify_payment( $ref_id, $pid, $amt );

            if ( $verified ) {
                $order->payment_complete( $ref_id );
                $order->add_order_note( 'eSewa payment verified. Ref ID: ' . $ref_id );
                $order->update_meta_data( '_esewa_ref_id', $ref_id );
                $order->save();
                WC()->cart->empty_cart();
                wp_redirect( $this->get_return_url( $order ) );
            } else {
                $order->update_status( 'failed', 'eSewa payment verification failed.' );
                wp_redirect( wc_get_checkout_url() );
            }
            exit;
        }

        private function verify_payment( string $ref_id, string $pid, string $amt ): bool {
            $url = $this->testmode ? self::VERIFY_SBX : self::VERIFY_LIVE;

            $response = wp_remote_post( $url, [
                'body' => [
                    'merchantId' => $this->merchant_id,
                    'amount'     => $amt,
                    'pid'        => $pid,
                    'rid'        => $ref_id,
                ],
                'timeout' => 15,
            ] );

            if ( is_wp_error( $response ) ) return false;

            $body = wp_remote_retrieve_body( $response );
            // eSewa returns XML: <response><status>Success</status></response>
            return str_contains( $body, '<status>Success</status>' );
        }
    }

    // Register gateway
    add_filter( 'woocommerce_payment_gateways', function( $gateways ) {
        $gateways[] = 'WC_IST_eSewa_Gateway';
        return $gateways;
    } );
}

// ── Inline SVG logo asset ──────────────────────────────────────────────────────
add_action( 'init', function() {
    $asset_dir = plugin_dir_path( __FILE__ ) . 'assets/';
    if ( ! is_dir( $asset_dir ) ) mkdir( $asset_dir, 0755, true );
    $svg = $asset_dir . 'esewa-logo.svg';
    if ( ! file_exists( $svg ) ) {
        file_put_contents( $svg, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 40"><rect width="120" height="40" rx="6" fill="#60BB46"/><text x="60" y="27" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="#fff" text-anchor="middle">eSewa</text></svg>' );
    }
} );
