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
		$args['taxonomies'] = [ 'wprofile_history_cat' ];
		return $args;
	}

	public function register_meta_box( \WP_Post $post ) {
		add_meta_box( 'wprofile-history-date', __( 'Date', 'wprofile' ), [ $this, 'date_meta_box' ], null, 'side', 'core', [] );
	}

	public function save_post( $post_id ) {
		if ( ! $_POST )
			return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
		$def = [
			'_wprofile_history_date_nonce' => \FILTER_DEFAULT,
			'wprofile_history_date_period' => \FILTER_DEFAULT,
			'wprofile_history_date' => \FILTER_DEFAULT,
			'wprofile_history_ucday' => [ \FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1, 'max_range' => 31 ] ] ]
		];
		$args = filter_input_array( \INPUT_POST, $def );
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
							if ( isset( $wprofile_history_ucday ) ) {
								if ( checkdate( $m, $wprofile_history_ucday, $y ) ) {
									$d = $wprofile_history_ucday;
								}
							}
							$wprofile_history_date .= '-' . sprintf( '%02d', $d );
						}
						update_post_meta( $post_id, 'wprofile_history_date', $wprofile_history_date );
					}
				}
			}
		}
	}

	public function date_meta_box( $post ) {
		$date = (string) get_post_meta( $post->ID, 'wprofile_history_date', true );
		$period = get_post_meta( $post->ID, 'wprofile_history_date_period', true ) ?: 'month';
		$date_val  = $date ? date( 'Y-m-d', strtotime( $date ) ) : date( 'Y-m-d' );
		$month_val = $date ? date( 'Y-m', strtotime( $date ) ) : date( 'Y-m' );
		$ucday_val = $date ? date( 'j', strtotime( $date ) ) : 1;
		$date_attr  = $period === 'date'  ? 'type="date" name="wprofile_history_date"'  : 'type="hidden"';
		$month_attr = $period === 'month' ? 'type="month" name="wprofile_history_date"' : 'type="hidden"';
		$ucday_attr = $period === 'month' ? '' : ' style="display: none;"';
		wp_nonce_field( 'wprofile-history-date', '_wprofile_history_date_nonce' );
?>
<p><?= __( 'Period', 'wprofile' ) ?></p>
<div id="wprofile-history-date-period">
	<label>
		<input type="radio" name="wprofile_history_date_period" value="month"<?php checked( $period, 'month' ); ?>>
		<?= __( 'Month', 'wprofile' ) ?>
	</label>
	<label>
		<input type="radio" name="wprofile_history_date_period" value="date"<?php checked( $period, 'date' ); ?>>
		<?= __( 'Day', 'wprofile' ) ?>
	</label>
</div>
<p><?= __( 'Date', 'wprofile' ) ?></p>
<div id="wprofile-history-date-input">
	<input id="wprofile-history-date-input-date" step="1" value="<?= esc_attr( $date_val ) ?>" <?= $date_attr ?>>
	<input id="wprofile-history-date-input-month" step="1" value="<?= esc_attr( $month_val ) ?>" <?= $month_attr ?>>
	<div id="wprofile-history-date-input-uncertain-day"<?= $ucday_attr ?>>
		<p><?= __( 'Roughly Date', 'wprofile' ) ?></p>
		<input name="wprofile_history_ucday" type="number" min="1" max="31" step="1" value="<?= esc_attr( $ucday_val ) ?>">
	</div>
</div>
<script>
	( function( $ ) {
		var radio = $( '#wprofile-history-date-period' ).find( 'input[type="radio"]' );
		radio.on( 'change', function() {
			var prd = $(this).val();
			if ( prd === 'date' ) {
				$( '#wprofile-history-date-input-date' ).attr( 'name', 'wprofile_history_date' ).attr( 'type', 'date' );
				$( '#wprofile-history-date-input-month' ).removeAttr( 'name' ).attr( 'type', 'hidden' );
				$( '#wprofile-history-date-input-uncertain-day').hide();
			} else {
				$( '#wprofile-history-date-input-month' ).attr( 'name', 'wprofile_history_date' ).attr( 'type', 'month' );
				$( '#wprofile-history-date-input-date' ).removeAttr( 'name' ).attr( 'type', 'hidden' );
				$( '#wprofile-history-date-input-uncertain-day').show();
			}
		} );
	} )(jQuery);
</script>
<?php
	}

	public function manage_columns( $columns ) {
		return [
			'cb' => $columns['cb'],
			'time_line' => __( 'Time Line', 'wprofile' ),
			'title' => __( 'History', 'wprofile' ),
			'taxonomy-wprofile_history_cat' => $columns['taxonomy-wprofile_history_cat'],
			'date' => __( 'Post Date', 'wprofile' )
		];
	}

	public function sortable_columns( $sortable_columns ) {
		$sortable_columns['time_line'] = [ 'time_line', true ];
		$sortable_columns['date'][1] = false;
		return $sortable_columns;
	}

	public function custom_columns( $column_name, $post_id ) {
		if ( $column_name === 'time_line' ) {
			$prd  = get_post_meta( $post_id, 'wprofile_history_date_period', true );
			$date = get_post_meta( $post_id, 'wprofile_history_date', true );
			$format = $prd === 'month' ? __( 'M. Y', 'wprofile' ) : __( 'M. j, Y', 'wprofile' );
			echo date_i18n( $format, strtotime( $date ) );
		}
	}

	public function columns_orderby( $query /* $vars */ ) {
		/*
		if ( isset( $vars['orderby'] ) && $vars['orderby'] === 'time_line' ) {
			$vars = array_merge( $vars, [
				'meta_key' => 'wprofile_history_date',
				'orderby'  => 'meta_value'
			] );
		}
		return $vars;
		*/
		if ( $query->query_vars['post_type'] === $this->post_type && $query->is_main_query() ) {
			$order = $query->get( 'order' ) ?: 'asc';
			$orderby = $query->get( 'orderby' );
			if ( ! $orderby || $orderby === 'time_line' ) {
				$query->set( 'meta_key', 'wprofile_history_date' );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', $order );
			}
		}
	}

	public function columns_style() {
		echo <<<EOF
<style>
	.column-time_line { width: 10em; }
</style>
EOF;
	}

}
