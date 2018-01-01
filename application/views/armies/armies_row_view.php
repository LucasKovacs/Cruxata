<script type="text/javascript">$(document).ready(function(){var div='#cm<?php echo $value;?>';$(div).mouseover(function(){$.cursorMessage('<?php echo $current_army;?>',{hideTimeout:0})});$(div).mouseout($.hideCursorMessage);$('#cds<?php echo $value;?>').countdown({until: <?php echo $start;?>,compact: true, expiryUrl: '<?php echo $base_url . 'armies'; ?>'});$('#cda<?php echo $value;?>').countdown({until: <?php echo $attack;?>,compact: true, expiryUrl: '<?php echo $base_url . 'armies'; ?>'});$('#cde<?php echo $value;?>').countdown({until: <?php echo $end;?>,compact: true, expiryUrl: '<?php echo $base_url . 'armies'; ?>'});});</script>
<tr>
	<td>
		<table width="100%">
			<tr>
				<th rowspan="6" width="15px">
					<?php echo $count; ?>
				</th>
			</tr>
			<tr>
				<th rowspan="4" width="100px">
					<img src="<?php echo $img_path; ?>castles/<?php echo $user_castle_img; ?>.gif" width="85px" height="85px" border="1"/>
					<?php
					if ( $show_cancel )
					{
					?>
						<div style="position:relative;background-color:#000;width:85px;top:-15px;left:9px;height:14px;">
							<a href="<?php echo $base_url . 'armies/cancel/' . $army_id; ?>" onclick="return confirm('<?php echo str_replace ( '%s' , $user_name , $this->lang->line ( 'am_cancel_attack_confirm' ) ); ?>');"><?php echo $this->lang->line ( 'am_cancel_attack' ); ?></a>
						</div>
					<?php	
					}
					?>
				</th>
				<td><?php echo $attack_in_progress . $arrival_time . $return_time; ?></td>
			</tr>
			<tr>
				<td>
					<a href="#" id="cm<?php echo $value; ?>">
						<font color="#990f1f">
							<?php echo $this->lang->line ( 'am_current_troops' ); ?>
						</font>
					</a>
				</td>
			</tr>
			<tr>
				<td><?php echo $gold_captured; ?></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td width="100px" id="header" style="height:5px;">
					<strong><a href="<?php echo $base_url;?>field/<?php echo $user_name ?>"><?php echo $user_name ?></a></strong>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</td>
</tr>