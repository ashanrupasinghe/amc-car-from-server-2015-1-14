<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if ( !empty($items) ) : ?>
<div class="theme-tabs">
	<ul class="">
	<?php
		$i = 1;
		foreach ($items as $key => $value) {
			echo '<li> <a class="' . ( $active == $key ? 'current' : '' ) . '" href="' . $value['url'] . '">' . $value['name'] . '</a>';
			//if ( $i != count($items) ) echo ' |';
			echo '</li>';
			$i++;
		}
	?>
	</ul>
	<div class="clear"></div>
</div>
<?php endif; ?>