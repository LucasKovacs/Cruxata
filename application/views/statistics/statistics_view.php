<div id="section">
	<div id="statistics">
		<div id="title">
			<?php echo $this->lang->line ( 'st_title' ); ?>
		</div>
		<div id="infoContainer">
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<tr>
					<td id="header"><?php echo $this->lang->line ( 'st_position' ); ?></td>
					<td id="header"><?php echo $this->lang->line ( 'st_player' ); ?></td>
					<td id="header"><?php echo $this->lang->line ( 'st_points' ); ?></td>
				</tr>
				<?php 
					foreach ( $statistics_rows as $key => $data )
					{
				?>
						<tr<?php echo $data['row_color']; ?>>
							<td><?php echo $data['user_position']; ?></td>
						    <td>
						    	<?php echo $data['user_name']; ?>
								<?php echo $data['user_message']; ?>
						    </td>
						    <td><?php echo $data['user_points']; ?></td>
						</tr>
				<?php
					}
				?>
			</table>
		</div>
	</div>
</div>