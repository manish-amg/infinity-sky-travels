<?php
/**
 * Custom Post Types: ist_package + add-on taxonomy.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── ist_package CPT ──────────────────────────────────────────────────────────
function ist_register_package_cpt() {
    $labels = [
        'name'               => __( 'Packages',             'infinity-sky' ),
        'singular_name'      => __( 'Package',              'infinity-sky' ),
        'add_new'            => __( 'Add New Package',      'infinity-sky' ),
        'add_new_item'       => __( 'Add New Package',      'infinity-sky' ),
        'edit_item'          => __( 'Edit Package',         'infinity-sky' ),
        'new_item'           => __( 'New Package',          'infinity-sky' ),
        'view_item'          => __( 'View Package',         'infinity-sky' ),
        'search_items'       => __( 'Search Packages',      'infinity-sky' ),
        'not_found'          => __( 'No packages found',    'infinity-sky' ),
        'not_found_in_trash' => __( 'No packages in trash', 'infinity-sky' ),
        'menu_name'          => __( 'Packages',             'infinity-sky' ),
    ];

    register_post_type( 'ist_package', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'packages', 'with_front' => false ],
        'capability_type'    => 'post',
        'has_archive'        => 'packages',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-location-alt',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ],
    ] );
}
add_action( 'init', 'ist_register_package_cpt' );

// ─── Package Region taxonomy ──────────────────────────────────────────────────
function ist_register_taxonomies() {

    register_taxonomy( 'ist_region', 'ist_package', [
        'labels'            => [
            'name'          => __( 'Regions',    'infinity-sky' ),
            'singular_name' => __( 'Region',     'infinity-sky' ),
            'search_items'  => __( 'Search Regions', 'infinity-sky' ),
            'all_items'     => __( 'All Regions', 'infinity-sky' ),
            'edit_item'     => __( 'Edit Region', 'infinity-sky' ),
            'add_new_item'  => __( 'Add New Region', 'infinity-sky' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'trek-region' ],
    ] );

    register_taxonomy( 'ist_difficulty', 'ist_package', [
        'labels'            => [
            'name'          => __( 'Difficulty Levels', 'infinity-sky' ),
            'singular_name' => __( 'Difficulty',        'infinity-sky' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'difficulty' ],
    ] );

    register_taxonomy( 'ist_traveller_type', 'ist_package', [
        'labels'            => [
            'name'          => __( 'Traveller Types', 'infinity-sky' ),
            'singular_name' => __( 'Traveller Type',  'infinity-sky' ),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'traveller-type' ],
    ] );
}
add_action( 'init', 'ist_register_taxonomies' );

// ─── Seed default taxonomy terms on theme activation ─────────────────────────
function ist_seed_taxonomy_terms() {
    $regions = [ 'Everest', 'Annapurna', 'Langtang', 'Mustang', 'Western Nepal', 'Central Nepal', 'Eastern Nepal' ];
    foreach ( $regions as $r ) {
        if ( ! term_exists( $r, 'ist_region' ) ) {
            wp_insert_term( $r, 'ist_region', [ 'slug' => sanitize_title( $r ) ] );
        }
    }

    $difficulties = [ 'Easy', 'Moderate', 'Challenging', 'Strenuous' ];
    foreach ( $difficulties as $d ) {
        if ( ! term_exists( $d, 'ist_difficulty' ) ) {
            wp_insert_term( $d, 'ist_difficulty', [ 'slug' => strtolower( $d ) ] );
        }
    }

    $types = [ 'Backpacker', 'Mid-Range', 'Luxury' ];
    foreach ( $types as $t ) {
        if ( ! term_exists( $t, 'ist_traveller_type' ) ) {
            wp_insert_term( $t, 'ist_traveller_type', [ 'slug' => sanitize_title( $t ) ] );
        }
    }
}
add_action( 'after_switch_theme', 'ist_seed_taxonomy_terms' );

// ─── Flush rewrite rules on CPT register ─────────────────────────────────────
function ist_flush_rewrite_rules() {
    ist_register_package_cpt();
    ist_register_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ist_flush_rewrite_rules' );
add_action( 'after_switch_theme', function() { flush_rewrite_rules(); } );
