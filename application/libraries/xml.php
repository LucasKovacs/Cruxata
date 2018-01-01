<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xml
{
	private $_path;
	private $_config;

	public function __construct()
	{
        $this->_path 	= '.' .  DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'cruxata-values.xml';
        $this->_config 	= simplexml_load_file ( $this->_path );
		
		// ERROR AL LEER LA HOJA XML
		// ERROR
		if ( $this->_config === FALSE )
		{
			die ( 'Error nº1: Problems to read the page content.' );
		}
	}

	// EXTRAE LA INFORMACION DEL XML
	public function get_xml_data ( $element )
	{
		$current	= $this->_config->xpath ( "/cruxata/elements/" . $element );

		// ERROR DE LECTURA DEL ELEMENTO
		if ( count ( $current ) == 0 )
		{
			return NULL;
		}
		else
		{
			return $current;
		}
	}
}

/* End of file xml.php */
/* Location: ./application/libraries/xml.php */