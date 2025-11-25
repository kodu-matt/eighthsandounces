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

<div id="wciz-variable-product-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Sync Variable Product', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Enable this option to sync the parent product for variations.', 'woo-crm-integration-for-zoho' ); ?></label>
				<input type="checkbox" class="sync_parent_product" name="sync_parent_product" value="yes" <?php checked( $sync_parent_product, 'yes' ); ?> >
			</div>
		</div>
	</div>
</div>
