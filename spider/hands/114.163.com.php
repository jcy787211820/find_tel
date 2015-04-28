<?php
trait hand
{
	private $number	= NULL;
	public function __construct()
	{
		$this->number					= $this->_getNumber();
		$this->ch						= curl_init();
		$this->curlopt_url				= "http://114.163.com/company/{$this->number}";
		$this->curlopt_post				= FALSE;
		$this->curlopt_header			= FALSE;
		$this->curlopt_postfields		= array();
		$this->curlopt_nobody			= FALSE;
		$this->curlopt_returntransfer	= TRUE;
		$this->curlopt_timeout			= 60;
	}

	private function _getNumber()
	{
		return file_get_contents(dirname(dirname(__FILE__)) . '/vars/114.163.com.number');
	}

	private function _setNumber()
	{
		return file_put_contents(dirname(dirname(__FILE__)) . '/vars/114.163.com.number', $this->number );
	}

	private function _incrementNumber()
	{
		$this->number++;
		$this->_setNumber();
	}

	public function action()
	{
		$curl_get_data	= $this->curlExec();

		$department	= array();
		if( $this->_isEmpty( $curl_get_data ) == FALSE )
		{
			$department['department_name']					= $this->_matchDepartmentName( $curl_get_data );
			$department['department_address']				= $this->_matchDepartmentAddress( $curl_get_data );
			$department['department_image_url']				= $this->_matchDepartmentImageUrl( $curl_get_data );
			$department['department_base_url']				= $this->_matchDepartmentBaseUrl( $curl_get_data );
			$department['department_description_content']	= $this->_matchDepartmentDescriptionContent( $curl_get_data );
			$department['department_tel_numbers']			= $this->_matchDepartmentTelNumbers( $curl_get_data );
			$department['department_tmp_json']				= $this->_matchDepartmentTmpJson( $curl_get_data );
			$department['department_unique_key']			= md5(json_encode($department));
		}

		$this->_incrementNumber();
		return $department;
	}

	private function _isEmpty( $curl_get_data )
	{
		return preg_match('@<div class="m-blank m-page-none">@siU', $curl_get_data, $match );
	}

	private function _matchDepartmentName( $curl_get_data )
	{
		$department_name	= '';
		if(preg_match('@<h1 class="js-title" title="([^"]+)">@siU', $curl_get_data, $match ))
		{
			$department_name	= $match[1];
		}
		return $department_name;
	}

	private function _matchDepartmentAddress( $curl_get_data )
	{
		$department_address	= '';
		if(preg_match('@<span class="js-address">(.+)</span>@siU', $curl_get_data, $match ))
		{
			$department_address	= $match[1];
		}
		return $department_address;
	}

	private function _matchDepartmentImageUrl( $curl_get_data )
	{
		$department_image_url	= '';
		if(preg_match('@<img style="display:none;" class="js-logo" src="([^"]+)" alt="公司logo" />@siU', $curl_get_data, $match ))
		{
			$department_image_url	= $match[1];
		}
		return $department_image_url;
	}

	private function _matchDepartmentBaseUrl( $curl_get_data )
	{
		$department_base_url	= '';
		if(preg_match('@<a href="([^"]+)" hidefocus="true" target="_blank" class="js-website">@siU', $curl_get_data, $match ))
		{
			$department_base_url	= $match[1];
		}
		return $department_base_url;
	}

	private function _matchDepartmentDescriptionContent( $curl_get_data )
	{
		$department_description_content	= '';
		if(preg_match('@<div id="des" class="intro-text">(.+)</div>\s*<div class="intro-action f-cb">@siU', $curl_get_data, $match ))
		{
			$department_description_content	= $match[1];
		}
		return $department_description_content;
	}


	private function _matchDepartmentTelNumbers( $curl_get_data )
	{
		$department_tel_numbers	= array();
		if(preg_match_all('@<span class="tel js-tel">(.+)</span>@siU', $curl_get_data, $matches ))
		{
			foreach( $matches[1] AS $match )
			{
				$department_tel_numbers[$match]	= '';
			}
		}

		if(preg_match_all('@{name:"([^"]*)",description:"([^"]*)",head:"[^"]*",phone:"([^"]+)",email:"[^"]*"}@siU', $curl_get_data, $matches ))
		{
			foreach( $matches[1] AS $key => $match )
			{
				$tel	= $matches[3][$key];
				$des	= "{$matches[1][$key]} {$matches[2][$key]}";
				$department_tel_numbers[$tel]	= $des;
			}
		}


		return $department_tel_numbers;
	}

	private function _matchDepartmentTmpJson( $curl_get_data )
	{
		$department_tmp_arr	= array();
		if(preg_match_all('@<a href="javascript:void\(0\);" hidefocus="true" class="js-tag-name">(.+)</a>@siU', $curl_get_data, $matches ))
		{
			$department_tmp_arr['cate']	= $matches[1];
		}
		if(preg_match_all('@{name:"[^"]+",description:"[^"]+",head:"[^"]+",phone:"[^"]+",email:"([^"]+)"}@siU', $curl_get_data, $matches ))
		{
			$department_tmp_arr['email']	= $matches[1];
		}
		return json_encode($department_tmp_arr);
	}
}