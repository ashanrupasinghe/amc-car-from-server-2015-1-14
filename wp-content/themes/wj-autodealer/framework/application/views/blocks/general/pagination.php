<?php if ( isset( $items ) && count( $items ) > 0 ) { ?>
<div class="pagination">
	<ul>
		<?php foreach ($items as $key => $item) { ?>
			<li><a href="#">2</a></li>
		<?php } ?>
		<!--li class="active"><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li class="space">...</li>
		<li><a href="#">8</a></li>
		<li class="next"><a href="#"><img src="/wp-content/themes/winterjuice/framework/assets/images/page_next.gif" alt=""/></a></li-->
	</ul>
</div>
<?php } else { ?>

<?php } ?>
<?php if ( isset( $list ) && count( $list ) > 0 ) { ?>
	<div class="pagination">
        <ul>
            <?php if (count($list) > 2) { ?>
                <?php if (isset($first) && !empty($first)) { ?>
                    <li class="quotes"><a href="<?php echo $first['url']; ?>">«</a></li>
                <?php }?>
                <?php if (isset($prev) && !empty($prev)) { ?>
                    <li><a href="<?php echo $prev['url']; ?>">&lt;</a></li>
                <?php } ?>
            <?php } ?>

            <?php foreach($list as $key=>$elem) { ?>
	            <?php if ($elem['current'] != TRUE) { ?>
	                <li><a href="<?php echo $elem['url']; ?>"><?php echo $elem['page']; ?></a></li>
	            <?php } else { ?>
	                <li class="active"><a href="<?php echo $elem['url']; ?>"><?php echo $elem['page']; ?></a></li>
	            <?php } ?>
            <?php } ?>

            <?php if (count($list) > 2) { ?>
                <?php if (isset($next) && !empty($next)) { ?>
                	<li><a href="<?php echo $next['url']; ?>">&gt;</a></li>
                <?php } ?>
                <?php if (isset($last) && !empty($last)) { ?>
                	<li class="quotes"><a href="<?php echo $last['url']; ?>">»</a></li>
                <?php } ?>
            <?php } ?>
        </ul>
	</div>
<?php } ?>