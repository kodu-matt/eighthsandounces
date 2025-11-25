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
<div id="wciz-dashboard" class="wciz-tabcontent">
	<section class="wciz-iwsa_support-head">
		<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'Dashboard', 'woo-crm-integration-for-zoho' ); ?>
		</h3>
		<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Access recent activity and quick actions', 'woo-crm-integration-for-zoho' ); ?>
		</p>
	</section>
	<div class="wciz-content-wrap wciz-content-wrap--dashhboard-header">	
		<?php
		require WOO_CRM_INTEGRATION_ZOHO_PATH . 'woo-crm-fw/templates/tabs-contents/wciz-dashboard-connection-status.php';
		?>
	</div>
	<div class="wciz-dashboard__about">
		<?php
		require WOO_CRM_INTEGRATION_ZOHO_PATH . 'woo-crm-fw/templates/tabs-contents/wciz-dashboard-sync-summary.php';
		?>
	</div>
	<div class="wciz-content-wrap">
		<?php
		require WOO_CRM_INTEGRATION_ZOHO_PATH . 'woo-crm-fw/templates/tabs-contents/wciz-dashboard-about-list.php';
		?>
	</div>
</div>
