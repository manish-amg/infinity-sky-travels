<?php
/**
 * Infinity Sky Travels — functions.php
 * Theme setup, enqueues, CPT includes, widgets, AJAX endpoints, schema.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'IST_VERSION', '1.0.0' );
define( 'IST_THEME_DIR', get_template_directory() );
define( 'IST_THEME_URI', get_template_directory_uri() );

// ─── Required includes ────────────────────────────────────────────────────────
require_once IST_THEME_DIR . '/inc/custom-post-types.php';
require_once IST_THEME_DIR . '/inc/cpt-routes-activities-inquiries.php';
require_once IST_THEME_DIR . '/inc/acf-fields.php';
require_once IST_THEME_DIR . '/inc/acf-fields-routes-activities.php';
require_once IST_THEME_DIR . '/inc/menus.php';
require_once IST_THEME_DIR . '/inc/widgets.php';
require_once IST_THEME_DIR . '/inc/schema.php';
require_once IST_THEME_DIR . '/inc/api-helpers.php';
require_once IST_THEME_DIR . '/inc/shortcodes.php';
require_once IST_THEME_DIR . '/inc/ajax-handlers.php';

// ─── Theme setup ──────────────────────────────────────────────────────────────
function ist_theme_setup() {
    load_theme_textdomain( 'infinity-sky', IST_THEME_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );

    add_image_size( 'ist-hero',     1920, 800,  true );
    add_image_size( 'ist-card',     600,  400,  true );
    add_image_size( 'ist-portrait', 400,  540,  true );
    add_image_size( 'ist-thumb',    300,  200,  true );
}
add_action( 'after_setup_theme', 'ist_theme_setup' );

// ─── Content width ─────────────────────────────────────────────────────────────
function ist_content_width() {
    $GLOBALS['content_width'] = 1280;
}
add_action( 'after_setup_theme', 'ist_content_width', 0 );

// ─── Enqueue styles ────────────────────────────────────────────────────────────
function ist_enqueue_styles() {
    // Google Fonts
    wp_enqueue_style(
        'ist-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Open+Sans:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    // Core stylesheets
    wp_enqueue_style( 'ist-main',       IST_THEME_URI . '/assets/css/main.css',             [ 'ist-google-fonts' ], IST_VERSION );
    wp_enqueue_style( 'ist-animations', IST_THEME_URI . '/assets/css/animations.css',        [ 'ist-main' ],         IST_VERSION );
    wp_enqueue_style( 'ist-responsive', IST_THEME_URI . '/assets/css/responsive.css',        [ 'ist-main' ],         IST_VERSION );

    // Page-specific stylesheets
    if ( is_page( 'flights' ) ) {
        wp_enqueue_style( 'ist-flights', IST_THEME_URI . '/assets/css/flight-search.css', [ 'ist-main' ], IST_VERSION );
    }
    if ( is_page( 'packages' ) || is_singular( 'ist_package' ) ) {
        wp_enqueue_style( 'ist-packages', IST_THEME_URI . '/assets/css/packages.css', [ 'ist-main' ], IST_VERSION );
    }
    if ( is_page( 'plan-my-trip' ) ) {
        wp_enqueue_style( 'ist-plan-my-trip', IST_THEME_URI . '/assets/css/plan-my-trip.css', [ 'ist-main' ], IST_VERSION );
    }
    if ( is_singular( 'post' ) || is_archive() || is_category() || is_tag() || is_page( 'about' ) || is_page( 'contact' ) ) {
        wp_enqueue_style( 'ist-blog', IST_THEME_URI . '/assets/css/blog.css', [ 'ist-main' ], IST_VERSION );
    }

    // Swiper CSS
    wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11.0.0' );
}
add_action( 'wp_enqueue_scripts', 'ist_enqueue_styles' );

// ─── Enqueue scripts ───────────────────────────────────────────────────────────
function ist_enqueue_scripts() {
    // CDN libraries
    wp_enqueue_script( 'jarallax',     'https://cdnjs.cloudflare.com/ajax/libs/jarallax/2.2.1/jarallax.min.js',      [], '2.2.1',  true );
    wp_enqueue_script( 'swiper',       'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',                 [], '11.0.0', true );
    wp_enqueue_script( 'flatpickr',    'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js',                [], '4.6.13', true );
    wp_enqueue_style(  'flatpickr-css','https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',               [], '4.6.13' );

    // Core JS
    wp_enqueue_script( 'ist-animations', IST_THEME_URI . '/assets/js/animations.js', [ 'jarallax', 'swiper' ], IST_VERSION, true );
    wp_enqueue_script( 'ist-main',        IST_THEME_URI . '/assets/js/main.js',        [ 'jquery', 'ist-animations' ], IST_VERSION, true );

    // Page-specific JS
    if ( is_front_page() || is_page( 'flights' ) ) {
        wp_enqueue_script( 'ist-flight-search',  IST_THEME_URI . '/assets/js/flight-search.js',  [ 'ist-main', 'flatpickr' ], IST_VERSION, true );
        wp_enqueue_script( 'ist-flight-results', IST_THEME_URI . '/assets/js/flight-results.js', [ 'ist-flight-search' ],     IST_VERSION, true );
        wp_enqueue_script( 'ist-booking-modal',  IST_THEME_URI . '/assets/js/booking-modal.js',  [ 'ist-flight-results' ],    IST_VERSION, true );
    }
    if ( is_page( 'packages' ) || is_singular( 'ist_package' ) ) {
        wp_enqueue_script( 'ist-package-filter', IST_THEME_URI . '/assets/js/package-filter.js', [ 'ist-main' ], IST_VERSION, true );
    }
    if ( is_page( 'plan-my-trip' ) ) {
        wp_enqueue_script( 'ist-plan-my-trip', IST_THEME_URI . '/assets/js/plan-my-trip.js', [ 'ist-main', 'flatpickr' ], IST_VERSION, true );
    }

    // WooCommerce cart fragments
    if ( function_exists( 'is_woocommerce' ) ) {
        wp_enqueue_script( 'wc-cart-fragments' );
    }

    // Localise AJAX + config data for JS
    wp_localize_script( 'ist-main', 'istConfig', [
        'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'ist_nonce' ),
        'siteUrl'     => home_url(),
        'themeUrl'    => IST_THEME_URI,
        'whatsapp'    => '+977 9810597893',
        'whatsappUrl' => 'https://wa.me/9779810597893',
        'currency'    => 'USD',
    ] );
}
add_action( 'wp_enqueue_scripts', 'ist_enqueue_scripts' );

// ─── Nepal airport list (used across multiple files) ──────────────────────────
function ist_get_nepal_airports() {
    return [
        'KTM' => 'Kathmandu (Tribhuvan International)',
        'PKR' => 'Pokhara International',
        'LUA' => 'Lukla (Tenzing-Hillary)',
        'BHR' => 'Bhadrapur',
        'BIR' => 'Biratnagar',
        'BJH' => 'Bajhang',
        'BIT' => 'Baitadi',
        'BGL' => 'Baglung',
        'DNP' => 'Dang (Tulsipur)',
        'GKH' => 'Gorkha',
        'HRJ' => 'Chaurjhari',
        'IMK' => 'Simikot',
        'JKR' => 'Janakpur',
        'JMO' => 'Jomsom',
        'KEP' => 'Nepalgunj',
        'MEY' => 'Meghauli (Chitwan)',
        'NGX' => 'Manang',
        'PPL' => 'Phaplu',
        'RHP' => 'Ramechhap (RMIA)',
        'RJB' => 'Rajbiraj',
        'RUM' => 'Rumjatar',
        'RUK' => 'Rukumkot',
        'SIF' => 'Simara (Birgunj)',
        'SKH' => 'Surkhet',
        'TMI' => 'Tumlingtar',
        'TPJ' => 'Taplejung',
    ];
}

// ─── Nepal airlines ───────────────────────────────────────────────────────────
function ist_get_nepal_airlines() {
    return [
        'U4'  => 'Buddha Air',
        'YT'  => 'Yeti Airlines',
        'TA'  => 'Tara Air',
        'S7'  => 'Summit Air',
        'SHA' => 'Shree Airlines',
        'RA'  => 'Nepal Airlines',
    ];
}

// ─── Remove WordPress version from head ───────────────────────────────────────
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// ─── Custom excerpt length ─────────────────────────────────────────────────────
function ist_excerpt_length() { return 25; }
add_filter( 'excerpt_length', 'ist_excerpt_length' );

function ist_excerpt_more() { return '&hellip;'; }
add_filter( 'excerpt_more', 'ist_excerpt_more' );

// ─── Body class additions ──────────────────────────────────────────────────────
function ist_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'ist-single';
    }
    if ( is_front_page() ) {
        $classes[] = 'ist-home';
    }
    return $classes;
}
add_filter( 'body_class', 'ist_body_classes' );

// ─── WooCommerce: remove default sidebar ──────────────────────────────────────
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// ─── Defer non-critical JS ────────────────────────────────────────────────────
function ist_defer_scripts( $tag, $handle, $src ) {
    $defer = [ 'ist-animations', 'ist-main', 'ist-flight-search', 'ist-flight-results', 'ist-booking-modal', 'ist-package-filter', 'ist-itinerary-builder' ];
    if ( in_array( $handle, $defer, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'ist_defer_scripts', 10, 3 );

// ─── Reading time helper ───────────────────────────────────────────────────────
function ist_reading_time( $post_id = null ) {
    $content    = get_post_field( 'post_content', $post_id ?: get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = (int) ceil( $word_count / 200 );
    return $minutes . ' min read';
}

// ─── Breadcrumb helper (used across templates) ────────────────────────────────
function ist_breadcrumb() {
    $items = [ '<a href="' . home_url() . '">' . __( 'Home', 'infinity-sky' ) . '</a>' ];

    if ( is_singular( 'ist_package' ) ) {
        $items[] = '<a href="' . home_url( '/packages' ) . '">' . __( 'Packages', 'infinity-sky' ) . '</a>';
        $items[] = get_the_title();
    } elseif ( is_singular( 'post' ) ) {
        $items[] = '<a href="' . home_url( '/blog' ) . '">' . __( 'Blog', 'infinity-sky' ) . '</a>';
        $items[] = get_the_title();
    } elseif ( is_page() ) {
        $items[] = get_the_title();
    } elseif ( is_category() || is_tag() || is_archive() ) {
        $items[] = single_cat_title( '', false );
    }

    echo '<nav class="ist-breadcrumb" aria-label="Breadcrumb"><ol>';
    foreach ( $items as $i => $item ) {
        $is_last = ( $i === count( $items ) - 1 );
        echo '<li' . ( $is_last ? ' aria-current="page"' : '' ) . '>' . wp_kses_post( $item );
        if ( ! $is_last ) echo '<span aria-hidden="true"> / </span>';
        echo '</li>';
    }
    echo '</ol></nav>';
}

// ─── Format price helper ──────────────────────────────────────────────────────
function ist_format_price( $price, $currency = 'USD' ) {
    if ( 'USD' === $currency ) {
        return '$' . number_format( (float) $price, 0 );
    }
    return 'NPR ' . number_format( (float) $price, 0 );
}

// ─── Image fallback helper ──────────────────────────────────────────────────────
/**
 * Returns a post's featured image URL, or the Infinity Sky logo mark
 * (never a dead third-party placeholder service) if none is set.
 */
