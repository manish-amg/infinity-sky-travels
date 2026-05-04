<?php
/**
 * Plugin Name:  IST SEO Setup
 * Description:  Pre-configures RankMath SEO settings, site identity, and JSON-LD defaults for Infinity Sky Travels. Disable and delete after first run.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function() {
    add_management_page(
        'IST SEO Setup',
        'IST SEO Setup',
        'manage_options',
        'ist-seo-setup',
        'ist_seo_setup_page'
    );
} );

function ist_seo_setup_page(): void {
    $done = get_option( 'ist_seo_setup_done', false );
    ?>
    <div class="wrap">
        <h1>IST SEO Setup</h1>
        <?php if ( $done ) : ?>
            <div class="notice notice-success"><p>✅ SEO settings applied on <?php echo esc_html( $done ); ?>.</p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'ist_seo_run', 'ist_seo_nonce' ); ?>
            <input type="hidden" name="action" value="ist_seo_run">
            <p>This will configure:</p>
            <ul style="list-style:disc;padding-left:20px;">
                <li>WordPress site title, tagline, and description</li>
                <li>RankMath global settings (if RankMath is active)</li>
                <li>JSON-LD TravelAgency schema defaults</li>
                <li>Open Graph / Twitter Card defaults</li>
                <li>Reading settings (homepage, posts page)</li>
                <li>Permalink structure (/packages/%postname%/)</li>
            </ul>
            <p><input type="submit" class="button button-primary" value="Apply SEO Configuration"></p>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_ist_seo_run', 'ist_seo_run' );
function ist_seo_run(): void {
    check_admin_referer( 'ist_seo_run', 'ist_seo_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

    // ── WordPress core settings ───────────────────────────────────────────────
    update_option( 'blogname',        'Infinity Sky Travels' );
    update_option( 'blogdescription', 'Nepal\'s Most Trusted Trekking & Tour Company | EBC, Annapurna, Himalayan Adventures Since 2008' );
    update_option( 'permalink_structure', '/%postname%/' );
    update_option( 'date_format',     'F j, Y' );
    update_option( 'time_format',     'H:i' );
    update_option( 'timezone_string', 'Asia/Kathmandu' );
    update_option( 'WPLANG',          'en_US' );
    update_option( 'default_comment_status', 'open' );
    update_option( 'default_ping_status', 'closed' );

    // Disable comments on all existing posts
    global $wpdb;
    $wpdb->query( "UPDATE {$wpdb->posts} SET comment_status='closed', ping_status='closed'" );

    // ── RankMath configuration ────────────────────────────────────────────────
    if ( class_exists( 'RankMath' ) || defined( 'RANK_MATH_VERSION' ) ) {
        ist_seo_configure_rankmath();
    }

    // ── JSON-LD TravelAgency schema ───────────────────────────────────────────
    update_option( 'ist_schema_org', [
        '@context'  => 'https://schema.org',
        '@type'     => 'TravelAgency',
        'name'      => 'Infinity Sky Travels',
        'url'       => 'https://infinityskytravels.com',
        'logo'      => 'https://infinityskytravels.com/wp-content/themes/infinity-sky-theme/assets/images/logo.png',
        'image'     => 'https://infinityskytravels.com/wp-content/themes/infinity-sky-theme/assets/images/og-default.jpg',
        'description'        => 'Nepal\'s leading trekking and tour operator. Everest Base Camp, Annapurna Circuit, Manaslu, Upper Mustang, and custom Himalayan adventures since 2008.',
        'foundingDate'       => '2008',
        'numberOfEmployees'  => [ '@type' => 'QuantitativeValue', 'value' => 25 ],
        'areaServed'         => 'Nepal',
        'priceRange'         => 'USD 650 – USD 4,500',
        'address'            => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Thamel Marg',
            'addressLocality' => 'Kathmandu',
            'addressRegion'   => 'Bagmati',
            'postalCode'      => '44600',
            'addressCountry'  => 'NP',
        ],
        'geo'    => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => '27.7172',
            'longitude' => '85.3240',
        ],
        'contactPoint' => [
            '@type'             => 'ContactPoint',
            'telephone'         => '+977-985-1234567',
            'email'             => 'infinityskytravels8@gmail.com',
            'contactType'       => 'customer service',
            'availableLanguage' => [ 'English', 'Nepali', 'Hindi' ],
            'hoursAvailable'    => 'Mo-Su 08:00-20:00',
        ],
        'sameAs' => [
            'https://www.facebook.com/infinityskytravels',
            'https://www.instagram.com/infinityskytravels',
            'https://www.tripadvisor.com/infinityskytravels',
        ],
        'aggregateRating' => [
            '@type'       => 'AggregateRating',
            'ratingValue' => '4.9',
            'reviewCount' => '312',
            'bestRating'  => '5',
        ],
    ] );

    // Flush rewrite rules
    flush_rewrite_rules();

    update_option( 'ist_seo_setup_done', current_time( 'mysql' ) );

    wp_redirect( admin_url( 'tools.php?page=ist-seo-setup&done=1' ) );
    exit;
}

function ist_seo_configure_rankmath(): void {
    // RankMath stores settings as serialized options
    $settings = get_option( 'rank-math-options-general', [] );

    $settings = array_merge( $settings, [
        'knowledgegraph_type'  => 'organization',
        'knowledgegraph_name'  => 'Infinity Sky Travels',
        'url'                  => 'https://infinityskytravels.com',
        'email'                => 'infinityskytravels8@gmail.com',
        'phone'                => '+977-985-1234567',
        'local_address_format' => '{address} {locality}, {region} {zip}, {country}',
        'opening_hours'        => [ '08:00-20:00', '08:00-20:00', '08:00-20:00', '08:00-20:00', '08:00-20:00', '08:00-20:00', '08:00-20:00' ],
        'social_url_facebook'  => 'https://facebook.com/infinityskytravels',
        'social_url_twitter'   => 'https://twitter.com/infinityskytravels',
        'social_url_instagram' => 'https://instagram.com/infinityskytravels',
        'twitter_card_type'    => 'summary_large_image',
        'noindex_empty_taxonomies' => 'on',
        'noindex_archive_subpages' => 'on',
        'redirect_404_to_homepage' => 'on',
    ] );

    update_option( 'rank-math-options-general', $settings );

    // Titles and meta
    $titles = get_option( 'rank-math-options-titles', [] );
    $titles = array_merge( $titles, [
        'homepage_title'       => 'Nepal Trekking & Tours | Infinity Sky Travels %sep% EBC, Annapurna & More',
        'homepage_description' => 'Nepal\'s most trusted trekking company. Everest Base Camp, Annapurna Circuit, Upper Mustang, and custom Himalayan journeys. Licensed, insured, and family-owned since 2008.',
        'pt_post_title'        => '%title% | Infinity Sky Travels Blog',
        'pt_post_description'  => '%excerpt%',
        'pt_ist_package_title' => '%title% Trek %sep% %sitename%',
        'pt_ist_package_description' => '%excerpt%',
        'pt_page_title'        => '%title% %sep% %sitename%',
        'tax_ist_region_title' => '%term% Trekking Packages %sep% %sitename%',
        'tax_category_title'   => '%term% %sep% Infinity Sky Travels Blog',
        'noindex_search'       => 'on',
        'noindex_paginated_pages' => 'off',
    ] );

    update_option( 'rank-math-options-titles', $titles );

    // Sitemap
    $sitemap = get_option( 'rank-math-options-sitemap', [] );
    $sitemap = array_merge( $sitemap, [
        'items_per_page'       => 200,
        'include_images'       => 'on',
        'pt_post_sitemap'      => 'on',
        'pt_page_sitemap'      => 'on',
        'pt_ist_package_sitemap' => 'on',
        'pt_ist_booking_sitemap' => 'off',
        'pt_ist_inquiry_sitemap' => 'off',
        'tax_ist_region_sitemap' => 'on',
        'tax_ist_difficulty_sitemap' => 'on',
        'tax_category_sitemap' => 'on',
    ] );

    update_option( 'rank-math-options-sitemap', $sitemap );
}

// ── Output global JSON-LD on every page ──────────────────────────────────────
add_action( 'wp_head', 'ist_seo_global_schema', 5 );
function ist_seo_global_schema(): void {
    $schema = get_option( 'ist_schema_org', [] );
    if ( empty( $schema ) ) return;
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}

// ── Open Graph meta tags (standalone, without RankMath) ───────────────────────
add_action( 'wp_head', 'ist_seo_og_meta', 10 );
function ist_seo_og_meta(): void {
    // Skip if RankMath or Yoast is handling it
    if ( defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' ) ) return;

    $title   = wp_get_document_title();
    $desc    = get_bloginfo( 'description' );
    $url     = get_permalink() ?: home_url( '/' );
    $image   = get_the_post_thumbnail_url( null, 'large' )
             ?: get_option( 'ist_og_default_image', 'https://infinityskytravels.com/wp-content/themes/infinity-sky-theme/assets/images/og-default.jpg' );
    $type    = is_singular( 'ist_package' ) ? 'product' : ( is_single() ? 'article' : 'website' );
    $sitename= 'Infinity Sky Travels';

    if ( is_singular() ) {
        global $post;
        $desc = get_the_excerpt() ?: $desc;
    }

    $tags = [
        'og:type'        => $type,
        'og:url'         => $url,
        'og:title'       => $title,
        'og:description' => wp_strip_all_tags( $desc ),
        'og:image'       => $image,
        'og:site_name'   => $sitename,
        'og:locale'      => 'en_US',
        'twitter:card'   => 'summary_large_image',
        'twitter:title'  => $title,
        'twitter:description' => wp_strip_all_tags( $desc ),
        'twitter:image'  => $image,
        'twitter:site'   => '@infinityskytravels',
    ];

    foreach ( $tags as $property => $content ) {
        if ( ! $content ) continue;
        $attr = str_starts_with( $property, 'twitter:' ) ? 'name' : 'property';
        printf( '<meta %s="%s" content="%s">' . "\n", esc_attr( $attr ), esc_attr( $property ), esc_attr( $content ) );
    }
}
