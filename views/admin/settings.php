<?php
/**
 * This template is responsible for displaying the settings page.
 *
 * @var CarusoPrayerPluginSettings $settings
 * @var array                      $tabs
 */
?>

<div class="wrap settings">
	<h2><?php echo __('Settings Page', 'caruso-prayer-plugin'); ?></h2>
	<form id="settings-tabs" class="settings-tabs" method="POST">
		<ul id="settings-navigation" class="settings-navigation">
			<li id="general-settings-link" class="general-settings-link"><a href="#general-settings"><?php echo __('General Settings', 'caruso-prayer-plugin'); ?></a></li>
			<?php foreach ( $tabs as $tab ): ?>
				<li id="<?php echo $tab['key']; ?>-link" class="<?php echo $tab['key']; ?>-link"><a href="#<?php echo $tab['key']; ?>"><?php echo $tab['name']; ?></a></li>
			<?php endforeach; ?>
		</ul>

		<div id="general-settings">
			<section>
				<h3>Publishing</h3>
				<p>
					<label for="publish_immediately"><?php echo __('Publish Immediately', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="publish_immediately" name="publish_immediately" value="1" <?php if ( $settings->publish_immediately ): ?>checked<?php endif; ?> />
				</p>
			</section>

			<section>
				<h3>Form Fields</h3>
				<p>
					<label for="show_first_name"><?php echo __('Show First Name', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="show_first_name" name="show_first_name" value="1" <?php if ( $settings->show_first_name ): ?>checked<?php endif; ?> />
				</p>
				<p>
					<label for="show_last_name"><?php echo __('Show Last Name', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="show_last_name" name="show_last_name" value="1" <?php if ( $settings->show_last_name ): ?>checked<?php endif; ?> />
				</p>
				<p>
					<label for="show_email"><?php echo __('Show Email', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="show_email" name="show_email" value="1" <?php if ( $settings->show_email ): ?>checked<?php endif; ?> />
				</p>
			</section>

			<section>
				<h3>Form Validation</h3>
				<p>
					<label for="require_first_name"><?php echo __('Require First Name', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="require_first_name" name="require_first_name" value="1" <?php if ( $settings->require_first_name ): ?>checked<?php endif; ?> />
				</p>
				<p>
					<label for="require_last_name"><?php echo __('Require Last Name', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="require_last_name" name="require_last_name" value="1" <?php if ( $settings->require_last_name ): ?>checked<?php endif; ?> />
				</p>
				<p>
					<label for="require_email"><?php echo __('Require Email', 'caruso-prayer-plugin'); ?></label>
					<input type="checkbox" id="require_email" name="require_email" value="1" <?php if ( $settings->require_email ): ?>checked<?php endif; ?> />
				</p>
			</section>

			<section>
				<h3>Form Messages</h3>
				<p>
					<label for="form_title"><?php echo __('Form Title', 'caruso-prayer-plugin'); ?></label>
					<input type="text" id="form_title" name="form_title" value="<?php echo $settings->form_title; ?>" />
				</p>
				<p>
					<label for="form_message"><?php echo __('Form Message', 'caruso-prayer-plugin'); ?></label>
					<textarea id="form_message" name="form_message"><?php echo $settings->form_message; ?></textarea>
				</p>
			</section>

			<section>
				<h3>Confirm Messages</h3>
				<p>
					<label for="confirm_title"><?php echo __('Confirm Title', 'caruso-prayer-plugin'); ?></label>
					<input type="text" id="confirm_title" name="confirm_title" value="<?php echo $settings->confirm_title; ?>" />
				</p>
				<p>
					<label for="confirm_message"><?php echo __('Confirm Message', 'caruso-prayer-plugin'); ?></label>
					<textarea id="confirm_message" name="confirm_message"><?php echo $settings->confirm_message; ?></textarea>
				</p>
				<p>
					<label>&nbsp;</label>
					<input class="ui-button ui-state-active" type="submit" name="action" value="Save Settings" />
				</p>
			</section>
		</div>

		<?php foreach ( $tabs as $tab ): ?>
			<div id="<?php echo $tab['key']; ?>" class="<?php echo $tab['key']; ?>">
				<?php call_user_func_array( $tab['callback'], array( $settings ) ); ?>
			</div>
		<?php endforeach; ?>
	</form>
</div>