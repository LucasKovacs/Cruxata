<div id="section">
	<div id="market">
		<div id="title">
			<?php echo $this->lang->line ( 'mk_market' ); ?>
		</div>
		<div id="infoContainer">
			<?php 
				echo form_open ( '' , 'name="market-form"' ); 
				echo form_hidden ( array ( 'buy_resource' => '0' , 'sell_resource' => '0' ) ); 				
			?>
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<tbody>
					<tr>
						<td id="header" class="left">
							<?php echo $this->lang->line ( 'mk_resource' ); ?>
						</td>
						<td id="header" class="left">
							<?php echo $this->lang->line ( 'mk_current_amount' ); ?>
						</td>
						<td id="header" class="left">
							<?php echo $this->lang->line ( 'mk_previous_amount' ); ?>
						</td>
						<td id="header" class="left">
							<?php echo $this->lang->line ( 'mk_ratio' ); ?>
						</td>
					</tr>
					<?php
						foreach ( $market_table as $key => $data )
						{
					?>
							<tr>
								<td>
									<div align="center">
										<div id="<?php echo $data['image']; ?>">
											<img src="<?php echo $img_path;?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $data['title']; ?>">
										</div>
									</div>
								</td>
								<td>
									<div align="center">
										<?php echo $data['resource_available']; ?> <?php echo $data['resource_difference']; ?>
									</div>
								</td>
								<td>
									<div align="center">
										<?php echo $data['resource_previous']; ?>
									</div>
								</td>
								<td>
									<div align="right">
										<?php echo $data['resource_final_ratio']; ?> %
									</div>
								</td>
							</tr>
					<?php		
						}
					?>
				</tbody>
			</table>
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<tbody>
					<tr>
						<td id="header" class="center" colspan="3">
							<?php echo $this->lang->line ( 'mk_exchange_title' ); ?>
						</td>
					</tr>
					<tr>
						<td width="100px"></td>
						<td>
							<div align="center">
								<div id="imgWood">
									<img src="<?php echo $img_path;?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'mk_resource_wood' ); ?>">
								</div>
							</div>
						</td>
						<td>
							<div align="center">
								<div id="imgStone">
									<img src="<?php echo $img_path;?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $this->lang->line ( 'mk_resource_stone' ); ?>">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->lang->line ( 'mk_buy' ); ?>
						</td>
						<td>
							1.000
						</td>
						<td>
							500
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->lang->line ( 'mk_sell' ); ?>
						</td>
						<td>
							500
						</td>
						<td>
							1.000
						</td>
					</tr>
				</tbody>
			</table>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>