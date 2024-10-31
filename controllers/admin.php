<?php

class CarusoPrayerPluginAdminController {
	public static function settings_action() {
		$settings = CarusoPrayerPluginSettings::get_instance();
		$tabs     = CarusoPrayerPluginSettings::get_tabs();

		if ( $settings->is_posted() ) {
			$settings->set_from_array($_REQUEST);

			if ( $settings->save() ) {
				$success = __( 'Successfully saved the settings.' , 'caruso-prayer-plugin' );
			} else {
				$error = __( 'Unable to save the settings.' , 'caruso-prayer-plugin' );
			}
		}

		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'caruso-prayer-admin-script', CARUSO_PRAYER_PLUGIN_URL . 'resources/scripts/admin.js' );
		wp_enqueue_style( 'caruso-prayer-ui', CARUSO_PRAYER_PLUGIN_URL . 'resources/styles/jquery-ui.min.css' );
		wp_enqueue_style( 'caruso-prayer-admin-style', CARUSO_PRAYER_PLUGIN_URL . 'resources/styles/admin.css' );

		include( CARUSO_PRAYER_PLUGIN_PATH . 'views/admin/settings.php' );
	}

	public static function list_action() {
		$table = new CarusoPrayerPluginListTable(array(
			'singular'  => 'prayer',
			'plural'    => 'prayers',
			'ajax'      => false
		));
		$table->prepare_items();

		include( CARUSO_PRAYER_PLUGIN_PATH . 'views/admin/prayer-list.php');
	}

	public static function display_meta_box($post) {
		if ( $post->post_type == CARUSO_PRAYER_POST_TYPE ) {
			wp_enqueue_style( 'caruso-prayer-admin-style', CARUSO_PRAYER_PLUGIN_URL . 'resources/styles/admin.css' );

			include( CARUSO_PRAYER_PLUGIN_PATH . 'views/admin/prayer-meta.php' );
		}
	}
}
