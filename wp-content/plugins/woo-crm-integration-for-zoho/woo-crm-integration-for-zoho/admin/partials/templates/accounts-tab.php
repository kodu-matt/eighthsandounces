<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the accounts creation page.
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
<div class="mwb_cf7_integration_account-wrap">

	<!-- Logo section start -->
	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-cf7_integration_logo-crm">
			<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/icons/zoho-logo.png' ); ?>" alt="<?php esc_html_e( 'Zoho', 'woo-crm-integration-for-zoho' ); ?>">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/icons/contact-form.svg' ); ?>" alt="<?php esc_html_e( 'CF7', 'woo-crm-integration-for-zoho' ); ?>">
		</div>
	</div>
	<!-- Logo section end -->

	<!--============================================================================================
										Dashboard page start.
	================================================================================================-->

	<!-- Connection status start -->
	<div class="mwb_cf7_integration_crm_connected">
		<ul>
			<li class="mwb-cf7_intergation_conn-row">
				<div class="mwb-cf7-integration__content-wrap">
					<div class="mwb-section__sub-heading__wrap">
						<h3 class="mwb-section__sub-heading">
							<?php printf( '%s %s', esc_html( 'CRM Integration for Zoho' ), esc_html__( 'Connection Status', 'woo-crm-integration-for-zoho' ) ); ?>
						</h3>
						<div class="mwb-dashboard__header-text">
							<span class="<?php echo esc_attr( 'is-connected' ); ?>" >
								<?php esc_html_e( 'Connected', 'woo-crm-integration-for-zoho' ); ?>
							</span>
						</div>
					</div>

					<div class="mwb-cf7-integration__status-wrap">
						<div class="mwb-cf7-integration__left-col">

							<div class="mwb-cf7-integration-token-notice__wrap">
								<p id="mwb-cf7-token-expiry-notice" >
									<?php if ( ! $expires_in ) : ?>
										<?php esc_html_e( 'Access token has been expired.', 'woo-crm-integration-for-zoho' ); ?>
										<?php else : ?>
											<?php
												// translators: %s: Token expiry time.
												echo esc_html( sprintf( __( 'The access token expires in %s minutes.', 'woo-crm-integration-for-zoho' ), esc_html( $expires_in ) ) );
											?>
										<?php endif; ?>
								</p>
								<p class="mwb-cf7-integration-token_refresh ">
									<img id ="mwb_cf7_integration_refresh_token" src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/refresh.svg' ); ?>" title="<?php esc_html_e( 'Refresh Access Token', 'woo-crm-integration-for-zoho' ); ?>">
								</p>
							</div>
						</div>

						<?php echo esc_url( wp_nonce_url( admin_url( '?mwb-cf7-integration-perform-reauth=1' ) ) ); ?>
						<?php esc_html_e( 'Reauthorize', 'woo-crm-integration-for-zoho' ); ?>
						<?php esc_html_e( 'Disconnect', 'woo-crm-integration-for-zoho' ); ?>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<!-- Connection status end -->

	<!-- About list start -->
	<div class="mwb-dashboard__about">
		<div class="mwb-dashboard__about-list">
			<div class="mwb-content__list-item-text">
				<h2 class="mwb-section__heading"><?php esc_html_e( 'Synced Contact Forms', 'woo-crm-integration-for-zoho' ); ?></h2>
				<div class="mwb-dashboard__about-number">
					<span><?php echo esc_html( ! empty( $count ) ? $count : '0' ); ?></span>
				</div>
				<div class="mwb-dashboard__about-number-desc">
					<p>
						<i><?php esc_html_e( 'Total number of Contact Form 7 submission successfully synchronized over ZOHO CRM.', 'woo-crm-integration-for-zoho' ); ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=wciz_zoho_cf7&tab=logs' ) ); ?>" target="_blank"><?php esc_html_e( 'View log', 'woo-crm-integration-for-zoho' ); ?></a></i>
					</p>
				</div>
			</div>
			<div class="mwb-content__list-item-image">
				<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/images/deals.svg' ); ?>" alt="<?php esc_html_e( 'Synced Contact Forms', 'woo-crm-integration-for-zoho' ); ?>
			</div>
		</div>

	</div>
	<!-- About list end -->

	<!-- Support section start -->
	<?php esc_html_e( 'Need help? Visit our documentation.', 'woo-crm-integration-for-zoho' ); ?>
	<?php echo esc_url( ! empty( $params['links']['doc'] ) ? $params['links']['doc'] : '' ); ?><?php esc_html_e( 'Documentation', 'woo-crm-integration-for-zoho' ); ?>
	<?php esc_html_e( 'Having trouble? Submit a support ticket.', 'woo-crm-integration-for-zoho' ); ?>
	<?php echo esc_url( ! empty( $params['links']['ticket'] ) ? $params['links']['ticket'] : '' ); ?><?php esc_html_e( 'Support', 'woo-crm-integration-for-zoho' ); ?>
	<?php esc_html_e( 'For a custom solution, reach out to us!', 'woo-crm-integration-for-zoho' ); ?>
	<?php echo esc_url( ! empty( $params['links']['contact'] ) ? $params['links']['contact'] : '' ); ?><?php esc_html_e( 'Connect', 'woo-crm-integration-for-zoho' ); ?>
	<!-- Support section end -->

</div>

