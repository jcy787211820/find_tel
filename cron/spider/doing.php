<?php
//	php /www/find_tel/cron/spider/doing.php dianping.com.php
define( 'HAND', $argv[1] );
define( 'REQUEST_IP', '127.0.0.1' );
define( 'DOCUMENT_ROOT', '/www/find_tel/' );
define( 'IMAGE_PATH', '/www/99phone_image/department_image' );

require_once( DOCUMENT_ROOT . 'global/error.php');
require_once( DOCUMENT_ROOT . 'global/config.php');
require_once( DOCUMENT_ROOT . 'global/function.php');
require_once( DOCUMENT_ROOT . 'trait/phone.php');
require_once( DOCUMENT_ROOT . 'spider/hands/' . HAND );
require_once( DOCUMENT_ROOT . '/spider/spider.php');

class process
{
	use Phone;
	public function __construct()
	{
		$spider			= new spider();
		$department		= new Department();

		$spider_department	= $spider->action();

		if(!empty( $spider_department ) && $department->isExisted( $spider_department['department_unique_key'] ) == FALSE )
		{
			var_dump( $spider_department );
			if(!empty( $spider_department['department_image_url'] ))
			{
				$tmp_image_name			= sha1(
						$spider_department['department_image_url'] . time()) . '.' . pathinfo( $spider_department['department_image_url'], PATHINFO_EXTENSION
				);
				if(file_put_contents ( IMAGE_PATH . $tmp_image_name , file_get_contents( $spider_department['department_image_url'] )) > 0 )
				{
					$spider_department['department_image_url']	= "http://image.99phone.cn/department_image/{$tmp_image_name}";
				}
			}

			$this->create( $spider_department );
		}
	}
}
new process();
