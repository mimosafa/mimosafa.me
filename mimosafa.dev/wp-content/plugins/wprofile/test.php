<?php
add_action( 'init', function() {
	add_rewrite_endpoint( 'resume', EP_ROOT );
	wprofile_repositories();
} );
add_filter( 'query_vars', function( $vars ) {
	$vars[] = 'resume';
	return $vars;
} );
add_action( 'template_redirect', function() {
	global $wp_query;
	if ( isset( $wp_query->query['resume'] ) ) {
		include dirname( WPROFILE_FILE ) . '/tmplt/index.php';
		exit;
	}
} );

function wprofile_repositories() {
	//
}
