<script type="text/javascript">$(document).ready(function(){$('#countdown').countdown({until: <?php echo $time;?>,compact: true,expiryText: '<?php echo $lang; ?>', expiryUrl: '<?php echo $base_url . 'armory'; ?>'});});</script>
<div id="section">
	<div id="armory">
		<div id="title">
			<?php echo $this->lang->line ( 'ar_armory' ); ?>
		</div>
		<div id="infoContainer">
			<?php echo form_open ( '' , 'name="armory-form"' ); ?>
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<?php
					foreach ( $armory_rows as $key => $data )
					{
				?>
						<tr>
							<table width="100%">
								<tr>
									<td id="header" class="left" colspan="3">
										<?php echo '<strong>' . $this->lang->line ( 'ar_' . $data['weapon'] ) . '</strong>' . $data['level']; ?>
									</td>
								</tr>
								<tr>
									<td width="150px" height="150px">
										<img src="<?php echo $data['img_path']; ?>armory/<?php echo $data['weapon']; ?>.gif" border="0" width="150px" height="150px"/>
									</td>
									<td valign="top" class="left">
										<table width="100%">
											<tr>
												<td colspan="3">
													<?php echo $this->lang->line ( 'ar_require' ) . $data['level_to_up'] . ':'; ?>
												</td>
											</tr>
											<tr>
												<td class="center" height="50px">
													<div align="center">
														<div id="imgWood">
															<img src="<?php echo $data['img_path']; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'ar_wood' ); ?>"/>
														</div>
													</div>
												</td>
												<td class="center">
													<div align="center">
														<div id="imgStone">
															<img src="<?php echo $data['img_path']; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'ar_stone' ); ?>"/>
														</div>
													</div>						
												</td>
												<td class="center">
													<div align="center">
														<div id="imgGold">
															<img src="<?php echo $data['img_path']; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'ar_gold' ); ?>"/>
														</div>
													</div>						
												</td>
											</tr>
											<tr>
												<td class="center"><?php echo $data['required_wood']; ?></td>
												<td class="center"><?php echo $data['required_stone']; ?></td>
												<td class="center"><?php echo $data['required_gold']; ?></td>
											</tr>
											<tr>
												<td colspan="3" height="50px">
													<?php echo $this->lang->line ( 'ar_time' ); ?><strong><?php echo $data['required_time']; ?></strong>
												</td>
											</tr>
											<tr>
												<td colspan="3">
													<?php 
														echo ( ( $data['type'] == 'offensive' ) ? $this->lang->line ( 'ar_attack' ) : $this->lang->line ( 'ar_defense' ) ) . 
																'<strong>' . $data['current_power'] .
																'% <span class="plusColor">(+' . $data['next_power'] . '%)</span></strong>'; 
													?>
												</td>
											</tr>
										</table>
									</td>
									<td width="100px">
										<?php echo $data['submit']; ?>
									</td>
								</tr>
								<tr> 
									<td colspan="3" class="justify italic" height="40px">
										<?php echo $this->lang->line ( 'ar_' . $data['weapon'] . '_description' ); ?>
									</td>
								</tr>
								<tr>
									<td colspan="3" height="20px"></td>
								</tr>
							</table>
						</tr>
				<?php					
					}
				?>
			</table>
			<?php echo form_close(); ?>	
		</div>
	</div>
</div>