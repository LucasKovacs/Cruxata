<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productivity extends CI_Controller {

	private $productivity;
	private $base_url;
	private $img_path;

	// __construct
	public function __construct()
	{
		parent::__construct();
				
		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'buildings_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'productivity_model' );

		// TRAEMOS TODOS LOS DATOS
		$this->productivity	=	$this->productivity_model->get_production_data ( $this->auth->get_id() );

		$this->base_url		=	base_url();
		$this->img_path		=	$this->base_url . IMG_FOLDER;
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			// MUESTRA LA PAGINA
			$this->show_page();
		}
		else
		{
			redirect ( base_url() );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page ()
	{
		$parse['img_path']			=	$this->img_path;
		$parse['production_rows']	=	$this->load_production();
		$parse['barracks_rows']		=	$this->load_production_table ( 'building_barracks' , $this->productivity[0]['building_barracks']);
		$parse['goldmine_rows']		=	$this->load_production_table ( 'building_goldmine' , $this->productivity[0]['building_goldmine'] );
		$parse['sawmill_rows']		=	$this->load_production_table ( 'building_sawmill' , $this->productivity[0]['building_sawmill'] );
		$parse['stonemine_rows']	=	$this->load_production_table ( 'building_stonemine' , $this->productivity[0]['building_stonemine'] );

		$this->template->page ( PRODUCTIVITY_FOLDER . 'production_view' , $parse );
	}

	// CARGA LA PRODUCCION
	private function load_production()
	{
		$sub_template	=	'';
		$resources		=	array ( 'resource_gold' , 'resource_stone' , 'resource_wood' );

		foreach ( $this->productivity[0] as $key => $resource )
		{
			if ( in_array ( $key , $resources ) )
			{
				$parse['img_path']	=	$this->img_path;
				$parse['image']		=	( $key ==  'resource_wood' ) ? 'imgWood' : ( ( $key == 'resource_stone' ) ? 'imgStone' : 'imgGold' );
				$parse['resource']	=	$this->lang->line ( 'pr_' . $key );
				$parse['limit']		=	$this->limit_production ( $key , $this->productivity[0] );
				$parse['base']		=	$this->base_production ( $key );
				$parse['hour']		=	$this->hour_production ( $key , $this->productivity[0] );
				$parse['mine']		=	$this->mine_production ( $key , $this->productivity[0] );
				$parse['day']		=	$this->day_production ( $key , $this->productivity[0] );
				$sub_template[]		= 	$parse;	   	
			}
		}

		return $sub_template;
	}

	// ARMA LAS TABLAS DE PRODUCCION
	private function load_production_table ( $building , $level )
	{
		$sub_template				=	'';

		if ( $level <= 5 )
		{
			$start	=	0;
			$end	=	MAX_PROD_ROWS;
		}
		else
		{
			$start 	= 	$level - 5;
			$end	=	MAX_PROD_ROWS + $start - 1;
		}

		for ( $i = $start ; $i <= $end ; $i++ )
		{
			if ( $i > 0 )
			{
				if ( $i == $level )
				{
					$parse['color']			=	' id="current"';
					$parse['level']			=	$this->functions->format_number ( $i );
					$parse['production']	=	'+' . $this->functions->format_number ( $this->buildings_library->building_production ( $building , $i , FALSE ) );
				}
				else
				{
					$parse['color']			=	'';
					$parse['level']			=	$this->functions->format_number ( $i );
					$parse['production']	=	'+' . $this->functions->format_number ( $this->buildings_library->building_production ( $building , $i , FALSE ) );
				}

				$sub_template[]		   		=	$parse;
			}
		}

		return $sub_template;
	}

	// DETERMINA EL LIMITE DE LA PRODUCCION
	private function limit_production ( $resource , $building )
	{
		switch ( $resource )
		{
			case 'resource_gold':

				return 0;

			break;
			case 'resource_stone':

				return 0;

			break;
			case 'resource_wood':

				return 0;

			break;
		}
	}

	// DETERMINA LA PRODUCCION BASE
	private function base_production ( $resource )
	{
		switch ( $resource )
		{
			case 'resource_gold':

				return BASE_GOLD;

			break;
			case 'resource_stone':

				return BASE_STONE;

			break;
			case 'resource_wood':

				return BASE_WOOD;

			break;
		}
	}

	// DETERMINA LA PRODUCCION POR HORA
	private function hour_production ( $resource , $building )
	{
		switch ( $resource )
		{
			case 'resource_gold':

				return $this->base_production ( $resource ) + $this->buildings_library->building_production ( 'building_goldmine' , $building['building_goldmine'] , FALSE );

			break;
			case 'resource_stone':

				return $this->base_production ( $resource ) + $this->buildings_library->building_production ( 'building_stonemine' , $building['building_stonemine'] , FALSE );

			break;
			case 'resource_wood':

				return $this->base_production ( $resource ) + $this->buildings_library->building_production ( 'building_sawmill' , $building['building_sawmill'] , FALSE );

			break;
		}
	}

	// DETERMINA LA PRODUCCION POR DIA
	private function day_production ( $resource , $building )
	{
		return $this->hour_production ( $resource , $building ) * 24;
	}

	// DETERMINA LA PRODUCCION DE LA MINA
	private function mine_production ( $resource , $building )
	{
		switch ( $resource )
		{
			case 'resource_gold':

				return $this->buildings_library->building_production ( 'building_goldmine' , $building['building_goldmine'] , FALSE );

			break;
			case 'resource_stone':

				return $this->buildings_library->building_production ( 'building_stonemine' , $building['building_stonemine'] , FALSE );

			break;
			case 'resource_wood':

				return $this->buildings_library->building_production ( 'building_sawmill' , $building['building_sawmill'] , FALSE );

			break;
		}
	}
}

/* End of file production.php */
/* Location: ./application/controllers/production.php */