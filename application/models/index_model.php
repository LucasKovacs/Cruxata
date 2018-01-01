<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// TOMA TODOS LOS USUARIOS BANEADOS
	public function load_banned_users()
	{
		$this->db->select ( 'banned.*,ub.user_name AS user_banned,ua.user_name AS user_admin' )->from ( 'banned' );

		$this->db->join ( 'users AS ub' , 'ub.user_id = banned.banned_user_id' );
		$this->db->join ( 'users AS ua' , 'ua.user_id = banned.banned_by' );

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

	// VALIDA QUE EL HASH EXISTA
	public function validate_hash ( $hash )
	{
		// SELECT
		$this->db->select ( 'validation_user_id' );

		// FROM
		$this->db->from ( '{PREFIX}validations' );

		// WHERE
		$this->db->where ( 'validation_hash' , $hash );

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

	// ACTUALIZA EL ESTADO DEL USUARIO
	public function update_user_status ( $user_id )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}users`
										SET `user_status` = '" . ACTIVE_USER . "'
										WHERE `user_id` = '" . $user_id . "'" );

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

	// ACTUALIZA EL ESTADO DEL USUARIO
	public function delete_hash ( $hash , $user_id )
	{
		// WHERE
		$this->db->where ( 'validation_user_id'	, $user_id	);
		$this->db->where ( 'validation_hash'	, $hash 	);

		// DELETE CON RETURN DE RESULTADO
		if ( $this->db->delete ( 'validations' ) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// CHEQUEA SI EL NOMBRE DE USUARIO EXISTE
	public function username_check ( $user_name )
	{
		$this->db->select ( 'user_name' )->from ( '{PREFIX}users' )->where ( 'user_name' , $user_name );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->num_rows() > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// CHEQUEA SI EL EMAIL DEL USUARIO EXISTE
	public function email_check ( $user_email )
	{
		$this->db->select ( 'user_email' )->from ( '{PREFIX}users' )->where ( 'user_email' , $user_email );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->num_rows() > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// CHEQUEA SI LA POSICION ESTA TOMADA
	public function check_position ( $position )
	{
		$this->db->select ( 'user_feud' )->from ( '{PREFIX}users' );

		$this->db->where ( 'user_kingdom'	, $position['kingdom'] );
		$this->db->where ( 'user_feud' 		, $position['feud'] );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->num_rows() > 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// ACTUALIZA EL EMAIL DEL USUARIO
	public function update_user_password ( $user_email , $password )
	{
		// QUERY
		$query = $this->db->query ( "UPDATE `{PREFIX}users`
										SET `user_password` = '" . $password . "'
										WHERE `user_email` = '" . $user_email . "'" );

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

	// INSERTA TODA LA INFORMACION DEL USUARIO
	public function insert_user_data ( $insert )
	{
		// INICIAMOS LAS TRANSACCIONES
		$this->db->trans_start();

		// INSERTAMOS EL USUARIO
		$this->db->insert ( 'users' , $insert );

		// SELECCIONAMOS EL ID DE USUARIO INSERTADO
		$query = $this->select_user_data ( $insert['user_email'] );

		// INSERTAMOS EL RESTO DE LOS REGISTROS PARA ESE USUARIO
		$this->db->insert ( 'academy' 		, array ( 'academy_user_id' 	=> $query[0]['user_id'] ) );
		$this->db->insert ( 'armory' 		, array ( 'armory_user_id' 		=> $query[0]['user_id'] ) );
		$this->db->insert ( 'buildings' 	, array ( 'building_user_id' 	=> $query[0]['user_id'] ) );
		$this->db->insert ( 'resources'		, array ( 'resource_user_id' 	=> $query[0]['user_id'] ) );
		$this->db->insert ( 'statistics'	, array ( 'statistic_user_id' 	=> $query[0]['user_id'] ) );
		$this->db->insert ( 'tutorial'		, array ( 'tutorial_user_id' 	=> $query[0]['user_id'] ) );
		$this->db->insert ( 'workshop'		, array ( 'workshop_user_id' 	=> $query[0]['user_id'] ) );

		// FINALIZAMOS LAS TRANSACCIONES
		$this->db->trans_complete();

		// RETORNAMOS EL VALOR DE ACUERDO A LO OCURRIDO
		if ( $this->db->trans_status() === FALSE )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	// SELECCIONA LA INFORMACION DEL USUARIO
	private function select_user_data ( $user_email )
	{
		$this->db->select ( 'user_id' )->from ( '{PREFIX}users' )->where ( 'user_email' , $user_email );

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

/* End of file index_model.php */
/* Location: ./application/models/index_model.php */