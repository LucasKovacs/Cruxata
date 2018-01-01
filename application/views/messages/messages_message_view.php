<script type="text/javascript">
 $(document).ready(function() {
	// Count up to 20 words and stop
	$("#message").counter({
		msg: '<?php echo $this->lang->line ( 'me_characters' ); ?>',
	    type: 'char',
	    goal: <?php echo MAX_CHARACTERS; ?>,
	    count: 'up',
	});
 });
</script>
<div id="section">
	<div id="messages">
		<div id="title">
			<?php echo $this->lang->line ( 'me_send_private_message' ) . '"' . $user_name . '"'; ?>
		</div>
		<div id="infoContainer">
			<?php echo form_open ( '' , 'name="private-message-form"' ); ?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<?php echo $message; ?>
					<tr>
						<td id="header" class="left">
							<?php echo form_label ( $this->lang->line ( 'me_write_message' ) , 'message' ); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo form_textarea ( 'message' , $text , 'id="message" style="width:99%;height:100px;"' ); ?>
						</td>
					</tr>
					<tr id="footer">
						<td><?php echo form_submit ( 'submit' , $this->lang->line ( 'me_send_message' ) ); ?></td>
					</tr>
				</table>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>