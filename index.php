<?php
try
{
	header("Content-Type: text/html; charset=UTF-8");
	
	require_once(dirname(__FILE__) . '/global/error.php');
	require_once(dirname(__FILE__) . '/global/config.php');
	require_once(dirname(__FILE__) . '/global/function.php');
	$router	= new Router();
	$router->load();
	exit;
}
catch(Exception $e)
{
	if(ISDEV)	echo $e->getMessage();
	exit;
}