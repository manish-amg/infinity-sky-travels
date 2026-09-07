<?php
/**
 * Custom Post Types: ist_route (Popular Domestic Routes),
 * ist_activity (Beyond Trekking activities), ist_inquiry (captured leads).
 *
 * These make the homepage "Popular Domestic Routes" and "Beyond Trekking"
 * sections fully editable from wp-admin instead of hardcoded PHP arrays,
 * and give every Plan My Trip / booking / contact submission a permanent
 * record in the backend (not just an outbound email that can get lost).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── ist_route CPT — Popular Domestic Routes ──────────────────────────────────
function ist_register_route_cpt() {
    register_post_type( 'ist_route', [
        'labels' => [
            'name'               => __( 'Domestic Routes', 'infinity-sky' ),
            'singular_name'      => __( 'Route',            'infinity-sky' ),
            'add_new_item'       => __( 'Add New Route',    'infinity-sky' ),
            'edit_item'          => __( 'Edit Route',       'infinity-sky' ),
            'search_items'       => __( 'Search Routes',    'infinity-sky' ),
            'not_found'          => __( 'No routes found',  'infinity-sky' ),
            'menu_name'          => __( 'Domestic Routes',  'infinity-sky' ),
        ],
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'has_archive'        => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-airplane',
        'supports'           => [ 'title', 'thumbnail', 'page-attributes' ],
    ] );
}
add_action( 'init', 'ist_register_route_cpt' );

// ─── ist_activity CPT — Beyond Trekking / Activities in Nepal ─────────────────
function ist_register_activity_cpt() {
    register_post_type( 'ist_activity', [
        'labels' => [
            'name'               => __( 'Activities', 'infinity-sky' ),
            'singular_name'      => __( 'Activity',    'infinity-sky' ),
            'add_new_item'       => __( 'Add New Activity', 'infinity-sky' ),
            'edit_item'          => __( 'Edit Activity',    'infinity-sky' ),
            'search_items'       => __( 'Search Activities','infinity-sky' ),
            'not_found'          => __( 'No activities found', 'infinity-sky' ),
            'menu_name'          => __( 'Activities',  'infinity-sky' ),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'activities', 'with_front' => false ],
        'has_archive'        => 'activities',
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
    ] );

    register_taxonomy( 'ist_activity_category', 'ist_activity', [
        'labels' => [
            'name'          => __( 'Activity Categories', 'infinity-sky' ),
            'singular_name' => __( 'Category',             'infinity-sky' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'activity-category' ],
    ] );
}
add_action( 'init', 'ist_register_activity_cpt' );

// ─── ist_inquiry CPT — captured leads (Plan My Trip, booking, contact, quote) ─
function ist_register_inquiry_cpt() {
    register_post_type( 'ist_inquiry', [
        'labels' => [
            'name'               => __( 'Trip Inquiries', 'infinity-sky' ),
            'singular_name'      => __( 'Inquiry',         'infinity-sky' ),
            'add_new_item'       => __( 'Add Inquiry',     'infinity-sky' ),
            'edit_item'          => __( 'View Inquiry',    'infinity-sky' ),
            'search_items'       => __( 'Search Inquiries','infinity-sky' ),
            'not_found'          => __( 'No inquiries yet','infinity-sky' ),
            'menu_name'          => __( 'Trip Inquiries',  'infinity-sky' ),
        ],
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_admin_bar'  => true,
        'show_in_rest'       => false,
        'query_var'          => false,
        'rewrite'            => false,
        'has_archive'        => false,
        'capability_type'    => 'post',
        'menu_position'      => 4,
        'menu_icon'          => 'dashicons-email-alt',
        'supports'           => [ 'title' ],
    ] );
}
add_action( 'init', 'ist_register_inquiry_cpt' );

/**
 * Insert one inquiry record. Called from ajax-handlers.php for every
 * lead-capturing form so submissions always land in wp-admin → Trip
 * Inquiries, even if outbound email fails or lands in spam.
 *
 * @param string $type    plan_trip | manual_quote | contact | trek_booking
 * @param string $name    Display name, used as the post title.
 * @param array  $fields  Associative array of submitted field => value.
 * @return int Post ID.
 */
