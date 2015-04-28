<?php
function __autoload( $class )
{
	$file	= dirname(__FILE__) . DIRECTORY_SEPARATOR . lcfirst( $class ) . '.php';
	if(is_file( $file ))
	{
		require_once $file;
		return;
	}

	$file	= dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . lcfirst( $class ) . '.php';
	if(is_file( $file ))
	{
		require_once $file;
		return;
	}
}

function init_get( $param = NULL, $default_value = NULL )
{
	if(is_null( $param ))	return $_GET;
	else if(isset( $_GET[$param] )) return $_GET[$param];
	else return $default_value;
}
function init_post( $param = NULL, $default_value = NULL )
{
	if(is_null( $param ))	return $_POST;
	else if(isset( $_POST[$param] )) return $_POST[$param];
	else return $default_value;
}
function init_request( $param = NULL, $default_value = NULL )
{
	if(is_null( $param ))	return $_REQUEST;
	else if(isset( $_REQUEST[$param] )) return $_REQUEST[$param];
	else return $default_value;
}
if(!function_exists( 'array_column' ))
{
	function array_column( $input , $column_key , $index_key = '' )
	{
		$result	= array();
		foreach( $input AS $row )
		{
			if(isset( $row[$index_key] ))	$result[$row[$index_key]]	= $row[$column_key];
			else 							$result[]					= $row[$column_key];
		}
		return	$result;
	}
}

function array_reset_key_to_arr( $input, $key )
{
	$result	= array();
	foreach( $input AS $row )
	{
		if(!isset( $result[$row[$key]] ))	$result[$row[$key]]	= array();
		$result[$row[$key]][]	= $row;
	}
	return $result;
}

function array_reset_key( $input, $key )
{
	$result	= array();
	foreach( $input AS $row )
	{
		$result[$row[$key]]	= $row;
	}
	return $result;
}


function image_format( $image )
{
	if(empty( $image ))								$image	= 'http://image.99phone.cn/base/none176_121.png';
	else if(strpos( $image, 'http://' ) === FALSE )	$image	= "http://{$image}";
	return $image;
}

/**
 * 把字符串转成数组，支持汉字，只能是utf-8格式的
 * @param $str
 * @return array
 */
function stringToArray($str)
{
	$result = array();
	$len = strlen($str);
	$i = 0;
	while($i < $len){
		$chr = ord($str[$i]);
		if($chr == 9 || $chr == 10 || (32 <= $chr && $chr <= 126)) {
			$result[] = substr($str,$i,1);
			$i +=1;
		}elseif(192 <= $chr && $chr <= 223){
			$result[] = substr($str,$i,2);
			$i +=2;
		}elseif(224 <= $chr && $chr <= 239){
			$result[] = substr($str,$i,3);
			$i +=3;
		}elseif(240 <= $chr && $chr <= 247){
			$result[] = substr($str,$i,4);
			$i +=4;
		}elseif(248 <= $chr && $chr <= 251){
			$result[] = substr($str,$i,5);
			$i +=5;
		}elseif(252 <= $chr && $chr <= 253){
			$result[] = substr($str,$i,6);
			$i +=6;
		}
	}
	return $result;
}