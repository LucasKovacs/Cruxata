<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshop_library
{
	protected $instance;

	// __construct
	public function __construct()
	{
		$this->instance = &get_instance();
	}

	// RETORNA EL PRECIO DE UN ELEMENTO DEL TALLER
	public function workshop_element_wood ( $weapon )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->wood["data"];
		}
	}

	// RETORNA EL PRECIO DE UN ELEMENTO DEL TALLER
	public function workshop_element_stone ( $weapon )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->stone["data"];
		}
	}
	
	// RETORNA EL PRECIO DE UN ELEMENTO DEL TALLER
	public function workshop_element_gold ( $weapon )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->gold["data"];
		}
	}

	// RETORNA EL TIEMPO DE UN ELEMENTO DEL TALLER
	public function workshop_element_time ( $weapon )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->time["data"];
		}
	}

	// CHEQUEA SI EL VALOR QUE SE LE PASA ES UN ARMA DEL TALLER O NO
	public function is_weapon ( $weapon )
	{
		$weapon		= str_replace ( 'workshop_' , '' , $weapon );
		$element 	= $this->instance->xml->get_xml_data ( 'workshop_' . $weapon );

		if ( $element !== NULL )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file workshop_library.php */
/* Location: ./application/libraries/workshop_library.php */