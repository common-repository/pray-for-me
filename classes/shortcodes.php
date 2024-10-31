<?php

class CarusoPrayerPluginShortcodes {
	public static function prayer_form() {
		$settings = CarusoPrayerPluginSettings::get_instance();
		$form     = new CarusoPrayerPluginForm( $_REQUEST );

		if ( $form->is_submitted() ) {
			if ( $form->is_valid() && ( $post = $form->save() ) ) {
				include( CARUSO_PRAYER_PLUGIN_PATH . 'views/form/confirmed.php' );
				return;
			}
		}

		wp_enqueue_style( 'caruso-prayer-plugin-style', CARUSO_PRAYER_PLUGIN_URL . 'resources/styles/style.css' );

		include( CARUSO_PRAYER_PLUGIN_PATH . 'views/form/form.php' );
		return;
	}
}
