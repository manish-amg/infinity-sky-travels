<?php
/**
 * ACF field groups for ist_route and ist_activity CPTs.
 * Lets content editors change Popular Domestic Routes and Beyond Trekking
 * activities from wp-admin — no code changes needed.
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'acf_add_local_field_group' ) ) return;

// ─── Route fields ──────────────────────────────────────────────────────────────
acf_add_local_field_group( [
    'key'      => 'group_ist_route_details',
    'title'    => 'Route Details',
    'fields'   => [
        [ 'key' => 'field_ist_route_from_code', 'label' => 'From Airport Code', 'name' => 'ist_route_from_code', 'type' => 'text', 'required' => 1, 'placeholder' => 'KTM' ],
        [ 'key' => 'field_ist_route_to_code',   'label' => 'To Airport Code',   'name' => 'ist_route_to_code',   'type' => 'text', 'required' => 1, 'placeholder' => 'PKR' ],
        [ 'key' => 'field_ist_route_from_name', 'label' => 'From City / Airport Name', 'name' => 'ist_route_from_name', 'type' => 'text', 'required' => 1 ],
        [ 'key' => 'field_ist_route_to_name',   'label' => 'To City / Airport Name',   'name' => 'ist_route_to_name',   'type' => 'text', 'required' => 1 ],
        [ 'key' => 'field_ist_route_duration',  'label' => 'Flight Duration',   'name' => 'ist_route_duration',  'type' => 'text', 'placeholder' => '25 min' ],
        [ 'key' => 'field_ist_route_price',     'label' => 'Price From (USD)',  'name' => 'ist_route_price',     'type' => 'number', 'prepend' => '$' ],
        [ 'key' => 'field_ist_route_airlines',  'label' => 'Airlines (comma-separated)', 'name' => 'ist_route_airlines', 'type' => 'text', 'placeholder' => 'Buddha Air, Yeti Airlines' ],
        [
            'key'          => 'field_ist_route_image_note',
            'label'        => 'Route Photo',
            'name'         => '',
            'type'         => 'message',
            'message'      => 'Set the route photo using the Featured Image box in the sidebar. If left empty, the Infinity Sky logo is shown instead of a broken image.',
        ],
    ],
    'location' => [
        [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'ist_route' ] ],
    ],
    'menu_order' => 0,
] );

// ─── Activity fields ────────────────────────────────────────────────────────────
acf_add_local_field_group( [
    'key'      => 'group_ist_activity_details',
    'title'    => 'Activity Details',
    'fields'   => [
        [ 'key' => 'field_ist_activity_location', 'label' => 'Location', 'name' => 'ist_activity_location', 'type' => 'text', 'placeholder' => 'Pokhara' ],
        [ 'key' => 'field_ist_activity_duration', 'label' => 'Duration', 'name' => 'ist_activity_duration', 'type' => 'text', 'placeholder' => 'Half day' ],
        [ 'key' => 'field_ist_activity_price',    'label' => 'Price From (USD)', 'name' => 'ist_activity_price', 'type' => 'number', 'prepend' => '$' ],
        [ 'key' => 'field_ist_activity_link',     'label' => 'Custom Link (optional)', 'name' => 'ist_activity_link', 'type' => 'url', 'instructions' => 'Leave blank to link to this activity\'s own page.' ],
        [
            'key'          => 'field_ist_activity_image_note',
            'label'        => 'Activity Photo',
            'name'         => '',
            'type'         => 'message',
            'message'      => 'Set the activity photo using the Featured Image box in the sidebar. If left empty, the Infinity Sky logo is shown instead of a broken image.',
        ],
    ],
    'location' => [
        [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'ist_activity' ] ],
    ],
    'menu_order' => 0,
] );
