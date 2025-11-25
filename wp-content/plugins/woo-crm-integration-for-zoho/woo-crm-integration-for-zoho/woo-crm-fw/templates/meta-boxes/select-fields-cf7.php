<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select fields section of feeds.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Zoho
 * @subpackage Mwb_Cf7_Integration_With_Zoho/includes/framework/templates/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

?>
<div id="mwb-fields-form-section-wrapper" class="mwb-feeds__content  mwb-content-wrap row-hide">
	<a class="mwb-feeds__header-link">
		<?php esc_html_e( 'Map Fields', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div id="mwb-fields-form-section" class="mwb-feeds__meta-box-main-wrapper">
	<?php
	$mapping_exists = ! empty( $mapping_data );

	foreach ( $crm_fields['fields'] as $key => $fields_data ) {

		if ( isset( $fields_data['field_read_only'] ) && ! $fields_data['field_read_only'] ) {
			$option_data  = $field_options;
			$default_data = array(
				'field_type'  => 'standard_field',
				'field_value' => '',
			);

			if ( $mapping_exists ) {
				if ( ! array_key_exists( $fields_data['api_name'], $mapping_data ) ) {
					continue;
				}
				$default_data = $mapping_data[ $fields_data['api_name'] ];

			} elseif ( isset( $fields_data['system_mandatory'] ) && ! $fields_data['system_mandatory'] ) {
					continue;
			}
			$template_class   = Woo_Crm_Integration_For_Zoho_Admin::get_field_section_html( $option_data, $fields_data, $default_data );
		}
	}
	?>
	</div>
</div>
