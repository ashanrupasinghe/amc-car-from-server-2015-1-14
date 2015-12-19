<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="main_wrapper">
	<h1><?php _e( 'Transaction Complete, but payment is still pending ', AT_TEXTDOMAIN ); ?></h1>
	<?php _e( 'Your Transaction ID is ', AT_TEXTDOMAIN ); ?> 
	<?php echo urldecode($transaction_id); ?>
	<p><?php _e( 'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a>', AT_TEXTDOMAIN ); ?></p>
	<p>&nbsp;</p>
	<?php print_r($response); ?>
	<p>&nbsp;</p>
</div>