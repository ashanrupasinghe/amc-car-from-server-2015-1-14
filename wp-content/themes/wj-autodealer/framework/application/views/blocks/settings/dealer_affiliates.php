<?php if (!defined("AT_DIR")) die('!!!'); ?>

<?php $this->add_script( 'jquery.form', 'assets/js/jquery/jquery.form.js'); ?>
<?php $this->add_script( 'jquery.validate', 'assets/js/jquery/jquery.validate.js'); ?>
<?php $this->add_script( 'login', 'assets/js/dealer_affiliates.js'); ?>

<div class="profile_content">
	<a href="#" class="btn2 add_affiliate" style="float:right;"><?php echo __( 'Add affiliate', AT_TEXTDOMAIN ); ?></a>
	<h1><?php echo __( '<strong>Dealer</strong> affiliates', AT_TEXTDOMAIN); ?></h1>
		<script type = "text/template" id="empty_affiliate_item">
		  <div class="item-title"><?php echo __( 'Affiliate ', AT_TEXTDOMAIN ); ?>{n}</div>
		  <div class="item-content" style="display:none;">
		  <form action="<?php echo AT_Common::site_url('profile/settings/dealer_affiliates/'); ?>" method="post" id="settings-form">
		  	<input type="hidden" name="affiliate_id" value="0">
			<div class="col1">
				<label class="text"><?php echo __( 'Affiliates name:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="name" value=""/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Affiliates e-mail:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="email" value=""/>
			</div>
			<div class="col1">
				<label class="text"><?php echo __( 'Affiliates region:', AT_TEXTDOMAIN); ?></label>
				<select name="region_id" class="text">
					<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN); ?></option>
					<?php foreach ($regions as $key => $region) { ?>
						<option value="<?php echo $region['id']; ?>"><?php echo $region['name']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Affiliates phone 1:', AT_TEXTDOMAIN); ?></label>
				<input type="text" name="phone_1" class="text" value=""/>
			</div>
			<div class="col1">
				<label class="text"><?php echo __( 'Affiliates adress:', AT_TEXTDOMAIN); ?></label>
				<input type="text" name="adress" class="text" value=""/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Affiliates phone 2:', AT_TEXTDOMAIN); ?></label>
				<input type="text"  name="phone_2" class="text" value=""/>
			</div>
			<div class="clear"></div>
			<div class="col1">
				<label class="text"><?php echo __( 'Schedule Monday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[monday]" value=""/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Schedule Tuesday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[tuesday]" value=""/>
			</div>
			<div class="col1">
				<label class="text"><?php echo __( 'Schedule Wednesday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[wednesday]" value=""/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Schedule Thursday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[thursday]" value=""/>
			</div>
			<div class="col1">
				<label class="text"><?php echo __( 'Schedule Friday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[friday]" value=""/>
			</div>
			<div class="col2">
				<label class="text"><?php echo __( 'Schedule Saturday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[saturday]" value=""/>
			</div>
			<div class="col1 last">
				<label class="text"><?php echo __( 'Schedule Sunday:', AT_TEXTDOMAIN); ?></label>
				<input type="text" class="text" name="schedule[sunday]" value=""/>
			</div>
			<div class="clear"></div>
			<a href="#" class="btn1 submit-save"><?php echo __( 'SAVE CHANGES', AT_TEXTDOMAIN ); ?></a>
			<a href="#" class="btn2 submit-main"><?php echo __( 'SET AS PRIMARY', AT_TEXTDOMAIN ); ?></a>
			<a href="#" class="btn1 submit-delete"><?php echo __( 'DELETE ITEM', AT_TEXTDOMAIN ); ?></a>
			</form>
		  </div>
		</script>
		<div id="dealer_affiliates_items">
		<?php foreach( $affiliates as $key=>$affiliate ) { ?>
			<div class="settings_form affiliate_item <?php if ($affiliate['is_main']) echo 'main_affiliate'; ?>" data-id="<?php echo $key; ?>">
			  <div class="item-title"><?php echo $affiliate['name']; ?></div>
			  <div class="item-content">
			  	<form action="<?php echo AT_Common::site_url('profile/settings/dealer_affiliates/'); ?>" method="post" class="settings-form">
			  		<input type="hidden" name="affiliate_id" value="<?php echo $affiliate['id']; ?>">
				<div class="col1">
					<label class="text"><?php echo __( 'Affiliates name:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="name" value="<?php echo $affiliate['name']; ?>"/>
				</div>
				<div class="col2">
					<label class="text"><?php echo __( 'Affiliates e-mail:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="email" value="<?php echo $affiliate['email']; ?>"/>
				</div>
				<div class="col1">
					<label class="text"><?php echo __( 'Affiliates region:', AT_TEXTDOMAIN); ?></label>
					<select name="region_id" class="text">
						<option value="0"><?php echo __( 'Any', AT_TEXTDOMAIN); ?></option>
						<?php foreach ($regions as $key => $region) { ?>
							<option value="<?php echo $region['id']; ?>" <?php if( $region['id'] == $affiliate['region_id'] ) echo 'selected'; ?>><?php echo $region['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col2">
					<label class="text"><?php echo __( 'Affiliates phone 1:', AT_TEXTDOMAIN); ?></label>
					<input type="text" name="phone_1" class="text" value="<?php echo $affiliate['phone']; ?>"/>
				</div>
				<div class="col1">
					<label class="text"><?php echo __( 'Affiliates adress:', AT_TEXTDOMAIN); ?></label>
					<input type="text" name="adress" class="text" value="<?php echo $affiliate['adress']; ?>"/>
				</div>
				<div class="col2">
					<label class="text"><?php echo __( 'Affiliates phone 2:', AT_TEXTDOMAIN); ?></label>
					<input type="text"  name="phone_2" class="text" value="<?php echo $affiliate['phone_2']; ?>"/>
				</div>
				<div class="clear"></div>
				<div class="col1">
					<label class="text"><?php echo __( 'Schedule Monday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[monday]" value="<?php echo $affiliate['schedule']['monday']; ?>"/>
				</div>
				<div class="col2">
					<label class="text"><?php echo __( 'Schedule Tuesday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[tuesday]" value="<?php echo $affiliate['schedule']['tuesday']; ?>"/>
				</div>
				<div class="col1">
					<label class="text"><?php echo __( 'Schedule Wednesday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[wednesday]" value="<?php echo $affiliate['schedule']['wednesday']; ?>"/>
				</div>
				<div class="col2">
					<label class="text"><?php echo __( 'Schedule Thursday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[thursday]" value="<?php echo $affiliate['schedule']['thursday']; ?>"/>
				</div>
				<div class="col1">
					<label class="text"><?php echo __( 'Schedule Friday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[friday]" value="<?php echo $affiliate['schedule']['friday']; ?>"/>
				</div>
				<div class="col2">
					<label class="text"><?php echo __( 'Schedule Saturday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[saturday]" value="<?php echo $affiliate['schedule']['saturday']; ?>"/>
				</div>
				<div class="col1 last">
					<label class="text"><?php echo __( 'Schedule Sunday:', AT_TEXTDOMAIN); ?></label>
					<input type="text" class="text" name="schedule[sunday]" value="<?php echo $affiliate['schedule']['sunday']; ?>"/>
				</div>
				<div class="clear"></div>
				<a href="#" class="btn1 submit-save"><?php echo __( 'SAVE CHANGES', AT_TEXTDOMAIN ); ?></a>
				<a href="#" class="btn2 submit-main"><?php echo __( 'SET AS PRIMARY', AT_TEXTDOMAIN ); ?></a>
				<a href="#" class="btn1 submit-delete"><?php echo __( 'DELETE ITEM', AT_TEXTDOMAIN ); ?></a>
				</form>
			  </div>
			</div>
		<?php } ?>
	</div>
</div>