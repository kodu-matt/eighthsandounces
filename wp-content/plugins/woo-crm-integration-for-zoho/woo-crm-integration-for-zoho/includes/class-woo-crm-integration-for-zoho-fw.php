<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */

if ( ! class_exists( 'Woo_Crm_Integration_Connect_Framework' ) ) {
	wp_die( 'Woo_Crm_Integration_Connect_Framework does not exists.' );
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Crm_Integration_For_Zoho
 * @subpackage Woo_Crm_Integration_For_Zoho/includes
 */
use Automattic\WooCommerce\Utilities\OrderUtil;
class Woo_Crm_Integration_For_Zoho_Fw extends Woo_Crm_Integration_Connect_Framework {

	/**
	 *  The instance of this class.
	 *
	 * @since    1.0.0
	 * @var      string    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * Main Woo_Crm_Integration_For_Zoho_Fw Instance.
	 *
	 * Ensures only one instance of Woo_Crm_Integration_For_Zoho_Fw is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Woo_Crm_Integration_For_Zoho_Fw - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get current mapping scenerio for current CRM connection.
	 *
	 * @since 1.0.0
	 *
	 * @return array - Current CRM to Woo mapping.
	 */
	public function getMappingDataset( $obj_type ) {

		$admin_instance = new Woo_Crm_Integration_For_Zoho_Admin( 'CRM Integration For Zoho', WOO_CRM_INTEGRATION_ZOHO_VERSION );

		$formatted_dataset = array();
		if ( !empty( $obj_type ) ) {
			foreach ( $obj_type as $key => $obj ) {
				$formatted_dataset[ $obj ] = $this->getMappingOptions( $obj );
			}
		}

		return $this->parse_labels( $formatted_dataset );
	}

	/**
	 * Get current mapping scenerio for current CRM connection.
	 *
	 * @param    mixed $form_id   CF7 Form ID.
	 * @since    1.0.0
	 * @return   array - Current CRM to CF7 mapping.
	 */
	public function getMappingDatasetCF7( $form_id = '' ) {

		if ( empty( $form_id ) ) {
			return;
		}

		$obj_type = array(
			'wpcf7',
		);

		$formatted_dataset = array();
		foreach ( $obj_type as $key => $obj ) {
			$formatted_dataset[ $obj ] = $this->getMappingOptionsCF7( $form_id );
		}

		$formatted_dataset = $this->parse_labelsCF7( $formatted_dataset );
		return $formatted_dataset;
	}

	/**
	 * Get current mapping scenerio for current CRM connection.
	 *
	 * @param string $obj_type - Woo Object type to get mapping for.
	 *
	 * @since 1.0.0
	 *
	 * @return array - Current CRM to Woo mapping.
	 */
	public function getMappingOptions( $obj_type = false ) {
		return $this->get_wp_meta( $obj_type );
	}

	/**
	 * Get current mapping scenerio for current CRM connection.
	 *
	 * @param    string $id    CF7 form ID.
	 * @since    1.0.0
	 * @return   array         Current CRM to CF7 mapping.
	 */
	public function getMappingOptionsCF7( $id = false ) {
		return $this->get_cf7_meta( $id );
	}

		/**
		 * Returns the mapping index from CF7 we require.
		 *
		 * @param    int $cf_id   The object we need index for.
		 * @since    1.0.0
		 * @return   array|bool     The current mapping step required.
		 */
	public function get_cf7_meta( $cf_id = false ) {

		if ( false == $cf_id ) { // phpcs:ignore
			return;
		}

		$fields = array();

		$id         = $cf_id;
		$form_input = get_post_meta( $id, '_form', true );

		if ( class_exists( 'WPCF7_FormTagsManager' ) ) {
			$tag_manager = WPCF7_FormTagsManager::get_instance();
			$tag_manager->scan( $form_input );
			$form_tags = $tag_manager->get_scanned_tags();

		} elseif ( class_exists( 'WPCF7_ShortcodeManager' ) ) {
			$tag_manager = WPCF7_ShortcodeManager::get_instance();
			$tag_manager->do_shortcode( $form_input );
			$form_tags = $tag_manager->get_scanned_tags();
		}

		if ( ! empty( $form_tags ) && is_array( $form_tags ) ) {

			foreach ( $form_tags as $tag ) {

				if ( is_object( $tag ) ) {
					$tag = (array) $tag;
				}

				if ( ! empty( $tag['name'] ) ) {

					$id           = str_replace( ' ', '', $tag['name'] );
					$tag['label'] = ucwords( str_replace( array( '-', '_' ), ' ', $tag['name'] ) );
					$tag['type_'] = $tag['type'];
					$tag['type']  = $tag['basetype'];
					$tag['req']   = false !== strpos( $tag['type'], '*' ) ? 'true' : '';

					if ( 'select' == $tag['type'] && ! empty( $tag['options'] ) && false !== array_search( 'multiple', $tag['options'] ) ) { // phpcs:ignore
						$tag['type'] = 'multiselect';
					}

					$fields[ $id ] = $tag;
				}
			}
			/**
			 * Filters the form fields.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $fields fields.
			 */
			$fields = apply_filters( 'wciz_zcf7_form_fields', $fields );
		}

		return ! empty( $fields ) ? $fields : false;
	}


	/**
	 * Current CF7 fields keys with Labels.
	 *
	 * @param array $dataset array for woo keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array - Current Woo meta keys with Labels to Woo keys.
	 */
	public function parse_labelsCF7( $dataset ) {

		$fields = array();
		if ( ! empty( $dataset ) && is_array( $dataset ) ) {
			foreach ( $dataset as $key => $value ) {

				if ( ! empty( $value ) && is_array( $value ) ) {
					foreach ( $value as $k => $v ) {

						// Create CF7 Index & Labels.
						$fields[ $v['name'] ] = $v['label'];
					}
				}
			}
		}
		return array(
			'CF7 Fields' => $fields,
		);
	}

	/**
	 * Get available filter options.
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	public function getFilterMappingDataset() {
		return $this->get_avialable_form_filters();
	}


	/**
	 * Feeds conditional filter options.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_avialable_form_filters() {

		$filter = array(
			'exact_match'       => esc_html__( 'Matches exactly', 'woo-crm-integration-for-zoho' ),
			'no_exact_match'    => esc_html__( 'Does not match exactly', 'woo-crm-integration-for-zoho' ),
			'contains'          => esc_html__( 'Contains (Text)', 'woo-crm-integration-for-zoho' ),
			'not_contains'      => esc_html__( 'Does not contain (Text)', 'woo-crm-integration-for-zoho' ),
			'exist'             => esc_html__( 'Exist in (Text)', 'woo-crm-integration-for-zoho' ),
			'not_exist'         => esc_html__( 'Does not Exists in (Text)', 'woo-crm-integration-for-zoho' ),
			'starts'            => esc_html__( 'Starts with (Text)', 'woo-crm-integration-for-zoho' ),
			'not_starts'        => esc_html__( 'Does not start with (Text)', 'woo-crm-integration-for-zoho' ),
			'ends'              => esc_html__( 'Ends with (Text)', 'woo-crm-integration-for-zoho' ),
			'not_ends'          => esc_html__( 'Does not end with (Text)', 'woo-crm-integration-for-zoho' ),
			'less_than'         => esc_html__( 'Less than (Text)', 'woo-crm-integration-for-zoho' ),
			'greater_than'      => esc_html__( 'Greater than (Text)', 'woo-crm-integration-for-zoho' ),
			'less_than_date'    => esc_html__( 'Less than (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'greater_than_date' => esc_html__( 'Greater than (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'equal_date'        => esc_html__( 'Equals (Date/Time)', 'woo-crm-integration-for-zoho' ),
			'empty'             => esc_html__( 'Is empty', 'woo-crm-integration-for-zoho' ),
			'not_empty'         => esc_html__( 'Is not empty', 'woo-crm-integration-for-zoho' ),
		);
		/**
		 * Filters the condition filters.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $filter filters.
		 */
		return apply_filters( 'wciz_zoho_cf7_condition_filter', $filter );
	}

	/**
	 * Returns the mapped objects counts.
	 *
	 * @param string|bool $obj_type The Object post type.
	 *
	 * @since 1.0.0
	 *
	 */
	public function getSyncedObjectsCount( $obj_type = false ) {

		if ( empty( $obj_type ) ) {
			echo '0';
		}

		$feed_ids = $this->get_object_feed( $obj_type );

		$order_query_result = array();
		$count              = 0;
		$feed_query         = '';

		global $wpdb;

		if ( ! empty( $feed_ids ) ) {

			if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
				if ( 'Products' == $obj_type ) {
					$post_meta = $wpdb->prefix . 'postmeta';
	
					$sql = "SELECT DISTINCT `meta_value` as zoho_id FROM {$post_meta} WHERE ";
				} else {
					$order_meta = $wpdb->prefix . 'wc_orders_meta';
	
					$sql = "SELECT DISTINCT `meta_value` as zoho_id FROM {$order_meta} WHERE ";
				}
			} else {
				$post_meta = $wpdb->prefix . 'postmeta';
	
				$sql = "SELECT DISTINCT `meta_value` as zoho_id FROM {$post_meta} WHERE ";
			}

			if ( is_array( $feed_ids ) ) {

				foreach ( $feed_ids as $key => $feed_id ) {
					$wciz_metakey = 'wciz_zoho_feed_' . $feed_id . '_association';
					$mwb_metakey = 'mwb_zoho_feed_' . $feed_id . '_association';
					$feed_query  = "`meta_key` =  '" . $mwb_metakey . "' OR `meta_key` =  '" . $wciz_metakey . "'";

					if ( empty( $feed_query ) ) {
						$feed_query = "`meta_key` = '" . $mwb_metakey . "' OR `meta_key` =  '" . $wciz_metakey . "'";
					} else {
						$feed_query .= "`meta_key` = '" . $mwb_metakey . "' OR `meta_key` =  '" . $wciz_metakey . "'";
					}
				}
			} else {

				$mwb_metakey = 'mwb_zoho_feed_' . $feed_ids . '_association';
				$wciz_metakey = 'wciz_zoho_feed_' . $feed_ids . '_association';
				$feed_query  = "`meta_key` =  '" . $mwb_metakey . "' OR `meta_key` =  '" . $wciz_metakey . "'";
			}

			$sql .= $feed_query;
			$sql .= 'AND `meta_value` != ""';

			if ( ! empty( $sql ) ) {

				$order_query_result = wciz_woo_zoho_get_query_results( $sql );
			}

			$count = ! empty( $order_query_result ) ? count( $order_query_result ) : 0;
			// Get count from user meta.
			if ( 'Contacts' == $obj_type ) {

				$user_meta = $wpdb->prefix . 'usermeta';

				$user_meta_sql = "SELECT DISTINCT `meta_value` as zoho_id FROM {$user_meta} WHERE ";

				$user_meta_sql .= $feed_query;

				$user_meta_sql .= 'AND `meta_value` != ""';

				$user_query_result = wciz_woo_zoho_get_query_results( $user_meta_sql );

				$count = $count + count( $user_query_result );

				if ( ! empty( $order_query_result ) ) {

					$order_zoho_ids  = array_column( $order_query_result, 'zoho_id' );
					$user_zoho_ids   = array_column( $user_query_result, 'zoho_id' );
					$unique_zoho_ids = array_unique( array_merge( $order_zoho_ids, $user_zoho_ids ) );
					$count           = count( $unique_zoho_ids );

				}
			}
		}

		echo esc_html( $count );
	}

	/**
	 * Get selected object api fields for filtering.
	 *
	 * @param    string $selected_object   selected object.
	 * @since 1.0.0
	 *
	 * @return array - Current CRM to Woo mapping.
	 */
	public function getObjectFilteringField( $selected_object = '' ) {

		$formatted_dataset        = array();
		$crm_integration_zoho_api = new Woo_Crm_Integration_Zoho_Api();
		$object_fields            = $crm_integration_zoho_api->get_module_fields( $selected_object, false );
		if ( empty( $object_fields['fields'] ) || ! is_array( $object_fields['fields'] ) ) {
			return;
		}
		$object_fields = $object_fields['fields'];
		$api_name      = array_column( $object_fields, 'api_name' );

		$formatted_dataset[ $selected_object ] = $api_name;

		return $this->parse_labels( $formatted_dataset );
	}

	// End of class.
}
