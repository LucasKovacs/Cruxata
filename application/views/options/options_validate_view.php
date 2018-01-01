<div id="section">
	<div id="options">
		<div id="title">
			<?php echo $this->lang->line ( 'op_validate_account' ); ?>
		</div>	
		<div id="infoContainer">
			<?php echo form_open ( '' , 'name="user-validate-form"' ); ?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td width="50%" class="right"><?php echo $this->lang->line ( 'op_email' ); ?></td>
						<td width="50%" class="left"><?php echo $email; ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo $this->lang->line ( 'op_enter_password' ); ?></td>
						<td class="left"><?php echo form_password ( 'validate_password' , '' , 'autocomplete="off"' ); ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo $this->lang->line ( 'op_enter_email' ); ?></td>
						<td class="left"><?php echo form_input ( 'validate_email' ); ?></td>
					</tr>
					<tr>
						<td colspan="2" id="footer">
							<?php echo form_submit ( 'send' , $this->lang->line ( 'op_request_email' ) ); ?>
						</td>
					</tr>
				</table>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>