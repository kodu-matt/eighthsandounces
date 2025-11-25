<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    zoho-woocommerce-marketing-automation
 * @subpackage zoho-woocommerce-marketing-automation/admin/templates
 */

if ( isset( $_GET['action'] ) && 'wciz_zoho_clear_cart' == $_GET['action'] ) {
	wciz_zoho_clear_cart();
}

if ( ! class_exists( 'WP_List_Table' ) ) {

	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ;
}

class Zoho_Abandoned_Carts extends WP_List_Table {

	public function prepare_items() {

		$perPage = 10;
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$data = $this->table_data();

		$currentPage = $this->get_pagenum();
		$totalItems = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $totalItems,
				'per_page'    => $perPage,
			)
		);
		$data = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage );
		$this->items = $data;
	}

	public function get_columns() {

		$columns = array(
			'cart_email'        => __( 'User Email', 'woo-crm-integration-for-zoho' ),
			'user_type'         => __( 'User Type', 'woo-crm-integration-for-zoho' ),
			'cart_total'        => __( 'Cart Total', 'woo-crm-integration-for-zoho' ),
			'last_updated'      => __( 'Last Updated', 'woo-crm-integration-for-zoho' ),
			'cart_status'       => __( 'Cart Status', 'woo-crm-integration-for-zoho' ),
			'sync_status'       => __( 'Sync Status', 'woo-crm-integration-for-zoho' ),
		);

		return $columns;
	}

	private function table_data() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'options';

		$query = "SELECT * FROM `$table_name` WHERE `option_name` LIKE '%zoho_cart_data_%'";

		$abdn_carts = array();
		$abdn_carts = wciz_woo_zoho_get_query_results( $query );

		$abdn_carts_data = array();

		$user_type_guest = __( 'Guest', 'woo-crm-integration-for-zoho' );
		$user_type_customer = __( 'Customer', 'woo-crm-integration-for-zoho' );

		foreach ( $abdn_carts as $key => $abdn_cart ) {

			$data = $abdn_cart['option_value'];

			$data = unserialize( $data );

			if ( count( $data['cart_items'] ) ) {

				$cart_data = array();

				$cart_data['cart_email'] = $data['cart_email'];

				$cp_name_new = 'No coupon';
				if ( get_option( 'coupon_' . $cart_data['cart_email'], '' ) != '' ) {
					$cp_name_new = get_option( 'coupon_' . $cart_data['cart_email'], '' );

				}

				$cart_data['last_updated'] = $data['last_updated'];

				if ( isset( $data['sync_status'] ) && 'not_synced' != $data['sync_status'] ) {

					$cart_data['sync_status'] = 'synced';
				} else {

					$cart_data['sync_status'] = 'not_synced';
				}

				$product_data = array();

				$cart_total = 0;
				foreach ( $data['cart_items'] as $key => $value ) {

					$cart_pro_id = $value['product_id'];
					if ( isset( $value['variation_id'] ) && ! empty( $value['variation_id'] ) && 0 != $value['variation_id'] ) {
						$cart_pro_id = $value['variation_id'];
					}

					$product = wc_get_product( $cart_pro_id );

					if ( $product ) {

						$product_data['id'] = $cart_pro_id;
						$product_data['price'] = $product->get_price();
						$product_data['qty'] = $value['quantity'];
						$product_data['name'] = $product->get_title();
						$cart_total += $product_data['qty'] * $product_data['price'];
						$cart_data['cart_total']  = $cart_total;
						$cart_data['coupon_applied'] = $cp_name_new;
						$cart_data['cart_items'][] = $product_data;

					}
				}

				$abdn_carts_data[] = $cart_data;

			}
		}

		$temp_data = array();

		foreach ( $abdn_carts_data as $key => $single_abdn_cart ) {

			$new_data = array(
				'cart_email'    => $single_abdn_cart['cart_email'],
				'user_type'     => $user_type_guest,
				'cart_total'    => $single_abdn_cart['cart_total'],

				'coupon_applied' => $single_abdn_cart['coupon_applied'],

				'last_updated'  => $single_abdn_cart['last_updated'],
				'cart_expiry'   => $single_abdn_cart['last_updated'],
				'sync_status'   => $single_abdn_cart['sync_status'],
			);

			$temp_data[] = $new_data;
		}

		// for logged in users

		$args['meta_query'] = array(

			array(
				'key'       => 'zoho_user_left_cart',
				'value'     => 'yes',
				'compare'   => '==',
			),
		);

		$zoho_abundant_users = get_users( $args );

		if ( count( $zoho_abundant_users ) > 0 ) {

			foreach ( $zoho_abundant_users as $key => $single_user ) {

				$cp_name_new = 'No coupon ';

				if ( get_option( 'coupon_' . $single_user->user_email, '' ) != '' ) {
					$cp_name_new = get_option( 'coupon_' . $single_user->user_email, '' );

				}

				if ( ! empty( $single_user->ID ) && ! in_array( 'administrator', $single_user->roles ) ) {

					$customer_cart = get_user_meta( $single_user->ID, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );

					$abdn_sync_status = get_user_meta( $single_user->ID, 'zoho_abdn_sync_status', true );

					if ( ! empty( $abdn_sync_status ) && 'not_synced' != $abdn_sync_status ) {
						$sync_status = 'synced';
					} else {
						$sync_status = 'not_synced';
					}

					$cart_items = array();
					$cart_total = 0;

					if ( isset( $customer_cart['cart'] ) && count( $customer_cart['cart'] ) > 0 ) {

						foreach ( $customer_cart['cart'] as $key => $value ) {

							$cart_pro_id = $value['product_id'];

							if ( isset( $value['variation_id'] ) && ! empty( $value['variation_id'] ) && 0 != $value['variation_id'] ) {
								$cart_pro_id = $value['variation_id'];
							}

							$product = wc_get_product( $cart_pro_id );

							if ( $product ) {

								$product_data['id'] = $cart_pro_id;
								$product_data['price'] = $product->get_price();
								$product_data['qty'] = $value['quantity'];
								$product_data['name'] = $product->get_title();
								$cart_total += $product_data['qty'] * $product_data['price'];
								$cart_items[] = $product_data;

							}
						}

						$last_updated = get_user_meta( $single_user->ID, 'zoho_last_addtocart', time() );

						$new_data = array(
							'cart_email'    => $single_user->user_email,
							'user_type'     => ucwords( $single_user->roles[0] ),
							'cart_total'    => $cart_total,
							'coupon_applied' => $cp_name_new,
							'last_updated'  => $last_updated,
							'cart_expiry'   => $last_updated,
							'sync_status'   => $sync_status,
						);

						$temp_data[] = $new_data;

					}
				}
			}
		}

		$last_updated_arr = array();

		foreach ( $temp_data as $key => $row ) {
			$last_updated_arr[ $key ] = $row['last_updated'];
		}
		array_multisort( $last_updated_arr, SORT_DESC, $temp_data );
		return $temp_data;
	}

	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'cart_id':
			case 'cart_email':
			case 'last_updated':
			case 'cart_status':
			case 'user_type':
			case 'sync_status':
			case 'cart_total':
			case 'coupon_applied':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	public function display_rows_or_placeholder() {

		if ( $this->has_items() ) {

			$this->display_rows();
		} else {

			echo '<tr class="no-items"><td class="colspanchange" colspan="' . esc_attr( $this->get_column_count() ) . '">';

			$this->no_items();

			echo '</td></tr>';
		}
	}

	public function no_items() {

		esc_html_e( 'Currently No Abandoned Cart Available', 'woo-crm-integration-for-zoho' );
	}


	public function column_cart_id( $data ) {

		return 'Abdn Cart #' . $data['cart_id'];
	}

	public function column_last_updated( $data ) {

		return Woo_Crm_Integration_For_Zoho_Admin::zoho_get_time_ago( $data['last_updated'] );
	}

	public function column_cart_expiry( $data ) {

		$zoho_abundant_timer = (int) get_option( 'wciz_zoho_cart_timer', 5 );
		return $zoho_abundant_timer;
	}

	public function column_cart_items( $data ) {

		if ( count( $data['cart_items'] ) > 0 ) {

			return $this->get_cart_items_table( $data['cart_items'] );

		} else {
			return __( 'Currently No Items in Cart', 'woo-crm-integration-for-zoho' );
		}
	}

	public function get_cart_items_table( $items ) {

		$table_html = '<table>';

		foreach ( $items as $key => $item ) {

			$table_html .= '<tr>';
			$table_html .= '<td>' . $item['name'] . '</td>';
			$table_html .= '<td>' . $item['qty'] . '</td>';
			$table_html .= '<td>' . $item['price'] . '</td>';
			$table_html .= '</tr>';
		}

		$table_html .= '</table>';
		return $table_html;
	}

	public function column_cart_status( $data ) {

		$zoho_abundant_timer = (int) get_option( 'wciz_zoho_cart_timer', 5 );

		$cart_expiry_time = $zoho_abundant_timer * 60 + $data['cart_expiry'];
		if ( $cart_expiry_time < time() ) {

			return __( 'Abandoned', 'woo-crm-integration-for-zoho' );
		} else {
			return __( 'Active', 'woo-crm-integration-for-zoho' );
		}
	}

	public function column_sync_status( $data ) {

		$img = ' ';
		if ( 'synced' == $data['sync_status'] ) {

			$img = '<span class="dashicons dashicons-yes-alt"></span>';
		} else {
			$img = '<span class="dashicons dashicons-dismiss"></span>';
		}
		return $img;
	}

	public function column_cart_total( $data ) {

		$html = '<span class="wciz_cart_table_cart_total">' . wc_price( $data['cart_total'] ) . '</span>';
		$html .= '<a href="#" class="wciz_cart_table_view_cart" data-email="' . $data['cart_email'] . '" data-user_type="' . $data['user_type'] . '">' . __( 'view cart', 'woo-crm-integration-for-zoho' ) . '</a>';

		return $html;
	}
}

