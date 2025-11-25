<?php
/**
 * Add meta box section view.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
?>

<div id="wciz-contact-user-type-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Guest / Registered Users', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<label><?php esc_html_e( 'Use for guest users only', 'woo-crm-integration-for-zoho' ); ?></label>
				<input type="checkbox" class="contact-only-guest-feed" name="contact_only_guest_feed" value="yes" <?php checked( $contact_only_guest_feed, 'yes' ); ?>>
				<p class="wciz-description">
					<?php
						esc_html_e( 'Check if this feed should be used, only to sync guest orders contact data.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
			</div>
			<?php $hide_class = ( 'no' == $contact_only_guest_feed ) ? 'hide' : ''; ?>
			<div class="wciz-form-wrapper wciz-form-wrapper-user-type <?php echo esc_attr( $hide_class ); ?>">
				<label><?php esc_html_e( 'Select feed for registered user syncing' , 'woo-crm-integration-for-zoho'); ?></label>
				<select id="contact-customer-feed-select" class="" name="contact_customer_feed">
					<option value=""><?php esc_html_e( '--Select Feed--' , 'woo-crm-integration-for-zoho'); ?></option>
					<?php foreach ($contact_feeds as $c_key => $c_feed) : ?>
						<option value="<?php echo esc_attr($c_feed->ID); ?>"
							<?php selected( $c_feed->ID, $contact_customer_feed ); ?>>
							<?php echo esc_html( $c_feed->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="wciz-description">
					<?php
						esc_html_e( 'Select other contact feed which should be used to sync, registered users data.', 'woo-crm-integration-for-zoho' );
						echo '<br>';
						esc_html_e( 'If you do not see any option to select, please create a contact feed, mapping user meta fields and selecting WP user create and update option as trigger event.', 'woo-crm-integration-for-zoho' );
					?>
				</p>
	
			</div>
		</div>
	</div>
</div>
