<?php
/**
 * Section 8: Testimonials — Swiper slider, dark background.
 */

$testimonials = [
    [
        'name'       => 'Sarah M.',
        'flag'       => '🇺🇸',
        'country'    => 'USA',
        'trek'       => 'EBC Classic Trek',
        'rating'     => 5,
        'quote'      => 'Seamless from KTM airport pickup to Lukla flight. The team handled every permit, every transfer. Best agency in Thamel — hands down.',
        'avatar_bg'  => '#E8751A',
        'initials'   => 'SM',
    ],
    [
        'name'       => 'James T.',
        'flag'       => '🇦🇺',
        'country'    => 'Australia',
        'trek'       => 'Annapurna Base Camp',
        'rating'     => 5,
        'quote'      => 'They handled everything. The add-on hotel booking in Pokhara saved us so much hassle. Our guide was brilliant — knowledgeable and hilarious.',
        'avatar_bg'  => '#29ABE2',
        'initials'   => 'JT',
    ],
    [
        'name'       => 'Miriam K.',
        'flag'       => '🇮🇱',
        'country'    => 'Israel',
        'trek'       => 'Upper Mustang',
        'rating'     => 5,
        'quote'      => 'Restricted area permits sorted in one day. Lo Manthang was absolutely magical. Infinity Sky made the impossible feel easy.',
        'avatar_bg'  => '#2ECC71',
        'initials'   => 'MK',
    ],
    [
        'name'       => 'Tom & Lisa W.',
        'flag'       => '🇬🇧',
        'country'    => 'United Kingdom',
        'trek'       => 'Rara Lake Trek',
        'rating'     => 5,
        'quote'      => 'The custom itinerary builder matched us with exactly the right package. The domestic flights to Talcha were booked perfectly. Rara Lake is breathtaking.',
        'avatar_bg'  => '#9B59B6',
        'initials'   => 'TW',
    ],
];
?>

<section class="ist-testimonials" id="testimonials" aria-labelledby="testimonials-heading">
    <div class="ist-container">

        <div class="text-center" data-fade>
            <h2 class="ist-section-heading ist-text-white" id="testimonials-heading">
                <?php esc_html_e( 'What Our Travellers Say', 'infinity-sky' ); ?>
            </h2>
            <div class="ist-divider"></div>
            <p class="ist-section-subheading" style="color:rgba(255,255,255,0.55);">
                <?php esc_html_e( 'Real reviews from independent travellers who trusted us with their Nepal adventure.', 'infinity-sky' ); ?>
            </p>
        </div>

        <div class="swiper testimonials-swiper" role="list" aria-label="<?php esc_attr_e( 'Traveller testimonials', 'infinity-sky' ); ?>">
            <div class="swiper-wrapper">

                <?php foreach ( $testimonials as $t ) : ?>
                <div class="swiper-slide" role="listitem">
                    <div class="ist-testimonial-card">

                        <!-- Stars -->
                        <div class="ist-stars" aria-label="<?php printf( esc_attr__( '%d out of 5 stars', 'infinity-sky' ), $t['rating'] ); ?>">
                            <?php for ( $i = 0; $i < $t['rating']; $i++ ) : ?>★<?php endfor; ?>
                        </div>

                        <!-- Quote -->
                        <p class="ist-testimonial__quote">
                            &ldquo;<?php echo esc_html( $t['quote'] ); ?>&rdquo;
                        </p>

                        <!-- Author -->
                        <div class="ist-testimonial__author">
                            <div class="ist-testimonial__avatar"
                                 style="background:<?php echo esc_attr( $t['avatar_bg'] ); ?>;display:flex;align-items:center;justify-content:center;font-family:var(--font-heading);font-weight:800;font-size:16px;color:#fff;"
                                 aria-hidden="true">
                                <?php echo esc_html( $t['initials'] ); ?>
                            </div>
                            <div>
                                <div class="ist-testimonial__name">
                                    <?php echo esc_html( $t['flag'] . ' ' . $t['name'] ); ?>
                                </div>
                                <div class="ist-testimonial__meta">
                                    <span><?php echo esc_html( $t['country'] ); ?></span>
                                    <span aria-hidden="true">·</span>
                                    <span class="ist-testimonial__trek"><?php echo esc_html( $t['trek'] ); ?></span>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.ist-testimonial-card -->
                </div><!-- /.swiper-slide -->
                <?php endforeach; ?>

            </div><!-- /.swiper-wrapper -->

            <!-- Pagination -->
            <div class="swiper-pagination" style="margin-top:var(--space-xl);position:static;"></div>

        </div><!-- /.swiper -->

        <!-- CTA below testimonials -->
        <div class="text-center mt-lg" data-fade>
            <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-primary">
                <?php esc_html_e( 'Start Your Adventure →', 'infinity-sky' ); ?>
            </a>
        </div>

    </div>
</section>

<style>
.testimonials-swiper .swiper-pagination-bullet { background: rgba(255,255,255,0.3); opacity: 1; }
.testimonials-swiper .swiper-pagination-bullet-active { background: var(--ist-orange); }
</style>
