<?php
namespace WProfile\Repos;

class History_Cat extends Repository {

	protected $taxonomy = 'wprofile_history_cat';
	protected $object_type = [ 'wprofile_history' ];

	public function register() {
		$labels = $this->taxonomy_labels();
		$args = [
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => 'users.php',
			'show_admin_column' => true
		];
		register_taxonomy( $this->taxonomy, $this->object_type, $args );
	}

	protected function taxonomy_labels() {
		return [
			'name'          => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
			'singular_name' => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
		];
	}

}
