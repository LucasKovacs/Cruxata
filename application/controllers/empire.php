<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empire extends CI_Controller {

	private $empire;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
		$this->load->model ( 'empire_model' );

		$this->empire	=	$this->empire_model->get_empire_data ( $this->auth->get_id() );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
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
		// DEFAULT
		$parse['building_watchtower']	=	'';
		$parse['attack_close']			= 	'';
		$parse['armory_title']			=	'-';
		$parse['armory_timer']			=	'';
		$parse['a_time']				=	0;
		$parse['building_title']		=	'-';
		$parse['building_timer']		=	'';
		$parse['b_time']				=	0;
		$parse['academy_title']			=	'-';
		$parse['academy_timer']			=	'';
		$parse['c_time']				=	0;
		$parse['workshop_title']		=	'-';
		$parse['workshop_timer']		=	'';
		$parse['d_time']				=	0;
		$parse['lang']					=	$this->lang->line ( 'em_ready' );
	
		// CONSULTAMOS LA INFORMACION DEL USUARIO
		$user_data						= 	$this->empire;
				
		// FORMATO URLS
		$parse['base_url']				=	base_url();
		$parse['img_path']				= 	$parse['base_url'] . IMG_FOLDER;

		// GENERAL
		$parse['user_name']				=	$user_data[0]['user_name'];
		$parse['user_castle_img']		=	$user_data[0]['user_castle_img'];
		$parse['user_points']			=	$this->functions->format_number ( $user_data[0]['statistic_points'] );
		$parse['current_attacks']		=	$user_data[0]['current_attacks'];

		// INFORMACION EXTRA
		$parse['location']				=	$this->functions->format_position ( $user_data[0]['user_kingdom'] ) . $this->lang->line ( 'em_feud' ) . $user_data[0]['user_feud'];
		
		// TORRE VIGIA
		if ( $user_data[0]['building_watchtower'] > 0  )
		{
			$parse['building_watchtower']	= '<img src="' . $parse['img_path'] . 'buildings/building_watchtower.gif" width="120px" height="120px"/>';
			$parse['building_watchtower']  .= '<div style="position:relative;background-color:#000;width:120px;top:-15px;left:1px;height:14px;text-align:center">';
			$parse['building_watchtower']  .= '<strong>' . strtr ( $this->lang->line ( 'em_to_range' ) , array ( '%s' => $this->watchtower_range ( $user_data[0]['building_watchtower'] ) ) ) . '</strong>';
			$parse['building_watchtower']  .= '</div>';
			$parse['attack_close']			= $this->lang->line ( 'em_no_close_attacks' );
			$count							= 1;
			
			if ( ( $user_data[0]['army_arrival'] != NULL ) && ( $user_data[0]['army_arrival'] != 0 ) && ( $user_data[0]['army_current'] != 0 ) )
			{
				$parse['attack_close']		= '';
			
				foreach ( $user_data as $key => $data )
				{
					$arrival_time			=	$data['army_arrival'] - time();
					$detect_time			=	$this->watchtower_range ( $user_data[0]['building_watchtower'] );
										
					if ( ( $arrival_time <= $detect_time ) )
					{
						$parse['attack_close']	.= 	$count . ' - ' . $this->lang->line ( 'em_attack_close' ) . $this->functions->format_time ( $data['army_arrival'] - time() ) . '<br />';
						$count++;
					}
				}
			}					
		}

		// INFORMACION DE LA PRODUCION DE LA ARMERIA
		if ( $user_data[0]['armory_current_build'] !='' )
		{
			$armory_time 				=	explode ( ';' , $user_data[0]['armory_current_build'] );
			$parse['armory_title']		=	$this->lang->line ( 'ar_' . $armory_time[0] ) . ' | ' . $this->lang->line ( 'em_ends' );
			$parse['a_time']			=	$armory_time[1] - time();
			$parse['armory_timer']		=	'<span id="armory_countdown">' . $this->functions->format_time ( $parse['a_time'] ) . '</span>';
		}

		// INFORMACION DE LA CONSTRUCCION DE EDIFICIOS
		if ( $user_data[0]['building_current_build'] !='' )
		{
			$building_time 				=	explode ( ';' , $user_data[0]['building_current_build'] );
			$parse['building_title']	=   $this->lang->line ( 'bu_' . $building_time[0] ) . ' | ' . $this->lang->line ( 'em_ends' );
			$parse['b_time']			=	$building_time[1] - time();
			$parse['building_timer']	=	'<span id="building_countdown">' . $this->functions->format_time ( $parse['b_time'] ) . '</span>';
		}
		
		// INFORMACION DEL ENTRENAMIENTO DE SOLDADOS
		if ( $user_data[0]['academy_current_build'] !='' )
		{
			$academy_time 				=	explode ( ';' , $user_data[0]['academy_current_build'] );
			$parse['academy_title']		= 	0;
			$parse['c_time']			= 	0;
			$final_time					=	0;
		
			foreach ( $academy_time as $elements )
			{
				$element				= explode ( ',' , $elements );
								
				if ( $element[0] != NULL )
				{
					$parse['academy_title']	+= $element[1];
					
					if ( $element[2] > $final_time )
					{
						$final_time	= $element[2];
					}					
				}
			}
			
			$parse['c_time']				+= ( $final_time - time() );
			$parse['academy_title']			.=  ' | ' . $this->lang->line ( 'em_ends' );
			$parse['academy_timer']			 =	'<span id="academy_countdown">' . $this->functions->format_time ( $parse['c_time'] ) . '</span>';
		}
		
		// INFORMACION DE LA CONSTRUCCIÓN DE ARMAS DE ASEDIO
		if ( $user_data[0]['workshop_current_build'] !='' )
		{
			$workshop_time 				=	explode ( ';' , $user_data[0]['workshop_current_build'] );
			$parse['workshop_title']	= 	0;
			$parse['d_time']			= 	0;
			$final_time					=	0;
		
			foreach ( $workshop_time as $elements )
			{
				$element				= explode ( ',' , $elements );
								
				if ( $element[0] != NULL )
				{
					$parse['workshop_title']	+= $element[1];
					
					if ( $element[2] > $final_time )
					{
						$final_time	= $element[2];
					}	
				}
			}
			
			$parse['d_time']					+= ( $final_time - time() );
			$parse['workshop_title']			.=  ' | ' . $this->lang->line ( 'em_ends' );
			$parse['workshop_timer']			 =	'<span id="workshop_countdown">' . $this->functions->format_time ( $parse['d_time'] ) . '</span>';
		}

		$this->template->page ( EMPIRE_FOLDER . 'empire_view' , $parse );
	}
	
	// RETORNA LA CANTIDAD DE TIEMPO CON ANTICIPACIÓN QUE PUEDE VER LA TORRE
	private function watchtower_range ( $watchtower_level )
	{
		// Thanks Lucas Jorge for this formula :)
		return ( WATCHTOWER_SECONDS + $this->functions->round_up ( ( pow ( ( 1 + ( - 1 / $watchtower_level ) ) * 2 , 3 ) * 100 ) ) );
	}
}

/* End of file empire.php */
/* Location: ./application/controllers/empire.php */