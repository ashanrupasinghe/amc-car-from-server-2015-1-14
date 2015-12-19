<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="main_wrapper">
	<h1><?php _e( 'Error occured', AT_TEXTDOMAIN ); ?></h1>
	<?php echo urldecode($msg); ?>
	<p>&nbsp;</p>
	<?php print_r($response); ?>
	<p>&nbsp;</p>
</div>