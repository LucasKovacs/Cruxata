<div id="section">
	<div id="search">
		<div id="title">
			<?php echo $this->lang->line ( 'se_global_search' ); ?>
		</div>
		<div id="infoContainer">
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<tr>
					<td colspan="2" width="100%" id="header">
					<?php echo form_open ( '' , 'name="search-form"' ); ?>
					<table>
						<tr>
							<td width="300px" align="right">
							<?php 
								echo $this->lang->line ( 'se_search' );
								
								$options	=	array	(
															1 => $this->lang->line ( 'se_commander' )
														);
			
								echo ' ' . form_dropdown ( 'search_tye' , $options );
							?>
							</td>
							<td width="150px"><?php echo form_input ( 'to_search' , set_value ( 'to_search' ) ); ?></td>
							<td><?php echo form_submit ( 'submit' , $this->lang->line ( 'se_search' ) ); ?></td>
						</tr>
					</table>
					<?php echo form_close(); ?>
					</td>
				</tr>
				<?php
					$i	= 0;
					
					if ( $search_rows != NULL )
					{
						foreach ( $search_rows as $key => $value )
						{
								$color	= ( $i % 2 == 0 ) ? '#' : '#';						
				?>
							<tr bgcolor="<?php echo $color; ?>">
								<td>
									<?php echo $value['user_name']; ?>
									<a href="<?php echo $base_url; ?>private-message/<?php echo $value['user_name']; ?>">
										<img src="<?php echo $img_path; ?>icons/message.gif" />
									</a>
								</td>
								<td>
									<a href="<?php echo $base_url; ?>field/<?php echo $value['user_name']; ?>">
										<?php echo $value['user_position']; ?>
									</a>
								</td>
							</tr>
				<?php
								$i++;
						}	
					}
				?>
			</table>
		</div>
	</div>
</div>