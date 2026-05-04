<?php
/**
 * Footer template — 4-column layout, dark #0D0D0D background.
 * Floating elements: WhatsApp button, scroll-to-top.
 */
?>

<!-- ═══════════════════════════════════════════════════════════
     MAIN FOOTER
═══════════════════════════════════════════════════════════ -->
<footer class="ist-footer" role="contentinfo">
    <div class="ist-footer__main">
        <div class="ist-container">
            <div class="ist-footer__grid">

                <!-- Col 1: Brand + Social -->
                <div class="ist-footer__col ist-footer__col--brand">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ist-footer__logo" aria-label="Infinity Sky Travels — Home">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                        <div class="ist-footer__logo-text">
                            <span class="ist-footer__logo-icon">✈</span>
                            <div>
                                <span class="ist-footer__logo-main">Infinity Sky</span>
                                <span class="ist-footer__logo-sub">Travels</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </a>
                    <p class="ist-footer__tagline"><?php esc_html_e( 'Your Sky. Your Nepal. Your Adventure.', 'infinity-sky' ); ?></p>
                    <p class="ist-footer__about">
                        <?php esc_html_e( 'Licensed travel agency based in Thamel, Kathmandu. Specialising in Nepal domestic flights and trekking packages for independent travellers worldwide.', 'infinity-sky' ); ?>
                    </p>
                    <div class="ist-footer__social">
                        <a href="https://www.instagram.com/infinityskytvl" target="_blank" rel="noopener noreferrer" class="ist-footer__social-link" aria-label="Follow us on Instagram">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162S8.597 18.325 12 18.325c3.403 0 6.162-2.759 6.162-6.162 0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=61581195998301" target="_blank" rel="noopener noreferrer" class="ist-footer__social-link" aria-label="Follow us on Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://wa.me/9779810597893" target="_blank" rel="noopener noreferrer" class="ist-footer__social-link ist-footer__social-link--whatsapp" aria-label="Contact us on WhatsApp">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                        </a>
                    </div>
                    <div class="ist-footer__badge">
                        <span class="ist-footer__badge-item">TAAN Licensed</span>
                        <span class="ist-footer__badge-item">NTB Registered</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="ist-footer__col">
                    <h4 class="ist-footer__heading"><?php esc_html_e( 'Quick Links', 'infinity-sky' ); ?></h4>
                    <ul class="ist-footer__links">
                        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'infinity-sky' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/flights' ) ); ?>"><?php esc_html_e( 'Domestic Flights', 'infinity-sky' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages' ) ); ?>"><?php esc_html_e( 'Trek Packages', 'infinity-sky' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>"><?php esc_html_e( 'Plan My Trip', 'infinity-sky' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Travel Blog', 'infinity-sky' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About Us', 'infinity-sky' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'infinity-sky' ); ?></a></li>
                    </ul>
                </div>

                <!-- Col 3: Top Packages -->
                <div class="ist-footer__col">
                    <h4 class="ist-footer__heading"><?php esc_html_e( 'Top Packages', 'infinity-sky' ); ?></h4>
                    <ul class="ist-footer__links">
                        <li><a href="<?php echo esc_url( home_url( '/packages/everest-base-camp-classic-trek' ) ); ?>">EBC Classic Trek — 14 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/annapurna-base-camp-trek' ) ); ?>">Annapurna Base Camp — 12 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/langtang-valley-trek' ) ); ?>">Langtang Valley — 10 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/upper-mustang-forbidden-kingdom' ) ); ?>">Upper Mustang — 12 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/rara-lake-trek' ) ); ?>">Rara Lake Trek — 10 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/manaslu-circuit-trek' ) ); ?>">Manaslu Circuit — 14 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/ebc-luxury-trek' ) ); ?>">EBC Luxury Trek — 16 Days</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/packages/nepal-cultural-heritage-nagarkot' ) ); ?>">Cultural Heritage — 7 Days</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact -->
                <div class="ist-footer__col ist-footer__col--contact">
                    <h4 class="ist-footer__heading"><?php esc_html_e( 'Contact Us', 'infinity-sky' ); ?></h4>
                    <ul class="ist-footer__contact-list">
                        <li class="ist-footer__contact-item">
                            <svg class="ist-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>Thamel, Kathmandu<br>Nepal 44600</span>
                        </li>
                        <li class="ist-footer__contact-item">
                            <svg class="ist-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                            <a href="https://wa.me/9779810597893" target="_blank" rel="noopener noreferrer">+977 9810597893 (WhatsApp)</a>
                        </li>
                        <li class="ist-footer__contact-item">
                            <svg class="ist-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:infinityskytravels8@gmail.com">infinityskytravels8@gmail.com</a>
                        </li>
                        <li class="ist-footer__contact-item">
                            <svg class="ist-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>Mon–Sat: 9:00 AM – 6:00 PM<br><small style="color:var(--ist-text-light)">Nepal Time (UTC+5:45)</small></span>
                        </li>
                    </ul>

                    <div class="ist-footer__emergency">
                        <span class="ist-footer__emergency-label">24/7 Emergency Line</span>
                        <a href="https://wa.me/9779810597893" class="ist-footer__emergency-link" target="_blank" rel="noopener noreferrer">
                            WhatsApp Now →
                        </a>
                    </div>
                </div>

            </div><!-- /.ist-footer__grid -->
        </div>
    </div><!-- /.ist-footer__main -->

    <!-- Bottom bar -->
    <div class="ist-footer__bottom">
        <div class="ist-container">
            <div class="ist-footer__bottom-inner">
                <p class="ist-footer__copyright">
                    &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'infinity-sky' ); ?>
                    <?php esc_html_e( 'TAAN Licensed Travel Agency — Thamel, Kathmandu, Nepal.', 'infinity-sky' ); ?>
                </p>
                <ul class="ist-footer__legal">
                    <li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/terms' ) ); ?>">Terms &amp; Conditions</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/cancellation-policy' ) ); ?>">Cancellation Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════════════════════════════
     FLOATING ELEMENTS
═══════════════════════════════════════════════════════════ -->
<?php get_template_part( 'template-parts/global/whatsapp-float' ); ?>
<?php get_template_part( 'template-parts/global/scroll-top' ); ?>

<?php wp_footer(); ?>
</body>
</html>
