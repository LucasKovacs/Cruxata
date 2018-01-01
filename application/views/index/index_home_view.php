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
						<div id="descriptionText">
							<?php echo $this->lang->line ( 'lo_description' ); ?>
						</div>
					</div>
					<div id="sectionLogin">
						<?php echo form_open ( '' , 'name="user-login-form"' ); ?>
						<div id="loginLabel">							
							<div id="loginButton">
								<?php echo form_submit ( 'login' , $this->lang->line ( 'lo_login' ) ); ?>							
							</div>
							<div id="forgotText">
								<a href="<?php echo $base_url; ?>lost-password"><?php echo $this->lang->line ( 'lo_lost_password' ); ?></a>
							</div>
						</div>
						<div id="loginFields">
							<?php
								echo form_label ( 'Email' , 'email' );
								echo form_input ( 'email' );
								echo form_label ( 'Clave' , 'password' );
								echo form_password ( 'password' );
								echo '<span style="color:#037FA8;">' . $error_msg . '</span>';
							?>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
