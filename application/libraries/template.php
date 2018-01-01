<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template
{
	private $ci;
	private $data;
	private $base_url;
	private $template;

	// __construct
	public function __construct()
	{
		// INSTANCIA DE CODEIGNITER
		$this->ci 				= 	&get_instance();

		// CARGAMOS LA LIBRARIA
		$this->ci->load->library ( 'update_armies' );
		$this->ci->load->library ( 'update_library' );

		// CARGAMOS EL MODEL
		$this->ci->load->model ( 'template_model' );

		// VALORES POR DEFECTO
		$this->base_url			=	base_url();
		$this->user_id			=	$this->ci->auth->get_id();

		// ACTUALIZAMOS LOS DATOS
		if ( $this->ci->update_armies->check_armies() )
		{
			if ( $this->user_id !== '' && $this->user_id !== 0 && $this->user_id !== NULL )
			{
				$this->ci->update_library->update_data ( $this->user_id );
			}
		}

		// LLAMAMOS A LA CONSULTA Y PASAMOS EL RESULTADO A UNA VARIABLE
		$this->template			=	$this->ci->template_model->get_template_data ( $this->user_id );
	}

	// ------------------------------------------------------------------- //
	// ----------------------------- GLOBAL ------------------------------ //
	// ------------------------------------------------------------------- //

	// SHOW ERROR MESSAGE
	public function message_box ( $message , $redirect = '' , $target = '' , $topbar = TRUE , $menu = TRUE )
	{
		$data['message']	= $message;
		$data['redirect']	= ( ( $redirect === '' ) ? '' : $redirect );
		$data['target']		= ( ( $target === '' ) ? $this->base_url : $this->base_url . $target );

		$this->page ( GLOBAL_FOLDER . 'global_messagebox_body' , $data , $topbar , $menu );
	}

	// ------------------------------------------------------------------- //
	// ----------------------------- PUBLIC ------------------------------ //
	// ------------------------------------------------------------------- //

	// BUILD PUBLIC PAGE
	public function public_page ( $template , $data = '' , $header = TRUE , $footer = TRUE )
	{
		if ( $header )
		{
			$this->public_head ();
		}

		if ( $template )
		{
			$this->public_body ( $template , $data );
		}

		if ( $footer )
		{
			$this->public_footer ();
		}
	}

	// GET HEAD
	private function public_head()
	{
		$header['base_url']		= $this->base_url;
		$header['css_path']		= $this->base_url . CSS_FOLDER;
		$header['js_path']		= $this->base_url . JS_FOLDER;
		$header['required_js']	= $this->return_js ( $header );

		return $this->ci->load->view ( INDEX_FOLDER . 'index_header_view' , $header );
	}

	// GET BODY
	private function public_body ( $template , $data )
	{
		if ( $template != '' )
		{
			$this->data['img_path']		= $this->base_url . IMG_FOLDER;

			if ( $data != '' )
			{
				$this->data			   += $data;
			}

			return $this->ci->load->view ( $template , $this->data );
		}
	}

	// GET FOOTER
	private function public_footer()
	{
		return $this->ci->load->view ( INDEX_FOLDER . 'index_footer_view' );

	}

	// ------------------------------------------------------------------- //
	// ----------------------------- IN GAME ----------------------------- //
	// ------------------------------------------------------------------- //

	// BUILD PAGE
	public function page ( $template , $data = '' , $topbar = TRUE , $menu = TRUE )
	{
		$this->head();
		$this->topvar( $topbar );
		$this->menu( $menu );
		$this->body( $template , $data );
		$this->footer();
	}

	// GET HEAD
	private function head()
	{
		$header['base_url']		= $this->base_url;
		$header['css_path']		= $this->base_url . CSS_FOLDER;
		$header['js_path']		= $this->base_url . JS_FOLDER;
		$header['game_title']	= 'Cruxata';
		$header['required_js']	= $this->return_js ( $header );

		return $this->ci->load->view ( GAME_FOLDER . 'game_header_view' , $header );
	}

	// GET TOPVAR
	private function topvar ( $topbar )
	{
		if ( $topbar )
		{
			$this->ci->load->library ( 'academy_library' );
			$this->ci->load->library ( 'buildings_library' );

			// RETORNA LA CANTIDAD DE SOLDADOS
			$soldiers				=	$this->ci->academy_library->count_soldiers();
			$max_soldiers			=	$this->ci->buildings_library->building_production ( 'building_barracks' , $this->template[0]['building_barracks'] );

			// PASAMOS LOS VALORES PARA QUE LUEGO SEAN LEIDOS EN LA PLANTILLA
			$navbar['wood']			=	$this->ci->functions->format_number ( $this->template[0]['resource_wood'] );
			$navbar['stone']		=	$this->ci->functions->format_number ( $this->template[0]['resource_stone'] );
			$navbar['gold']			=	$this->ci->functions->format_number ( $this->template[0]['resource_gold'] );
			$navbar['diamonds']		=	$this->ci->functions->format_number ( $this->template[0]['resource_diamonds'] );
			$navbar['soldiers']		=	$this->ci->academy_library->return_limit_color ( $soldiers , $max_soldiers );
			$navbar['messages']		=	$this->ci->functions->format_number ( $this->template[0]['resource_messages'] );
			$navbar['base_url']		=	$this->base_url;
			$navbar['img_path']		=	$this->base_url . IMG_FOLDER;
			$navbar['notices']		= 	'';
			$navbar['style']		= 	'';

			/* NOTICES - IF THE ACCOUNT IS INACTIVE, AND DELETE ACCOUNT IS TURN OFF */
			if ( $this->template[0]['user_status'] == INACTIVE_USER )
			{
				$navbar['notices']	= '<a href="' . $navbar['base_url'] . 'options" title="' . $this->ci->lang->line ( 'ge_inactive_account' ) . '"><img src="' . $navbar['base_url'] . '/img/clear.gif" width="32px" height="32px"/></a>';
				$navbar['style']	= 'notices';
			}

			/* NOTICES - IF DELETE ACCOUNT IS TURN ON, AND VACATION MODE IS OFF */
			if ( $this->template[0]['user_status'] == DELETED_USER )
			{
				$navbar['notices']	= '<a href="' . $navbar['base_url'] . 'options" title="' . $this->ci->lang->line ( 'ge_deleted_account' ) . '"><img src="' . $navbar['base_url'] . '/img/clear.gif" width="32px" height="32px"/></a>';
				$navbar['style']	= 'notices';
			}

			return $this->ci->load->view ( GAME_FOLDER . 'game_navigationbar_view' , $navbar );
		}
	}

	// GET MENU
	private function menu ( $menu )
	{
		if ( $menu )
		{
			$sections 	= array (
									'empire' 			=> array ( $this->ci->lang->line ( 'ge_empire' ) ),
									'production' 		=> array ( $this->ci->lang->line ( 'ge_buildings_production' ) ),
									'infrastructure' 	=> array ( $this->ci->lang->line ( 'ge_buildings_infrastructure' ) ),
									'productivity' 		=> array ( $this->ci->lang->line ( 'ge_productivity') ),
									'armory' 			=> array ( $this->ci->lang->line ( 'ge_armory' ) ),
									'academy' 			=> array ( $this->ci->lang->line ( 'ge_academy' ) ),
									'workshop'			=> array ( $this->ci->lang->line ( 'ge_workshop' ) ),
									'armies' 			=> array ( $this->ci->lang->line ( 'ge_armies' ) ),
									'field' 			=> array ( $this->ci->lang->line ( 'ge_field' ) ),
									'market'			=> array ( $this->ci->lang->line ( 'ge_market' ) ),
								);

			$menu				= array();
			$menu['sections']	= $sections;
			$menu['base_url']	= $this->base_url;
			$menu['user_name']	= $this->template[0]['user_name'];

			return $this->ci->load->view ( GAME_FOLDER . 'game_menu_view' , $menu );
		}
	}

	// GET BODY
	private function body ( $template , $data = '' )
	{
		if ( $template != '' )
		{
			return $this->ci->load->view ( $template , $data );
		}
	}

	// GET FOOTER
	private function footer()
	{
		$parse['base_url']	=	$this->base_url;
		return $this->ci->load->view ( GAME_FOLDER . 'game_footer_view' , $parse );
	}

	// RETORNA EL JS DE ACUERDO A LA PAGINA
	private function return_js ( $header )
	{
		// http://briancray.com/2009/08/06/check-all-jquery-javascript/
		$current_page	=	$this->ci->uri->segment(1);
		$public_pages	=	array ( '' , 'register' , 'credits' , 'rules' , 'terms-and-conditions' , 'lost-password' );
		$with_overlib	=	array ( 'armies' );
		$with_counter	=	array ( 'private-message' , 'global-message' );
		$with_countdown	=	array ( 'academy' , 'armies' , 'armory' , 'empire' , 'infrastructure' , 'production' , 'workshop' );

//		$js		 		= '<script language="JavaScript" src="' . $header['js_path'] .'jquery.min.js"></script>';
		$js				= '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>';
		$js	   		   .= "\n";
		// http://jacklmoore.com/colorbox/
		$js    		   .= '<script language="JavaScript" src="' . $header['js_path'] .'jquery.colorbox.min.js"></script>';
		$js	   		   .= "\n";

		if ( !in_array ( $current_page , $public_pages ) )
		{
			// http://plugins.jquery.com/project/jqClock
			$js    		.= '<script language="JavaScript" src="' . $header['js_path'] .'jquery.clock.min.js"></script>';
			$js	   		.= "\n";

			if ( in_array ( $current_page , $with_overlib ) )
			{
				// http://plugins.jquery.com/project/CursorMessage
				// http://www.kingsquare.nl/cursormessage
				$js    	.= '<script language="JavaScript" src="' . $header['js_path'] .'jquery.cursorMessage.min.js"></script>';
				$js	   	.= "\n";
			}

			if ( in_array ( $current_page , $with_counter ) )
			{
				$js    	.= '<script language="JavaScript" src="' . $header['js_path'] .'jquery.charCounter.min.js"></script>';
				$js	   	.= "\n";
			}

			if ( in_array ( $current_page , $with_countdown ) )
			{
				// http://keith-wood.name/countdown.html
				$js    	.= '<script language="JavaScript" src="' . $header['js_path'] .'jquery.countdown.min.js"></script>';
				$js	   	.= "\n";
			}
		}

		return $js;
	}
}

/* End of file template.php */
/* Location: ./application/libraries/template.php */