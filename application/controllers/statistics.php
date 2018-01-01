<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends CI_Controller {

	private $statistics;
	private $base_url;
	private $img_path;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS EL MODEL
		$this->load->model ( 'statistics_model' );

		// TRAEMOS TODOS LOS DATOS
		$this->statistics	=	$this->statistics_model->get_all_players();

		$this->base_url		= 	base_url();
		$this->img_path		=	$this->base_url . IMG_FOLDER;
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index()
	{
		if ( $this->auth->check() )
		{
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
		$statistics						=	$this->statistics;
		$parse['base_url']				= 	$this->base_url;
		$parse['img_path']				=	$this->img_path;
		$parse['statistics_rows']		=	'';
		$i								= 	0;

		if ( $statistics != NULL )
		{
			foreach ( $statistics as $statistic )
			{
				if ( $statistic['user_id'] == $this->auth->get_id() )
				{
					$row['row_color']		=	' style="background-color:yellowgreen;color:#000;"';
					$row['user_message']	=	'';
				}
				else
				{
					$row['row_color']		=	' style="background-color:#000;"';
					$row['user_message']	=	' <a href="' . $this->base_url . 'private-message/' . $statistic['user_name'] . '"><img src="' . $this->img_path . 'icons/message.gif" border="0"/></a>';
				}

				$row['user_position']		= 	++$i;
				$row['user_name']			=	$statistic['user_name'];
				$row['user_points']			=	$this->functions->format_number ( $statistic['statistic_points'] );

				$statistics_rows[]			=	$row;
			}
		}

		$parse['statistics_rows']			= 	$statistics_rows;

		$this->template->page ( STATISTICS_FOLDER . 'statistics_view' , $parse );
	}
}

/* End of file statistics.php */
/* Location: ./application/controllers/statistics.php */