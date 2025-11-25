<?php
/**
 * Add meta box section view.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
?>

<div id="wciz-order-status-mapping-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide" order_statuses="<?php echo esc_attr( wp_json_encode( $woo_statuses ) ); ?>" status_mapping="<?php echo esc_attr( wp_json_encode( $status_mapping ) ); ?>" >
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Map Woo Order Statuses', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<table id="woo-status-table" class="wciz-form-table">
					<?php foreach ( $woo_statuses as $key => $value ) : ?>
						<tr class="woo-status-row">
							<td>
								<label><?php echo esc_html( $value ); ?></label>
								<input type="hidden" name="woo_status[]" value="<?php echo esc_attr( $key ); ?>" class="woo-status" ></td>
							<td>
								<select <?php echo ( 'Sales_Orders' == $selected_object || 'Deals' == $selected_object ) ? 'required' : ''; ?> class="crm-status-mapping-select" name="crm_picklist_value[]">
								</select>
							</td>
							<td></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	</div>
</div>
