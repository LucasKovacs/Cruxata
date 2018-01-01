<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_armies
{
	private $ci;
	private $attacker;
	private $enemy;

	// __construct
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model ( 'update_model' );
	}

	// ESTE METODO REVISA QUE NECESITA ACTUALIZACIÓN
	public function check_armies ()
	{
		// INICIAMOS LAS TRANSACCIONES
		$this->ci->db->trans_start();

		// TRAEMOS LOS DATOS DE LOS ATAQUES QUE DEBEN SER PROCESADOS
		$current_attacks	=	$this->ci->update_model->get_current_attacks();
		
		// PROCESAMOS LAS TROPAS QUE VAN A ATACAR
		if ( $current_attacks != NULL )
		{
			foreach ( $current_attacks as $troops )
			{
				$this->pick_mission ( $troops );
			}
		}

		// TRAEMOS LOS DATOS DE LOS EJERCITOS QUE DEBEN SER REESTABLECIDOS AL IMPERIO
		$returning_attacks	=	$this->ci->update_model->get_returning_attacks();

		// PROCESAMOS LAS TROPAS QUE LLEGAR AL IMPERIO
		if ( $returning_attacks != NULL )
		{
			foreach ( $returning_attacks as $troops )
			{
				$this->restore_army ( $troops );
			}
		}
	
		// FINALIZAMOS LAS TRANSACCIONES
		$this->ci->db->trans_complete();

		return TRUE;
	}

	// DETERMINA LA MISIÓN A EJECUTAR
	private function pick_mission ( $troops_info )
	{
		switch ( $troops_info['army_mission'] )
		{
			case 1: // ATAQUE
			
				$this->process_attack ( $troops_info );
				
			break;
			
			case 2: // EXPLORAR
				$this->process_attack ( $troops_info );
//				$this->process_explore ( $troops_info );
			
			break;
			
			case 3: // INVADIR
				$this->process_attack ( $troops_info );
//				$this->process_invade ( $troops_info );
			
			break;
			
			case 4: // OCUPAR
				$this->process_attack ( $troops_info );
//				$this->process_occupy ( $troops_info );
			
			break;
		}
	}

	// PROCESA EL ATAQUE
	private function process_attack ( $required_data )
	{
		// CARGAMOS LAS LIBRERIAS NECESARIAS
		$this->ci->load->library ( 'academy_library' );
		$this->ci->load->library ( 'armory_library' );
		
		$soldiers_attackers	= array();
		$soldiers_defenders	= array();
				
		// 1º CONSTRUIMOS Y ARMAMOS LOS EJERCITOS DE CADA JUGADOR
		$troops						=	explode ( ';' , $required_data['army_troops'] );

		// DESARMAMOS EL ARRAY DE TROPAS PARA REARMARLO MAS PROLIJO.
		foreach ( $troops as $soldiers )
		{
			if ( $soldiers != NULL )
			{
				$soldier_data						=	explode ( ',' , $soldiers );
				$soldiers_array[$soldier_data[0]]	=	$soldier_data[1];
			}
		}

		// TRAEMOS LA INFORMACIÓN NECESARIA
		// IMPORTANTE A TENER EN CUENTA:
		// EL QUE ATACA SIEMPRE VA A ATACAR CON EL EJERCITO QUE TENGA
		// EL QUE DEFIENDE SIEMPRE VA A DEFENDER CON LO QUE TIENE EN EL IMPERIO
		// POR ENDE EL QUE ATACA USA LO DE LA TABLA armies
		// Y EL QUE DEFIENDE USA LO DE LA TABLA armory
		$this->ci->update_library->update_data ( $required_data['army_receptor_id'] );
		
		// OBTENEMOS LA INFORMACIÓN
		$this->attacker		= $this->ci->update_model->get_attacker_data ( $required_data['army_user_id'] );
		$this->enemy		= $this->ci->update_model->get_defender_data ( $required_data['army_receptor_id'] );
		$this->attacker[0] += $soldiers_array;
		
		// ORDENAMOS LA INFORMACION
		$this->attacker		= $this->attacker[0];
		$this->enemy		= $this->enemy[0];
		
		//print_r($this->attacker);
		//print_r($this->enemy);


		// 2º PASO 
		// INDENTIFICAR LA RESISTENCIA DEL ATACANTE Y EL DEFENSOR
		// ATACANTE
		$attacker_gauntlet		= $this->return_resistance ( 'armory_gauntlet' 		, $this->attacker['armory_gauntlet'] ) / 100;
		$attacker_boot			= $this->return_resistance ( 'armory_boot' 			, $this->attacker['armory_boot'] ) / 100;
		$attacker_helmet		= $this->return_resistance ( 'armory_helmet' 		, $this->attacker['armory_helmet'] ) / 100;
		$attacker_shield		= $this->return_resistance ( 'armory_shield' 		, $this->attacker['armory_shield'] ) / 100;
		$attacker_breastplate	= $this->return_resistance ( 'armory_breastplate' 	, $this->attacker['armory_breastplate'] ) / 100;
	
		// DEFENSOR
		$defender_gauntlet		= $this->return_resistance ( 'armory_gauntlet' 		, $this->enemy['armory_gauntlet'] ) / 100;
		$defender_boot			= $this->return_resistance ( 'armory_boot' 			, $this->enemy['armory_boot'] ) / 100;
		$defender_helmet		= $this->return_resistance ( 'armory_helmet' 		, $this->enemy['armory_helmet'] ) / 100;
		$defender_shield		= $this->return_resistance ( 'armory_shield' 		, $this->enemy['armory_shield'] ) / 100;
		$defender_breastplate	= $this->return_resistance ( 'armory_breastplate' 	, $this->enemy['armory_breastplate'] ) / 100;

		// PODER DE ATAQUE POR DEFECTO
		$attack_power['academy_warrior']		= $this->ci->academy_library->academy_element_attack ( 'academy_warrior' );
		$attack_power['academy_spearman']		= $this->ci->academy_library->academy_element_attack ( 'academy_spearman' );
		$attack_power['academy_infantryman']	= $this->ci->academy_library->academy_element_attack ( 'academy_infantryman' );
		$attack_power['academy_swordsman']		= $this->ci->academy_library->academy_element_attack ( 'academy_swordsman' );
		$attack_power['academy_crossbowman']	= $this->ci->academy_library->academy_element_attack ( 'academy_crossbowman' );

		// 3º PASO
		// DIVIDIR LOS SOLDADOS Y SETEARLES
		// LA VIDA, RESISTENCIA, DAÑO Y ARMAS DEFENSIVAS
								
		// ARRAY DE SOLDADOS ATACANTES						
		foreach ( $this->attacker as $element => $level )
		{
			if ( strpos ( $element , 'academy_' ) !== FALSE )
			{
				for ( $i = 1 ; $i <= $level ; $i++ )
				{
					$array_data['soldier']				=	$element;
					$array_data['damage']				=	$attack_power[$element] + ( $attack_power[$element] * ( $this->return_damage ( $element , $this->attacker[$this->ci->academy_library->return_weapon ( $element )] - 1 ) / 100 ) );
					$array_data['life']					=	$this->ci->academy_library->academy_element_life ( $element );
					$array_data['resistance']			=	$this->ci->academy_library->academy_element_resistance ( $element );
					$array_data['armory_gauntlet']		=	1 + $attacker_gauntlet;
					$array_data['armory_boot']			=	1 + $attacker_boot;
					$array_data['armory_helmet']		=	1 + $attacker_helmet;
					$array_data['armory_shield']		=	1 + $attacker_shield;
					$array_data['armory_breastplate']	=	1 + $attacker_breastplate;
					$soldiers_attackers[]				=	$array_data;
				}
			}
		}

		// ARRAY DE SOLDADOS DEFENSORES
		foreach ( $this->enemy as $element => $level )
		{
			if ( strpos ( $element , 'academy_' ) !== FALSE )
			{
				for ( $i = 1 ; $i <= $level ; $i++ )
				{
					$array_data['soldier']				= $element;
					$array_data['damage']				= $attack_power[$element] + ( $attack_power[$element] * ( $this->return_damage ( $element , $this->enemy[$this->ci->academy_library->return_weapon ( $element )] - 1 ) / 100 ) );
					$array_data['life']					= $this->ci->academy_library->academy_element_life ( $element );
					$array_data['resistance']			= $this->ci->academy_library->academy_element_resistance ( $element );
					$array_data['armory_gauntlet']		= 1 + $defender_gauntlet;
					$array_data['armory_boot']			= 1 + $defender_boot;
					$array_data['armory_helmet']		= 1 + $defender_helmet;
					$array_data['armory_shield']		= 1 + $defender_shield;
					$array_data['armory_breastplate']	= 1 + $defender_breastplate;
					$soldiers_defenders[]				= $array_data;
				}
			}
		}
		
		// add watch tower
		if ( $this->enemy['building_watchtower'] >  0 )
		{
			$array_data['soldier']				= 'building_watchtower';
			$array_data['damage']				= floor ( 10 * ( $this->enemy['building_watchtower'] / 2 ) );
			$array_data['life']					= floor ( 100 * ( $this->enemy['building_watchtower'] / 2 ) );
			$array_data['resistance']			= floor ( 1000 * ( $this->enemy['building_watchtower'] / 2 ) );
			$array_data['armory_gauntlet']		= 0;
			$array_data['armory_boot']			= 0;
			$array_data['armory_helmet']		= 0;
			$array_data['armory_shield']		= 0;
			$array_data['armory_breastplate']	= 0;
			$soldiers_defenders[]				= $array_data;
		}

		//print_r($soldiers_attackers);
		//print_r($soldiers_defenders);
			
		// HASTA ESTE PUNTO TENEMOS LOS EJERCITOS ARMADOS
		// CADA SOLDADO TIENE SU VIDA, SU RESISTENCIA, SUS ARMAS MONTADAS Y EL DAÑO QUE APLICARA
		// AHORA HAY QUE VER COMO VAN A CHOCAR!
		
		// 4º PASO
		// PRIMERO VAMOS A VER CUANTOS SOLDADOS HAY EN CADA GRUPO PARA TENER UNA IDEA DE QUIEN TIENE VENTAJA TACTICA
		// NO SE SI ESTO ME VA A SERVIR MAS ADELANTE.
		$soldiers_amount['attackers']	= count ( $soldiers_attackers );
		$soldiers_amount['defenders']	= count ( $soldiers_defenders );
		$points_to_remove['attackers']	= 0;
		$points_to_remove['defenders']	= 0;
		$rounds							= 1; // CONTAMOS LAS RONDAS
		
		//print_r($soldiers_amount);

		// 5º PASO
		// SI EL DEFENSOR NO TIENE SOLDADOS FUE PERDIO.
		if ( $soldiers_amount['defenders'] == 0 )
		{
			// PERDIO EL DEFENSOR
			// LISTO TODO
			$end_data['result']	= 'won';	
		}
		else
		{
			$max_attacks	= ( 2 * $this->enemy['building_watchtower'] ) + 18;
		
			// 6º
			// SINO EMPEZAMOS A REPARTIR AL AZAR
			// TOMAMOS TODOS LOS SOLDADOS DEL ATACANTE Y LES ASIGNAMOS UN SOLDADO DEL DEFENSOR AL AZAR.	
			foreach ( $soldiers_attackers as $soldier_number => $soldier_data )
			{
				$encounters[1][]	= array ( 'type' => 'attacker' , 'encounter' => $soldier_number . ',' . mt_rand ( 0 , $soldiers_amount['defenders'] - 1 ) );
			}
			
			$temp_soldiers_attackers	= $soldiers_attackers;
			
			// AHORA TOMAMOS TODOS LOS SOLDADOS DEL DEFENSOR Y LES ASIGNAMOS UN SOLDADOS DEL ATACANTE AL AZAR
			foreach ( $soldiers_defenders as $soldier_number => $soldier_data )
			{
				if ( $soldier_data['soldier'] == 'building_watchtower' )
				{
					for ( $shoot = 1 ; $shoot <= $max_attacks ; ++$shoot )
					{					
						$to_shoot	= array_rand ( $temp_soldiers_attackers );
						
						if ( $to_shoot === NULL )
						{
							break;
						}
												
						unset ( $temp_soldiers_attackers[$to_shoot] );
												
						$encounters[2][]	= array ( 'type' => 'defender' , 'encounter' => $soldier_number  . ',' .  $to_shoot );	
					}
				}
				else
				{
					$encounters[2][]	= array ( 'type' => 'defender' , 'encounter' => $soldier_number  . ',' .  mt_rand ( 0 , $soldiers_amount['attackers'] - 1 ) );	
				}
			}
			
			//print_r($encounters);

			// 7º
			// YA CREADOS LOS ENFRENTAMIENTOS
			// AHORA INTERCALAMOS LOS ENFRENTAMIENTOS DEL ATACANTE Y EL DEFENSOR
			// DISPONIENDOLOS DE ESTA FORMA COMO EN UNA ESPECIE DE TURNOS
			$take_from_column	= 1;
			$total_elements		= $soldiers_amount['attackers'] + $soldiers_amount['defenders'];
			
			for ( $i = 0 ; $i < ( $total_elements - 1 ) ; $i++ )
			{
				if ( isset ( $encounters[1][$i] ) && $encounters[1][$i] != NULL )
				{
					$final_encounters[]	=	$encounters[1][$i];	
				}
				
				if ( isset ( $encounters[2][$i] ) && $encounters[2][$i] != NULL )
				{
					$final_encounters[]	=	$encounters[2][$i];	
				}
			}
			
//			print_r($final_encounters);
//			print_r($soldiers_attackers);
//			print_r($soldiers_defenders);
//			print_r($soldiers_amount);

			// 8º
			// PROCESAMOS LOS ATAQUES PASO POR PASO
			$continue	= TRUE; // FLAG
			
			while ( $continue )
			{		
				foreach ( $final_encounters as $encounter_id => $encounter_data )
				{
					// SI ES EL TURNO DEL ATACANTE
					// ARRAY: $soldiers_attackers => $soldiers_defenders
					if ( $encounter_data['type'] == 'attacker' && $soldiers_amount['attackers'] > 0 && $soldiers_amount['defenders'] > 0 )
					{
						// OBTENEMOS EL SOLDADO QUE PEGA Y EL QUE RECIBE EL GOLPE
						$soldiers	= explode ( ',' , $encounter_data['encounter'] );
						
						// DETERMINAMOS DONDE VA A PEGAR
						$hit_to		= mt_rand ( 1 , 5 );
						$hit_to		= $this->return_defense_weapon ( $hit_to );
						
						// SI EL SOLDADO TIENE UN ARMA EN ESA POSICION
						// EL DAÑO PEGA EN ESA ARMA Y ABSORBE EL DAÑO DE IR DIRECTAMENTE A LA RESISTENCIA DEL SOLDADO
						if ( $soldiers_defenders[$soldiers[1]][$hit_to] > 0 && $soldiers_defenders[$soldiers[1]]['resistance'] > 0 )
						{
							if ( $soldiers_attackers[$soldiers[0]]['damage'] >= $soldiers_defenders[$soldiers[1]][$hit_to] )
							{
								$soldiers_defenders[$soldiers[1]][$hit_to]		= 0;
								
								// remaining damage
								$remaining_damage	= $soldiers_attackers[$soldiers[0]]['damage'] - $soldiers_defenders[$soldiers[1]][$hit_to];
								
								if ( $remaining_damage >= $soldiers_defenders[$soldiers[1]]['resistance'] )
								{
									$soldiers_defenders[$soldiers[1]]['resistance']	= 0;
								}
								else
								{
									$soldiers_defenders[$soldiers[1]]['resistance'] -= $remaining_damage;
								}								
							}
							else
							{
								$soldiers_defenders[$soldiers[1]][$hit_to] -= $soldiers_attackers[$soldiers[0]]['damage'];
							}	
						}
						else
						{
							// SI NO TIENE ARMA DEFENSIVA PEGAMOS SOBRE LA RESISTENCIA Y LUEGO LA VIDA
							// SI TIENE RESISTENCIA, PEGAMOS SOBRE ELLA
							if ( $soldiers_defenders[$soldiers[1]]['resistance'] > 0 )
							{
								if ( $soldiers_attackers[$soldiers[0]]['damage'] >= $soldiers_defenders[$soldiers[1]]['resistance'] )
								{
									$soldiers_defenders[$soldiers[1]]['resistance']	= 0;
								}
								else
								{
									$soldiers_defenders[$soldiers[1]]['resistance'] -= $soldiers_attackers[$soldiers[0]]['damage'];
								}	
							}
							else
							{
								// SINO ESTA MUERTO YA
								if ( $soldiers_defenders[$soldiers[1]]['life'] > 0 )
								{
									// SI NO TIENE RESISTENCIA PEGAMOS FINALMENTE SOBRE LA VIDA
									if ( $soldiers_attackers[$soldiers[0]]['damage'] >= $soldiers_defenders[$soldiers[1]]['life'] )
									{
										$soldiers_defenders[$soldiers[1]]['life']	= 0;
										$resource_amount['wood']					= 0;
										$resource_amount['stone']					= 0;
										$resource_amount['gold']					= $this->ci->academy_library->academy_element_price ( $soldiers_defenders[$soldiers[1]]['soldier'] );
										$points_to_remove['defenders']			   += $this->ci->functions->calculate_points ( $resource_amount );
										$soldiers_amount['defenders']--;
									}
									else
									{
										$soldiers_defenders[$soldiers[1]]['life']	-= $soldiers_attackers[$soldiers[0]]['damage'];
									}	
								}
							}
			
						}
					}
					
					// SI ES EL TURNO DEL DEFENSOR
					// ARRAY: $soldiers_defenders => $soldiers_attackers
					if ( $encounter_data['type'] == 'defender' && $soldiers_amount['attackers'] > 0 && $soldiers_amount['defenders'] > 0 )
					{
						// OBTENEMOS EL SOLDADO QUE PEGA Y EL QUE RECIBE EL GOLPE
						$soldiers	= explode ( ',' , $encounter_data['encounter'] );
						
						// DETERMINAMOS DONDE VA A PEGAR
						$hit_to		= mt_rand ( 1 , 5 );
						$hit_to		= $this->return_defense_weapon ( $hit_to );
						
						// SI EL SOLDADO TIENE UN ARMA EN ESA POSICION
						// EL DAÑO PEGA EN ESA ARMA Y ABSORBE EL DAÑO DE IR DIRECTAMENTE A LA RESISTENCIA DEL SOLDADO
						if ( $soldiers_attackers[$soldiers[1]][$hit_to] > 0 && $soldiers_attackers[$soldiers[1]]['resistance'] > 0 )
						{
							if ( $soldiers_defenders[$soldiers[0]]['damage'] >= $soldiers_attackers[$soldiers[1]][$hit_to] )
							{
								$soldiers_attackers[$soldiers[1]][$hit_to]		= 0;
								
								// remaining damage
								$remaining_damage	= $soldiers_defenders[$soldiers[0]]['damage'] - $soldiers_attackers[$soldiers[1]][$hit_to];
								
								if ( $remaining_damage >= $soldiers_attackers[$soldiers[1]]['resistance'] )
								{
									$soldiers_attackers[$soldiers[1]]['resistance']	= 0;
								}
								else
								{
									$soldiers_attackers[$soldiers[1]]['resistance'] -= $remaining_damage;
								}								
							}
							else
							{
								$soldiers_attackers[$soldiers[1]][$hit_to] -= $soldiers_defenders[$soldiers[0]]['damage'];
							}	
						}
						else
						{
							// SI NO TIENE ARMA DEFENSIVA PEGAMOS SOBRE LA RESISTENCIA Y LUEGO LA VIDA
							// SI TIENE RESISTENCIA, PEGAMOS SOBRE ELLA
							if ( $soldiers_attackers[$soldiers[1]]['resistance'] > 0 )
							{
								if ( $soldiers_defenders[$soldiers[0]]['damage'] >= $soldiers_attackers[$soldiers[1]]['resistance'] )
								{
									$soldiers_attackers[$soldiers[1]]['resistance']	= 0;
								}
								else
								{
									$soldiers_attackers[$soldiers[1]]['resistance'] -= $soldiers_defenders[$soldiers[0]]['damage'];
								}	
							}
							else
							{
								// SINO ESTA MUERTO YA
								if ( isset ( $soldiers_attackers[$soldiers[1]]['life'] ) && $soldiers_attackers[$soldiers[1]]['life'] > 0 )
								{
									// SI NO TIENE RESISTENCIA PEGAMOS FINALMENTE SOBRE LA VIDA
									if ( ( $soldiers_defenders[$soldiers[0]]['damage'] >= $soldiers_attackers[$soldiers[1]]['life'] ) )
									{
										$soldiers_attackers[$soldiers[1]]['life']	= 0;
										$resource_amount['wood']					= 0;
										$resource_amount['stone']					= 0;
										$resource_amount['gold']					= $this->ci->academy_library->academy_element_price ( $soldiers_attackers[$soldiers[1]]['soldier'] );
										$points_to_remove['attackers']			   += $this->ci->functions->calculate_points ( $resource_amount );
										$soldiers_amount['attackers']--;
									}
									else
									{
										$soldiers_attackers[$soldiers[1]]['life']	-= $soldiers_defenders[$soldiers[0]]['damage'];
									}	
								}
							}
			
						}
						
					}
				}
	
				$rounds++;	// RONDA TERMINADA, INCREMENTAMOS	
							
				if ( $soldiers_amount['attackers'] == 0 or $soldiers_amount['defenders'] == 0 or $rounds == MAX_ROUNDS  )
				{
					$continue	= FALSE;
				}
										
			} // END WHILE

			//print_r($soldiers_attackers);
			//print_r($soldiers_defenders);
			//print_r($soldiers_amount);
			//die();	
						
			if ( ( $soldiers_amount['attackers'] > $soldiers_amount['defenders'] ) or ( $soldiers_amount['defenders'] == 0 ) )
			{
				$end_data['result']	= 'won';
			}
			
			if ( ( $soldiers_amount['attackers'] < $soldiers_amount['defenders'] ) or ( $soldiers_amount['attackers'] == 0 ) )
			{
				$end_data['result']	= 'lost';
			}
			
			if ( ( $soldiers_amount['attackers'] == $soldiers_amount['defenders'] ) or ( $soldiers_amount['attackers'] == 0 && $soldiers_amount['defenders'] == 0 ) )
			{
				$end_data['result']	= 'tie'; // EMPATE
			}		
			
		} // END ELSE

		// 9º
		// ULTIMO PASO VER QUIEN OBTUVO LOS MAYORES VALORES
		$end_data['army_id']			=	$required_data['army_id'];
		$end_data['attacker_id']		=	$required_data['army_user_id'];
		$end_data['enemy_id']			=	$required_data['army_receptor_id'];
		$end_data['army_current']		=	$required_data['army_current'];
		$end_data['user_name']			=	$this->attacker['user_name'];
		$end_data['enemy_name']			=	$this->enemy['user_name'];
		$end_data['soldiers_attacker']	=	$soldiers_amount['attackers'];
		$end_data['soldiers_defender']	=	$soldiers_amount['defenders'];
		$end_data['rounds']				= 	$rounds;
		$end_data['attacker_army']		=	$soldiers_attackers;
		$end_data['defender_army']		=	$soldiers_defenders;
		$end_data['attacker_points']	=	$points_to_remove['attackers'];
		$end_data['defender_points']	=	$points_to_remove['defenders'];
		$end_data['gold']				=	$this->enemy['resource_gold'];
		$end_data['stone']				=	$this->enemy['resource_stone'];
		$end_data['wood']				=	$this->enemy['resource_wood'];
		$end_data					   +=	$this->calculate_steal ( $end_data );
				
		//print_r($end_data);
			
		// 10º
		// ACA YA ESTAMOS PARA ACTUALIZAR LOS EJERCITOS CON LOS VALORES RESULTANTES
		// TENEMOS QUE ACTUALIZAR LOS DATOS DEL EJERCITO
		// TENEMOS QUE ACTUALIZAR LOS DATOS DEL IMPERIO
		// TENEMOS QUE ARMAR EL REPORTE
		$this->ci->update_model->end_attack ( $end_data );
		$this->make_report ( $end_data );				
	}

	// CALCULA EL DAÑO
	private function return_damage ( $soldier , $off_level )
	{
		if ( $off_level < 0 )
		{
			return 0;
		}
		else
		{
			// RETORNAMOS EL DAÑO DEL ARMA OFENSIVA
			return floor ( $this->ci->armory_library->armory_element_power ( $this->ci->academy_library->return_weapon ( $soldier ) , $off_level ) );
		}
	}

	// CALCULA LA RESISTENCIA
	private function return_resistance ( $def_weapon , $def_level )
	{	
		if ( $def_level < 0 )
		{
			return 0;
		}
		else
		{
			return floor ( $this->ci->armory_library->armory_element_power ( $def_weapon , $def_level ) );
		}
	}

	// RETORNA EL ARMA DEFENSIVA CORRESPONDIENTE
	private function return_defense_weapon ( $weapon )
	{
		switch ( $weapon )
		{
			case '1':
				return 'armory_shield';
			break;
			case '2':
				return 'armory_gauntlet';
			break;
			case '3':
				return 'armory_boot';
			break;
			case '4':
				return 'armory_helmet';
			break;
			case '5':
				return 'armory_breastplate';
			break;
		}
	}

	// CACULA EL ROBO DE RECURSOS
	private function calculate_steal ( $result_attack_data )
	{	
		$result['stolen_wood']	= 0;
		$result['stolen_stone']	= 0;
		$result['stolen_gold']	= 0;
		
		if  ( $result_attack_data['result'] == 'won' )
		{
			// CALCULAMOS EL MAXIMO QUE SE PUEDE SACAR DE CADA RECURSOS
			$max['wood']		=	floor ( $result_attack_data['wood'] 	* ( mt_rand ( 41 , 61 ) / 100 ) );
			$max['stone']		=	floor ( $result_attack_data['stone'] 	* ( mt_rand ( 41 , 61 ) / 100 ) );
			$max['gold']		=	floor ( $result_attack_data['gold'] 	* ( mt_rand ( 41 , 61 ) / 100 ) );
			
			// CALCULAMOS LA CAPACIDAD QUE SERIA LA CONSTANTE POR LA CANT. DE SOLDADOS			
			$max_capacity		=	( MAX_CAPACITY * $result_attack_data['soldiers_attacker'] );
						
			// SI LOS RECURSOS A ROBAR ES MAYOR A LA CAPACIDAD ENTONCES ROBAMOS SOLO
			// LO QUE PODEMOS LLEVARNOS
			if ( ( $max['wood'] + $max['stone'] + $max['gold'] ) > $max_capacity )
			{	
				// 1º CALCULAMOS CUANTO SE PUEDE LLEVAR DE CADA RECURSO
				$capacity		=	floor ( $max_capacity / 3 );
								
				// 2º VEMOS QUE RECURSOS CUMPLEN CON ESTOS LIMITES				
				// PARA LA MADERA
				foreach ( $max as $key => $value )
				{
					if ( $max[$key] >= $capacity )
					{
						$stolen[$key]	=	$capacity;
						$complete[$key]	=	'complete';
						// NO QUEDA ESPACIO LIBRE
					}
					else
					{
						$stolen[$key]	=	$max[$key];
						$complete[$key]	=	'incomplete';
						// QUEDA ESPACIO LIBRE
					}
				}
				
				// 3º DETERMINAMOS LA CANTIDAD DE RECURSOS QUE PUEDEN SER COMPLETADOS
				// EL INCOMPLETO ES UN RECURSO QUE NO LLEGO A SU LIMITE, POR LO TANTO NOS DEJA MAS ESPACIO PARA LLEVAR OTROS RECURSOS
				// EL COMPLETO SON RECURSOS QUE CUBRIERON CON SU CUOTA BASE Y NO PODRAN LLEVAR MAS, SALVO QUE OTRO RECURSO ESTE INCOMPLETO
				$complete_count	= 0;
				
				// CON ESTE CICLO DETERMINAMOS
				// A: LA CANTIDAD DE RECURSOS QUE PUEDEN SER COMPLETADOS AUN.
				// B: LA CAPACIDAD QUE NOS QUEDA POR COMPLETAR
				foreach ( $complete as $key => $value )
				{
					if ( $value == 'incomplete' )
					{
						$max_capacity	-= $stolen[$key];
					}
					else
					{
						$max_capacity	-= $stolen[$key];
						$complete_count++;
					}
				}
				
				// 4º SI $complete_count ES IGUAL A 0 SIGNIFICA QUE DE ENTRADA TODOS LOS RECURSOS ESTAN DENTRO DE SU CAPACIDAD BASE, ES DECIR,
				// QUE LOS RECURSOS ENTRARON DENTRO DE SU CAPACIDAD MAXIMA ESTABLECIDA Y NO ES NECESARIO ANDAR RELLENANDO PORQUE NO QUEDAN RECURSOS PARA RELLENAR
				// AHORA BIEN SI $complete_count ES IGUAL A 3 SIGNIFICA QUE DE ENTRADA TODOS LOS RECURSOS ESTAN COMPLETOS Y NO HAY MAS ESPACIO,
				// POR LO QUE NO SE PODRÁ RELLENAR.
				if ( $complete_count != 0 && $complete_count != 3 )
				{
					// 5º ES HORA DE REPARTIR. COMO NOS QUEDA ESPACIO LIBRE DEBIDO A QUE ALGUN RECURSO NO CUMPLIO CON SU CUOTA,
					// TOMAMOS LA CAPACIDAD MAXIMA RESTANTE Y LA DIVIDIMOS POR LA CANTIDAD DE RECURSOS A RELLENAR
					// SOLO PUEDE SER 1 RECURSO O 2 RECURSOS, NI MAS NI MENOS.
					$to_share	= floor ( $max_capacity / $complete_count );
									
					foreach ( $complete as $key => $value )
					{
						if ( $value == 'complete' ) // BUSCAMOS LOS QUE LLENARON BIEN SU CAPACIDAD
						{
							// SI LO QUE ROBARON INICIALMENTE + LO QUE LES CORRESPONDE DEL OTRO RECURSO
							// SUPERO LO MAXIMO POSIBLE ROBAR, ENTONCES PONEMOS EL RECURSO AL LIMITE
							if ( ( $stolen[$key] + $to_share ) > $max[$key] )
							{
								$stolen[$key]	= $max[$key];
							}
							else // SINO SOLO LO QUE LE CORRESPONDE.
							{
								$stolen[$key]	+= $to_share;
							}
						}
					}		
				}
				
				$result['stolen_wood']	= $stolen['wood'];
				$result['stolen_stone']	= $stolen['stone'];
				$result['stolen_gold']	= $stolen['gold'];
			}
			
			if ( ( $max['wood'] + $max['stone'] + $max['gold'] ) <= $max_capacity )
			{
				$result['stolen_wood']	= $max['wood'];
				$result['stolen_stone']	= $max['stone'];
				$result['stolen_gold']	= $max['gold'];
			}
		}

		return $result;
	}

	// EMITE EL REPORTE DE COMBATE PARA CADA EJERCITO
	private function make_report ( $end_attack_data )
	{
//		print_r($end_attack_data);
	
		$message	= $this->ci->load->view ( MISSIONS_FOLDER . 'missions_attack_report_view' , $end_attack_data , TRUE );

		// ENVIA EL MENSAJE
		$this->ci->update_model->insert_message ( $message , $end_attack_data );
	}

	// PROCESA LA EXPLORACIÓN
	private function process_explore ( $required_data )
	{
	}

	// PROCESA LA INVASIÓN
	private function process_invade ( $required_data )
	{
	}
	
	// PROCESA LA OCUPACIÓN
	private function process_occupy ( $required_data )
	{
	}
	
	// RESTABLECE EL EJERCITO AL IMPERIO
	private function restore_army ( $data )
	{	
		$this->ci->update_model->return_army ( $data );
	}
}

/* End of file update_armies.php */
/* Location: ./application/libraries/update_armies.php */