<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Missions extends CI_Controller {

	private $attack;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'academy_library' );
		$this->load->library ( 'field_library' );
		$this->load->library ( 'missions_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'missions_model' );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index ( $user_name )
	{
		if ( $this->auth->check() )
		{
			// ESTO LO TRAEMOS SIEMPRE, ES NECESARIO SIEMPRE.
			$user_id			=	$this->missions_model->check_username ( $user_name );

			if ( $user_id && ( $user_id[0]['user_id'] != $this->auth->get_id() ) )
			{
				// OBTIENE LA INFORMACIÓN DEL USUARIO Y LA ALMACENA PARA USARLA LUEGO
				$this->attack	=	$this->missions_model->get_mission_data ( $this->auth->get_id() );
				$this->enemy	=	$this->missions_model->get_mission_data ( $user_id[0]['user_id'] );
			}
			else
			{
				redirect ( base_url() . 'field/' . $user_name );
			}

			// SI HIZO SUBMIT
			if ( $this->input->post() )
			{
				// REALIZA EL PROCESO DE ATAQUE
				$this->insert_attack();
			}
			else
			{
				if ( $this->field_library->count_soldiers ( $this->attack[0] ) == 0 )
				{
					$this->template->message_box ( $this->lang->line ( 'mi_soldiers_needed' ) , '2' , 'field/' . $user_name );
				}
				else
				{
					 // MUESTRA LA PAGINA
					$this->show_page();
				}
			}
		}
		else
		{
			redirect ( base_url() );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page ()
	{
		$attack_data						=	$this->attack;
		$enemy_data							=	$this->enemy;

		$parse['base_url']					=	base_url();
		$parse['img_path']					=	$parse['base_url'] . IMG_FOLDER;
		$parse['soldiers_cols']				=	'';
		$i									=	0;

		$parse['enemy_name']				=	$enemy_data[0]['user_name'];
		$parse['value']						=	$this->field_library->count_soldiers ( $this->attack );
		$attack_time						= 	$this->field_library->get_attack_time ( $this->attack , $this->enemy );
		$parse['arrival_time']				=	$this->functions->format_time ( $attack_time );
		$parse['return_time']				=	$this->functions->format_time ( $attack_time + $attack_time + ATTACK_DURATION );

		// CONSTRUIMOS UN ARRAY DE SOLDADOS QUE ESTARÁN DISPONIBLES PARA ATACAR
		// NOS AHORRAMOS UNA PLANTILLA
		foreach ( $attack_data[0] as $soldier => $value )
		{
			if ( $this->academy_library->is_soldier ( $soldier ) )
			{
				if ( $value > 0 )
				{
					$column[]					= 	array 	(
																'img_path'	=>	$parse['img_path'],
																'soldier'	=>	$soldier,
																'value' 	=>	$value
															);

					$i++;
				}
			}
		}

		$parse['count']						=	$i;
		$parse['column']					= 	$column;

		$this->template->page ( MISSIONS_FOLDER . 'missions_view' , $parse );
	}

	// INSERTA LA INFORMACIÓN DEL ATAQUE
	private function insert_attack()
	{
		$attack_time						= 	$this->field_library->get_attack_time ( $this->attack , $this->enemy );
		$data['army_user_id']				= 	$this->attack[0]['user_id'];
		$data['army_receptor_id']			=	$this->enemy[0]['user_id'];
		$data['army_mission']				=	(int)$this->input->post ( 'mission' );
		$data['army_mission']				=	( $data['army_mission'] == 0 ) ? 1 : $data['army_mission'];
		$current_time						=	time();
		$data['army_arrival']				=	floor ( $current_time + $attack_time );
		$mission_duration					=	$this->missions_library->return_mission_duration ( $data['army_mission'] );	
		$data['army_return']				=	floor ( $mission_duration + $current_time + $attack_time * 2 );
		$data['army_current']				=	floor ( $mission_duration + $current_time + $attack_time);
		$data['army_troops']				=	'';		

		// RECORREMOS PARA PODER CARGAR LOS SOLDADOS EN EL MOVIMIENTO DEL EJERCITO
		foreach ( $this->input->post() as $soldier => $amount )
		{
			if ( $this->academy_library->is_soldier ( $soldier ) )
			{
				if ( $amount != 0 && ( $this->functions->is_a_number ( $amount ) ) )
				{
					if ( $this->attack[0][$soldier] > 0 )
					{
						$data['army_troops']   .=	$soldier . ',' . ( ( $this->attack[0][$soldier] >= $amount ) ? $amount : $this->attack[0][$soldier] ) . ';';
						$reduce[$soldier]		= 	( ( $this->attack[0][$soldier] >= $amount ) ? $amount : $this->attack[0][$soldier] );
					}
				}
			}
		}

		// CHEQUEAMOS SI SE CARGO ALGO EN EL ARMY_TROOPS
		if ( $data['army_troops'] != '' )
		{
			$this->missions_model->insert_attack_data ( $data ); // INSERTAMOS
			$this->missions_model->reduce_soldiers ( $reduce , $data['army_user_id'] ); // REDUCE LA CANTIDAD DE SOLDADOS

			redirect ( base_url() . 'armies' );
		}
		else
		{

			// MOSTRAMOS MENSAJE DE ERROR
			$this->template->message_box ( $this->lang->line ( 'mi_soldiers_required' ) , '2' , 'missions/' . $this->enemy[0]['user_name'] );
		}
	}
}

/* End of file missions.php */
/* Location: ./application/controllers/missions.php */