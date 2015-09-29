<?php
namespace WProfile\Repos;

class History extends PostType {

	protected $post_type = 'wprofile_history';

	protected function post_type_labels() {
		return [
			'name'               => _x( 'Your History', 'Post Type General Name', 'wprofile' ),
			'singular_name'      => _x( 'History', 'Post Type Singular Name', 'wprofile' ),
			'menu_name'          => __( 'Your History', 'wprofile' ),
			'name_admin_bar'     => __( 'Your History', 'wprofile' ),
			'all_items'          => __( 'Your History', 'wprofile' ),
			'add_new_item'       => __( 'Add More History', 'wprofile' ),
			'add_new'            => __( 'Add More', 'wprofile' ),
			'new_item'           => __( 'More History', 'wprofile' ),
			'edit_item'          => __( 'Edit History', 'wprofile' ),
			'update_item'        => __( 'Update History', 'wprofile' ),
			'view_item'          => __( 'View History', 'wprofile' ),
			'search_items'       => __( 'Search Your History', 'wprofile' ),
			'not_found'          => __( 'Not found', 'wprofile' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'wprofile' ),
		];
	}

	public function post_type_args( Array $args ) {
		$args['show_in_menu'] = 'users.php';
		$args['supports'] = [ 'title', 'editor', 'custom-fields' ];
		return $args;
	}

	public function register_meta_box( \WP_Post $post ) {
		add_meta_box( 'wprofile-history-date', __( 'Date', 'wprofile' ), [ $this, 'date_meta_box' ], null, 'side', 'core', [] );
	}

	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
		$def = [
			'_wprofile_history_date_nonce' => \FILTER_DEFAULT,
			'wprofile_history_date_period' => \FILTER_DEFAULT,
			'wprofile_history_date' => \FILTER_DEFAULT
		];
		if ( $args = filter_input_array( \INPUT_POST, $def ) ) :
			extract( $args );
			if ( isset( $_wprofile_history_date_nonce ) && wp_verify_nonce( $_wprofile_history_date_nonce, 'wprofile-history-date' ) ) {
				if ( isset( $wprofile_history_date_period ) && in_array( $wprofile_history_date_period, [ 'month', 'date' ], true ) ) {
					update_post_meta( $post_id, 'wprofile_history_date_period', $wprofile_history_date_period );
				}
				if ( isset( $wprofile_history_date ) ) {
					$ymd = explode( '-', $wprofile_history_date );
					if ( in_array( count( $ymd ), [ 2, 3 ], true ) ) {
						$y = (int) $ymd[0];
						$m = (int) $ymd[1];
						$d = isset( $ymd[2] ) ? (int) $ymd[2] : 1;
						if ( checkdate( $m, $d, $y ) ) {
							if ( count( $ymd ) === 2 ) {
								$wprofile_history_date .= '-' . sprintf( '%02d', date( 't', $wprofile_history_date . '-01' ) );
							}
							update_post_meta( $post_id, 'wprofile_history_date', $wprofile_history_date );
						}
					}
				}
			}
		endif;
	}

	public function date_meta_box( $post ) {
		$date = (string) get_post_meta( $post->ID, 'wprofile_history_date', true );
		$period = get_post_meta( $post->ID, 'wprofile_history_date_period', true ) ?: 'month';
		$month_val = $date ? date( 'Y-m', strtotime( $date ) ) : date( 'Y-m' );
		$date_val  = $date ? date( 'Y-m-d', strtotime( $date ) ) : date( 'Y-m-d' );
		$month_lbl = __( 'Month', 'wprofile' );
		$date_lbl  = __( 'Day', 'wprofile' );
		$date_attr  = $period === 'date' ? 'type="date" name="wprofile_history_date"' : 'type="hidden"';
		$month_attr = $period === 'month' ? 'type="month" name="wprofile_history_date"' : 'type="hidden"';
		wp_nonce_field( 'wprofile-history-date', '_wprofile_history_date_nonce' );
?>
<div id="wprofile-history-date-period">
	<label>
		<input type="radio" name="wprofile_history_date_period" value="month"<?php checked( $period, 'month' ); ?>>
		<?= esc_html( $month_lbl ) ?>
	</label>
	<label>
		<input type="radio" name="wprofile_history_date_period" value="date"<?php checked( $period, 'date' ); ?>>
		<?= esc_html( $date_lbl ) ?>
	</label>
</div>
<hr>
<div id="wprofile-history-date-input">
	<input id="wprofile-history-date-input-date" step="1" value="<?= esc_attr( $date_val ) ?>" <?= $date_attr ?>>
	<input id="wprofile-history-date-input-month" step="1" value="<?= esc_attr( $month_val ) ?>" <?= $month_attr ?>>
</div>
<script>
	( function( $ ) {
		var radio = $( '#wprofile-history-date-period' ).find( 'input[type="radio"]' );
		radio.on( 'change', function() {
			var prd = $(this).val();
			if ( prd === 'date' ) {
				$( '#wprofile-history-date-input-date' ).attr( 'name', 'wprofile_history_date' ).attr( 'type', 'date' );
				$( '#wprofile-history-date-input-month' ).removeAttr( 'name' ).attr( 'type', 'hidden' );
			} else {
				$( '#wprofile-history-date-input-month' ).attr( 'name', 'wprofile_history_date' ).attr( 'type', 'month' );
				$( '#wprofile-history-date-input-date' ).removeAttr( 'name' ).attr( 'type', 'hidden' );
			}
		} );
	} )(jQuery);
</script>
<?php
	}

	public function manage_columns( $columns ) {
		extract( $columns );
		$history_date = 'Era';
		$history_cat  = 'Category';
		return compact( 'cb', 'history_date', 'title', 'history_cat', 'date' );
	}

	public function sortable_columns( $sortable_columns ) {
		$sortable_columns['history_date'] = [ 'history_date', true ];
		$sortable_columns['date'][1] = false;
		return $sortable_columns;
	}

	public function custom_columns( $column_name, $post_id ) {
		if ( $column_name === 'history_date' ) {
			$date = get_post_meta( $post_id, 'wprofile_history_date', true );
			echo esc_html( $date );
		}
	}

	public function columns_orderby( $query /* $vars */ ) {
		/*
		if ( isset( $vars['orderby'] ) && $vars['orderby'] === 'history_date' ) {
			$vars = array_merge( $vars, [
				'meta_key' => 'wprofile_history_date',
				'orderby'  => 'meta_value'
			] );
		}
		return $vars;
		*/
		if ( ! is_admin() )
			return;
		$order = $query->get( 'order' ) ?: 'asc';
		$orderby = $query->get( 'orderby' );
		if ( ! $orderby || $orderby === 'history_date' ) {
			$query->set( 'meta_key', 'wprofile_history_date' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', $order );
		}
	}

}
