<?php
/**
 * Plugin Name:  IST Sample Content
 * Description:  One-click installer for all Infinity Sky Travels sample packages, blog posts, categories, and taxonomy terms. Disable and delete after installation.
 * Version:      1.0.0
 * Author:       Infinity Sky Travels
 * Text Domain:  ist-sc
 */

defined( 'ABSPATH' ) || exit;

define( 'IST_SC_DIR', plugin_dir_path( __FILE__ ) );

// ── Admin page ────────────────────────────────────────────────────────────────
add_action( 'admin_menu', 'ist_sc_menu' );
function ist_sc_menu(): void {
    add_management_page(
        'Install Sample Content',
        'IST Sample Content',
        'manage_options',
        'ist-sample-content',
        'ist_sc_page'
    );
}

function ist_sc_page(): void {
    $installed = get_option( 'ist_sc_installed', false );
    ?>
    <div class="wrap">
        <h1>IST Sample Content Installer</h1>
        <?php if ( $installed ) : ?>
            <div class="notice notice-success"><p>✅ Sample content was installed on <?php echo esc_html( $installed ); ?>. You can safely deactivate and delete this plugin.</p></div>
        <?php endif; ?>

        <p>This will install:</p>
        <ul style="list-style:disc;padding-left:24px;">
            <li><strong>8 package posts</strong> with full itinerary, ACF meta, highlights, FAQ, and pricing</li>
            <li><strong>10 blog posts</strong> with full content across categories</li>
            <li><strong>Taxonomy terms</strong>: regions, difficulty levels, traveller types, blog categories</li>
            <li><strong>3 static pages</strong>: Plan My Trip, Booking Confirmed, Privacy Policy stubs</li>
        </ul>
        <p style="color:#ef4444;"><strong>Warning:</strong> This may create duplicate content if run more than once. Run only on a fresh WordPress install.</p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'ist_sc_install', 'ist_sc_nonce' ); ?>
            <input type="hidden" name="action" value="ist_sc_install">
            <p>
                <input type="submit" class="button button-primary button-large" value="<?php echo $installed ? '🔄 Reinstall Sample Content' : '🚀 Install Sample Content'; ?>">
            </p>
        </form>
    </div>
    <?php
}

