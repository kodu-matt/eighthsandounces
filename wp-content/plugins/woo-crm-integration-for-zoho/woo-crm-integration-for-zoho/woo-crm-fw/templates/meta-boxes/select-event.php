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

<div id="wciz-feed-event-section-wrapper"  class="wciz-feeds__content  wciz-content-wrap row-hide">
	<a class="wciz-feeds__header-link">
		<?php esc_html_e( 'Select Event', 'woo-crm-integration-for-zoho' ); ?>
	</a>
	<div class="wciz-feeds__meta-box-main-wrapper">
		<div class="wciz-feeds__meta-box-wrap">
			<div class="wciz-form-wrapper">
				<select id="select-feed-event" name="feed_event">
					<?php foreach ( $feed_event_options as $key => $value ) : ?>
						<option  <?php echo esc_attr( selected( $key, $feed_event ) ); ?> value="<?php echo esc_attr( $key ); ?>" >
							<?php echo esc_attr( $value ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
</div>
