<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshop_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LA ARMERIA
	public function get_workshop_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'w.workshop_current_build,
							w.workshop_catapult,
							w.workshop_trebuchet,
							b.building_workshop,
							r.resource_wood,
							r.resource_gold' );

		// FROM
		$this->db->from ( '{PREFIX}workshop AS w, {PREFIX}buildings AS b, {PREFIX}resources AS r' );

		// WHERE
		$this->db->where ( '`w`.`workshop_user_id`' , $user_id , FALSE );
		$this->db->where ( '`b`.`building_user_id`' , $user_id , FALSE );
		$this->db->where ( '`r`.`resource_user_id`'	, $user_id , FALSE );

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

	// INSERTA LOS VALORES PARA CONSTRUIR LAS ARMAS Y REDUCE EL ORO
	public function update_workshop ( $update_data , $wood , $gold , $user_id )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}resources` AS r, `{PREFIX}workshop` AS w SET
										r.`resource_wood` = r.`resource_wood` - " . $wood . ",
										r.`resource_gold` = r.`resource_gold` - " . $gold . ",
										w.`workshop_current_build` = '" . $update_data . "'
										WHERE r.`resource_user_id` = '" . $user_id . "'
												AND w.`workshop_user_id` = '" . $user_id . "'" );

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

/* End of file workshop_model.php */
/* Location: ./application/models/workshop_model.php */