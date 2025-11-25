<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the primary field of feeds section.
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

$prefilled_indexes = isset( $condition ) ? count( $condition ) : '';
?>
<div id="mwb-condition-filter-section-wrapper"  class="mwb-feeds__content  mwb-content-wrap">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Condition Filter', 'woo-crm-integration-for-zoho' ); ?>
	</a>

	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper  mwb-form-filter-wrapper">

				<div class="mwb-toggle-wrap">
					<input type="checkbox" name="enable_add_condition" id="mwb-zoho-enable-add-condition" value="yes" <?php checked( 'yes', $enable_filter); ?>>
					<div class="mwb_field_desc">
						<p>
							<?php esc_html_e( 'Enable to add Conditional filters on form submission', 'woo-crm-integration-for-zoho' ); ?>
						</p>
					</div>
				</div>

				<div class="mwb-initial-filter">
					<?php if ( ! empty( $condition ) && is_array( $condition ) ) : ?>
						<?php foreach ( $condition as $or_index => $and_conditions ) : ?>
							<div class="or-condition-filter" data-or-index="<?php echo esc_html( $or_index ); ?>">
								<div class="mwb-form-filter-row">
									<?php foreach ( $and_conditions as $and_index => $and_condition ) : ?>
										<?php
										$and_condition['form'] = $fields;
										$template_manager      = new Woo_Crm_Integration_Connect_Framework();
										$template_manager->render_and_conditon_cf7( $and_condition, $and_index, $or_index );
										?>
									<?php endforeach; ?>
									<button data-next-and-index="<?php echo esc_html( ++$and_index ); ?>" data-or-index="<?php echo esc_html( $or_index ); ?>" class="button condition-and-btn"><?php esc_html_e( 'Add "AND" filter', 'woo-crm-integration-for-zoho' ); ?></button>
									<?php if ( 1 != $prefilled_indexes ) :  // phpcs:ignore ?>
										<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/trash.svg' ); ?>" style="max-width: 20px;" class="dashicons-trash" alt="<?php esc_html_e( 'Trash', 'woo-crm-integration-for-zoho' ); ?>">
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<button data-next-or-index="<?php echo esc_html( ++$prefilled_indexes ); ?>" class="button condition-or-btn"><?php esc_html_e( 'Add "OR" filter', 'woo-crm-integration-for-zoho' ); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>


