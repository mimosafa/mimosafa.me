<?php
namespace WProfile;

trait Singleton {

	public static function getInstance() {
		static $instance;
		$class = __CLASS__;
		return $instance ?: $instance = new $class();
	}

	public function __clone() {}
	public function __wakeup() {}

}
