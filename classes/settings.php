<?php

/**
 * Class CarusoPrayerPluginSettings
 *
 * This class is responsible for creating, saving, and deleting the general settings,
 * along with managing custom settings that are added to the plugin.
 */
class CarusoPrayerPluginSettings {

	/**
	 * The title of the prayer request form.
	 *
	 * @var string
	 */
	public $form_title;

	/**
	 * Introduction paragraph that is above the prayer request form.
	 *
	 * @var string
	 */
	public $form_message;

	/**
	 * The title that will appear after the prayer request has been submitted.
	 *
	 * @var string
	 */
	public $confirm_title;

	/**
	 * Thank you message that will appear once the prayer request form is submitted.
	 *
	 * @var string
	 */
	public $confirm_message;

	/**
	 * Tells whether we are showing the first name field on the form.
	 *
	 * @var string
	 */
	public $show_first_name;

	/**
	 * Tells whether we are showing the last name field on the form.
	 *
	 * @var string
	 */
	public $show_last_name;

	/**
	 * Tells whether we are showing the email field on the form.
	 *
	 * @var string
	 */
	public $show_email;

	/**
	 * Tells whether the first name on the form is a required field.
	 *
	 * @var bool
	 */
	public $require_first_name;

	/**
	 * Tells whether the last name on the form is a required field.
	 *
	 * @var string
	 */
	public $require_last_name;

	/**
	 * Tells whether the email address on the form is a required field.
	 *
	 * @var string
	 */
	public $require_email;

	/**
	 * Tells whether the prayers should be published immediately.
	 *
	 * @var bool
	 */
	public $publish_immediately;

	/**
	 * Singleton instance of the settings class.
	 *
	 * Always use get_instance() to retrieve the settings class.
	 *
	 * @var CarusoPrayerPluginSettings
	 */
	protected static $_instance = null;

	/**
	 * Returns the singleton instance of this class.
	 *
	 * @return CarusoPrayerPluginSettings
	 */
	public static function &get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * CarusoPrayerPluginSettings constructor.
	 *
	 * Creates a new instance of the settings class.
	 */
	public function __construct() {
		$settings = get_option( 'caruso-prayer-plugin' , array() );
		$this->set_from_array($settings);
	}

	/**
	 * Check to see if the form is posted.
	 */
	public function is_posted() {
		return isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'Save Settings';
	}

	/**
	 * Returns all of the tabs that are displayed on the settings page.
	 *
	 * Every tab must have a name, key and callback.
	 *
	 * @return array
	 */
	public static function get_tabs() {
		$tab_list = apply_filters( CARUSO_PRAYER_FILTER_SETTINGS_TABS, array() );
		if ( !is_array( $tab_list ) ) {
			$tab_list = array();
		}

		$tabs = array();
		foreach ( $tab_list as $tab ) {
			if ( ! isset( $tab['name'] ) || ! is_string( $tab['name'] ) ) {
				break;
			}

			if ( ! isset( $tab['key'] ) || ! is_string( $tab['key'] ) ) {
				break;
			}

			if ( ! isset( $tab['callback'] ) || ! is_callable($tab['callback'] ) ) {
				break;
			}

			$tabs[] = $tab;
		}

		return $tabs;
	}

	/**
	 * Sets all of the values for the general settings.
	 *
	 * @param array $settings
	 */
	public function set_from_array( array $settings = array() ) {
		$this->form_title          = isset( $settings['form_title'] ) ?  stripslashes($settings['form_title']) : __( 'Submit a Prayer Request', 'caruso-prayer-plugin' );
		$this->form_message        = isset( $settings['form_message'] ) ?  stripslashes($settings['form_message']) : __( 'Fill out the form below to submit a prayer request.', 'caruso-prayer-plugin' );
		$this->confirm_title       = isset( $settings['confirm_title'] ) ?  stripslashes($settings['confirm_title']) : __( 'Thank You for the Request.', 'caruso-prayer-plugin' );
		$this->confirm_message     = isset( $settings['confirm_message'] ) ?  stripslashes($settings['confirm_message']) : __( 'We will pray for you and your loved ones.', 'caruso-prayer-plugin' );
		$this->show_first_name     = isset( $settings['show_first_name'] ) ?  boolval($settings['show_first_name']) : false;
		$this->show_last_name      = isset( $settings['show_last_name'] ) ?  boolval($settings['show_last_name']) : false;
		$this->show_email          = isset( $settings['show_email'] ) ?  boolval($settings['show_email']) : false;
		$this->require_first_name  = isset( $settings['require_first_name'] ) ?  boolval($settings['require_first_name']) : false;
		$this->require_last_name   = isset( $settings['require_last_name'] ) ?  boolval($settings['require_last_name']) : false;
		$this->require_email       = isset( $settings['require_email'] ) ?  boolval($settings['require_email']) : false;
		$this->publish_immediately = isset( $settings['publish_immediately'] ) ?  boolval($settings['publish_immediately']) : false;
	}

	/**
	 * Saves all of the general settings for this plugin.
	 *
	 * @return bool
	 */
	public function save() {
		$settings     = get_option( 'caruso-prayer-plugin', array() );
		$new_settings = array(
			'form_title'          => $this->form_title,
			'form_message'        => $this->form_message,
			'confirm_title'       => $this->confirm_title,
			'confirm_message'     => $this->confirm_message,
			'show_first_name'     => $this->show_first_name,
			'show_last_name'      => $this->show_last_name,
			'show_email'          => $this->show_email,
			'require_first_name'  => $this->require_first_name,
			'require_last_name'   => $this->require_last_name,
			'require_email'       => $this->require_email,
			'publish_immediately' => $this->publish_immediately,
		);

		do_action( CARUSO_PRAYER_ACTION_SETTINGS_SAVE, $settings);

		/**
		 * WordPresses update_option() will return false if the settings are exactly the same as before.
		 * So we are manually checking and return true to make sure the 'success' message shows.
		 */
		if ( $settings === $new_settings ) {
			update_option( 'caruso-prayer-plugin', $new_settings );

			return true;
		}

		return update_option( 'caruso-prayer-plugin', $new_settings );
	}

	/**
	 * Deletes all of the general settings for this plugin.
	 *
	 * @return bool
	 */
	public function delete() {
		return delete_option( 'caruso-prayer-plugin' );
	}
}
