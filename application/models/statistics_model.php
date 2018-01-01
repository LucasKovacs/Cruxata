<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// TRAE TODOS LOS JUGADORES
	public function get_all_players ()
	{
		// SELECT
		$this->db->select ( 'u.user_id, u.user_name, s.statistic_points' );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( 'statistics AS s' , 'u.user_id = s.statistic_user_id' );

		// WHERE
//		$this->db->where ( 'u.`user_status`' , ACTIVE_USER , TRUE );

		// ORDER BY
		$this->db->order_by ( 's.statistic_points  DESC, u.user_name ASC' );

		$query = $this->db->get ();

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

/* End of file statistics_model.php */
/* Location: ./application/models/statistics_model.php */