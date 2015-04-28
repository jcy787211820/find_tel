<?php
define('ISDEV', 			TRUE );
define('REQUEST_TIME',		$_SERVER['REQUEST_TIME'] );
if(defined( 'REQUEST_IP' ) == FALSE ) define('REQUEST_IP',		$_SERVER['REMOTE_ADDR'] );
define('APPLICATION_DIR',	$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'action/' );
define('LAYOUT_DIR',		$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'layout/' );
define('IMAGE_ROOT',		'/www/99phone_image/');
define('IMAGE_BASE_URI',	'http://image.99phone.cn/');

/**
 * ajax request check
 */
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) == TRUE && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )	define('ISAJAX', TRUE );
else	define('ISAJAX', FALSE );


$config						= array();
$config['login_cookie']		= 'login_cookie';

$config['database']				= array();
$config['database']['default']	= array(
		'host'					=> '127.0.0.1',
		'port'					=> '3306',
		'user'					=> 'root',
		'password'				=> 'root',
		'database'				=> 'find_tel',
		'charset'				=> 'utf8',
		'table_prefix'			=> 'ft_',
);
