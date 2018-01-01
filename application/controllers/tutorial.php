<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Recruit class for Cruxata
 *
 * @author Lucas Kovács <lucas.kovacs@gameloft.com>
 * @package Cruxata
 */

/**
 * Recruit implementation
 *
 * Recruit is a class that will handle user recruits.
 *
 * @version		Release: v2.1.0
 * @link		./application/controllers/tutorial.php
 * @since		12/04/2014
 * @deprecated
 */
class Tutorial extends CI_Controller 
{
	// the base url
	private $_base_url;
	
	// the img path
	private $_img_path;
	
	// current guide step
	private $_step;

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
		parent::__construct();

		$this->_base_url	= base_url();
		$this->_img_path	= $this->_base_url . IMG_FOLDER;
	}

	/**
	 * Handles the page and shows it
	 *
	 * @param (string) $user_name
	 * @return (void)
	 */
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

	/**
	 * Show the page and it's content
	 *
	 * @param
	 * @return (void)
	 */
	private function show_page ()
	{
		$data = '';
		
		$this->template->page ( TUTORIAL_FOLDER . 'tutorial_view' , $data );
	}
}

/* End of file tutorial.php */
/* Location: ./application/controllers/tutorial.php */