function ist_image_or_logo( int $post_id, string $size = 'ist-portrait' ): array {
    $img_id = get_post_thumbnail_id( $post_id );
    if ( $img_id ) {
        $url = wp_get_attachment_image_url( $img_id, $size );
        if ( $url ) {
            $alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
            return [ 'url' => $url, 'alt' => $alt ?: get_the_title( $post_id ), 'is_logo' => false ];
        }
    }
    return [ 'url' => IST_THEME_URI . '/assets/images/logo.svg', 'alt' => 'Infinity Sky Travels', 'is_logo' => true ];
}

// ─── Difficulty badge helper ───────────────────────────────────────────────────
function ist_difficulty_badge( $difficulty ) {
    $map = [
        'easy'        => [ 'label' => 'Easy',        'class' => 'badge-easy' ],
        'moderate'    => [ 'label' => 'Moderate',    'class' => 'badge-moderate' ],
        'challenging' => [ 'label' => 'Challenging', 'class' => 'badge-challenging' ],
        'strenuous'   => [ 'label' => 'Strenuous',   'class' => 'badge-strenuous' ],
    ];
    $key  = strtolower( $difficulty );
    $data = $map[ $key ] ?? [ 'label' => ucfirst( $key ), 'class' => 'badge-moderate' ];
    return '<span class="badge ' . esc_attr( $data['class'] ) . '">' . esc_html( $data['label'] ) . '</span>';
}

