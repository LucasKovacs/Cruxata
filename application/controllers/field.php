<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Field extends CI_Controller {

	private $field;
	private $kingdom;
	private	$feud;
	private $current_page;
	private $user_id;
	private $base_url;
	private $img_path;

	// __construct
	public function __construct()
	{
		parent::__construct();

		// CARGAMOS LAS LIBRERIAS
		$this->load->library ( 'field_library' );

		// CARGAMOS EL MODEL
		$this->load->model ( 'field_model' );

		$this->user_id	=	$this->auth->get_id();
		$this->base_url	=	base_url();
		$this->img_path	=	$this->base_url . IMG_FOLDER;
	}

	// ADMINISTRA SI SE MOSTRARA LA PAGINA Y QUE MOSTRARA
	public function index ( $user_name = '' )
	{
		if ( $this->auth->check() )
		{
			if ( $this->input->post()  )
			{
				if ( ( $this->input->post ( 'kingdom' ) == 'franks'   ) or
					 ( $this->input->post ( 'kingdom' ) == 'germanic' ) or
					 ( $this->input->post ( 'kingdom' ) == 'hungary'  ) or
					 ( $this->input->post ( 'kingdom' ) == 'english'  ) )
				{

					$this->current_page			=	'';

					if ( $this->input->post ( 'page' ) != $this->input->post ( 'h_page' ) )
					{
						$this->current_page		=	$this->input->post ( 'page' );
					}
					else
					{
						if ( $this->input->post ( 'left' ) )
						{
							$this->current_page	= 	$this->input->post ( 'page' ) - 1;
						}

						if ( $this->input->post ( 'right' ) )
						{
							$this->current_page	= 	$this->input->post ( 'page' ) + 1;
						}
					}

					if ( $this->current_page == '' )
					{
						$this->current_page =	$this->input->post ( 'page' );
					}

					$this->kingdom			=	$this->input->post ( 'kingdom' );

					if ( $this->functions->is_a_number ( $this->current_page ) )
					{
						$page_count			=	$this->field_library->return_page_count ( $this->kingdom );

						if ( ( $this->current_page >= 1 ) && ( $this->current_page <= $page_count ) )
						{
							$where			=	$this->field_library->return_between_page ( $this->current_page );
						}
						else
						{
							$where['start']	=	$page_count - ( PAGE_QUANTITY - 1 );
							$where['end']	=	$page_count;
						}

					}
					else
					{
						$where['start']		=	 1;
						$where['end']		=	PAGE_QUANTITY;
					}

					$where['kingdom']		=	$this->kingdom;

					$this->field			=	$this->field_model->get_field_data ( $where );
				}
				else
				{
					redirect ( $this->base_url . 'field' );
				}
			}
			else
			{
				$user_id				=	$this->field_model->check_username ( $user_name );

				if ( $user_id )
				{
					// PASAMOS LA POSICION DEL USUARIO ACTUAL
					$position			=	$this->field_model->get_user_position ( $user_id[0]['user_id'] );

					// SETEAMOS EL REINO Y EL FEUDO POR SEPARADO, PUEDEN SERVIR MAS ADELANTE.
					$this->kingdom		=	$position[0]['user_kingdom'];
					$this->feud			=	$position[0]['user_feud'];

					$where				=	$this->field_library->return_between_feud ( $this->field_library->return_page_count ( $this->kingdom ) , $this->feud );
					$where['kingdom']	=	$this->kingdom;

					// PASAMOS LA INFORMACIÓN TRAIDO DESDE LA CONSULTA.
					$this->field		=	$this->field_model->get_field_data ( $where );
				}
				else
				{
					redirect ( $this->base_url . 'empire' );
				}
			}

			// MUESTRA LA PAGINA
			$this->show_page();
		}
		else
		{
			redirect ( $this->base_url );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page ()
	{
		$field_data				=	$this->field;
		$parse['page_count']	=	$this->field_library->return_page_count ( $this->kingdom );
		$parse['selected']		= 	$this->kingdom;
		$parse['current_page']	= 	$this->field_library->return_current_page ( $parse['page_count'] , $this->current_page , $this->feud );
		$page					= 	( ( $this->feud === NULL ) ? $this->field_library->return_between_page ( $parse['current_page'] ) : $this->field_library->return_between_feud ( $parse['page_count'] , $this->feud ) );
		$counter				=	0;
		$field_columns			=	'';
		$field_rows				=	'';
		$parse['base_url']		=	$this->base_url;
		$parse['img_path']		=	$this->img_path;

		if ( $field_data != NULL )
		{
			// RECORREMOS LOS DATOS
			foreach ( $field_data as $key => $user_data )
			{
				// ESTE PROCESO RECORRE TODAS LAS FILAS Y ASIGNA LOS DATOS A LA COLUMNA CORRESPONDIENTE
				for ( $i = ( $page['start'] ) ; $i <= $page['end'] ; $i++ )
				{
					if ( $i == $user_data['user_feud'] )
					{
						$parse['castle']	= '<img style="border:1px solid #000" src="' . $parse['img_path'] . 'castles/' . $user_data['user_castle_img'] . '.gif" border="0" width="50px" height="50px" title="' . $this->lang->line ( 'fi_who_castle' ) . $user_data['user_name'] . '"/>';
						$parse['title']		= '(<a title="' . $this->lang->line ( 'fi_feud' ) . '">' . $user_data['user_feud'] . '</a>) ' . $user_data['user_name'];

						if ( $this->user_id != $user_data['user_id'] )
						{
							$parse['actions']	= '<a href="' . $parse['base_url'] . 'missions/' . $user_data['user_name'] . '" title="' . $this->lang->line ( 'fi_mission_to' ) . $user_data['user_name'] . '"><img src="' . $parse['img_path'] . 'field/field_attack.png" border="0" width="16px" height="16px"/></a>';
							$parse['actions']  .= ' <a href="' . $parse['base_url'] . 'private-message/' . $user_data['user_name'] . '" title="' . $this->lang->line ( 'fi_send_message' ) . $user_data['user_name'] . '"><img src="' . $parse['img_path'] . 'icons/message.gif" border="0" width="16px" height="16px"/></a>';
						}
						else
						{
							$parse['actions']	= ' - ';
						}

						$field_columns[]	=	$parse;

						$counter++;

						if ( ( $counter % 4 ) == 0 )
						{
							$field_rows[]				=	$field_columns;
							$field_columns				=	'';
						}

						$page['start']++;
						break;
					}
					else
					{
						$parse['title']		=	'';
						$parse['castle']	=	'';
						$parse['conf']		=	'';
						$parse['actions']	=	'';
						$field_columns[]	=	$parse;

						$counter++;

						if ( ( $counter % 4 ) == 0 )
						{
							$field_rows[]				=	$field_columns;
							$field_columns				=	'';
						}

						$page['start']++;
					}
				}
			}
		}

		// SI EL CAMPO NO ESTA COMPLETO LO COMPLETAMOS
		for ( $i = $page['start']; $i <= $page['end']; $i++ )
		{
			$parse['title']		=	'';
			$parse['castle']	=	'';
			$parse['conf']		=	'';
			$parse['actions']	=	'';
			$field_columns[]	=	$parse;

			$counter++;

			if ( ( $counter % 4 ) == 0 )
			{
				$field_rows[]				=	$field_columns;
				$field_columns				=	'';
			}
		}

		$parse['field_rows']	=	$field_rows;
		$this->template->page ( FIELD_FOLDER . 'field_view' , $parse );
	}
}

/* End of file field.php */
/* Location: ./application/controllers/field.php */