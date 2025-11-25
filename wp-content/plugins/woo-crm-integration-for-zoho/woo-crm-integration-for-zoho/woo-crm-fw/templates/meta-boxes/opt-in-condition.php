<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the primary field of feeds section.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/woo-crm-fw/templates/meta-boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$prefilled_indexes = isset( $params['condition'] ) ? count( $params['condition'] ) : '';
?>
<div id="wciz-condition-filter-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap">
	<a class="wciz-feeds__header-link active">
		<?php esc_html_e( 'Condition Filter', 'woo-crm-integration-for-zoho' ); ?>
	</a>

	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper  wciz-form-filter-wrapper">
				<div class="wciz-toggle-wrap">
					<input type="checkbox" name="enable_add_condition" id="wciz-zoho-enable-add-condition" value="yes" <?php checked( 'yes', $params['enable_filter'] ); ?>>
					<div class="wciz_field_desc">
						<p>
							<?php esc_html_e( 'Enable to add Conditional filters on data syncing.', 'woo-crm-integration-for-zoho' ); ?>
						</p>
					</div>
				</div>
				<div class="wciz-initial-filter">
					<?php if ( ! empty( $params['condition'] ) && is_array( $params['condition'] ) ) : ?>
						<?php foreach ( $params['condition'] as $or_index => $and_conditions ) : ?>
							<div class="or-condition-filter" data-or-index="<?php echo esc_html( $or_index ); ?>">
								<div class="wciz-form-filter-row">
									<?php foreach ( $and_conditions as $and_index => $and_condition ) : ?>
										<?php
										$and_condition['object_fields'] = $params['fields'];
										$template_manager               = new Woo_Crm_Integration_Connect_Framework();
										$template_manager->render_and_conditon( $and_condition, $and_index, $or_index );
										?>
									<?php endforeach; ?>
									<button data-next-and-index="<?php echo esc_html( ++$and_index ); ?>" data-or-index="<?php echo esc_html( $or_index ); ?>" class="wciz-btn wciz-btn--filled condition-and-btn"><?php esc_html_e( 'Add "AND" filter', 'woo-crm-integration-for-zoho' ); ?></button>
									<?php if ( 1 != $prefilled_indexes ) :  // phpcs:ignore ?>
										<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/trash.svg' ); ?>" class="dashicons-trash" alt="<?php esc_html_e( 'Trash', 'woo-crm-integration-for-zoho' ); ?>">
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<button data-next-or-index="<?php echo esc_html( ++$prefilled_indexes ); ?>" class="wciz-btn wciz-btn--filled condition-or-btn"><?php esc_html_e( 'Add "OR" filter', 'woo-crm-integration-for-zoho' ); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>


