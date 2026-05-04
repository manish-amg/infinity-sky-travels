<?php
/**
 * Widget area registration.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function ist_register_widgets() {
    register_sidebar( [
        'name'          => __( 'Blog Sidebar', 'infinity-sky' ),
        'id'            => 'ist-blog-sidebar',
        'description'   => __( 'Widgets in the blog sidebar.', 'infinity-sky' ),
        'before_widget' => '<div id="%1$s" class="ist-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="ist-widget__title">',
        'after_title'   => '</h4>',
    ] );

    register_sidebar( [
        'name'          => __( 'Package Sidebar', 'infinity-sky' ),
        'id'            => 'ist-package-sidebar',
        'description'   => __( 'Widgets below the booking box on package pages.', 'infinity-sky' ),
        'before_widget' => '<div id="%1$s" class="ist-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="ist-widget__title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'ist_register_widgets' );
