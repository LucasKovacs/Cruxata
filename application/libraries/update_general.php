<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_general
{
	private $ci;
	private $current_time;
	private $query_data;

	// __construct
	public function __construct()
	{
		$this->ci = &get_instance();
	}

	// ESTE METODO REVISA QUE NECESITA ACTUALIZACIÓN
	public function check_updates ( $get_data )
	{
		// TRAEMOS LOS DATOS
		$this->query_data				=	$get_data['update_data'];
		$this->current_time				=	$get_data['time'];

		// SI HAY DATOS PROCEDEMOS CON LOS UPDATES
		if ( $this->query_data != NULL )
		{
			$academy_array		=	$this->update_academy(); 	//	ACTUALIZA LA COLA DE LA ACADEMIA
			$armory_array		=	$this->update_armory();		//	ACTUALIZA LA COLA DE LA ARMERIA
			$buildings_array	=	$this->update_buildings();	//	ACTUALIZA LA COLA DE LOS EDIFICIOS
			$resources_array	=	$this->update_resources();	//	ACTUALIZA LOS RECURSOS Y SOLDADOS
			$workshop_array		=	$this->update_workshop();	//	ACTUALIZA LA COLA DEL TALLER

			$update_array['user_id']	=	$get_data['user_id'];
			$update_array['time']		=	$this->current_time;
			$update_array['academy']	=	$academy_array;
			$update_array['armory']		=	$armory_array;
			$update_array['buildings']	=	$buildings_array;
			$update_array['resources']	=	$resources_array;
			$update_array['workshop']	=	$workshop_array;			

			return $update_array;
		}
		else
		{
			return FALSE;
		}
	}

	// ACTUALIZA LA COLA DE SOLDADOS
	private function update_academy ()
	{
		// CHEQUEAMOS SI HAY ALGO EN PROGRESO
		if ( $this->query_data[0]['academy_current_build'] != '' )
		{
			$this->ci->load->library ( 'academy_library' );

			// EXTRAEMOS EL ARRAY
			$element 				= explode ( ';' , $this->query_data[0]['academy_current_build'] );
			$current_academy_build	= $this->query_data[0]['academy_current_build'];
			$new_update				= FALSE;
			$i						= 1;

			foreach ( $element as $queues )
			{
				if ( $queues != NULL )
				{
					$queue					=	explode ( ',' , $queues );

					$element_time			= $this->ci->academy_library->academy_element_time ( $queue[0] );					
					$base_time				= $queue[2] - ( $element_time * $queue[1] );	
					$current_element_time	= $base_time + $element_time;
					$amount_done			= 0;
								
					for ( $unit = 1 ; $unit <= $queue[1] ; $unit++  )
					{
						if ( ( $base_time + $element_time * $unit ) <= time() )
						{
							$amount_done++;
						}
					}

					$update_academy[$i]['element']	= $queue[0];
					$update_academy[$i]['amount']	= $amount_done;
					
					if ( ( $queue[1] - $amount_done ) == 0 ) // if nothing else to do, destroy the element
					{
						$current_academy_build		= str_replace ( $queues . ';' , '' , $current_academy_build );
					}
					else
					{
						$new_queue					= $queue[0] . ',' . ( $queue[1] - $amount_done ) . ',' . $queue[2] . ';';
						$current_academy_build		= str_replace ( $queues . ';' , $new_queue , $current_academy_build );
					}
					
					$resource_amount['wood']		= 0;
					$resource_amount['stone']		= 0;
					$resource_amount['gold']		= $this->ci->academy_library->academy_element_price ( $queue[0] );
					$update_academy[$i]['points']	= $this->ci->functions->calculate_points ( $resource_amount ) * $amount_done;
					$i++;
					
					if ( $amount_done > 0 )
					{
						$new_update	= TRUE;
					}
				}
			}

			if ( $new_update )
			{
				$update_academy[0]['current']		= $current_academy_build;		
				return $update_academy;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return NULL;
		}
	}

	// ACTUALIZA LA COLA DE ARMAS
	private function update_armory ()
	{
		// CHEQUEAMOS SI HAY ALGO EN PROGRESO
		if ( $this->query_data[0]['armory_current_build'] != '' )
		{
			$this->ci->load->library ( 'armory_library' );

			// EXTRAEMOS EL ARRAY
			$element = explode ( ';' , $this->query_data[0]['armory_current_build'] );

			if ( $element[1] <= $this->current_time )
			{
				$update_armory['element']	=	$element[0];
				$resource_amount['wood']	=	0;
				$resource_amount['stone']	=	0;
				$resource_amount['gold']	=	$this->ci->armory_library->armory_element_price ( $element[0] , $this->query_data[0][$element[0]] );
				$update_armory['points']	=	$this->ci->functions->calculate_points ( $resource_amount );

				return $update_armory;
			}
		}
		else
		{
			return FALSE;
		}
	}

	// ACTUALIZA LA COLA DE CONSTRUCCIÓN
	private function update_buildings ()
	{
		$this->ci->load->library ( 'buildings_library' );

		// CHEQUEAMOS SI HAY ALGO EN PROGRESO
		if ( $this->query_data[0]['building_current_build'] != '' )
		{
			// EXTRAEMOS EL ARRAY
			$element = explode ( ';' , $this->query_data[0]['building_current_build'] );

			if ( $element[1] <= $this->current_time )
			{
				$update_buildings['element']	=	$element[0];
				$update_buildings['points']		=	$this->ci->functions->calculate_points ( $this->ci->buildings_library->building_price ( $element[0] , $this->query_data[0][$element[0]] ) );

				return $update_buildings;
			}
		}
		else
		{
			return FALSE;
		}
	}

	// ACTUALIZA LOS RECURSOS
	private function update_resources ()
	{
		// CHEQUEAMOS SI HAY ALGO EN PROGRESO
		$this->ci->load->library ( 'buildings_library' );

		$level['gold_mine']				=	$this->query_data[0]['building_goldmine'];
		$level['stone_mine']			=	$this->query_data[0]['building_stonemine'];
		$level['wood_mine']				=	$this->query_data[0]['building_sawmill'];

		$time_since_update				=	$this->current_time	- $this->query_data[0]['user_updatetime'];
		$update_resources['gold']		=	$time_since_update * ( $this->ci->functions->gold_per_hour ( $level['gold_mine'] ) / 3600 );
		$update_resources['stone']		=	$time_since_update * ( $this->ci->functions->stone_per_hour ( $level['stone_mine'] ) / 3600 );
		$update_resources['wood']		=	$time_since_update * ( $this->ci->functions->wood_per_hour ( $level['wood_mine'] ) / 3600 );

		return $update_resources;
	}
	
	// ACTUALIZA LA COLA DE ARMAS DE ASEDIO
	private function update_workshop ()
	{
		// CHEQUEAMOS SI HAY ALGO EN PROGRESO
		if ( $this->query_data[0]['workshop_current_build'] != '' )
		{
			$this->ci->load->library ( 'workshop_library' );

			// EXTRAEMOS EL ARRAY
			$element 				= explode ( ';' , $this->query_data[0]['workshop_current_build'] );
			$current_workshop_build	= $this->query_data[0]['workshop_current_build'];
			$new_update				= FALSE;
			$i						= 1;

			foreach ( $element as $queues )
			{
				if ( $queues != NULL )
				{
					$queue					=	explode ( ',' , $queues );

					$element_time			= $this->ci->workshop_library->workshop_element_time ( $queue[0] );					
					$base_time				= $queue[2] - ( $element_time * $queue[1] );	
					$current_element_time	= $base_time + $element_time;
					$amount_done			= 0;
								
					for ( $unit = 1 ; $unit <= $queue[1] ; $unit++  )
					{
						if ( ( $base_time + $element_time * $unit ) <= time() )
						{
							$amount_done++;
						}
					}

					$update_workshop[$i]['element']	= $queue[0];
					$update_workshop[$i]['amount']	= $amount_done;
					
					if ( ( $queue[1] - $amount_done ) == 0 ) // if nothing else to do, destroy the element
					{
						$current_workshop_build		= str_replace ( $queues . ';' , '' , $current_workshop_build );
					}
					else
					{
						$new_queue					= $queue[0] . ',' . ( $queue[1] - $amount_done ) . ',' . $queue[2] . ';';
						$current_workshop_build		= str_replace ( $queues . ';' , $new_queue , $current_workshop_build );
					}
					
					$resource_amount['wood']		= $this->ci->workshop_library->workshop_element_wood ( $queue[0] );
					$resource_amount['stone']		= 0;
					$resource_amount['gold']		= $this->ci->workshop_library->workshop_element_gold ( $queue[0] );
					$update_workshop[$i]['points']	= $this->ci->functions->calculate_points ( $resource_amount ) * $amount_done;
					$i++;
					
					if ( $amount_done > 0 )
					{
						$new_update	= TRUE;
					}
				}
			}

			if ( $new_update )
			{
				$update_workshop[0]['current']		= $current_workshop_build;
				return $update_workshop;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return NULL;
		}
	}
}

/* End of file update_general.php */
/* Location: ./application/libraries/update_general.php */