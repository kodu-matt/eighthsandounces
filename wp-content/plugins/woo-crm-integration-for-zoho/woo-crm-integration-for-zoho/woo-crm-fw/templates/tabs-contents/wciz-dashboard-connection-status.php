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
<div class="wciz-dashboard__header-content">
	<h3 class="wciz-section__sub-heading">
		<?php esc_html_e( 'ZOHO Connection Status', 'woo-crm-integration-for-zoho' ); ?>
	</h3>
	<div class="wciz-dashboard__header-text">
		<span class="<?php echo esc_attr( $connection_status ? 'is-connected' : 'disconnected' ); ?>" >
			<?php esc_html_e( 'Connected', 'woo-crm-integration-for-zoho' ); ?>
		</span>
	</div>
</div>
<div class="wciz-dashboard__header-button-wrap">	
	<div class="wciz-dashboard__header-button-text">
		<div class="wciz-dashboard__header-token-notice" id="wciz-zoho-token-notice">
			<?php if ( ! $token_expiry ) : ?>
				<?php esc_html_e( 'Access token has been expired.', 'woo-crm-integration-for-zoho' ); ?>
				<?php else : ?>
					<?php
						// translators: Token expiry time.
						echo esc_html( sprintf( __( 'The access token expires in %s minutes.', 'woo-crm-integration-for-zoho' ), esc_html( $token_expiry ) ) );
					?>
				<?php endif; ?>
		</div>
		<div class="wciz-dashboard__header-refresh-access-token-wrap">
			<a id="wciz-woo-zoho-refresh-access-token">
				<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/refresh.svg">
				<span><?php esc_html_e( 'Refresh Access Token', 'woo-crm-integration-for-zoho' ); ?></span>
			</a>
		</div>
	</div>
	<div class="wciz-dashboard__header-button">
		<a id="wciz-woo-zoho-disconnect" href="#" class="wciz-btn wciz-btn--filled">
			<?php esc_html_e( 'Disconnect', 'woo-crm-integration-for-zoho' ); ?>
		</a>
		<a id="wciz-woo-zoho-reauth" href="<?php echo esc_url( wp_nonce_url( admin_url( '?wciz_get_zoho_code=1' ) ) ); ?>" class="wciz-btn wciz-btn--filled">
			<?php esc_html_e( 'Reauthorize', 'woo-crm-integration-for-zoho' ); ?>
		</a>
	</div>
</div>
<div class="wciz-dashboard__header-text">
	<div class="wciz-dashboard-organization-details">
		<div class="row wciz-auth-user">
			<?php 
			$wciz_api = Woo_Crm_Integration_Zoho_Api::get_instance();
			$res_data = $wciz_api->get_oauth_user_details();
			$body     = json_decode( $res_data['body'] );

			$user_email = ( ! empty( $body ) && isset( $body->Email ) ) ? $body->Email : '';
			$user_zuid  = ( ! empty( $body ) && isset( $body->ZUID ) ) ? $body->ZUID : '';

			?>
			<p><?php esc_html_e( 'Authenticate user in Zoho', 'woo-crm-integration-for-zoho' ); ?> : </p>
			<span class="wciz_zoho_logged_in_user_details"><?php echo esc_html($user_email). '( ZUID - '.esc_html( $user_zuid ) .' )'; ?></span>
		</div>
	</div>
</div>
