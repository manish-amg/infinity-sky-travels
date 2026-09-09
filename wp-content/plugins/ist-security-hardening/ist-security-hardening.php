<?php
/**
 * Plugin Name:  IST Security Hardening
 * Description:  Disables weak defaults, obscures WordPress fingerprints, rate-limits login, and enforces CSP for Infinity Sky Travels.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 */

defined( 'ABSPATH' ) || exit;

// ── Remove WordPress version fingerprint ─────────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// Hide the WordPress core version fingerprint only — NOT the theme/plugin
// cache-busting version. Stripping every ?ver= (the original behaviour)
// meant a CSS/JS edit never reached an already-cached browser, since
// .htaccess also caches CSS/JS for a month client-side with no other way
// to bust it. Only mask ?ver= when it actually equals WP's own version.
add_filter( 'style_loader_src',  'ist_sec_remove_version', 10, 2 );
add_filter( 'script_loader_src', 'ist_sec_remove_version', 10, 2 );
function ist_sec_remove_version( string $src, string $handle ): string {
    $wp_version = get_bloginfo( 'version' );
    if ( $wp_version && strpos( $src, '?ver=' . $wp_version ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// ── Disable feed links ────────────────────────────────────────────────────────
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

// ── Disable RSD and WLW links ────────────────────────────────────────────────
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// ── Disable shortlink ─────────────────────────────────────────────────────────
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// ── Remove X-Pingback header ─────────────────────────────────────────────────
add_filter( 'wp_headers', function( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

// ── Disable XML-RPC ──────────────────────────────────────────────────────────
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_xmlrpc_server_class', '__return_false' );

// ── Disable REST API user enumeration ────────────────────────────────────────
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) )   unset( $endpoints['/wp/v2/users'] );
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    return $endpoints;
} );

// Require authentication for REST API user endpoints
add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) return $result;
    if ( ! is_user_logged_in() ) {
        $routes_needing_auth = [ '/wp/v2/users', '/wp/v2/users/' ];
        $current_route = $GLOBALS['wp']->query_vars['rest_route'] ?? '';
        if ( in_array( $current_route, $routes_needing_auth, true ) ) {
            return new WP_Error( 'rest_not_logged_in', 'Authentication required.', [ 'status' => 401 ] );
        }
    }
    return $result;
} );

// ── Login rate limiting ───────────────────────────────────────────────────────
add_action( 'wp_login_failed', 'ist_sec_login_failed' );
function ist_sec_login_failed( string $username ): void {
    $ip      = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
    $key     = 'ist_login_fail_' . md5( $ip );
    $count   = (int) get_transient( $key );
    set_transient( $key, $count + 1, 15 * MINUTE_IN_SECONDS );
}

add_filter( 'authenticate', 'ist_sec_check_login_rate', 30, 3 );
function ist_sec_check_login_rate( $user, string $username, string $password ) {
    $ip    = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
    $key   = 'ist_login_fail_' . md5( $ip );
    $count = (int) get_transient( $key );

    if ( $count >= 5 ) {
        return new WP_Error(
            'too_many_attempts',
            sprintf( __( 'Too many failed login attempts. Please try again in 15 minutes.' ), $count )
        );
    }
    return $user;
}

// Clear fails on successful login
add_action( 'wp_login', function( string $user_login ): void {
    $ip  = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
    $key = 'ist_login_fail_' . md5( $ip );
    delete_transient( $key );
} );

// ── Disable login error messages ─────────────────────────────────────────────
add_filter( 'login_errors', function() {
    return 'Login failed. Please try again.';
} );

// ── Rename login hint in error message ───────────────────────────────────────
add_filter( 'login_headertitle', function() { return get_bloginfo( 'name' ); } );
add_filter( 'login_headerurl',   function() { return home_url(); } );

// ── Disable file editing from admin ──────────────────────────────────────────
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

// ── Force strong passwords (nag only — not enforced without plugin) ──────────
add_action( 'user_profile_update_errors', function( WP_Error $errors, bool $update, object $user ): void {
    if ( isset( $user->user_pass ) && strlen( $user->user_pass ) < 12 ) {
        $errors->add( 'weak_password', 'Password must be at least 12 characters for security.' );
    }
}, 10, 3 );

// ── Content Security Policy header ───────────────────────────────────────────
add_action( 'send_headers', 'ist_sec_csp_header' );
function ist_sec_csp_header(): void {
    if ( is_admin() ) return;

    $policy = implode( '; ', [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://maps.googleapis.com https://www.google-analytics.com https://www.googletagmanager.com https://cdn.jsdelivr.net",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
        "font-src 'self' https://fonts.gstatic.com data:",
        "img-src 'self' data: blob: https: http:",
        "connect-src 'self' https://api.duffel.com https://www.google-analytics.com https://esewa.com.np https://uat.esewa.com.np https://khalti.com https://dev.khalti.com",
        "frame-src 'self' https://www.google.com https://maps.google.com",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self' https://esewa.com.np https://uat.esewa.com.np https://khalti.com https://dev.khalti.com",
        "upgrade-insecure-requests",
    ] );

    header( 'Content-Security-Policy: ' . $policy );
}

// ── Add security headers ──────────────────────────────────────────────────────
add_action( 'send_headers', 'ist_sec_extra_headers' );
function ist_sec_extra_headers(): void {
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-XSS-Protection: 1; mode=block' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self), payment=(self)' );
}

// ── Hide admin bar from non-admins ───────────────────────────────────────────
add_action( 'after_setup_theme', function() {
    if ( ! current_user_can( 'manage_options' ) ) {
        show_admin_bar( false );
    }
} );

// ── NONCES: extend expiration to 24 hours ────────────────────────────────────
add_filter( 'nonce_life', function() { return DAY_IN_SECONDS; } );

// ── Sanitize uploaded file names ─────────────────────────────────────────────
add_filter( 'sanitize_file_name', function( string $filename ): string {
    $filename = strtolower( $filename );
    $filename = preg_replace( '/[^a-z0-9\.\-\_]/', '-', $filename );
    $filename = preg_replace( '/-+/', '-', $filename );
    return $filename;
} );

// ── Block PHP upload types ────────────────────────────────────────────────────
add_filter( 'upload_mimes', function( $mimes ) {
    $dangerous = [ 'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'pht', 'phar', 'exe', 'sh', 'bash', 'pl', 'py', 'rb', 'cgi', 'com', 'bat', 'vbs', 'jar', 'js', 'jsp', 'htaccess' ];
    foreach ( $dangerous as $type ) {
        unset( $mimes[ $type ] );
    }
    return $mimes;
} );

add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    $dangerous = [ 'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'exe', 'sh', 'bash' ];
    if ( in_array( strtolower( $ext ), $dangerous, true ) ) {
        $data['ext']  = false;
        $data['type'] = false;
    }
    return $data;
}, 10, 4 );
