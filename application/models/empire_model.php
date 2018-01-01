<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empire_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DEL IMPERIO
	public function get_empire_data ( $user_id )
	{
		// SELECT
		$this->db->select ( '(SELECT COUNT(army_id) AS current_attacks FROM {PREFIX}armies WHERE army_user_id = u.user_id) AS current_attacks,
							 a.armory_current_build,
							 r.army_user_id,
							 r.army_arrival,
							 r.army_current,
							 r.army_troops,
  							 c.academy_current_build,
  							 b.building_current_build,
  							 b.building_watchtower,
							 u.user_name,
							 u.user_castle_img,
							 u.user_kingdom,
							 u.user_feud,
							 s.statistic_points,
							 w.workshop_current_build' );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( 'armies AS r' 	, 'r.army_receptor_id = u.user_id' , 'left' );
		$this->db->join ( 'armory AS a' 	, 'a.armory_user_id = u.user_id' );
		$this->db->join ( 'academy AS c' 	, 'c.academy_user_id = u.user_id' );
		$this->db->join ( 'buildings AS b' 	, 'b.building_user_id = u.user_id' );
		$this->db->join ( 'statistics AS s' , 's.statistic_user_id = u.user_id' );
		$this->db->join ( 'workshop AS w' 	, 'w.workshop_user_id = u.user_id' );

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
}

/* End of file empire_model.php */
/* Location: ./application/models/empire_model.php */