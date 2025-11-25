<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    zoho-woocommerce-marketing-automation
 * @subpackage zoho-woocommerce-marketing-automation/admin/templates
 */

?>
<div id="wciz-cart-settings" class="wciz-tabcontent wciz-content-wrap">
	<section class="wciz-iwsa_support-head">
		<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'Abandoned Cart Settings', 'woo-crm-integration-for-zoho' ); ?>
		</h3>
		<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Configure settings to track and recover abandoned carts.', 'woo-crm-integration-for-zoho' ); ?>
		</p>
	</section>
	<div class="wciz-content-wrap">
		<?php
			$settings = Woo_Crm_Integration_For_Zoho_Admin::get_abandoned_cart_settings();
		?>
		<form action="#" method="post" id="wciz-zoho-settings">
			<?php woocommerce_admin_fields( $settings ); ?>
		</form>
		<div class="wciz-intro__button">
				<a href="" class="wciz-btn wciz-btn--filled" id="wciz-zoho-abandoned-cart-setting-button" >
					<?php esc_html_e( 'Save', 'woo-crm-integration-for-zoho' ); ?>
				</a>
		</div>
	</div>
</div>