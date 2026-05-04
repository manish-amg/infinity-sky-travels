<?php
/**
 * Plugin Name:  IST Itinerary Builder
 * Description:  Saves draft custom itineraries from Plan My Trip form submissions to a CPT, with admin management and email threading.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 * Text Domain:  ist-itin
 */

defined( 'ABSPATH' ) || exit;

define( 'IST_ITIN_VERSION', '1.0.0' );
define( 'IST_ITIN_DIR',     plugin_dir_path( __FILE__ ) );
define( 'IST_ITIN_URL',     plugin_dir_url(  __FILE__ ) );

// ─── Register CPT: ist_inquiry ────────────────────────────────────────────────
function ist_itin_register_cpt() {
    register_post_type( 'ist_inquiry', [
        'label'               => __( 'Trip Inquiries', 'ist-itin' ),
        'labels'              => [
            'name'          => __( 'Trip Inquiries',    'ist-itin' ),
            'singular_name' => __( 'Trip Inquiry',      'ist-itin' ),
            'add_new_item'  => __( 'New Inquiry',       'ist-itin' ),
            'edit_item'     => __( 'Manage Inquiry',    'ist-itin' ),
            'search_items'  => __( 'Search Inquiries',  'ist-itin' ),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-clipboard',
        'menu_position'       => 26,
        'supports'            => [ 'title', 'custom-fields' ],
        'capability_type'     => 'post',
        'map_meta_cap'        => true,
    ] );
}
add_action( 'init', 'ist_itin_register_cpt' );

// ─── Save inquiry from AJAX plan-trip submission ──────────────────────────────
add_action( 'wp_ajax_ist_plan_trip',        'ist_itin_save_inquiry', 5 );
add_action( 'wp_ajax_nopriv_ist_plan_trip', 'ist_itin_save_inquiry', 5 );

function ist_itin_save_inquiry() {
    // Nonce already checked by theme handler; we run at priority 5 (before theme's 10)
    if ( ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ?? '' ), 'ist_plan_trip' ) ) {
        return;
    }

    $first   = sanitize_text_field( $_POST['first_name'] ?? '' );
    $last    = sanitize_text_field( $_POST['last_name']  ?? '' );
    $email   = sanitize_email(      $_POST['email']      ?? '' );
    $region  = sanitize_text_field( $_POST['region']     ?? '' );
    $date    = sanitize_text_field( $_POST['depart_date']?? '' );

    if ( ! $first || ! $email ) return;

    $title = "$first $last — $region — $date";

    $post_id = wp_insert_post( [
        'post_type'   => 'ist_inquiry',
        'post_title'  => $title,
        'post_status' => 'publish',
    ] );

    if ( is_wp_error( $post_id ) ) return;

    // Save all form fields as post meta
    $fields = [
        'first_name', 'last_name', 'email', 'phone', 'nationality',
        'region', 'base_package', 'depart_date', 'date_flexibility',
        'duration', 'accommodation', 'budget', 'fitness_level', 'experience',
        'group_type', 'special_requests', 'referral_source',
    ];
    foreach ( $fields as $f ) {
        if ( isset( $_POST[ $f ] ) ) {
            update_post_meta( $post_id, '_itin_' . $f, sanitize_text_field( $_POST[ $f ] ) );
        }
    }

    // Pax
    if ( isset( $_POST['pax'] ) && is_array( $_POST['pax'] ) ) {
        update_post_meta( $post_id, '_itin_pax', array_map( 'absint', $_POST['pax'] ) );
    }

    // Activities
    if ( isset( $_POST['activities'] ) && is_array( $_POST['activities'] ) ) {
        update_post_meta( $post_id, '_itin_activities', array_map( 'sanitize_text_field', $_POST['activities'] ) );
    }

    // Status
    update_post_meta( $post_id, '_itin_status',   'new' );
    update_post_meta( $post_id, '_itin_submitted', current_time( 'mysql' ) );
}

// ─── Admin columns ────────────────────────────────────────────────────────────
add_filter( 'manage_ist_inquiry_posts_columns', function ( $cols ) {
    return [
        'cb'           => '<input type="checkbox">',
        'title'        => __( 'Inquiry',       'ist-itin' ),
        'itin_email'   => __( 'Email',         'ist-itin' ),
        'itin_region'  => __( 'Region',        'ist-itin' ),
        'itin_date'    => __( 'Departure',     'ist-itin' ),
        'itin_status'  => __( 'Status',        'ist-itin' ),
        'itin_sub'     => __( 'Received',      'ist-itin' ),
    ];
} );

add_action( 'manage_ist_inquiry_posts_custom_column', function ( $col, $post_id ) {
    switch ( $col ) {
        case 'itin_email':
            $e = get_post_meta( $post_id, '_itin_email', true );
            echo '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>';
            break;
        case 'itin_region':
            echo esc_html( get_post_meta( $post_id, '_itin_region', true ) );
            break;
        case 'itin_date':
            echo esc_html( get_post_meta( $post_id, '_itin_depart_date', true ) );
            break;
        case 'itin_status':
            $status = get_post_meta( $post_id, '_itin_status', true ) ?: 'new';
            $colors = [
                'new'      => '#e8751a',
                'quoted'   => '#2563eb',
                'booked'   => '#16a34a',
                'declined' => '#6b7280',
            ];
            $col_hex = $colors[ $status ] ?? '#333';
            printf( '<span style="background:%s;color:#fff;padding:2px 8px;border-radius:12px;font-size:12px;font-weight:700;">%s</span>',
                esc_attr( $col_hex ), esc_html( ucfirst( $status ) ) );
            break;
        case 'itin_sub':
            echo esc_html( get_post_meta( $post_id, '_itin_submitted', true ) );
            break;
    }
}, 10, 2 );

