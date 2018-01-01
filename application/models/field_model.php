<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Field_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// RETORNA LOS VALORES NECESARIOS PARA MOSTRAR LA PAGINA DE LA ARMERIA
	public function get_field_data ( $where )
	{
		// SELECT
		$this->db->select ( 'user_id,
							 user_name,
							 user_castle_img,
							 user_kingdom,
							 user_feud' );

		// FROM
		$this->db->from ( '{PREFIX}users' );

		// WHERE
		$this->db->where ( '`user_kingdom`' , $where['kingdom'] );
		$this->db->where ( '`user_feud` BETWEEN ' . $where['start'] . ' AND ' . $where['end'] );

		//ORDER BY
		$this->db->order_by ( '`user_feud` ASC' );

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

	// RETORNA LA POSICION DEL USUARIO
	public function get_user_position ( $user_id )
	{
		$this->db->select ( 'u.user_kingdom, u.user_feud' )
					->from ( 'users AS u' )
					->where ( 'u.user_id' , $user_id );

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

/* End of file field_model.php */
/* Location: ./application/models/field_model.php */