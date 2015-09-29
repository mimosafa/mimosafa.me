<?php
namespace WProfile\Repos;

class Skills extends PostType {

	protected $post_type = 'wprofile_skill';

	protected function post_type_labels() {
		return [
			'name'               => _x( 'Your Skills', 'Post Type General Name', 'wprofile' ),
			'singular_name'      => _x( 'Skill', 'Post Type Singular Name', 'wprofile' ),
			'menu_name'          => __( 'Your Skills', 'wprofile' ),
			'name_admin_bar'     => __( 'Your Skills', 'wprofile' ),
			'all_items'          => __( 'Your Skills', 'wprofile' ),
			'add_new_item'       => __( 'Add More Skill', 'wprofile' ),
			'add_new'            => __( 'Add More', 'wprofile' ),
			'new_item'           => __( 'More Skill', 'wprofile' ),
			'edit_item'          => __( 'Edit Skill', 'wprofile' ),
			'update_item'        => __( 'Update Skill', 'wprofile' ),
			'view_item'          => __( 'View Skill', 'wprofile' ),
			'search_items'       => __( 'Search Your Skill', 'wprofile' ),
			'not_found'          => __( 'Not found', 'wprofile' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'wprofile' ),
		];
	}

	public function post_type_args( Array $args ) {
		$args['show_in_menu'] = 'users.php';
		return $args;
	}

	public function register_meta_box( \WP_Post $post ) {
		add_meta_box( 'test', 'TEST', function( $post ) { var_dump( $post ); }, null, 'side', 'default', [] );
	}

	public function save_post( $post_id ) {
		//
	}

}
