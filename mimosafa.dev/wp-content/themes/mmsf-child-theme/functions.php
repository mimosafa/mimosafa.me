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

add_action( 'wp_footer', function() {
	if ( ! is_user_logged_in() ) {
		echo <<<EOF
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-26079619-2', 'auto');
  ga('send', 'pageview');

</script>
EOF;
	}
} );
