<?php
/**
 * Single Blog Post Template
 */

get_header();

while ( have_posts() ) : the_post();

$categories  = get_the_category();
$cat         = ! empty( $categories ) ? $categories[0] : null;
$thumb_url   = get_the_post_thumbnail_url( null, 'full' ) ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1920&q=80&auto=format&fit=crop';
$author_id   = get_the_author_meta( 'ID' );
$author_name = get_the_author_meta( 'display_name' );
$author_bio  = get_the_author_meta( 'description' ) ?: 'Travel writer and Nepal trekking enthusiast at Infinity Sky Travels.';
$author_avatar = get_avatar_url( $author_id, [ 'size' => 80 ] );
$read_time   = max( 1, round( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
?>

<div class="ist-single-post">

    <!-- ── Hero ─────────────────────────────────────────────────── -->
    <div class="ist-post-hero jarallax" data-jarallax data-speed="0.5"
         style="background-image:url('<?php echo esc_url( $thumb_url ); ?>');">
        <div class="ist-post-hero__overlay" aria-hidden="true"></div>
        <div class="ist-container" style="position:relative;z-index:1;">
            <?php get_template_part( 'template-parts/global/breadcrumb' ); ?>

            <?php if ( $cat ) : ?>
            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
               class="ist-post-hero__cat"><?php echo esc_html( $cat->name ); ?></a>
            <?php endif; ?>

            <h1 class="ist-post-hero__title"><?php the_title(); ?></h1>

            <div class="ist-post-hero__meta">
                <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>"
                     class="ist-post-hero__avatar" width="40" height="40">
                <span class="ist-post-hero__author"><?php echo esc_html( $author_name ); ?></span>
                <span class="ist-post-hero__sep" aria-hidden="true">·</span>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
                <span class="ist-post-hero__sep" aria-hidden="true">·</span>
                <span><?php echo esc_html( $read_time ); ?> <?php esc_html_e( 'min read', 'infinity-sky' ); ?></span>
            </div>
        </div>
    </div>

    <!-- ── Body ─────────────────────────────────────────────────── -->
    <div class="ist-container">
        <div class="ist-post-body">

            <!-- ── Content ──────────────────────────────────────── -->
            <article class="ist-post-content" itemscope itemtype="https://schema.org/Article">
                <meta itemprop="headline"      content="<?php echo esc_attr( get_the_title() ); ?>">
                <meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                <meta itemprop="author"        content="<?php echo esc_attr( $author_name ); ?>">

                <div class="ist-post-content__body" itemprop="articleBody">
                    <?php the_content(); ?>
                </div>

                <!-- Tags -->
                <?php $tags = get_the_tags();
                if ( $tags ) : ?>
                <div class="ist-post-tags">
                    <?php foreach ( $tags as $tag ) : ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="ist-post-tag">
                        #<?php echo esc_html( $tag->name ); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Share row -->
                <div class="ist-post-share">
                    <span class="ist-post-share__label"><?php esc_html_e( 'Share this article:', 'infinity-sky' ); ?></span>
                    <?php
                    $url   = urlencode( get_permalink() );
                    $title = urlencode( get_the_title() );
                    ?>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>"
                       class="ist-post-share__btn ist-post-share__btn--twitter" target="_blank" rel="noopener noreferrer" aria-label="Share on Twitter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>"
                       class="ist-post-share__btn ist-post-share__btn--facebook" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://wa.me/?text=<?php echo $title . '%20' . $url; ?>"
                       class="ist-post-share__btn ist-post-share__btn--wa" target="_blank" rel="noopener noreferrer" aria-label="Share on WhatsApp">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    </a>
                </div>

                <!-- Author box -->
                <div class="ist-author-box">
                    <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>"
                         class="ist-author-box__avatar" width="80" height="80">
                    <div class="ist-author-box__info">
                        <p class="ist-author-box__name"><?php echo esc_html( $author_name ); ?></p>
                        <p class="ist-author-box__bio"><?php echo esc_html( $author_bio ); ?></p>
                    </div>
                </div>

                <!-- Post navigation -->
                <nav class="ist-post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'infinity-sky' ); ?>">
                    <?php $prev = get_previous_post(); $next = get_next_post(); ?>
                    <?php if ( $prev ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="ist-post-nav__item ist-post-nav__item--prev">
                        <span class="ist-post-nav__dir">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            <?php esc_html_e( 'Previous', 'infinity-sky' ); ?>
                        </span>
                        <span class="ist-post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
                    </a>
                    <?php endif; ?>
                    <?php if ( $next ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="ist-post-nav__item ist-post-nav__item--next">
                        <span class="ist-post-nav__dir">
                            <?php esc_html_e( 'Next', 'infinity-sky' ); ?>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </span>
                        <span class="ist-post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
                    </a>
                    <?php endif; ?>
                </nav>

            </article>

            <!-- ── Sidebar ───────────────────────────────────────── -->
            <aside class="ist-post-sidebar">

                <!-- CTA widget -->
                <div class="ist-post-sidebar__widget ist-post-sidebar__widget--cta">
                    <p class="ist-post-sidebar__cta-eyebrow"><?php esc_html_e( 'Ready to trek?', 'infinity-sky' ); ?></p>
                    <h3><?php esc_html_e( 'Plan Your Nepal Adventure', 'infinity-sky' ); ?></h3>
                    <p><?php esc_html_e( 'Turn this article into a real itinerary — free, within 24 hours.', 'infinity-sky' ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="btn-primary" style="width:100%;justify-content:center;">
                        <?php esc_html_e( 'Get a Free Quote', 'infinity-sky' ); ?>
                    </a>
                </div>

                <!-- Popular posts -->
                <?php
                $popular = new WP_Query([
                    'post_type'      => 'post',
                    'posts_per_page' => 4,
                    'post__not_in'   => [ get_the_ID() ],
                    'post_status'    => 'publish',
                    'orderby'        => 'comment_count',
                    'order'          => 'DESC',
                ]);
                if ( $popular->have_posts() ) : ?>
                <div class="ist-post-sidebar__widget">
                    <h4 class="ist-post-sidebar__widget-title"><?php esc_html_e( 'Popular Articles', 'infinity-sky' ); ?></h4>
                    <?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="ist-post-sidebar__popular-item">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'thumbnail' ) ); ?>"
                             alt="<?php echo esc_attr( get_the_title() ); ?>"
                             class="ist-post-sidebar__popular-thumb" width="60" height="60">
                        <?php endif; ?>
                        <div>
                            <p class="ist-post-sidebar__popular-title"><?php the_title(); ?></p>
                            <p class="ist-post-sidebar__popular-date"><?php echo esc_html( get_the_date() ); ?></p>
                        </div>
                    </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <?php endif; ?>

                <!-- Category list -->
                <?php
                $cats = get_categories( [ 'hide_empty' => true ] );
                if ( $cats ) : ?>
                <div class="ist-post-sidebar__widget">
                    <h4 class="ist-post-sidebar__widget-title"><?php esc_html_e( 'Categories', 'infinity-sky' ); ?></h4>
                    <ul class="ist-post-sidebar__cat-list">
                        <?php foreach ( $cats as $c ) : ?>
                        <li>
                            <a href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>">
                                <?php echo esc_html( $c->name ); ?>
                                <span>(<?php echo esc_html( $c->count ); ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

            </aside>

        </div><!-- /.ist-post-body -->
    </div>

    <!-- ── Related posts ─────────────────────────────────────────── -->
    <?php
    $related_args = [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => [ get_the_ID() ],
        'post_status'    => 'publish',
        'orderby'        => 'rand',
    ];
    if ( $cat ) {
        $related_args['category__in'] = [ $cat->term_id ];
    }
    $related_posts = new WP_Query( $related_args );
    if ( $related_posts->have_posts() ) : ?>
    <div class="ist-section ist-section--light">
        <div class="ist-container">
            <h2 class="ist-section-title" style="margin-bottom:var(--space-xl);"><?php esc_html_e( 'You Might Also Enjoy', 'infinity-sky' ); ?></h2>
            <div class="ist-blog-grid">
                <?php while ( $related_posts->have_posts() ) : $related_posts->the_post();
                    get_template_part( 'template-parts/global/blog-card' );
                endwhile;
                wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /.ist-single-post -->

<?php endwhile; ?>
<?php get_footer(); ?>
