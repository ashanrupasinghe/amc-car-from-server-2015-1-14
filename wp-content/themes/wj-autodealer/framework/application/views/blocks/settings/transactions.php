<?php if (!defined("AT_DIR")) die('!!!'); ?>


<div class="profile_content">
<table width="100%">
	<thead>
		<th><?php _e('Item ID', AT_TEXTDOMAIN); ?></th>
		<th><?php _e('Amount', AT_TEXTDOMAIN); ?></th>
		<th><?php _e('ACK', AT_TEXTDOMAIN); ?></th>
		<th><?php _e('Created', AT_TEXTDOMAIN); ?></th>
		<th><?php _e('Finished', AT_TEXTDOMAIN); ?></th>
		<th><?php _e('Service', AT_TEXTDOMAIN); ?></th>
		<th><?php _e('Message', AT_TEXTDOMAIN); ?></th>
	</thead>
	<tbody>
	<?php foreach ($transactions as $transaction) { ?>
		<tr>
			<td><?php echo $transaction['entity_id']; ?>&nbsp;</td>
			<td><?php echo number_format($transaction['amount'],2, '.', ','); ?>&nbsp;</td>
			<td><?php echo $transaction['ack']; ?>&nbsp;</td>
			<td><?php echo $transaction['created_at']; ?>&nbsp;</td>
			<td><?php echo $transaction['completed_at']; ?>&nbsp;</td>
			<td><?php echo $transaction['entity']; ?>&nbsp;</td>
			<td><?php echo urldecode($transaction['msg']); ?>&nbsp;</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
</div>