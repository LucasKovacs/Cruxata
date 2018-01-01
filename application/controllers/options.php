<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Options extends CI_Controller {

	private $base_url;
	private $img_path;
	private	$options;
	private $user_id;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGA EL MODELO
		$this->load->model ( 'options_model' );

		$this->base_url		= 	base_url();
		$this->img_path		=	$this->base_url . IMG_FOLDER;
		$this->user_id		=	$this->auth->get_id();
		$this->options		=	$this->options_model->get_user_data ( $this->user_id );
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post ( 'save' )  )
			{
				// SI SE RECIBIO UN REQUEST ENTONCES ACCEDEMOS A GRABAR LOS DATOS
				$this->update_user_data();
			}
			elseif ( $this->input->post ( 'send' ) )
			{
				$this->validation_email();
			}
			else
			{
				// MUESTRA LA PAGINA
				$this->show_page();
			}
		}
		else
		{
			redirect ( base_url() );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page()
	{
		$parse['base_url']		= 	$this->base_url;
		$parse['img_path']		=	$this->img_path;
		$parse['email']			=	$this->options[0]['user_email'];

		// LA CUENTA REQUIERE VALIDACIÓN
		if ( $this->options[0]['user_status'] == INACTIVE_USER )
		{
			$this->template->page ( OPTIONS_FOLDER . 'options_validate_view' , $parse );
		}
		else // LA CUENTA YA ESTA ACTIVA
		{
			$parse['username']	=	$this->options[0]['user_name'];
			$parse['checked']	=	( ( $this->options[0]['user_status'] == DELETED_USER ) ? TRUE : FALSE );

			$this->template->page ( OPTIONS_FOLDER . 'options_view' , $parse );
		}
	}

	// ACTUALIZA LA INFORMACION DEL USUARIO
	private function update_user_data()
	{
		$error_msg				=	'';
		$error					=	0;
		$username				=	$this->input->post ( 'username' );
		$old_password			=	$this->input->post ( 'old_password' );
		$first_new_password		=	$this->input->post ( 'first_new_password' );
		$second_new_password	=	$this->input->post ( 'second_new_password' );
		$delete_account			=	$this->input->post ( 'delete_account' );

		// VALIDACIONES PARA EL NOMBRE DE USUARIO
		if ( $username == '' )
		{
			$data['username']	=	$this->options[0]['user_name'];
		}
		else
		{
			$data['username']	=	$username;
		}

		// VALIDACIONES PARA LA CLAVE
		if ( ( $old_password == '' ) && ( $first_new_password == '' ) && ( $second_new_password == '' ) )
		{
			$data['password']	=	'';
		}
		else
		{
			if ( sha1 ( $old_password ) == $this->options[0]['user_password'] )
			{
				if ( $first_new_password == $second_new_password )
				{
					$data['password']	=	sha1 ( $first_new_password );
				}
				else
				{
					$data['password']	=	'';
					$error++;
					$error_msg 			.=	$this->lang->line ( 'op_password_dont_match' );
				}
			}
			else
			{
				$data['password']	=	'';
				$error++;
				$error_msg 			.=	$this->lang->line ( 'op_old_passsword_dont_match' );
			}
		}

		// VALIDACION PARA EL MODO DE BORRAR CUENTA
		if ( $this->input->post ( 'delete_account' ) )
		{
			$data['status']	= DELETED_USER;
		}
		else

		{
			$data['status']	= ACTIVE_USER;
		}

		// CHEQUEAMOS LOS ERRORES Y REALIZAMOS LAS ULTIMAS ACCIONES
		if ( $error == 0 )
		{
			$data['user_id']		= $this->user_id;

			$this->options_model->update_user_data ( $data );

			$this->template->message_box ( $this->lang->line ( 'op_userdata_updated' ) , '2' , 'options' );
		}
		else
		{
			$this->template->message_box ( $error_msg , '2' , 'options' );
		}
	}

	// VERIFICA LOS DATOS INGRESADOS Y SI SON CORRECTOS ENVIA EL EMAIL DE VALIDACION
	private function validation_email()
	{
		$this->load->library ( 'form_validation' );

		$this->form_validation->set_rules ( 'validate_password'	, $this->lang->line ( 'op_validation_password' ) , 'required'			 );
		$this->form_validation->set_rules ( 'validate_email'	, $this->lang->line ( 'op_validation_email' )	 , 'required|valid_email' );

		if ( $this->form_validation->run() )
		{
			$data['password']	= sha1 ( $this->input->post ( 'validate_password' ) );
			$data['email']		= $this->input->post ( 'validate_email' );
			$data['user_id']	= $this->user_id;

			if ( ( $data['password'] == $this->options[0]['user_password'] ) && ( $data['email'] == $this->options[0]['user_email'] ) )
			{
				$this->send_email();
			}
			else
			{
				$this->template->message_box ( $this->lang->line ( 'op_invalid_data' ) , '2' , 'options' );
			}
		}
		else
		{
			$this->template->message_box ( validation_errors() , '2' , 'options' );
		}
	}

	// ENVIA EL EMAIL
	private function send_email()
	{
		$this->load->library ( 'email' );

		$this->email->from		( 'info@cruxata.com' , 'Info Cruxata' );
		$this->email->to 		( $this->options[0]['user_email'] );
		$this->email->subject	( $this->lang->line ( 'op_account_validation' ) );

		$email_data['email']		= $this->options[0]['user_email'];

		// PREPARAMOS LA INFORMACION NECESARIA PARA ARMAR EL HASH
		$hash_data['user_email']	= $email_data['email'];
		$hash_data['user_id']		= $this->user_id;
		$hash_data['user_password']	= $this->options[0]['user_password'];

		// ARMAMOS EL HASH, SUPUESTAMENTE DEBERIA SER ÚNICO
		$hash						= $this->functions->generate_hash ( $hash_data );

		// PASAMOS EL HASH AL LINK DE VALIDACION
		$email_data['val_link']		= $this->base_url . 'validate-account/' . $hash;

		// AGREGAMOS LA INFORMACION A LA PLANTILLA
		$email_form 				= $this->load->view ( OPTIONS_FOLDER . 'options_mail_view' , $email_data , TRUE );

		// ENVIAMOS EL EMAIL
		$this->email->message	( $email_form );

		// ENVIAMOS EL EMAIL
		if ( $this->email->send() )
		{
			$this->template->message_box ( $this->lang->line ( 'op_email_sended' ) , '2' , 'options' );
		}
		else
		{
			$this->template->message_box ( $this->lang->line ( 'op_email_not_sended' ) , '2' , 'options' );
		}
	}
}

/* End of file options.php */
/* Location: ./application/controllers/options.php */