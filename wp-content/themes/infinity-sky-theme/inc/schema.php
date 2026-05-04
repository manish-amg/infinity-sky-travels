<?php
/**
 * JSON-LD schema output — TravelAgency on all pages,
 * TouristTrip on package pages, Article on blog posts, FAQPage on packages.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function ist_output_schema() {
    $schemas = [];

    // ── TravelAgency (every page) ──────────────────────────────────────────────
    $schemas[] = [
        '@context'    => 'https://schema.org',
        '@type'       => 'TravelAgency',
        'name'        => 'Infinity Sky Travels',
        'url'         => 'https://infinityskytravels.com',
        'logo'        => get_template_directory_uri() . '/assets/images/logo.png',
        'description' => 'Nepal domestic flight booking and trekking package agency for independent travellers. Based in Thamel, Kathmandu.',
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Thamel',
            'addressLocality' => 'Kathmandu',
            'postalCode'      => '44600',
            'addressCountry'  => 'NP',
        ],
        'telephone' => '+977-9810597893',
        'email'     => 'infinityskytravels8@gmail.com',
        'sameAs'    => [
            'https://www.instagram.com/infinityskytvl',
            'https://www.facebook.com/profile.php?id=61581195998301',
        ],
        'openingHoursSpecification' => [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
            'opens'     => '09:00',
            'closes'    => '18:00',
        ],
    ];

    // ── TouristTrip (individual package) ─────────────────────────────────────
    if ( is_singular( 'ist_package' ) ) {
        $post_id  = get_the_ID();
        $price    = get_field( 'ist_price', $post_id );
        $duration = get_field( 'ist_duration_days', $post_id );
        $rating   = get_field( 'ist_schema_rating', $post_id ) ?: 5.0;
        $rev_count= get_field( 'ist_schema_review_count', $post_id ) ?: 1;
        $altitude = get_field( 'ist_max_altitude', $post_id );
        $season   = get_field( 'ist_best_season', $post_id );

        $trip_schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'TouristTrip',
            'name'        => get_the_title(),
            'description' => wp_strip_all_tags( get_the_excerpt() ),
            'url'         => get_permalink(),
            'image'       => get_the_post_thumbnail_url( $post_id, 'ist-hero' ),
            'provider'    => [
                '@type' => 'TravelAgency',
                'name'  => 'Infinity Sky Travels',
                'url'   => 'https://infinityskytravels.com',
            ],
            'offers' => [
                '@type'         => 'Offer',
                'price'         => $price,
                'priceCurrency' => 'USD',
                'availability'  => 'https://schema.org/InStock',
                'url'           => get_permalink(),
            ],
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => $rating,
                'reviewCount' => $rev_count,
                'bestRating'  => 5,
                'worstRating' => 1,
            ],
        ];

        if ( $altitude ) {
            $trip_schema['touristType'] = 'Trekking';
        }
        if ( $duration ) {
            $trip_schema['duration'] = 'P' . $duration . 'D';
        }

        $schemas[] = $trip_schema;

        // FAQPage schema
        $faqs = get_field( 'ist_faqs', $post_id );
        if ( ! empty( $faqs ) ) {
            $faq_schema = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => [],
            ];
            foreach ( $faqs as $faq ) {
                $faq_schema['mainEntity'][] = [
                    '@type'          => 'Question',
                    'name'           => wp_strip_all_tags( $faq['question'] ),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => wp_strip_all_tags( $faq['answer'] ),
                    ],
                ];
            }
            $schemas[] = $faq_schema;
        }
    }

    // ── Article (single blog post) ────────────────────────────────────────────
    if ( is_singular( 'post' ) ) {
        $schemas[] = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'description'   => wp_strip_all_tags( get_the_excerpt() ),
            'url'           => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'image'         => get_the_post_thumbnail_url( get_the_ID(), 'ist-hero' ),
            'author'        => [
                '@type' => 'Organization',
                'name'  => 'Infinity Sky Travels',
                'url'   => 'https://infinityskytravels.com',
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'Infinity Sky Travels',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => get_template_directory_uri() . '/assets/images/logo.png',
                ],
            ],
        ];
    }

    // Output all schemas
    foreach ( $schemas as $schema ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'ist_output_schema' );
