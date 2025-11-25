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

<div class="wciz-feeds__content  wciz-content-wrap wciz-feed__select-object">
	<a class="wciz-feeds__header-link active">
		<?php esc_html_e( 'Select Object', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<select name="crm_object" id="wciz-feeds-zoho-object" class="wciz-form__dropdown">
					<option value="-1"><?php esc_html_e( '--Select Object--', 'woo-crm-integration-for-zoho' ); ?></option>
					<?php
					foreach ( $objects as $key => $object ) :
						if ( ! $object['editable'] ) {
							continue;
						}
						?>
						<option value="<?php echo esc_attr( $object['api_name'] ); ?>" <?php echo esc_attr( selected( $selected_object, $object['api_name'] ) ); ?> >
							<?php echo esc_html( $object['module_name'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="wciz-form-wrapper">
				<a id="wciz-woo-zoho-refresh-object" class="wciz-btn wciz-btn--filled refresh-object"><?php esc_html_e( 'Refresh Objects', 'woo-crm-integration-for-zoho' ); ?></a>
				<a id="wciz-woo-zoho-refresh-fields" class="wciz-btn wciz-btn--filled refresh-fields"><?php esc_html_e( 'Refresh Fields', 'woo-crm-integration-for-zoho' ); ?></a>
			</div>
		</div>
	</div>
</div>
