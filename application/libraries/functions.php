<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions
{
	protected $ci;

	// __construct
	public function __construct()
	{
		$this->ci = &get_instance();

		$this->ci->load->model ( 'functions_model' );
	}

	// REVISA QUE SEA UN NUMERO VALIDO
	public function is_a_number ( $number )
	{
		if ( ( intval ( is_numeric ( $number ) ) ) && ( $number !== '' ) && ( $number >= 0 ) )
		{
			$number = str_replace ( array ( ',' , '.' ) , '' , $number ); // SEGUNDA VALIDACION

			return $number;
		}
		else
		{
			return FALSE;
		}
	}

	// LE DA FORMATO A LOS NUMEROS
	public function format_number ( $number , $floor = TRUE )
	{
		if ( $floor )
		{
			$number = floor ( $number );
		}

		return number_format ( $number , 0 , "," , "." );
	}

	// LE DA FORMATO A UN TIMEPO PASADO EN SEGUNDOS
	public function format_time ( $seconds )
	{
		$seconds 	= floor ( $seconds );
		$day 		= floor ( $seconds / ( 24 * 3600 ) );
		$hs 		= floor ( $seconds /   3600 % 24 );
		$ms 		= floor ( $seconds /     60 % 60 );
		$sr 		= floor ( $seconds /      1 % 60 );

		if ($hs < 10) { $hh = "0" . $hs; } else { $hh = $hs; }
		if ($ms < 10) { $mm = "0" . $ms; } else { $mm = $ms; }
		if ($sr < 10) { $ss = "0" . $sr; } else { $ss = $sr; }

		$time = '';
		if ($day != 0) { $time .= $day . 'd '; }
		if ($hs  != 0) { $time .= $hh . 'h ';  } else { if ( $day == 0 ) { $time .= '';} else { $time .= '00s ';} }
		if ($ms  != 0) { $time .= $mm . 'm ';  } else { $time .= '00m '; }
		$time .= $ss . 's';

		return $time;
	}

	// LE DA FORMATO A UNA HORA
	public function format_hour ( $time , $seconds = FALSE )
	{
		date_default_timezone_set ( 'America/Buenos_Aires' );

		if ( $seconds )
		{
			return date ( 'd/m/Y H:i:s' , $time );
		}
		else
		{
			return date ( 'd/m/y H:i' , $time );
		}

	}

	// EXTRAE LA POSICION
	public function extract_position ( $position )
	{
		return explode ( ';' , $position );
	}

	// FORMATEA LA POSICION
	public function format_position ( $position )
	{
		switch ( $position )
		{
			case 'franks':

				return $this->ci->lang->line ( 'ge_franks' );

			break;

			case 'germanic':

				return $this->ci->lang->line ( 'ge_germanic' );

			break;

			case 'hungary':

				return $this->ci->lang->line ( 'ge_hungary' );

			break;

			case 'english':

				return $this->ci->lang->line ( 'ge_english' );

			break;

			default:

				return '';

			break;
		}
	}

	// RETORNA EL TIEMPO DESDE LA ULTIMA CONEXION CON UN FORMATO
	public function format_online_time ( $online_time )
	{
		$online_time	=	( time() - $online_time ) / 60;

		if ( ( $online_time >= 0 ) && ( $online_time < 15 ) )
		{
			return '<font color="green">' . $this->ci->lang->line ( 'ge_online' ) . '</font>';
		}

		if ( ( $online_time >= 15 ) && ( $online_time < 60 ) )
		{
			return '<font color="orange">' . $this->ci->lang->line ( 'ge_five_minutes' ) . '</font>';
		}

		if ( $online_time >= 60 )
		{
			return '<font color="red">' . $this->ci->lang->line ( 'ge_offline' ) . '</font>';
		}
	}

	// RETORNA LA CANTIDAD DE PUNTOS
	public function calculate_points ( $resource_amount )
	{
		return ( ( $resource_amount['gold'] + $resource_amount['stone'] + $resource_amount['wood'] ) / POINTS_FACTOR );
	}

	// CALCULA EL ORO POR HORA
	public function gold_per_hour ( $level )
	{
		$this->ci->load->library ( 'buildings_library' );

		return	BASE_GOLD + $this->ci->buildings_library->building_production ( 'building_goldmine' , $level , FALSE );
	}

	// CALCULA EL ORO POR HORA
	public function stone_per_hour ( $level )
	{
		$this->ci->load->library ( 'buildings_library' );

		return	BASE_STONE + $this->ci->buildings_library->building_production ( 'building_stonemine' , $level , FALSE );
	}

	// CALCULA EL ORO POR HORA
	public function wood_per_hour ( $level )
	{
		$this->ci->load->library ( 'buildings_library' );

		return	BASE_WOOD + $this->ci->buildings_library->building_production ( 'building_sawmill' , $level , FALSE );
	}

	// GENERA EL HASH BASANDOSE EN LOS DATOS PROVISTOS
	public function generate_hash ( $data )
	{
		$rand_number	= rand ( 1 , 1000000000 );
		$hash 			= '@CruxataHash:' . $rand_number . sha1 ( $data['user_password'] ) . $data['user_email'] . $data['user_id'] . ':#';
		$hash			= sha1 ( $hash );

		if ( $this->ci->functions_model->insert_hash_data ( $hash , $rand_number , $data['user_id'] ) )
		{
			return $hash;
		}
		else
		{
			return FALSE;
		}

	}

	// VALIDA EL FORMULARIO
	public function validate_form ( $rules )
	{
		$this->ci->load->library ( 'form_validation' );

		$this->ci->form_validation->set_rules ( $rules );

		if ( $this->ci->form_validation->run() )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	// round up a number
	public function round_up ( $number , $increments = 1 ) 
	{
	    $increments	= 1 / $increments;
	    
	    return ( ceil ( $number * $increments ) / $increments );
	}
}

/* End of file functions.php */
/* Location: ./application/libraries/functions.php */