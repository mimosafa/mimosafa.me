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
		add_action( 'init', [ $this, 'register_repositories' ] );
	}

	private function settings_options() {
		$opts = WP\Settings\Options::instance( 'wprofile' );
		$opts->add( 'display_frontend', 'boolean' );
		$opts->add( 'profile_slug' );
		self::$options = $opts;
	}

	public function register_repositories() {
		Repos\History::init();

		$skills_args = [
			'label' => 'Your Skills',
			'show_ui' => true,
			'show_in_menu' => 'users.php'
		];
		register_post_type( 'wprofile_skill', $skills_args );

		$resume_args = [
			'label' => 'Resumes',
			'show_ui' => true,
			'menu_position' => 70
		];
		register_post_type( 'wprofile_resume', $resume_args );
	}

	private function settings_page() {
		$page = new WP\Settings\Page( 'options-general.php' );
		$page
		->init( 'wprofile', 'WProfile' )
			->section( 'frontend-setting' )
				->field( 'display-frontend' )
				->option( self::$options->display_frontend, 'checkbox' );
		if ( self::$options->get_display_frontend() ) {
			$page
				->field( 'profile-slug' )
				->option( self::$options->profile_slug, 'text' );
		}
		$page->done();
	}

}
