<?php
/*
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style(
		( $parent = get_template() ),
		trailingslashit( get_template_directory_uri() ) . 'style.css',
		[],
		wp_get_theme( $parent )->get( 'Version' )
	);
	wp_enqueue_style(
		get_stylesheet(),
		get_stylesheet_uri(),
		[ $parent ],
		wp_get_theme()->get( 'Version' )
	);
}, 0 );
*/

add_action( 'wp_enqueue_scripts', function() {
	function twentyfifteen_stylesheet_uri() {
		remove_action( 'stylesheet_uri', __FUNCTION__ );
		return trailingslashit( get_template_directory_uri() ) . 'style.css';
	}
	add_filter( 'stylesheet_uri', 'twentyfifteen_stylesheet_uri' );
} );

add_action( 'wp_enqueue_scripts', function() {
	$theme = wp_get_theme();
	wp_enqueue_style(
		$theme->get_stylesheet(),
		$theme->get_stylesheet_directory_uri() . '/style.css',
		[],
		$theme->get( 'Version' )
	);
}, 11 );
