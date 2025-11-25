<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select object section of feeds.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Zoho
 * @subpackage Mwb_Cf7_Integration_With_Zoho/includes/framework/templates/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$objects = isset( $objects ) ? $objects : array();

if ( ! is_array( $objects ) ) {
	echo esc_html( $objects );
	return;
}

?>
<div class="mwb-feeds__content  mwb-content-wrap  mwb-feed__select-object">
	<a class="mwb-feeds__header-link active">
		<?php
		printf(
			/* translators: %s:crm name */
			esc_html__( 'Select %s Object', 'woo-crm-integration-for-zoho' ),
			esc_html( 'ZOHO' )
		);
		?>
	</a>

	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select name="crm_object" id="mwb-feeds-zoho-object" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( '--Select Object--', 'woo-crm-integration-for-zoho' ); ?></option>
					<?php if ( ! empty( $objects ) && is_array( $objects ) ) : ?>
						<?php
						foreach ( $objects as $key => $object ) :
							if ( ! $object['editable'] ) {
								continue;
							}
							?>
							<option value="<?php echo esc_attr( $object['api_name'] ); ?>" <?php echo esc_attr( selected( $selected_object, $object['api_name'] ) ); ?> > <?php echo esc_html( $object['module_name'] ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="mwb-form-wrapper">
				<a id="mwb-zoho-refresh-object" class="button refresh-object">
					<span class="mwb-cf7-refresh-object ">
						<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/refresh.svg' ); ?>">
					</span>
					<?php esc_html_e( 'Refresh Objects', 'woo-crm-integration-for-zoho' ); ?>
				</a>
				<a id="mwb-zoho-refresh-fields" class="button refresh-fields">
					<span class="mwb-cf7-refresh-fields ">
						<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/refresh.svg' ); ?>">
					</span>
					<?php esc_html_e( 'Refresh Fields', 'woo-crm-integration-for-zoho' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
