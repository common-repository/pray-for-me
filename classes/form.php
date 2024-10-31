<?php

class CarusoPrayerPluginForm {

	/**
	 * The first name of the person submitting the request.
	 *
	 * @var string|null
	 */
	public $user_first_name;

	/**
	 * The last name of the person submitting the request.
	 *
	 * @var string|null
	 */
	public $user_last_name;

	/**
	 * The email address of the person submitting the request.
	 *
	 * @var string|null
	 */
	public $user_email;

	/**
	 * The title of the prayer request.
	 *
	 * @var string|null
	 */
	public $prayer_title;

	/**
	 * The request for a prayer.
	 *
	 * @var string|null
	 */
	public $prayer_request;

	/**
	 * Tells whether this prayer should be anonymous.
	 *
	 * @var bool
	 */
	public $is_anonymous;

	/**
	 * Tells whether the form has been submitted.
	 *
	 * @var bool
	 */
	protected $_is_submitted = false;

	/**
	 * Tells whether the form has passed the validation rules.
	 *
	 * @var bool
	 */
	protected $_is_valid = true;

	/**
	 * Contains any errors that occurred when processing the form.
	 *
	 * @var string[]
	 */
	protected $_errors = array();

	/**
	 * CarusoPrayerPluginForm constructor.
	 *
	 * @param array $data This should contain the data to populate the form, usually the $_REQUEST variable.
	 *                    We are passing it through the constructor because that also allows us to populate
	 *                    using other data sources also.
	 */
	public function __construct( $data = array() ) {
		$this->user_first_name = isset( $data['user_first_name'] ) ? stripslashes( $data['user_first_name'] ) : null;
		$this->user_last_name  = isset( $data['user_last_name'] ) ? stripslashes( $data['user_last_name'] ) : null;
		$this->user_email      = isset( $data['user_email'] ) ? stripslashes( $data['user_email'] ) : null;
		$this->prayer_title    = isset( $data['prayer_title'] ) ? stripslashes( $data['prayer_title'] ) : null;
		$this->prayer_request  = isset( $data['prayer_request'] ) ? stripslashes( $data['prayer_request'] ) : null;
		$this->is_anonymous    = isset( $data['is_anonymous'] ) ? $data['is_anonymous'] : false;
		$this->_is_submitted   = isset( $data['_prayer_is_submitted'] ) ? true : false;
	}

	/**
	 * Saves the form by converting it into a WordPress post.
	 *
	 * @return bool|WP_Post
	 */
	public function save() {
		if ( empty( $this->user_first_name ) && empty( $this->user_last_name ) ) {
			$this->is_anonymous = true;
		}

		do_action( CARUSO_PRAYER_ACTION_FORM_SUBMITTED, $this );

		$settings = CarusoPrayerPluginSettings::get_instance();

		$post_id = wp_insert_post( array(
			'post_title'     => $this->prayer_title,
			'post_content'   => $this->prayer_request,
			'post_type'      => CARUSO_PRAYER_POST_TYPE,
			'post_status'    => $settings->publish_immediately ? 'publish' : 'pending',
			'comment_status' => 'closed',
			'meta_input'     => array(
				'user_first_name' => $this->user_first_name,
				'user_last_name'  => $this->user_last_name,
				'user_email'      => $this->user_email,
				'prayer_title'    => $this->prayer_title,
				'prayer_request'  => $this->prayer_request,
				'is_anonymous'    => $this->is_anonymous,
			)
		) );

		if ( is_wp_error( $post_id ) ) {
			$this->_errors[] = $post_id->get_error_message();

			return false;
		}

		$post = WP_Post::get_instance( $post_id );
		if ( $post->ID < 1 ) {
			$this->_errors[] = __( 'Unable to load the prayer request.', 'caruso-prayer-plugin' );

			return false;
		}

		do_action( CARUSO_PRAYER_ACTION_FORM_SAVED, $post );

		return $post;
	}

	/**
	 * Runs the validation rules and returns whether or not the validation passed.
	 *
	 * @return bool
	 */
	public function is_valid() {
		$settings = CarusoPrayerPluginSettings::get_instance();

		$this->_is_valid = true;

		$this->user_first_name = trim( $this->user_first_name );
		if ( $settings->require_first_name && empty( $this->user_first_name ) ) {
			$this->_errors['user_first_name'] = __( 'Please supply your first name.', 'caruso-prayer-plugin' );
			$this->_is_valid                  = false;
		}

		$this->user_last_name = trim( $this->user_last_name );
		if ( $settings->require_last_name && empty( $this->user_last_name ) ) {
			$this->_errors['user_last_name'] = __( 'Please supply your last name.', 'caruso-prayer-plugin' );
			$this->_is_valid                 = false;
		}

		$this->user_email = trim( $this->user_email );
		if ( $settings->require_email && ( empty( $this->user_email ) || ! is_email( $this->user_email ) ) ) {
			$this->_errors['user_email'] = __( 'Please supply a valid email address.', 'caruso-prayer-plugin' );
			$this->_is_valid             = false;
		}

		$this->prayer_title = trim( $this->prayer_title );
		if ( empty( $this->prayer_title ) ) {
			$this->_errors['prayer_title'] = __( 'Please supply your prayer title.', 'caruso-prayer-plugin' );
			$this->_is_valid               = false;
		}

		$this->prayer_request = trim( $this->prayer_request );
		if ( empty( $this->prayer_request ) ) {
			$this->_errors['prayer_request'] = __( 'Please supply your prayer request.', 'caruso-prayer-plugin' );
			$this->_is_valid                 = false;
		}

		$this->_is_valid = (bool) apply_filters( CARUSO_PRAYER_FILTER_SUBMISSION_IS_VALID, $this->_is_valid, $this );

		return $this->_is_valid;
	}

	/**
	 * Tells whether the submission has been successfully completed.
	 *
	 * @return bool
	 */
	public function is_submitted() {
		return $this->_is_submitted;
	}

	/**
	 * Tells whether the form had any errors during processing.
	 *
	 * @return bool
	 */
	public function has_errors() {
		return count( $this->_errors ) > 0;
	}

	/**
	 * Check to see if there is an error for a specific field.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function has_error( $name ) {
		return isset( $this->_errors[ $name ] );
	}

	/**
	 * Returns a list of errors that occurred during processing.
	 *
	 * @return string[]
	 */
	public function get_errors() {
		return $this->_errors;
	}

	/**
	 * Returns an error for a specific field.
	 *
	 * @param string $name
	 *
	 * @return null|string
	 */
	public function get_error( $name ) {
		$error = null;

		if ( $this->has_error( $name ) ) {
			$error = $this->_errors[ $name ];
		}

		return $error;
	}
}
