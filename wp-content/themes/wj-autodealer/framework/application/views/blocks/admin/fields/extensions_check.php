<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $upload_dir = wp_upload_dir(); ?>
<div class="theme_option_set one-row">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
		<table width="100%">
			<thead>
				<th>Extension</th>
				<th>State</th>
			</thead>
			<tbody>
				<tr>
					<td width="50%">GD2 (required for images)</td>
					<td>
						<?php echo (extension_loaded('gd') && function_exists('gd_info')) ? '<span class="success">INSTALLED</span>' : '<span class="fail">NOT INSTALLED</span>' ?>
					</td>
				</tr>
				<tr>
					<td>CURL (required for PayPal express checkout)</td>
					<td>
						<?php echo (function_exists('curl_init')) ? '<span class="success">INSTALLED</span>' : '<span class="fail">NOT INSTALLED</span>' ?>
					</td>
				</tr>

			</tbody>
		</table>
	</div>
	<div class="clear"></div>
</div>