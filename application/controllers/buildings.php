<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buildings extends CI_Controller {

	private $buildings;
	private $building_to_build;
	private $type;

	// CONSTRUCTOR
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'buildings_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'buildings_model' );

		// TRAEMOS TODOS LOS DATOS
		$this->buildings	=	$this->buildings_model->get_buildings_data ( $this->auth->get_id() );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index ( $type = '' )
	{
		if ( $this->auth->check() )
		{
			$this->type =	$type;

			if ( $this->type == 'production' or $this->type == 'infrastructure' )
			{
				if ( $this->input->post()  )
				{
					// SI SE RECIBIO UN REQUEST ENTONCES ACCEDEMOS A GRABAR EL EDIFICIO
					$this->add_building();
				}

				// MUESTRA LA PAGINA
				$this->show_page();
			}
			else
			{
				redirect ( base_url() . 'empire' );
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
		// OBTENEMOS Y SETEAMOS ALGUNOS VALORES
		$buildings_data					= $this->buildings;
		$buildings_rows					= '';
		$parse['input_building']		= '';
		$current_build 					= explode ( ';' , $buildings_data[0]['building_current_build'] );

		// VALORES A PASAR
		$parse['base_url']				= base_url();
		$parse['img_path']				= $parse['base_url'] . IMG_FOLDER;
		$parse['page']					= $this->type;
		$parse['time']					= '';
		$parse['lang']					= '';

		foreach ( $buildings_data[0] as $building => $level )
		{
			$data	=	$this->return_building_data ( $building , $buildings_data );

			if ( $this->buildings_library->is_building_type ( $building ) == $this->type )
			{
				$parse['building_image']	= $building;
				$parse['title']				= $this->lang->line ( 'bu_' . $building );
				$parse['description']		= $this->lang->line ( 'bu_' . $building . '_description' );
				$parse['required_gold']		= $this->functions->format_number ( $data['price']['gold'] );
				$parse['required_stone']	= $this->functions->format_number ( $data['price']['stone'] );
				$parse['required_wood']		= $this->functions->format_number ( $data['price']['wood'] );
				$parse['required_time']		= $this->functions->format_time ( $data['time'] );
				$parse['level']				= $this->lang->line ( 'bu_level' ) . ' ' . $buildings_data[0][$building];
				$parse['level_to_up']		= $buildings_data[0][$building] + 1;

				if ( $current_build[0] == $building )
				{
					$parse['time']					= $current_build[1] - time();
					$parse['lang']					= $this->lang->line ( 'em_ready' );
					$parse['input_building']	   	= '<span id="countdown">' . $this->functions->format_time ( $parse['time'] ) . '</span>';
				}
				else
				{
					if ( $this->reached_max_level ( $building , $buildings_data[0][$building] ) )
					{
						$parse['input_building']	= $this->lang->line ( 'bu_max_level' );
					}
					else
					{
						if ( ( $data['price']['gold'] <= $buildings_data[0]['resource_gold'] ) &&
							 ( $data['price']['stone'] <= $buildings_data[0]['resource_stone'] ) &&
							 ( $data['price']['wood'] <= $buildings_data[0]['resource_wood'] ) &&
							( $current_build[0] == '' ) )
						{
							$parse['input_building']	= '<input type="submit" name="' . $building . '" value="' . $this->lang->line ( 'bu_upgrade' ) . '" />';
						}
						else
						{
							$parse['input_building']	= '';
						}
					}
				}

				$buildings_rows[]	= $parse;
			}
		}

		$parse['buildings_table']	= $buildings_rows;


		$this->template->page ( BUILDINGS_FOLDER . 'buildings_view' , $parse );
	}

	// VALIDA EL ENVIO DE LOS DATOS
	private function validate_form()
	{
		$count	= 0;
		$error	= 0;

		foreach ( $this->input->post () as $key => $value )
		{
			$count++;

			$this->building_to_build	= $key;
		}

		$data	=	$this->return_building_data ( $this->building_to_build , $this->buildings );

		if ( ( $data['price']['gold'] > $this->buildings[0]['resource_gold'] ) or
			 ( $data['price']['stone'] > $this->buildings[0]['resource_stone'] ) or
			 ( $data['price']['wood'] > $this->buildings[0]['resource_wood'] ) or
			 ( $this->buildings[0]['building_current_build'] != '' ) )
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

	// PROCESA LO NECESARIO PARA INSERTAR EL EDIFICIO
	private function add_building()
	{
		// VALIDAMOS
		if ( $this->validate_form() )
		{
			// SI PASO, AGREGAMOS LOS DATOS Y DESCONTAMOS EL ORO
			$data 			= '';
			$user_id		= $this->auth->get_id();
			$building_info	= array();

			$building_info	= $this->return_building_data ( $this->building_to_build , $this->buildings );

			// REVISA Y ARMA EL ARRAY PARA GUARDAR EL ARMA
			$data	= $this->building_to_build . ';' . ( time() + $building_info['time'] ) . ';';

			// SI EL ARRAY DEL ARMA FUE ESTABLECIDO CORRECTAMENTE LO GUARDAMOS EN LA BASE
			if ( $data != '' )
			{
				// SI LLEGAMOS HASTA AQUI SE SUPONE QUE EL ARMA PASO A LA COLA DE CONSTRUCCION
				if ( ( $building_info['price']['gold'] <= $this->buildings[0]['resource_gold'] ) &&
					 ( $building_info['price']['stone'] <= $this->buildings[0]['resource_stone'] ) &&
					 ( $building_info['price']['wood'] <= $this->buildings[0]['resource_wood'] ) &&
					( $this->buildings[0]['building_current_build'] == '' ) )
				{
					// ACTUALIZAMOS
					$this->buildings_model->update_build ( $data , $building_info['price'] , $user_id );
				}
			}
		}

		// FINALMENTE REDIRIGIMOS
		redirect ( base_url() . $this->type );
	}

	// SELECCIONA LA INFORMACION CORRESPONDIENTE AL EDIFICIO ACTUAL Y LA RETORNA
	private function return_building_data ( $building , $building_data , $zero = TRUE )
	{
		$level			= $building_data[0][$building];
		$data['price']	= $this->buildings_library->building_price ( $building , $level );
		$data['time']	= $this->buildings_library->building_time ( $building , $level );

		return $data;
	}

	// DETERMINA SI ES UN ELEMENTO CON NIVEL MAXIMO
	private function reached_max_level ( $building , $level )
	{
		$max_level_elements	= array (
										'building_armory',
										'building_market',
										'building_workshop'
									);

		$max_level			= array (
										'building_armory' 	=> 1,
										'building_market'	=> 1,
										'building_workshop'	=> 1
									);

		if ( in_array ( $building , $max_level_elements ) && ( $level >= $max_level[$building] ) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file buildings.php */
/* Location: ./application/controllers/buildings.php */