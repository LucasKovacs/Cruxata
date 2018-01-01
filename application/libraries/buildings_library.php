<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buildings_library
{
	protected $instance;

	// __construct
	public function __construct()
	{
		$this->instance = &get_instance();
	}

	// COSTO DE LOS EDIFICIOS
	public function building_price ( $building , $level  )
	{
		$element 	=	$this->instance->xml->get_xml_data ( $building );
		$gold		=	0;
		$stone		=	0;
		$wood		=	0;
		$resources	=	array();

		if ( $element !== NULL )
		{
			switch ( $building )
			{
				case 'building_academy':
				
					$gold		=	floor ( $element[0]->gold["data"] * pow ( ACADEMY_PRICE_EXP , $level ) );
					$stone		=	floor ( $element[0]->stone["data"] * pow ( ACADEMY_PRICE_EXP , $level ) );
					$wood		=	floor ( $element[0]->wood["data"] * pow ( ACADEMY_PRICE_EXP , $level ) );
				
				break;
				
				case 'building_armory':
				case 'building_workshop':

					$gold		=	floor ( $element[0]->gold["data"] );
					$stone		=	floor ( $element[0]->stone["data"] );
					$wood		=	floor ( $element[0]->wood["data"] );

				break;

				case 'building_barracks':

					$gold		=	floor ( $element[0]->gold["data"] + ( pow ( $level , BARRACKS_PRICE_EXP_GOLD ) * BARRACKS_PRICE_MULT_GOLD ) );
					$stone		=	floor ( $element[0]->stone["data"] + ( pow ( $level , BARRACKS_PRICE_EXP_STONE ) * BARRACKS_PRICE_MULT_STONE ) );
					$wood		=	floor ( $element[0]->wood["data"] + ( pow ( $level , BARRACKS_PRICE_EXP_WOOD ) * BARRACKS_PRICE_MULT_WOOD ) );

				break;

				case 'building_fortified_wall':

					$gold		=	floor ( $element[0]->gold["data"] * pow ( FORTIFIELDWALL_PRICE_EXP , $level ) );
					$stone		=	floor ( $element[0]->stone["data"] * pow ( FORTIFIELDWALL_PRICE_EXP , $level ) );
					$wood		=	floor ( $element[0]->wood["data"] * pow ( FORTIFIELDWALL_PRICE_EXP , $level ) );

				break;

				case 'building_goldmine':

					$gold		=	floor ( $element[0]->gold["data"] );
					$stone		=	floor ( $element[0]->stone["data"] + ( pow ( $level , GOLDMINE_PRICE_EXP_STONE ) * GOLDMINE_PRICE_MULT_STONE ) );
					$wood		=	floor ( $element[0]->wood["data"] + ( pow ( $level , GOLDMINE_PRICE_EXP_WOOD ) * GOLDMINE_PRICE_MULT_WOOD ) );

				break;

				case 'building_sawmill':

					$gold		=	floor ( $element[0]->gold["data"] );
					$stone		=	floor ( $element[0]->stone["data"] + ( pow ( $level , SAWMILL_PRICE_EXP_STONE ) * SAWMILL_PRICE_MULT_STONE ) );
					$wood		=	floor ( $element[0]->wood["data"] + ( pow ( $level , SAWMILL_PRICE_EXP_WOOD ) * SAWMILL_PRICE_MULT_WOOD ) );

				break;

				case 'building_stonemine':

					$gold		=	floor ( $element[0]->gold["data"] );
					$stone		=	floor ( $element[0]->stone["data"] + ( pow ( $level , STONEMINE_PRICE_EXP_STONE ) * STONEMINE_PRICE_MULT_STONE ) );
					$wood		=	floor ( $element[0]->wood["data"] + ( pow ( $level , STONEMINE_PRICE_EXP_WOOD ) * STONEMINE_PRICE_MULT_WOOD ) );

				break;

				case 'building_watchtower':

					$gold		=	floor ( $element[0]->gold["data"] * pow ( WATCHTOWER_PRICE_EXP , $level ) );
					$stone		=	floor ( $element[0]->stone["data"] * pow ( WATCHTOWER_PRICE_EXP , $level ) );
					$wood		=	floor ( $element[0]->wood["data"] * pow ( WATCHTOWER_PRICE_EXP , $level ) );

				break;
			}

			$resources['gold']	=	$gold;
			$resources['stone']	=	$stone;
			$resources['wood']	=	$wood;

			return $resources;
		}
	}

	// TIEMPO DE LOS EDIFICIOS
	public function building_time ( $building , $level )
	{
		$element 	= $this->instance->xml->get_xml_data ( $building );

		if ( $element !== NULL )
		{
			switch ( $building )
			{
				case 'building_academy':
				
					return floor ( $element[0]->time["data"] * pow ( ACADEMY_TIME_EXP , $level ) );
				
				break;
				
				case 'building_armory':
				case 'building_workshop':

					return floor ( $element[0]->time["data"] );

				break;

				case 'building_barracks':

					return floor ( $element[0]->time["data"] + ( pow ( $level , BARRACKS_TIME_EXP ) * BARRACKS_TIME_MULT ) );

				break;

				case 'building_fortified_wall':

					return floor ( $element[0]->time["data"] * pow ( FORTIFIELDWALL_TIME_EXP , $level ) );

				break;

				case 'building_goldmine':

					return floor ( $element[0]->time["data"] + ( pow ( $level , GOLDMINE_TIME_EXP ) * GOLDMINE_TIME_MULT ) );

				break;

				case 'building_sawmill':

					return floor ( $element[0]->time["data"] + ( pow ( $level , SAWMILL_TIME_EXP ) * SAWMILL_TIME_MULT ) );

				break;

				case 'building_stonemine':

					return floor ( $element[0]->time["data"] + ( pow ( $level , STONEMINE_TIME_EXP ) * STONEMINE_TIME_MULT ) );

				break;

				case 'building_watchtower':

					return floor ( $element[0]->time["data"] * pow ( WATCHTOWER_TIME_EXP , $level ) );

				break;
			}
		}
	}

	// PRODUCCION DE LOS EDIFICIOS
	public function building_production ( $building , $level , $all_levels = TRUE  )
	{
		$element 	= $this->instance->xml->get_xml_data ( $building );

		if ( $element !== NULL )
		{
			switch ( $building )
			{
				case 'building_barracks':

					if ( $level == 0 )
					{
						return 0;
					}
					else
					{
						// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
						return floor ( $element[0]->limit["data"] + ( pow ( $level , BARRACKS_LIMIT_EXP ) * BARRACKS_LIMIT_MULT ) );
					}

				break;

				case 'building_goldmine':

					// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
					// ACEPTA TODOS LOS NIVELES INCLUSO EL 0
					if ( $all_levels )
					{
						return floor ( $element[0]->production["data"] + ( pow ( $level , GOLDMINE_PROD_EXP ) * GOLDMINE_PROD_MULT ) );
					}
					else
					{
						// PERMITE EXCLUIR EL 0
						// TIENE UTILIDAD PARA CALCULAR LA PRODUCCION POR HORA
						if ( $level > 0 )
						{
							return floor ( $element[0]->production["data"] + ( pow ( $level , GOLDMINE_PROD_EXP ) * GOLDMINE_PROD_MULT ) );
						}
						else
						{
							return 0;
						}
					}

				break;

				case 'building_sawmill':

					// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
					// ACEPTA TODOS LOS NIVELES INCLUSO EL 0
					if ( $all_levels )
					{
						return floor ( $element[0]->production["data"] + ( pow ( $level , SAWMILL_PROD_EXP ) * SAWMILL_PROD_MULT ) );
					}
					else
					{
						// PERMITE EXCLUIR EL 0
						// TIENE UTILIDAD PARA CALCULAR LA PRODUCCION POR HORA
						if ( $level > 0 )
						{
							return floor ( $element[0]->production["data"] + ( pow ( $level , SAWMILL_PROD_EXP ) * SAWMILL_PROD_MULT ) );
						}
						else
						{
							return 0;
						}
					}

				break;

				case 'building_stonemine':

					// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
					// ACEPTA TODOS LOS NIVELES INCLUSO EL 0
					if ( $all_levels )
					{
						return floor ( $element[0]->production["data"] + ( pow ( $level , STONEMINE_PROD_EXP ) * STONEMINE_PROD_MULT ) );
					}
					else
					{
						// PERMITE EXCLUIR EL 0
						// TIENE UTILIDAD PARA CALCULAR LA PRODUCCION POR HORA
						if ( $level > 0 )
						{
							return floor ( $element[0]->production["data"] + ( pow ( $level , STONEMINE_PROD_EXP ) * STONEMINE_PROD_MULT ) );
						}
						else
						{
							return 0;
						}
					}

				break;
			}
		}
	}

	// CHEQUEA SI EL VALOR QUE SE LE PASA ES UN EDIFICIO O NO
	public function is_building_type ( $building )
	{
		$element 	= $this->instance->xml->get_xml_data ( $building );

		if ( $element !== NULL )
		{
			return $element[0]->type["data"];
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file buildings_library.php */
/* Location: ./application/libraries/buildings_library.php */