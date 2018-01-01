<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Market_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LA ARMERIA
	public function get_market_data()
	{
		// SELECT
		$this->db->select ( '*' )
					->from ( '{PREFIX}market AS m' )
					->order_by ( 'm.`market_resource_actual`' , 'DESC' );

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

	// INSERTA LOS VALORES PARA CONSTRUIR LOS EDIFICIOS Y REDUCE LA CANTIDAD DE ORO
	public function update_build ( $update_data , $resources , $user_id )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}resources` AS r, {PREFIX}buildings AS b
										SET
										b.`building_current_build` = '" . $update_data . "',
										r.`resource_gold` = r.`resource_gold` - " . $resources['gold'] . ",
										r.`resource_stone` = r.`resource_stone` - " . $resources['stone'] . ",
										r.`resource_wood` = r.`resource_wood` - " . $resources['wood'] . "
										WHERE r.`resource_user_id` = '" . $user_id . "' AND
												b.`building_user_id` = '" . $user_id . "'" );

		// UPDATE CON RETURN DE RESULTADO
		if ( $query )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file buildings_model.php */
/* Location: ./application/models/buildings_model.php */