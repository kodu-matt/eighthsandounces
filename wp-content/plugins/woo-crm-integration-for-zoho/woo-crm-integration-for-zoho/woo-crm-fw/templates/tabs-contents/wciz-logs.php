<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin/partials
 */

?>
<?php
$log_enable      = Woo_Crm_Integration_For_Zoho_Admin::is_log_enable();
$data_log_enable = Woo_Crm_Integration_For_Zoho_Admin::is_data_log_enable();

?>
<div id="wciz-logs" class="wciz-content-wrap wciz-tabcontent" ajax_nonce="<?php echo esc_attr( wp_create_nonce( 'ajax_nonce' ) ); ?>"  ajax_url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" >
	<section class="wciz-iwsa_support-head">
		<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'Sync Log Data', 'woo-crm-integration-for-zoho' ); ?>
		</h3>
		<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Record of all sync activities', 'woo-crm-integration-for-zoho' ); ?>
		</p>
	</section>
	<div class="wciz-logs__header">
		<div class="wciz-logs__heading-wrap">
		</div>
		<?php if ( $log_enable ) : ?>
		<ul class="wciz-logs__settings-list">
			<li class="wciz-logs__settings-list-item">
				<a id="wciz-woo-zoho-reload-log" class="wciz-logs__setting-link">
					<?php esc_html_e( 'Reload', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
			</li>
			<li class="wciz-logs__settings-list-item">
				<a id="wciz-woo-zoho-clear-log" href="#" class="wciz-logs__setting-link">
					<?php esc_html_e( 'Clear Log', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
			</li>
			<li class="wciz-logs__settings-list-item">
				<a id="wciz-woo-zoho-download-log" class="wciz-logs__setting-link">
					<?php esc_html_e( 'Download', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
			</li>
		</ul>
		<?php endif; ?>
	</div>
	<?php if ( $log_enable ) : ?>
	<div class="wciz-table__wrapper">
		<!-- Custom Filter -->
		<table class="error_filter">
			<tr>
				<td><?php esc_html_e( 'Filter by: ', 'woo-crm-integration-for-zoho' ); ?></td>
				<td>
					<select id="searchByError">
						<option value=""><?php esc_html_e( 'All', 'woo-crm-integration-for-zoho' ); ?></option>
						<option value="error"><?php esc_html_e( 'Errors', 'woo-crm-integration-for-zoho' ); ?></option>
					</select>
				</td>
			</tr>
		</table>
		<table id="wciz-table" width="100%" class="wciz-table display dt-responsive">
			<thead>
				<tr>
					<th><?php esc_html_e( '', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Woo Id', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Feed', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Woo', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Zoho Id', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Zoho', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Time', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Request', 'woo-crm-integration-for-zoho' ); ?></th>
					<th><?php esc_html_e( 'Response', 'woo-crm-integration-for-zoho' ); ?></th>
				</tr>
			</thead>
		</table>
	</div>
	<?php else : ?>
	<div class="wciz-content-wrap">
		<?php esc_html_e( 'Please enable the log', 'woo-crm-integration-for-zoho' ); ?>
	</div>
	<?php endif; ?>
	<div class="wciz-logs__header">
		<div class="wciz-logs__heading-wrap">
			<h2 class="wciz-section__heading">
				<?php esc_html_e( 'Zoho Data Log', 'woo-crm-integration-for-zoho' ); ?>	
			</h2>
		</div>
		<?php if ( $data_log_enable ) : ?>
		<ul class="wciz-logs__settings-list">
			<li class="wciz-logs__settings-list-item">
				<a id="wciz-woo-zoho-reload-data-log" class="wciz-logs__setting-link">
					<?php esc_html_e( 'Reload', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
			</li>
			<li class="wciz-logs__settings-list-item">
				<a id="wciz-zoho-clear-data-log" href="#" class="wciz-logs__setting-link">
					<?php esc_html_e( 'Clear Log', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
			</li>
			<li class="wciz-logs__settings-list-item">
				<a id="wciz-zoho-download-data-log" class="wciz-logs__setting-link">
					<?php esc_html_e( 'Download', 'woo-crm-integration-for-zoho' ); ?>	
				</a>
			</li>
		</ul>
		<?php endif; ?>
	</div>
	<?php if ( $data_log_enable ) : ?>
	<div class="wciz-content-wrap wciz-zoho-data-log-wrap">
		<div class="wciz-zoho-data-log">

		</div>
	</div>
	<?php else : ?>
	<div class="wciz-content-wrap">
		<?php esc_html_e( 'Please enable the data log', 'woo-crm-integration-for-zoho' ); ?>
	</div>
	<?php endif; ?>
</div>
