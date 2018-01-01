<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package phpFTM
 * @version $Id$
 * @copyright (c) 2011 phpFTM Group
 * @license http://creativecommons.org/licenses/by-nd/3.0/
 *
 */

class Auth
{
	private $_logged 	= FALSE;
	private $_ci;
	private $_id 		= 0;
	private $_username 	= '';
	private $_password	= '';
	private $_level		= 0;

	//	__construct
	public function __construct()
	{
		$this->_ci = &get_instance();

		if ( $this->login ( $this->_ci->session->userdata ( 'user_email' ) ,
			 				$this->_ci->session->userdata ( 'user_password' ) )
		   )
		{
			$this->_id 			= $this->_ci->session->userdata ( 'user_id' );
			$this->_username	= $this->_ci->session->userdata ( 'user_email' );
		}
	}

	// LOGIN
	public function login ( $username = '' , $password = '' )
	{
		if ( ( $username === '' ) OR ( $password === '' ) )
		{
			return FALSE;
		}
		else
		{
			$this->_ci->load->model ( 'auth_model' );

			$query		= $this->_ci->auth_model->get_user_data ( $username , sha1 ( $password ) );

			// LOGIN OK
			if ( $query )
			{
				// OBTENEMOS LOS DATOS DE LA FILA
				$row	=	$query[0];

				// SETEAMOS LOS DATOS DE LA SESION
				$this->_ci->session->set_userdata ( 'user_id' 		, $row['user_id'] );
				$this->_ci->session->set_userdata ( 'user_email' 	, $username );
				$this->_ci->session->set_userdata ( 'user_password'	, $password );

				// PASAMOS LOS VALORES A LAS VARIABLES
				$this->_id 			= $row['user_id'];
				$this->_level		= $row['user_level'];
				$this->_username	= $username;
				$this->_logged 		= TRUE;

				return TRUE;
			}
			else
			{
				$this->_logged 		= FALSE;
				$this->logout();


				return FALSE;
			}
		}
	}

	// SALIR
	public function logout()
	{
		// DESTRUIMOS LA SESION
		$this->_ci->session->sess_destroy();

		// YA NO ESTAMOS LOGUEADOS
		$this->_logged = FALSE;
	}

	// REVISA LA SESSION
	public function check ()
	{
		if ( !$this->_logged )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	// RETORNA EL ID DEL USUARIO
	public function get_id ()
	{
		return $this->_id;
	}

	// RETORNA EL NIVEL DEL USUARIO
	public function get_level()
	{
		return $this->_level;
	}
}

/* End of file auth.php */
/* Location: ./application/libraries/auth.php */