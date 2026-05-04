<?php
/**
 * Section 4: Featured trek packages — top 6 from CPT,
 * horizontal scroll on mobile via Swiper, 3-col grid on desktop.
 */

$packages_query = new WP_Query( [
    'post_type'      => 'ist_package',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );

$has_packages = $packages_query->have_posts();
?>

<section class="ist-section ist-featured-packages" id="featured-packages" aria-labelledby="packages-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <h2 class="ist-section-heading" id="packages-heading">
                <?php esc_html_e( 'Iconic Trekking Packages', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading">
                <?php esc_html_e( 'Handpicked adventures for independent travellers — from budget teahouse to luxury lodge.', 'infinity-sky' ); ?>
            </p>
        </div>

        <?php if ( $has_packages ) : ?>

        <!-- Desktop: 3-col grid | Mobile: horizontal Swiper -->
        <div class="ist-package-grid ist-package-grid--home swiper packages-swiper" data-stagger>
            <div class="swiper-wrapper">
                <?php while ( $packages_query->have_posts() ) : $packages_query->the_post(); ?>
                    <div class="swiper-slide">
                        <?php get_template_part( 'template-parts/global/package-card' ); ?>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <?php else : ?>

        <!-- Fallback static cards when no CPT posts exist yet -->
        <div class="ist-package-grid ist-package-grid--home" data-stagger>

            <?php
            $static_packages = [
                [
                    'title'      => 'Everest Base Camp Classic Trek',
                    'days'       => 14,
                    'difficulty' => 'challenging',
                    'price'      => 1350,
                    'highlight'  => 'Stand at 5,545m — Kala Patthar viewpoint',
                    'highlights' => [ 'Lukla flight included', 'Experienced guide + porter', 'National park permits', 'All meals on trek' ],
                    'slug'       => 'everest-base-camp-classic-trek',
                    'image'      => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=75&auto=format&fit=crop',
                    'image_alt'  => 'Everest Base Camp trek Nepal',
                ],
                [
                    'title'      => 'Annapurna Base Camp Trek',
                    'days'       => 12,
                    'difficulty' => 'moderate',
                    'price'      => 950,
                    'highlight'  => 'Poon Hill sunrise + Jhinu hot springs',
                    'highlights' => [ 'Poon Hill 3,210m sunrise', 'Jhinu hot springs', 'Modi Khola gorge', 'ABC glacier amphitheatre' ],
                    'slug'       => 'annapurna-base-camp-trek',
                    'image'      => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=600&q=75&auto=format&fit=crop',
                    'image_alt'  => 'Annapurna Base Camp trek Nepal',
                ],
                [
                    'title'      => 'Langtang Valley Trek',
                    'days'       => 10,
                    'difficulty' => 'moderate',
                    'price'      => 750,
                    'highlight'  => 'Kyanjin Gompa & yak cheese factory',
                    'highlights' => [ 'Kyanjin Gompa monastery', 'Yak cheese factory', 'Tserko Ri panorama', 'Close to Kathmandu' ],
                    'slug'       => 'langtang-valley-trek',
                    'image'      => 'https://images.unsplash.com/photo-1569437061241-a848be43cc82?w=600&q=75&auto=format&fit=crop',
                    'image_alt'  => 'Langtang Valley Nepal',
                ],
                [
                    'title'      => 'Upper Mustang Forbidden Kingdom',
                    'days'       => 12,
                    'difficulty' => 'moderate',
                    'price'      => 2100,
                    'highlight'  => 'Ancient walled city Lo Manthang',
                    'highlights' => [ 'Lo Manthang walled city', 'Tibetan Buddhist culture', 'Restricted area permit', 'Unique arid landscape' ],
                    'slug'       => 'upper-mustang-forbidden-kingdom',
                    'image'      => 'https://images.unsplash.com/photo-1589553416260-f586c1f2c4e9?w=600&q=75&auto=format&fit=crop',
                    'image_alt'  => 'Upper Mustang Lo Manthang Nepal',
                ],
                [
                    'title'      => 'Rara Lake Trek',
                    'days'       => 10,
                    'difficulty' => 'moderate',
                    'price'      => 1200,
                    'highlight'  => "Nepal's largest lake — almost no crowds",
                    'highlights' => [ "Nepal's largest lake", 'Remote & pristine', '2 domestic flights', 'Alpine scenery' ],
                    'slug'       => 'rara-lake-trek',
                    'image'      => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?w=600&q=75&auto=format&fit=crop',
                    'image_alt'  => 'Rara Lake Nepal',
                ],
                [
                    'title'      => 'EBC Luxury Trek',
                    'days'       => 16,
                    'difficulty' => 'challenging',
                    'price'      => 3500,
                    'highlight'  => 'Yeti Mountain Home lodges + heli return',
                    'highlights' => [ 'Yeti Mountain Home lodges', 'Private guide throughout', 'Helicopter return option', 'Single rooms guaranteed' ],
                    'slug'       => 'ebc-luxury-trek',
                    'image'      => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600&q=75&auto=format&fit=crop',
                    'image_alt'  => 'Luxury EBC trek Everest Nepal',
                ],
            ];
            foreach ( $static_packages as $pkg ) :
            ?>
            <article class="ist-package-card" itemscope itemtype="https://schema.org/TouristTrip">
                <div class="ist-package-card__image">
                    <img src="<?php echo esc_url( $pkg['image'] ); ?>"
                         alt="<?php echo esc_attr( $pkg['image_alt'] ); ?>"
                         loading="lazy"
                         width="600" height="800"
                         itemprop="image">
                </div>
                <div class="ist-package-card__gradient" aria-hidden="true"></div>
                <div class="ist-package-card__content">
                    <div class="ist-package-card__meta">
                        <?php echo ist_difficulty_badge( $pkg['difficulty'] ); ?>
                        <span class="ist-package-card__duration"><?php echo esc_html( $pkg['days'] ); ?> Days</span>
                    </div>
                    <h3 class="ist-package-card__title" itemprop="name">
                        <a href="<?php echo esc_url( home_url( '/packages/' . $pkg['slug'] ) ); ?>" style="color:inherit;text-decoration:none;">
                            <?php echo esc_html( $pkg['title'] ); ?>
                        </a>
                    </h3>
                    <p class="ist-package-card__highlight"><?php echo esc_html( $pkg['highlight'] ); ?></p>
                    <div class="ist-package-card__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                        <meta itemprop="priceCurrency" content="USD">
                        <meta itemprop="price" content="<?php echo esc_attr( $pkg['price'] ); ?>">
                        from <strong><?php echo esc_html( ist_format_price( $pkg['price'] ) ); ?></strong><span>/person</span>
                    </div>
                    <div class="ist-package-card__actions">
                        <a href="<?php echo esc_url( home_url( '/packages/' . $pkg['slug'] ) ); ?>" class="btn-primary btn-sm">View Package</a>
                        <a href="<?php echo esc_url( 'https://wa.me/9779810597893?text=' . rawurlencode( 'Hi! I\'m interested in the ' . $pkg['title'] . ' package.' ) ); ?>"
                           class="btn-outline btn-sm" target="_blank" rel="noopener noreferrer">Quick Enquiry</a>
                    </div>
                </div>
                <div class="ist-package-card__hover-overlay" aria-hidden="true">
                    <ul class="ist-package-card__highlights-list">
                        <?php foreach ( array_slice( $pkg['highlights'], 0, 4 ) as $hl ) : ?>
                            <li><?php echo esc_html( $hl ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </article>
            <?php endforeach; ?>

        </div><!-- /.ist-package-grid static fallback -->

        <?php endif; ?>

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-primary btn-lg">
                <?php esc_html_e( 'View All Packages →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>
