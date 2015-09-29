<?php
/*
Plugin Name: WProfile
Author: Toshimichi Mimoto
*/

define( 'WPROFILE_FILE', __FILE__ );
define( 'WPROFILE_BASENAME', plugin_basename( __FILE__ ) );

if ( _wprofile_plugin_requirements() ) {
	//*
	add_action( 'plugins_loaded', '_wprofile_init' );
	function _wprofile_init() {
		if ( class_exists( 'mimosafa\\ClassLoader' ) ) {
			mimosafa\ClassLoader::register( 'WProfile', __DIR__ . '/inc', [ 'hyphenate_classname' => true ] );
			WProfile\Bootstrap::init();
		}
	}
	//*/
	# require_once __DIR__ . '/test.php';
}

/**
 * Plugin's requirements check
 *
 * @param  string $file   Plugin's file path
 * @param  string $phpReq Required PHP Ver.
 * @param  string $wpReq  Required WordPress Ver.
 * @return boolean
 */
function _wprofile_plugin_requirements() {
	$e = new WP_Error();
	// Required
	$phpReq = '5.4';
	$wpReq  = '4.0';
	// Current
	$phpEnv = PHP_VERSION;
	$wpEnv  = $GLOBALS['wp_version'];
	// Check PHP Ver.
	if ( version_compare( $phpEnv, $phpReq, '<' ) ) {
		$e->add(
			'error',
			sprintf(
				__( '<p>PHP version %1$s does not meet the requirements to activate <code>%2$s</code>. %3$s or higher will be required.</p>' ),
				esc_html( $phpEnv ), WPROFILE_BASENAME, esc_html( $phpReq )
			)
		);
	}
	// Check WordPress Ver.
	if ( version_compare( $wpEnv, $wpReq, '<' ) ) {
		$e->add(
			'error',
			sprintf(
				__( '<p>WordPress version %1$s does not meet the requirements to activate <code>%2$s</code>. %3$s or higher will be required.</p>' ),
				esc_html( $wpEnv ), WPROFILE_BASENAME, esc_html( $wpReq )
			)
		);
	}
	if ( $e->get_error_code() ) {
		global $_wprofile_version_error_messages;
		$_wprofile_version_error_messages = $e->get_error_messages();
		add_action( 'admin_notices', '_wprofile_plugin_requirements_error' );
		return false;
	}
	return true;
}

function _wprofile_plugin_requirements_error() {
	global $_wprofile_version_error_messages;
	foreach ( $_wprofile_version_error_messages as $msg ) {
		echo "<div class=\"message error notice is-dismissible\">\n\t{$msg}\n</div>\n";
	}
	deactivate_plugins( WPROFILE_BASENAME, true );
	unset( $_wprofile_version_error_messages );
}
