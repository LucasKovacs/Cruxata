<script>
// Wait until the DOM has loaded before querying the document
$(document).ready(function(){
	$('#start').click(function(){
		$('div#steps a[href="#tab1"]').click();
	});
	$('div#steps').each(function(){
		// For each set of tabs, we want to keep track of
		// which tab is active and it's associated content
		var $active, $content, $links = $(this).find('a');

		// If the location.hash matches one of the links, use that as the active tab.
		// If no match is found, use the first link as the initial active tab.
		$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
		$active.addClass('active');

		$content = $($active[0].hash);

		// Hide the remaining content
		$links.not($active).each(function () {
			$(this.hash).hide();
		});

		// Bind the click event handler
		$(this).on('click', 'a', function(e){
			// Make the old tab inactive.
			$active.removeClass('active');
			$content.hide();

			// Update the variables with the new link and content
			$active = $(this);
			$content = $(this.hash);

			// Make the tab active.
			$active.addClass('active');
			$content.show();

			// Prevent the anchor's default click action
			e.preventDefault();
		});
	});
});
</script>
<div id="section">
	<div id="tutorial">
		<div id="title">
			<?php echo $this->lang->line ( 'tt_title' ); ?>
		</div>
		<div id="infoContainer">
		
			<br/>
			
			<div id="steps">
				<ul>
					<a href="#tab0" style="visibility:hidden"><li>0</li></a>
					<a href="#tab1"><li>1</li></a>
					<a href="#tab2"><li>2</li></a>
					<a href="#tab3"><li>3</li></a>
					<a href="#tab4"><li>4</li></a>
					<a href="#tab5"><li>5</li></a>
					<a href="#tab6"><li>6</li></a>
					<a href="#tab7"><li>7</li></a>
					<a href="#tab8"><li>8</li></a>
					<a href="#tab9"><li>9</li></a>
					<a href="#tab10"><li>10</li></a>
				</ul>
			</div>
			
			<br/><br/>
		
			<div id="instructions">
				<div id="tab0">
					<h2><?php echo $this->lang->line ( 'tt_step0_title' ); ?></h2>
					
					<br/>
					
					<p><?php echo $this->lang->line ( 'tt_step0_row1' ); ?></p>
					<br/><br/>
					<ul>
						<li><?php echo $this->lang->line ( 'tt_step0_row2' ); ?></li>
						<li><?php echo $this->lang->line ( 'tt_step0_row3' ); ?></li>
						<li><?php echo $this->lang->line ( 'tt_step0_row4' ); ?></li>
						<li><?php echo $this->lang->line ( 'tt_step0_row5' ); ?></li>
					</ul>
					
					<div align="center">
						<input type="submit" name="start" id="start" value="<?php echo $this->lang->line ( 'tt_step0_button_start' ); ?>"> 
					</div>
				</div>
				<div id="tab1">
					<h2>Paso 1 - Iniciando la producción</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/buildings/building_sawmill.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +300 madera +150 piedra
				</div>
				<div id="tab2">
					<h2>Paso 2 - Defendiendo tu imperio</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/buildings/building_watchtower.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +1 nivel de la torre
				</div>
				<div id="tab3">
					<h2>Paso 3 - Mejorando la producción</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/buildings/building_goldmine.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +4000 madera +1000 piedra
				</div>
				<div id="tab4">
					<h2>Paso 4 - Tu primer soldado</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/soldiers/academy_warrior.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +2 soldados
				</div>
				<div id="tab5">
					<h2>Paso 5 - Sociedad</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/castles/castle01.gif" width="150px" height="150px">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +400 oro
				</div>
				<div id="tab6">
					<h2>Paso 6 - Economía</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/buildings/building_stonemine.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> 20% descuento la próxima vez que uses el mercado
				</div>
				<div id="tab7">
					<h2>Paso 7 - Primeros ataques</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/armory/armory_sword.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +1 nivel al martillo
				</div>
				<div id="tab8">
					<h2>Paso 8 - Defensas avanzadas</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/buildings/building_fortified_wall.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
							
					<br/>
					
					<strong><u>Premio:</u></strong> +1 nivel a la muralla
				</div>
				<div id="tab9">
					<h2>Paso 9 - Armamento pesado</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/buildings/building_workshop.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +1 catapulta +1 trebuchet
				</div>
				<div id="tab10">
					<h2>Paso 10 - Asediando al enemigo</h2>
					
					<br/>
					
					<div style="float:left;margin-right:10px">
						<img src="http://localhost/own/cruxata.com/img/workshop/workshop_trebuchet.gif">
					</div>
					
					<div style="overflow:hidden">
						<p>Todo imperio para crecer y volverse competitivo deberá desarrollar sus edificios de producción.</p> 
						<br/>
						<p>Los edificios de producción los encontrarás en Menú como "Producción". Allí podrás ampliar tu Aserradero, Cantera y Mina de oro.</p>
						<br/>						
						<p>Los recursos se muestran de acuerdo a su nivel de requerimiento la madera es el recurso básico para tu imperio, es el que se va a necesitar y a producir en mayor medida.</p>
						<br/>
						<p>El resto de los recursos son también importantes y deberás desarrollarlos a medida que puedas.</p>
					</div>
					
					<strong><u>Pasos:</u></strong>
					<ul>
						<li style="height:auto;">Aserradero a nivel 3 (0/3)</li>
						<li style="height:auto;">Cantera a nivel 2 (0/2)</li>
						<li style="height:auto;">Mina de Oro a nivel 1 (0/1)</li>
					</ul>
					
					<br/>
					
					<strong><u>Premio:</u></strong> +5000 oro
				</div>
			</div>
		</div>
	</div>
</div>