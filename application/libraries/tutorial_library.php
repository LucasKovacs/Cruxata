<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tutorial_library class for Cruxata
 *
 * @author Lucas Kovács <lucas.kovacs@gameloft.com>
 * @package Cruxata
 */

/**
 * Tutorial_library implementation
 *
 * Tutorial_library is a class that will handle the tutorial, we need this because any action inside the game will have impact over the tutorial.
 *
 * @version		Release: v2.1.0
 * @link		./application/libraries/tutorial_library.php
 * @since		02/05/2014
 * @deprecated
 */
class Tutorial_library
{
	// __construct
	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model ( '' );
	}
	
	
}

/* End of file tutorial_library.php */
/* Location: ./application/libraries/tutorial_library.php */