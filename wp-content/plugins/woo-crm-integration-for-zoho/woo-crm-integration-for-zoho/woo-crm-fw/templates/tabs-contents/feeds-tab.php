<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the feeds listing aspects of the plugin.
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

<div id="mwb-feeds" class="mwb-cf7-integration__feedlist-wrap">

	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-cf7_integration_logo-crm">
			<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/icons/zoho-logo.png' ); ?>" alt="<?php esc_html_e( 'ZOHO', 'woo-crm-integration-for-zoho' ); ?>">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'admin/icons/contact-form.svg' ); ?>" alt="<?php esc_html_e( 'CF7', 'woo-crm-integration-for-zoho' ); ?>">
		</div>
		<div class="mwb-cf7-integration__filterfeed">
			<Select class="filter-feeds-by-form" name="filter-feeds-by-form" >
				<option value="-1"><?php esc_html_e( 'Select CF7 form', 'woo-crm-integration-for-zoho' ); ?></option>
				<option value="all"><?php esc_html_e( 'All Feeds', 'woo-crm-integration-for-zoho' ); ?></option>
				<?php if ( ! empty( $wpcf7 ) && is_array( $wpcf7 ) ) : ?>
					<?php foreach ( $wpcf7 as $cf_post => $val ) : ?>
						<option value="<?php echo esc_attr( $val->ID ); ?>"><?php echo esc_html( $val->post_title ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</Select>
		</div>
	</div>

	<ul class="mwb-cf7-integration__feed-list" id="mwb-<?php echo esc_attr( 'zoho' ); ?>-cf7_independent">
		<?php
		$feed_module = new Woo_Crm_Integration_For_Zoho_Cpt();
		foreach ( $feeds as $key => $feed ) :


			$feed_title     = $feed->post_title;
			$_status        = get_post_status( $feed->ID );
			$active         = ( 'publish' === $feed->post_status ) ? 'yes' : 'no';
			$edit_link      = get_edit_post_link( $feed->ID );
			$cf7_from       = $feed_module->get_feed_data( $feed->ID, 'wciz_zcf7_form', '-' );
			$crm_object     = $feed_module->get_feed_data( $feed->ID, 'wciz_zcf7_object', '-' );
			$primary_field  = $feed_module->get_feed_data( $feed->ID, 'wciz_zcf7_primary_field', '-' );
			$filter_applied = $feed_module->get_feed_data( $feed->ID, 'wciz_zcf7_enable_filters', '-' );
			?>
			<li class="mwb-cf7-integration__feed-row">
				<div class="mwb-cf7-integration__left-col">
					<h3 class="mwb-about__list-item-heading">
						<?php echo esc_html( $feed_title ); ?>
					</h3>
					<div class="mwb-feed-status__wrap">
						<p class="mwb-feed-status-text_<?php echo esc_attr( $feed->ID ); ?>" ><strong><?php echo 'publish' == $_status ? esc_html__( 'Active', 'woo-crm-integration-for-zoho' ) : esc_html__( 'Sandbox', 'woo-crm-integration-for-zoho' ); // phpcs:ignore ?></strong></p>
						<p><input type="checkbox" class="mwb-feed-status" value="publish" <?php checked( 'publish', $_status ); ?> feed-id=<?php echo esc_attr( $feed->ID ); ?> ></p>
					</div>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Form : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( get_the_title( $cf7_from ) ); ?></span>   
					</p>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Object : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( $crm_object ); ?></span> 
					</p>
					<p> 
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Primary Key : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo esc_html( $primary_field ); ?></span> 
					</p>
					<p>
						<span class="mwb-about__list-item-sub-heading"><?php esc_html_e( 'Conditions : ', 'woo-crm-integration-for-zoho' ); ?></span>
						<span><?php echo 'yes' != $filter_applied ? esc_html__( '-', 'woo-crm-integration-for-zoho' ) : esc_html__( 'Applied', 'woo-crm-integration-for-zoho' ); // phpcs:ignore ?></span> 
					</p>
				</div>
				<div class="mwb-cf7-integration__right-col">
					<a href="<?php echo esc_url( $edit_link ); ?>">
						<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/edit.svg' ); ?>" alt="<?php esc_html_e( 'Edit feed', 'woo-crm-integration-for-zoho' ); ?>">
					</a>
					<div class="mwb-cf7-integration__right-col1">
						<a href="javascript:void(0)" class="mwb_cf7_integration_trash_feed" feed-id="<?php echo esc_html( $feed->ID ); ?>">
							<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL . 'assets/src/images/trash.svg' ); ?>" alt="<?php esc_html_e( 'Trash feed', 'woo-crm-integration-for-zoho' ); ?>">
						</a>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<div class="mwb-about__list-item mwb-about__list-add">            
		<div class="mwb-about__list-item-btn">
			<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=wciz_cf7_zoho_feeds' ) ); ?>" class="mwb-btn mwb-btn--filled">
				<?php esc_html_e( 'Add Feeds', 'woo-crm-integration-for-zoho' ); ?>
			</a>
		</div>
	</div>

	<?php 
		/**
		 * Trigger associated Feeds.
		 *
		 * @since 1.0.0
		 *
		 */
		do_action( 'wciz_zcf7_display_dependent_feeds' ); 
	?>
</div>
