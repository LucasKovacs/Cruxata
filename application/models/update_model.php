<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_model extends CI_Model
{
	// __construct
	public function __construct()
	{
		parent::__construct();
	}

	// TRAE LA INFORMACION NECESARIA PARA ACTUALIZAR
	public function get_update_data ( $user_id )
	{
		// SELECT
		$this->db->select	(  'acad.*,
							   	armo.*,
							   	buil.*,
							   	work.*,
							   	reso.resource_gold,
							   	user.user_name,
							   	user.user_updatetime'
							);

		// FROM
		$this->db->from ( '{PREFIX}users AS user' );

		// JOIN
		$this->db->join ( 'academy AS acad' 	, 'user.user_id = acad.academy_user_id' );
		$this->db->join ( 'armory AS armo' 		, 'user.user_id = armo.armory_user_id' );
		$this->db->join ( 'buildings AS buil' 	, 'user.user_id = buil.building_user_id' );
		$this->db->join ( 'resources AS reso' 	, 'user.user_id = reso.resource_user_id' );
		$this->db->join ( 'workshop AS work' 	, 'user.user_id = work.workshop_user_id' );

		// WHERE
		$this->db->where ( 'user.`user_id`'	, $user_id , TRUE );

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

	// TRAE TODOS LOS ATAQUES QUE ESTEN EN PROCESO
	public function get_current_attacks()
	{
		$this->db->select ( 'a.*' )
					->from ( 'armies AS a' )
					->where ( 'a.army_current <=' , time() )
					->where ( 'a.army_current <>' , 0 )
					->order_by ( 'a.army_id ASC' );

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

	// TRAE TODOS LOS ATAQUES QUE ESTEN POR FINALIZAR
	public function get_returning_attacks()
	{
		$this->db->select ( 'a.*' )
					->from ( 'armies AS a' )
					->where ( 'a.army_return <=' , time() )
					->where ( 'a.army_current' , 0 )
					->order_by ( 'a.army_id ASC' );

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

	// ACTUALIZA TODA LA INFORMACION EN UNA ÚNICA CONSULTA
	public function update_data ( $data )
	{
		// DEFINIMOS
		$query					=	'';
		$preQuery				=	'';
		$where					=	'';
		$points					=	0;

		// BASE
		$query			 		 =	"UPDATE ";

		// TABLAS
		if ( $data['academy'] != '' )
		{
			$query 	   			.=	"`{PREFIX}academy`, ";
			$where				 =	"`academy_user_id` = '" . $data['user_id'] . "' AND ";
		}

		if ( $data['armory'] != '' )
		{
			$query 	   			.=	"`{PREFIX}armory`, ";
			$where				.=	"`armory_user_id` = '" . $data['user_id'] . "' AND ";
		}

		if ( $data['buildings'] != '' )
		{
			$query 	   			.=	"`{PREFIX}buildings`, ";
			$where				.=	"`building_user_id` = '" . $data['user_id'] . "' AND ";
		}
		
		if ( $data['workshop'] != '' )
		{
			$query 	   			.=	"`{PREFIX}workshop`, ";
			$where				.=	"`workshop_user_id` = '" . $data['user_id'] . "' AND ";
		}

		// INICIA LA $preQuery
		$preQuery				 =	"SET ";

		// CARGA DE LOS CAMPOS
		if ( $data['academy'] != '' )
		{
			$preQuery 	   		.=	"`academy_current_build` = '" . $data['academy'][0]['current'] . "', ";

			foreach ( $data['academy'] as $key => $array )
			{
				if ( $key != 0 )
				{
					$preQuery	.=	"`" . $array['element'] . "` = `" . $array['element'] . "` + '" . $array['amount'] . "', ";
					$points		+=	$array['points'];
				}
			}
		}

		if ( $data['armory'] != '' )
		{
			$preQuery 	   		.=	"`armory_current_build` = '', ";
			$preQuery			.=	"`" . $data['armory']['element'] . "` = `" . $data['armory']['element'] . "` + 1, ";
			$points				+=	$data['armory']['points'];
		}

		if ( $data['buildings'] != '' )
		{
			$preQuery 	   		.=	"`building_current_build` = '', ";
			$preQuery			.=	"`" . $data['buildings']['element'] . "` = `" . $data['buildings']['element'] . "` + 1, ";
			$points				+=	$data['buildings']['points'];
		}
		
		// CARGA DE LOS CAMPOS
		if ( $data['workshop'] != '' )
		{
			$preQuery 	   		.=	"`workshop_current_build` = '" . $data['workshop'][0]['current'] . "', ";

			foreach ( $data['workshop'] as $key => $array )
			{
				if ( $key != 0 )
				{
					$preQuery	.=	"`" . $array['element'] . "` = `" . $array['element'] . "` + '" . $array['amount'] . "', ";
					$points		+=	$array['points'];
				}
			}
		}

		// ULTIMAS CARGA
		if ( $points > 0 )
		{
			$query 	   			.=	"`{PREFIX}statistics`, ";
			$preQuery			.=	"`statistic_points` = `statistic_points` + '" . $points . "', ";
			$where				.=	"`statistic_user_id` = '" . $data['user_id'] . "' AND ";
		}

		if ( $data['resources'] != '' )
		{
			$query 	   			.=	"`{PREFIX}resources`, `{PREFIX}users` ";
			$where				.=	"`resource_user_id` = '" . $data['user_id'] . "' AND `user_id` = '" . $data['user_id'] . "'";
		}

		// AHORA SI INSERTAMOS LA $preQuery
		$query					.=	$preQuery;

		// EL RESTO DE LA CONSULTA QUE QUEDA SIEMPRE IGUAL
		$query					.= 	"`resource_gold` = `resource_gold` + " . $data['resources']['gold'] . ",";
		$query					.= 	"`resource_stone` = `resource_stone` + " . $data['resources']['stone'] . ",";
		$query					.= 	"`resource_wood` = `resource_wood` + " . $data['resources']['wood'] . ",";
		$query					.= 	"`user_onlinetime` = " . ( ( $data['user_id'] == $this->auth->get_id() ) ? time() : '`user_onlinetime`' ) . ",";
		$query					.= 	"`user_updatetime` = " . $data['time'] . " ";
		$query					.=	"WHERE ";

		// EL WHERE
		$query					.= 	$where;

		// Y FINALMENTE CORREMOS LA CONSULTA
		$this->db->query ( $query );
	}

	// OBTIENE TODA LA INFORMACIÓN NECESARIA DEL ATACANTE Y DEL ENEMIGO
	public function get_attacker_data ( $attacker_id )
	{
		// SELECT
		$this->db->select ( 'a.armory_shield,
							 a.armory_gauntlet,
							 a.armory_boot,
							 a.armory_helmet,
							 a.armory_breastplate,
							 a.armory_hammer,
							 a.armory_spear,
							 a.armory_ax,
							 a.armory_sword,
							 a.armory_crossbow,
							 u.user_id,
							 u.user_name' 
							 );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( 'armory AS a' 	, 'u.user_id = a.armory_user_id' );

		// WHERE
		$this->db->where 	( '`u`.`user_id`'	, $attacker_id 	, FALSE );

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
	
	// OBTIENE TODA LA INFORMACIÓN NECESARIA DEL ATACANTE Y DEL ENEMIGO
	public function get_defender_data ( $enemy_id )
	{
		// SELECT
		$this->db->select ( 'a.armory_shield,
							 a.armory_gauntlet,
							 a.armory_boot,
							 a.armory_helmet,
							 a.armory_breastplate,
							 a.armory_hammer,
							 a.armory_spear,
							 a.armory_ax,
							 a.armory_sword,
							 a.armory_crossbow,
							 c.academy_warrior,
							 c.academy_spearman,
							 c.academy_infantryman,
							 c.academy_swordsman,
							 c.academy_crossbowman,
							 b.building_fortified_wall,
							 b.building_watchtower,
							 r.resource_gold,
							 r.resource_stone,
							 r.resource_wood,
							 u.user_id,
							 u.user_name' 
							 );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( 'academy AS c' 	, 'u.user_id = c.academy_user_id' );
		$this->db->join ( 'armory AS a' 	, 'u.user_id = a.armory_user_id' );
		$this->db->join ( 'buildings AS b' 	, 'u.user_id = b.building_user_id' );
		$this->db->join ( 'resources AS r' 	, 'u.user_id = r.resource_user_id' );

		// WHERE
		$this->db->where 	( '`u`.`user_id`'	, $enemy_id 	, FALSE );

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

/*	
	// OBTIENE TODA LA INFORMACIÓN NECESARIA DEL ATACANTE Y DEL ENEMIGO
	public function get_attack_data ( $attacker_id , $enemy_id )
	{
		// SELECT
		$this->db->select ( 'a.armory_shield,
							 a.armory_gauntlet,
							 a.armory_boot,
							 a.armory_helmet,
							 a.armory_breastplate,
							 a.armory_hammer,
							 a.armory_spear,
							 a.armory_ax,
							 a.armory_sword,
							 a.armory_crossbow,
							 c.academy_warrior,
							 c.academy_spearman,
							 c.academy_infantryman,
							 c.academy_swordsman,
							 c.academy_crossbowman,
							 r.resource_gold,
							 r.resource_stone,
							 r.resource_wood,
							 u.user_id,
							 u.user_name' 
							 );

		// FROM
		$this->db->from ( '{PREFIX}users AS u' );

		// JOIN
		$this->db->join ( 'academy AS c' 	, 'u.user_id = c.academy_user_id' );
		$this->db->join ( 'armory AS a' 	, 'u.user_id = a.armory_user_id' );
		$this->db->join ( 'resources AS r' 	, 'u.user_id = r.resource_user_id' );

		// WHERE
		$this->db->where 	( '`u`.`user_id`'	, $attacker_id 	, FALSE );
		$this->db->or_where ( '`u`.`user_id`'	, $enemy_id 	, FALSE );

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
	}*/

	// FINALIZA EL ATAQUE
	public function end_attack ( $end_attack_data )
	{
		//print_r($end_attack_data);
	
		// PRIMERO ACTUALIZAMOS EL EJERCITO CON LO QUE QUEDO
		$soldiers_attacker	= array();
		$soldiers_defender	= array();	
		$army_array			= '';
		$sub_query_remove	= '';	
			
		foreach ( $end_attack_data['attacker_army'] as $soldier => $info )
		{
			if ( $info['life'] > 0 )
			{
				if ( ! isset ( $soldiers_attacker[$info['soldier']] ) )
				{
					$soldiers_attacker[$info['soldier']]	= 0;
				}
				
				++$soldiers_attacker[$info['soldier']];			
			}
		}
		
		foreach ( $end_attack_data['defender_army'] as $soldier => $info )
		{
			if ( $info['life'] <= 0 )
			{
				if ( ! isset ( $soldiers_defender[$info['soldier']] ) )
				{
					$soldiers_defender[$info['soldier']]	= 0;
				}
				
				if ( isset ( $soldiers_defender['building_watchtower'] ) )
				{
					unset ( $soldiers_defender['building_watchtower'] );
				}
				else
				{
					++$soldiers_defender[$info['soldier']];
				}	
			}
		}
		
		//  HAY ALGO?
		if ( $soldiers_attacker != NULL )
		{
			// ARRAY DE SOLDADOS, REARMAMOS EN FORMATO EJERCITOS
			foreach ( $soldiers_attacker as $soldier => $amount )
			{
				$army_array	.= $soldier . ',' . $amount . ';';
			}	
		}
		
		//  HAY ALGO?
		if ( $soldiers_defender != NULL )
		{
			// ARRAY DE SOLDADOS, REARMAMOS EN FORMATO ACADEMIA
			foreach ( $soldiers_defender as $soldier => $amount )
			{
				if ( $soldier != 'building_watchtower' )
				{
					$sub_query_remove	.= 'c.`'.$soldier.'` = c.`'.$soldier.'` - ' . $amount . ',';	
				}
			}	
		}
		
		// The attacker lost!
		if ( $soldiers_attacker == NULL )
		{
			$this->db->where ( 'army_id' , $end_attack_data['army_id'] );
			$this->db->delete ( 'armies' );	
		
			// maybe the enemy lost nothing (How could I miss this! Damn Man!)
			if ( $sub_query_remove != '' )
			{
				$query = $this->db->query ( "UPDATE `{PREFIX}academy` AS c
												SET
													" . substr ( $sub_query_remove , 0 , -1 ) . "
												WHERE c.`academy_user_id` = '" . $end_attack_data['enemy_id'] . "'" );	
			}											
		}
		else // The attacker wins!
		{	
			$query = $this->db->query ( "UPDATE `{PREFIX}resources` AS r, `{PREFIX}armies` AS a, `{PREFIX}academy` AS c
											SET
												r.`resource_gold` = r.`resource_gold` - " . (int)$end_attack_data['stolen_gold'] . ",
												r.`resource_stone` = r.`resource_stone` - " . (int)$end_attack_data['stolen_stone'] . ",
												r.`resource_wood` = r.`resource_wood` - " . (int)$end_attack_data['stolen_wood'] . ",
												" . $sub_query_remove . "
												a.`army_current` = '',
												a.`army_troops` = '" . $army_array . "',
												a.`army_gold` = " . (int)$end_attack_data['stolen_gold'] . ",
												a.`army_stone` = " . (int)$end_attack_data['stolen_stone'] . ",
												a.`army_wood` = " . (int)$end_attack_data['stolen_wood'] . "
											WHERE r.`resource_user_id` = '" . $end_attack_data['enemy_id'] . "' AND
													c.`academy_user_id` = '" . $end_attack_data['enemy_id'] . "' AND
													a.`army_id` = '" . $end_attack_data['army_id'] . "'" );														
		}
		
		// ACTUALIZACIÓN FINAL DE PUNTOS
		// SI HAY PUNTOS QUE ALTERAR ACTUALIZAMOS
		if ( $end_attack_data['attacker_points'] > 0 )
		{
			$this->db->query ( "UPDATE `{PREFIX}statistics` AS s SET 
									s.`statistic_points` = s.`statistic_points` - " . $end_attack_data['attacker_points'] . "
									WHERE s.`statistic_user_id` = " . $end_attack_data['attacker_id'] . ";" );	
		}
		
		// SI HAY PUNTOS QUE ALTERAR ACTUALIZAMOS
		if ( $end_attack_data['defender_points'] > 0 )
		{
			$this->db->query ( "UPDATE `{PREFIX}statistics` AS s SET 
									s.`statistic_points` = s.`statistic_points` - " . $end_attack_data['defender_points'] . "
									WHERE s.`statistic_user_id` = " . $end_attack_data['enemy_id'] . ";" );
		}		
	}

	// RETORNA EL EJERCITO Y LOS RECURSOS AL IMPERIO
	public function return_army ( $data )
	{
		// EXTRAEMOS EL ARRAY DE TROPAS
		$sub_query	=	'';
		$troops		=	explode ( ';' , $data['army_troops'] );

		// RECORREMOS EL ARRAY DE TROPAS
		foreach ( $troops as $key => $soldier )
		{
			// SI NO ES NULO
			if ( $soldier != NULL )
			{
				// EXTRAEMOS EL SOLDADO Y LA CANTIDAD
				$info	=	explode ( ',' , $soldier );

				// GUARDAMOS LOS VALORES EN UN ARRAY PARA PODER
				// LEERLOS FACILMENTE Y ARMAR LA QUERY DE ACTUALIZACION
				// ARMOS LA SUBQUERY
				$sub_query	.=	'`' . $info[0] . '` = `' . $info[0] . '` + \'' . $info[1] . '\', ';
			}
		}

		// ACTUALIZAMOS FINALMENTE EL ORO Y RESTABLECEMOS LOS SOLDADOS A LA ACADEMIA
		$this->db->query ( 'UPDATE `{PREFIX}academy`, `{PREFIX}resources`
							SET ' . $sub_query . '
								`resource_gold` = `resource_gold` + \'' . $data['army_gold'] . '\',
								`resource_stone` = `resource_stone` + \'' . $data['army_stone'] . '\',
								`resource_wood` = `resource_wood` + \'' . $data['army_wood'] . '\'
							WHERE `academy_user_id` = \'' . $data['army_user_id'] . '\' AND `resource_user_id` = \'' . $data['army_user_id'] . '\';' );

		// BORRAMOS EL REGISTRO DEL EJERCITO DE LA TABLA
		$this->db->where ( 'army_id' , $data['army_id'] );
		$this->db->delete ( 'armies' );
	}

	// INSERTA UN MENSAJE
	public function insert_message ( $message , $end_attack_data )
	{
		// PREPARAMOS EL MENSAJE PARA EL ATACANTE
		$insert['message_user_id']	=	$end_attack_data['attacker_id'];
		$insert['message_sender']	=	0;
		$insert['message_date']		=	$end_attack_data['army_current'];
		$insert['message_type']		=	ATTACKS_MADE;
		$insert['message_subject']	=	strtr ( $this->lang->line ( 'me_combat_report' ) , array ( '%a' => $end_attack_data['user_name'] , '%b' => $end_attack_data['enemy_name'] ) );
		$insert['message_text']		=	$message;

		$this->db->insert ( 'messages' , $insert );	// ENVIAMOS

		// PREPARAMOS EL MENSAJE PARA EL ENEMIGO
		$insert['message_user_id']	=	$end_attack_data['enemy_id'];
		$insert['message_sender']	=	0;
		$insert['message_date']		=	$end_attack_data['army_current'];
		$insert['message_type']		=	ATTACKS_RECEIVED;
		$insert['message_subject']	=	strtr ( $this->lang->line ( 'me_combat_report' ) , array ( '%a' => $end_attack_data['user_name'] , '%b' => $end_attack_data['enemy_name'] ) );
		$insert['message_text']		=	$message;

		$this->db->insert ( 'messages' , $insert );	// ENVIAMOS
	}	
}

/* End of file armory_model.php */
/* Location: ./application/models/armory_model.php */