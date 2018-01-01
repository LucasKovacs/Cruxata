<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Options_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LA INFORMACION DEL USUARIO
	public function get_user_data ( $user_id )
	{
		// SELECT
		$this->db->select ( 'u.user_name,
							 u.user_email,
							 u.user_password,
							 u.user_status' );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// WHERE
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

	// ACTUALIZA LA INFORMACIÓN DEL USUARIO
	public function update_user_data ( $user_data )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}users`
										SET
											`user_name` = '" . $user_data['username'] . "',
											`user_password` = " . ( ( $user_data['password'] == '' ) ? '`user_password`' : '\'' . $user_data['password'] . '\'' ) . ",
											`user_status` = '" . $user_data['status'] . "'
										WHERE `user_id` = '" . $user_data['user_id'] . "'" );

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

/* End of file options_model.php */
/* Location: ./application/models/options_model.php */