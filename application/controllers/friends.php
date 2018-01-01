<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Friends class for Cruxata
 *
 * @author Lucas Kovács <lucas.kovacs@gameloft.com>
 * @package Cruxata
 */

/**
 * Friends implementation
 *
 * Friends is a class that will handle several stuff to promote the game, 
 * the user will receive a social experience will Cruxata will receive new people.
 *
 * @version		Release: v2.1.0
 * @link		./application/controllers/friends.php
 * @since		02/05/2014
 * @deprecated
 */
class Friends extends CI_Controller 
{
	// the friends data
	private $_friends;

	// the base url
	private $_base_url;

	/**
	 * Constructor
	 */
	/**
	 * start everything
	 *
	 * @param
	 * @return (void)
	 */
	public function __construct()
	{
		// refer to the parent
		parent::__construct();
		
		// load the model
		$this->load->model ( 'friends_model' );

		// get required data
		$this->_friends		= $this->friends_model->get_friends_data ( $this->auth->get_id() );
		
		// get extra data
		$this->_base_url	= base_url();
	}
	
	/**
	 * Handles the page and shows it
	 *
	 * @param
	 * @return (void)
	 */
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
	
	/**
	 * Show the page and it's content
	 *
	 * @param
	 * @return (void)
	 */
	private function show_page()
	{
		$parse['user_link']	= $this->_base_url . 'recruit/' . $this->_friends[0]['user_name'];
	
		$this->template->page ( FRIENDS_FOLDER . 'friends_view' , $parse );	
	}
}

/* End of file friends.php */
/* Location: ./application/controllers/friends.php */