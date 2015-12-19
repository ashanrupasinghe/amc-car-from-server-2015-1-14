<?php if (!defined("AT_DIR")) die('!!!'); ?>
<?php $upload_dir = wp_upload_dir(); ?>
<div class="theme_option_set one-row">
	<div class="theme_option_header">
		<h3 class="caption"><?php echo $title; ?></h3>
		<div class="clear"></div>
		<p><?php echo $description; ?></p>
		<table width="100%">
			<thead>
				<th>Directory</th>
				<th>State</th>
			</thead>
			<tbody>
				<tr>
					<td width="50%"><?php echo $upload_dir['basedir']; ?></td>
					<td>
						<?php echo (is_writable( $upload_dir['basedir'] )) ? '<span class="success">PASSED</span>' : '<span class="fail">FAIL</span>' ?>
					</td>
				</tr>
				<tr>
					<td><?php echo AT_UPLOAD_DIR_THEME; ?></td>
					<td>
						<?php echo (is_writable( AT_UPLOAD_DIR_THEME )) ? '<span class="success">PASSED</span>' : '<span class="fail">FAIL</span>' ?>
					</td>
				</tr>
				<tr>
					<td><?php echo AT_UPLOAD_DIR_THEME; ?>/car</td>
					<td>
						<?php echo (is_writable( AT_UPLOAD_DIR_THEME . '/car' )) ? '<span class="success">PASSED</span>' : '<span class="fail">FAIL</span>' ?>
					</td>
				</tr>
				<tr>
					<td><?php echo AT_UPLOAD_DIR_THEME; ?>/user</td>
					<td>
						<?php echo (is_writable( AT_UPLOAD_DIR_THEME . '/user' )) ? '<span class="success">PASSED</span>' : '<span class="fail">FAIL</span>' ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="clear"></div>
</div>