// ─── Admin meta box: full inquiry details + status changer ───────────────────
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'ist_inquiry_details', __( 'Inquiry Details', 'ist-itin' ), 'ist_itin_meta_box_cb', 'ist_inquiry', 'normal', 'high' );
} );

function ist_itin_meta_box_cb( $post ) {
    $fields = [
        'first_name'         => __( 'First Name',           'ist-itin' ),
        'last_name'          => __( 'Last Name',            'ist-itin' ),
        'email'              => __( 'Email',                 'ist-itin' ),
        'phone'              => __( 'Phone / WhatsApp',     'ist-itin' ),
        'nationality'        => __( 'Nationality',           'ist-itin' ),
        'region'             => __( 'Region',                'ist-itin' ),
        'depart_date'        => __( 'Departure Date',        'ist-itin' ),
        'date_flexibility'   => __( 'Date Flexibility',     'ist-itin' ),
        'duration'           => __( 'Duration',              'ist-itin' ),
        'accommodation'      => __( 'Accommodation Style',  'ist-itin' ),
        'budget'             => __( 'Budget per Person',    'ist-itin' ),
        'fitness_level'      => __( 'Fitness Level',        'ist-itin' ),
        'experience'         => __( 'Trekking Experience',  'ist-itin' ),
        'group_type'         => __( 'Travelling As',        'ist-itin' ),
        'referral_source'    => __( 'Heard About Us From',  'ist-itin' ),
        'special_requests'   => __( 'Special Requests',     'ist-itin' ),
    ];

    $status  = get_post_meta( $post->ID, '_itin_status', true ) ?: 'new';
    $pax     = get_post_meta( $post->ID, '_itin_pax', true )    ?: [];
    $acts    = get_post_meta( $post->ID, '_itin_activities', true ) ?: [];
    $email   = get_post_meta( $post->ID, '_itin_email', true );
    $fname   = get_post_meta( $post->ID, '_itin_first_name', true );

    wp_nonce_field( 'ist_itin_save', 'ist_itin_nonce' );
    echo '<table style="width:100%;border-collapse:collapse;">';

    foreach ( $fields as $key => $label ) {
        $val = get_post_meta( $post->ID, "_itin_$key", true );
        echo '<tr>';
        echo '<th style="text-align:left;padding:8px 12px;width:200px;background:#f9f9f9;border:1px solid #e2e8f0;">' . esc_html( $label ) . '</th>';
        echo '<td style="padding:8px 12px;border:1px solid #e2e8f0;">' . esc_html( $val ?: '—' ) . '</td>';
        echo '</tr>';
    }

    // Pax row
    if ( $pax ) {
        echo '<tr><th style="text-align:left;padding:8px 12px;background:#f9f9f9;border:1px solid #e2e8f0;">Group Size</th>';
        $pax_str = implode( ', ', array_map( fn($k, $v) => "$v $k", array_keys( $pax ), $pax ) );
        echo '<td style="padding:8px 12px;border:1px solid #e2e8f0;">' . esc_html( $pax_str ) . '</td></tr>';
    }

    // Activities row
    if ( $acts ) {
        echo '<tr><th style="text-align:left;padding:8px 12px;background:#f9f9f9;border:1px solid #e2e8f0;">Activities</th>';
        echo '<td style="padding:8px 12px;border:1px solid #e2e8f0;">' . esc_html( implode( ', ', $acts ) ) . '</td></tr>';
    }

    echo '</table>';

    // Status changer
    echo '<p style="margin-top:16px;"><strong>Update Status:</strong> ';
    echo '<select name="ist_itin_status" style="margin-left:8px;">';
    foreach ( [ 'new', 'quoted', 'booked', 'declined' ] as $s ) {
        printf( '<option value="%s"%s>%s</option>', esc_attr( $s ), selected( $status, $s, false ), esc_html( ucfirst( $s ) ) );
    }
    echo '</select></p>';

    // Quick reply link
    if ( $email ) {
        $subject = rawurlencode( 'Your Custom Trek Itinerary — Infinity Sky Travels' );
        $body    = rawurlencode( "Dear $fname,\n\nThank you for your enquiry. Here is your custom itinerary proposal:\n\n[Paste itinerary here]\n\nBest regards,\nInfinity Sky Travels Team" );
        echo '<p><a href="mailto:' . esc_attr( $email ) . '?subject=' . $subject . '&body=' . $body . '" class="button button-primary">✉ Send Itinerary Email</a></p>';
    }
}

add_action( 'save_post_ist_inquiry', function ( $post_id ) {
    if ( ! isset( $_POST['ist_itin_nonce'] ) || ! wp_verify_nonce( $_POST['ist_itin_nonce'], 'ist_itin_save' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['ist_itin_status'] ) ) {
        update_post_meta( $post_id, '_itin_status', sanitize_text_field( $_POST['ist_itin_status'] ) );
    }
} );

// ─── Admin notification email to team on new inquiry ─────────────────────────
add_action( 'save_post_ist_inquiry', function ( $post_id ) {
    if ( get_post_status( $post_id ) !== 'publish' ) return;
    if ( get_post_meta( $post_id, '_itin_notified', true ) ) return;
    update_post_meta( $post_id, '_itin_notified', 1 );

    $title = get_the_title( $post_id );
    $link  = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
    wp_mail(
        get_option( 'admin_email' ),
        "New Custom Trip Inquiry: $title",
        "A new trip inquiry has been submitted.\n\nView and manage it in WP Admin:\n$link",
        [ 'Reply-To: noreply@infinityskytravels.com' ]
    );
}, 20 );
