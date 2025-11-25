<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the Zoho logs listing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Zoho
 * @subpackage Mwb_Cf7_Integration_With_Zoho/admin/partials/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mwb-cf7-integration__logs-wrap" id="mwb-zoho-cf7-logs" ajax_url="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>">
	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-cf7_integration_logo-crm">
			<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/icons/zoho-logo.png' ); ?>" alt="<?php esc_html_e( 'ZOHO', 'woo-crm-integration-for-zoho' ); ?>">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/icons/contact-form.svg' ); ?>" alt="<?php esc_html_e( 'CF7', 'woo-crm-integration-for-zoho' ); ?>">
		</div>
		<?php if ( $log_enable ) : ?>
				<ul class="mwb-logs__settings-list">
					<li class="mwb-logs__settings-list-item">
						<a id="mwb-zoho-cf7-clear-log" href="javascript:void(0)" class="mwb-logs__setting-link">
							<?php esc_html_e( 'Clear Log', 'woo-crm-integration-for-zoho' ); ?>	
						</a>
					</li>
					<li class="mwb-logs__settings-list-item">
						<a id="mwb-zoho-cf7-download-log" href="javascript:void(0)"  class="mwb-logs__setting-link">
							<?php esc_html_e( 'Download', 'woo-crm-integration-for-zoho' ); ?>	
						</a>
					</li>
				</ul>
		<?php endif; ?>
	</div>
	<div>
		<div>
			<?php if ( $log_enable ) : ?>
			<div class="mwb-cf7-integration__logs-table-wrap">
				<table id="mwb-zoho-cf7-table" class="display mwb-cf7-integration__logs-table dt-responsive nowrap" style="width: 100%;">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Expand', 'woo-crm-integration-for-zoho' ); ?></th>
							<th><?php esc_html_e( 'Feed', 'woo-crm-integration-for-zoho' ); ?></th>
							<th><?php esc_html_e( 'Feed ID', 'woo-crm-integration-for-zoho' ); ?></th>
							<th>
								<?php
								/* translators: %s: CRM name. */
								printf( esc_html__( '%s Object', 'woo-crm-integration-for-zoho' ), esc_html( 'Zoho' ) );
								?>
							</th>
							<th>
								<?php
								/* translators: %s: CRM name. */
								printf( esc_html__( '%s ID', 'woo-crm-integration-for-zoho' ), esc_html( 'Zoho' ) );
								?>
							</th>
							<th><?php esc_html_e( 'Event', 'woo-crm-integration-for-zoho' ); ?></th>
							<th><?php esc_html_e( 'Timestamp', 'woo-crm-integration-for-zoho' ); ?></th>
							<th class=""><?php esc_html_e( 'Request', 'woo-crm-integration-for-zoho' ); ?></th>
							<th class=""><?php esc_html_e( 'Response', 'woo-crm-integration-for-zoho' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
			<?php else : ?>
				<div class="mwb-content-wrap">
					<?php esc_html_e( 'Please enable the logs from ', 'woo-crm-integration-for-zoho' ); ?><a href="<?php echo esc_url( 'admin.php?page=wciz_zoho_cf7&tab=settings' ); ?>" target="_blank"><?php esc_html_e( 'Settings tab', 'woo-crm-integration-for-zoho' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