// ── Handle install ────────────────────────────────────────────────────────────
add_action( 'admin_post_ist_sc_install', 'ist_sc_install' );
function ist_sc_install(): void {
    check_admin_referer( 'ist_sc_install', 'ist_sc_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

    ist_sc_install_taxonomies();
    ist_sc_install_packages();
    ist_sc_install_blog_posts();
    ist_sc_install_pages();

    update_option( 'ist_sc_installed', current_time( 'mysql' ) );

    wp_redirect( admin_url( 'tools.php?page=ist-sample-content&installed=1' ) );
    exit;
}

// ── Taxonomy terms ────────────────────────────────────────────────────────────
function ist_sc_install_taxonomies(): void {
    // Regions
    $regions = [
        'khumbu-everest'  => 'Khumbu / Everest',
        'annapurna'       => 'Annapurna',
        'langtang'        => 'Langtang',
        'mustang'         => 'Mustang',
        'manaslu'         => 'Manaslu',
        'kathmandu-valley'=> 'Kathmandu Valley',
        'pokhara'         => 'Pokhara',
        'chitwan'         => 'Chitwan & Bardia',
        'kanchenjunga'    => 'Kanchenjunga',
    ];
    foreach ( $regions as $slug => $name ) {
        if ( ! term_exists( $slug, 'ist_region' ) ) {
            wp_insert_term( $name, 'ist_region', [ 'slug' => $slug ] );
        }
    }

    // Difficulty
    $difficulties = [
        'easy'        => 'Easy',
        'moderate'    => 'Moderate',
        'challenging' => 'Challenging',
        'strenuous'   => 'Strenuous',
        'extreme'     => 'Extreme',
    ];
    foreach ( $difficulties as $slug => $name ) {
        if ( ! term_exists( $slug, 'ist_difficulty' ) ) {
            wp_insert_term( $name, 'ist_difficulty', [ 'slug' => $slug ] );
        }
    }

    // Traveller types
    $types = [
        'adventure'   => 'Adventure',
        'family'      => 'Family',
        'cultural'    => 'Cultural',
        'solo'        => 'Solo',
        'couple'      => 'Couple',
        'group'       => 'Group',
        'luxury'      => 'Luxury',
        'photography' => 'Photography',
    ];
    foreach ( $types as $slug => $name ) {
        if ( ! term_exists( $slug, 'ist_traveller_type' ) ) {
            wp_insert_term( $name, 'ist_traveller_type', [ 'slug' => $slug ] );
        }
    }

    // Blog categories
    $cats = [ 'Trek Guides', 'Travel Tips', 'Destinations', 'Health & Safety', 'Nepal Culture', 'Trip Reports' ];
    foreach ( $cats as $cat ) {
        if ( ! term_exists( $cat, 'category' ) ) {
            wp_insert_term( $cat, 'category' );
        }
    }
}

// ── Packages ──────────────────────────────────────────────────────────────────
function ist_sc_install_packages(): void {
    $packages = require IST_SC_DIR . 'data/packages.php';

    foreach ( $packages as $pkg ) {
        $post_id = wp_insert_post( [
            'post_type'    => 'ist_package',
            'post_title'   => $pkg['title'],
            'post_excerpt' => $pkg['excerpt'],
            'post_content' => $pkg['content'],
            'post_status'  => 'publish',
            'menu_order'   => 0,
        ] );

        if ( is_wp_error( $post_id ) ) continue;

        $acf = $pkg['acf'];

        // Core ACF-compatible meta
        $meta_map = [
            'duration'          => $acf['duration'],
            'difficulty'        => $acf['difficulty'],
            'altitude'          => $acf['altitude'],
            'min_group_size'    => $acf['min_group_size'],
            'max_group_size'    => $acf['max_group_size'],
            'start_location'    => $acf['start_location'],
            'end_location'      => $acf['end_location'],
            'seasons'           => $acf['seasons'],
            'domestic_flight'   => $acf['domestic_flight'] ? '1' : '0',
            'departure_airport' => $acf['departure_airport'],
            'arrival_airport'   => $acf['arrival_airport'],
            'price'             => $acf['price'],
            'currency'          => $acf['currency'],
            'is_featured'       => $acf['is_featured'] ? '1' : '0',
            'is_bestseller'     => $acf['is_bestseller'] ? '1' : '0',
            'gallery'           => $acf['gallery'],
            'highlights'        => $acf['highlights'],
            'inclusions'        => $acf['inclusions'],
            'exclusions'        => $acf['exclusions'],
            'itinerary'         => $acf['itinerary'],
            'faq'               => $acf['faq'],
        ];

        foreach ( $meta_map as $key => $value ) {
            if ( is_array( $value ) ) {
                update_post_meta( $post_id, $key, $value );
            } else {
                update_post_meta( $post_id, $key, sanitize_text_field( (string) $value ) );
            }
        }

        // Taxonomy assignment
        if ( ! empty( $acf['region'] ) ) {
            $term = get_term_by( 'slug', $acf['region'], 'ist_region' );
            if ( $term ) wp_set_post_terms( $post_id, [ $term->term_id ], 'ist_region' );
        }

        if ( ! empty( $acf['difficulty_tax'] ) ) {
            $term = get_term_by( 'slug', $acf['difficulty_tax'], 'ist_difficulty' );
            if ( $term ) wp_set_post_terms( $post_id, [ $term->term_id ], 'ist_difficulty' );
        }

        if ( ! empty( $acf['traveller_type'] ) ) {
            $term_ids = [];
            foreach ( (array) $acf['traveller_type'] as $slug ) {
                $t = get_term_by( 'slug', $slug, 'ist_traveller_type' );
                if ( $t ) $term_ids[] = $t->term_id;
            }
            if ( $term_ids ) wp_set_post_terms( $post_id, $term_ids, 'ist_traveller_type' );
        }

        // Set featured image from Unsplash (first gallery image stored as attachment URL meta)
        if ( ! empty( $acf['gallery'][0] ) ) {
            update_post_meta( $post_id, '_thumbnail_url', esc_url_raw( $acf['gallery'][0] ) );
        }
    }
}

// ── Blog posts ────────────────────────────────────────────────────────────────
function ist_sc_install_blog_posts(): void {
    $posts = require IST_SC_DIR . 'data/blog-posts.php';

    // Unsplash banner images per post index
    $banners = [
        'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200',
        'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=1200',
        'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=1200',
        'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1200',
        'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200',
        'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?w=1200',
        'https://images.unsplash.com/photo-1580630274246-ed0fc88a5a40?w=1200',
        'https://images.unsplash.com/photo-1557571249-af93fcba80de?w=1200',
        'https://images.unsplash.com/photo-1593181629936-11c609b8db9b?w=1200',
        'https://images.unsplash.com/photo-1612456225835-44aed09029d2?w=1200',
    ];

    foreach ( $posts as $i => $p ) {
        // Category
        $cat_id = 0;
        $cat    = get_term_by( 'name', $p['category'], 'category' );
        if ( $cat ) $cat_id = $cat->term_id;

        $post_id = wp_insert_post( [
            'post_type'    => 'post',
            'post_title'   => $p['title'],
            'post_name'    => $p['slug'],
            'post_excerpt' => $p['excerpt'],
            'post_content' => $p['content'],
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_category'=> $cat_id ? [ $cat_id ] : [],
        ] );

        if ( is_wp_error( $post_id ) ) continue;

        // Tags
        if ( ! empty( $p['tags'] ) ) {
            wp_set_post_tags( $post_id, $p['tags'] );
        }

        // Store banner URL as meta for the theme to use
        update_post_meta( $post_id, '_thumbnail_url', $banners[ $i ] ?? $banners[0] );
    }
}

// ── Static pages ──────────────────────────────────────────────────────────────
function ist_sc_install_pages(): void {
    $pages = [
        [
            'title'    => 'Plan My Trip',
            'slug'     => 'plan-my-trip',
            'template' => 'page-plan-my-trip.php',
            'content'  => 'Use the form below to tell us about your dream Nepal journey. Our team will create a custom itinerary within 24 hours.',
        ],
        [
            'title'    => 'Booking Confirmed',
            'slug'     => 'booking-confirmed',
            'template' => 'page-booking-confirmed.php',
            'content'  => 'Thank you for your booking with Infinity Sky Travels.',
        ],
        [
            'title'    => 'About Us',
            'slug'     => 'about',
            'template' => 'page-about.php',
            'content'  => 'Infinity Sky Travels — Nepal\'s most trusted trekking and tour company since 2008.',
        ],
        [
            'title'    => 'Contact Us',
            'slug'     => 'contact',
            'template' => 'page-contact.php',
            'content'  => 'Get in touch with our team in Kathmandu.',
        ],
        [
            'title'    => 'Privacy Policy',
            'slug'     => 'privacy-policy',
            'template' => '',
            'content'  => '<h2>Privacy Policy</h2><p>Infinity Sky Travels respects your privacy. We collect only the information necessary to process your bookings and provide our services. We do not sell or share your personal data with third parties. For full details, please contact us at infinityskytravels8@gmail.com.</p>',
        ],
    ];

    foreach ( $pages as $page ) {
        // Skip if slug already exists
        if ( get_page_by_path( $page['slug'] ) ) continue;

        $post_id = wp_insert_post( [
            'post_type'    => 'page',
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_content' => $page['content'],
            'post_status'  => 'publish',
        ] );

        if ( ! is_wp_error( $post_id ) && $page['template'] ) {
            update_post_meta( $post_id, '_wp_page_template', $page['template'] );
        }
    }

    // Set homepage to a "Home" page if it exists, otherwise create it
    if ( ! get_page_by_path( 'home' ) ) {
        $home_id = wp_insert_post( [
            'post_type'    => 'page',
            'post_title'   => 'Home',
            'post_name'    => 'home',
            'post_content' => '',
            'post_status'  => 'publish',
        ] );
        if ( ! is_wp_error( $home_id ) ) {
            update_post_meta( $home_id, '_wp_page_template', 'front-page.php' );
            update_option( 'page_on_front', $home_id );
            update_option( 'show_on_front', 'page' );
        }
    }

    // Set blog posts page
    if ( ! get_page_by_path( 'blog' ) ) {
        $blog_id = wp_insert_post( [
            'post_type'   => 'page',
            'post_title'  => 'Blog',
            'post_name'   => 'blog',
            'post_content'=> '',
            'post_status' => 'publish',
        ] );
        if ( ! is_wp_error( $blog_id ) ) {
            update_option( 'page_for_posts', $blog_id );
        }
    }

    // Create packages archive page
    if ( ! get_page_by_path( 'packages' ) ) {
        wp_insert_post( [
            'post_type'    => 'page',
            'post_title'   => 'Our Packages',
            'post_name'    => 'packages',
            'post_content' => '',
            'post_status'  => 'publish',
        ] );
    }
}

// ── Thumbnail fallback: use _thumbnail_url if no real featured image ─────────
add_filter( 'post_thumbnail_html', 'ist_sc_fallback_thumbnail', 10, 5 );
function ist_sc_fallback_thumbnail( string $html, int $post_id, $thumbnail_id, $size, $attr ): string {
    if ( $html ) return $html;
    $url = get_post_meta( $post_id, '_thumbnail_url', true );
    if ( ! $url ) return $html;
    $w   = is_array( $size ) ? $size[0] : 800;
    $h   = is_array( $size ) ? $size[1] : 500;
    $alt = get_the_title( $post_id );
    return '<img src="' . esc_url( $url ) . '" width="' . esc_attr( $w ) . '" height="' . esc_attr( $h ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
}

add_filter( 'has_post_thumbnail', 'ist_sc_has_fallback_thumbnail', 10, 3 );
function ist_sc_has_fallback_thumbnail( $has, $post, $thumbnail_id ): bool {
    if ( $has ) return $has;
    $post_id = is_int( $post ) ? $post : ( $post instanceof WP_Post ? $post->ID : 0 );
    return (bool) get_post_meta( $post_id, '_thumbnail_url', true );
}

add_filter( 'get_post_metadata', 'ist_sc_thumbnail_id_filter', 10, 4 );
function ist_sc_thumbnail_id_filter( $value, int $post_id, string $meta_key, bool $single ) {
    if ( $meta_key !== '_thumbnail_id' ) return $value;
    if ( $value ) return $value;
    // Return a placeholder ID so has_post_thumbnail() returns true
    $url = get_post_meta( $post_id, '_thumbnail_url', true );
    if ( $url && $single ) return -1; // Sentinel value
    return $value;
}
