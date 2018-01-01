<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_library
{
	private $ci;

	// __construct
	public function __construct()
	{
		// INSTANCIA DE CI
		$this->ci = &get_instance();

		// MODEL
		$this->ci->load->model ( 'update_model' );

		// CARGAMOS LA LIBRERIA
		$this->ci->load->library ( 'update_general' );
	}

	// TOMA DATOS REQUERIDOS Y LOS PASA A LA LIBRERIA DE ACTUALIZACION
	public function update_data ( $user_id )
	{
		// INICIAMOS LAS TRANSACCIONES
		$this->ci->db->trans_start();

		$get_data['user_id']		=	$user_id;
		$get_data['time']			=	time();
		$get_data['update_data']	=	$this->ci->update_model->get_update_data ( $get_data['user_id'] );

		// RETORNA EL ARRAY DE ACTUALIZACION GENERAL
		$update_data				=	$this->ci->update_general->check_updates ( $get_data );

		// ACTUALIZAMOS LA INFORMACION RECOPILADA
		if ( $update_data !== FALSE )
		{
			$this->ci->update_model->update_data ( $update_data );
		}
		
		// FINALIZAMOS LAS TRANSACCIONES
		$this->ci->db->trans_complete();
	}
}

/* End of file update_library.php */
/* Location: ./application/libraries/update_library.php */