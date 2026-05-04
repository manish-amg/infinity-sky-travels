<?php
/**
 * ACF field group registration for ist_package CPT.
 * All package fields are defined here. ACF must be active.
 */

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'acf_add_local_field_group' ) ) return;

// ─── Package Details field group ──────────────────────────────────────────────
acf_add_local_field_group( [
    'key'      => 'group_ist_package_details',
    'title'    => 'Package Details',
    'fields'   => [

        // ── Overview tab ──────────────────────────────────────────────────────
        [ 'key' => 'field_tab_overview', 'label' => 'Overview', 'type' => 'tab', 'placement' => 'top' ],

        [
            'key'           => 'field_ist_price',
            'label'         => 'Price From (USD)',
            'name'          => 'ist_price',
            'type'          => 'number',
            'instructions'  => 'Starting price per person in USD.',
            'required'      => 1,
            'min'           => 1,
            'prepend'       => '$',
        ],
        [
            'key'           => 'field_ist_price_luxury',
            'label'         => 'Luxury Price (USD)',
            'name'          => 'ist_price_luxury',
            'type'          => 'number',
            'instructions'  => 'For packages with luxury tiers, enter the premium price.',
            'min'           => 0,
            'prepend'       => '$',
        ],
        [
            'key'           => 'field_ist_duration_days',
            'label'         => 'Duration (Days)',
            'name'          => 'ist_duration_days',
            'type'          => 'number',
            'required'      => 1,
            'min'           => 1,
            'append'        => 'days',
        ],
        [
            'key'           => 'field_ist_duration_nights',
            'label'         => 'Duration (Nights)',
            'name'          => 'ist_duration_nights',
            'type'          => 'number',
            'min'           => 0,
            'append'        => 'nights',
        ],
        [
            'key'     => 'field_ist_difficulty_level',
            'label'   => 'Difficulty Level',
            'name'    => 'ist_difficulty_level',
            'type'    => 'select',
            'required' => 1,
            'choices' => [
                'easy'        => 'Easy',
                'moderate'    => 'Moderate',
                'challenging' => 'Challenging',
                'strenuous'   => 'Strenuous',
            ],
            'default_value' => 'moderate',
        ],
        [
            'key'     => 'field_ist_max_altitude',
            'label'   => 'Max Altitude',
            'name'    => 'ist_max_altitude',
            'type'    => 'text',
            'placeholder' => 'e.g. 5,545m (Kala Patthar)',
        ],
        [
            'key'     => 'field_ist_best_season',
            'label'   => 'Best Season',
            'name'    => 'ist_best_season',
            'type'    => 'text',
            'placeholder' => 'e.g. March–May, Sept–Nov',
        ],
        [
            'key'     => 'field_ist_group_size_min',
            'label'   => 'Min Group Size',
            'name'    => 'ist_group_size_min',
            'type'    => 'number',
            'default_value' => 1,
            'min'     => 1,
        ],
        [
            'key'     => 'field_ist_group_size_max',
            'label'   => 'Max Group Size',
            'name'    => 'ist_group_size_max',
            'type'    => 'number',
            'default_value' => 12,
            'min'     => 1,
        ],
        [
            'key'     => 'field_ist_start_location',
            'label'   => 'Start Location',
            'name'    => 'ist_start_location',
            'type'    => 'text',
            'default_value' => 'Kathmandu, Nepal',
        ],
        [
            'key'     => 'field_ist_end_location',
            'label'   => 'End Location',
            'name'    => 'ist_end_location',
            'type'    => 'text',
            'default_value' => 'Kathmandu, Nepal',
        ],
        [
            'key'     => 'field_ist_region_display',
            'label'   => 'Region Display Name',
            'name'    => 'ist_region_display',
            'type'    => 'text',
            'placeholder' => 'e.g. Everest Region, Sagarmatha',
        ],
        [
            'key'     => 'field_ist_traveller_type',
            'label'   => 'Traveller Type',
            'name'    => 'ist_traveller_type',
            'type'    => 'checkbox',
            'choices' => [
                'backpacker' => '🎒 Backpacker',
                'mid-range'  => '🧭 Explorer / Mid-Range',
                'luxury'     => '⭐ Luxury Trekker',
            ],
        ],
        [
            'key'     => 'field_ist_highlights',
            'label'   => 'Highlights',
            'name'    => 'ist_highlights',
            'type'    => 'repeater',
            'button_label' => 'Add Highlight',
            'sub_fields' => [
                [
                    'key'   => 'field_ist_highlight_item',
                    'label' => 'Highlight',
                    'name'  => 'highlight',
                    'type'  => 'text',
                ],
            ],
        ],
        [
            'key'     => 'field_ist_hero_image',
            'label'   => 'Hero Image (1920×600)',
            'name'    => 'ist_hero_image',
            'type'    => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
        ],
        [
            'key'     => 'field_ist_gallery',
            'label'   => 'Photo Gallery',
            'name'    => 'ist_gallery',
            'type'    => 'gallery',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'min'     => 1,
            'max'     => 12,
        ],
        [
            'key'   => 'field_ist_short_description',
            'label' => 'Short Description (card excerpt)',
            'name'  => 'ist_short_description',
            'type'  => 'textarea',
            'rows'  => 3,
            'maxlength' => 200,
        ],
        [
            'key'   => 'field_ist_key_highlight_line',
            'label' => 'Key Highlight (1 line for card)',
            'name'  => 'ist_key_highlight_line',
            'type'  => 'text',
            'placeholder' => 'e.g. Stand at the foot of the world\'s highest peak',
        ],
        [
            'key'   => 'field_ist_map_embed',
            'label' => 'Map Embed URL / Image',
            'name'  => 'ist_map_embed',
            'type'  => 'url',
            'placeholder' => 'Google Maps embed URL or static image URL',
        ],

        // ── Inclusions/Exclusions tab ─────────────────────────────────────────
        [ 'key' => 'field_tab_inclusions', 'label' => 'Inclusions & Exclusions', 'type' => 'tab', 'placement' => 'top' ],

        [
            'key'     => 'field_ist_includes',
            'label'   => 'What\'s Included',
            'name'    => 'ist_includes',
            'type'    => 'repeater',
            'button_label' => 'Add Inclusion',
            'sub_fields' => [
                [ 'key' => 'field_ist_include_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text' ],
            ],
        ],
        [
            'key'     => 'field_ist_excludes',
            'label'   => 'What\'s Excluded',
            'name'    => 'ist_excludes',
            'type'    => 'repeater',
            'button_label' => 'Add Exclusion',
            'sub_fields' => [
                [ 'key' => 'field_ist_exclude_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text' ],
            ],
        ],

        // ── Itinerary tab ─────────────────────────────────────────────────────
        [ 'key' => 'field_tab_itinerary', 'label' => 'Day-by-Day Itinerary', 'type' => 'tab', 'placement' => 'top' ],

        [
            'key'     => 'field_ist_itinerary',
            'label'   => 'Itinerary Days',
            'name'    => 'ist_itinerary',
            'type'    => 'repeater',
            'button_label' => 'Add Day',
            'sub_fields' => [
                [ 'key' => 'field_ist_day_number',      'label' => 'Day #',           'name' => 'day_number',     'type' => 'number', 'min' => 1 ],
                [ 'key' => 'field_ist_day_title',       'label' => 'Day Title',        'name' => 'day_title',      'type' => 'text' ],
                [ 'key' => 'field_ist_day_altitude',    'label' => 'Altitude',         'name' => 'day_altitude',   'type' => 'text',   'placeholder' => 'e.g. 3,440m' ],
                [ 'key' => 'field_ist_day_distance',    'label' => 'Distance / Hours', 'name' => 'day_distance',   'type' => 'text',   'placeholder' => 'e.g. 6–7 hrs, 12km' ],
                [ 'key' => 'field_ist_day_description', 'label' => 'Description',      'name' => 'day_description','type' => 'wysiwyg','toolbar' => 'basic', 'media_upload' => 0 ],
                [ 'key' => 'field_ist_day_accommodation','label'=> 'Accommodation',    'name' => 'day_accommodation','type' => 'text',  'placeholder' => 'e.g. Teahouse / Lodge' ],
                [ 'key' => 'field_ist_day_meals',       'label' => 'Meals',            'name' => 'day_meals',      'type' => 'text',   'placeholder' => 'e.g. Breakfast + Dinner' ],
            ],
        ],

        // ── FAQ tab ───────────────────────────────────────────────────────────
        [ 'key' => 'field_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top' ],

        [
            'key'     => 'field_ist_faqs',
            'label'   => 'FAQs',
            'name'    => 'ist_faqs',
            'type'    => 'repeater',
            'button_label' => 'Add FAQ',
            'min'     => 0,
            'max'     => 15,
            'sub_fields' => [
                [ 'key' => 'field_ist_faq_q', 'label' => 'Question', 'name' => 'question', 'type' => 'text' ],
                [ 'key' => 'field_ist_faq_a', 'label' => 'Answer',   'name' => 'answer',   'type' => 'wysiwyg', 'toolbar' => 'basic', 'media_upload' => 0 ],
            ],
        ],

        // ── Flights integration ────────────────────────────────────────────────
        [ 'key' => 'field_tab_flights', 'label' => 'Flights', 'type' => 'tab', 'placement' => 'top' ],

        [
            'key'     => 'field_ist_flight_from',
            'label'   => 'Required Flight From (IATA)',
            'name'    => 'ist_flight_from',
            'type'    => 'text',
            'default_value' => 'KTM',
            'placeholder' => 'e.g. KTM',
        ],
        [
            'key'     => 'field_ist_flight_to',
            'label'   => 'Required Flight To (IATA)',
            'name'    => 'ist_flight_to',
            'type'    => 'text',
            'default_value' => 'LUA',
            'placeholder' => 'e.g. LUA',
        ],
        [
            'key'     => 'field_ist_requires_permit',
            'label'   => 'Requires Special Permit',
            'name'    => 'ist_requires_permit',
            'type'    => 'true_false',
            'default_value' => 0,
            'ui'      => 1,
        ],
        [
            'key'     => 'field_ist_permit_details',
            'label'   => 'Permit Details',
            'name'    => 'ist_permit_details',
            'type'    => 'text',
            'placeholder' => 'e.g. Restricted Area Permit $500/person',
            'conditional_logic' => [
                [ [ 'field' => 'field_ist_requires_permit', 'operator' => '==', 'value' => '1' ] ],
            ],
        ],

        // ── SEO tab ───────────────────────────────────────────────────────────
        [ 'key' => 'field_tab_seo', 'label' => 'SEO & Schema', 'type' => 'tab', 'placement' => 'top' ],

        [
            'key'     => 'field_ist_schema_rating',
            'label'   => 'Average Rating (1–5)',
            'name'    => 'ist_schema_rating',
            'type'    => 'number',
            'default_value' => 5.0,
            'min'     => 1,
            'max'     => 5,
            'step'    => 0.1,
        ],
        [
            'key'     => 'field_ist_schema_review_count',
            'label'   => 'Review Count',
            'name'    => 'ist_schema_review_count',
            'type'    => 'number',
            'default_value' => 1,
            'min'     => 0,
        ],

    ],
    'location' => [
        [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'ist_package' ] ],
    ],
    'active'   => true,
    'style'    => 'default',
    'position' => 'normal',
    'label_placement' => 'top',
] );


// ─── Blog Post extras ─────────────────────────────────────────────────────────
acf_add_local_field_group( [
    'key'   => 'group_ist_blog_extras',
    'title' => 'Blog Post Extras',
    'fields' => [
        [
            'key'   => 'field_ist_post_focus_kw',
            'label' => 'Focus Keyword',
            'name'  => 'ist_post_focus_kw',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_ist_post_reading_time',
            'label' => 'Reading Time Override (mins)',
            'name'  => 'ist_post_reading_time',
            'type'  => 'number',
            'min'   => 1,
            'placeholder' => 'Leave blank to auto-calculate',
        ],
        [
            'key'   => 'field_ist_post_hero_image',
            'label' => 'Post Hero Image',
            'name'  => 'ist_post_hero_image',
            'type'  => 'image',
            'return_format' => 'array',
        ],
    ],
    'location' => [
        [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ] ],
    ],
    'active' => true,
] );
