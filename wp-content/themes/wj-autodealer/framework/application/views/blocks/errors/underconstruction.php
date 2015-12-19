<?php $this->add_script( 'jquery.countdown', 'assets/js/jquery/jquery.countdown.js', array( 'jquery' ) ); ?>
<?php $this->add_script( 'underconstruction', 'assets/js/underconstruction.js', array( 'jquery', THEME_PREFIX . 'jquery.countdown' ) ); ?>
<div class="counter_heading"><?php echo $this->get_option( 'underconstruction_text' ); ?></div>
<?php if( count( $counter_options ) > 0 ) { ?>
<?php $this->add_localize_script( 'underconstruction', 'counter_options', $counter_options ); ?>
<div class="counter_wrapper">
	<div id="counter">
		<span class="countdown_row countdown_show4">
			<span class="countdown_section">
				<span class="countdown_amount"><?php echo $counter_options['days'] ?></span>
				<?php echo __( 'Days', AT_TEXTDOMAIN ); ?>
			</span>
			<span class="countdown_section">
				<span class="countdown_amount"><?php echo $counter_options['hours'] ?></span>
				<?php echo __( 'Hours', AT_TEXTDOMAIN ); ?>
			</span>
			<span class="countdown_section">
				<span class="countdown_amount"><?php echo $counter_options['minutes'] ?></span>
				<?php echo __( 'Minutes', AT_TEXTDOMAIN ); ?>
			</span>
			<span class="countdown_section">
				<span class="countdown_amount"><?php echo $counter_options['seconds'] ?></span>
				<?php echo __( 'Seconds', AT_TEXTDOMAIN ); ?>
			</span>		
		</span>
	</div>
</div>
<?php } ?>