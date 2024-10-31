<div>
	<p>
		<label>First Name:</label>
		<span><?php echo $post->user_first_name; ?></span>
	</p>
	<p>
		<label>Last Name:</label>
		<span><?php echo $post->user_last_name; ?></span>
	</p>
	<p>
		<label>Email:</label>
		<span><?php echo $post->user_email; ?></span>
	</p>
	<p>
		<label>Anonymous:</label>
		<span><?php echo $post->is_anonymous ? 'Yes' : 'No'; ?></span>
	</p>
</div>