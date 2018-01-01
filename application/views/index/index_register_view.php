<body>
	<div class="mainContainer">
	<!-- HEADER -->
		<div id="pageContainer">
			<div id="page">
				<div id="mainLoginImageTopLeft"><!-- CONTAINS THE LOGIN MAIN IMAGE --></div>
				<div id="mainLoginTextTopLeft">
					<a href="<?php echo $base_url; ?>register"><?php echo $this->lang->line ( 'pm_register' ); ?></a>
				</div>
				<div id="mainLoginImageTopRight"><!-- CONTAINS THE LOGIN MAIN IMAGE --></div>
				<div id="mainLoginTextTopRight">
					<a href="<?php echo $base_url; ?>community" target="_blanck"><?php echo $this->lang->line ( 'pm_community' ); ?></a>
				</div>
				<div id="mainLoginImage"><!-- CONTAINS THE LOGIN MAIN IMAGE --></div>
				<div id="header" class="header">
					<div id="clockClass">
						<span id="clock"><!-- CLOCK --></span>
					</div>
					<div id="optionsBar">
						<!-- PROBABLE CONTENT - NOT FOR NOW -->
					</div>
				</div>
				<!-- END HEADER -->
				<div id="section">
					<div id="sectionContainer">
						<div id="sectionOptions">
							<ul>
								<li>
									<a href="<?php echo $base_url; ?>"><?php echo $this->lang->line ( 'pm_start' ); ?></a>
								</li>
								<li>
									<a href="<?php echo $base_url; ?>"><?php echo $this->lang->line ( 'pm_about' ); ?></a>
								</li>
								<li>
									<a href="<?php echo $base_url; ?>"><?php echo $this->lang->line ( 'pm_media' ); ?></a>
								</li>
								<li>
									<a href="<?php echo $base_url; ?>rules" class="ajax cboxElement"><?php echo $this->lang->line ( 'pm_rules' ); ?></a>
								</li>
							</ul>
						</div>					
						<?php echo form_open ( 'register' , 'name="register-form"' ); ?>
						<div id="sectionRegister">
							<table width="100%">
								<tr>
									<td width="50%" align="left">
										<?php
											echo form_label ( $this->lang->line ( 're_username' ) , 'username' );
											echo form_input ( 'username' , set_value( 'username' ) );
											echo form_label ( $this->lang->line ( 're_password' ) , 'password' );
											echo form_password ( 'password' , '' , 'autocomplete="off"');
										?>
									</td>
									<td width="50%" align="left">
									<?php
										echo form_label ( $this->lang->line ( 're_email' ) , 'email' );
										echo form_input ( 'email' , set_value( 'email' ) );
										echo form_label ( $this->lang->line ( 're_select_kingdom' ) , 'kingdom' );
										$options	= array (
																1 => $this->lang->line ( 'ge_franks' ),
																2 => $this->lang->line ( 'ge_germanic' ),
																3 => $this->lang->line ( 'ge_hungary' ),
																4 => $this->lang->line ( 'ge_english' )
															);
										echo form_dropdown ( 'kingdom' , $options );
									?>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<div style="display:inline">
										<?php 
											echo form_label ( '<a href="'. $base_url .'terms-and-conditions" class="ajax">' . $this->lang->line ( 're_terms_and_conditions' ) . '</a>' , 'terms[]' );
											echo form_checkbox ( 'terms[]'  ); 
										?>
										</div>
										<div id="registerButton">
											<?php echo form_submit ( 'submit' , $this->lang->line ( 're_register' ) ); ?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div id="sectionRegisterErrors">
						<?php echo ( $error_msg == '' ) ? validation_errors() : $error_msg; ?>
						<?php echo form_close(); ?>
					</div>
				</div>
