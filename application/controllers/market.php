<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Market extends CI_Controller {

	private $_base_url;
	private $_img_path;
	private $_market;
	private $_exchange_mode;

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
	public function index ( $exchange_mode = '' )
	{
		if ( $this->auth->check() )
		{
			$this->_exchange_mode	= $exchange_mode;
		
			// MUESTRA LA PAGINA
			$this->show_page();
		}
		else
		{
			redirect ( $this->_base_url );
		}
	}

	// MUESTRA LA PAGINA
	private function show_page ()
	{
		$data['base_url']							= $this->_base_url;
		$data['img_path']							= $this->_img_path;
		
		foreach ( $this->_market  as $key => $element )
		{
			$calculated_difference					= $this->calculate_difference ( $element['market_resource_actual'] , $element['market_resource_previous'] );
		
			$market_data['image']					= $this->return_resource_img ( $element['market_resource'] );
			$market_data['title']					= $this->lang->line ( 'mk_' . $element['market_resource'] );
			$market_data['resource_available']		= $this->functions->format_number ( $element['market_resource_actual'] );
			$market_data['resource_previous']		= $this->functions->format_number ( $element['market_resource_previous'] );
			$market_data['resource_difference']		= $this->return_difference_color ( $element['market_resource_actual'] , $element['market_resource_previous'] );
			$market_data['resource_ratio']			= $element['market_resource_ratio'];		
			$market_data['resource_add_ratio']		= $calculated_difference['value'];
			$market_data['resource_market']			= $element['market_resource'];
			
			if ( $calculated_difference['type'] == 'less' )
			{
				$market_data['resource_final_ratio']	= $market_data['resource_ratio'] + $market_data['resource_ratio'] * ( $calculated_difference['value'] / 100 );
			}
			
			if ( $calculated_difference['type'] == 'same' )
			{
				$market_data['resource_final_ratio']	= $market_data['resource_ratio'];
			}
			
			if ( $calculated_difference['type'] == 'plus' )
			{
				$market_data['resource_final_ratio']	= $market_data['resource_ratio'] - $market_data['resource_ratio'] * ( $calculated_difference['value'] / 100 );
			}	
		
			$data['market_table'][]					= $market_data;
		}
		
		switch ( $this->_exchange_mode )
		{
			case 'resource_wood':
			
				$data['test']	= $this->_exchange_mode;
			
			break;
			
			case 'resource_stone':
			
				$data['test']	= $this->_exchange_mode;
			
			break;
			
			case 'resource_gold':
			
				$data['test']	= $this->_exchange_mode;
			
			break;
		}
		

		$this->template->page ( MARKET_FOLDER . 'market_view' , $data );
	}
	
	// RETORNA LA IMAGEN NECESARIA
	private function return_resource_img ( $current_resource )
	{
		switch ( $current_resource )
		{
			case 'resource_wood':
			
				return 'imgWood';
			
			break;
			
			case 'resource_stone':
			
				return 'imgStone';
			
			break;
			
			case 'resource_gold':
			
				return 'imgGold';
			
			break;
		}
	}
	
	// RETORNA LA FLUCTUACION DE RECURSOS CON ESTILOS
	private function return_difference_color ( $current , $previous )
	{
		if ( $current > $previous )
		{
			return '<span class="plusColor bold">( - ' . $this->functions->format_number ( ( $current - $previous ) / 1000 )  . ' )</span>';
		}
		
		if ( $current == $previous )
		{
			return '<span>(-)</span>';
		}
		
		if ( $current < $previous )
		{
			return '<span class="minusColor bold">( + ' . $this->functions->format_number ( ( $previous - $current ) / 1000 ) . ' )</span>';
		}
	}
	
	// RETORNA LA FLUCTUACION DE RECURSOS EN NÚMEROS
	private function calculate_difference ( $current , $previous )
	{
		if ( $current > $previous )
		{
			$return['value']	= $this->functions->format_number ( ( $current - $previous ) / 1000 );
			$return['type']		= 'less';
		};
		
		if ( $current == $previous )
		{
			$return['value']	= 0;
			$return['type']		= 'same';
		}
		
		if ( $current < $previous )
		{
			$return['value']	= $this->functions->format_number ( ( $previous - $current ) / 1000 );
			$return['type']		= 'plus';
		}
		
		return $return;
	}
}

/* End of file market.php */
/* Location: ./application/controllers/market.php */