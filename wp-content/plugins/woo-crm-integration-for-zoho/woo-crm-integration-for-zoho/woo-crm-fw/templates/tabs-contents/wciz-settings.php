<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin/partials
 */

$order_statuses      = Woo_Crm_Integration_For_Zoho_Admin::get_woo_order_statuses();
$zoho_statuses       = Woo_Crm_Integration_For_Zoho_Admin::get_zoho_order_statuses( false );
$status_mapping      = get_option( 'wciz_zoho_status_mapping', array() );
$update_order_status = get_option( 'wciz_zoho_update_order_status', 'no' );
$mapping_table_class = ( 'yes' == $update_order_status ) ? '' : 'hide';

?>
<div id="wciz-settings" class="wciz-tabcontent">
	<section class="wciz-iwsa_support-head">
		<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'General Settings', 'woo-crm-integration-for-zoho' ); ?>
		</h3>
		<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Configure basic plugin preferences', 'woo-crm-integration-for-zoho' ); ?>
		</p>
	</section>
	<div class="wciz-content-wrap wciz-settings">
		<?php
			$settings = Woo_Crm_Integration_For_Zoho_Admin::get_general_settings();
		?>
		<form action="#" method="post" id="wciz-zoho-settings">
			<?php woocommerce_admin_fields( $settings ); ?>
			<div class="wciz-zoho-status-mapping-wrapper <?php echo esc_attr( $mapping_table_class ); ?>">
				<h3><?php esc_html_e( 'Map Zoho Order Status to WooCommerce Order Status', 'woo-crm-integration-for-zoho' ); ?></h3>
				<table class="form-table wciz-zoho-status-mapping-table">
					<thead>
						<tr class="woo-status-row">
							<th>
								<?php esc_html_e( 'Zoho Order Status', 'woo-crm-integration-for-zoho' ); ?>
								<span class="dashicons dashicons-update wciz-refresh-order-status"></span>
							</th>
							<th><?php esc_html_e( 'Woo Order Status', 'woo-crm-integration-for-zoho' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ( $zoho_statuses as $z_key => $z_value ) : ?>
						<tr> 
						<th scope="row" class="titledesc">
							<?php echo esc_html( $z_value['display_value'] ); ?>
						</th>
						<td>
							<select class="wciz-zoho-select" name="wciz_zoho_zoho_status[]" zoho_status="<?php echo esc_attr( $z_value['actual_value'] ); ?>">
								<?php

								$select_status = ! empty( $status_mapping[ $z_value['actual_value'] ] ) ? $status_mapping[ $z_value['actual_value'] ] : '';

								foreach ( $order_statuses as $o_key => $o_value ) : 
									?>
									<option value="<?php echo esc_attr( $o_key ); ?>" <?php esc_attr( selected( $select_status, $o_key ) ); ?>>
										<?php echo esc_attr( $o_value ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>	
			</div>
		</form>
		<div class="wciz-intro__button">
				<a href="" class="wciz-btn wciz-btn--filled" id="wciz-zoho-setting-button" >
					<?php esc_html_e( 'Save', 'woo-crm-integration-for-zoho' ); ?>
				</a>
		</div>
	</div>
</div>
