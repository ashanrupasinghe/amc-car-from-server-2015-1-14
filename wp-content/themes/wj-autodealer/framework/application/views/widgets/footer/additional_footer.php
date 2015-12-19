<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if($this->get_option( 'google_analytics' ) != '' ) { ?>
	<?php echo stripslashes( $this->get_option( 'google_analytics' ) ); ?>
<?php } ?>