function ist_create_inquiry( string $type, string $name, array $fields ): int {
    $post_id = wp_insert_post( [
        'post_type'   => 'ist_inquiry',
        'post_status' => 'publish',
        'post_title'  => sprintf( '[%s] %s — %s', strtoupper( str_replace( '_', ' ', $type ) ), $name ?: 'Unknown', current_time( 'Y-m-d H:i' ) ),
    ] );

    if ( ! $post_id || is_wp_error( $post_id ) ) return 0;

    update_post_meta( $post_id, 'ist_inquiry_type', $type );
    update_post_meta( $post_id, 'ist_inquiry_status', 'new' );
    update_post_meta( $post_id, 'ist_inquiry_fields', $fields );
    update_post_meta( $post_id, 'ist_inquiry_email', $fields['email'] ?? '' );

    return $post_id;
}

// ─── Admin columns: Trip Inquiries list table ─────────────────────────────────
add_filter( 'manage_ist_inquiry_posts_columns', function( $columns ) {
    $columns = [
        'cb'          => $columns['cb'],
        'title'       => __( 'Inquiry', 'infinity-sky' ),
        'ist_type'    => __( 'Type', 'infinity-sky' ),
        'ist_email'   => __( 'Email', 'infinity-sky' ),
        'ist_status'  => __( 'Status', 'infinity-sky' ),
        'date'        => __( 'Received', 'infinity-sky' ),
    ];
    return $columns;
} );

add_action( 'manage_ist_inquiry_posts_custom_column', function( $column, $post_id ) {
    switch ( $column ) {
        case 'ist_type':
            $type = get_post_meta( $post_id, 'ist_inquiry_type', true );
            echo esc_html( ucwords( str_replace( '_', ' ', $type ) ) );
            break;
        case 'ist_email':
            $email = get_post_meta( $post_id, 'ist_inquiry_email', true );
            echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '&mdash;';
            break;
        case 'ist_status':
            $status = get_post_meta( $post_id, 'ist_inquiry_status', true ) ?: 'new';
            $colors = [ 'new' => '#E8751A', 'contacted' => '#29ABE2', 'closed' => '#888' ];
            $color  = $colors[ $status ] ?? '#888';
            echo '<span style="display:inline-block;padding:2px 10px;border-radius:12px;font-size:11px;font-weight:600;color:#fff;background:' . esc_attr( $color ) . ';text-transform:uppercase;">' . esc_html( $status ) . '</span>';
            break;
    }
}, 10, 2 );

// ─── Meta box: full submitted details + status control ────────────────────────
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'ist_inquiry_details', __( 'Submitted Details', 'infinity-sky' ), 'ist_inquiry_details_metabox', 'ist_inquiry', 'normal', 'high' );
    add_meta_box( 'ist_inquiry_status_box', __( 'Status', 'infinity-sky' ), 'ist_inquiry_status_metabox', 'ist_inquiry', 'side', 'high' );
} );

function ist_inquiry_details_metabox( $post ) {
    $fields = get_post_meta( $post->ID, 'ist_inquiry_fields', true );
    if ( ! is_array( $fields ) || ! $fields ) {
        echo '<p>' . esc_html__( 'No details recorded.', 'infinity-sky' ) . '</p>';
        return;
    }
    echo '<table class="widefat striped"><tbody>';
    foreach ( $fields as $key => $value ) {
        if ( is_array( $value ) ) $value = implode( ', ', $value );
        echo '<tr><th style="width:220px;text-align:left;">' . esc_html( ucwords( str_replace( '_', ' ', $key ) ) ) . '</th><td>' . esc_html( (string) $value ) . '</td></tr>';
    }
    echo '</tbody></table>';
}

function ist_inquiry_status_metabox( $post ) {
    $status = get_post_meta( $post->ID, 'ist_inquiry_status', true ) ?: 'new';
    wp_nonce_field( 'ist_inquiry_status_save', 'ist_inquiry_status_nonce' );
    ?>
    <select name="ist_inquiry_status" style="width:100%;">
        <option value="new" <?php selected( $status, 'new' ); ?>><?php esc_html_e( 'New', 'infinity-sky' ); ?></option>
        <option value="contacted" <?php selected( $status, 'contacted' ); ?>><?php esc_html_e( 'Contacted', 'infinity-sky' ); ?></option>
        <option value="closed" <?php selected( $status, 'closed' ); ?>><?php esc_html_e( 'Closed', 'infinity-sky' ); ?></option>
    </select>
    <?php
}

