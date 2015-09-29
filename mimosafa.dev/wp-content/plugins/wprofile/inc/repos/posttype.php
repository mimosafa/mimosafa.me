<?php
namespace WProfile\Repos;

abstract class PostType extends Repository {

	// protected $post_type;

	abstract protected function post_type_labels();
	abstract public function post_type_args( Array $args );
	abstract public function register_meta_box( \WP_Post $post );
	abstract public function save_post( $post_id );

	protected function __construct() {
		parent::__construct();
		add_action( 'save_post', [ $this, 'save_post' ] );
		add_filter( $this->post_type . '_post_type_args', [ $this, 'post_type_args' ] );
		$this->post_type_columns();
	}

	public function register() {
		$labels = $this->post_type_labels();
		$args = [
			'labels' => $labels,
			'show_ui' => true,
			'register_meta_box_cb' => [ $this, 'register_meta_box' ]
		];
		$args = apply_filters( $this->post_type . '_post_type_args', $args );
		register_post_type( $this->post_type, $args );
	}

	protected function post_type_columns() {
		if ( method_exists( $this, 'manage_columns' ) ) {
			add_filter( 'manage_' . $this->post_type . '_posts_columns', [ $this, 'manage_columns' ] );
		}
		if ( method_exists( $this, 'sortable_columns' ) ) {
			add_filter( 'manage_edit-' . $this->post_type . '_sortable_columns', [ $this, 'sortable_columns' ] );
		}
		if ( method_exists( $this, 'custom_columns' ) ) {
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', [ $this, 'custom_columns' ], 10, 2 );
		}
		if ( method_exists( $this, 'columns_orderby' ) ) {
			//add_filter( 'request', [ $this, 'columns_orderby' ] );
			add_action( 'pre_get_posts', [ $this, 'columns_orderby' ] );
		}
	}

}
