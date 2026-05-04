<?php
/**
 * Section 6: Traveller types — 3 full-height cards side-by-side.
 * Hover reveals suggested packages per type.
 */

$types = [
    [
        'title'    => __( 'The Backpacker', 'infinity-sky' ),
        'tagline'  => __( 'Adventure without breaking the bank', 'infinity-sky' ),
        'range'    => __( 'Budget picks under $700', 'infinity-sky' ),
        'link'     => home_url( '/packages?type=backpacker' ),
        'image'    => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=75&auto=format&fit=crop',
        'image_alt'=> 'Backpacker trekking Nepal mountains',
        'packages' => [
            'Langtang Valley Trek — 10 Days from $750',
            'Nepal Cultural Heritage — 7 Days from $550',
            'Annapurna Base Camp — 12 Days from $950',
        ],
    ],
    [
        'title'    => __( 'The Explorer', 'infinity-sky' ),
        'tagline'  => __( 'Balanced comfort and authenticity', 'infinity-sky' ),
        'range'    => __( 'Mid-range $700–$1,500', 'infinity-sky' ),
        'link'     => home_url( '/packages?type=mid-range' ),
        'image'    => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=75&auto=format&fit=crop',
        'image_alt'=> 'Trekker in Nepal mountains',
        'packages' => [
            'EBC Classic Trek — 14 Days from $1,350',
            'Manaslu Circuit — 14 Days from $1,450',
            'Rara Lake Trek — 10 Days from $1,200',
        ],
    ],
    [
        'title'    => __( 'The Luxury Trekker', 'infinity-sky' ),
        'tagline'  => __( 'Premium lodges, private guides', 'infinity-sky' ),
        'range'    => __( 'Premium from $2,500', 'infinity-sky' ),
        'link'     => home_url( '/packages?type=luxury' ),
        'image'    => 'https://images.unsplash.com/photo-1562303817-4a05a820a1e1?w=800&q=75&auto=format&fit=crop',
        'image_alt'=> 'Luxury mountain lodge Nepal Everest',
        'packages' => [
            'EBC Luxury Trek — 16 Days from $3,500',
            'Upper Mustang — 12 Days from $2,100',
            'Custom Heli Trek — from $4,500',
        ],
    ],
];
?>

<section class="ist-section" id="traveller-types" aria-labelledby="traveller-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <h2 class="ist-section-heading" id="traveller-heading">
                <?php esc_html_e( 'Which Traveller Are You?', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading">
                <?php esc_html_e( 'We tailor every Nepal experience to your style and budget.', 'infinity-sky' ); ?>
            </p>
        </div>

    </div>

    <div class="ist-traveller-grid" data-fade role="list">
        <?php foreach ( $types as $type ) : ?>

        <div class="ist-traveller-card" role="listitem">
            <div class="ist-traveller-card__image" aria-hidden="true">
                <img src="<?php echo esc_url( $type['image'] ); ?>"
                     alt="<?php echo esc_attr( $type['image_alt'] ); ?>"
                     loading="lazy"
                     width="800" height="1000">
            </div>
            <div class="ist-traveller-card__overlay" aria-hidden="true"></div>

            <div class="ist-traveller-card__content">
                <h3 class="ist-traveller-card__type"><?php echo esc_html( $type['title'] ); ?></h3>
                <p class="ist-traveller-card__desc"><?php echo esc_html( $type['tagline'] ); ?></p>
                <span class="ist-traveller-card__range"><?php echo esc_html( $type['range'] ); ?></span>

                <div class="ist-traveller-card__packages">
                    <ul>
                        <?php foreach ( $type['packages'] as $pkg ) : ?>
                            <li><?php echo esc_html( $pkg ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo esc_url( $type['link'] ); ?>" class="btn-primary btn-sm" style="margin-top:14px;display:inline-flex;">
                        <?php esc_html_e( 'See Packages →', 'infinity-sky' ); ?>
                    </a>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>

</section>
