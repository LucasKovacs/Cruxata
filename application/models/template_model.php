<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR EL HEADER
	public function get_template_data ( $user_id )
	{
		// SELECT
		$this->db->select ( '(SELECT COUNT( `message_id` ) FROM `{PREFIX}messages` WHERE `message_viewed` =0 AND message_user_id = ' . $user_id . ') AS resource_messages,
							b.building_barracks,
							r.resource_diamonds,
							r.resource_gold,
							r.resource_stone,
							r.resource_wood,
							u.user_name,
							u.user_status')
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

/* End of file template_model.php */
/* Location: ./application/models/template_model.php */