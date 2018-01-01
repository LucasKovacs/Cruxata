<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Recruit class for Cruxata
 *
 * @author Lucas Kovács <lucas.kovacs@gameloft.com>
 * @package Cruxata
 */

// require some needed files
require_once ( 'application/libraries/external/proxy_detector.class.php' );

/**
 * Recruit implementation
 *
 * Recruit is a class that will handle user recruits.
 *
 * @version		Release: v2.1.0
 * @link		./application/controllers/recruit.php
 * @since		01/05/2014
 * @deprecated
 */
class Recruit extends CI_Controller 
{
	// the user id that will receive a recruit
	private $_user_id;
	
	// the anonymous user request data
	private $_user_host;
	
	// the proxy detector instance
	private $_proxy_detector;
	
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
		$this->load->model ( 'recruit_model' );
		
		// load some libraries
		$this->load->library ( array ( 'academy_library' , 'buildings_library' ) );
		
		// create the proxy class
		$this->_proxy_detector 	= new proxy_detector();
		
		// load current user with it's REQUEST data
		$this->_user_host		= $_SERVER;
	}
	
	/**
	 * Handles the page and shows it
	 *
	 * @param (string) $user_name
	 * @return (void)
	 */
	public function index ( $user_name = '' )
	{	
		// check proxy
		if ( ! $this->_proxy_detector->detect() )
		{		
			// get user data
			$this->_user_data	= $this->recruit_model->user_exists ( $user_name );
		
			// if we have something
			if ( ! empty ( $this->_user_data ) )
			{			
				// check if the user already recruited and the current amount of soldiers
				if ( $this->not_recruited() && $this->enought_room() )
				{
					// ok, everything it's ok! Let's recruit the soldier
					$this->do_recruit();	
				}
			}	
		}
		
		// redirect to registration page
		redirect ( base_url() . 'register' );
	}
	
	/**
	 * recruit the soldier after some checks
	 *
	 * @param
	 * @return (bool) $recruitment_status
	 */
	private function do_recruit()
	{
		// the data that will be inserted
		$recruitment_data['recruitment_user_id']	= $this->_user_data[0]['user_id'];
		$recruitment_data['recruitment_ip']			= $this->_user_host['REMOTE_ADDR'];
		$recruitment_data['recruitment_time']		= time();
	
		// start transactions
		$this->db->trans_start();
	
		// record the IP
		$this->recruit_model->insert_ip ( $recruitment_data );
		
		// update soldiers amount
		$this->recruit_model->add_soldier ( $this->_user_data[0]['user_id'] );
		
		// end transactions
		$this->db->trans_complete();
	}
	
	/**
	 * check if a recruit was made with this IP recently
	 *
	 * @param
	 * @return (bool)
	 */
	private function not_recruited()
	{
		// check all the IPs	
		foreach ( $this->_user_data as $key => $data )
		{
			$recruitment_time	= $data['recruitment_time'];
			$expire_time		= $recruitment_time + 60 * 60 * 24;
			$current_time		= time();
			
			// if it's the same IP let's check the time!
			if ( $data['recruitment_ip'] == $this->_user_host['REMOTE_ADDR'] )
			{			
				// check the expire time, just in case. A cron should remove old records
				if ( $expire_time > $current_time )
				{
					// sorry, no recruit available
					return FALSE;
				}
				
				// end the loop, we found something
				break;
			}
		}
		
		// ok, the user can recruit
		return TRUE;
	}
	
	/**
	 * check the current amount of soldiers
	 *
	 * @param
	 * @return (bool)
	 */
	private function enought_room()
	{
		// get some data
		$current_soldiers	= $this->academy_library->count_soldiers ( $this->_user_data[0]['user_id'] );
		$max_soldiers		= $this->buildings_library->building_production ( 'building_barracks' , $this->_user_data[0]['building_barracks'] );
		
		// check the soldiers amount
		if ( $current_soldiers < $max_soldiers )
		{
			// ok, we have enought room for more soldiers
			return TRUE;
		}
		
		// no more room for soldiers
		return FALSE;
	}
}

/* End of file recruit.php */
/* Location: ./application/controllers/recruit.php */