<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$headings = $headings;
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<main class="mwb-cf7-integration-main">
	<header class="mwb-cf7-integration-header">
		<h1 class="mwb-cf7-integration-header__title"><?php echo esc_html( ! empty( $headings['name'] ) ? $headings['name'] : '' ); ?></h1>
		<span class="mwb-cf7-integration-version"><?php printf( 'v%s', esc_html( WOO_CRM_INTEGRATION_ZOHO_VERSION ) ); ?></span>
	</header>
	<?php if ( '1' == get_option( 'wciz_woo_zoho_authorised', false ) && '1' == get_option( 'wciz_woo_zoho_onboarding_completed', false ) ) : // phpcs:ignore ?>
		<!-- Dashboard Screen -->
		
		<?php
			/**
			 * Display Cf7 nav tab.
			 *
			 * @since 1.0.0
			 */
			do_action( 'wciz_zcf7_cf7_nav_tab' );
		?>
	<?php else : ?>
		<!-- Authorisation Screen -->
		<?php
		wp_safe_redirect( admin_url( 'admin.php?page=woo-crm-integration-for-zoho' ) );
		?>
	<?php endif; ?>
</main>
