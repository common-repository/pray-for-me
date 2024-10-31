<?php
/**
 * This template displays the thank you message after the prayer request has been submitted.
 *
 * @var CarusoPrayerPluginForm     $form
 * @var CarusoPrayerPluginSettings $settings
 */
?>

<div class="prayer_request_submitted">
	<h3><?php echo apply_filters(CARUSO_PRAYER_FILTER_SUBMITTED_HEADER, __( htmlentities($settings->confirm_title), 'caruso-prayer-plugin' ) ); ?></h3>
	<p>
		<?php echo apply_filters(CARUSO_PRAYER_FILTER_SUBMITTED_MESSAGE, __( htmlentities($settings->confirm_message), 'caruso-prayer-plugin' ) ); ?>
	</p>
</div>
