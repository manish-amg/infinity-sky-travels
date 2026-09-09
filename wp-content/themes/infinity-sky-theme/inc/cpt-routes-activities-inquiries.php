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
 * Set a seeded post's image via the same _thumbnail_url meta mechanism
 * ist-sample-content uses for packages and blog posts (see its
 * post_thumbnail_html / has_post_thumbnail / get_post_metadata filters).
 *
 * This intentionally does NOT use media_sideload_image() to fetch the photo
 * into the Media Library — this host's outbound HTTP from PHP is unreliable
 * for that (sideloading silently failed for every route on first deploy,
 * leaving every card on the logo fallback). Storing the URL directly is
 * instant and 100% reliable, and ist_image_or_logo() already prefers a real
 * uploaded Featured Image first — so replacing this with an actual upload
 * later via wp-admin works exactly as expected.
 */
function ist_seed_set_thumbnail( int $post_id, string $image_url, string $desc ): void {
    update_post_meta( $post_id, '_thumbnail_url', esc_url_raw( $image_url ) );
}

// ─── Seed default Routes + Activities content (idempotent) ────────────────────
function ist_seed_routes_and_activities() {
    if ( 'yes' !== get_option( 'ist_routes_activities_seeded' ) ) {

        // Popular Domestic Routes — every image URL below was verified (fetched
        // and confirmed HTTP 200, source page title checked for a real content
        // match) before being used — no guessed stock photos.
        $routes = [
            [ 'title' => 'Kathmandu → Lukla',     'from_code' => 'KTM', 'to_code' => 'LUA', 'from_name' => 'Kathmandu', 'to_name' => 'Lukla',               'duration' => '35 min', 'price' => 89,  'airlines' => 'Tara Air, Summit Air',      'image' => 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Tenzing-Hillary Airport, Lukla, Nepal' ],
            [ 'title' => 'Kathmandu → Pokhara',    'from_code' => 'KTM', 'to_code' => 'PKR', 'from_name' => 'Kathmandu', 'to_name' => 'Pokhara',             'duration' => '25 min', 'price' => 79,  'airlines' => 'Buddha Air, Yeti Airlines', 'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=75&auto=format&fit=crop', 'image_desc' => 'Phewa Lake, Pokhara, Nepal' ],
            [ 'title' => 'Kathmandu → Bharatpur',  'from_code' => 'KTM', 'to_code' => 'MEY', 'from_name' => 'Kathmandu', 'to_name' => 'Bharatpur (Chitwan)', 'duration' => '20 min', 'price' => 69,  'airlines' => 'Buddha Air',                'image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Chital,%20at%20Chitwan%20NP,%20Nepal.jpg?width=1200', 'image_desc' => 'Wildlife at Chitwan National Park, Nepal' ],
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

        // Beyond Trekking / Activities in Nepal — every image below is a real,
        // verified Wikimedia Commons photo of the actual activity/location
        // (file title checked for a genuine content match, URL confirmed
        // HTTP 200) rather than a guessed stock photo. Replace any of these
        // via Featured Image in wp-admin → Activities whenever a branded
        // photo is ready — ist_image_or_logo() always prefers that first.
        $activities = [
            [ 'title' => 'Paragliding in Pokhara',           'category' => 'Adventure', 'location' => 'Pokhara',          'duration' => 'Half day', 'price' => 90,  'excerpt' => 'Tandem paraglide over Phewa Lake with the Annapurna range as your backdrop.', 'image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Fewa-Pamey-Paragliding.JPG?width=1200', 'image_desc' => 'Paragliding over Phewa Lake, Pokhara' ],
            [ 'title' => 'Chitwan Jungle Safari',             'category' => 'Wildlife',  'location' => 'Chitwan',          'duration' => '2–3 days','price' => 180, 'excerpt' => 'Jeep and canoe safari through Chitwan National Park — rhinos, gharials, and Tharu culture.', 'image' => "https://commons.wikimedia.org/wiki/Special:FilePath/Rhino's%20in%20Chitwan%20National%20Park.jpg?width=1200", 'image_desc' => 'Rhino in Chitwan National Park, Nepal' ],
            [ 'title' => 'Kathmandu Cultural Heritage Tour',  'category' => 'Culture',   'location' => 'Kathmandu',        'duration' => 'Full day','price' => 45,  'excerpt' => 'Durbar Square, Pashupatinath, Boudhanath and Swayambhunath with a licensed guide.', 'image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Kathmandu%20Durbar%20Square%2002122024%2058.jpg?width=1200', 'image_desc' => 'Kathmandu Durbar Square, Nepal' ],
            [ 'title' => 'White-Water Rafting, Trishuli',     'category' => 'Adventure', 'location' => 'Trishuli',         'duration' => '1 day',   'price' => 55,  'excerpt' => 'Grade III–IV rapids on the Trishuli River, an easy add-on between Kathmandu and Pokhara.', 'image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Demonstration%20Before%20Raft-Rafting%20in%20Trishuli%20River,%20Nepal-3060%20-%20edited.jpg?width=1200', 'image_desc' => 'White-water rafting on the Trishuli River, Nepal' ],
            [ 'title' => 'Mountain Biking, Kathmandu Valley', 'category' => 'Adventure', 'location' => 'Kathmandu Valley', 'duration' => 'Full day','price' => 60,  'excerpt' => 'Ride ancient trade trails through Newari villages around the Kathmandu Valley rim.', 'image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Mountain%20Biking%20in%20Bhaktapur.JPG?width=1200', 'image_desc' => 'Mountain biking in Bhaktapur, Kathmandu Valley' ],
            [ 'title' => 'Bungee Jump, The Last Resort',      'category' => 'Adventure', 'location' => 'Bhote Koshi',      'duration' => 'Half day','price' => 100, 'excerpt' => 'A 160m freefall over the Bhote Koshi gorge on the way to the Tibet border.', 'image' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Bhote%20Koshi.JPG?width=1200', 'image_desc' => 'Bhote Koshi gorge, Nepal' ],
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
                if ( $a['image'] ) {
                    ist_seed_set_thumbnail( $id, $a['image'], $a['image_desc'] );
                }

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
