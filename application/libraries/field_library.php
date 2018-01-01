<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Field_library
{
	protected $instance;

	// __construct
	public function __construct()
	{
		$this->instance = &get_instance();

		$this->instance->load->model ( 'functions_model' );
	}

	// RETORNA LA CANTIDAD DE PÁGINAS
	public function return_page_count ( $kingdom )
	{
		switch ( $kingdom )
		{
			case 'franks':

				return FRANK / PAGE_QUANTITY;

			break;

			case 'germanic':

				return GERMANIC / PAGE_QUANTITY;

			break;

			case 'hungary':

				return HUNGARY / PAGE_QUANTITY;

			break;

			case 'english':

				return ENGLAND / PAGE_QUANTITY;

			break;

			default:

				return FRANK / PAGE_QUANTITY;

			break;
		}
	}

	// RETORNA LA PAGINA ACTUAL BASADA EN EL FEUDO
	public function return_page ( $pages_count , $feud )
	{
		for ( $current_page = 0; $current_page <= $pages_count ; $current_page++  )
		{
			$start_range	= ( $current_page * PAGE_QUANTITY ) + 1;
			$end_range 		= ( $start_range + ( PAGE_QUANTITY - 1 ) );

			if ( ( $feud >= $start_range ) && ( $feud <= $end_range ) )
			{
				return $current_page + 1;
			}
		}
	}

	// RETORNA EN UN ARRAY EL PRINCIPIO Y FINAL BASADO EN EL FEUDO PASADO
	public function return_between_feud ( $pages_count , $feud )
	{
		for ( $current_page = 0; $current_page <= $pages_count ; $current_page++  )
		{
			$start_range	= ( $current_page * PAGE_QUANTITY ) + 1;
			$end_range 		= ( $start_range + ( PAGE_QUANTITY - 1 ) );

			if ( ( $feud >= $start_range ) && ( $feud <= $end_range ) )
			{
				$between['start']	=	$start_range;
				$between['end']		=	$end_range;

				return $between;
			}
		}
	}

	// RETORNA EN UN ARRAY EL PRINCIPIO Y FINAL BASADO EN LA PAGINA PASADA
	public function return_between_page( $page )
	{
		$between['end']		=	$page * PAGE_QUANTITY;
		$between['start']	=	$between['end'] - 19;

		return $between;
	}

	// RETORNA LA PAGINA ACTUAL LUEGO DE UNA SERIE DE VALIDACIONES
	public function return_current_page ( $page_count , $page , $feud )
	{
		// SI LA PAGINA VINO VACIA POR POST
		if ( $page == '' )
		{
			// CHEQUEAMOS LA PAGINA CORRESPONDIENTE
			if ( $this->return_page ( $page_count , $feud ) == '' )
			{
				// SI NO TRAE NADA VIENE VACIO
				$current_page	=	1;
			}
			else
			{
				// EN CASO CONTRARIO RETORNAMOS LA PAGINA CORRECTA
				$current_page	=	$this->return_page ( $page_count , $feud );
			}
		}
		else
		{
			// SI LA PAGINA ESTA ENTRE EL RANGO PERMITIDO
			if ( ( $page >= 1 ) && ( $page <= $page_count ) )
			{
				$current_page		= $page;
			}
			else
			{
				// SINO
				// SI ES MENOS A 1 DEVOLVEMOS LA PRIMER PAGINA
				if ( $page < 1  )
				{
					$current_page	= 1;
				}

				//SI ES MAYOR AL LIMITE DEVOLVEMOS LA ULTIMA PAGINA
				if ( $page > $page_count)
				{
					$current_page	= $page_count;
				}
			}
		}

		// RETORNAMOS LA PAGINA
		return $current_page;
	}

	// RETORNA EL TIEMPO QUE TARDA EL ATAQUE
	public function get_attack_time ( $attacker , $enemy )
	{
		$attacker_page	=	$this->return_page ( $this->return_page_count ( $attacker[0]['user_kingdom'] ) , $attacker[0]['user_feud'] );
		$enemy_page		=	$this->return_page ( $this->return_page_count ( $enemy[0]['user_kingdom'] ) , $enemy[0]['user_feud'] );
		$attacker_mult	=	( PAGE_QUANTITY * ( $attacker_page - 1 ) );
		$enemy_mult		=	( PAGE_QUANTITY * ( $enemy_page - 1 ) );

		if ( $attacker_page == $enemy_page )
		{
			// LLENAMOS EL ARRAY Y A MEDIDA QUE VAMOS ARMANDOLO VAMOS TOMANDO LA FILA Y COLUMNA
			for ( $row = 1 ; $row <= 5 ; $row++ )
			{
				for ( $col = 1 ; $col <= 4 ; $col++ )
				{
					// LLENA NORMALMENTE
					if ( $row == 1 )
					{
						$field[$row][$col]	= ( $col + $attacker_mult );
					}
					else
					{
						$field[$row][$col]	= ( $field[$row-1][$col] + 4 );
					}

					// EXTRAEMOS LOS DATOS PARA EL ATACANTE
					if ( $field[$row][$col] == $attacker[0]['user_feud'] )
					{
						$attacker_row	= $row;
						$attacker_col	= $col;
					}

					// EXTRAEMOS LOS DATOS PARA EL ENEMIGO
					if ( $field[$row][$col] == $enemy[0]['user_feud'] )
					{
						$enemy_row	= $row;
						$enemy_col	= $col;
					}
				}
			}
		}
		else
		{
			// CHEQUEAMOS EL MULTIPLICADOR DE ACUERDO A CUAL ES MAYOR
			// ASI DETERMINAMOS LA CANTIDAD DE PAGINAS
			if ( $attacker_page > $enemy_page )
			{
				$multiplier	= 	$attacker_page;
			}
			else
			{
				$multiplier	=	$enemy_page;
			}

			// LLENAMOS EL ARRAY Y A MEDIDA QUE VAMOS ARMANDOLO VAMOS TOMANDO LA FILA Y COLUMNA
			for ( $row = 1 ; $row <= ( 5 * $multiplier ) ; $row++ )
			{
				for ( $col = 1 ; $col <= 4 ; $col++ )
				{
					// LLENA NORMALMENTE
					if ( $row == 1 )
					{
						$field[$row][$col]	= $col;
					}
					else
					{
						$field[$row][$col]	= ( $field[$row-1][$col] + 4 );
					}

					// EXTRAEMOS LOS DATOS PARA EL ATACANTE
					if ( $field[$row][$col] == $attacker[0]['user_feud'] )
					{
						$attacker_row	= $row;
						$attacker_col	= $col;
					}

					// EXTRAEMOS LOS DATOS PARA EL ENEMIGO
					if ( $field[$row][$col] == $enemy[0]['user_feud'] )
					{
						$enemy_row	= $row;
						$enemy_col	= $col;
					}
				}
			}
		}

		// MOVIMIENTOS
		$row_movement	= abs ( $attacker_row - $enemy_row );
		$col_movement	= abs ( $attacker_col - $enemy_col );
		$total_movement	= ( $row_movement + $col_movement ) * FIELD_ROWS_COLS_MOV_TIME;

		// SI LOS REINOS NO SON EL MISMO DEBEMOS AGREGAR UN PLUS AL TIEMPO TOTAL
		if ( $attacker[0]['user_kingdom'] != $enemy[0]['user_kingdom'] )
		{
			$total_movement += FIELD_KINGDOMS_MOV_TIME;
		}

		return $total_movement;
	}

	// CUENTA LA CANTIDAD DE SOLDADOS
	public function count_soldiers ( $current )
	{
		$this->instance->load->library ( 'academy_library' );

		$count = 0;

		foreach ( $current as $key => $value )
		{
			if ( $this->instance->academy_library->is_soldier ( $key ) )
			{
				$count += $value;
			}
		}

		return $count;
	}
}

/* End of file field_library.php */
/* Location: ./application/libraries/field_library.php */