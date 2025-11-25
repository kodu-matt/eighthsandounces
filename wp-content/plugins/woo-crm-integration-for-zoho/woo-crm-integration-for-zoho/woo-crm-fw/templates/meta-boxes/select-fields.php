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

<div id="wciz-fields-form-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Map Fields', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div id="wciz-fields-form-section" class="wciz-feeds__meta-box-main-wrapper" mapping_data="<?php echo esc_attr( htmlspecialchars( wp_json_encode( $mapping_data ), ENT_QUOTES, 'UTF-8' ) ); ?> " crm_object="<?php echo esc_attr( $selected_object ); ?>">
	</div>
</div>
<?php

