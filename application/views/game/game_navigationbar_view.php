<body>
	<div class="mainContainer">
	<!-- HEADER -->
		<div id="pageContainer">
			<div id="page">
				<div id="flag">
					<!-- FLAG TOP -->
					<div id="logo">
						<!-- CRUXATA LOGO -->
					</div>
				</div>
				<div id="header" class="header">
					<div id="clockClass">
						<span id="clock"><!-- CLOCK --></span>
					</div>
					<div id="optionsBar">
						<ul>
							<li>
								<a href="<?php echo $base_url; ?>friends"><?php echo $this->lang->line ( 'ge_friends' ); ?></a>
							</li>
							<li>
								<a href="<?php echo $base_url; ?>tutorial"><?php echo $this->lang->line ( 'ge_tutorial' ); ?></a>
							</li>
							<li>
								<a href="<?php echo $base_url; ?>statistics"><?php echo $this->lang->line ( 'ge_statistics' ); ?></a>
							</li>
							<li>
								<a href="<?php echo $base_url; ?>search"><?php echo $this->lang->line ( 'ge_search' ); ?></a>
							</li>
							<li>
								<a href="<?php echo $base_url; ?>options"><?php echo $this->lang->line ( 'ge_options' ); ?></a>
							</li>
							<li>
								<a href="<?php echo $base_url; ?>logout" onClick="return confirm('<?php echo $this->lang->line ( 'ge_logout_confirm' ); ?>')"><?php echo $this->lang->line ( 'ge_logout' ); ?></a>
							</li>
						</ul>
					</div>
					<ul id="resources">
						<li class="wood">
							<a href="<?php echo $base_url; ?>productivity" border="0">
								<img src="<?php echo $img_path; ?>clear.gif" width="48px" height="32px" border="1" title="<?php echo $this->lang->line ( 'ge_wood' ); ?>"/>
							</a>
							<span class="value">
								<?php echo $wood; ?>
							</span>
						</li>
						<li class="stone">
							<a href="<?php echo $base_url; ?>productivity" border="0">
								<img src="<?php echo $img_path; ?>clear.gif" width="48px" height="32px" border="1" title="<?php echo $this->lang->line ( 'ge_stone' ); ?>"/>
							</a>
							<span class="value">
								<?php echo $stone; ?>
							</span>
						</li>
						<li class="gold">
							<a href="<?php echo $base_url; ?>productivity" border="0">
								<img src="<?php echo $img_path; ?>clear.gif" width="48px" height="32px" border="1" title="<?php echo $this->lang->line ( 'ge_gold' ); ?>"/>
							</a>
							<span class="value">
								<?php echo $gold; ?>
							</span>
						</li>
						<li class="diamonds">
							<a href="<?php echo $base_url; ?>empire" border="0">
								<img src="<?php echo $img_path; ?>clear.gif" width="48px" height="32px" border="1" title="<?php echo $this->lang->line ( 'ge_diamonds' ); ?>"/>
							</a>
							<span class="value">
								<?php echo $diamonds; ?>
							</span>
						</li>
						<li class="soldiers">
							<a href="<?php echo $base_url; ?>academy" border="0">
								<img src="<?php echo $img_path; ?>clear.gif" width="48px" height="32px" border="1" title="<?php echo $this->lang->line ( 'ge_soldiers' ); ?>"/>
							</a>
							<span class="value">
								<?php echo $soldiers; ?>
							</span>
						</li>
						<li class="messages">
							<a href="<?php echo $base_url; ?>messages" border="0">
								<img src="<?php echo $img_path; ?>clear.gif" width="48px" height="32px" border="0" title="<?php echo $this->lang->line ( 'ge_messages' ); ?>"/>
							</a>
							<span class="value">
								<?php echo $messages; ?>
							</span>
						</li>
						<li class="<?php echo $style; ?>">
							<?php echo $notices; ?>
						</li>
					</ul>
				</div>
				<!-- END HEADER -->
