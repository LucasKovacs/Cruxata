<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Armies extends CI_Controller {

	private $armies;
	private $base_url;
	private $img_path;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'missions_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'armies_model' );

		// TRAEMOS TODOS LOS DATOS
		$this->armies	=	$this->armies_model->get_armies_data ( $this->auth->get_id() );

		$this->base_url	=	base_url();
		$this->img_path	=	$this->base_url . IMG_FOLDER;
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
		$armies_data										=  	$this->armies;

		if ( count ( $armies_data[0] ) > 0 )
		{
			$parse['base_url']								=	$this->base_url;
			$parse['img_path']								=	$this->img_path;
			$parse['armies_rows']							=	'';
			$i												=	0;

			foreach ( $armies_data as $army )
			{
				$parse['count']								=	++$i;
				$parse['army_id']							=	$army['army_id'];
				$parse['user_name']							=	$army['user_name'];
				$parse['user_castle_img']					=	$army['user_castle_img'];
				$parse['current_army']						=	$this->build_troops_popup ( $army['army_troops'] );
				$parse['attack_in_progress']				=	'';
				$parse['gold_captured']						=	'';
				$parse['value']								=	'troops' . $i;
				$parse['show_cancel']						=	TRUE;
				$flag										=	FALSE;
				$parse['start']								= 	$army['army_arrival'] - time();	
				$parse['attack']							= 	$army['army_current'] - time();
				$parse['end']								= 	$army['army_return'] - time();
				
				// MUESTRA EL TIEMPO DE IDA Y EL MENSAJE CORRESPONDIENTE
				if ( $army['army_arrival'] - time() < 0 )
				{
					$parse['arrival_time'] 					= 	'';
					$flag									= 	TRUE;
				}
				else
				{
					$parse['arrival_time'] 					=	$this->lang->line ( 'am_arrival' ) . '<span id="cds' . $parse['value'] . '">' . $this->functions->format_hour ( $parse['start'] ) . '</span>';
				}

				// MUESTRA EL TIEMPO DE REGRESO Y EL MENSAJE CORRESPONDIENTE
				if ( $army['army_return'] - time() < 0 )
				{
					$parse['return_time']					=   '';
				}
				else
				{					
					// MUESTRA EL TIEMPO DE ATAQUE Y EL MENSAJE CORRESPONDIENTE
					if ( ( $army['army_current'] - time() >= 0 ) && ( $army['army_current'] - time() <= $this->missions_library->return_mission_duration ( $army['army_mission'] ) ) )
					{
						$parse['attack_in_progress']		=	'<font color="#990f1f">[' . $this->return_mission_language ( $army['army_mission'] ) . ' - (<span id="cda' . $parse['value'] . '">' . $this->functions->format_hour ( $parse['attack'] ) . '</span>)] </font>';
						$parse['show_cancel']				=	FALSE;						
					}
					else
					{
						if ( $flag )
						{
							$parse['show_cancel']			=	FALSE;
							$parse['attack_in_progress']	=	'<font color="#9acd32">[' . $this->lang->line ( 'am_attack_returning' ) . '] </font>';
							$parse['gold_captured']			=	str_replace ( 	array 	( '%w' , '%s' , '%g' ) ,
																				array	(
																							'<font color="brown">' . $this->functions->format_number ( $army['army_wood'] ) . '</font>',
																							'<font color="#999999">' . $this->functions->format_number ( $army['army_stone'] ) . '</font>',
																							'<font color="#808000">' . $this->functions->format_number ( $army['army_gold'] ) . '</font>'
																						) , $this->lang->line ( 'am_captured' ) );
						}
						else
						{
							$parse['attack_in_progress']	=	'<font color="#6f9fc8">[' . $this->lang->line ( 'am_attack_going' ) . '] </font>';
						}
					}

					// MUESTRA EL TIEMPO DE REGRESO Y EL MENSAJE CORRESPONDIENTE
					if ( $flag )
					{
						$parse['return_time']				=   $this->lang->line ( 'am_extended_return' ) . '<span id="cde' . $parse['value'] . '">' . $this->functions->format_hour ( $parse['end'] ) . '</span>';
					}
					else
					{
						$parse['return_time']				=   $this->lang->line ( 'am_return' ) . '<span id="cde' . $parse['value'] . '">' . $this->functions->format_hour ( $parse['end'] ) . '</span>';
					}
				}

				$parse['armies_rows']	   .= $this->load->view ( ARMIES_FOLDER . 'armies_row_view' , $parse , TRUE );
			}

			$this->template->page ( ARMIES_FOLDER . 'armies_view' , $parse );
		}
		else
		{
			$this->template->message_box ( $this->lang->line ( 'am_no_armies' ) , '5' , 'empire' );
		}
	}
	
	// CANCELA EL ATAQUE
	public function cancel_attack ( $army_id )
	{
		// RECORREMOS TODOS LOS EJERCITOS		
		foreach ( $this->armies as $army_key => $army_data )
		{
			// CUANDO DETECTAMOS EL QUE NECESITAMOS LABURAMOS
			if ( ( $army_data['army_id'] == $army_id ) && ( $army_data['army_current'] != 0 ) && !( ( $army_data['army_current'] - time() >= 0 ) && ( $army_data['army_current'] - time() <= ATTACK_DURATION ) ) )
			{
				$this->armies_model->return_army ( $army_data );
			}
		}
		
		redirect ( 'armies' );
	}
	
	// ARMA EL POPUP DE SOLDADOS QUE ESTAN EN UN EJERCITO
	private function build_troops_popup ( $troops )
	{
		$troop					=	explode ( ';' , $troops );
		$sub_template			=	'';

		foreach ( $troop as $element )
		{
			if ( $element != NULL )
			{
				$soldier			=	explode ( ',' , $element );

				$row['soldier']		=	$this->lang->line ( 'ac_' . $soldier[0] ) . ': ';
				$row['amount']		=	$soldier[1];

				$sub_template  	   .=	$this->load->view ( ARMIES_FOLDER . 'armies_troops_row_view' , $row , TRUE );
			}
		}

		$parse['base_url']		=	$this->base_url;
		$parse['img_path']		=	$this->img_path;
		$parse['troops_rows']	=	$sub_template;

		return $this->load->view ( ARMIES_FOLDER . 'armies_troops_view' , $parse , TRUE );
	}
	
	// RETORNA EL NOMBRE DE LA MISIÓN ACTUAL
	private function return_mission_language ( $mission_id )
	{
		switch ( $mission_id )
		{
			case 1: // ATAQUE
				return $this->lang->line ( 'am_attack_in_progress' );
			break;
			
			case 2: // EXPLORAR
				return $this->lang->line ( 'am_explore_in_progress' );
			break;
			
			case 3: // INVADIR
				return $this->lang->line ( 'am_invade_in_progress' );
			break;
			
			case 4: // INVADIR
				return $this->lang->line ( 'am_occupy_in_progress' );
			break;
		}
	}
}

/* End of file armies.php */
/* Location: ./application/controllers/armies.php */