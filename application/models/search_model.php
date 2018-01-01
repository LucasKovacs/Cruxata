<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// BUSCAR POR USUARIO
	public function search_user ( $value )
	{
		$this->db->select ( 'user_name, user_kingdom, user_feud' )
					->from ( 'users' )
					->like ( 'user_name' , $value )
					->order_by ( 'user_name ASC' );

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

/* End of file search_model.php */
/* Location: ./application/models/search_model.php */