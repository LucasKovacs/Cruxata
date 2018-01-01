<div id="section">
	<div id="academy">
		<div id="title">
			<?php echo $this->lang->line ( 'ac_academy' ); ?>
		</div>
		<div id="infoContainer">
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<?php 
					foreach ( $academy_rows as $key => $data )
					{
				?>
						<script type="text/javascript">$(document).ready(function(){$('#<?php echo $data['value'];?>').countdown({until: <?php echo $data['time'];?>,compact: true,expiryText: '<?php echo $data['lang']; ?>', expiryUrl: '<?php echo $data['base_url'] . 'academy'; ?>'});});</script>
						<tr>
							<table width="100%">
								<tr>
									<td id="header" class="left" colspan="3">
										<?php echo '<strong>' . $this->lang->line ( 'ac_' . $data['soldier'] ) . '</strong> (' . $data['quantity'] . $this->lang->line ( 'ac_units' ) . ')';?>
									</td>
								</tr>
								<tr>
									<td width="150px" height="150px">
										<img src="<?php echo $data['img_path']; ?>soldiers/<?php echo $data['soldier_image']; ?>.gif" border="0" width="150px" height="150px"/>
									</td>
									<td valign="top" class="left">
										<table width="100%">
											<tr>
												<td colspan="3">
													<?php echo $this->lang->line ( 'ac_price' ); ?>
												</td>
											</tr>
											<tr>
												<td class="center" height="50px">
													<div align="center">
														<div id="imgWood">
															<img src="<?php echo $data['img_path']; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'ac_wood' ); ?>"/>
														</div>
													</div>
												</td>
												<td class="center">
													<div align="center">
														<div id="imgStone">
															<img src="<?php echo $data['img_path']; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'ac_stone' ); ?>"/>
														</div>
													</div>						
												</td>
												<td class="center">
													<div align="center">
														<div id="imgGold">
															<img src="<?php echo $data['img_path']; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'ac_gold' ); ?>"/>
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
													<?php echo $this->lang->line ( 'ac_time' ); ?><strong><?php echo $data['required_time']; ?></strong>
												</td>
											</tr>
										</table>
									</td>
									<td width="100px">
										<?php echo $data['actions']; ?>
									</td>
								</tr>
								<tr> 
									<td colspan="3" class="justify italic" height="40px">
										<?php echo $this->lang->line ( 'ac_' . $data['soldier'] . '_description' ); ?>
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
		</div>
	</div>
</div>