<?php
/**
 * This template displays the prayer request form.
 *
 * @var CarusoPrayerPluginForm     $form
 * @var CarusoPrayerPluginSettings $settings
 */
?>

<form id="prayer_request_form" class="prayer_request_form" method="post">
	<h3><?php echo apply_filters( CARUSO_PRAYER_FILTER_FORM_HEADER, __( htmlentities($settings->form_title), 'caruso-prayer-plugin' ) ); ?></h3>
	<p>
		<?php echo apply_filters( CARUSO_PRAYER_FILTER_FORM_MESSAGE, __( htmlentities($settings->form_message), 'caruso-prayer-plugin' ) ); ?>
	</p>

	<?php if ( $settings->show_first_name ): ?>
	<p class="prayer_request_form_field">
		<label for="user_first_name"><?php echo __( 'First Name', 'caruso-prayer-plugin' ); ?></label>
		<?php if ( $form->has_error( 'user_first_name' ) ): ?>
			<span><?php echo $form->get_error( 'user_first_name' ); ?></span>
		<?php endif; ?>
		<input id="user_first_name" type="text" name="user_first_name" value="<?php echo $form->user_first_name; ?>"/>
	</p>
	<?php endif; ?>

	<?php if ( $settings->show_last_name ): ?>
	<p class="prayer_request_form_field">
		<label for="user_last_name"><?php echo __( 'Last Name', 'caruso-prayer-plugin' ); ?></label>
		<?php if ( $form->has_error( 'user_last_name' ) ): ?>
			<span><?php echo $form->get_error( 'user_last_name' ); ?></span>
		<?php endif; ?>
		<input id="user_last_name" type="text" name="user_last_name" value="<?php echo $form->user_last_name; ?>"/>
	</p>
	<?php endif; ?>

	<?php if ( $settings->show_email ): ?>
	<p class="prayer_request_form_field">
		<label for="user_email"><?php echo __( 'Email', 'caruso-prayer-plugin' ); ?></label>
		<?php if ( $form->has_error( 'user_email' ) ): ?>
			<span><?php echo $form->get_error( 'user_email' ); ?></span>
		<?php endif; ?>
		<input id="user_email" type="email" name="user_email" value="<?php echo $form->user_email; ?>"/>
	</p>
	<?php endif; ?>

	<p class="prayer_request_form_field">
		<label for="prayer_title"><?php echo __( 'Prayer Title', 'caruso-prayer-plugin' ); ?></label>
		<?php if ( $form->has_error( 'prayer_title' ) ): ?>
			<span><?php echo $form->get_error( 'prayer_title' ); ?></span>
		<?php endif; ?>
		<input id="prayer_title" type="text" name="prayer_title" value="<?php echo $form->prayer_title; ?>"/>
	</p>

	<p class="prayer_request_form_field">
		<label for="prayer_request"><?php echo __( 'Prayer Request', 'caruso-prayer-plugin' ); ?></label>
		<?php if ( $form->has_error( 'prayer_request' ) ): ?>
			<span><?php echo $form->get_error( 'prayer_request' ); ?></span>
		<?php endif; ?>
		<textarea id="prayer_request" name="prayer_request"><?php echo $form->prayer_request; ?></textarea>
	</p>

	<p class="prayer_request_form_field">
		<label for="is_anonymous">
			<input id="is_anonymous" type="checkbox" name="is_anonymous" value="1" <?php if ( $form->is_anonymous ): ?>checked<?php endif; ?> />
			<?php echo __( 'Would you like your prayer request to be anonymous?', 'caruso-prayer-plugin' ); ?>
		</label>
	</p>

	<?php do_action( CARUSO_PRAYER_ACTION_ADD_FORM_FIELDS ); ?>

	<p class="prayer_request_form_buttons">
		<input type="submit" name="_prayer_is_submitted" class="prayer_request_form_button" value="<?php echo __( 'Submit Prayer Request', 'caruso-prayer-plugin' ); ?>"/>
	</p>
</form>