<?php
namespace WProfile;

use mimosafa\WP as WP;

class Bootstrap {
	use Singleton { getInstance as init; }

	private static $options;

	private function __construct() {
		$this->settings_options();
		if ( is_admin() )
			$this->settings_page();
		$this->register_repositories();
	}

	private function settings_options() {
		$opts = WP\Settings\Options::instance( 'wprofile' );
		$opts->add( 'display_frontend', 'boolean' );
		$opts->add( 'profile_slug' );
		self::$options = $opts;
	}

	public function register_repositories() {
		Repos\History::init();
		Repos\History_Cat::init();
		#Repos\Skills::init();
		#Repos\Resumes::init();
	}

	private function settings_page() {
		$page = new WP\Settings\Page( 'wprofile', __( 'WProfile', 'wprofile' ) );
		$page
		->position( 71 )
			->section( 'frontend-setting' )
				->field( 'display-frontend' )
				->option( self::$options->display_frontend, 'checkbox' );
		if ( self::$options->get_display_frontend() ) {
			$page
				->field( 'profile-slug' )
				->option( self::$options->profile_slug, 'text' );
		}
		$page->init( 'edit-tags.php?taxonomy=wprofile_history_cat' );
		$page->done();
	}

}
