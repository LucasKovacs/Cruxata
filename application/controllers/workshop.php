<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshop extends CI_Controller {

	private $workshop;
	private $amount;
	private $base_url;
	private $img_path;
	private $user_id;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'workshop_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'workshop_model' );

		// ALGUNOS VALORES POR DEFECTO
		$this->base_url		=	base_url();
		$this->img_path		=	$this->base_url . IMG_FOLDER;
		$this->user_id		=	$this->auth->get_id();

		// TRAEMOS TODOS LOS DATOS
		$this->workshop		=	$this->workshop_model->get_workshop_data (  $this->user_id );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post()  )
			{
				// SI SE RECIBIO UN REQUEST ENTONCES ACCEDEMOS A GRABAR LOS SOLDADOS
				$this->add_weapon();
			}
			else
			{
				// MUESTRA LA PAGINA
				$this->show_page();
			}
		}
		else
		{
			redirect ( $this->base_url );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page ()
	{
		$workshop_data	=	$this->workshop;

		$parse['base_url']			=	$this->base_url;
		$parse['img_path']			= 	$this->img_path;
		$parse['workshop_rows']		=	'';
		$i							=	0;

		foreach ( $workshop_data[0] as $weapon => $amount )
		{
			if ( $this->workshop_library->is_weapon ( $weapon ) )
			{
				// PEGAMOS LA INFORMACION
				$parse['amount']			=	$amount;
				$parse['weapon']			=	$weapon;
				$wood						=	$this->workshop_library->workshop_element_wood ( $weapon );
				$gold						=	$this->workshop_library->workshop_element_gold ( $weapon );
				$parse['required_wood'] 	= 	$this->functions->format_number ( $wood );
				$parse['required_stone'] 	= 	0;
				$parse['required_gold']		=	$this->functions->format_number ( $gold );
				$parse['required_time']		=	$this->functions->format_time ( $this->workshop_library->workshop_element_time ( $weapon ) );
				$parse['weapon_image']		=	$weapon;
				$current_work				= 	$this->get_working_weapons ( $weapon );
				$parse['value']				=	'weapon' . ++$i;
				$parse['time']				=	$current_work[2] - time();
				$parse['actions']			=	'';
				$parse['lang']				= 	'';

				if ( $workshop_data[0]['building_workshop'] > 0 )
				{
					// MANEJAMOS LAS ACCIONES
					if ( $current_work[0] == $weapon )
					{
						$parse['lang']		= 	$this->lang->line ( 'wo_ready' );
						$parse['actions']  .=	'<span id="' . $parse['value'] . '">' . $this->functions->format_time ( $parse['time'] ) . '</span>';
					}

					if ( ( $wood <= $workshop_data[0]['resource_wood'] ) && ( $gold <= $workshop_data[0]['resource_gold'] ) )
					{
						$parse['actions']	.=	form_open( '' , 'name="' . $weapon . '-form"' );
						$parse['actions']  	.=	'<input type="text" name="' . $weapon . '" style="width:68px" maxlength="4"/><br /><br /><input type="submit" name="submit-' . $weapon . '" value="' . $this->lang->line ( 'wo_build' ) . '"/>';
						$parse['actions']  	.= 	form_close();
					}
				}
				else
				{
					$parse['actions']		= 	$this->lang->line ( 'wo_workshop_required' );
				}

				$workshop_rows[]    	   	=	$parse;
			}
		}

		$parse['workshop_rows']				= $workshop_rows;

		$this->template->page ( WORKSHOP_FOLDER . 'workshop_view' , $parse );
	}

	// VALIDA EL ENVIO DE LOS DATOS
	private function validate_form()
	{
		$count	=	0;

		foreach ( $this->input->post() as $input => $value )
		{
			if ( $this->functions->is_a_number ( $value ) )
			{
				$this->weapon	=	$input;
				$this->amount	=	$value;
				$count++;
			}
		}

		if ( $count == 1 )
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
		$data	=	'';

		if ( $this->validate_form() )
		{
			$user_wood				=	$this->workshop[0]['resource_wood'];
			$user_gold				=	$this->workshop[0]['resource_gold'];
			$amount_to_add			=	$this->amount;
			$weapon_wood			= 	$this->workshop_library->workshop_element_wood ( $this->weapon );
			$weapon_gold			= 	$this->workshop_library->workshop_element_gold ( $this->weapon );
			$weapon_time			=  	$this->workshop_library->workshop_element_time ( $this->weapon );

			// PRIMERO VERIFICAMOS POR LA CANTIDAD DE SOLDADOS
			if ( $amount_to_add > 0 )
			{
				$wood_required	=	$amount_to_add * $weapon_wood;
				$gold_required	= 	$amount_to_add * $weapon_gold;
				$time_required	=	time() + ( $amount_to_add * $weapon_time );

				// RECURSOS NECESARIO
				// SI LOS RECURSOS QUE TIENE EL JUGADOR ES EL MISMO O ES MAYOR AL QUE REQUIERE
				// ENTONCES CONTINUAMOS
				// SINO CALCULAMOS EL PROPORCIONAL
				if ( ( $user_wood >= $wood_required ) && ( $user_gold >= $gold_required ) )
				{
					$max_to_add		=	$amount_to_add;
					$wood_required	=	$wood_required;
					$gold_required	=	$gold_required;
					$time_required	=	$time_required;
				}
				else
				{
					$max_wood_to_add	=	floor ( $user_wood / $weapon_wood );
					$max_gold_to_add	=	floor ( $user_gold / $weapon_gold );

					if ( $max_wood_to_add > $max_gold_to_add )
					{
						$max_to_add		=	$max_gold_to_add;
					}

					if ( $max_gold_to_add > $max_wood_to_add )
					{
						$max_to_add		=	$max_wood_to_add;
					}

					$wood_required		=	$max_to_add * $weapon_wood;
					$gold_required		=	$max_to_add * $weapon_gold;
					$time_required		=	time() + ( $max_to_add * $weapon_time );
				}

				// ARMAMOS EL ARRAY
				$data		=	$this->workshop[0]['workshop_current_build'] . $this->weapon . ',' . $max_to_add . ',' . $time_required . ';';

				// ACTUALIZACIONES
				$this->workshop_model->update_workshop ( $data , $wood_required , $gold_required , $this->user_id );

				// REDIRIGIMOS
				redirect ( $this->base_url . 'workshop' );
			}
		}
		else
		{
			redirect ( $this->base_url . 'workshop' );
		}
	}

	// PROCESA LA COLA DE ESPECIALIZACION DE SOLDADOS
	private function get_working_weapons ( $weapon_needed )
	{
		$queues			=	explode ( ';' , $this->workshop[0]['workshop_current_build'] );

		foreach ( $queues as $queue )
		{
			$elements	=	explode ( ',' , $queue );

			if ( $elements[0] == $weapon_needed )
			{
				return $elements;
			}
		}
	}
}


/* End of file workshop.php */
/* Location: ./application/controllers/workshop.php */