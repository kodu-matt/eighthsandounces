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

<div id="wciz-product-detail-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Product / Shipping Details', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Add Order line item as product', 'woo-crm-integration-for-zoho' ); ?></label>
				<input type="checkbox" class="add-line-item-cb" name="add_line_item" value="yes" <?php checked( $add_line_item, 'yes' ); ?>> 
			</div>
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Add shipping as line item', 'woo-crm-integration-for-zoho' ); ?></label>
				<input type="checkbox" class="add-shipping-line-item-cb" name="add_shipping_line_item" value="yes" <?php checked( $add_shipping_line_item, 'yes' ); ?>>
			</div>
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Shipping Product', 'woo-crm-integration-for-zoho' ); ?></label>
				<a href="#" id="create-shipping-product" class="wciz-btn wciz-btn--filled <?php echo ( $shipping_product_id ) ? 'hide' : ''; ?>">
					<?php esc_html_e( 'Create Product', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
				<span class="shipping-product-status-msg <?php echo ( $shipping_product_id ) ? '' : 'hide'; ?>">
					<?php
					// translators: Shipping product id.
					echo esc_html( sprintf( __( 'Created ( Id : %s )', 'woo-crm-integration-for-zoho' ), $shipping_product_id ) );
					?>
					<a href="#"  id="create-shipping-product"><?php esc_html_e( 'Update', 'woo-crm-integration-for-zoho' ); ?></a>
				</span>
			</div>
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Shipping Amount', 'woo-crm-integration-for-zoho' ); ?></label>
				<select id="shipping-item-total" class="" name="shipping_line_item_total">
					<option value="order_shipping" <?php selected( $shipping_line_item_total, 'order_shipping' ); ?>>
						<?php esc_html_e( 'Order Shipping Total', 'woo-crm-integration-for-zoho' ); ?>
					</option>
					<option value="order_shipping_plus_tax" <?php selected( $shipping_line_item_total, 'order_shipping_plus_tax' ); ?>>
						<?php esc_html_e( 'Order Shipping Total + Shipping Tax', 'woo-crm-integration-for-zoho' ); ?>
					</option>
				</select>
			</div>
		</div>
	</div>
</div>
