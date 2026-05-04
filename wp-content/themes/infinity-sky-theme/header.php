<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ═══════════════════════════════════════════════════════════
     MAIN NAVIGATION
═══════════════════════════════════════════════════════════ -->
<header class="ist-header" id="ist-header" role="banner">
    <nav class="ist-nav" id="ist-nav" role="navigation" aria-label="Primary Navigation">
        <div class="ist-nav__container">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ist-nav__logo" aria-label="Infinity Sky Travels — Home">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <div class="ist-nav__logo-text">
                        <span class="ist-nav__logo-icon">✈</span>
                        <div class="ist-nav__logo-words">
                            <span class="ist-nav__logo-main">Infinity Sky</span>
                            <span class="ist-nav__logo-sub">Travels</span>
                        </div>
                    </div>
                <?php endif; ?>
            </a>

            <!-- Primary Menu -->
            <div class="ist-nav__menu-wrapper" id="ist-nav__menu-wrapper" role="menubar">

                <!-- Flights mega menu -->
                <div class="ist-nav__item ist-nav__item--has-mega" role="none">
                    <a href="<?php echo esc_url( home_url( '/flights' ) ); ?>" class="ist-nav__link" role="menuitem" aria-haspopup="true" aria-expanded="false">
                        Flights
                        <svg class="ist-nav__arrow" width="12" height="8" viewBox="0 0 12 8" aria-hidden="true"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </a>
                    <div class="ist-mega-menu" role="menu" aria-label="Flights submenu">
                        <div class="ist-mega-menu__container">
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">Popular Routes</h4>
                                <ul class="ist-mega-menu__list">
                                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=LUA' ) ); ?>" role="menuitem">
                                        <span class="route-tag">KTM → LUA</span> Kathmandu–Lukla
                                    </a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=PKR' ) ); ?>" role="menuitem">
                                        <span class="route-tag">KTM → PKR</span> Kathmandu–Pokhara
                                    </a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=MEY' ) ); ?>" role="menuitem">
                                        <span class="route-tag">KTM → MEY</span> Kathmandu–Chitwan
                                    </a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=BIR' ) ); ?>" role="menuitem">
                                        <span class="route-tag">KTM → BIR</span> Kathmandu–Biratnagar
                                    </a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=KEP' ) ); ?>" role="menuitem">
                                        <span class="route-tag">KTM → KEP</span> Kathmandu–Nepalgunj
                                    </a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=JKR' ) ); ?>" role="menuitem">
                                        <span class="route-tag">KTM → JKR</span> Kathmandu–Janakpur
                                    </a></li>
                                </ul>
                            </div>
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">Search Flights</h4>
                                <p class="ist-mega-menu__desc">Real-time domestic flight search across all Nepal airlines. Best rates guaranteed.</p>
                                <a href="<?php echo esc_url( home_url( '/flights' ) ); ?>" class="btn-primary btn-sm" role="menuitem">
                                    Search All Flights →
                                </a>
                            </div>
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">All Airlines</h4>
                                <ul class="ist-mega-menu__airlines">
                                    <li>Buddha Air (U4)</li>
                                    <li>Yeti Airlines (YT)</li>
                                    <li>Tara Air (TA)</li>
                                    <li>Summit Air (S7)</li>
                                    <li>Shree Airlines (SHA)</li>
                                    <li>Nepal Airlines (RA)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Packages mega menu -->
                <div class="ist-nav__item ist-nav__item--has-mega" role="none">
                    <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="ist-nav__link" role="menuitem" aria-haspopup="true" aria-expanded="false">
                        Packages
                        <svg class="ist-nav__arrow" width="12" height="8" viewBox="0 0 12 8" aria-hidden="true"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </a>
                    <div class="ist-mega-menu" role="menu" aria-label="Packages submenu">
                        <div class="ist-mega-menu__container">
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">By Region</h4>
                                <ul class="ist-mega-menu__list">
                                    <li><a href="<?php echo esc_url( home_url( '/packages?region=everest' ) ); ?>" role="menuitem">Everest Region</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?region=annapurna' ) ); ?>" role="menuitem">Annapurna Region</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?region=langtang' ) ); ?>" role="menuitem">Langtang Region</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?region=mustang' ) ); ?>" role="menuitem">Mustang</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?region=western' ) ); ?>" role="menuitem">Western Nepal</a></li>
                                </ul>
                            </div>
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">By Duration</h4>
                                <ul class="ist-mega-menu__list">
                                    <li><a href="<?php echo esc_url( home_url( '/packages?duration=under-7' ) ); ?>" role="menuitem">Under 7 Days</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?duration=7-10' ) ); ?>" role="menuitem">7–10 Days</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?duration=11-14' ) ); ?>" role="menuitem">11–14 Days</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?duration=15-plus' ) ); ?>" role="menuitem">15+ Days</a></li>
                                </ul>
                            </div>
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">By Traveller</h4>
                                <ul class="ist-mega-menu__list">
                                    <li><a href="<?php echo esc_url( home_url( '/packages?type=backpacker' ) ); ?>" role="menuitem">🎒 Backpacker</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?type=mid-range' ) ); ?>" role="menuitem">🧭 Explorer</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/packages?type=luxury' ) ); ?>" role="menuitem">⭐ Luxury Trekker</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plan My Trip -->
                <div class="ist-nav__item" role="none">
                    <a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="ist-nav__link" role="menuitem">Plan My Trip</a>
                </div>

                <!-- Blog mega menu -->
                <div class="ist-nav__item ist-nav__item--has-mega" role="none">
                    <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="ist-nav__link" role="menuitem" aria-haspopup="true" aria-expanded="false">
                        Blog
                        <svg class="ist-nav__arrow" width="12" height="8" viewBox="0 0 12 8" aria-hidden="true"><path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                    </a>
                    <div class="ist-mega-menu ist-mega-menu--narrow" role="menu" aria-label="Blog submenu">
                        <div class="ist-mega-menu__container">
                            <div class="ist-mega-menu__col">
                                <h4 class="ist-mega-menu__heading">Categories</h4>
                                <ul class="ist-mega-menu__list">
                                    <?php
                                    $cats = get_categories( [ 'orderby' => 'count', 'order' => 'DESC', 'number' => 8 ] );
                                    foreach ( $cats as $cat ) :
                                    ?>
                                    <li><a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" role="menuitem"><?php echo esc_html( $cat->name ); ?></a></li>
                                    <?php endforeach; ?>
                                    <?php if ( empty( $cats ) ) : ?>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Trekking Tips</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Flight Guide</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Nepal Travel Guide</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Permits &amp; Visas</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Seasonal Guides</a></li>
                                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Gear &amp; Packing</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About -->
                <div class="ist-nav__item" role="none">
                    <a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="ist-nav__link" role="menuitem">About</a>
                </div>

                <!-- Contact -->
                <div class="ist-nav__item" role="none">
                    <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="ist-nav__link" role="menuitem">Contact</a>
                </div>
            </div><!-- /.ist-nav__menu-wrapper -->

            <!-- Right: WhatsApp icon + CTA -->
            <div class="ist-nav__actions">
                <a href="https://wa.me/9779810597893" class="ist-nav__whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Contact us on WhatsApp">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    <span class="ist-nav__whatsapp-label">WhatsApp</span>
                </a>
                <a href="<?php echo esc_url( home_url( '/packages' ) ); ?>" class="btn-primary btn-sm ist-nav__cta">
                    Book Now
                </a>

                <!-- Hamburger -->
                <button class="ist-nav__hamburger" id="ist-hamburger" aria-label="Toggle mobile menu" aria-expanded="false" aria-controls="ist-mobile-nav">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div><!-- /.ist-nav__container -->
    </nav>
