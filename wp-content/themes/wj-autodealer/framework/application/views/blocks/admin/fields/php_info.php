<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $upload_dir = wp_upload_dir(); ?>
<div class="theme_option_set one-row php-info">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
		<?php

            ob_start();
            phpinfo();
            $phpInfo = preg_replace("/<head>(.*)<\/head>/iUs", "", ob_get_contents());
            // $phpinf = preg_replace("/<head>.+?<\/head>/i", "", ob_get_contents());
            ob_end_clean();

            echo $phpInfo;
	    ?>
	</div>
	<div class="clear"></div>
</div>