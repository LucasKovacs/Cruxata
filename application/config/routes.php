<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] 	= "index";
$route['404_override'] 			= '';

// DEFINIDAS PARA CRUXATA - SIRVEN COMO URLS ESTÁTICAS
$route['lost-password']					= 'index/lost_password';
$route['register']						= 'index/register';
$route['credits']						= 'index/credits';
$route['rules']							= 'index/rules';
$route['terms-and-conditions']			= 'index/terms_and_conditions';
$route['banned']						= 'index/banned';
$route['community']						= 'index/community';
$route['version']						= 'index/version';
$route['logout']						= 'index/logout';
$route['production']					= 'buildings/index/production';
$route['infrastructure']				= 'buildings/index/infrastructure';
$route['validate-account/(:any)']		= 'index/validate_account/$1';
$route['missions/(:any)']				= 'missions/index/$1';
$route['armies/cancel/(:num)']			= 'armies/cancel_attack/$1';
$route['field/(:any)']					= 'field/index/$1';
$route['private-message/(:any)']		= 'messages/private_message/$1';
$route['show-message/(:num)/(:num)']	= 'messages/show_message/$1/$2';
$route['delete-message/(:num)']			= 'messages/delete_message/$1';
$route['mark-read/(:num)']				= 'messages/mark_read/$1';
$route['market/(:any)']					= 'market/index/$1/';
$route['recruit/(:any)']				= 'recruit/index/$1';
$route['tutorial/(:any)']				= 'tutorial/index/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */