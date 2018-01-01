<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

// CRUXATA VERSION
define('CRUXATA_VERSION', '- Cruxata v2.1.0 &copy; ' . date ( 'Y' ) . ' -');

// CRUXATA FORUM URL
define('FORUM_URL',		   'http://community.cruxata.com');

// CARPETAS DE LOS TEMPLATES
define('ADMIN_FOLDER',						 'admin/');
define('ACADEMY_FOLDER',				   'academy/');
define('ARMIES_FOLDER',					    'armies/');
define('ARMORY_FOLDER',					    'armory/');
define('BUILDINGS_FOLDER',				 'buildings/');
define('EMPIRE_FOLDER',					    'empire/');
define('FIELD_FOLDER',						 'field/');
define('FRIENDS_FOLDER',				   'friends/');
define('GAME_FOLDER',					      'game/');
define('GLOBAL_FOLDER',						'global/');
define('INDEX_FOLDER',					     'index/');
define('MARKET_FOLDER',						'market/');
define('MESSAGES_FOLDER',				  'messages/');
define('MISSIONS_FOLDER',				  'missions/');
define('OPTIONS_FOLDER',				   'options/');
define('PREMIUM_FOLDER',		 	  	   'premium/');
define('PRODUCTIVITY_FOLDER',		  'productivity/');
define('SEARCH_FOLDER',						'search/');
define('STATISTICS_FOLDER',				'statistics/');
define('TUTORIAL_FOLDER',				  'tutorial/');
define('VERSION_FOLDER',				   'version/');
define('WORKSHOP_FOLDER',				  'workshop/');

// OTRAS CARPETAS
define('CSS_FOLDER',						   'css/');
define('IMG_FOLDER',				           'img/');
define('JS_FOLDER',					            'js/');

// REINOS TAMA�OS EN FEUDOS
define('FRANK',								   '1300'); // KM2=450000 // CONST=346 // ORIG=1300
define('GERMANIC',							   '2240');	// KM2=950000 // CONST=427 // ORIG=2224
define('HUNGARY',							    '880'); // KM2=250000 // CONST=294 // ORIG=850
define('ENGLAND',							    '720'); // KM2=130000 // CONST=181 // ORIG=718
define('PAGE_QUANTITY',							 '20'); // DETERMINA LA CANTIDAD DE FEUDOS POR PAGINA

// RECURSOS PRODUCCION BASE
define('BASE_GOLD',								  '0');
define('BASE_STONE',							 '30');
define('BASE_WOOD',								 '60');

// ESTATUS DE LOS USUARIOS
define('INACTIVE_USER',							  '0');
define('ACTIVE_USER',							  '1');
define('BANNED_USER',							  '2');
define('DELETED_USER',							  '3');

// VALORES FIJOS DE LAS FORMULAS
// EDIFICIOS
// BARRACKS
define('BARRACKS_PRICE_EXP_WOOD',				  '4');
define('BARRACKS_PRICE_MULT_WOOD',				'2.5');
define('BARRACKS_PRICE_EXP_STONE',				  '4');
define('BARRACKS_PRICE_MULT_STONE',				'1.2');
define('BARRACKS_PRICE_EXP_GOLD',				  '4');
define('BARRACKS_PRICE_MULT_GOLD',				'0.6');
define('BARRACKS_TIME_EXP',						  '3');
define('BARRACKS_TIME_MULT',					  '6');
define('BARRACKS_LIMIT_EXP',					  '3');
define('BARRACKS_LIMIT_MULT',					'0.5');
// WATCHTOWER
define('WATCHTOWER_PRICE_EXP',					  '2');
define('WATCHTOWER_TIME_EXP',					  '2');
// FORTIFIELD WALL
define('FORTIFIELDWALL_PRICE_EXP',				  '2');
define('FORTIFIELDWALL_TIME_EXP',				  '2');

// SAWMILL
define('SAWMILL_PRICE_EXP_WOOD',				  '4');
define('SAWMILL_PRICE_MULT_WOOD',			    '1.7');
define('SAWMILL_PRICE_EXP_STONE',				  '4');
define('SAWMILL_PRICE_MULT_STONE',				'0.8');
define('SAWMILL_TIME_EXP',						  '3');
define('SAWMILL_TIME_MULT',					      '3');
define('SAWMILL_PROD_EXP',						  '2');
define('SAWMILL_PROD_MULT',						  '3');
// STONEMINE
define('STONEMINE_PRICE_EXP_WOOD',				  '4');
define('STONEMINE_PRICE_MULT_WOOD',				'2.8');
define('STONEMINE_PRICE_EXP_STONE',				  '4');
define('STONEMINE_PRICE_MULT_STONE',			'0.9');
define('STONEMINE_TIME_EXP',					  '3');
define('STONEMINE_TIME_MULT',					  '4');
define('STONEMINE_PROD_EXP',					  '2');
define('STONEMINE_PROD_MULT',					  '2');
// GOLDMINE
define('GOLDMINE_PRICE_EXP_WOOD',				  '4');
define('GOLDMINE_PRICE_MULT_WOOD',				'3.9');
define('GOLDMINE_PRICE_EXP_STONE',				  '4');
define('GOLDMINE_PRICE_MULT_STONE',				'1.9');
define('GOLDMINE_TIME_EXP',						  '3');
define('GOLDMINE_TIME_MULT',					  '5');
define('GOLDMINE_PROD_EXP',						  '2');
define('GOLDMINE_PROD_MULT',					  '1');
// ACADEMIA
define('ACADEMY_PRICE_EXP',					 	  '1.3');
define('ACADEMY_TIME_EXP',						  '1.4');
// ARSENAL
define('ARMORY_TIME_EXP',						  '4'); // armory_element_time
//define('ARMORY_POWER_EXP',						'1.1'); // armory_element_power
// TALLER
define('WORKSHOP_PRICE_EXP',					  '5');
define('WORKSHOP_TIME_EXP',						  '4');
define('WORKSHOP_POWER_EXP',					'1.1');

// CAMPO - ATAQUES - EJERCITOS
define('ATTACK_DURATION',						'120'); // EN SEGUNDOS
define('EXPLORE_DURATION',						 '60'); // EN SEGUNDOS
define('INVADE_DURATION',						'240'); // EN SEGUNDOS
define('OCCUPY_DURATION',						 '60'); // EN SEGUNDOS
define('MAX_ROUNDS',							 '12');
define('MAX_CAPACITY',							'100');
define('FIELD_ROWS_COLS_MOV_TIME',				 '30'); // EN SEGUNDOS
define('FIELD_KINGDOMS_MOV_TIME',			   '1800'); // EN SEGUNDOS

// TIPOS DE MENSAJES
define('ALL_MESSAGES',							  '0');
define('PRIVATE_MESSAGES',						  '1');
define('ATTACKS_MADE',							  '2');
define('ATTACKS_RECEIVED',						  '3');

// NIVELES DE USUARIO
define('LEVEL_USER',							  '0');
define('LEVEL_ADM',							      '1');

// OTROS
define('MAX_CHARACTERS',                       '2500');
define('MAX_PROD_ROWS',							 '10');
define('POINTS_FACTOR',						   '1000');
define('WATCHTOWER_SECONDS',					 '60');

/* End of file constants.php */
/* Location: ./application/config/constants.php */