<?php
/**
 * Plugin Name:  IST Flight Booking
 * Description:  Duffel API flight order management, booking CPT, admin dashboard, and WooCommerce integration for Infinity Sky Travels.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 * Text Domain:  ist-fb
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

define( 'IST_FB_VERSION', '1.0.0' );
define( 'IST_FB_DIR',     plugin_dir_path( __FILE__ ) );
define( 'IST_FB_URL',     plugin_dir_url(  __FILE__ ) );

// Load sub-modules
require_once IST_FB_DIR . 'inc/class-duffel-api.php';
require_once IST_FB_DIR . 'inc/class-booking-manager.php';
require_once IST_FB_DIR . 'inc/admin-dashboard.php';
require_once IST_FB_DIR . 'inc/ajax-handlers.php';

// ─── Activation ────────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'ist_fb_activate' );
function ist_fb_activate() {
    ist_fb_register_cpt();
    flush_rewrite_rules();
    add_option( 'ist_fb_version', IST_FB_VERSION );
}

// ─── Register CPT: ist_booking ───────────────────────────────────────────────
add_action( 'init', 'ist_fb_register_cpt' );
function ist_fb_register_cpt() {
    register_post_type( 'ist_booking', [
        'label'           => __( 'Flight Bookings', 'ist-fb' ),
        'labels'          => [
            'name'          => __( 'Flight Bookings', 'ist-fb' ),
            'singular_name' => __( 'Flight Booking',  'ist-fb' ),
            'edit_item'     => __( 'Booking Details', 'ist-fb' ),
            'search_items'  => __( 'Search Bookings', 'ist-fb' ),
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => 'ist-flight-bookings',
        'supports'        => [ 'title', 'custom-fields' ],
        'capability_type' => 'post',
        'map_meta_cap'    => true,
    ] );
}

// ─── Admin menu ──────────────────────────────────────────────────────────────
add_action( 'admin_menu', 'ist_fb_admin_menu' );
function ist_fb_admin_menu() {
    add_menu_page(
        __( 'Flight Bookings', 'ist-fb' ),
        __( 'Flight Bookings', 'ist-fb' ),
        'manage_options',
        'ist-flight-bookings',
        'ist_fb_dashboard_page',
        'dashicons-airplane',
        25
    );
    add_submenu_page(
        'ist-flight-bookings',
        __( 'Dashboard',  'ist-fb' ),
        __( 'Dashboard',  'ist-fb' ),
        'manage_options',
        'ist-flight-bookings',
        'ist_fb_dashboard_page'
    );
    add_submenu_page(
        'ist-flight-bookings',
        __( 'All Bookings', 'ist-fb' ),
        __( 'All Bookings', 'ist-fb' ),
        'manage_options',
        'edit.php?post_type=ist_booking'
    );
    add_submenu_page(
        'ist-flight-bookings',
        __( 'Settings', 'ist-fb' ),
        __( 'Settings', 'ist-fb' ),
        'manage_options',
        'ist-fb-settings',
        'ist_fb_settings_page'
    );
}
