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
<section id="wciz-tab__wrapper" class="wciz-tab">
	
	<?php
	/**
	 * Trigger associated Feeds.
	 *
	 * @since 1.0.0
	 */
	do_action( 'wciz_crm_connect_render_tab' );
	?>
	
	<div class="wciz-tab-content-wrapper">
	<?php
	/**
	 * Trigger associated Feeds.
	 *
	 * @since 1.0.0
	 */
	do_action( 'wciz_crm_connect_render_tab_content' );
	?>
	</div>
</section>
