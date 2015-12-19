<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="widget widget-nav-profile">
	<h2><?php echo __('<strong>Account</strong> menu', AT_TEXTDOMAIN ); ?></h2>
	<?php if ( !empty($items) ) : ?>
	<ul>
	<?php
		foreach ($items as $key => $value) {
			echo '<li class="' . ( $active == $key ? 'active' : '' ) . ' ' . $key . '"><a href="' . $value['url'] . '">' . $value['name'] . '</a></li>';
		}
	?>
	</ul>
<?php endif; ?>
</div>
<?php if( AT_Common::is_user_logged() && $this->get_option( 'want_be_dealer_enable', true ) ) { ?>
	<?php $user_info =  $this->registry->get( 'user_info' ); ?>
	<?php if( !$user_info['is_dealer'] ) { ?>
		<?php $this->add_script( 'jquery-ui-core'); ?>
		<?php $this->add_script( 'jquery-ui-dialog'); ?>
		<?php $this->add_script( 'request_dealer', 'assets/js/request_dealer.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' )); ?>

		<?php $this->add_style( 'jquery-ui', 'assets/css/jquery-ui/jquery-ui.min.css');?>
		<?php $this->add_style( 'jquery-ui-theme', 'assets/css/jquery-ui/jquery-ui.theme.css');?>
		<div id="dialog-request-dealer" title="<?php echo __( 'I want to be a dealer', AT_TEXTDOMAIN);?>" style='display:none;'>
			<div style="">
				<div class="row">
					<label><?php echo __( 'Comment', AT_TEXTDOMAIN ); ?></label>
					<textarea name="comment" id="comment" class="text want_be_dealer_comment" style="margin-bottom:0;" ></textarea>
				</div>
			</div>
		</div>
		<div class="widget widget-for-dealer">
			<a href="#" class="btn2 dialog_request_dealer"><?php echo __( 'I want to be a dealer', AT_TEXTDOMAIN ); ?></a>
		</div>
	<?php } ?>
<?php } ?>