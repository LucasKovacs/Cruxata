<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Cron class for Cruxata
 *
 * @author Lucas Kovács <lucas.kovacs@gameloft.com>
 * @package Cruxata
 */

/**
 * Cron implementation
 *
 * Cron is a class that will handle all the cron jobs.
 *
 * @version		Release: v2.0.4
 * @link		./application/controllers/cron.php
 * @since		20/04/2014
 * @deprecated
 */

class Cron extends CI_Controller 
{
	// the runned scripts result
	private $_run	= array();

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
	}

	/**
	 * Run all the tasks
	 *
	 * @param
	 * @return (void)
	 */
	public function run()
	{
		// db backup
		$this->db_backup();
		
		// remove old messages
		$this->remove_old_messages();
		
		// remove old recruitments
		$this->remove_old_recruitments();
		
		// print cron result
		$this->result();
	}
	
	/**
	 * Print the result that will be sent by mail
	 *
	 * @param
	 * @return (void)
	 */
	private function result()
	{
		print_r ( $this->_run );
	}
	
	/**
	 * Database backup task
	 *
	 * @param
	 * @return (void)
	 */
	private function db_backup()
	{
		// Reference: http://ellislab.com/codeigniter/user-guide/database/utilities.html
		
		// Load the DB utility class
		$this->load->dbutil();
		
		// Backup your entire database and assign it to a variable
		$backup 	= &$this->dbutil->backup(); 
		
		// The filename
		$file_name	= date ( 'YmdHi' ) . '.gz';
		
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		
		$write_file	= write_file ( '/home/xtremega/backups/' . $file_name , $backup ); 
		
		$this->_run['db_backup']	= array (
												'run' 			=> true,
												'file_name'		=> $file_name,
												'write_file'	=> $write_file
											);
	}
	
	/**
	 * Clean old messages task
	 *
	 * @param
	 * @return (void)
	 */
	private function remove_old_messages()
	{
		// delete messages older than 7 days
		$this->db->where ( 'message_date <' , time() - ( 60 * 60 * 24 * 7 ) );
		$this->db->delete ( 'messages' );
		
		$this->_run['remove_old_messages']	= array (
														'run' 			=> true,
														'message_date'	=> date ( 'Y/m/d H:i' , ( time() - ( 60 * 60 * 24 * 7 ) ) )
													);
	}
	
	/**
	 * Clean old recruitments task
	 *
	 * @param
	 * @return (void)
	 */
	private function remove_old_recruitments()
	{
		// delete recruitments older than 1 day
		$this->db->where ( 'recruitment_time <' , time() - ( 60 * 60 * 24 ) );
		$this->db->delete ( 'recruitments' );
		
		$this->_run['remove_old_recruitments']	= array (
															'run' 				=> true,
															'recruitment_date'	=> date ( 'Y/m/d H:i' , ( time() - ( 60 * 60 * 24) ) )
														);
	}
}

/* End of file cron.php */
/* Location: ./application/controllers/cron.php */