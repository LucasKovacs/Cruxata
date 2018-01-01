<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Missions_library
{
	private $ci;

	// __construct
	public function __construct()
	{
		$this->ci = &get_instance();
	}

	// MISIÓN: ATACAR
	// ATACAMOS A UN OPONENTE PARA ROBARLE RECURSOS
	public function mission_attack ()
	{
	}
	
	// MISIÓN: EXPLORAR
	// REVISAMOS QUE TIENE UN OPONENTE Y OBTENEMOS UN REPORTE
	public function mission_explore ()
	{
	}
	
	// MISIÓN: INVADIR
	// INVADIMOS UN FEUDO OCUPADO PARA OBTENER UNA RENTA DE OTRO JUGADOR
	public function mission_invade ()
	{
	}
	
	// MISIÓN: OCUPAR
	// OCUPAMOS UN FEUDO VACIO PARA EXPLOTAR
	public function mission_occupy ()
	{
	}
	
	// RETORN EL TIEMPO DE DURACIÓN DE LA MISIÓN
	public function return_mission_duration ( $mission )
	{
		switch ( $mission )
		{
			case 1: // ATAQUE
				return ATTACK_DURATION;
			break;
			
			case 2: // EXPLORAR
				return EXPLORE_DURATION;
			break;
			
			case 3: // INVADIR
				return INVADE_DURATION;
			break;
			
			case 4: // OCUPAR
				return OCCUPY_DURATION;
			break;
		}
	}
}

/* End of file missions_library.php */
/* Location: ./application/libraries/missions_library.php */