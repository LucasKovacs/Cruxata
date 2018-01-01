<div id="section">
	<div id="attack">
		<div id="title">
			<?php echo $this->lang->line ( 'mi_title' ); ?> "<?php echo $enemy_name; ?>"
		</div>
		<div id="infoContainer">
			<?php echo form_open ( '' , 'name="attack-form"' ); ?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td valign="top" width="50%">
							<table border="0" width="100%" cellpadding="10" cellspacing="11">
								<tr>
									<td colspan="2" id="header">
										<strong><?php echo $this->lang->line ( 'mi_missions' ); ?></strong>
									</td>
								</tr>
								<tr>
									<td width="50%" class="right" height="20px">
										<?php
											echo form_radio (
																array (
																			'name' => 'mission' ,
																			'id' => '1' ,
																			'value' => '1' ,
																			'checked' => TRUE ,
																			'onclick' => '$(\'#attack_duration\').show(\'fast\');$(\'#explore_duration\').hide(\'fast\');$(\'#invade_duration\').hide(\'fast\');'
																		)
															);
										?>
									</td>
									<td width="50%" class="left">
										<?php echo $this->lang->line ( 'mi_attack' ); ?>
									</td>
								</tr>
								<tr>
									<td width="50%" class="right" height="20px">
										<?php
				/*							echo form_radio (
																array (
																			'name' => 'mission' ,
																			'id' => '2' ,
																			'value' => '2' ,
																			'onclick' => '$(\'#attack_duration\').hide(\'fast\');$(\'#explore_duration\').show(\'fast\');$(\'#invade_duration\').hide(\'fast\');'
																		)
															); */
										?>
									</td>
									<td width="50%" class="left">
										<?php //echo $this->lang->line ( 'mi_explore' ); ?>
									</td>
								</tr>
								<tr>
									<td width="50%" class="right" height="20px">
										<?php
								/*			echo form_radio (
																array (
																			'name' => 'mission' ,
																			'id' => '3' ,
																			'value' => '3' ,
																			'onclick' => '$(\'#attack_duration\').hide(\'fast\');$(\'#explore_duration\').hide(\'fast\');$(\'#invade_duration\').show(\'fast\');'
																		)
															); */
										?>
									</td>
									<td width="50%" class="left">
										<?php //echo $this->lang->line ( 'mi_invade' ); ?>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" width="50%">
							<table border="0" width="100%" cellpadding="10" cellspacing="11">
								<tr>
									<td colspan="2" class="left" id="header">
										<strong><?php echo $this->lang->line ( 'mi_times' ); ?></strong>
									</td>
								</tr>
								<tr>
									<td width="50%" height="20px" class="right">
										<?php echo $this->lang->line ( 'mi_arrival_time' ); ?>
									</td>
									<td width="50%" class="left">
										<strong><?php echo $arrival_time; ?></strong>
									</td>
								</tr>
								<tr>
									<td width="50%" class="right" height="20px">
										<?php echo $this->lang->line ( 'mi_mission_duration' ); ?>
									</td>
									<td width="50%" class="left">
										<span id="attack_duration" style="display:inline">
											<strong><?php echo $this->functions->format_time ( ATTACK_DURATION ); ?></strong>
										</span>
										<span id="explore_duration" style="display:none">
											<strong><?php echo $this->functions->format_time ( EXPLORE_DURATION ); ?></strong>
										</span>
										<span id="invade_duration" style="display:none">
											<strong><?php echo $this->functions->format_time ( INVADE_DURATION ); ?></strong>
										</span>
									</td>
								</tr>
								<tr>
									<td width="50%" class="right" height="20px">
										<?php echo $this->lang->line ( 'mi_return_time' ); ?>
									</td>
									<td width="50%" class="left">
										<strong><?php echo $return_time; ?></strong>
									</td>
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
							<?php echo form_submit ( 'attack' , $this->lang->line ( 'mi_start_mission' ) ); ?>

							<a href="<?php echo $base_url;?>field/<?php echo $enemy_name; ?>">
								<?php echo form_button ( 'cancel' , $this->lang->line ( 'mi_cancel_mission' )  ); ?>
							</a>
						</td>
					</tr>
				</table>
			<?php echo form_close (); ?>
		</div>
	</div>
</div>