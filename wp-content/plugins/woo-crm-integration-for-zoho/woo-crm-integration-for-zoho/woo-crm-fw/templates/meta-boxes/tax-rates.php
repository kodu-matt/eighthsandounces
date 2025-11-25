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
$tax_classes = wc_get_product_tax_class_options();
?>

<div id="wciz-tax-rate-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Tax Setup', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Make Products Taxable', 'woo-crm-integration-for-zoho' ); ?></label>
				<input type="checkbox" class="taxable_product" name="taxable_product" value="yes"  <?php checked( $taxable_product , 'yes' ); ?> >
			</div>
			<div class="wciz-form-wrapper">
				<label for="tax_rates"><?php esc_html_e( 'Select Tax Rates', 'woo-crm-integration-for-zoho' ); ?></label>
				<table id="woo-status-table" class="wciz-form-table">
						<tr class="woo-status-row">
							<td><strong><?php esc_html_e( 'Woo tax rates', 'woo-crm-integration-for-zoho' ); ?></strong></td>
							<td><strong><?php esc_html_e( 'Zoho Tax Rates', 'woo-crm-integration-for-zoho' ); ?></strong></td>
						</tr>
				<?php
				foreach ( $tax_classes as $tax_key => $tax_value ) :

					$selected_rates = isset( $tax_rate_mapping[ $tax_key ] ) ? $tax_rate_mapping[ $tax_key ] : '';
					?>
					<tr class="woo-status-row">
						<td>
							<label for="woo_tax_rate"><?php echo esc_html( $tax_value ); ?></label>
							<input type="hidden" name="woo_tax_rate[]" value="<?php echo esc_html( $tax_key ); ?>">
						</td>
						<td>
							<select name="zoho_tax_rate[]" class="wc-enhanced-select tax-rate-select" 
							selected_rates="<?php echo esc_attr( $selected_rates ); ?>">
							</select>
						</td>
					</tr>
					<?php
				endforeach;
				?>
				</table>
				<p class="wciz-description">
					<?php
						esc_html_e( 'Please map woocommerce tax rates to zoho tax rates which will applied on product while purchase.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
				<p class="wciz-description">
					<?php
						esc_html_e( 'You can create new tax rates in your zoho crm and select them here. If you are not able to see the added tax rate please click on refresh fields button above.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
			</div>
		</div>
	</div>
</div> 
