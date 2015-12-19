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