<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="color:#000;">
		<tbody>
		    <tr>
		    	<td colspan="6">
		    		<?php echo $this->lang->line ( 'ba_title' ); ?>
		    	</td>
		    </tr>
		    <tr>
		    	<center>
		        <td><?php echo $this->lang->line ( 'ba_username' ); ?></td>
		        <td><?php echo $this->lang->line ( 'ba_reason' ); ?></td>
		        <td><?php echo $this->lang->line ( 'ba_since' ); ?></td>
		        <td><?php echo $this->lang->line ( 'ba_until' ); ?></td>
		        <td><?php echo $this->lang->line ( 'ba_left' ); ?></td>
		        <td><?php echo $this->lang->line ( 'ba_by' ); ?></td>
		        </center>
		    </tr>
		    <?php echo $banned_rows; ?>
		</tbody>
	</table>
</body>