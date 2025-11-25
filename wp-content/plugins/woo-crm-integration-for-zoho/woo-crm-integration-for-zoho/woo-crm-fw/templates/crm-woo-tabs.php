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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	wp_die();
}
?>

<?php if ( ! empty( $tabs ) && is_array( $tabs ) ) : ?>
<div class="wciz-tab__header wciz-content-wrap wciz-dash-tabs">
	<ul class="wciz-tab__header-list">
		<?php foreach ( $tabs as $key => $label ) : ?>
			<li class="wciz-tab__header-list-item">
				<?php $is_active = 'wciz-dashboard' === $key ? 'active' : ''; ?>
				<a href="#<?php echo esc_html( $key ); ?>" class="wciz-tab-link <?php echo esc_html( $key ); ?>-tab-link <?php echo esc_html( $is_active ); ?>" onclick="opentab( event, '<?php echo esc_html( $key ); ?>')"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
