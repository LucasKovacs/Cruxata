<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friends_model extends CI_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get the user name
	 *
	 * @param (int) $user_id
	 * @return (mixed)
	 */
	public function get_friends_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'u.user_name' );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

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