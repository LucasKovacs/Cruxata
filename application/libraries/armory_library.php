<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Armory_library
{
	protected $instance;

	// __construct
	public function __construct()
	{
		$this->instance = &get_instance();
	}

	// RETORNA EL PRECIO DE UN ELEMENTO DE LA ARMERIA
	public function armory_element_price ( $weapon , $level )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->price["data"] + pow ( $level , $this->armory_element_price_factor ( $weapon ) );
		}
	}
	
	// RETORNA EL FACTOR DEL PRECIO DE UN ELEMENTO DE LA ARMERIA
	public function armory_element_price_factor ( $weapon )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->price_factor["data"];
		}
	}

	// RETORNA EL TIEMPO DE UN ELEMENTO DE LA ARMERIA
	public function armory_element_time ( $weapon , $level )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->time["data"] + pow ( $level , ARMORY_TIME_EXP );
		}
	}

	// RETORNA EL PODER DE UN ELEMENTO DE LA ARMERIA
	public function armory_element_power ( $weapon , $level )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return  $element[0]->power["data"] * $level;
//			return  $element[0]->power["data"] + pow ( $level , ARMORY_POWER_EXP );
		}
	}

	// RETORNA EL TIPO DE UN ELEMENTO DE LA ARMERIA
	public function armory_element_type ( $weapon )
	{
		$element 	= $this->instance->xml->get_xml_data ( $weapon );

		if ( $element !== NULL )
		{
			// EXTRAEMOS LA INFORMACION Y RETORNAMOS EL VALOR
			return $element[0]->type["data"];
		}
	}
}

/* End of file armory_library.php */
/* Location: ./application/libraries/armory_library.php */