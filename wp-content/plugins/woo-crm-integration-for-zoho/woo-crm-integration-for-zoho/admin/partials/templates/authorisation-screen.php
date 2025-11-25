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

<div class="wciz-progress__wrap">
	<div class="wciz-progress">
	<div class="wciz-progress__bar step-1 active">.</div>
</div>
<div class="wciz-progress__step-wrap">
	<div class="wciz-progress__step">
	</div>
	<div class="wciz-progress__step">
		<?php esc_html_e( 'Initialize Connection', 'woo-crm-integration-for-zoho' ); ?>
	</div>
	<div class="wciz-progress__step">
		<?php esc_html_e( 'Build Feeds', 'woo-crm-integration-for-zoho' ); ?>
	</div>
	<div class="wciz-progress__step">
		<?php esc_html_e( 'Finalized', 'woo-crm-integration-for-zoho' ); ?>
	</div>
</div>
</div>
<section class="wciz-intro">
	<div class="wciz-content-wrap wciz-sc-auth">
		<div class="wciz-intro__header">
			<h2 class="wciz-section__heading">
				<?php
				echo esc_html__( 'Integrate Zoho with WooCommerce for CRM, inventory management, and automation. Sync customer data, orders, and stock levels seamlessly. Automate workflows for better efficiency.', 'woo-crm-integration-for-zoho' );
				?>
			</h2>
		</div>
		<?php if ( '1' != get_option( 'wciz_woo_zoho_authorised', false ) ) : ?>
		<div class="wciz-intro__body wciz-intro__content">
			<div class="wciz-card">
				<p>
					<?php
					esc_html_e(
						'Easily sync WooCommerce data with Zoho CRM. Automatically create Contacts, Deals, Sales Orders, and Products based on WooCommerce orders. Products are mapped to Sales Orders in Zoho CRM.
						Improve workflow efficiency by integrating WooCommerce orders directly into Zoho CRM.',
						'woo-crm-integration-for-zoho'
					);
					?>
				</p>
				<ul class="wciz-intro__list">
					<li class="wciz-intro__list-item">
						<?php esc_html_e( 'Integrate Zoho CRM with WooCommerce.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-intro__list-item">
					<?php esc_html_e( 'Build Feeds for Contacts, Products, Deals, and Orders.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-intro__list-item">
					<?php esc_html_e( 'Synchronize your data with Zoho CRM.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
				</ul>
				<div class="wciz-intro__button">
					<a href="#" class="wciz-btn wciz-btn--filled" onclick="showAuthForm( event )">
						<?php
						esc_html_e(
							'Link your Account',
							'woo-crm-integration-for-zoho'
						);
						?>
					</a>
				</div>
			</div>  
		</div>  
		<div class="wciz-intro__body wciz-zoho-auth-wrap section-hide">
			<div class="wciz-card">
				<?php
				$settings = Woo_Crm_Integration_For_Zoho_Admin::get_authorization_settings();
				?>
				<form action="#" method="post" id="wciz-zoho-auth-portal">
					<?php woocommerce_admin_fields( $settings ); ?>
				</form>
				<div class="wciz-intro__button">
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( '?wciz_get_zoho_code=1' ) ) ); ?>" class="wciz-btn wciz-btn--filled" id="wciz-zoho-authorize-button" >
						<?php esc_html_e( 'Authorize', 'woo-crm-integration-for-zoho' ); ?>
					</a>
					<div class="wciz-intro__bottom-text-wrap">
						<p>
						<?php
						esc_html_e(
							'Don’t have an account?',
							'woo-crm-integration-for-zoho'
						);
						?>
							<a href="https://www.zoho.com/signup.html" target="_blank" class="wciz-btn__bottom-text"><?php esc_html_e( 'Free Account Registration', 'woo-crm-integration-for-zoho' ); ?></a></p>
						<p class="wciz-link-raw">
						<?php
						esc_html_e(
							'Find your Client/Secret ID here.',
							'woo-crm-integration-for-zoho'
						);
						?>
							<a href="https://api-console.zoho.in/" target="_blank" class="wciz-btn__bottom-text"><?php esc_html_e( 'Get API Keys', 'woo-crm-integration-for-zoho' ); ?></a></p>
						<!-- <p>
							<?php // esc_html_e( 'App setup instructions.', 'woo-crm-integration-for-zoho' ); ?>
							<a href="https://nebulastores.com/product/woo-crm-integration-of-zoho/" target="_blank" class="wciz-btn__bottom-text"><?php // esc_html_e( 'View Details', 'woo-crm-integration-for-zoho' ); ?></a>
						</p> -->
					</div>
				</div>
			</div>
		</div>
		<?php else : ?>
		<div class="wciz-intro__body wciz-zoho-auth-success">
			<div class="wciz-card">
				<p> <?php esc_html_e( 'Authentication successful. Proceed to the next step.', 'woo-crm-integration-for-zoho' ); ?> </p>
				<div class="wciz-intro__button">
					<a href="#" class="wciz-btn wciz-btn--filled progress-next show-properties-btn">
						<?php esc_html_e( 'Move to the next', 'woo-crm-integration-for-zoho' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>

<section class="wciz-properties">
	<div class="wciz-content-wrap wciz-sc-auth">
		<div class="wciz-properties__header">
			<h2 class="wciz-section__heading">
				<?php esc_html_e( 'Build Feeds', 'woo-crm-integration-for-zoho' ); ?>
			</h2>
		</div>
		<div class="wciz-properties__body wciz-properties__content">
			<div class="wciz-card">
				<p>
					<?php
					esc_html_e( 'You’ve successfully connected WooCommerce to Zoho CRM. Now, create feeds to map and send data to Zoho CRM.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
				<p>
					<?php
					esc_html_e( 'We create four default feeds to sync WooCommerce data with Zoho CRM.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
				<ul class="wciz-properties__list">
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'View your default contact feed.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'View your default product feed.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'View your primary deals feed.', 'woo-crm-integration-for-zoho' ); ?> 
					</li>
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'View your standard order feed.', 'woo-crm-integration-for-zoho' ); ?> 
					</li>
				</ul>
				<div class="wciz-properties__progress-button">
					<a href="#" class="wciz-btn wciz-btn--filled show-intro-progress">
						<?php esc_html_e( 'Build Feeds', 'woo-crm-integration-for-zoho' ); ?>
					</a>
				</div>
				<div class="wciz-properties__progress">
					<div id="wciz-properties__progress-bar" class="wciz-properties__progress-bar" role="progressbar"></div>
				</div>
				<div class="wciz-sync-result">
					<span class="wciz-properties__progress-result"></span>
				</div>
				<div class="wciz-properties__button">
					<a href="#" class="wciz-btn wciz-btn--filled progress-next wciz_next_step">
						<?php esc_html_e( 'Move to the next', 'woo-crm-integration-for-zoho' ); ?>
					</a>
				</div>
			</div>         
		</div>         
	</div>
</section>

<section class="wciz-abandoned-cart">
	<div class="wciz-content-wrapper wciz-sc-auth">
		<div class="wciz-abandoned-cart__body wciz-abandoned-cart__content-wrap">                  
			<div class="wciz-abandoned-cart__content">
				<div class="wciz-card">
					<p>
						<?php
						esc_html_e( 'Add abandoned cart object and custom fields in Zoho CRM. Create feed in the plugin for the abandoned cart object.', 'woo-crm-integration-for-zoho' );
						?>
					</p>
					<ul class="wciz-properties__list">
						<li class="wciz-properties__list-item">
							<?php esc_html_e( 'Create an abandoned cart object.', 'woo-crm-integration-for-zoho' ); ?>
						</li>
						<li class="wciz-properties__list-item">
							<?php esc_html_e( 'Create an abandoned cart object fields.', 'woo-crm-integration-for-zoho' ); ?>
						</li>
						<li class="wciz-properties__list-item">
							<?php esc_html_e( 'Create a feed for abandoned carts.', 'woo-crm-integration-for-zoho' ); ?>
						</li>
						<li class="wciz-properties__list-item">
							<?php esc_html_e( 'Map default fields mapping', 'woo-crm-integration-for-zoho' ); ?>
						</li>
					</ul>          
					<div class="wciz-abandoned-cart__button">
						<a href="#" class="wciz-btn wciz-btn--filled" id="create_abandoned_cart_object" onclick="">
							<?php esc_html_e( 'Create an object with fields and a feed', 'woo-crm-integration-for-zoho' ); ?>
						</a>
					</div>
					<div class="wciz-seprator"></div>
					<div class="wciz-abandoned-cart__button">
						<a href="#" class="wciz-btn wciz-btn--filled" id="skip_abandoned_cart_creation" onclick="">
							<?php esc_html_e( 'Skip', 'woo-crm-integration-for-zoho' ); ?>
						</a>
					</div>
					<div class="wciz-abandoned-cart-feed__progress">
					<div id="wciz-abandoned-cart-feed__progress-bar" class="wciz-abandoned-cart-feed__progress-bar" role="progressbar"></div>
					</div>
					<div class="wciz-abandoned-cart-feed-result">
						<span class="wciz-abandoned-cart-feed__progress-result"></span>
					</div>
					<div class="wciz-abandoned-cart__button">
						<a href="#" class="wciz-btn wciz-btn--filled progress-next create-abandoned-cart-object-and-fields">
							<?php esc_html_e( 'Move to the next', 'woo-crm-integration-for-zoho' ); ?>
						</a>
					</div>
				</div>             
			</div>             
		</div>       
	</div>
</section>

<section class="wciz-product-initial-sync">
	<div class="wciz-content-wrapper wciz-sc-auth">
		<div class="wciz-product-initial-sync__body wciz-product-initial-sync__content-wrap">                  
			<div class="wciz-product-initial-sync__content">
				<div class="wciz-card">
					<p>
						<?php
						esc_html_e( 'Sync all products on your site to prevent issues with order and deal synchronization.', 'woo-crm-integration-for-zoho' );
						?>
					</p>        
					<div class="wciz-product-initial-sync__button">
						<a href="#" class="wciz-btn wciz-btn--filled" id="sync_products_initially" onclick="">
							<?php esc_html_e( 'Sync all products', 'woo-crm-integration-for-zoho' ); ?>
						</a>
						<a href="#" class="wciz-btn wciz-btn--filled" id="skip_product_initial_syncing" onclick="">
							<?php esc_html_e( 'Skip syncing', 'woo-crm-integration-for-zoho' ); ?>
						</a>
					</div>
					<div class="wciz-product-initial-sync__progress">
					<div id="wciz-product-initial-sync__progress-bar" class="wciz-product-initial-sync__progress-bar" role="progressbar" style="width: 1%;"></div>
					</div>
					<div class="wciz-product-initial-sync-result">
						<span class="wciz-product-initial-sync__progress-result"></span>
					</div>
					<div class="wciz-product-initial-sync__button">
						<a href="#" class="wciz-btn wciz-btn--filled progress-next sync-products-initially">
							<?php esc_html_e( 'Move to the next', 'woo-crm-integration-for-zoho' ); ?>
						</a>
					</div>
				</div>             
			</div>             
		</div>       
	</div>
</section>

<section class="wciz-sync">
	<div class="wciz-content-wrap wciz-sc-auth">
		<div class="wciz-sync__header">
			<h2 class="wciz-section__heading">
				<?php
				esc_html_e( 'Congratulations! The Woo CRM Integration for Zoho plugin has been set up successfully!', 'woo-crm-integration-for-zoho' );
				?>
			</h2>
		</div>
		<div class="wciz-sync__body wciz-sync__content-wrap">
			<div class="wciz-sync__content">            
				<p> 
					<?php
					esc_html_e( 'You can now access the dashboard and sync the data.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
				<ul class="wciz-properties__list wciz-setup-complete-step">
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'Review all created feeds and their mappings. Adjust them as needed.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'Enable instant or background sync in settings to start syncing data.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'Use the bulk sync feature in the Data Sync tab to sync historical data.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
					<li class="wciz-properties__list-item">
						<?php esc_html_e( 'If your data is missing in Zoho CRM, check the logs for errors.', 'woo-crm-integration-for-zoho' ); ?>
					</li>
				</ul>
				<div class="wciz-sync__button">
					<a href="#" class="wciz-btn wciz-btn--filled wciz-onboarding-complete" onclick="">
						<?php esc_html_e( 'Access Dashboard', 'woo-crm-integration-for-zoho' ); ?>
					</a>
				</div>
			</div>
			<div class="wciz-sync__image">    
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/congratulation.png">
			</div>
		</div>       
	</div>
</section>
