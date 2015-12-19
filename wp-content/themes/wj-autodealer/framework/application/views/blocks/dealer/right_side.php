<div class="widget grey car_widget">
	<h2><?php echo __( '<strong>About</strong> us', AT_TEXTDOMAIN ); ?></h2>
	<div class="post">
		<?php if ( count( $dealer_info['photo'] ) ) { ?>
			<img style="max-width:70px;float:left;margin-right:20px;" src="<?php echo AT_Common::static_url( $dealer_info['photo']['photo_url'] . '138x138/' . $dealer_info['photo']['photo_name'] ); ?>" alt=""/>
		<?php } ?>
		<p><?php echo $dealer_info['about']; ?></p>
		<div class="clear"></div>
	</div>
</div>
<?php if ( count( $dealer_contact ) > 0 ) { ?>
<div class="widget contacts_widget">
	<h2><?php echo __( '<strong>Contact</strong> details', AT_TEXTDOMAIN );?>: <?php echo $affiliate['name']; ?></h2>
	<?php if(!empty( $dealer_contact['adress'] )) { ?>
		<div class="addr detail"><?php echo $dealer_contact['adress']; ;?></div>
	<?php } ?>
	<?php if(!empty( $dealer_contact['phones'] )) { ?>
		<div class="phones detail"><?php echo $dealer_contact['phones']; ?></div>
	<?php } ?>
	<?php if(!empty( $dealer_contact['email'] )) { ?>
		<div class="email detail single_line"><a href="#" rel="<?php echo AT_Common::nospam( $dealer_contact['email'] ); ?>" class="email_link_replace markered"><?php echo AT_Common::nospam( $dealer_contact['email'] ); ?></a></div>
	<?php } ?>
	<!--
	<?php if(!empty( $dealer_contact['url'] )) { ?>
		<div class="web detail single_line"><a href="<?php echo $dealer_contact['url']; ?>"><?php echo $dealer_contact['url']; ?></a></div>
	<?php } ?>
	-->
	<div class="clear"></div>
</div>
<?php } ?>
<?php if ( count( $affiliates ) > 1 ) { ?>
<div class="widget schedule_widget">
	<h2><?php echo __( '<strong>Contact</strong> Affiliates', AT_TEXTDOMAIN ); ?></h2>
	<div class="accordion">
		<?php foreach ($affiliates as $key => $item) { ?>
			<?php if( !$item['is_main'] ) {  ?>
				<?php 
					$phones = array();
					if( trim( $item['phone'] ) != '' ) $phones[] = trim($item['phone'] );
					if( trim( $item['phone_2'] ) != '' ) $phones[] = trim( $item['phone_2'] );
					$adress = (!empty($item['region']) ? $item['region'] . ', ' : '') . $item['adress']; 
				?>
			<div class="acc_box">
				<h4><?php echo $item['name']; ?></h4>
				<div style="display: none;">
					<?php if(!empty( $adress )) { ?>
						<p><?php echo $adress; ?></p>
					<?php } ?>
					<?php if(!empty( $phones )) { ?>
						<p><?php echo implode( '<br/>', $phones ); ?></p>
					<?php } ?>
					<?php if(!empty( $item['email'] )) { ?>
						<p><a href="#" rel="<?php echo AT_Common::nospam( $item['email'] ); ?>" class="email_link_replace markered"><?php echo AT_Common::nospam( $item['email'] ); ?></a></p>
					<?php } ?>
					<h4><?php echo __( '<strong>Schedule</strong> affiliates', AT_TEXTDOMAIN ); ?></h4>
					<ul>
						<li>
							<?php echo __( 'Monday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['monday']; ?></span>
						</li>
						<li>
							<?php echo __( 'Tuesday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['tuesday']; ?></span>
						</li>
						<li>
							<?php echo __( 'Wednesday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['wednesday']; ?></span>
						</li>
						<li>
							<?php echo __( 'Thursday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['thursday']; ?></span>
						</li>
						<li>
							<?php echo __( 'Friday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['friday']; ?></span>
						</li>
						<li>
							<?php echo __( 'Saturday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['saturday']; ?></span>
						</li>
						<li>
							<?php echo __( 'Sunday', AT_TEXTDOMAIN );?>
							<span><?php echo $item['schedule']['sunday']; ?></span>
						</li>
					</ul>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>
<?php } ?>
<?php if ( !empty( $affiliate['schedule'] ) ) { ?>
<div class="widget schedule_widget">
	<h2><?php echo __( '<strong>Schedule</strong>', AT_TEXTDOMAIN ); ?></h2>
	<ul class="schedule_widget_items">
		<li>
			<?php echo __( 'Monday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['monday']; ?></span>
		</li>
		<li>
			<?php echo __( 'Tuesday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['tuesday']; ?></span>
		</li>
		<li>
			<?php echo __( 'Wednesday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['wednesday']; ?></span>
		</li>
		<li>
			<?php echo __( 'Thursday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['thursday']; ?></span>
		</li>
		<li>
			<?php echo __( 'Friday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['friday']; ?></span>
		</li>
		<li>
			<?php echo __( 'Saturday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['saturday']; ?></span>
		</li>
		<li>
			<?php echo __( 'Sunday', AT_TEXTDOMAIN );?>
			<span><?php echo $affiliate['schedule']['sunday']; ?></span>
		</li>
	</ul>
	<div class="clear"></div>
</div>
<?php } ?>