<?php if (!defined("AT_DIR")) die('!!!'); ?>
			<!-- </div> -->
		</div>
	</div>
<!--BEGIN FOOTER-->
	<div id="footer">
	  <?php if(count($top_sidebars) > 0){ ?>
		<div class="bg_top_footer">
			<div class="top_footer">
			<?php 
				end($top_sidebars); 
				$last_key = key($top_sidebars);
				reset($top_sidebars);
			?>
			<?php foreach ($top_sidebars as $key => $value) {?>
				<div class="f_widget <?php echo $value['class'] . ' '; echo ($last_key == $key) ? 'last' : ''; ?>">
					<?php echo $value['content']; ?>
				</div>	
			<?php } ?>
				<!--div class="f_widget last" style="width:700px;">
					<img src="<?php echo AT_Common::static_url('/assets/images/pics/banner_footer.jpg');?>" />
					<img src="http://wp-test/wp-content/themes/winterjuice/framework/assets/images/pics/banner_footer.jpg" />
				</div-->	
			</div>
		</div>
	  <?php } ?>
	  <?php if(count($bottom_sidebars) > 0){ ?>
		<div class="bottom_footer">
			<?php 
				end($bottom_sidebars); 
				$last_key = key($bottom_sidebars);
				reset($bottom_sidebars);
			?>
			<?php foreach ($bottom_sidebars as $key => $value) {?>
				<div class="f_widget <?php echo $value['class'] . ' '; echo ($last_key == $key) ? 'last' : ''; ?>">
					<?php echo $value['content']; ?>
				</div>	
			<?php } ?>
		</div>
	  <?php } ?>
		
		<div class="copyright_wrapper">
			<div class="copyright_container">
				<?php if( $sociable_view ) { ?>
				<div class="socials">
					<?php
					foreach ($sociable as $key => $item) {
						$sociable_link = ( !empty( $item['link'] ) ) ? $item['link'] : '#';
						echo '<a href="' . esc_url( $sociable_link ) . '"><i class="' . $item['icon'] . '"></i></a>';
					}
					?>
				</div>
				<?php } ?>
				<?php if( !empty($logo) ) { ?>
					<div class="footer_logo"><?php echo $logo ?></div>
				<?php } ?>
				<div class="copyright"><?php echo str_replace('%Y', date('Y'), $this->get_option( 'footer_copyright_text' ) ) ?></div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<!--EOF FOOTER-->
<?php get_footer(); ?>