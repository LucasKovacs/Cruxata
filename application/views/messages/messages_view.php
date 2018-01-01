<script type="text/javascript">
$(function () {
	$('#checkall').click(function () {
		$(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
	});
});
</script>
<div id="section">
	<div id="messages">
		<div id="title">
			<?php echo $this->lang->line ( 'me_title' ); ?>
		</div>
		<div id="infoContainer">
			<?php
				echo
				form_open ( '' , 'name="action-form"' )
				.
				form_hidden ( 'message_type' , $message_type );
			?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td><?php echo form_checkbox ( 'checkall' , '' , '' , 'id="checkall"' ); ?></td>
						<td id="header" width="225px"><?php echo $this->lang->line ( 'me_from' ); ?></td>
						<td id="header" width="450px"><?php echo $this->lang->line ( 'me_subject' ); ?></td>
						<td id="header" width="225px"><?php echo $this->lang->line ( 'me_date' ); ?></td>
					</tr>
						<?php echo $all_messages; ?>
					<tr>
						<td colspan="4" id="footer">
							<?php
							$options =	array (
												'checked_read' => $this->lang->line ( 'me_read_selected' ),
												'unchecked_read' => $this->lang->line ( 'me_read_no_selected' ),
												'all_read' => $this->lang->line ( 'me_read_all_messages' ),
												'checked_delete' => $this->lang->line ( 'me_delete_selected' ),
												'unchecked_delete' => $this->lang->line ( 'me_delete_no_selected' ),
												'all_delete' => $this->lang->line ( 'me_delete_all_messages' )
											  );
			
							echo form_dropdown ( 'messagesactions' , $options , '' , 'class="select" onChange="javascript:document.action-form.submit()"' );
							echo ' ';
							echo form_submit ( 'submit' , $this->lang->line ( 'me_submit' ) );
			
							echo form_close();
							?>
						</td>
					</tr>
				</table>
		</div>
	</div>
</div>