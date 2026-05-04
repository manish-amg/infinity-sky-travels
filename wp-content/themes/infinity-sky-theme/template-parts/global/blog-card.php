<?php
/**
 * Blog post card — used in archive, single related, home preview.
 */

$cats      = get_the_category();
$cat       = ! empty( $cats ) ? $cats[0] : null;
$thumb     = get_the_post_thumbnail_url( null, 'medium_large' ) ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&q=70&auto=format&fit=crop';
$read_time = max( 1, round( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
?>
<article class="ist-blog-card" itemscope itemtype="https://schema.org/Article">
    <a href="<?php the_permalink(); ?>" class="ist-blog-card__thumb-link" tabindex="-1" aria-hidden="true">
        <div class="ist-blog-card__thumb">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"
                 loading="lazy" width="800" height="480">
        </div>
    </a>
    <div class="ist-blog-card__body">
        <?php if ( $cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="ist-blog-card__cat">
            <?php echo esc_html( $cat->name ); ?>
        </a>
        <?php endif; ?>
        <h3 class="ist-blog-card__title" itemprop="headline">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="ist-blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
        <div class="ist-blog-card__meta">
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
                <?php echo esc_html( get_the_date() ); ?>
            </time>
            <span>·</span>
            <span><?php echo esc_html( $read_time ); ?> <?php esc_html_e( 'min read', 'infinity-sky' ); ?></span>
        </div>
    </div>
</article>
