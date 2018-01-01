<div id="section">
	<div id="friends">
		<div id="title">
			<?php echo $this->lang->line ( 'fr_title' ); ?>
		</div>
		<div id="subtitle">
			<?php echo $this->lang->line ( 'fr_sub_title' ); ?>
		</div>
		<div id="infoContainer">
			
			<div id="shareUrl">
				<table border="0" width="100%" cellpadding="10" cellspacing="11">
					<tr>
						<td id="header">
							<strong>
								<a href="<?php echo $user_link; ?>">
									<?php echo $user_link; ?>
								</a>
							</strong>
						</td>
					</tr>
				</table>
			</div>
			
			<div id="socialLinks">
				
				<!-- Facebook -->
				<div style="float:left;width:200px">
					<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=169731273219928&version=v2.0";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-share-button" data-href="<?php echo $user_link; ?>" data-type="button_count"></div>
				</div>
				
				<!-- Twitter -->
				<div style="float:left;width:300px">
					<a href="https://twitter.com/share" class="twitter-share-button" data-related="cruxata" data-via="Cruxata" data-lang="es" data-count="horizontal" data-url="<?php echo $user_link; ?>">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>
				
				<!-- Google+ -->
				<div style="float:left;">
					<div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?php echo $user_link; ?>"></div>
					<script type="text/javascript">window.___gcfg = {lang: 'es-419'};(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/platform.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();</script>
				</div>
			</div>
			
			<br /><br /><br />
			
			<div id="conditions">
				<?php echo $this->lang->line ( 'fr_conditions' ); ?>
				<ul class="conditions">
					<li><?php echo $this->lang->line ( 'fr_conditions_1' ); ?></li>
					<li><?php echo $this->lang->line ( 'fr_conditions_2' ); ?></li>
					<li><?php echo $this->lang->line ( 'fr_conditions_3' ); ?></li>
					<li><?php echo $this->lang->line ( 'fr_conditions_4' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>