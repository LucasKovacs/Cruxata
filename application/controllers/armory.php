<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Armory extends CI_Controller {

	private $armory;
	private $base_url;
	private $img_path;
	private $user_id;
	private	$current_build;
	private $weapon_to_build;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'armory_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'armory_model' );

		$this->base_url			= 	base_url();
		$this->img_path			=	$this->base_url . IMG_FOLDER;
		$this->user_id			=	$this->auth->get_id();

		// TRAEMOS TODOS LOS DATOS
		$this->armory			=	$this->armory_model->get_armory_data ( $this->user_id );

		// CONSTRUCCION ACTUAL
		$this->current_build	=	explode ( ';' , $this->armory[0]['armory_current_build'] );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post()  )
			{
				// SI SE RECIBIO UN REQUEST ENTONCES ACCEDEMOS A GRABAR EL ARMA
				$this->add_weapon();
			}

			// MUESTRA LA PAGINA
			$this->show_page();
		}
		else
		{
			redirect ( $this->base_url );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page()
	{
		if ( $this->armory[0]['building_armory'] == 1 )
		{
			$sub_template					= '';
			$parse['img_path']				= $this->img_path;
			$parse['time']					= '';
			$parse['lang']					= '';	
				
			foreach ( $this->armory[0] as $weapon => $level )
			{
				// VARIOS
				$parse['countdown_time']	=	'';
				$parse['weapon']			=	$weapon;
				$parse['level']				=	' (' . $this->lang->line ( 'ar_level' ) . $level . ') ';
				$parse['level_to_up']		=	$level + 1;
				$parse['description']		= 	'';
	
				$current_price				=	$this->armory_library->armory_element_price ( $weapon , $this->armory[0][$weapon] );
	
				$parse['required_wood']		=	0;
				$parse['required_stone']	=	0;
				$parse['required_gold']		=	$this->functions->format_number ( $current_price );
				$parse['required_time']		=	$this->functions->format_time ( $this->armory_library->armory_element_time ( $weapon , $this->armory[0][$weapon] ) );
				
				// CALCULO DEL DAÑO
				if ( $this->armory[0][$weapon] <= 0 )
				{
					$parse['current_power']	=	0;
				}
				else
				{
					$parse['current_power']	=	$this->functions->format_number ( $this->armory_library->armory_element_power ( $weapon , $this->armory[0][$weapon] ) );
				}
				
					$parse['next_power']	=	$this->functions->format_number ( $this->armory_library->armory_element_power ( $weapon , $this->armory[0][$weapon] + 1 ) );

				
				// TIPO
				$parse['type']				=	$this->armory_library->armory_element_type ( $weapon );
	
	
				// DETERMINA EL TIPO Y PEGA EL RADIO BUTTON SOLO SI VE QUE TIENE ORO
				if ( $parse['type'] == 'offensive' or $parse['type'] == 'defensive' )
				{
					if ( $this->current_build[0] == $weapon )
					{
						$parse['time']			= $this->current_build[1] - time();
						$parse['submit']   		= '<span id="countdown">' . $this->functions->format_time ( $parse['time'] ) . '</span>';
						$parse['lang']			= $this->lang->line ( 'ar_ready' );
					}
					else
					{
						if ( ( $current_price <= $this->armory[0]['resource_gold'] ) && ( $this->current_build[0] == '' ) )
						{
							$parse['submit']		= '<input type="submit" name="' . $weapon . '" value="' . $this->lang->line ( 'ar_levelup' ) . '" />';
						}
						else
						{
							$parse['submit']		= '&nbsp;';
						}
					}
					$sub_template[] 				= $parse;
				}
			}
		
			$parse['armory_rows']					= 	$sub_template;

			$this->template->page ( ARMORY_FOLDER . 'armory_view' , $parse );
		}
		else
		{
			$this->template->message_box ( $this->lang->line ( 'ar_armory_required' ) , '5' , 'empire' );
		}
	}

	// VALIDA EL ENVIO DE LOS DATOS
	private function validate_form()
	{
		$count	= 0;
		$error	= 0;

		foreach ( $this->input->post () as $key => $value )
		{
			$count++;

			$this->weapon_to_build	= $key;
		}

		$weapon_price	=	$this->armory_library->armory_element_price ( $this->weapon_to_build , $this->armory[0][$this->weapon_to_build] );

		if ( ( $weapon_price == 0 ) or ( $weapon_price == NULL ) or ( $weapon_price == '' ) )
		{
			$error++;
		}

		if ( ( $weapon_price > $this->armory[0]['resource_gold'] ) or ( $this->armory[0]['armory_current_build'] != '' ) )
		{
			$error++;
		}

		// SI O SI DEBE SER UNO PARA LA CUENTA
		// SI O SI DEBE SER CERO PARA LOS ERRORES
		if ( ( $count == 1 ) && ( $error == 0 ) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// PROCESA LO NECESARIO PARA INSERTAR EL ARMA
	private function add_weapon()
	{
		// VALIDAMOS EL POST
		if ( $this->validate_form() )
		{
			$data 		= '';
			$weapon		= $this->weapon_to_build;

			// REVISA Y ARMA EL ARRAY PARA GUARDAR EL ARMA
			if ( $weapon != '' )
			{
				$data	= $weapon . ';' . ( time() + $this->armory_library->armory_element_time ( $weapon , $this->armory[0][$weapon] ) ) . ';';
			}

			// SI EL ARRAY DEL ARMA FUE ESTABLECIDO CORRECTAMENTE LO GUARDAMOS EN LA BASE
			if ( $data != '' )
			{
				// SI LLEGAMOS HASTA AQUI SE SUPONE QUE EL ARMA PASO A LA COLA DE CONSTRUCCION
				$amount	=	$this->armory_library->armory_element_price ( $weapon , $this->armory[0][$weapon] );

				if ( ( $amount <= $this->armory[0]['resource_gold'] ) && ( $this->armory[0]['armory_current_build'] == '' ) )
				{
					if ( $this->armory_model->update_build ( $data , $this->user_id ) )
					{
						// YA PODEMOS DESCONTAR EL ORO DEL USUARIO
						$this->armory_model->reduce_gold ( $amount , $this->user_id );
					}
				}
			}

			// FINALMENTE REDIRIGIMOS
			redirect ( $this->base_url . 'armory' );
		}
	}
}

/* End of file armory.php */
/* Location: ./application/controllers/armory.php */