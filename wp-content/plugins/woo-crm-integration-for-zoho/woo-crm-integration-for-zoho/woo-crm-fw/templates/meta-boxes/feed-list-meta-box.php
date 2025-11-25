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

?>

<div id="wciz-feed-list-meta-box-wrap">
	<div class="wciz-feed-list-wrap">
		<?php foreach ( $feed_list as $key => $feed_item ) : ?>
		<div class="wciz-feed-list-item">
			<div class="item-title">
				<?php echo esc_html( $feed_item['feed_title'] ); ?>
			</div>
			<div class="item-meta">
				<span><?php echo isset( $feed_item['item_type'] ) ? esc_html( $feed_item['item_type'] ) : ''; ?></span>
				<span><?php echo isset( $feed_item['item_id'] ) ? esc_html( $feed_item['item_id'] ) : ''; ?></span>
			</div>
			<div class="item-data">
				<span class="item-id-link"><a href="<?php echo isset( $feed_item['data_href'] ) ? esc_html( $feed_item['data_href'] ) : ''; ?> " target="_blank"> <?php echo esc_html( $feed_item['reference_id'] ); ?> </a></span>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
