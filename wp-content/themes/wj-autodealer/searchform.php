<div id="search_form">
	<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
		<input type="text" class="txb_search" name="s" id="s" value="<?php if ( !empty($_GET['s']) ) echo htmlspecialchars( strip_tags( $_GET['s'] ) );  ?>" placeholder="<?php echo __( 'Search on site', AT_TEXTDOMAIN );?>" />
		<input type="submit" class="btn4 btn_search" id="searchsubmit" value="<?php echo __( 'Search', AT_TEXTDOMAIN );?>">
	</form>
</div>