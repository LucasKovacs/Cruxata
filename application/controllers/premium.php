<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Premium extends CI_Controller {

	private $base_url;
	private $img_path;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
//		$this->load->model ( 'armies_model' );

		// TRAEMOS TODOS LOS DATOS
//		$this->armies	=	$this->armies_model->get_armies_data ( $this->auth->get_id() );

		$this->base_url	=	base_url();
		$this->img_path	=	$this->base_url . IMG_FOLDER;
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
			redirect ( base_url() );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page ()
	{
		$this->template->page ( PREMIUM_FOLDER . 'premium_view' );
	}
}

/* End of file premium.php */
/* Location: ./application/controllers/premium.php */