</header>

<!-- ═══════════════════════════════════════════════════════════
     MOBILE NAVIGATION OVERLAY
═══════════════════════════════════════════════════════════ -->
<div class="ist-mobile-nav" id="ist-mobile-nav" role="dialog" aria-modal="true" aria-label="Mobile navigation" hidden>
    <div class="ist-mobile-nav__inner">
        <button class="ist-mobile-nav__close" id="ist-mobile-close" aria-label="Close menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>

        <ul class="ist-mobile-nav__list">
            <li class="ist-mobile-nav__item ist-mobile-nav__item--has-sub">
                <button class="ist-mobile-nav__link ist-mobile-nav__toggle" aria-expanded="false">
                    Flights <span class="ist-mobile-nav__chevron" aria-hidden="true">+</span>
                </button>
                <ul class="ist-mobile-nav__sub">
                    <li><a href="<?php echo esc_url( home_url( '/flights' ) ); ?>">Search All Flights</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=LUA' ) ); ?>">KTM → Lukla</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=PKR' ) ); ?>">KTM → Pokhara</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/flights?from=KTM&to=MEY' ) ); ?>">KTM → Chitwan</a></li>
                </ul>
            </li>
            <li class="ist-mobile-nav__item ist-mobile-nav__item--has-sub">
                <button class="ist-mobile-nav__link ist-mobile-nav__toggle" aria-expanded="false">
                    Packages <span class="ist-mobile-nav__chevron" aria-hidden="true">+</span>
                </button>
                <ul class="ist-mobile-nav__sub">
                    <li><a href="<?php echo esc_url( home_url( '/packages' ) ); ?>">All Packages</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/packages?region=everest' ) ); ?>">Everest Region</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/packages?region=annapurna' ) ); ?>">Annapurna Region</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/packages?type=luxury' ) ); ?>">Luxury Treks</a></li>
                </ul>
            </li>
            <li class="ist-mobile-nav__item"><a href="<?php echo esc_url( home_url( '/plan-my-trip' ) ); ?>" class="ist-mobile-nav__link">Plan My Trip</a></li>
            <li class="ist-mobile-nav__item ist-mobile-nav__item--has-sub">
                <button class="ist-mobile-nav__link ist-mobile-nav__toggle" aria-expanded="false">
                    Blog <span class="ist-mobile-nav__chevron" aria-hidden="true">+</span>
                </button>
                <ul class="ist-mobile-nav__sub">
                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">All Articles</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Trekking Tips</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Flight Guide</a></li>
                </ul>
            </li>
            <li class="ist-mobile-nav__item"><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="ist-mobile-nav__link">About</a></li>
            <li class="ist-mobile-nav__item"><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="ist-mobile-nav__link">Contact</a></li>
        </ul>

        <div class="ist-mobile-nav__footer">
            <a href="https://wa.me/9779810597893" class="btn-primary" target="_blank" rel="noopener noreferrer">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                WhatsApp Us
            </a>
            <a href="tel:+9779810597893" class="btn-outline">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                Call Us
            </a>
        </div>
    </div>
</div>
<div class="ist-mobile-nav__overlay" id="ist-mobile-overlay" hidden></div>

<!-- ═══════════════════════════════════════════════════════════
     PAGE CONTENT STARTS HERE
═══════════════════════════════════════════════════════════ -->
