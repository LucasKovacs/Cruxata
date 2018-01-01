<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Recruit_model class for Cruxata
 *
 * @author Lucas Kovács <lucas.kovacs@gameloft.com>
 * @package Cruxata
 */

/**
 * Recruit_model implementation
 *
 * Recruit_model is a class that will handle user recruits.
 *
 * @version		Release: v2.1.0
 * @link		./application/modelds/recruit_model.php
 * @since		02/05/2014
 * @deprecated
 */

class Recruit_model extends CI_Model
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Check if the user exists and return its data
	 *
	 * @param (string) $user_name
	 * @return (mixed)
	 */
	public function user_exists ( $user_name )
	{
		// SELECT
		$this->db->select	(  'u.user_id,
								b.building_barracks,
							   	r.*'
							);

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( 'buildings AS b' , 'u.user_id = b.building_user_id' );
		$this->db->join ( 'recruitments AS r' , 'u.user_id = r.recruitment_user_id' , 'left' );

		// WHERE
		$this->db->where ( 'u.`user_name`' , $user_name , TRUE );

		$query = $this->db->get();

		// RESULTADO
		if ( $query->result() )
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Insert the recruitment IP
	 *
	 * @param (array) $insert_data
	 * @return (void)
	 */
	public function insert_ip ( $insert_data )
	{
		// insert the data
		$this->db->insert ( 'recruitments' , $insert_data );
	}
	
	/**
	 * Update academy data, add one soldier
	 *
	 * @param (int) $user_id
	 * @return (void)
	 */
	public function add_soldier ( $user_id )
	{
		// update the database
		$this->db->query ( "UPDATE `{PREFIX}academy` AS a SET 
								a.`academy_warrior` = a.`academy_warrior` + 1
								WHERE a.`academy_user_id` = " . $user_id . ";" );
	}
}

/* End of file recruit_model.php */
/* Location: ./application/models/recruit_model.php */