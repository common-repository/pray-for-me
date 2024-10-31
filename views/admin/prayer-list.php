<?php
/**
 * This view is responsible for displaying a WordPress table list with the prayer requests.
 *
 * @var CarusoPrayerPluginListTable $table
 */
?>
<div class="wrap">
	<h1>Prayer Requests</h1>
	<form method="post">
	<?php $table->display(); ?>
	</form>
</div>
