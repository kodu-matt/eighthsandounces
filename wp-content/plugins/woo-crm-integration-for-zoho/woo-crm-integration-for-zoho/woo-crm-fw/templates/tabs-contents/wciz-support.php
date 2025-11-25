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


<div id="wciz-support" class="wciz-tabcontent wciz-iwsa_support">
	<div class="wciz-iwsa_support-inner">
		<section class="wciz-iwsa_support-head">
			<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'Thank You for Becoming Our Valued Customer', 'woo-crm-integration-for-zoho' ); ?>
			</h3>
			<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Your purchase allows us to extend our support to you. We aim to provide you with uninterrupted services tirelessly!', 'woo-crm-integration-for-zoho' ); ?>
			</p>
		</section>
		<section class="wciz-iwsa_support-content">
			<a href="#" class="wciz-iwsa_support-content-item-wrap video">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/video.png' ); ?>" alt="video">
				<span class="item-content">
					<h4 class="title"><?php esc_html_e( 'Watch Our Video', 'woo-crm-integration-for-zoho' ); ?></h4>
					<p class="desc"><?php esc_html_e( 'For a better audio-visual understanding of the plugin.', 'woo-crm-integration-for-zoho' ); ?></p>
				</span>
			</a>
			<a href="https://nebulastores.com/product/woo-crm-integration-of-zoho/" class="wciz-iwsa_support-content-item-wrap type-2">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/doc.png' ); ?>" alt="Documentations">
				<span class="item-content">
					<h4 class="title"><?php esc_html_e( 'Read documentation', 'woo-crm-integration-for-zoho' ); ?></h4>
					<p class="desc"><?php esc_html_e( 'To get an in-depth understanding of the plugin settings.', 'woo-crm-integration-for-zoho' ); ?></p>
				</span>
			</a>
			<a href="#" class="wciz-iwsa_support-content-item-wrap type-3">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/faq.png' ); ?>" alt="faqs">
				<span class="item-content">
					<h4 class="title"><?php esc_html_e( 'Refer to the FAQs', 'woo-crm-integration-for-zoho' ); ?></h4>
					<p class="desc"><?php esc_html_e( 'To learn what your peers are curious about and resolve your queries instantly.', 'woo-crm-integration-for-zoho' ); ?></p>
				</span>
			</a>
			<a href="#" class="wciz-iwsa_support-content-item-wrap type-4">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/settings.png' ); ?>" alt="setup">
				<span class="item-content">
					<h4 class="title"><?php esc_html_e( 'Contact us for a paid setup', 'woo-crm-integration-for-zoho' ); ?></h4>
					<p class="desc"><?php esc_html_e( 'And avail best-in-class WooCommerce Services.', 'woo-crm-integration-for-zoho' ); ?></p>
				</span>
			</a>
			<a href="#" class="wciz-iwsa_support-content-item-wrap type-5">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/queries.png' ); ?>" alt="queries">
				<span class="item-content">
					<h4 class="title"><?php esc_html_e( 'Submit a Ticket', 'woo-crm-integration-for-zoho' ); ?></h4>
					<p class="desc"><?php esc_html_e( 'To get your queries resolved one-on-one with our team.', 'woo-crm-integration-for-zoho' ); ?></p>
				</span>
			</a>
			<a href="https://wa.me/message/JSDF7KNKMUSKA1" class="wciz-iwsa_support-content-item-wrap type-6">
				<img class="wciz-wp" src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/whatsapp.svg' ); ?>" alt="whatsapp">
				<span class="item-content">
					<h4 class="title"><?php esc_html_e( 'Get in touch via WhatsApp', 'woo-crm-integration-for-zoho' ); ?></h4>
					<p class="desc"><?php esc_html_e( 'For a quick chat with our support team.', 'woo-crm-integration-for-zoho' ); ?></p>
				</span>
			</a>
		</section>
	</div>
</div>
