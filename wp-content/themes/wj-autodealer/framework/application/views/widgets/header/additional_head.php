<?php if (!defined("AT_DIR")) die('!!!'); ?>
  <?php if($this->get_option( 'custom_js' ) != '' ) { ?>
  	<script type='text/javascript'>
	/* <![CDATA[ */
	<?php echo stripslashes( $this->get_option( 'custom_js' ) ); ?>
	/* ]]> */
	</script>
  <?php } ?>
  <?php if($this->get_option( 'custom_css' ) != '' ) { ?>
  	<style type="text/css" media="screen">
	<?php echo stripslashes( $this->get_option( 'custom_css' ) ); ?>
	</style>
  <?php } ?>