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
<?php $zoho_instance = Woo_Crm_Integration_For_Zoho_Fw::get_instance(); ?>
<ul class="wciz-dashboard__about-list">
	<li class="wciz-dashboard__about-list-item wciz-content-wrap">
		<!-- <div class="wciz-dash-card"> -->
			<h2 class="wciz-section__heading">
				<?php esc_html_e( 'Contacts Synced', 'woo-crm-integration-for-zoho' ); ?>
			</h2>
			<div class="wciz-content__list-item-text">
				<div class="wciz-dashboard__about-number">
					<span>
					<?php $zoho_instance->getSyncedObjectsCount( 'Contacts' ); ?>
					</span>
				</div>
				<div class="wciz-dashboard__about-number-desc">
					<p>	
					</p>
				</div>
			</div>
			<div class="wciz-content__list-item-image">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/contact.svg" alt="">
			</div>
		<!-- </div> -->
	</li>
	<li class="wciz-dashboard__about-list-item wciz-content-wrap">
		<!-- <div class="wciz-dash-card"> -->
			<h2 class="wciz-section__heading">
				<?php esc_html_e( 'Deals Synced', 'woo-crm-integration-for-zoho' ); ?>
			</h2>
			<div class="wciz-content__list-item-text ">
				<div class="wciz-dashboard__about-number">
					<span>
					<?php $zoho_instance->getSyncedObjectsCount( 'Deals' ); ?>
					</span>
				</div>
				<div class="wciz-dashboard__about-number-desc">
					<p>
					</p>
				</div>
			</div>
			<div class="wciz-content__list-item-image">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/deals.svg" alt="">
			</div>
		<!-- </div> -->
	</li>
	<li class="wciz-dashboard__about-list-item wciz-content-wrap">
		<!-- <div class="wciz-dash-card"> -->
			<h2 class="wciz-section__heading">
				<?php esc_html_e( 'Products Synced', 'woo-crm-integration-for-zoho' ); ?>
			</h2>
			<div class="wciz-content__list-item-text">
				<div class="wciz-dashboard__about-number">
					<span>
					<?php $zoho_instance->getSyncedObjectsCount( 'Products' ); ?>
					</span>
				</div>
				<div class="wciz-dashboard__about-number-desc">
					<p>	
					</p>
				</div>
			</div>
			<div class="wciz-content__list-item-image">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/product.svg" alt="">
			</div>
		<!-- </div> -->
	</li>
	<li class="wciz-dashboard__about-list-item  wciz-content-wrap">
		<!-- <div class="wciz-dash-card"> -->
			<h2 class="wciz-section__heading">
				<?php esc_html_e( 'Orders Synced', 'woo-crm-integration-for-zoho' ); ?>
			</h2>
			<div class="wciz-content__list-item-text ">
				<div class="wciz-dashboard__about-number">
					<span>
					<?php $zoho_instance->getSyncedObjectsCount( 'Sales_Orders' ); ?>
					</span>
				</div>
				<div class="wciz-dashboard__about-number-desc">
					<p>
					</p>
				</div>
			</div>
			<div class="wciz-content__list-item-image">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/sales-order.svg" alt="">
			</div>
		<!-- </div> -->
	</li>
</ul>
