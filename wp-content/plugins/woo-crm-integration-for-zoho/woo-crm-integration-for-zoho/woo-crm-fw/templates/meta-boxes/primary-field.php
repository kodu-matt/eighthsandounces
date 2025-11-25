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

<div id="wciz-primary-field-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Primary Field', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label for="primary_field"><?php esc_html_e( 'Select Primary Field', 'woo-crm-integration-for-zoho' ); ?></label>
				<select id="primary-field-select" name="primary_field" primary_field="<?php echo esc_attr( $primary_field ); ?>" ></select>
				<p class="wciz-description">
					<?php
						esc_html_e( 'Please select a field which should be used as "primary key" to update an existing record.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
				<p class="wciz-description">
					<?php
						esc_html_e( 'Make sure "Do not allow duplicate values" is checked in property settings, in order to prevent duplicate record creation.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
			</div>
		</div>
	</div>
</div>
