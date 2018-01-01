<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// OBTIENE TODA LA INFORMACION DEL USUARIO
	public function get_user_data ( $user_name , $user_password )
	{
		$this->db->select ( 'u.user_id, u.user_email, u.user_password, u.user_level' )
					-> from ( 'users AS u' )
					->where ( 'u.user_email' 	, $user_name		, TRUE )
					->where ( 'u.user_password'	, $user_password	, TRUE );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->num_rows() == 1 )
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file auth_model.php */
/* Location: ./application/models/auth_model.php */