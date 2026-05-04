<?php
/**
 * Plugin Name:  IST Khalti Payment Gateway
 * Description:  Khalti digital wallet payment gateway for WooCommerce.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 * Text Domain:  ist-khalti
 * Requires PHP: 8.0
 * WC requires at least: 6.0
 */

defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', 'ist_khalti_init_gateway', 11 );
function ist_khalti_init_gateway(): void {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

    class WC_IST_Khalti_Gateway extends WC_Payment_Gateway {

        const INITIATE_LIVE = 'https://khalti.com/api/v2/epayment/initiate/';
        const INITIATE_SBX  = 'https://dev.khalti.com/api/v2/epayment/initiate/';
        const LOOKUP_LIVE   = 'https://khalti.com/api/v2/epayment/lookup/';
        const LOOKUP_SBX    = 'https://dev.khalti.com/api/v2/epayment/lookup/';

        public function __construct() {
            $this->id                 = 'ist_khalti';
            $this->method_title       = 'Khalti';
            $this->method_description = 'Accept payments via Khalti digital wallet (Nepal).';
            $this->has_fields         = false;
            $this->icon               = plugin_dir_url( __FILE__ ) . 'assets/khalti-logo.svg';

            $this->init_form_fields();
            $this->init_settings();

            $this->title       = $this->get_option( 'title',       'Khalti' );
            $this->description = $this->get_option( 'description', 'Pay using your Khalti wallet or bank.' );
            $this->enabled     = $this->get_option( 'enabled' );
            $this->testmode    = 'yes' === $this->get_option( 'testmode' );
            $this->secret_key  = $this->get_option( 'secret_key' );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
            add_action( 'woocommerce_api_ist_khalti_return', [ $this, 'handle_return' ] );
        }

        public function init_form_fields(): void {
            $this->form_fields = [
                'enabled'     => [
                    'title'   => 'Enable/Disable',
                    'type'    => 'checkbox',
                    'label'   => 'Enable Khalti payment gateway',
                    'default' => 'no',
                ],
                'title'       => [
                    'title'   => 'Title',
                    'type'    => 'text',
                    'default' => 'Khalti',
                ],
                'description' => [
                    'title'   => 'Description',
                    'type'    => 'textarea',
                    'default' => 'Pay securely using your Khalti digital wallet or linked bank.',
                ],
                'testmode'    => [
                    'title'   => 'Test Mode',
                    'type'    => 'checkbox',
                    'label'   => 'Enable Khalti sandbox environment',
                    'default' => 'yes',
                ],
                'secret_key'  => [
                    'title'       => 'Live Secret Key',
                    'type'        => 'password',
                    'description' => 'Your Khalti live secret key from the merchant dashboard.',
                    'default'     => '',
                ],
                'test_secret' => [
                    'title'       => 'Test Secret Key',
                    'type'        => 'password',
                    'description' => 'Your Khalti test secret key (starts with test_secret_key_).',
                    'default'     => 'test_secret_key_f59e8b7d18b4499ca40f68195a846e9b',
                ],
            ];
        }

        private function get_key(): string {
            if ( $this->testmode ) {
                return $this->get_option( 'test_secret', 'test_secret_key_f59e8b7d18b4499ca40f68195a846e9b' );
            }
            return $this->secret_key;
        }

        public function process_payment( $order_id ): array {
            $order = wc_get_order( $order_id );
            if ( ! $order ) return [ 'result' => 'failure' ];

            $order->update_status( 'pending', 'Awaiting Khalti payment initiation.' );

            $return_url = add_query_arg( [
                'wc-api' => 'ist_khalti_return',
                'oid'    => $order_id,
            ], home_url( '/' ) );

            // Khalti amounts are in paisa (1 NPR = 100 paisa)
            $amount_paisa = (int) round( (float) $order->get_total() * 100 );

            $body = [
                'return_url'    => $return_url,
                'website_url'   => home_url(),
                'amount'        => $amount_paisa,
                'purchase_order_id'   => 'IST-' . $order_id,
                'purchase_order_name' => 'Infinity Sky Travels — Order #' . $order_id,
                'customer_info' => [
                    'name'  => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'email' => $order->get_billing_email(),
                    'phone' => $order->get_billing_phone() ?: '9800000000',
                ],
            ];

            $initiate_url = $this->testmode ? self::INITIATE_SBX : self::INITIATE_LIVE;

            $response = wp_remote_post( $initiate_url, [
                'headers' => [
                    'Authorization' => 'Key ' . $this->get_key(),
                    'Content-Type'  => 'application/json',
                ],
                'body'    => wp_json_encode( $body ),
                'timeout' => 20,
            ] );

            if ( is_wp_error( $response ) ) {
                wc_add_notice( 'Khalti connection error: ' . $response->get_error_message(), 'error' );
                return [ 'result' => 'failure' ];
            }

            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            $code = wp_remote_retrieve_response_code( $response );

            if ( $code !== 200 || empty( $data['payment_url'] ) ) {
                $msg = $data['detail'] ?? ( $data['error_key'] ?? 'Khalti payment initiation failed.' );
                wc_add_notice( esc_html( $msg ), 'error' );
                return [ 'result' => 'failure' ];
            }

            $order->update_meta_data( '_khalti_pidx', $data['pidx'] );
            $order->save();

            return [
                'result'   => 'success',
                'redirect' => $data['payment_url'],
            ];
        }

        public function handle_return(): void {
            $order_id = absint( $_GET['oid']    ?? 0 );
            $pidx     = sanitize_text_field( $_GET['pidx'] ?? '' );
            $status   = sanitize_text_field( $_GET['status'] ?? '' );

            $order = wc_get_order( $order_id );
            if ( ! $order ) { wp_redirect( wc_get_checkout_url() ); exit; }

            if ( strtolower( $status ) !== 'completed' || ! $pidx ) {
                $order->update_status( 'failed', 'Khalti payment not completed.' );
                wc_add_notice( 'Payment was not completed. Please try again.', 'error' );
                wp_redirect( wc_get_checkout_url() );
                exit;
            }

            // Verify via Khalti lookup
            $lookup_url = $this->testmode ? self::LOOKUP_SBX : self::LOOKUP_LIVE;
            $response   = wp_remote_post( $lookup_url, [
                'headers' => [
                    'Authorization' => 'Key ' . $this->get_key(),
                    'Content-Type'  => 'application/json',
                ],
                'body'    => wp_json_encode( [ 'pidx' => $pidx ] ),
                'timeout' => 15,
            ] );

            if ( is_wp_error( $response ) ) {
                $order->update_status( 'on-hold', 'Khalti verification failed — manual review needed.' );
                wp_redirect( $this->get_return_url( $order ) );
                exit;
            }

            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( isset( $data['status'] ) && strtolower( $data['status'] ) === 'completed' ) {
                $order->payment_complete( $pidx );
                $order->add_order_note( 'Khalti payment verified. PIDX: ' . $pidx . ' | Transaction ID: ' . ( $data['transaction_id'] ?? '—' ) );
                $order->update_meta_data( '_khalti_pidx', $pidx );
                $order->update_meta_data( '_khalti_txn',  $data['transaction_id'] ?? '' );
                $order->save();
                WC()->cart->empty_cart();
                wp_redirect( $this->get_return_url( $order ) );
            } else {
                $order->update_status( 'failed', 'Khalti verification returned status: ' . ( $data['status'] ?? 'unknown' ) );
                wc_add_notice( 'Payment verification failed. Please contact support.', 'error' );
                wp_redirect( wc_get_checkout_url() );
            }
            exit;
        }
    }

    add_filter( 'woocommerce_payment_gateways', function( $gateways ) {
        $gateways[] = 'WC_IST_Khalti_Gateway';
        return $gateways;
    } );
}

// ── Inline SVG logo asset ──────────────────────────────────────────────────────
add_action( 'init', function() {
    $asset_dir = plugin_dir_path( __FILE__ ) . 'assets/';
    if ( ! is_dir( $asset_dir ) ) mkdir( $asset_dir, 0755, true );
    $svg = $asset_dir . 'khalti-logo.svg';
    if ( ! file_exists( $svg ) ) {
        file_put_contents( $svg, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 40"><rect width="120" height="40" rx="6" fill="#5C2D91"/><text x="60" y="27" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="#fff" text-anchor="middle">Khalti</text></svg>' );
    }
} );
