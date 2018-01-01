<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Academy_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LA ACADEMIA
	public function get_academy_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'a.*,
							 ar.armory_hammer,
							 ar.armory_spear,
							 ar.armory_ax,
							 ar.armory_sword,
							 ar.armory_crossbow,
							 b.building_academy,
							 b.building_barracks,
  						     r.resource_gold' );

		// FROM
		$this->db->from ( '{PREFIX}academy AS a, {PREFIX}armory AS ar, {PREFIX}buildings AS b, {PREFIX}resources AS r' );

		// WHERE
		$this->db->where ( '`a`.`academy_user_id`' , $user_id , FALSE );
		$this->db->where ( '`ar`.`armory_user_id`' , $user_id , FALSE );
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
			return false;
		}
	}

	// RETORNA LOS SOLDADOS
	public function get_soldiers ( $user_id )
	{
		// SELECT
		$this->db->select ( 'a.academy_current_build,
							 a.academy_warrior,
							 a.academy_spearman,
							 a.academy_infantryman,
							 a.academy_swordsman,
							 a.academy_crossbowman' );

		// FROM
		$this->db->from ( '{PREFIX}academy AS a' );

		// WHERE
		$this->db->where ( '`a`.`academy_user_id`' , $user_id , FALSE );

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

	// RETORNA LOS EJERCITOS EN MOVIMIENTO
	public function get_armies ( $user_id )
	{
		// SELECT
		$this->db->select ( 'arm.army_troops' );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( '{PREFIX}armies AS arm' , 'arm.army_user_id = u.user_id' , 'LEFT' );

		// WHERE
		$this->db->where ( '`u`.`user_id`' , $user_id , FALSE );

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

	// INSERTA LOS VALORES PARA CONSTRUIR LAS ARMAS Y REDUCE EL ORO
	public function update_academy ( $update_data , $amount , $user_id )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}resources` AS r, `{PREFIX}academy` AS a SET 
										r.`resource_gold` = r.`resource_gold` - " . $amount . ",
										a.`academy_current_build` = '" . $update_data . "'
										WHERE r.`resource_user_id` = '" . $user_id . "'
												AND a.`academy_user_id` = '" . $user_id . "'" );

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

/* End of file armory_model.php */
/* Location: ./application/models/armory_model.php */