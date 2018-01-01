<div align="center">
	<h3>
		<?php echo $user_name; ?>
		<img src="<?php echo base_url() . IMG_FOLDER; ?>field/field_attack.png" border="0px"/>
		<?php echo $enemy_name; ?>
	</h3>
</div>	
<div align="center">
	<br />
	<br />
	<h3><?php echo $this->lang->line ( 'at_initial_troops' ); ?></h3>
	<br />
	<br />
	<table style="border:1px solid #000" width="200px">
		<tr>
			<td style="border-bottom:1px solid #000" colspan="2">Ejercito de <?php echo $user_name; ?></td>
		</tr>
		<?php 
			$soldiers_attacker	= array();
			$soldiers_defender	= array();
			
			foreach ( $attacker_army as $soldier => $info )
			{
				@$soldiers_attacker[$info['soldier']]++;
			}
			
			if ( $soldiers_attacker != NULL )
			{
				foreach ( $soldiers_attacker as $soldier => $amount )
				{
			?>
				<tr>
					<td><?php echo $this->lang->line ( 'ac_' . $soldier );?></td>
					<td><?php echo $amount; ?></td>
				</tr>
			<?php
				}	
			}
			
		?>
	</table>
	<br />
	<br />
	<table style="border:1px solid #000" width="200px">
		<tr>
			<td style="border-bottom:1px solid #000" colspan="2">Ejercito de <?php echo $enemy_name; ?></td>
		</tr>
		<?php 
			foreach ( $defender_army as $soldier => $info )
			{
				@$soldiers_defender[$info['soldier']]++;
			}
			
			if ( $soldiers_defender != NULL )
			{
				foreach ( $soldiers_defender as $soldier => $amount )
				{
			?>
				<tr>
					<td><?php echo $this->lang->line ( 'ac_' . $soldier );?></td>
					<td><?php echo $amount; ?></td>
				</tr>
			<?php
				}				
			}
			else
			{
			?>
				<tr>
					<td colspan="2"><?php echo $this->lang->line ( 'at_no_troops' ); ?></td>
				</tr>
			<?php
			}
		?>
	</table>
</div>
<div align="center">
	<br />
	<br />
	<h3><?php echo $this->lang->line ( 'at_end_troops' ); ?></h3>
	<br />
	<br />
	<table style="border:1px solid #000" width="200px">
		<tr>
			<td style="border-bottom:1px solid #000" colspan="2">Ejercito de <?php echo $user_name; ?></td>
		</tr>
		<?php 
			$soldiers_attacker	= array();
			$soldiers_defender	= array();
			
			foreach ( $attacker_army as $soldier => $info )
			{
				if ( $info['life'] > 0 )
				{
					@$soldiers_attacker[$info['soldier']]++;	
				}
			}
			
			if ( $soldiers_attacker != NULL )
			{
				foreach ( $soldiers_attacker as $soldier => $amount )
				{
			?>
				<tr>
					<td><?php echo $this->lang->line ( 'ac_' . $soldier );?></td>
					<td><?php echo $amount; ?></td>
				</tr>
			<?php
				}	
			}
			
		?>
	</table>
	<br />
	<br />
	<table style="border:1px solid #000" width="200px">
		<tr>
			<td style="border-bottom:1px solid #000" colspan="2">Ejercito de <?php echo $enemy_name; ?></td>
		</tr>
		<?php 
			foreach ( $defender_army as $soldier => $info )
			{
				if ( $info['life'] > 0 )
				{
					@$soldiers_defender[$info['soldier']]++;	
				}
			}
			
			if ( $soldiers_defender != NULL )
			{
				foreach ( $soldiers_defender as $soldier => $amount )
				{
			?>
				<tr>
					<td><?php echo $this->lang->line ( 'ac_' . $soldier );?></td>
					<td><?php echo $amount; ?></td>
				</tr>
			<?php
				}				
			}
			else
			{
			?>
				<tr>
					<td colspan="2"><?php echo $this->lang->line ( 'at_no_troops' ); ?></td>
				</tr>
			<?php
			}
		?>
	</table>
</div>
<div align="center">

	<br />
	<br />
	<?php
	
		switch ( $result )
		{
			case 'won':
			
				echo $this->lang->line ( 'at_won_message' ) . str_replace	(	array	( 
																								'%w' , 
																								'%s' , 
																								'%g' 
																							) ,
																					array 	(
																								'<font color="brown">' . $this->functions->format_number ( $stolen_wood ) . '</font>',
																								'<font color="#999999">' . $this->functions->format_number ( $stolen_stone ) . '</font>',
																								'<font color="#808000">' . $this->functions->format_number ( $stolen_gold ) . '</font>'
																							) ,
																					$this->lang->line ( 'at_stolen_resources' ) );
			
			break;
			
			case 'lost':
				echo $this->lang->line ( 'at_lost_message' );
			break;
			
			case 'tie':
				echo $this->lang->line ( 'at_draw_message' );
			break;
		}
	?>
</div>
<div align="center">
	
	<br />
	<br />
	<?php echo $this->lang->line ( 'at_battle_time' ) . $this->functions->format_hour ( $army_current ); ?>

</div>