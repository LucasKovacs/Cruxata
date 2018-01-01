<div id="section">
	<div id="options">
		<div id="title">
			<?php echo $this->lang->line ( 'op_general_title' ); ?>
		</div>	
		<div id="infoContainer">
			<?php echo form_open ( '' , 'name="user-account-form"' ); ?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td width="50%" class="right"><?php echo $this->lang->line ( 'op_email' ); ?></td>
						<td width="50%" class="left"><?php echo $email; ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo $this->lang->line ( 'op_change_user' ); ?> <!--(<font color="darkred"><em>-2500 diamantes</em></font>)--></td>
						<td class="left"><?php echo form_input ( 'username' , $username ); ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo $this->lang->line ( 'op_old_password' ); ?></td>
						<td class="left"><?php echo form_password ( 'old_password' , '' , 'autocomplete="off"' ); ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo $this->lang->line ( 'op_new_password' ); ?></td>
						<td class="left"><?php echo form_password ( 'first_new_password' , '' , 'autocomplete="off"' ); ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo $this->lang->line ( 'op_new_password2' ); ?></td>
						<td class="left"><?php echo form_password ( 'second_new_password' , '' , 'autocomplete="off"' ); ?></td>
					</tr>
					<tr>
						<td class="right"><?php echo form_checkbox ( 'delete_account[]' , '' , $checked ); ?></td>
						<td class="left"><?php echo $this->lang->line ( 'op_delete_account' ); ?></td>
					</tr>
					<tr>
						<td colspan="2" id="footer">
							<?php echo form_submit ( 'save' , $this->lang->line ( 'op_save' ) ); ?>
						</td>
					</tr>
				</table>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>