<?php
namespace WProfile\Repos;

abstract class Repository {

	abstract public function register();

	public static function init() {
		static $instance;
		$class = get_called_class();
		return $instance ?: $instance = new $class();
	}
	public function __clone() {}
	public function __wakeup() {}

	protected function __construct() {
		add_action( 'init', [ $this, 'register' ] );
	}

}
