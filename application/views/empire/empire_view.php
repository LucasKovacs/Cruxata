<script type="text/javascript">$(document).ready(function(){$('#armory_countdown').countdown({until: <?php echo $a_time;?>,compact: true,expiryText: '<?php echo $lang; ?>', expiryUrl: '<?php echo $base_url . 'empire'; ?>'});$('#academy_countdown').countdown({until: <?php echo $c_time;?>,compact: true,expiryText: '<?php echo $lang; ?>', expiryUrl: '<?php echo $base_url . 'empire'; ?>'});$('#building_countdown').countdown({until: <?php echo $b_time;?>,compact: true,expiryText: '<?php echo $lang; ?>', expiryUrl: '<?php echo $base_url . 'empire'; ?>'});$('#workshop_countdown').countdown({until: <?php echo $d_time;?>,compact: true,expiryText: '<?php echo $lang; ?>', expiryUrl: '<?php echo $base_url . 'empire'; ?>'});});</script>
<div id="section">
	<div id="empire">
		<div id="title">
			<?php echo $this->lang->line ( 'em_empire_who' ) . ' "' . $user_name . '"'; ?>
		</div>
		<div id="subtitle">
			<a href="<?php echo $base_url . 'field/' . $user_name; ?>" title="<?php echo $this->lang->line ( 'em_tt_position' ); ?>">
				<?php echo $location; ?>
			</a>
		</div>
		<div id="infoContainer">
			<div id="castle" class="fleft">

				<img src="<?php echo $img_path; ?>castles/<?php echo $user_castle_img; ?>.gif" height="200px" width="200px">

			</div>
			<div id="building">
				<table width="450px">
					<tr>
						<td id="header"><strong><?php echo $this->lang->line ( 'em_empire_data' ); ?></strong></td>
					</tr>
					<tr>
						<td>
							<?php echo $this->lang->line ( 'em_points' ) .  $user_points; ?>
						</td>
					</tr>
				</table>
				<table width="450px">
					<tr>
						<td id="header"><strong><?php echo $this->lang->line ( 'em_empire_activity' ); ?></strong></td>
					</tr>
					<tr>
						<td><?php echo $this->lang->line ( 'em_current_armory' ) . $armory_title . $armory_timer; ?></td>
					</tr>
					<tr>
						<td><?php echo $this->lang->line ( 'em_current_building' ) . $building_title . $building_timer; ?></td>
					</tr>
					<tr>
						<td><?php echo $this->lang->line ( 'em_current_academy' ) .  $academy_title . $academy_timer; ?></td>
					</tr>
					<tr>
						<td><?php echo $this->lang->line ( 'em_current_workshop' ) .  $workshop_title . $workshop_timer; ?></td>
					</tr>
					<tr>
						<td><?php echo $this->lang->line ( 'em_current_attacks' ) .  $current_attacks; ?></td>
					</tr>
				</table>
			</div>
		</div>
		<div id="watchtowerContainer">
			<div id="watchtower" class="fleft">
				<?php echo $building_watchtower; ?>
			</div>
			<div id="watchtowerAlert">
				<?php echo $attack_close; ?>
			</div>
		</div>
	</div>
</div>
</div>