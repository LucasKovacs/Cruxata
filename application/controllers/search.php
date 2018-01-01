<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	private $search;
	private $base_url;
	private	$image_path;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
		$this->load->model ( 'search_model' );

		$this->base_url	=	base_url();
		$this->img_path	=	$this->base_url . IMG_FOLDER;
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post()  )
			{
				// SI SE RECIBIO UN REQUEST ENTONCES ACCEDEMOS A GRABAR LOS SOLDADOS
				$this->search	=	$this->do_search();
			}

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
		$parse['search_rows']				=	'';
		$search_rows						= 	array();

		// SI LOS RESULTADOS NO SON NULOS.
		if ( $this->search != NULL )
		{
			$parse['base_url']				=	$this->base_url;
			$parse['img_path']				=	$this->img_path;

			// RECORREMOS
			foreach ( $this->search as $result )
			{
				$search['user_name']		=	$result['user_name'];
				$search['user_position']	=	$this->functions->format_position ( $result['user_kingdom'] ) . $this->lang->line ( 'se_feud' ) . $this->functions->format_number ( $result['user_feud'] );
				
				$search_rows[]				=	$search;
			}
		}

		$parse['search_rows']				=	$search_rows;
		
		$this->template->page ( SEARCH_FOLDER . 'search_view' , $parse );
	}

	// REALIZA LA BÚSQUEDA
	private function do_search ()
	{
		if ( $this->validate_form() )
		{
			switch ( $this->input->post ( 'search_tye' ) )
			{
				case '1':
					// BUSCAR POR USUARIO
					return $this->search_model->search_user ( $this->input->post ( 'to_search' ) );

				break;

				default:

					return NULL;

				break;
			}
		}
	}

	// VALIDA EL FORMULARIO
	private function validate_form()
	{
		$this->load->library ( 'form_validation' );

		$this->form_validation->set_rules ( 'search_tye'	, ''	, 'required|numeric'		);
		$this->form_validation->set_rules ( 'to_search' 	, '' 	, 'required|min_length[3]'	);

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

/* End of file search.php */
/* Location: ./application/controllers/search.php */