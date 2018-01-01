<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	private $base_url;

	// CONSTRUCTOR
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
		$this->load->model ( 'index_model' );

		$this->base_url	=	base_url();
	}

	// HOME
	public function index()
	{
		$data['error_msg']	= '';
		
		if ( $this->input->post() )
		{
			if ( $this->auth->login ( $this->input->post ( 'email' ) , $this->input->post ( 'password' ) ) )
			{
				redirect ( $this->base_url . 'empire' );
				exit;
			}
			else
			{
				$data['error_msg']	= $this->lang->line ( 'lo_error_login' );
			}
		}

		$this->template->public_page ( INDEX_FOLDER . 'index_home_view' , $data );
	}

	// REGISTRARSE
	public function register()
	{
		$data['error_msg']	= '';
		
		if ( $this->input->post() )
		{
			if ( $this->validate_register() )
			{
				// CREAMOS TODA LA INFORMACION DEL USUARIO
				$position	=	$this->create_position ( $this->return_kingdom  ( $this->input->post ( 'kingdom' ) ) );
				$castle		=	$this->create_castle();

				$insert['user_name']		=	$this->input->post ( 'username' );
				$insert['user_password']	=	sha1 ( $this->input->post ( 'password' ) );
				$insert['user_email']		=	$this->input->post ( 'email' );
				$insert['user_level']		=	LEVEL_USER;
				$insert['user_onlinetime']	=	time();
				$insert['user_updatetime']	=	time();
				$insert['user_register_ip']	=	$_SERVER['REMOTE_ADDR'];
				$insert['user_last_ip']		=	$_SERVER['REMOTE_ADDR'];
				$insert['user_castle_img']	=	$castle;
				$insert['user_kingdom']		=	$position['kingdom'];
				$insert['user_feud']		=	$position['feud'];

				// INSERTAMOS TODA LA INFORMACION DEL USUARIO
				if ( $this->index_model->insert_user_data ( $insert ) )
				{
					// Register in the forum
					$crux_mail		= $this->input->post ( 'email' );
					$crux_username	= $this->input->post ( 'username' );
					$crux_password	= $this->input->post ( 'password' );
					
					// process the registration
					//$this->register_in_vb ( $crux_username , $crux_password , $crux_mail );
					
					// SI TODO SE CREO BIEN LO LOGUEAMOS
					if ( $this->auth->login ( $this->input->post ( 'email' ) , $this->input->post ( 'password' ) ) )
					{
						// LO REDIRIGIMOS
						redirect ( $this->base_url . 'empire' );
					}
				}
				else
				{
					$data['error_msg']	= $this->lang->line ( 're_fatal_error' );
				}
			}
		}

		$this->template->public_page ( INDEX_FOLDER . 'index_register_view' , $data );
	}

	// RECUPERAR CLAVE
	public function lost_password()
	{
		$data['error_msg']	= '';
		
		if ( $this->input->post() )
		{
			if ( $this->validate_lost_password() )
			{
				$this->load->library ( 'email' );

				$send_to		=	$this->input->post ( 'email' );
				$new_password	=	$this->build_password();

				$this->email->from		( 'info@cruxata.com' , 'Password Cruxata' );
				$this->email->to 		( $send_to );
				$this->email->subject	( $this->lang->line ( 'lp_retrieve_password' ) );

				// AGREGAMOS LA INFORMACION A LA PLANTILLA
				$email_form 	= 	$this->lang->line ( 'lp_mail_text' ) . $new_password;

				// ENVIAMOS EL EMAIL
				$this->email->message	( $email_form );

				// ENVIAMOS EL EMAIL
				if ( $this->email->send() )
				{
					$this->index_model->update_user_password ( $send_to , sha1 ( $new_password ) );

					$data['error_msg']	= $this->lang->line ( 'lp_email_sended' );
				}
				else
				{
					$data['error_msg']	= $this->lang->line ( 'lp_email_not_sended' );
				}

			}
			else
			{
				$data['error_msg']		= $this->lang->line ( 'lp_email_error' );
			}
		}

		$this->template->public_page ( INDEX_FOLDER . 'index_lostpassword_view' , $data );
	}

	// CRÉDITOS
	public function credits()
	{
		$this->template->public_page ( INDEX_FOLDER . 'index_credits_view' , '' , FALSE , FALSE );
	}

	// REGLAS
	public function rules()
	{
		$this->template->public_page ( INDEX_FOLDER . 'index_rules_view' , '' , FALSE , FALSE  );
	}

	// TERMINOS Y CONDICIONES DE USO
	public function terms_and_conditions()
	{
		$this->template->public_page ( INDEX_FOLDER . 'index_termsandconditions_view' , '' , FALSE , FALSE  );
	}

	// BANEADOS
	public function banned()
	{
		$banned_users			=	$this->index_model->load_banned_users();
		$parse['banned_rows']	=	'';

		if ( $banned_users != NULL )
		{
			foreach ( $banned_users as $banned_info )
			{
				$banned['banned_user']	= 	$banned_info['user_banned'];
				$banned['reason']		=	$banned_info['banned_reason'];
				$banned['since']		=	$this->functions->format_time ( $banned_info['banned_since'] );
				$banned['until']		=	$this->functions->format_time ( $banned_info['banned_until'] );
				$banned['left']			=	$this->functions->format_time ( $banned_info['banned_until'] - $banned_info['banned_since'] );
				$banned['banned_by']	=	$banned_info['user_admin'];

				$parse['banned_rows']  .= $this->load->view ( INDEX_FOLDER . 'index_banned_row_view' , $banned , TRUE );
			}
		}

		$this->template->public_page ( INDEX_FOLDER . 'index_banned_view' , $parse  ,  FALSE , FALSE );

	}

	// REDIRIGE AL FORO
	public function community()
	{
		redirect ( FORUM_URL );
	}
	
	public function version()
	{
		// CARGAMOS EL LENGUAJE
		$this->lang->load ( 'changelog' , 'spanish' );
				
		$parse['ve_versions']	= $this->lang->line ( 've_versions' );
		
		$this->template->public_page ( INDEX_FOLDER . 'index_version_view' , $parse  ,  FALSE , FALSE );
	}

	// SALE DEL JUEGO SI ESTA LOGUEADO
	public function logout()
	{
		if ( $this->auth->check() )
		{
			$this->auth->logout();
		}
		
		redirect ( $this->base_url );
	}

	// VALIDA LA CUENTA
	public function validate_account ( $hash )
	{
		$validation_hash	=	$this->index_model->validate_hash ( $hash );

		if ( $validation_hash[0]['validation_user_id'] )
		{
			$this->index_model->update_user_status ( $validation_hash[0]['validation_user_id'] );
			$this->index_model->delete_hash ( $hash , $validation_hash[0]['validation_user_id']);

			redirect ( $this->base_url . 'empire' );
		}
		else
		{
			redirect ( $this->base_url );
		}
	}

	// VALIDA LOS DATOS ANTES DE REGISTRAR AL USUARIO
	private function validate_register()
	{
		$this->load->library ( 'form_validation' );

		$this->form_validation->set_rules ( 'username' 	, $this->lang->line ( 're_username' )				, 'required|alpha_numeric|min_length[3]|max_length[32]|callback_username_check|username_pass_check'	);		
		$this->form_validation->set_rules ( 'password' 	, $this->lang->line ( 're_password' ) 				, 'required|min_length[4]' 											);
		$this->form_validation->set_rules ( 'email' 	, $this->lang->line ( 're_email' )					, 'required|valid_email|callback_email_check'						);
		$this->form_validation->set_rules ( 'kingdom' 	, $this->lang->line ( 're_select_kingdom' )			, 'required|numeric' 												);
		$this->form_validation->set_rules ( 'terms' 	, $this->lang->line ( 're_terms_and_conditions' )	, 'required' 														);

		if ( $this->username_pass_check() )
		{
			if ( $this->form_validation->run() )
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	// VALIDA LOS DATOS ANTES DE ENVIAR EL EMAIL DE CONFIRMACION AL USUARIO
	private function validate_lost_password()
	{
		$this->load->library ( 'form_validation' );

		$this->form_validation->set_rules ( 'email' , $this->lang->line ( 'lp_email' ) , 'required|valid_email|callback_email_check_password' );

		if ( $this->form_validation->run() )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// Checks the name with the password
	public function username_pass_check()
	{
		if ( $this->input->post ( 'username' ) == $this->input->post ( 'password' ) )
		{
			$this->form_validation->set_message ( 'username_check' , $this->lang->line ( 're_username_pass_check' ) );
			return FALSE;
		}

		else
		{
			return TRUE;
		}
	}

	// CHEQUEA EL NOMBRE DE USUARIO
	public function username_check ( $user_name )
	{
		if ( $this->index_model->username_check ( $user_name ) )
		{
			$this->form_validation->set_message ( 'username_check' , $this->lang->line ( 're_username_already_exists' ) );
			return FALSE;
		}

		else
		{
			return TRUE;
		}
	}

	// CHEQUEA EL EMAIL DEL USUARIO
	public function email_check ( $user_email )
	{
		if ( $this->index_model->email_check ( $user_email ) )
		{
			$this->form_validation->set_message ( 'email_check' , $this->lang->line ( 're_email_already_exists' ) );
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	// CHEQUEA EL EMAIL DEL USUARIO, PERO BUSCA QUE EXISTA
	public function email_check_password ( $user_email )
	{
		if ( $this->index_model->email_check ( $user_email ) )
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message ( 'email_check_password' , $this->lang->line ( 'lp_email_not_exists' ) );
			return FALSE;
		}
	}

	// RETORNA LOS REINOS Y LA CANTIDAD DE FEUDOS
	private function return_kingdom ( $kingdom )
	{
		switch ( $kingdom )
		{
			case'1':

				$return['kingdom']	=	'franks';
				$return['feuds']	=	FRANK;

			break;

			case'2':

				$return['kingdom']	=	'germanic';
				$return['feuds']	=	GERMANIC;

			break;

			case'3':

				$return['kingdom']	=	'hungary';
				$return['feuds']	=	HUNGARY;

			break;

			case'4':

				$return['kingdom']	=	'english';
				$return['feuds']	=	ENGLAND;

			break;

			default:

				$return['kingdom']	=	'franks';
				$return['feuds']	=	FRANK;

			break;
		}
		return $return;
	}

	// CREA LA POSICION
	private function create_position ( $position_data )
	{
		// SETEAMOS ALGUNOS VALORES
		$new_position	= 	FALSE;
		$feud			= 	'';
		$taken			=	array();

		// RECORREMOS HASTA QUE SE ENCUENTRE ALGO
		while ( !$new_position )
		{
			for ( $feuds = 1; $feuds <= $position_data['feuds']; $feuds++)
			{
				$position_data['feud']	= mt_rand ( $feuds , $position_data['feuds'] );

				if ( !in_array ( $position_data['feud'] , $taken ) )
				{
					$query 			= $this->index_model->check_position ( $position_data );
				}
				else
				{
					$query			= FALSE;
				}

				if ( $query )
				{
					$taken[]		= $position_data['feud'];
					$new_position 	= FALSE;
				}
				else
				{
					$new_position 	= TRUE;
					$feud			= $position_data['feud'];
					break;
				}
			}
		}

		if ( $feud == '' )
		{
			return FALSE;
		}
		else
		{
			return $position_data;
		}
	}

	// "CREA" EL CASTILLO PARA EL USUARIO
	private function create_castle()
	{
		$castle 		= array ( 'castle' );
		$castle_kind 	= array ( '01' , '02' , '03' , '04' , '05' , '06' , '07' , '08' , '09' , '10' ,'11' );

		$image       	= $castle[ mt_rand ( 0 , count ( $castle ) -1 ) ];
		$image	       .= $castle_kind[ mt_rand ( 0 , count ( $castle_kind ) - 1 ) ];

		return $image;
	}

	// CREA LA NUEVA CLAVE PARA EL USUARIO
	private function build_password()
	{
		$characters		=	'abcdefghijqlmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#.-_:';
		$count			=	strlen ( $characters );
		$new_password	=	'';
		$long			=	10;

		for ( $i = 1 ; $i <= $long ; $i++ )
		{
			$rand			=	mt_rand ( 1 , $count );
			$new_password  .=	substr ( $characters , $rand , 1 );
		}

		return	$new_password;
	}
	
	// Create vBulletin user
	private function register_in_vb($username, $password, $email) 
	{ 
	    define('VB_AREA', 'External'); 
	    define('SKIP_SESSIONCREATE', 0); 
	    define('SKIP_USERINFO', 1); 
	    define('CWD', '/home/xtremega/public_html/forum' ); 
	    require_once(CWD . '/includes/init.php'); 
	    require_once(CWD . '/includes/functions_misc.php'); 
	
	    $registry	= $vbulletin; 
	    
	    unset ( $vbulletin );
	    
	    $vbDb 		= $registry->db;  
	    
	    //declare as global vbulletin's registry and db objects  
	    global $vbulletin, $db;  
	    
	    $vbulletin 	= $registry;  
	    
	    //backup the original $db object (new!!)  
	    $backupdb 	= $db;  
	    $db 		= $vbDb;  
	
	    $newuser = &datamanager_init ( 'User' , $vbulletin , ERRTYPE_ARRAY ); 
	    $newuser->set ( 'username' , $username ); 
	    $newuser->set ( 'email' , $email ); 
	    $newuser->set ( 'password' , $password ); 
	    $newuser->set ( 'usergroupid' , 2 ); 
	     
	    $newuser->pre_save(); 
	     
	    if ( empty ( $newuser->errors ) )
	    { 
	        $db	= $backupdb; 
	        $newuser->save(); 
	        return TRUE; 
	    }
	    else
	    { 
	        $db	= $backupdb; 
	        return $newuser->errors; 
	    } 
	         
	}	
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */