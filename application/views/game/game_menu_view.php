				<!-- LEFTMENU -->
				<div id="menu">
					<ul id="links">
						<?php
							foreach ( $sections as $section => $info )
							{
						?>
								<li>
									<a class="menubutton" href="<?php echo $base_url . ( ( $section == 'field' ) ? $section . '/' . $user_name : $section ); ?>" target="_self">
										<span class="textlabel">
											<?php echo $info[0]; ?>
										</span>
									</a>
								</li>
						<?php
							}
						?>
					</ul>
				</div>
				<!-- END LEFTMENU -->

				<!-- CONTENT AREA -->
				<div id="sectionContainer">
