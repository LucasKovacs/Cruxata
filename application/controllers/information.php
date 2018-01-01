<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Information extends CI_Controller 
{
	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
		$this->load->model ( 'market_model' );

		// TRAEMOS TODOS LOS DATOS
		$this->_market		=	$this->market_model->get_market_data();

		$this->_base_url	=	base_url();
		$this->_img_path	=	$this->_base_url . IMG_FOLDER;
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{		
			// MUESTRA LA PAGINA
			$this->show_page();
		}
		else
		{
			redirect ( $this->_base_url );
		}
	}
}

/* End of file information.php */
/* Location: ./application/controllers/information.php */