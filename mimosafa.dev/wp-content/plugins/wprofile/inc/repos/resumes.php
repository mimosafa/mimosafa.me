<?php
namespace WProfile\Repos;

class Resumes extends PostType {

	protected $post_type = 'wprofile_resume';

	protected function post_type_labels() {
		return [
			'name'               => _x( 'Resumes', 'Post Type General Name', 'wprofile' ),
			'singular_name'      => _x( 'Resume', 'Post Type Singular Name', 'wprofile' ),
			'menu_name'          => __( 'Resumes', 'wprofile' ),
			'name_admin_bar'     => __( 'Resumes', 'wprofile' ),
			'all_items'          => __( 'All Resumes', 'wprofile' ),
			'add_new_item'       => __( 'Add New Resume', 'wprofile' ),
			'add_new'            => __( 'Add New', 'wprofile' ),
			'new_item'           => __( 'New Resume', 'wprofile' ),
			'edit_item'          => __( 'Edit Resume', 'wprofile' ),
			'update_item'        => __( 'Update Resume', 'wprofile' ),
			'view_item'          => __( 'View Resume', 'wprofile' ),
			'search_items'       => __( 'Search Your Resume', 'wprofile' ),
			'not_found'          => __( 'Not found', 'wprofile' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'wprofile' ),
		];
	}

	public function post_type_args( Array $args ) {
		$args['menu_position'] = 70;
		return $args;
	}

	public function register_meta_box( \WP_Post $post ) {
		add_meta_box( 'test', 'TEST', function( $post ) { var_dump( $post ); }, null, 'side', 'default', [] );
	}

	public function save_post( $post_id ) {
		//
	}

}
