<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['base_url']					= 'http://localhost/webservice/';
$config['index_page'] 				= '';

/* Adicionados */

//$config['application'] 			= $GLOBALS['application_default'];

$config['base_path'] 				= $GLOBALS['base_path'];
$config['full_path'] 				= $GLOBALS['application_default']."/";
$config['full_path_views'] 			= $config['full_path']."views/";
$config['full_path_models'] 		= $config['full_path']."models/";
$config['full_path_controllers'] 	= $config['full_path']."controllers/";
$config['domain'] 					= $GLOBALS['domain'];
$config['base_url']					= $GLOBALS['domain'].$config['base_path'].$config['full_path'];
$config['application'] 				= $GLOBALS['application_default'];

 /* Adicionados */


$config['uri_protocol']				= 'AUTO';

$config['url_suffix'] 				= '';

$config['language']					= 'english';

$config['charset'] 					= 'UTF-8';

$config['enable_hooks'] 			= FALSE;

$config['subclass_prefix'] 			= 'MY_';

$config['permitted_uri_chars'] 		= 'a-z 0-9~%.:_\-';


$config['allow_get_array']			= TRUE;
$config['enable_query_strings'] 	= FALSE;
$config['controller_trigger']		= 'c';
$config['function_trigger']			= 'm';
$config['directory_trigger']		= 'd'; // experimental not currently in use

$config['log_threshold'] 			= 0;

$config['log_path'] 				= '';
$config['log_date_format'] 			= 'Y-m-d H:i:s';

$config['cache_path'] 				= '';

$config['encryption_key'] 			= '73ac4b495703080def5dcb254912f63b'; //md5 2idea

$config['sess_cookie_name']			= 'ci_session';
$config['sess_expiration']			= 7200;
$config['sess_expire_on_close']		= FALSE;
$config['sess_encrypt_cookie']		= FALSE;
$config['sess_use_database']		= FALSE;
$config['sess_table_name']			= 'ci_sessions';
$config['sess_match_ip']			= FALSE;
$config['sess_match_useragent']		= TRUE;
$config['sess_time_to_update']		= 300;

$config['cookie_prefix']			= "";
$config['cookie_domain']			= "";
$config['cookie_path']				= "/";
$config['cookie_secure']			= FALSE;

$config['global_xss_filtering'] 	= FALSE;

$config['csrf_protection'] 			= FALSE;
$config['csrf_token_name'] 			= 'csrf_test_name';
$config['csrf_cookie_name'] 		= 'csrf_cookie_name';
$config['csrf_expire'] 				= 7200;

$config['compress_output'] 			= FALSE;

$config['time_reference'] 			= 'local';

$config['rewrite_short_tags'] 		= FALSE;

$config['proxy_ips'] 				= '';

