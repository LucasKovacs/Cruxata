<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller {

	private $base_url;
	private $img_path;
	private $type;
	private $user_id;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
		$this->load->model ( 'messages_model' );

		// ALGUNOS VALORES POR DEFECTO
		$this->base_url	=	base_url();
		$this->img_path	= 	$this->base_url . IMG_FOLDER;
		$this->type		=	0;
		$this->user_id	=	$this->auth->get_id();
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post ( 'select_type' ) )
			{
				if ( $this->functions->is_a_number ( $this->input->post ( 'select_type' ) ) )
				{
					$this->type	= 	$this->input->post ( 'select_type' );
				}
				else
				{
					$this->type	=	ALL_MESSAGES;
				}
			}

			if ( $this->input->post ( 'messagesactions' ) )
			{
				$this->action_messages();
			}

			$this->show_page();
		}
		else
		{
			redirect ( base_url() );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page()
	{
		$parse['all_messages']	= $this->get_all_messages();
		$parse['base_url']		= $this->base_url;
		$parse['img_path']		= $this->img_path;
		$parse['message_type']	= $this->type;

		// OPCIONES DEL COMBO
//		$parse['options']		= 	array	(
//												ALL_MESSAGES => $this->lang->line ( 'me_all_messages' ),
//												PRIVATE_MESSAGES => $this->lang->line ( 'me_private_message' ),
//												ATTACKS_MADE => $this->lang->line ( 'me_attacks_made' ),
//												ATTACKS_RECEIVED => $this->lang->line ( 'me_attacks_received' ),
//						      				);


		$this->template->page ( MESSAGES_FOLDER . 'messages_view' , $parse );
	}

	// OBTIENE LOS MENSAJES
	private function get_all_messages ()
	{
		$submsgtemp	= 	'';
		$query		= 	$this->messages_model->get_messages ( $this->user_id , $this->type );

		if ( $query != NULL )
		{
			foreach ( $query as $message )
			{
				$message['base_url']		= 	$this->base_url;
				$message['img_path']		= 	$this->img_path;
				$submsgtemp		   .= $this->load->view ( MESSAGES_FOLDER . 'messages_row_view' , $message , TRUE );
			}

			return $submsgtemp;
		}
	}

	// BORRA LOS MENSAJES
	private function action_messages ()
	{
		switch ( $this->input->post ( 'messagesactions' ) )
		{
			// MARCAMOS COMO LEIDOS LOS MARCADOS
			case 'checked_read':

				// RECORREMOS TODOS LOS VALORES DE POST
				foreach ( $this->input->post() as $message => $status )
				{
					// PROCESAMOS LA CADENA
					if ( preg_match ( '/delmes/i' , $message ) && $status == 'on' )
					{
						// EXTRAE EL ID
						$id	=	str_replace ( 'delmes' , '' , $message );

						// CHEQUEAMOS EL ID, UNO NUNCA SABE
						if ( $this->functions->is_a_number ( $id ) )
						{
							// TODO BIEN! BORREMOS TRANQUILOS
							$this->messages_model->mark_read_messages ( $this->user_id , $id );
						}
					}
				}

			break;
			// MARCAMOS COMO LEIDOS LOS NO MARCADOS
			case 'unchecked_read':

				foreach ( $this->input->post() as $message => $status )
				{
					// EXTRAE EL ID
					$id			= str_replace ( 'showmes' , '' , $message );
					$selected 	= $this->input->post ( 'delmes' . $id );

					// PROCESAMOS LA CADENA
					if ( preg_match ( "/showmes/i" , $message ) && $selected == '' )
					{
						// CHEQUEAMOS EL ID, UNO NUNCA SABE
						if ( $this->functions->is_a_number ( $id ) )
						{
							// TODO BIEN! BORREMOS TRANQUILOS
							$this->messages_model->mark_read_messages ( $this->user_id , $id );
						}
					}
				}

			break;
			// TODOS COMO LEIDOS
			case 'all_read':

				if ( $this->functions->is_a_number ( $this->input->post ( 'message_type' ) ) !== FALSE )
				{
					$this->messages_model->mark_read_all_messages ( $this->user_id , $this->input->post ( 'message_type' ) );
				}

			break;
			// SOLO MARCADOS
			case 'checked_delete':

				// RECORREMOS TODOS LOS VALORES DE POST
				foreach ( $this->input->post() as $message => $status )
				{
					// PROCESAMOS LA CADENA
					if ( preg_match ( '/delmes/i' , $message ) && $status == 'on' )
					{
						// EXTRAE EL ID
						$id	=	str_replace ( 'delmes' , '' , $message );

						// CHEQUEAMOS EL ID, UNO NUNCA SABE
						if ( $this->functions->is_a_number ( $id ) )
						{
							// TODO BIEN! BORREMOS TRANQUILOS
							$this->messages_model->delete_some_messages ( $this->user_id , $id );
						}
					}
				}

			break;

			// SOLO NO MARCADOS
			case 'unchecked_delete':

				foreach ( $this->input->post() as $message => $status )
				{
					// EXTRAE EL ID
					$id			= str_replace ( 'showmes' , '' , $message );
					$selected 	= $this->input->post ( 'delmes' . $id );

					// PROCESAMOS LA CADENA
					if ( preg_match ( "/showmes/i" , $message ) && $selected == '' )
					{
						// CHEQUEAMOS EL ID, UNO NUNCA SABE
						if ( $this->functions->is_a_number ( $id ) )
						{
							// TODO BIEN! BORREMOS TRANQUILOS
							$this->messages_model->delete_some_messages ( $this->user_id , $id );
						}
					}
				}

			break;

			// TODOS
			case 'all_delete':

				if ( $this->functions->is_a_number ( $this->input->post ( 'message_type' ) ) !== FALSE )
				{
					$this->messages_model->delete_all_messages ( $this->user_id , $this->input->post ( 'message_type' ) );
				}

			break;

			// POR SEGURIDAD, PARA QUE NO TIREN CUALQUIER VERDURA
			default:

				// NO HACEMOS NADA

			break;
		}
	}

	// BORRA UN MENSAJE
	public function delete_message ( $message_id )
	{
		if ( $this->functions->is_a_number ( $message_id ) )
		{
			// TODO BIEN! BORREMOS TRANQUILOS
			$this->messages_model->delete_some_messages ( $this->user_id , $message_id );
		}
		
		redirect ( base_url() . 'messages' );
	}
	
	// MARCA UN MENSAJE COMO LEIDO
	public function mark_read ( $message_id )
	{
		if ( $this->functions->is_a_number ( $message_id ) )
		{
			// TODO BIEN! BORREMOS TRANQUILOS
			$this->messages_model->mark_read_messages ( $this->user_id , $message_id );
		}
		
		redirect ( base_url() . 'messages' );
	}
	
	// MUESTRA EL MENSAJE
	public function show_message ( $message_id , $message_type )
	{
		if ( $this->functions->is_a_number ( $message_id ) && $this->functions->is_a_number ( $message_type )  )
		{
			$query	=	$this->messages_model->get_message ( $this->user_id , $message_id , $message_type );

			if ( $query )
			{
				// MARCAMOS LOS MENSAJES COMO LEIDOS
				$this->messages_model->mark_read_messages ( $this->user_id , $message_id );
												
				if ( $message_type == 2 or $message_type == 3 )
				{
					die ('<span style="color:#000;">'.$query[0]['message_text'].'</span>');
				}
				else
				{
					if ( !in_array ( $this->user_id , $query[0] ) )
					{
						die ('<span style="color:red;">'.$this->lang->line ( 'me_message_not_found' ).'</span>');
					}
				
					$parse['data']		= $query;
					$parse['user_id']	= $this->user_id;
					$parse['base_url']	= $this->base_url;
					$parse['img_path']	= $this->img_path;
					
					$this->load->view ( MESSAGES_FOLDER . 'messages_message_thread_view' , $parse );
				}	
			}
			else
			{
				die ('<span style="color:red;">'.$this->lang->line ( 'me_message_not_found' ).'</span>');
			}
		}
		else
		{
			die ('<span style="color:red;">'.$this->lang->line ( 'me_message_not_found' ).'</span>');
		}

	}

	// ENVIA UN MENSAJE
	public function private_message ( $user_name = '' )
	{
		// ESTO LO TRAEMOS SIEMPRE, ES NECESARIO SIEMPRE.
		$user_id	=	$this->messages_model->check_username ( $user_name );

		if ( $user_id && ( $user_id[0]['user_id'] != $this->user_id ) )
		{
			if ( $this->input->post( 'submit' ) )
			{
				if ( $this->validations() )
				{
					// DATOS A ENVIAR
					$data['message_user_id']	= $user_id[0]['user_id'];
					$data['message_sender']		= $this->user_id;
					$data['message_date']		= time();
					$data['message_type']		= PRIVATE_MESSAGES;
					$data['message_subject']	= $this->lang->line ( 'me_private_message' );
					$data['message_text']		= $this->input->post ( 'message' );

					// ENVIAR MENSAJE
					$this->messages_model->send_message ( $data );

					// LIMPIAMOS EL CAMPO DE TEXTO
					$parse['user_name']		= $user_name;
					$parse['text']			= '';

					$error_table['msg']		= $this->lang->line ( 'me_sended' );
					$error_table['color']	= 'lime';
					$parse['message']		= $this->load->view ( MESSAGES_FOLDER . 'messages_error_view' , $error_table , TRUE );

				}
				else
				{
					$parse['user_name']		= $user_name;
					$parse['text']			= $this->input->post ( 'message' );

					$error_table['msg']		= $this->lang->line ( 'me_fail' );
					$error_table['color']	= 'red';
					$parse['message']		= $this->load->view ( MESSAGES_FOLDER . 'messages_error_view' , $error_table , TRUE );
				}
			}
			else
			{
				$parse['user_name']			= $user_name;
				$parse['text']				= '';
				$parse['message']			= '';
			}

			$this->template->page ( MESSAGES_FOLDER . 'messages_message_view' , $parse );
		}
		else
		{
			redirect ( base_url() . 'messages' );
		}
	}

	// VALIDACIONES PARA ANTES DE ENVIAR UN MENSAJE
	private function validations()
	{
		// CARGAMOS LA LIBRERIA
		$this->load->library ( 'form_validation' );

		// SETEAMOS LAS REGLAS
		$this->form_validation->set_rules ( 'message' , $this->lang->line ( 'me_message' ) , 'strip_tags|trim|required|max_length[' . MAX_CHARACTERS . ']' );

		if ( $this->form_validation->run() )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file messages.php */
/* Location: ./application/controllers/messages.php */