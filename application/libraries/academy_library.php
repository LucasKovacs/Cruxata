<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Academy_library
{
	private $ci;
	private $user_id;

	// __construct
	public function __construct()
	{
		$this->ci  = &get_instance();

		$this->ci->load->model ( 'academy_model' );

		$this->user_id	=	$this->ci->auth->get_id();
	}

	// RETORNA EL PRECIO DE UN ELEMENTO DE LA ACADEMIA
	public function academy_element_price ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->price["data"];
		}
	}

	// RETORNA EL TIEMPO DE UN ELEMENTO DE LA ACADEMIA
	public function academy_element_time ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->time["data"];
		}
	}

	// RETORNA EL TIEMPO DE UN ELEMENTO DE LA ACADEMIA
	public function academy_element_weapon ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->weapon["data"];
		}
	}
	
	// RETORNA LA VIDA DEL SOLDADO
	public function academy_element_life ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->life["data"] * 1;
		}	
	}

	// RETORNA EL PODER DE ATAQUE DEL SOLDADO
	public function academy_element_attack ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->attack["data"] * 1;
		}
	}

	// RETORNA LA RESISTENCIA DEL SOLDADO
	public function academy_element_resistance ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->resistance["data"] * 1;
		}
	}
	
	// RETORNA EL NIVEL DEL SOLDADO
	public function academy_element_level ( $soldier )
	{
		$element 	= $this->ci ->xml->get_xml_data ( $soldier );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->level["data"] * 1;
		}
	}

	// CHEQUEA SI ES SOLDADO
	public function is_soldier ( $soldier )
	{
		$soldier	= str_replace ( 'academy_' , '' , $soldier );
		$element 	= $this->ci ->xml->get_xml_data ( 'academy_' . $soldier );

		if ( $element !== NULL )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// RETORNA EL CONTEO DE SOLDADOS
	public function count_soldiers ( $id = '' )
	{
		if ( $id === '' )
		{
			$id	= $this->user_id ;
		}
	
		$amounts				= 	$this->ci->academy_model->get_soldiers ( $id );
		$army_troops			=	$this->ci->academy_model->get_armies ( $id );

		$soldiers_in_academy	=	0;
		$soldiers_in_queue		=	0;
		$soldiers_in_battle		=	0;
		$queues					=	explode ( ';' , $amounts[0]['academy_current_build'] );

		// CONTAMOS LA CANTIDAD DE SOLDADOS YA PRODUCIDOS
		if ( $amounts != NULL )
		{
			foreach ( $amounts[0] as $key => $value )
			{
				if ( $this->ci->academy_library->is_soldier ( $key ) )
				{
					$soldiers_in_academy += $value;
				}
			}	
		}

		// CONTAMOS LA CANTIDAD DE SOLDADOS EN LA COLA DE ESPECIALIZACION
		if ( $queues != NULL )
		{
			foreach ( $queues as $queue )
			{
				if ( $queue != NULL )
				{
					$queue		=	explode ( ',' , $queue );
	
					$soldiers_in_queue += $queue[1];	
				}
			}	
		}

		// CONTAMOS LA CANTIDAD DE SOLDADOS EN MOVIMIENTO
		if ( $army_troops != NULL )
		{
			foreach ( $army_troops as $armies )
			{
				if ( $armies != NULL )
				{
					$army		=	explode ( ';' , $armies['army_troops'] );
	
					foreach ( $army as $element )
					{
						if ( $element != NULL )
						{
							$amount	=	explode ( ',' , $element );
	
							$soldiers_in_battle += $amount[1];
						}
					}	
				}
			}	
		}

		return ( $soldiers_in_academy + $soldiers_in_queue + $soldiers_in_battle );
	}

	// TOMA EL SOLDADO Y DETERMINA EL ARMA QUE LE CORRESPONDE
	public function return_weapon ( $soldier )
	{
		switch ( $soldier )
		{
			case'academy_warrior':

				return 'armory_hammer';

			break;

			case'academy_spearman':

				return 'armory_spear';

			break;

			case'academy_infantryman':

				return 'armory_ax';

			break;

			case'academy_swordsman':

				return 'armory_sword';

			break;

			case'academy_crossbowman':

				return 'armory_crossbow';

			break;
		}
	}

	// RETORNA EL COLOR DE ACUERDO AL LIMITE
	public function return_limit_color ( $current_soldiers , $max_soldiers )
	{
		// SI LA CANTIDAD DE SOLDADOS ACTUALES ES IGUAL AL MAXIMO
		if ( $current_soldiers == $max_soldiers )
		{
			return '<font color="#d43635">' . $current_soldiers . '/' . $max_soldiers . '</font>';
		}
		else
		{
			$porcentage	=	( ( $current_soldiers * 100 ) / $max_soldiers );

			if ( $porcentage >= 75 )
			{
				return '<font color="#d29d00">' . $current_soldiers . '/' . $max_soldiers . '</font>';
			}
			else
			{
				return '<font color="#ffffff">' . $current_soldiers . '/' . $max_soldiers . '</font>';
			}
		}
	}
}

/* End of file academy_library.php */
/* Location: ./application/libraries/academy_library.php */