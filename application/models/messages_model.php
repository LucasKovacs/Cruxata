<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// OBTIENE TODOS LOS MENSAJES
	public function get_messages ( $user_id , $type )
	{
		// SELECT
		$this->db->select ('m.`message_id`,
							u.`user_name` AS `message_sender`,
							m.`message_date`,
							m.`message_type`,
							m.`message_subject`,
							m.`message_text`,
							m.`message_viewed`'
						  );

		// FROM
		$this->db->from	( 'messages AS m' );

		// JOIN
		$this->db->join ( 'users AS u' , 'u.user_id = m.message_sender' , 'LEFT' );

		// WHERE
		$this->db->where  ( 'm.`message_user_id`' , $user_id );

		if ( $type > 0 )
		{
			$this->db->where  ( 'm.`message_type`' , $type );
		}

		// BUILD, EXECUTE QUERY AND RETURN RESULT IN AN ARRAY
		$this->db->order_by ( 'message_date' , 'DESC' );


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

	// OBTIENE EL MENSAJE PARA EL USUARIO
	public function get_message ( $user_id , $message_id , $message_type )
	{
	
		if ( $message_type == 3 or $message_type == 2 )
		{
			// SELECT
			$this->db->select ('u.`user_name` AS `message_sender`,
								m.`message_date`,
								m.`message_type`,
								m.`message_subject`,
								m.`message_text`'
			);
	
			// FROM
			$this->db->from	( 'messages AS m' );
	
			// JOIN
			$this->db->join ( 'users AS u' , 'u.user_id = m.message_sender' , 'LEFT' );
	
			// WHERE
			$this->db->where  ( 'm.`message_user_id`' , $user_id );
			$this->db->where  ( 'm.`message_id`' , $message_id );
			
			$query	= $this->db->get();	
		}
		else
		{
			$query	= $this->db->query ( "SELECT m.*, um.user_name AS receiver, uo.user_name AS sender
											FROM {PREFIX}messages AS m
											INNER JOIN {PREFIX}users AS um ON um.user_id = m.message_user_id
											INNER JOIN {PREFIX}users AS uo ON uo.user_id = m.message_sender
											WHERE (message_user_id = (SELECT message_user_id FROM c4r2u1x3a2t7a_messages WHERE message_id = ".$message_id.") 
											AND message_sender = (SELECT message_sender FROM c4r2u1x3a2t7a_messages WHERE message_id = ".$message_id.")) OR
											(message_user_id = (SELECT message_sender FROM c4r2u1x3a2t7a_messages WHERE message_id = ".$message_id.") 
											AND message_sender = (SELECT message_user_id FROM c4r2u1x3a2t7a_messages WHERE message_id = ".$message_id."))
											ORDER BY message_date DESC" );	
		}
		
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

	// MARCA COMO LEIDOS ALGUNOS MENSAJES
	public function mark_read_messages ( $user_id , $msg_id )
	{
		$this->db->where  ( array ( 'message_user_id' => $user_id , 'message_id' => $msg_id ) );
		$this->db->update ( 'messages' , array ( 'message_viewed' => 1 ) );
	}

	// MARCA COMO LEIDOS TODOS LOS MENSAJES
	public function mark_read_all_messages ( $user_id , $type_id )
	{
		if ( $type_id > 0 )
		{
			$this->db->where  ( array ( 'message_user_id' => $user_id , 'message_id' => $msg_id ) );
		}
		else
		{
			$this->db->where  ( array ( 'message_user_id' => $user_id ) );
		}
		$this->db->update ( 'messages' , array ( 'message_viewed' => 1 ) );
	}

	// BORRA ALGUNOS MENSAJES
	public function delete_some_messages ( $user_id , $msg_id )
	{
		$this->db->delete ( 'messages' , array ( 'message_user_id' => $user_id , 'message_id' => $msg_id ) );
	}

	// BORRA TODOS LOS MENSAJES
	public function delete_all_messages ( $user_id , $type_id )
	{
		if ( $type_id > 0 )
		{
			$delete	= array ( 'message_user_id' => $user_id , 'message_type' => $type_id );
		}
		else
		{
			$delete	= array ( 'message_user_id' => $user_id );
		}


		$this->db->delete ( 'messages' , $delete );
	}

	// REVISA SI EL NOMBRE DE USUARIO EXISTE
	public function check_username ( $user_name )
	{
		// QUERY COMPLETA
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

	// SENVIO EL MENSAJE
	public function send_message ( $data )
	{
		$insert	=	array	(
								'message_user_id'	=> $data['message_user_id'],
								'message_sender'	=> $data['message_sender'],
								'message_date' 		=> $data['message_date'],
								'message_type'		=> $data['message_type'],
								'message_subject'	=> $data['message_subject'],
								'message_text'		=> $data['message_text'],
			  				);

		if ( $this->db->insert ( 'messages' , $insert ) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file messages_model.php */
/* Location: ./application/models/messages_model.php */