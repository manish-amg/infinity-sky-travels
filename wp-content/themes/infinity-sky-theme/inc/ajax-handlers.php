<?php
/**
 * AJAX handlers — flight search, package filter, manual quote form.
 * All handlers verify nonce and sanitize input.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── Flight Search (Duffel API) ───────────────────────────────────────────────
function ist_ajax_flight_search() {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $from      = strtoupper( sanitize_text_field( $_POST['from']      ?? '' ) );
    $to        = strtoupper( sanitize_text_field( $_POST['to']        ?? '' ) );
    $date      = sanitize_text_field( $_POST['date']      ?? '' );
    $adults    = absint( $_POST['adults']    ?? 1 );
    $children  = absint( $_POST['children']  ?? 0 );
    $infants   = absint( $_POST['infants']   ?? 0 );
    $ret_date  = sanitize_text_field( $_POST['return_date'] ?? '' );

    if ( ! $from || ! $to || ! $date ) {
        wp_send_json_error( [ 'message' => __( 'Missing required search parameters.', 'infinity-sky' ) ], 400 );
    }

    // Validate date format
    if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid date format.', 'infinity-sky' ) ], 400 );
    }

    $result = IST_Duffel_API::search_flights( $from, $to, $date, $adults, $ret_date, $children, $infants );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( [
            'message'  => $result->get_error_message(),
            'fallback' => true,
        ], 503 );
    }

    wp_send_json_success( $result );
}
add_action( 'wp_ajax_ist_flight_search',        'ist_ajax_flight_search' );
add_action( 'wp_ajax_nopriv_ist_flight_search', 'ist_ajax_flight_search' );

// ─── Package Filter (AJAX) ────────────────────────────────────────────────────
function ist_ajax_filter_packages() {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $region      = sanitize_text_field( $_POST['region']      ?? '' );
    $difficulty  = sanitize_text_field( $_POST['difficulty']  ?? '' );
    $duration    = sanitize_text_field( $_POST['duration']    ?? '' );
    $budget      = sanitize_text_field( $_POST['budget']      ?? '' );
    $type        = sanitize_text_field( $_POST['type']        ?? '' );
    $page        = absint( $_POST['paged']  ?? 1 );

    $args = [
        'post_type'      => 'ist_package',
        'posts_per_page' => 9,
        'paged'          => $page,
        'post_status'    => 'publish',
        'meta_query'     => [],
        'tax_query'      => [],
    ];

    if ( $region ) {
        $args['tax_query'][] = [ 'taxonomy' => 'ist_region', 'field' => 'slug', 'terms' => $region ];
    }
    if ( $difficulty ) {
        $args['meta_query'][] = [ 'key' => 'ist_difficulty_level', 'value' => $difficulty, 'compare' => '=' ];
    }
    if ( $type ) {
        $args['meta_query'][] = [ 'key' => 'ist_traveller_type', 'value' => '"' . $type . '"', 'compare' => 'LIKE' ];
    }

    // Duration filter
    if ( $duration ) {
        switch ( $duration ) {
            case 'under-7':
                $args['meta_query'][] = [ 'key' => 'ist_duration_days', 'value' => 7,  'compare' => '<',  'type' => 'NUMERIC' ];
                break;
            case '7-10':
                $args['meta_query'][] = [ 'key' => 'ist_duration_days', 'value' => [ 7, 10 ], 'compare' => 'BETWEEN', 'type' => 'NUMERIC' ];
                break;
            case '11-14':
                $args['meta_query'][] = [ 'key' => 'ist_duration_days', 'value' => [ 11, 14 ], 'compare' => 'BETWEEN', 'type' => 'NUMERIC' ];
                break;
            case '15-plus':
                $args['meta_query'][] = [ 'key' => 'ist_duration_days', 'value' => 15, 'compare' => '>=', 'type' => 'NUMERIC' ];
                break;
        }
    }

    // Budget filter
    if ( $budget ) {
        switch ( $budget ) {
            case 'under-800':
                $args['meta_query'][] = [ 'key' => 'ist_price', 'value' => 800,  'compare' => '<',  'type' => 'NUMERIC' ];
                break;
            case '800-1500':
                $args['meta_query'][] = [ 'key' => 'ist_price', 'value' => [ 800, 1500 ], 'compare' => 'BETWEEN', 'type' => 'NUMERIC' ];
                break;
            case '1500-2500':
                $args['meta_query'][] = [ 'key' => 'ist_price', 'value' => [ 1500, 2500 ], 'compare' => 'BETWEEN', 'type' => 'NUMERIC' ];
                break;
            case '2500-plus':
                $args['meta_query'][] = [ 'key' => 'ist_price', 'value' => 2500, 'compare' => '>=', 'type' => 'NUMERIC' ];
                break;
        }
    }

    $query = new WP_Query( $args );
    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/global/package-card' );
        }
    } else {
        echo '<div class="ist-no-results"><p>' . esc_html__( 'No packages match your filters. Try broadening your search or', 'infinity-sky' ) . ' <a href="' . esc_url( home_url( '/plan-my-trip' ) ) . '">' . esc_html__( 'plan a custom trip', 'infinity-sky' ) . '</a>.</p></div>';
    }
    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success( [
        'html'       => $html,
        'found'      => $query->found_posts,
        'max_pages'  => $query->max_num_pages,
        'current'    => $page,
    ] );
}
add_action( 'wp_ajax_ist_filter_packages',        'ist_ajax_filter_packages' );
add_action( 'wp_ajax_nopriv_ist_filter_packages', 'ist_ajax_filter_packages' );

// ─── Manual Quote Request ─────────────────────────────────────────────────────
function ist_ajax_manual_quote() {
    check_ajax_referer( 'ist_nonce', 'nonce' );

    $name     = sanitize_text_field( $_POST['name']     ?? '' );
    $email    = sanitize_email(      $_POST['email']    ?? '' );
    $whatsapp = sanitize_text_field( $_POST['whatsapp'] ?? '' );
    $from     = strtoupper( sanitize_text_field( $_POST['from'] ?? '' ) );
    $to       = strtoupper( sanitize_text_field( $_POST['to']   ?? '' ) );
    $date     = sanitize_text_field( $_POST['date']     ?? '' );
    $pax      = absint( $_POST['passengers'] ?? 1 );
    $notes    = sanitize_textarea_field( $_POST['notes'] ?? '' );

    if ( ! $name || ! $email || ! $from || ! $to || ! $date ) {
        wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'infinity-sky' ) ], 400 );
    }

    $subject = sprintf( 'Manual Flight Quote Request — %s to %s on %s | %s', $from, $to, $date, $name );
    $message = "Manual Quote Request\n";
    $message .= "══════════════════\n";
    $message .= "Name: $name\n";
    $message .= "Email: $email\n";
    $message .= "WhatsApp: $whatsapp\n";
    $message .= "Route: $from → $to\n";
    $message .= "Date: $date\n";
    $message .= "Passengers: $pax\n";
    $message .= "Notes: $notes\n";

    $sent = wp_mail( 'infinityskytravels8@gmail.com', $subject, $message, [ 'Reply-To: ' . $email ] );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => __( 'Your quote request has been sent! We\'ll WhatsApp you within 2 hours.', 'infinity-sky' ) ] );
    } else {
        wp_send_json_error( [ 'message' => __( 'Failed to send. Please WhatsApp us directly.', 'infinity-sky' ) ], 500 );
    }
}
add_action( 'wp_ajax_ist_manual_quote',        'ist_ajax_manual_quote' );
add_action( 'wp_ajax_nopriv_ist_manual_quote', 'ist_ajax_manual_quote' );

// ─── Plan My Trip: custom itinerary request ──────────────────────────────────
function ist_ajax_plan_trip() {
    check_ajax_referer( 'ist_plan_trip', 'nonce' );

    // Sanitize all fields
    $first_name    = sanitize_text_field( $_POST['first_name']     ?? '' );
    $last_name     = sanitize_text_field( $_POST['last_name']      ?? '' );
    $email         = sanitize_email(      $_POST['email']          ?? '' );
    $phone         = sanitize_text_field( $_POST['phone']          ?? '' );
    $nationality   = sanitize_text_field( $_POST['nationality']    ?? '' );
    $region        = sanitize_text_field( $_POST['region']         ?? '' );
    $depart_date   = sanitize_text_field( $_POST['depart_date']    ?? '' );
    $flexibility   = sanitize_text_field( $_POST['date_flexibility']?? '' );
    $duration      = sanitize_text_field( $_POST['duration']       ?? '' );
    $accommodation = sanitize_text_field( $_POST['accommodation']  ?? '' );
    $budget        = sanitize_text_field( $_POST['budget']         ?? '' );
    $fitness       = sanitize_text_field( $_POST['fitness_level']  ?? '' );
    $experience    = sanitize_text_field( $_POST['experience']     ?? '' );
    $group_type    = sanitize_text_field( $_POST['group_type']     ?? '' );
    $special       = sanitize_textarea_field( $_POST['special_requests'] ?? '' );
    $referral      = sanitize_text_field( $_POST['referral_source']?? '' );

    // Pax
    $pax_raw = isset( $_POST['pax'] ) && is_array( $_POST['pax'] ) ? $_POST['pax'] : [];
    $adults   = absint( $pax_raw['adults']   ?? 2 );
    $children = absint( $pax_raw['children'] ?? 0 );
    $seniors  = absint( $pax_raw['seniors']  ?? 0 );

    // Activities
    $activities_raw = isset( $_POST['activities'] ) && is_array( $_POST['activities'] ) ? $_POST['activities'] : [];
    $activities = implode( ', ', array_map( 'sanitize_text_field', $activities_raw ) );

    // Base package
    $base_package_id = absint( $_POST['base_package'] ?? 0 );
    $base_package    = $base_package_id ? get_the_title( $base_package_id ) : 'None';

    // Validate required
    if ( ! $first_name || ! $last_name || ! $email || ! $region || ! $depart_date ) {
        wp_send_json_error( __( 'Please complete all required fields.', 'infinity-sky' ), 422 );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( __( 'Please provide a valid email address.', 'infinity-sky' ), 422 );
    }

    // Build email
    $to      = 'infinityskytravels8@gmail.com';
    $subject = sprintf( '[Custom Trip Request] %s %s — %s / %s', $first_name, $last_name, $region, $depart_date );

    $lines = [
        '=== CONTACT DETAILS ===',
        "Name:          $first_name $last_name",
        "Email:         $email",
        "Phone:         $phone",
        "Nationality:   $nationality",
        "Referral:      $referral",
        '',
        '=== TRIP DETAILS ===',
        "Region:        $region",
        "Base Package:  $base_package",
        "Depart Date:   $depart_date (flexibility: $flexibility)",
        "Duration:      $duration",
        "Accommodation: $accommodation",
        "Budget pp:     $budget",
        '',
        '=== GROUP DETAILS ===',
        "Adults:        $adults",
        "Children:      $children",
        "Seniors:       $seniors",
        "Group Type:    $group_type",
        "Fitness Level: $fitness",
        "Experience:    $experience",
        "Activities:    " . ( $activities ?: 'Not specified' ),
        '',
        '=== SPECIAL REQUESTS ===',
        $special ?: 'None',
    ];

    $message = implode( "\n", $lines );
    $headers = [ "Reply-To: $first_name $last_name <$email>" ];

    $sent = wp_mail( $to, $subject, $message, $headers );

    // Also email the enquirer a confirmation
    if ( $sent ) {
        $confirm_subject = 'Your custom trek request — Infinity Sky Travels';
        $confirm_body    = "Dear $first_name,\n\nThank you for your custom trip enquiry!\n\nWe have received your request for:\n• Region: $region\n• Departure: $depart_date\n• Duration: $duration\n• Group: $adults adult(s), $children child(ren), $seniors senior(s)\n\nOur team will prepare a personalised itinerary and get back to you within 24 hours.\n\nMeanwhile, feel free to WhatsApp us at +977 9810597893 for faster assistance.\n\nWarm regards,\nInfinity Sky Travels Team\nThamel, Kathmandu, Nepal";
        wp_mail( $email, $confirm_subject, $confirm_body, [ 'Reply-To: infinityskytravels8@gmail.com' ] );
    }

    if ( $sent ) {
        wp_send_json_success( [
            'message' => __( 'Your trip request has been received! Check your inbox for a confirmation email.', 'infinity-sky' ),
        ] );
    } else {
        wp_send_json_error( __( 'Failed to send your request. Please WhatsApp us directly at +977 9810597893.', 'infinity-sky' ), 500 );
    }
}
add_action( 'wp_ajax_ist_plan_trip',        'ist_ajax_plan_trip' );
add_action( 'wp_ajax_nopriv_ist_plan_trip', 'ist_ajax_plan_trip' );

// ─── General contact form ────────────────────────────────────────────────────
function ist_ajax_contact_form() {
    check_ajax_referer( 'ist_contact', 'nonce' );

    $name    = sanitize_text_field( $_POST['name']    ?? '' );
    $email   = sanitize_email(      $_POST['email']   ?? '' );
    $subject = sanitize_text_field( $_POST['subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( ! $name || ! $email || ! $message || ! is_email( $email ) ) {
        wp_send_json_error( __( 'Please fill in all required fields.', 'infinity-sky' ), 422 );
    }

    $sent = wp_mail(
        'infinityskytravels8@gmail.com',
        "[Contact Form] $subject — $name",
        "Name: $name\nEmail: $email\nSubject: $subject\n\n$message",
        [ "Reply-To: $name <$email>" ]
    );

    $sent
        ? wp_send_json_success()
        : wp_send_json_error( __( 'Could not send message. Please email us directly.', 'infinity-sky' ), 500 );
}
add_action( 'wp_ajax_ist_contact_form',        'ist_ajax_contact_form' );
add_action( 'wp_ajax_nopriv_ist_contact_form', 'ist_ajax_contact_form' );
