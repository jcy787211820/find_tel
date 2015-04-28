<?php
trait hand
{
	private $search_word	= NULL;
	private $search_page	= NULL;
	public $is_empty		= FALSE;
	public function __construct()
	{
		$this->search_word				= NULL;
		$this->search_page				= NULL;
		$this->ch						= curl_init();
		$this->curlopt_url				= "http://www.chadianhua.net/". urlencode("{$this->search_word}_{$this->search_page}");
		$this->curlopt_post				= FALSE;
		$this->curlopt_header			= FALSE;
		$this->curlopt_postfields		= array();
		$this->curlopt_nobody			= FALSE;
		$this->curlopt_returntransfer	= TRUE;
		$this->curlopt_timeout			= 60;
	}

	public function setSearchWord( $word )
	{
		$this->search_word 				= $word;
		$this->curlopt_url				= "http://www.chadianhua.net/". urlencode("{$this->search_word}_{$this->search_page}");
	}

	public function setSearchPage( $page )
	{
		$this->search_page = $page - 1 ;
		$this->curlopt_url	= "http://www.chadianhua.net/". urlencode("{$this->search_word}_{$this->search_page}");
	}

	public function action()
	{
		$curl_get_data	= $this->curlExec();
		var_dump( $curl_get_data );
		echo "\n";
		$departments	= array();
		if( $this->_isEmpty( $curl_get_data ) == FALSE )
		{
			$departments	= $this->_match( $curl_get_data );
			var_dump( $departments );
			echo "\n";
			if(empty( $departments ))
			{
				$error_report						= new errorReport();
				$error_report->insert(array(
						'error_report_title'	=> '自己决定',
						'error_report_content'	=> "{$curl_get_data}\n". json_encode($departments)
				));
				echo "异常.\n";
			}
 		}
 		else
 		{
			$this->is_empty	= TRUE;
 		}
 		return $departments;
	}

	private function _isEmpty( $curl_get_data )
	{
		return preg_match('@电话没找到@siU', $curl_get_data, $match );
	}

// 	$department['department_name']					= $this->_matchDepartmentName( $curl_get_data );
// 	$department['department_address']				= $this->_matchDepartmentAddress( $curl_get_data );
// 	$department['department_image_url']				= $this->_matchDepartmentImageUrl( $curl_get_data );
// 	$department['department_base_url']				= $this->_matchDepartmentBaseUrl( $curl_get_data );
// 	$department['department_description_content']	= $this->_matchDepartmentDescriptionContent( $curl_get_data );
// 	$department['department_tel_numbers']			= $this->_matchDepartmentTelNumbers( $curl_get_data );
// 	$department['department_tmp_json']				= $this->_matchDepartmentTmpJson( $curl_get_data );
// 	$department['department_unique_key']			= md5(json_encode($department));

	private function _match( $curl_get_data )
	{
		$departments	= array();

// 		<dl><dt><a style="float:left;" href="/秦皇岛四川蜀一干锅鸭头电话" onfocus="this.blur();" target="_blank">四川蜀<font color='red'>一干</font>锅鸭头</a></dt><dd>地区：秦皇岛</dd><dd>地址：抚宁县金山大街</dd><dd>电话：0335-6661688&emsp;<a href="/秦皇岛四川蜀一干锅鸭头电话" target="_blank">详细>></a></dd></dl>
		if(preg_match_all('@<dl><dt><a[^>]*>(.*)</a></dt><dd>地区：(.*)</dd><dd>地址：(.*)</dd><dd>电话：(.*)&emsp;<a [^>]*>详细>></a></dd></dl>@siU', $curl_get_data, $matches ))
		{
			foreach( $matches[1] AS $key => $department_name )
			{
				$department_tel_numbers	= array();
				$tmp_tels		= explode( " ", $matches[4][$key] );
				foreach( $tmp_tels AS $tmp_tel );
				{
					$department_tel_numbers[$tmp_tel]	= '';
				}

				$departments[$key]					= array(
						'department_name'			=> strip_tags( $department_name ),
						'department_address'		=> strip_tags( $matches[2][$key] . $matches[3][$key] ),
						'department_tel_numbers'	=> $department_tel_numbers,
						'department_tmp_json'		=> json_encode(array('city' => $matches[2][$key] )),
				);
			}
		}


		return $departments;
	}
}