?>
<div id="wciz-abandoned-carts" class="wciz-tabcontent wciz-content-wrap">
<section class="wciz-iwsa_support-head">
	<h3 class="wciz-iwsa_support-head-title">
		<?php esc_html_e( 'Abandoned Cart Reports', 'woo-crm-integration-for-zoho' ); ?>
	</h3>
	<p class="wciz-iwsa_support-head-desc">
		<?php esc_html_e( 'Summary of abandoned carts and recovery rates', 'woo-crm-integration-for-zoho' ); ?>
	</p>
</section>

<div id="wciz_cart_table_cart_details">
	
</div>
<button id="sync_abandoned_carts" class="wciz-btn">Sync Abandoned carts</button>
<?php 
if ( 'no' == get_option( 'wciz_zoho_abandoned_cart_object_created', 'no' ) || 'no' == get_option( 'wciz_zoho_abandoned_cart_feed_id', 'no' ) ) {
	?>
	<button id="create_abandoned_cart_object" class="wciz-btn wciz-btn--filled"><?php esc_html_e( 'Create Abandoned Cart object in Zoho CRM and sync with plugin', 'woo-crm-integration-for-zoho' ); ?></button>
<?php
}
?>
<?php 
if ( 'no' == get_option( 'wciz_zoho_abandoned_cart_feed_id', 'no' ) && 'yes' == get_option( 'wciz_zoho_abandoned_cart_object_created')  ) {
	?>
	<button id="create_feed_for_abandoned_cart_object" class="wciz-btn wciz-btn--filled"><?php esc_html_e( 'Create a data feed for abandoned cart objects', 'woo-crm-integration-for-zoho' ); ?></button>
<?php
}
?>
<button id="create_feed_for_abandoned_cart_object" class="wciz-btn wciz-btn--filled abundant-feed"><?php esc_html_e( 'Create a data feed for abandoned cart objects', 'woo-crm-integration-for-zoho' ); ?></button>
<div class="zoho_coupons_list">
	<?php
		$zoho_cart_table = new Zoho_Abandoned_Carts();
		$zoho_cart_table->prepare_items();
		$zoho_cart_table->display();
	?>
	<div class="wciz-intro__button">
		<a href="?page=woo-crm-integration-for-zoho&action=wciz_zoho_clear_cart#wciz-abandoned-carts" class="wciz-btn wciz-btn--filled"><?php esc_html_e( 'Empty guest carts', 'woo-crm-integration-for-zoho' ); ?></a>
	</div>
</div>
</div>
<?php
