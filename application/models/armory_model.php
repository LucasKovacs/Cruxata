<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Armory_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LA ARMERIA
	public function get_armory_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'a.armory_current_build,
							a.armory_hammer,
							a.armory_spear,
							a.armory_ax,
							a.armory_sword,
							a.armory_crossbow,
							a.armory_gauntlet,
							a.armory_boot,
							a.armory_helmet,
							a.armory_shield,
							a.armory_breastplate,
							b.building_armory,
							r.resource_gold' );

		// FROM
		$this->db->from ( '{PREFIX}armory AS a, {PREFIX}buildings AS b, {PREFIX}resources AS r' );

		// WHERE
		$this->db->where ( '`a`.`armory_user_id`' 	, $user_id , FALSE );
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

	// INSERTA LOS VALORES PARA CONSTRUIR LAS ARMAS
	public function update_build ( $update_data , $user_id )
	{
		// WHERE
		$this->db->where ( 'armory_user_id' , $user_id );

		// UPDATE CON RETURN DE RESULTADO
		if ( $this->db->update ( '{PREFIX}armory' ,
													array (
															'armory_current_build' 	=> $update_data
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

	// REDUCE LA CANTIDAD DE ORO
	public function reduce_gold ( $amount , $user_id )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}resources`
										SET `resource_gold` = `resource_gold` - " . $amount . " WHERE `resource_user_id` = '" . $user_id . "'" );

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