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
<div id="wciz-data-sync" class="wciz-support wciz-content-wrap wciz-tabcontent">
	<section class="wciz-iwsa_support-head">
		<h3 class="wciz-iwsa_support-head-title">
			<?php esc_html_e( 'All Data Synchronization', 'woo-crm-integration-for-zoho' ); ?>
		</h3>
		<p class="wciz-iwsa_support-head-desc">
			<?php esc_html_e( 'Configure auto-sync and manual sync options', 'woo-crm-integration-for-zoho' ); ?>
		</p>
	</section>
	<div class="wciz-content__bottom-list-wrap">

		<div id="myProgress">
			<div id="myBar"></div>
			<p id="myBarText"></p>
		</div>

		<ul class="wciz-about__list wciz-bulk-data-center-wrap">
			<?php foreach ( $settings as $key => $attr ) : ?>
				<li class="wciz-about__list-item">
					<div class="wciz-about__list-item-text-1" data-form="
						<?php
						echo esc_html(
							$attr['button']['class'] ?
							$attr['button']['class'] :
							''
						);
						?>
						">
						<h3 class="wciz-about__list-item-heading">
							<?php
							echo esc_html(
								$attr['title'] ?
								$attr['title'] :
								false
							);
							?>
						</h3>
						<p>
							<?php
							echo esc_html(
								$attr['description'] ?
								$attr['description'] :
								false
							);
							?>
						</p>
					</div>
					<div class="wciz-about__list-item-btn">
						<a 
						<?php
						echo esc_attr(
							$attr['button']['newtab'] ?
							'target=_blank' :
							false
						);
						?>
						href="
						<?php
						echo esc_attr(
							$attr['button']['href'] ?
							$attr['button']['href'] :
							'javascript:void(0);'
						);
						?>
						" class="wciz-sync-start" data-form="
						<?php
						echo esc_html(
							$attr['button']['class'] ?
							$attr['button']['class'] :
							''
						);
						?>
						">                     
						<img src="<?php echo esc_url( WOO_CRM_INTEGRATION_ZOHO_URL ); ?>assets/src/images/down.svg">
						</a>                        
					</div>
					<div class="wciz-about__list-item-text is_hidden">
						<form class="wciz-sync-process-form form_<?php echo esc_html( $attr['button']['class'] ? $attr['button']['class'] : '' ); ?>" method="post">
							<?php
							$dependent_setting = array();

							$dependent_setting[] = array(
								'title'   => esc_html__(
									'Choose WC Object Type',
									'woo-crm-integration-for-zoho'
								),
								'type'    => 'select',
								'options' => $options,
								'class'   => 'wciz-crm-select-sync-fields',
								'id'      => 'wciz-crm-select-woo-object',
								'value'   => '',
							);

							$dependent_setting[] = array(
								'title'   => esc_html__(
									'Select Feed',
									'woo-crm-integration-for-zoho'
								),
								'type'    => 'select',
								'options' => array(
									'' => esc_html__( 'Choose option', 'woo-crm-integration-for-zoho' ),
								),
								'class'   => 'wciz-crm-select-sync-fields',
								'id'      => 'wciz-crm-select-object-feed',
								'value'   => '',
							);

							if ( 'bulk_data_sync' == $attr['button']['class']) {

								$dependent_setting[] = array(
									'title'   => esc_html__(
										'From Date',
										'woo-crm-integration-for-zoho'
									),
									'type'    => 'date',
									'class'   => 'wciz-crm-select-sync-fields',
									'id'      => 'wciz-crm-select-woo-object-date-from',
									'custom_attributes' => array(
										'max' => gmdate('Y-m-d'),
									),
									'value'   => '',
								);
								$dependent_setting[] = array(
									'title'   => esc_html__(
										'To Date',
										'woo-crm-integration-for-zoho'
									),
									'type'    => 'date',
									'class'   => 'wciz-crm-select-sync-fields',
									'id'      => 'wciz-crm-select-woo-object-date-to',
									'custom_attributes' => array(
										'max' => gmdate('Y-m-d'),
									),
									'value'   => '',
								);
							}
							?>
							<?php foreach ( $dependent_setting as $key => $setting ) : ?>
								<div class="wciz-sync-form__field">
									<?php woocommerce_admin_fields( array( $setting ) ); ?>
								</div>
							<?php endforeach; ?>
							<div class="wciz-intro__button">
								<button type="button" class="wciz-btn wciz-btn--filled wciz-start-sync-action is_hidden" sync_type="<?php echo esc_html( $attr['button']['class'] ? $attr['button']['class'] : '' ); ?>" >
									<?php esc_html_e( 'Synchronize', 'woo-crm-integration-for-zoho' ); ?>
								</button>
							</div>
						</form>
						<div class="wciz-lists__progress">
							<?php $progress_id = 'bulk-data-sync' === $attr['button']['class'] ? 'bulk_data_sync' : 'one_click_sync'; ?>
							<div id="wciz-lists__progress-bar-<?php echo esc_attr( $progress_id ); ?>" class="wciz-lists__progress-bar" role="progressbar" style="width: 1%;"></div>
						</div>
						<div class="wciz-sync-result">
							<span class="wciz-lists__progress-result"></span>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
