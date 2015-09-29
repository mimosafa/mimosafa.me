<?php
namespace WProfile\Repos;

class History {
	use \WProfile\Singleton { getInstance as init; }

	protected function __construct() {
		add_action( 'init', [ $this, 'register' ] );
	}

	public function register() {
		$history_args = [
			'label' => 'Your History',
			'show_ui' => true,
			'show_in_menu' => 'users.php'
		];
		register_post_type( 'wprofile_history', $history_args );
	}

}
