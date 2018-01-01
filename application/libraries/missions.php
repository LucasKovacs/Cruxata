<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Missions
{
	private $ci;
	private $attacker;
	private $enemy;

	// __construct
	public function __construct()
	{
		$this->ci = &get_instance();
	}

	// MISIÓN: ATACAR
	// ATACAMOS A UN OPONENTE PARA ROBARLE RECURSOS
	private function mission_attack ()
	{
	}
	
	// MISIÓN: EXPLORAR
	// REVISAMOS QUE TIENE UN OPONENTE Y OBTENEMOS UN REPORTE
	private function mission_explore ()
	{
	}
	
	// MISIÓN: INVADIR
	// INVADIMOS UN FEUDO OCUPADO PARA OBTENER UNA RENTA DE OTRO JUGADOR
	private function mission_invade ()
	{
	}
	
	// MISIÓN: OCUPAR
	// OCUPAMOS UN FEUDO VACIO PARA EXPLOTAR
	private function mission_occupy ()
	{
	}
}

/* End of file missions.php */
/* Location: ./application/libraries/missions.php */