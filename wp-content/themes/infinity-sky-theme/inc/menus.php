<?php
/**
 * Menu registration.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function ist_register_menus() {
    register_nav_menus( [
        'primary'   => __( 'Primary Navigation', 'infinity-sky' ),
        'footer-1'  => __( 'Footer Quick Links',  'infinity-sky' ),
        'footer-2'  => __( 'Footer Packages',     'infinity-sky' ),
        'footer-legal' => __( 'Footer Legal',     'infinity-sky' ),
    ] );
}
add_action( 'init', 'ist_register_menus' );
