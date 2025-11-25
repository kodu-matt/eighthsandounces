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

<div id="wciz-shop-order-meta-box-wrap" >
	<div class="select-feed-wrap">
		<select id="wciz-zoho-delete-sync_data-select" style="margin: 10px 0px;" >
			<?php foreach ( $feeds as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>">
					<?php echo esc_html( $value ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<a href="#" class="button" id="wciz-zoho-delete-sync_data-button">
			<?php esc_html_e('Delete meta keys', 'woo-crm-integration-for-zoho' ); ?>
		</a>
	</div>
</div>
<input type="hidden" name="meta_box_nonce" value="<?php echo esc_attr( wp_create_nonce( 'meta_box_nonce' ) ); ?>">
