<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php if (isset($items)) { ?>
<div class="breadcrumbs">
	<?php foreach ($items as $key => $value) { ?>
		<?php if ( (count($items) - 1) != $key ) { ?>
		<a href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a>
		<img src="<?php echo AT_Common::static_url('/assets/images/marker_2.gif');?>" alt=""/>
		<?php } else { ?>
		<?php echo $value['name']; ?>
		<?php } ?>
	<?php } ?>
</div>
<?php } ?>