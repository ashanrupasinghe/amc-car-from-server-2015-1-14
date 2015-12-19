<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="main_wrapper">
	<h1><?php _e( 'Payment Completed', AT_TEXTDOMAIN ); ?></h1>
	<?php if ( $transaction_id ) : ?>
		<?php _e( 'Your Transaction ID is ', AT_TEXTDOMAIN ); ?> 
		<?php echo urldecode($transaction_id); ?>
	<?php endif; ?>
	<p><?php _e( 'Your product will be sent to you very soon!', AT_TEXTDOMAIN ); ?></p>
	<p>&nbsp;</p>
</div>