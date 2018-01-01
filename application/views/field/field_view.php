<div id="section">
	<div id="field">
		<div id="title">
			<?php //echo $this->lang->line ( 'fi_title' ); ?>
		</div>
		<div id="infoContainer">
			<?php echo form_open( '' , 'name="field-form"' ); ?>
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td colspan="4" width="50px" class="left" id="header">
							<?php
		
								$options = array (
													'franks' => $this->lang->line ( 'ge_franks' ) ,
													'germanic' => $this->lang->line ( 'ge_germanic' ) ,
													'hungary' => $this->lang->line ( 'ge_hungary' ) ,
													'english' => $this->lang->line ( 'ge_english' ) );
								echo
								$this->lang->line ( 'fi_kingdom' ) .
								' ' .
								form_dropdown ( 'kingdom' , $options , $selected , 'class="select" onChange="javascript:document.field-form.submit()"' ) .
								' ' .
								$this->lang->line ( 'fi_position' ) .
								' ' .
								form_submit ( 'left' , '<' , 'class="select" title="' . $this->lang->line ( 'fi_prev_page' ) . '"' ) .
								' ' .
								form_input ( 'page' , ( ( $current_page == '' ) ? set_value ( 'page' ) : $current_page ) , 'maxlength="3" style="width:30px;height:19px;" class="select"' ) . ' / ' . $page_count .
								form_hidden ( 'h_page' , ( ( $current_page == '' ) ? set_value ( 'page' ) : $current_page ) ) .
								' ' .
								form_submit ( 'right' , '>' , 'class="select" title="' . $this->lang->line ( 'fi_next_page' ) . '"' );
							?>
						</td>
					</tr>
					<?php 
						foreach ( $field_rows as $row_key => $row )
						{
					?>		
						<tr>
							<?php
								foreach ( $row as $col_key => $col  )
								{
							?>
								<td class="col">
									<table style="height:62px;">
										<tr>
											<td rowspan="4" width="50px"><?php echo $col['castle']; ?></td>
										</tr>
										<tr>
											<td width="100px" id="<?php echo ( $col['title'] != '' ) ? 'headerCol' : ''; ?>"><?php echo $col['title']; ?></td>
										</tr>
										<tr>
											<td width="100px"><?php echo $col['actions']; ?></td>
										</tr>
										<tr>
											<td width="100px">&nbsp;</td>
										</tr>
									</table>
								</td>
							<?php		
								}
							?>
						</tr>
					<?php	
						}					
					?>
				</table>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>