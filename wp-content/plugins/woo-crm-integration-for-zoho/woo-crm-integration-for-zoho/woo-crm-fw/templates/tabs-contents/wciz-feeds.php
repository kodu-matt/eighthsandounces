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
<div id="wciz-feeds" class="wciz-content-wrap wciz-tabcontent">
	<section class="wciz-iwsa_support-head">
		<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'Feed Settings', 'woo-crm-integration-for-zoho' ); ?>
		</h3>
		<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Configure feed format and rules', 'woo-crm-integration-for-zoho' ); ?>
		</p>
	</section>
	<ul class="wciz-about__list">
			<?php
			foreach ( $feeds as $key => $feed ) :

				$feed_title     = $feed->post_title;
				$active         = ( 'publish' === $feed->post_status ) ? 'yes' : 'no';
				$edit_link      = get_edit_post_link( $feed->ID );
				$feed_event     = $cpt_instance->get_feed_data( $feed->ID, 'feed_event', '' );
				$crm_object     = $cpt_instance->get_feed_data( $feed->ID, 'crm_object', '' );
				$primary_field  = $cpt_instance->get_feed_data( $feed->ID, 'primary_field', '' );
				$filter_applied = $cpt_instance->get_feed_data( $feed->ID, 'wciz-zoho-enable-filters', '-' );
				$default_feed   = $cpt_instance->get_feed_data( $feed->ID, 'default_feed', '' );

				$feed_event_label = isset( $feed_options[ $feed_event ] ) ? $feed_options[ $feed_event ] : '' ;
				?>
				<li class="wciz-about__list-item">
					<div class="wciz-about__list-item-text">
						<h3 class="wciz-about__list-item-heading">
							<?php echo esc_html( $feed_title ); ?>
						</h3>
						<p>  
							<span class="wciz-about__list-item-sub-heading">
								<?php esc_html_e( 'Active', 'woo-crm-integration-for-zoho' ); ?>  
							</span>
							<span><input type="checkbox" feed_id="<?php echo esc_attr( $feed->ID ); ?>" class="wciz-crm-feed-chk" name="wciz_crm_feed_active" value="yes" <?php echo esc_attr( checked( $active, 'yes' ) ); ?> /></span>  	
						</p>
						<p>
							<span class="wciz-about__list-item-sub-heading"> <?php esc_html_e( 'Object', 'woo-crm-integration-for-zoho' ); ?> </span>
							<span><?php echo esc_html( $crm_object ); ?></span>   
						</p>
						<p>
							<span class="wciz-about__list-item-sub-heading"> <?php esc_html_e( 'Event', 'woo-crm-integration-for-zoho' ); ?> </span>
							<span><?php echo esc_html( $feed_event_label ); ?></span> 
						</p>
						<p> 
							<span class="wciz-about__list-item-sub-heading"> <?php esc_html_e( 'Primary Field', 'woo-crm-integration-for-zoho' ); ?></span>
							<span><?php echo esc_html( $primary_field ); ?></span> 
						</p>
						<p>
							<span class="wciz-about__list-item-sub-heading"><?php esc_html_e( 'Conditions : ', 'woo-crm-integration-for-zoho' ); ?></span>
							<span><?php echo esc_html( 'yes' !== $filter_applied ? '-' : esc_html__( 'Applied', 'woo-crm-integration-for-zoho' ) ); // phpcs:ignore ?></span> 
						</p>
					</div>
					<div class="wciz-about__list-item-btn">
						<a class="wciz-edit-feed-btn"
							href="<?php echo esc_url( $edit_link ); ?>">
							<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/edit.svg">
						</a>
						<?php if ( ! $default_feed ) : ?>
						<a class="wciz-delete-feed-btn"
							href="#" feed_id="<?php echo esc_attr( $feed->ID ); ?>">
							<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/trash.svg">
						</a>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="wciz-about__list-item wciz-about__list-add">            
			<div class="wciz-about__list-item-btn">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=wciz_crm_feed' ) ); ?>" class="wciz-btn wciz-btn--filled">Add Feeds</a>
			</div>
		</div>
</div>
