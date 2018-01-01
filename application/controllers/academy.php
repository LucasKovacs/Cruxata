<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Academy extends CI_Controller {

	private $academy;
	private $amount;
	private $base_url;
	private $img_path;
	private $user_id;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'academy_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'academy_model' );

		// ALGUNOS VALORES POR DEFECTO
		$this->base_url		=	base_url();
		$this->img_path		=	$this->base_url . IMG_FOLDER;
		$this->user_id		=	$this->auth->get_id();

		// TRAEMOS TODOS LOS DATOS
		$this->academy		=	$this->academy_model->get_academy_data (  $this->user_id );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post()  )
			{
				// SI SE RECIBIO UN REQUEST ENTONCES ACCEDEMOS A GRABAR LOS SOLDADOS
				$this->add_soldier();
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
		$academy_data				=	$this->academy;

		$parse['base_url']			=	$this->base_url;
		$parse['img_path']			= 	$this->img_path;
		$parse['academy_rows']		=	'';
		$i							=	0;
		$user_soldiers				=	$this->academy_library->count_soldiers();
		$max_soldiers				=	$this->buildings_library->building_production ( 'building_barracks' , $this->academy[0]['building_barracks'] );


		foreach ( $academy_data[0] as $soldier => $amount )
		{
			if ( $this->academy_library->is_soldier ( $soldier ) )
			{
				// PEGAMOS LA INFORMACION
				$parse['quantity']			=	$amount;
				$parse['soldier']			=	$soldier;
				$required_gold				=	$this->functions->format_number ( $this->academy_library->academy_element_price ( $soldier ) );
				$parse['required_wood'] 	= 	0;
				$parse['required_stone'] 	= 	0;
				$parse['required_gold']		=	( ( $required_gold == 0 ) ? $this->lang->line ( 'ac_price_free' ) : $required_gold );
				$required_time				=	$this->functions->format_time ( $this->academy_library->academy_element_time ( $soldier ) );
				$parse['required_time']		=	( ( $required_time == 0 ) ? $this->lang->line ( 'ac_time_free' ) : $required_time );
				$parse['soldier_image']		=	$soldier;
				$current_work				= 	$this->get_working_soldiers ( $soldier );
				$parse['value']				=	'soldier' . ++$i;
				$parse['time']				=	$current_work[2] - time();
				$parse['actions']			=	'';
				$parse['lang']				= 	'';

				if ( sqrt ( $academy_data[0]['building_academy'] ) >= $i )
				{
					// MANEJAMOS LAS ACCIONES
					if ( $current_work[0] == $soldier )
					{
						$parse['lang']		= 	$this->lang->line ( 'ac_ready' );
						$parse['actions']  .=	'<span id="' . $parse['value'] . '">' . $this->functions->format_time ( $parse['time'] ) . '</span><br />';
					}

					if ( $this->academy_library->academy_element_price ( $soldier ) <= $academy_data[0]['resource_gold'] && ( ( $max_soldiers - $user_soldiers ) > 0 ) )
					{
						$parse['actions']  .=	form_open( '' , 'name="' . $soldier . '-form"' );
						$parse['actions']  .=	'<input type="text" name="' . $soldier . '" style="width:68px" maxlength="4"/><br /><br /><input type="submit" name="submit-' . $soldier . '" value="' . $this->lang->line ( 'ac_specialize' ) . '"/>';
						$parse['actions']  .= 	form_close();
					}
				}
				else
				{
					$parse['actions']		= 	str_replace ( '%s' , $this->academy_library->academy_element_level ( $soldier ) , $this->lang->line ( 'ac_academy_required' ) );
				}

				$academy_rows[] 	   	    =	$parse;
			}
		}

		$parse['academy_rows']				=	$academy_rows;

		$this->template->page ( ACADEMY_FOLDER . 'academy_view' , $parse );
	}

	// VALIDA EL ENVIO DE LOS DATOS
	private function validate_form()
	{
		$count	=	0;

		foreach ( $this->input->post() as $input => $value )
		{
			if ( $this->functions->is_a_number ( $value ) )
			{
				$this->soldier	=	$input;
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
	private function add_soldier()
	{
		$data	=	'';

		if ( $this->validate_form() )
		{
			$this->load->library ( 'buildings_library' );

			$user_gold				=	$this->academy[0]['resource_gold'];
			$amount_to_add			=	$this->amount;
			$user_soldiers			=	$this->academy_library->count_soldiers();
			$max_soldiers			=	$this->buildings_library->building_production ( 'building_barracks' , $this->academy[0]['building_barracks'] );
			$required_soldiers		=	$max_soldiers -	$user_soldiers;
			$soldier_price			= 	$this->academy_library->academy_element_price ( $this->soldier );
			$soldier_time			=  	$this->academy_library->academy_element_time ( $this->soldier );

			// PRIMERO VERIFICAMOS POR LA CANTIDAD DE SOLDADOS
			if ( $required_soldiers > 0 )
			{
				// CHEQUEAMOS LOS SOLDADOS QUE DESEA COLOCAR EN COMPARACION CON LOS QUE
				// YA TIENE
				if ( $required_soldiers >= $this->amount )
				{
					$to_add		=	$this->amount;
				}
				else
				{
					$to_add		=	$required_soldiers;
				}

				$gold_required	= 	$to_add * $soldier_price;
				$time_required	=	time() + ( $to_add * $soldier_time );

				// ORO NECESARIO
				// SI EL ORO QUE TIENE EL JUGADOR ES EL MISMO O ES MAYOR AL QUE REQUIERE
				// ENTONCES CONTINUAMOS
				// SINO CALCULAMOS EL PROPORCIONAL
				if ( $user_gold >= $gold_required )
				{
					$max_to_add		=	$to_add;
					$gold_required	=	$gold_required;
					$time_required	=	$time_required;
				}
				else
				{
					$max_to_add		=	floor ( $user_gold / $soldier_price );
					$gold_required	=	$max_to_add * $soldier_price;
					$time_required	=	time() + ( $max_to_add * $soldier_time );
				}

				// ARMAMOS EL ARRAY
				$data		=	$this->academy[0]['academy_current_build'] . $this->soldier . ',' . $max_to_add . ',' . $time_required . ';';

				// ACTUALIZACIONES
				$this->academy_model->update_academy ( $data , $gold_required , $this->user_id );

				// REDIRIGIMOS
				redirect ( $this->base_url . 'academy' );
			}
			else
			{
				$this->template->message_box ( $this->lang->line ( 'ac_soldiers_required' ) , '2' , 'academy' );
			}
		}
		else
		{
			redirect ( $this->base_url . 'academy' );
		}
	}

	// PROCESA LA COLA DE ESPECIALIZACION DE SOLDADOS
	private function get_working_soldiers ( $soldier_needed )
	{
		$queues			=	explode ( ';' , $this->academy[0]['academy_current_build'] );

		foreach ( $queues as $queue )
		{
			$elements	=	explode ( ',' , $queue );

			if ( $elements[0] == $soldier_needed )
			{
				return $elements;
			}
		}
	}
}


/* End of file academy.php */
/* Location: ./application/controllers/academy.php */