<div id="section">
	<div id="productivity">
		<div id="title">
			<?php echo $this->lang->line ( 'pr_title' ); ?>
		</div>
		<div id="infoContainer">
			<table border="0" width="100%" cellpadding="10" cellspacing="11">
				<tr>
					<td width="61px" id="header"><?php echo $this->lang->line ( 'pr_resources' ); ?></td>
					<td id="header"><?php echo $this->lang->line ( 'pr_base' ); ?></td>
					<td id="header"><?php echo $this->lang->line ( 'pr_per_hour' ); ?></td>
					<td id="header"><?php echo $this->lang->line ( 'pr_per_day' ); ?></td>
				</tr>
				<?php 
					foreach ( $production_rows as $key => $data )
					{
				?>		
				<tr>
					<th>
						<div align="center">
							<div id="<?php echo $data['image']; ?>">
								<img src="<?php echo $img_path; ?>clear.gif" border="0" width="48px" height="32px" title="<?php echo $data['resource']; ?>"/>
							</div>
						</div>						
					</th>
					<td>
						<?php
							echo
								( ( $data['base'] == 0 ) ? '<a title="' . $this->lang->line ( 'pr_base_production' ) . '">0</a>' : '<a title="' . $this->lang->line ( 'pr_base_production' ) . '">' . $this->functions->format_number ( $data['base'] ) ) . '</a>' .
								( ( $data['mine'] == 0 ) ? '' : '<font color="#9acd32"> + (<a style="color:#9acd32;" title="' . $this->lang->line ( 'pr_mine_production' ) . '">' . $this->functions->format_number ( $data['mine'] ) . '</a>)</font>' );
						?>
					</td>
					<td>
						<?php echo ( ( $data['hour'] == 0 ) ? '-' : '<font color="#9acd32"> +' . $this->functions->format_number ( $data['hour'] ) ) . '</font>'; ?>
					</td>
					<td>
						<?php echo ( ( $data['day'] == 0 ) ? '-' : '<font color="#9acd32"> +' . $this->functions->format_number ( $data['day'] ) ) . '</font>'; ?>
					</td>
				</tr>
				<?php		
					}
								
				?>
			</table>
			<table border="0" width="650px" cellpadding="1" cellspacing="2">
			<tr>
				<td width="230px" valign="top">
					<table width="100%">
						<tr>
							<td colspan="2" id="header"><?php echo $this->lang->line ( 'bu_building_sawmill' ); ?></td>
						</tr>
						<tr>
							<td><?php echo $this->lang->line ( 'pr_level' ); ?></td>
							<td><?php echo $this->lang->line ( 'pr_production' ); ?></td>
						</tr>
						<?php 
							foreach ( $sawmill_rows as $key => $data )
							{
						?>
						<tr<?php echo $data['color']; ?>>
							<td<?php echo $data['color']; ?>><?php echo $data['level']; ?></td>
							<td<?php echo $data['color']; ?>><?php echo $data['production']; ?></td>
						</tr>
						<?php
							}				
						?>
					</table>
				</td>
				<td width="230px" valign="top">
					<table width="100%">
						<tr>
							<td colspan="2" id="header"><?php echo $this->lang->line ( 'bu_building_stonemine' ); ?></td>
						</tr>
						<tr>
							<td><?php echo $this->lang->line ( 'pr_level' ); ?></td>
							<td><?php echo $this->lang->line ( 'pr_production' ); ?></td>
						</tr>
						<?php 
							foreach ( $stonemine_rows as $key => $data )
							{
						?>
						<tr<?php echo $data['color']; ?>>
							<td<?php echo $data['color']; ?>><?php echo $data['level']; ?></td>
							<td<?php echo $data['color']; ?>><?php echo $data['production']; ?></td>
						</tr>
						<?php
							}				
						?>					</table>
				</td>
				<td width="230px" valign="top">
					<table width="100%">
						<tr>
							<td colspan="2" id="header"><?php echo $this->lang->line ( 'bu_building_goldmine' ); ?></td>
						</tr>
						<tr>
							<td><?php echo $this->lang->line ( 'pr_level' ); ?></td>
							<td><?php echo $this->lang->line ( 'pr_production' ); ?></td>
						</tr>
						<?php 
							foreach ( $goldmine_rows as $key => $data )
							{
						?>
						<tr<?php echo $data['color']; ?>>
							<td<?php echo $data['color']; ?>><?php echo $data['level']; ?></td>
							<td<?php echo $data['color']; ?>><?php echo $data['production']; ?></td>
						</tr>
						<?php
							}				
						?>
					</table>
				</td>
				<td width="230px" valign="top">
					<table width="100%">
						<tr>
							<td colspan="2" id="header"><?php echo $this->lang->line ( 'bu_building_barracks' ); ?></td>
						</tr>
						<tr>
							<td><?php echo $this->lang->line ( 'pr_level' ); ?></td>
							<td><?php echo $this->lang->line ( 'pr_limit' ); ?></td>
						</tr>
						<?php 
							foreach ( $barracks_rows as $key => $data )
							{
						?>
						<tr<?php echo $data['color']; ?>>
							<td<?php echo $data['color']; ?>><?php echo $data['level']; ?></td>
							<td<?php echo $data['color']; ?>><?php echo $data['production']; ?></td>
						</tr>
						<?php
							}				
						?>
					</table>
				</td>
			</tr>
			</table>
		</div>
	</div>
</div>