<tr>
	<?php echo form_hidden ( 'showmes' . $message_id , '1' ); ?>
    <td>
    	<input name="delmes<?php echo $message_id; ?>" type="checkbox">
    </td>
    <td class="row">
    	<?php
    	if ( $message_sender == '' )
    	{
    		echo $this->lang->line ( 'me_command_center' );

    	}
    	else
    	{
    		echo $message_sender;
    	?>
	    	<a href="<?php echo $base_url; ?>private-message/<?php echo $message_sender; ?>">
	    		<img src="<?php echo $img_path; ?>icons/message.gif" />
	    	</a>
	    <?php
		}
?>
	</td>
	<td class="row">
		<span id="message<?php echo $message_id; ?>" class="<?php echo ( $message_viewed == 0 ) ? 'noread' : ''  ?>">
			<a onclick="$('#message<?php echo $message_id;?>').removeClass('noread');" href="<?php echo $base_url; ?>show-message/<?php echo $message_id . '/' . $message_type; ?>" title="" class="ajax">
				<?php echo $message_subject; ?>
			</a>
		</span>
	</td>
    <td class="row">
    	<?php echo $this->functions->format_hour ( $message_date ); ?>
    	
		<a href="<?php echo $base_url; ?>delete-message/<?php echo $message_id; ?>" title="Eliminar mensaje" style="text-decoration:none">
			<img src="<?php echo $img_path; ?>icons/message_delete.png" width="16px" height="16px" border="0"/>
		</a>
    	
    	<?php   
	    	
	    	if ( $message_viewed == 0 )
	    	{
		?>
				<a href="<?php echo $base_url; ?>mark-read/<?php echo $message_id; ?>" title="Marcar mensaje como le&iacute;do" style="text-decoration:none">
					<img src="<?php echo $img_path; ?>icons/message_read.png" width="16px" height="16px" border="0"/>
				</a>
		<?php    	
	    	}
	    	
    	?>
    </td>
</tr>