// ─── Custom login URL (companion to WPS Hide Login) ───────────────────────────
function ist_login_redirect( $url, $query, $user ) {
    if ( $user && is_a( $user, 'WP_User' ) ) {
        if ( $user->has_cap( 'manage_options' ) ) {
            return admin_url();
        }
        return home_url();
    }
    return $url;
}
add_filter( 'login_redirect', 'ist_login_redirect', 10, 3 );

// ─── REST API: custom endpoints ───────────────────────────────────────────────
function ist_register_rest_routes() {
    register_rest_route( 'ist/v1', '/airports', [
        'methods'             => 'GET',
        'callback'            => fn() => rest_ensure_response( ist_get_nepal_airports() ),
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( 'ist/v1', '/airlines', [
        'methods'             => 'GET',
        'callback'            => fn() => rest_ensure_response( ist_get_nepal_airlines() ),
        'permission_callback' => '__return_true',
    ] );
}
add_action( 'rest_api_init', 'ist_register_rest_routes' );

// ─── Mail: set from name and email ────────────────────────────────────────────
function ist_mail_from_name() { return 'Infinity Sky Travels'; }
add_filter( 'wp_mail_from_name', 'ist_mail_from_name' );

function ist_mail_from() { return 'infinityskytravels8@gmail.com'; }
add_filter( 'wp_mail_from', 'ist_mail_from' );

// ─── Thumbnail support for nav menus ─────────────────────────────────────────
add_filter( 'nav_menu_css_class', function( $classes, $item ) {
    if ( $item->object_id === get_the_ID() ) {
        $classes[] = 'current-menu-item';
    }
    return $classes;
}, 10, 2 );
