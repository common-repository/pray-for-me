<?php

/**
 * Plugin Name: Pray For Me
 * Plugin URI: http://flamingsupport.com/
 * Description: Adds ability for users to submit prayer requests.
 * Version: 1.0.4
 * Author: Project Caruso
 * Author URI: http://flamingsupport.com/
 * License: MIT
 */

define( 'CARUSO_PRAYER_PLUGIN_PATH', rtrim( plugin_dir_path( __FILE__ ), '\\/' ) . '/' );
define( 'CARUSO_PRAYER_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '\\/' ) . '/' );
define( 'CARUSO_PRAYER_PLUGIN_VERSION', '1.0.3' );

define( 'CARUSO_PRAYER_POST_TYPE', 'prayer' );
define( 'CARUSO_PRAYER_ACTION_FORM_SUBMITTED', 'caruso_prayer_action_form_submitted' );
define( 'CARUSO_PRAYER_ACTION_FORM_SAVED', 'caruso_prayer_action_form_saved' );
define( 'CARUSO_PRAYER_ACTION_ADD_FORM_FIELDS', 'caruso_prayer_action_add_form_fields' );
define( 'CARUSO_PRAYER_ACTION_SETTINGS_SAVE', 'caruso_prayer_action_settings_save' );
define( 'CARUSO_PRAYER_ACTION_BULK_ACTIONS', 'caruso_prayer_action_bulk_actions' );
define( 'CARUSO_PRAYER_FILTER_SUBMISSION_IS_VALID', 'caruso_prayer_filter_submission_is_valid' );
define( 'CARUSO_PRAYER_FILTER_SUBMITTED_HEADER', 'caruso_prayer_filter_submitted_header' );
define( 'CARUSO_PRAYER_FILTER_SUBMITTED_MESSAGE', 'caruso_prayer_filter_submitted_message' );
define( 'CARUSO_PRAYER_FILTER_FORM_HEADER', 'caruso_prayer_filter_form_header' );
define( 'CARUSO_PRAYER_FILTER_FORM_MESSAGE', 'caruso_prayer_filter_form_message' );
define( 'CARUSO_PRAYER_FILTER_SETTINGS_TABS', 'caruso_prayer_filter_settings_tabs' );
define( 'CARUSO_PRAYER_FILTER_BULK_ACTIONS', 'caruso_prayer_filter_bulk_actions' );

class CarusoPrayerPlugin {
	protected static $_instance = null;

	public static function &getInstance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		$this->register_post_type();
		$this->register_post_status();

		require_once( CARUSO_PRAYER_PLUGIN_PATH . 'functions.php' );
		require_once( CARUSO_PRAYER_PLUGIN_PATH . 'classes/form.php' );
		require_once( CARUSO_PRAYER_PLUGIN_PATH . 'classes/shortcodes.php' );
		require_once( CARUSO_PRAYER_PLUGIN_PATH . 'classes/settings.php' );

		add_shortcode( 'prayer_form', 'CarusoPrayerPluginShortcodes::prayer_form' );

		if ( is_admin() ) {
			require_once( CARUSO_PRAYER_PLUGIN_PATH . 'classes/list-table.php' );
			require_once( CARUSO_PRAYER_PLUGIN_PATH . 'controllers/admin.php' );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		}
	}

	public function activate() {
		$this->register_post_type();

		flush_rewrite_rules();
	}

	public function deactivate() {
		flush_rewrite_rules();
	}

	public static function uninstall() {
		$query = new WP_Query( array(
			'post_type' => CARUSO_PRAYER_POST_TYPE,
			'nopaging'  => true,
		) );

		while ( $query->have_posts() ) {
			$query->the_post();
			wp_delete_post( get_the_ID(), true );
		}

		wp_reset_postdata();

		flush_rewrite_rules();
	}

	public function admin_menu() {
		add_menu_page(
			__( 'Pray For Me', 'caruso-prayer-plugin' ),
			__( 'Pray For Me', 'caruso-prayer-plugin' ),
			'edit_posts',
			'caruso_prayer_plugin',
			'CarusoPrayerPluginAdminController::list_action',
			 CARUSO_PRAYER_PLUGIN_URL . 'resources/images/icon.png' );
			 
		add_submenu_page( 'caruso_prayer_plugin', __( 'Settings', 'caruso-prayer-plugin' ), __( 'Settings', 'caruso-prayer-plugin' ), 'edit_posts', 'caruso_prayer_plugin_settings', 'CarusoPrayerPluginAdminController::settings_action' );
	}

	public function meta_boxes( $post_type ) {
		if ( $post_type === CARUSO_PRAYER_POST_TYPE ) {
			add_meta_box( 'caruso-prayer-meta-box', 'Prayer Request', 'CarusoPrayerPluginAdminController::display_meta_box' );
		}
	}

	public function register_post_type() {
		register_post_type( CARUSO_PRAYER_POST_TYPE, array(
			'labels'             => array(
				'name'               => _x( 'Prayers', 'post type general name', 'caruso-prayer-plugin' ),
				'singular_name'      => _x( 'Prayer', 'post type singular name', 'caruso-prayer-plugin' ),
				'menu_name'          => _x( 'Prayers', 'admin menu', 'caruso-prayer-plugin' ),
				'name_admin_bar'     => _x( 'Prayer', 'add new on admin bar', 'caruso-prayer-plugin' ),
				'add_new'            => _x( 'Add Prayer', 'book', 'caruso-prayer-plugin' ),
				'add_new_item'       => __( 'Add New Prayer', 'caruso-prayer-plugin' ),
				'new_item'           => __( 'New Prayer', 'caruso-prayer-plugin' ),
				'edit_item'          => __( 'Edit Prayer', 'caruso-prayer-plugin' ),
				'view_item'          => __( 'View Prayer', 'caruso-prayer-plugin' ),
				'all_items'          => __( 'All Prayers', 'caruso-prayer-plugin' ),
				'search_items'       => __( 'Search Prayers', 'caruso-prayer-plugin' ),
				'parent_item_colon'  => __( 'Parent Prayers:', 'caruso-prayer-plugin' ),
				'not_found'          => __( 'No prayers found.', 'caruso-prayer-plugin' ),
				'not_found_in_trash' => __( 'No prayers found in Trash.', 'caruso-prayer-plugin' )
			),
			'description'        => __( 'Manage prayers.', 'caruso-prayer-plugin' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => CARUSO_PRAYER_POST_TYPE ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor' ),
		) );
	}

	function register_post_status() {
		register_post_status( 'archived', array(
			'label'                     => __( 'Archived', 'caruso-prayer-plugin' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Archived <span class="count">(%s)</span>', 'Archived <span class="count">(%s)</span>' ),
		) );
	}
}

$prayerPlugin = CarusoPrayerPlugin::getInstance();
add_action( 'init', array( $prayerPlugin, 'initialize' ) );
register_activation_hook( __FILE__, array( $prayerPlugin, 'activate' ) );
register_deactivation_hook( __FILE__, array( $prayerPlugin, 'deactivate' ) );
register_uninstall_hook( __FILE__, 'CarusoPrayerPlugin::uninstall' );
