<!--
function upload( $value ) {
	$out = '<div class="wj_option_set upload_option_set">';
	
	$out .= $this->option_start( $value );
	$out .= '<input type="text" name="' . WJ_SETTINGS . '[' . $value['id'] . ']" value="' . ( isset( $this->saved_options[$value['id']] )
	? esc_url(stripslashes( $this->saved_options[$value['id']] ) )
	: ( isset( $value['default'] ) ? $value['default'] : '' ) ) . '" id="' . $value['id'] . '" class="wj_upload" />';
	$out .= '<input type="button" value="' . esc_attr__( 'Upload' , WJ_ADMIN_TEXTDOMAIN ) . '" class="upload_button ' . $value['id'] . ' button" /><br />';
	$out .= $this->option_end( $value );
	
	$out .= '</div>
	
	return $out;
}
-->
<div class="theme_option_set">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
	</div>
	<div class="theme_option">
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" id="<?php echo $id; ?>" />
		<input type="button" value="<?php echo __( 'Upload' , AT_ADMIN_TEXTDOMAIN );?>" class="upload_button <?php echo $name; ?> button" data-target-id="<?php echo $id; ?>" id="_button_<?php echo $name; ?>" />
		<br>
	</div>
	<div class="clear"></div>
</div>