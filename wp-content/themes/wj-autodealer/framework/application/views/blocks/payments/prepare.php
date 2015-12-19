<?php if (!defined("AT_DIR")) die('!!!'); ?>
<div class="main_wrapper">
	<h1><?php _e( 'Payment System (BETA)', AT_TEXTDOMAIN ); ?></h1>



	<?php echo sprintf( __( 'You\'re going to promote %s with paid service.', AT_TEXTDOMAIN ), '<strong>' . $cars['post_title'] . '</strong>' ); ?>

	<p>&nbsp;</p>
	<form action="<?php echo AT_Common::site_url('payments/checkout'); ?>" method="POST">
	<h2><?php _e( 'Select Plan', AT_TEXTDOMAIN ); ?></h2>
	<?php _e( 'Please specify your plan', AT_TEXTDOMAIN ); ?>
	<?php
		if (is_array($plans)) {
			// echo 1;
			$width = 100/count( $plans );
			echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">';
			echo '<tr>';
			foreach($plans as $pkey => $plan ) {
			// echo 2;
			// print_r( $method );
			// if ( $method['state'] !== 0 ) {
			// echo 3;
					echo '<td width="' . $width . '">';
					echo '<label for="' . $pkey . '">';
					echo '<h4>' . $plan['name'] . '</h4>';
					echo '</label>';
					echo '<p>' . $cars['options']['_currency_id']['alias'] . $plan['rate'] . '/' . $plan['period_label'] . '</p>';
					echo '<div><input type="radio" id="' . $pkey . '" name="plan" value="' . $pkey . '" /></div>';
					echo '</td>';
				// }
			}
			echo '</tr>';
			echo '</table>';

		}
	?>

	<p>&nbsp;</p>


	<h2><?php _e( 'Payment Method', AT_TEXTDOMAIN ); ?></h2>
	<?php _e( 'Please specify payment method', AT_TEXTDOMAIN ); ?>
	<?php
		if (is_array($methods)) {
			// echo 1;
			echo '<table border="0" cellpadding="10" cellspacing="10">';
			foreach($methods as $key => $method ) {
			// echo 2;
			// print_r( $method );
				if ( $method['state'] !== 0 ) {
			// echo 3;
					echo '<tr>';
					echo '<td><input type="radio" id="radio_method_paypal" name="payment_method" value="' . $key . '" /></td>';
					echo '<td><label for="radio_method_paypal">';
					if ( !empty($method['logo']) ) {
						echo '<p><img src="' . $method['logo'] . '" alt="' . $method['name'] . '" /></p>';
					} else {
						echo '<h3>' . $method['name'] . '</h3>';
					}
					echo '</label></td>';
					echo '</tr>';
				}
			}
			echo '</table>';

		}
	?>
	<input type="submit" value="<?php _e( 'Order now', AT_TEXTDOMAIN ); ?>" />
	</form>
	<p>&nbsp;</p>

</div>