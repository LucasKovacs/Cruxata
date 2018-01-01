<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attack_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LOS ATAQUES
	public function get_attack_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'c.academy_warrior,
							 c.academy_spearman,
							 c.academy_infantryman,
							 c.academy_swordsman,
							 c.academy_crossbowman,
							 a.armory_shield,
							 a.armory_gauntlet,
							 a.armory_boot,
							 a.armory_helmet,
							 a.armory_breastplate,
							 a.armory_hammer,
							 a.armory_spear,
							 a.armory_ax,
							 a.armory_sword,
							 a.armory_crossbow,
							 r.resource_gold,
							 r.resource_stone,
							 r.resource_wood,
							 u.user_id,
							 u.user_name,
							 u.user_kingdom,
							 u.user_feud' );

		// FROM
		$this->db->from ( '{PREFIX}academy AS c, {PREFIX}armory AS a, {PREFIX}resources AS r, {PREFIX}users AS u' );

		// WHERE
		$this->db->where ( '`c`.`academy_user_id`' 	, $user_id , FALSE );
		$this->db->where ( '`a`.`armory_user_id`' 	, $user_id , FALSE );
		$this->db->where ( '`r`.`resource_user_id`' , $user_id , FALSE );
		$this->db->where ( '`u`.`user_id`' 			, $user_id , FALSE );

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

	// REVISA SI EL NOMBRE DE USUARIO EXISTE
	public function check_username ( $user_name )
	{
		$this->db->select ( 'user_id' )->from ( 'users' )->where ( 'user_name' , $user_name );

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

	// INSERTA LA INFORMACIÓN DEL ATAQUE
	public function insert_attack_data ( $data )
	{
		if ( $this->db->insert ( 'armies' , $data ) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// REDUCE LOS SOLDADOS DE LA TABLA DE ACADEMIA
	public function reduce_soldiers ( $data , $user_id )
	{
		$count	=	count ( $data );
		$sql 	= 'UPDATE `{PREFIX}academy` SET ';
		$i		= 0;
		foreach ( $data as $field => $value )
		{
			if ( $count == ++$i )
			{
				$sql	.=	'`' . $field . '` = `' . $field . '` - ' . $value . ' ';
			}
			else
			{
				$sql	.=	'`' . $field . '` = `' . $field . '` - ' . $value . ', ';
			}
		}

		$sql .= 'WHERE academy_user_id = ' . $user_id .';';

		$this->db->query ( $sql );
	}
}

/* End of file attack_model.php */
/* Location: ./application/models/attack_model.php */