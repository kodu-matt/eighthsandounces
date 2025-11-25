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

<div class="wciz-body-container">
	<?php if ( '1' == get_option( 'wciz_woo_zoho_authorised', false ) && '1' == get_option( 'wciz_woo_zoho_onboarding_completed', false ) ) : ?>
		<div class="wciz-crm-name">
				<h1 class="wciz-crm-name__title">
					<?php
					echo esc_html( 'Woo CRM Integration for Zoho' );
					?>
				</h1>
				<div class="wciz-crm-name__version">
				<?php printf( 'V %s', esc_html( WOO_CRM_INTEGRATION_ZOHO_VERSION ) ); ?>
				</div>
			</div>
			<!-- Dashboard Screen. -->
		<?php
		/**
		 * Trigger associated Feeds.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $user_id  Woo user id.
		 * @param mixed array.
		 */
		do_action( 'wciz_woo_connect_dashboard_screen' );
		?>
		<?php else : ?>
			<!-- Authorisation Screen. -->
			<?php
			/**
			 * Trigger associated Feeds.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed  $user_id  Woo user id.
			 * @param mixed array.
			 */
			do_action( 'wciz_woo_connect_authorisation_screen' );
			?>
					
	<?php endif; ?>
</div>
