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

<div id="wciz-product-image-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Product Image', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Add Product Image to Product', 'woo-crm-integration-for-zoho' ); ?></label>
				<input type="checkbox" class="add_product_image" name="add_product_image" value="yes" <?php checked( $add_product_image, 'yes' ); ?> >
				<p class="wciz-description">
					<?php
						esc_html_e( 'Enabling this option may be slow down your bulk syncing process, keep it only when you need to sync new product images.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
			</div>
		</div>
	</div>
</div>
