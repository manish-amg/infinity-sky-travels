<?php
/**
 * Section 9: Latest blog posts — 3-card row.
 */

$blog_query = new WP_Query( [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'ignore_sticky_posts' => true,
] );
?>

<?php if ( $blog_query->have_posts() ) : ?>

<section class="ist-section" id="blog-preview" aria-labelledby="blog-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <h2 class="ist-section-heading" id="blog-heading">
                <?php esc_html_e( 'Nepal Travel Guides & Tips', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading">
                <?php esc_html_e( 'Expert advice on trekking, flights, permits, and planning your perfect Nepal trip.', 'infinity-sky' ); ?>
            </p>
        </div>

        <div class="ist-blog-grid" data-stagger>
            <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>

            <article class="ist-blog-card" data-fade itemscope itemtype="https://schema.org/BlogPosting">
                <div class="ist-blog-card__image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                            <?php the_post_thumbnail( 'ist-card', [ 'itemprop' => 'image', 'loading' => 'lazy' ] ); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                            <img src="https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=600&q=75&auto=format&fit=crop"
                                 alt="<?php the_title_attribute(); ?>" loading="lazy" width="600" height="400">
                        </a>
                    <?php endif; ?>
                </div>

                <div class="ist-blog-card__body">
                    <div class="ist-blog-card__meta">
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) :
                        ?>
                        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
                           class="ist-tag ist-tag--orange ist-blog-card__category"
                           itemprop="articleSection">
                            <?php echo esc_html( $cats[0]->name ); ?>
                        </a>
                        <?php endif; ?>
                        <time class="ist-blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                        <span class="ist-blog-card__date"><?php echo esc_html( ist_reading_time() ); ?></span>
                    </div>

                    <h3 class="ist-blog-card__title" itemprop="headline">
                        <a href="<?php the_permalink(); ?>" itemprop="url"><?php the_title(); ?></a>
                    </h3>

                    <p class="ist-blog-card__excerpt" itemprop="description">
                        <?php echo esc_html( get_the_excerpt() ); ?>
                    </p>

                    <a href="<?php the_permalink(); ?>" class="ist-blog-card__read-more" aria-label="<?php printf( esc_attr__( 'Read %s', 'infinity-sky' ), get_the_title() ); ?>">
                        <?php esc_html_e( 'Read Article', 'infinity-sky' ); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>

        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="btn-outline--dark btn-outline">
                <?php esc_html_e( 'View All Articles →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>

<?php endif; ?>
