<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productivity_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR EL HEADER
	public function get_production_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'b.building_barracks,
							 b.building_goldmine,
							 b.building_sawmill,
							 b.building_stonemine,
							 r.resource_wood,
							 r.resource_stone,
							 r.resource_gold')
				->from ( 'users AS u' )
				->join ( 'buildings AS b' , 'b.building_user_id = u.user_id' )
				->join ( 'resources AS r' , 'r.resource_user_id = u.user_id' )
				->where ( 'u.user_id' , $user_id , FALSE );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->result() )
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file productivity_model.php */
/* Location: ./application/models/productivity_model.php */