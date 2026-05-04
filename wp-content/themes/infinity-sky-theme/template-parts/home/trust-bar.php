<?php
/**
 * Section 2: Trust bar — badges + animated stat counters.
 */
?>

<section class="ist-trust-bar" aria-label="<?php esc_attr_e( 'Why trust Infinity Sky Travels', 'infinity-sky' ); ?>">
    <div class="ist-container">

        <!-- Trust badges -->
        <div class="ist-trust-bar__grid" data-stagger>

            <div class="ist-trust-item" data-fade>
                <div class="ist-trust-item__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <span class="ist-trust-item__label"><?php esc_html_e( 'TAAN Licensed Agency', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-trust-item" data-fade>
                <div class="ist-trust-item__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2.5">
                        <path d="M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z"/>
                    </svg>
                </div>
                <span class="ist-trust-item__label"><?php esc_html_e( 'All 6 Nepal Airlines', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-trust-item" data-fade>
                <div class="ist-trust-item__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="color:var(--ist-orange)">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                </div>
                <span class="ist-trust-item__label"><?php esc_html_e( '24/7 WhatsApp Support', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-trust-item" data-fade>
                <div class="ist-trust-item__icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ist-orange)" stroke-width="2.5">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                    </svg>
                </div>
                <span class="ist-trust-item__label"><?php esc_html_e( 'Best Price Guarantee', 'infinity-sky' ); ?></span>
            </div>

        </div><!-- /.ist-trust-bar__grid -->

        <!-- Animated stat counters -->
        <div class="ist-trust-bar__stats" data-stagger>

            <div class="ist-trust-stat" data-fade>
                <span class="ist-trust-stat__number"
                      data-counter="500"
                      data-suffix="+"
                      aria-label="500+ routes">0</span>
                <span class="ist-trust-stat__label"><?php esc_html_e( 'Routes Covered', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-trust-stat" data-fade>
                <span class="ist-trust-stat__number"
                      data-counter="2000"
                      data-suffix="+"
                      aria-label="2000+ happy travellers">0</span>
                <span class="ist-trust-stat__label"><?php esc_html_e( 'Happy Travellers', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-trust-stat" data-fade>
                <span class="ist-trust-stat__number"
                      data-counter="10"
                      data-suffix="+"
                      aria-label="10+ years experience">0</span>
                <span class="ist-trust-stat__label"><?php esc_html_e( 'Years Experience', 'infinity-sky' ); ?></span>
            </div>

            <div class="ist-trust-stat" data-fade>
                <span class="ist-trust-stat__number"
                      data-counter="6"
                      aria-label="6 partner airlines">0</span>
                <span class="ist-trust-stat__label"><?php esc_html_e( 'Partner Airlines', 'infinity-sky' ); ?></span>
            </div>

        </div><!-- /.ist-trust-bar__stats -->

    </div>
</section>
