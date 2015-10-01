<?php
namespace WProfile\Repos;

class History_Cat extends Repository {

	protected $taxonomy = 'wprofile_history_cat';
	protected $object_type = [ 'wprofile_history' ];

	protected function __construct() {
		parent::__construct();
		add_action( 'admin_menu', [ $this, 'users_submenu' ] );
		add_filter( 'parent_file', [ $this, 'parent_file'] );
	}

	public function register() {
		$labels = $this->taxonomy_labels();
		$args = [
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'show_admin_column' => true,
			//'meta_box_cb' => [ $this, 'meta_box_cb' ]
		];
		register_taxonomy( $this->taxonomy, $this->object_type, $args );
	}

	public function users_submenu() {
		add_users_page( $this->taxonomy, $this->taxonomy_labels()['name'], 'manage_options', 'edit-tags.php?taxonomy=' . $this->taxonomy );
	}
	/**
	 * @see https://github.com/WordPress/WordPress/blob/4.3-branch/wp-admin/menu-header.php#L37
	 */
	public function parent_file( $parent_file ) {
		global $taxnow;
		if ( $taxnow === 'wprofile_history_cat' )
			$parent_file = 'users.php';
		return $parent_file;
	}

	protected function taxonomy_labels() {
		return [
			'name'          => _x( 'Categories', 'Taxonomy General Name', 'text_domain' ),
			'singular_name' => _x( 'Category', 'Taxonomy Singular Name', 'text_domain' ),
		];
	}

	public function meta_box_cb( \WP_Post $post, $box ) {
		//
	}

}
