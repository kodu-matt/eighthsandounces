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

$forms = isset( $cf7_forms ) ? $cf7_forms : array();
?>

<div id="wciz-primary-field-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Crm Form', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label for="primary_field"><?php esc_html_e( 'Select CF7 From', 'woo-crm-integration-for-zoho' ); ?></label>
				<select name="crm_form" id="wciz-crm-integration-for-zoho-cf7-select-form" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( 'Select Form', 'woo-crm-integration-for-zoho' ); ?></option>
					<optgroup label="<?php esc_html_e( 'Contact Form 7', 'woo-crm-integration-for-zoho' ); ?>" ></optgroup>
					<?php if ( ! empty( $forms ) && is_array( $forms ) ) : ?>
						<?php foreach ( $forms as $key => $value ) : ?>
							<option value="<?php echo esc_html( $value->ID ); ?>" ><?php echo esc_html( $value->post_title ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>

				<p class="wciz-description">
					<?php
						esc_html_e( 'Please select a field which should be used as "primary key" to update an existing record.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
			</div>
		</div>
	</div>
</div>
