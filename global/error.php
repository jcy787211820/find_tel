<?php
function error( $error_key, $error_message = '' )
{
	$error	= array();

	/*
	 * system error code
	 */
	$error['url_exception']		= array('code'=>'000001','message'=>'您访问的URL有异常.');
	$error['database_error']	= array('code'=>'000002','message'=>'数据库发生异常.');
	$error['system_error']		= array('code'=>'000002','message'=>'系统异常.');

	/*
	 * database class error
	 */
	$error['department_error']				= array('code'=>'100001','message'=>'公司异常.');
	$error['department_cate_error']			= array('code'=>'100002','message'=>'公司分类映射异常.');
	$error['department_description_error']	= array('code'=>'100003','message'=>'公司描述异常.');
	$error['department_tel_error']			= array('code'=>'100004','message'=>'公司电话异常.');
	$error['department_tmp']				= array('code'=>'100005','message'=>'公司临时数据异常.');
	$error['search_error']					= array('code'=>'100101','message'=>'搜索异常.');



	/*
	 * trait class error
	 */
	$error['phone_error']					= array('code'=>'200001','message'=>'电话操作异常.');


	/*
	 * controller error
	 */
	$error['post_error']					= array('code'=>'300001','message'=>'信息发布错误.');

	/*
	 * user message
	*/
	$error['user_message']					= array('code'=>'999999','message'=>'');


	/*
	 * print error
	 */
	if(ISAJAX == TRUE)
	{
		exit(json_encode(array(
				'is_error' => TRUE,
				'error_message' => "错误：[编号]{$error[$error_key]['code']} [错误描述]{$error[$error_key]['message']} {$error_message}"
		)));
	}
	else
	{
		exit( "错误：[编号]{$error[$error_key]['code']} [错误描述]{$error[$error_key]['message']} {$error_message}\n" );
	}
}
