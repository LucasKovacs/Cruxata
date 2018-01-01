<div id="section">
	<div id="attack">
		<div id="title">
			<?php echo $this->lang->line ( 'at_title' ); ?> "<?php echo $enemy_name; ?>"
		</div>
		<div id="infoContainer">
			<?php echo form_open ( '' , 'name="attack-form"' ); ?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td valign="top" width="50%">
							<table border="0" width="100%" cellpadding="10" cellspacing="11">
								<tr>
									<td colspan="2" id="header">
										<?php echo $this->lang->line ( 'at_resources' ); ?>
									</td>
								</tr>
								<tr>
									<td width="50%" class="right"><?php echo $this->lang->line ( 'at_enemy_wood' ); ?></td>
									<td width="50%" class="left">
										<span class="woodColor">
											<?php echo $enemy_wood; ?>
										</span>
									</td>
								</tr>
								<tr>
									<td class="right"><?php echo $this->lang->line ( 'at_enemy_stone' ); ?></td>
									<td class="left">
										<span class="stoneColor">
											<?php echo $enemy_stone; ?>
										</span>
									</td>
								</tr>
								<tr>
									<td class="right"><?php echo $this->lang->line ( 'at_enemy_gold' ); ?></td>
									<td class="left">
										<span class="goldColor">
											<?php echo $enemy_gold; ?>
										</span>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" width="50%">
							<table border="0" width="100%" cellpadding="10" cellspacing="11">
								<tr>
									<td colspan="2" class="left" id="header">
										<?php echo $this->lang->line ( 'at_times' ); ?>
									</td>
								</tr>
								<tr>
									<td class="right"><?php echo $this->lang->line ( 'at_arrival_time' ); ?></td>
									<td class="left"><?php echo $arrival_time; ?></td>
								</tr>
								<tr>
									<td class="right"><?php echo $this->lang->line ( 'at_return_time' ); ?></td>
									<td class="left"><?php echo $return_time; ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td colspan="<?php echo $count; ?>">
							<hr style="border-bottom:1px solid #fff;"/>
						</td>
					</tr>
					<tr>
						<?php
							foreach ( $column as $key => $value )
							{
								?>
									<td class="left">
										<img src="<?php echo $value['img_path']; ?>soldiers/<?php echo $value['soldier']; ?>.gif" title="<?php echo $this->lang->line ( 'ac_' . $value['soldier'] ); ?>" alt="<?php echo $this->lang->line ( 'ac_' . $value['soldier'] ); ?>" width="100px" height="100px"/>
										<br />
										<?php
											echo form_input ( $value['soldier'] , $value['value'] , 'style="width:98px;" maxlength="6"' );
										?>
									</td>
								<?php
							}
						?>
					</tr>
					<tr>
						<td colspan="<?php echo $count; ?>" id="footer">
							<?php echo form_submit ( 'attack' , $this->lang->line ( 'at_attack' ) ); ?>
							
							<a href="<?php echo $base_url;?>field/<?php echo $enemy_name; ?>">
								<?php echo form_button ( 'cancel' , $this->lang->line ( 'at_cancel' )  ); ?>
							</a>
						</td>
					</tr>
				</table>
			<?php echo form_close (); ?>
		</div>
	</div>
</div>