add_action( 'save_post_ist_inquiry', function( $post_id ) {
    if ( ! isset( $_POST['ist_inquiry_status_nonce'] ) || ! wp_verify_nonce( $_POST['ist_inquiry_status_nonce'], 'ist_inquiry_status_save' ) ) return;
    if ( isset( $_POST['ist_inquiry_status'] ) ) {
        update_post_meta( $post_id, 'ist_inquiry_status', sanitize_text_field( $_POST['ist_inquiry_status'] ) );
    }
} );

/**
 * Sideload a remote image and set it as a post's featured image.
 * Used only for pre-vetted, verified-reachable URLs — never a guess.
 * Failures are silent (post simply falls back to the logo via
 * ist_image_or_logo()), so a slow/blocked remote host never breaks seeding.
 */
function ist_seed_set_thumbnail( int $post_id, string $image_url, string $desc ): void {
    if ( ! function_exists( 'media_sideload_image' ) ) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    $attachment_id = media_sideload_image( $image_url, $post_id, $desc, 'id' );
    if ( ! is_wp_error( $attachment_id ) ) {
        set_post_thumbnail( $post_id, $attachment_id );
        update_post_meta( $attachment_id, '_wp_attachment_image_alt', $desc );
    }
}

// ─── Seed default Routes + Activities content (idempotent) ────────────────────
function ist_seed_routes_and_activities() {
    if ( 'yes' !== get_option( 'ist_routes_activities_seeded' ) ) {

        // Popular Domestic Routes — image URLs are pre-verified, working Unsplash
        // direct-CDN links (not the dead source.unsplash.com redirect service).
        // Routes without a verified photo simply fall back to the Infinity Sky
        // logo via ist_image_or_logo() rather than risk showing a mismatched image.
        $routes = [
            [ 'title' => 'Kathmandu → Lukla',     'from_code' => 'KTM', 'to_code' => 'LUA', 'from_name' => 'Kathmandu', 'to_name' => 'Lukla',               'duration' => '35 min', 'price' => 89,  'airlines' => 'Tara Air, Summit Air',      'image' => 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Tenzing-Hillary Airport, Lukla, Nepal' ],
            [ 'title' => 'Kathmandu → Pokhara',    'from_code' => 'KTM', 'to_code' => 'PKR', 'from_name' => 'Kathmandu', 'to_name' => 'Pokhara',             'duration' => '25 min', 'price' => 79,  'airlines' => 'Buddha Air, Yeti Airlines', 'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Phewa Lake, Pokhara, Nepal' ],
            [ 'title' => 'Kathmandu → Bharatpur',  'from_code' => 'KTM', 'to_code' => 'MEY', 'from_name' => 'Kathmandu', 'to_name' => 'Bharatpur (Chitwan)', 'duration' => '20 min', 'price' => 69,  'airlines' => 'Buddha Air',                'image' => '', 'image_desc' => 'Bharatpur, Chitwan, Nepal' ],
            [ 'title' => 'Kathmandu → Biratnagar', 'from_code' => 'KTM', 'to_code' => 'BIR', 'from_name' => 'Kathmandu', 'to_name' => 'Biratnagar',          'duration' => '40 min', 'price' => 95,  'airlines' => 'Buddha Air, Yeti Airlines', 'image' => 'https://images.unsplash.com/photo-1469521669194-babb45599def?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Eastern Nepal Himalayas' ],
            [ 'title' => 'Kathmandu → Nepalgunj',  'from_code' => 'KTM', 'to_code' => 'KEP', 'from_name' => 'Kathmandu', 'to_name' => 'Nepalgunj',           'duration' => '55 min', 'price' => 110, 'airlines' => 'Buddha Air, Yeti Airlines', 'image' => 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Western Nepal landscape' ],
            [ 'title' => 'Kathmandu → Janakpur',   'from_code' => 'KTM', 'to_code' => 'JKR', 'from_name' => 'Kathmandu', 'to_name' => 'Janakpur',            'duration' => '35 min', 'price' => 85,  'airlines' => 'Yeti Airlines',             'image' => 'https://images.unsplash.com/photo-1562778612-e1e0cda9915c?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Janakpur Dham temple, Nepal' ],
        ];
        foreach ( $routes as $i => $r ) {
            $id = wp_insert_post( [
                'post_type'   => 'ist_route',
                'post_status' => 'publish',
                'post_title'  => $r['title'],
                'menu_order'  => $i,
            ] );
            if ( $id && ! is_wp_error( $id ) ) {
                update_post_meta( $id, 'ist_route_from_code', $r['from_code'] );
                update_post_meta( $id, 'ist_route_to_code',   $r['to_code'] );
                update_post_meta( $id, 'ist_route_from_name', $r['from_name'] );
                update_post_meta( $id, 'ist_route_to_name',   $r['to_name'] );
                update_post_meta( $id, 'ist_route_duration',  $r['duration'] );
                update_post_meta( $id, 'ist_route_price',     $r['price'] );
                update_post_meta( $id, 'ist_route_airlines',  $r['airlines'] );
                if ( $r['image'] ) {
                    ist_seed_set_thumbnail( $id, $r['image'], $r['image_desc'] );
                }
            }
        }

        // Beyond Trekking / Activities in Nepal — no stock photo is assigned
        // here on purpose: rather than guess a mismatched image, every activity
        // starts on the Infinity Sky logo fallback until a real photo is
        // uploaded via Featured Image in wp-admin → Activities.
        $activities = [
            [ 'title' => 'Paragliding in Pokhara',           'category' => 'Adventure', 'location' => 'Pokhara',          'duration' => 'Half day', 'price' => 90,  'excerpt' => 'Tandem paraglide over Phewa Lake with the Annapurna range as your backdrop.' ],
            [ 'title' => 'Chitwan Jungle Safari',             'category' => 'Wildlife',  'location' => 'Chitwan',          'duration' => '2–3 days','price' => 180, 'excerpt' => 'Jeep and canoe safari through Chitwan National Park — rhinos, gharials, and Tharu culture.' ],
            [ 'title' => 'Kathmandu Cultural Heritage Tour',  'category' => 'Culture',   'location' => 'Kathmandu',        'duration' => 'Full day','price' => 45,  'excerpt' => 'Durbar Square, Pashupatinath, Boudhanath and Swayambhunath with a licensed guide.' ],
            [ 'title' => 'White-Water Rafting, Trishuli',     'category' => 'Adventure', 'location' => 'Trishuli',         'duration' => '1 day',   'price' => 55,  'excerpt' => 'Grade III–IV rapids on the Trishuli River, an easy add-on between Kathmandu and Pokhara.' ],
            [ 'title' => 'Mountain Biking, Kathmandu Valley', 'category' => 'Adventure', 'location' => 'Kathmandu Valley', 'duration' => 'Full day','price' => 60,  'excerpt' => 'Ride ancient trade trails through Newari villages around the Kathmandu Valley rim.' ],
            [ 'title' => 'Bungee Jump, The Last Resort',      'category' => 'Adventure', 'location' => 'Bhote Koshi',      'duration' => 'Half day','price' => 100, 'excerpt' => 'A 160m freefall over the Bhote Koshi gorge on the way to the Tibet border.' ],
        ];
        foreach ( $activities as $i => $a ) {
            $id = wp_insert_post( [
                'post_type'    => 'ist_activity',
                'post_status'  => 'publish',
                'post_title'   => $a['title'],
                'post_excerpt' => $a['excerpt'],
                'menu_order'   => $i,
            ] );
            if ( $id && ! is_wp_error( $id ) ) {
                update_post_meta( $id, 'ist_activity_location', $a['location'] );
                update_post_meta( $id, 'ist_activity_duration', $a['duration'] );
                update_post_meta( $id, 'ist_activity_price',    $a['price'] );

                if ( ! term_exists( $a['category'], 'ist_activity_category' ) ) {
                    wp_insert_term( $a['category'], 'ist_activity_category', [ 'slug' => sanitize_title( $a['category'] ) ] );
                }
                wp_set_object_terms( $id, $a['category'], 'ist_activity_category' );
            }
        }

        update_option( 'ist_routes_activities_seeded', 'yes' );
    }
}
add_action( 'init', 'ist_seed_routes_and_activities', 20 );

// ─── Flush rewrite rules once these CPTs are registered ───────────────────────
add_action( 'after_switch_theme', function() {
    ist_register_route_cpt();
    ist_register_activity_cpt();
    ist_register_inquiry_cpt();
    flush_rewrite_rules();
} );
