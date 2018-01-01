<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Armies_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LA ARMERIA
	public function get_armies_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'armies.*,
							 users.user_name,
							 users.user_castle_img' );

		// FROM
		$this->db->from ( '{PREFIX}armies' );

		// JOIN
		$this->db->join ( 'users' , 'users.user_id = armies.army_receptor_id' );

		// WHERE
		$this->db->where ( 'armies.`army_user_id`' , $user_id );

		//ORDER BY
		$this->db->order_by ( 'armies.army_arrival ASC' );
		$this->db->order_by ( 'armies.army_return ASC' );

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
	
	public function return_army ( $army_data )
	{
		$time	= $army_data['army_return'] - $army_data['army_arrival'];
		
		$time_to_reach_target		= $army_data['army_arrival'] - time();
		$time_for_return			= $army_data['army_return'] - time() - $time_to_reach_target - ATTACK_DURATION;	
		$difference					= $time_for_return - $time_to_reach_target;

		$query 	= $this->db->query ( "UPDATE `{PREFIX}armies` SET
										army_arrival = '0',
										army_return = '" . ( time() + $difference ) . "',
										army_current = '0'
										WHERE army_id = '" . $army_data['army_id'] . "'" );
	}
}

/* End of file armies_model.php */
/* Location: ./application/models/armies_model.php */