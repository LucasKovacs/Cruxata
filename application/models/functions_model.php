<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA EL NIVEL DEL CUARTEL (BARRACKS)
	public function get_barracks_level ( $user_id )
	{
		// SELECT
		$this->db->select ( 'building_barracks' );

		// FROM
		$this->db->from ( '{PREFIX}buildings' );

		// WHERE
		$this->db->where ( 'building_user_id' , $user_id );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->result() )
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	// INSERTA EL HASH EN SU RESPECTIVA TABLA
	public function insert_hash_data ( $hash , $rand_number , $user_id )
	{
		$this->db->delete ( 'validations' , array ( 'validation_user_id' => $user_id ) );

		if ( $this->db->insert ( '{PREFIX}validations' ,
														array (
																'validation_user_id' 		=> $user_id,
																'validation_control_number' => $rand_number,
																'validation_hash'			=> $hash
															  )
							   )

		   )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file functions_model.php */
/* Location: ./application/models/functions_model.php */