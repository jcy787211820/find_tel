<?php
trait Phone
{
	private
		$_department_seq,
		$_department_unique_key,
		$_department_name,
		$_department_key_string,
		$_department_address,
		$_department_image_url,
		$_department_base_url,
		$_department_tel_numbers,
		$_department_description_content,
		$_department_tmp_json;

	public function create(array $department )
	{
		/**
		 * @params
		 */
		$this->_department_unique_key			= md5(json_encode( $department ));
		$this->_department_name					= isset( $department['department_name'] ) ? $department['department_name'] : '';
		$this->_department_key_string			= isset( $department['department_key_string'] ) ? $department['department_key_string'] : '';
		$this->_department_address				= isset( $department['department_address'] ) ? $department['department_address'] : '';
		$this->_department_image_url			= isset( $department['department_image_url'] ) ? $department['department_image_url'] : '';
		$this->_department_base_url				= isset( $department['department_base_url'] ) ? $department['department_base_url'] : '';
		$this->_department_tel_numbers			= isset( $department['department_tel_numbers'] ) ? $department['department_tel_numbers'] : '';
		$this->_department_description_content	= isset( $department['department_description_content'] ) ? $department['department_description_content'] : '';
		$this->_department_tmp_json				= isset( $department['department_tmp_json'] ) ? $department['department_tmp_json'] : '';

		/**
		 * transtart
		 * insert ft_search_map
		 * transcomplete
		 */
		Database::transtart();
		try
		{
			$this->_insertDepartment();
			$this->_insertDepartmentDescription();
			$this->_insertDepartmentTel();
			$this->_insertDepartmentTmp();
			$this->_insertSearchInfo();

			Database::trancommit();
		}
		catch(Exception $e)
		{
			Database::tranrollback();
			error('system_error');
		}
	}

	private function _insertDepartment()
	{
		$department	= new Department();
		$department->insert(array(
				'department_unique_key'		=> $this->_department_unique_key,
				'department_name'			=> $this->_department_name,
				'department_key_string'		=> $this->_department_key_string,
				'department_address'		=> $this->_department_address,
				'department_image_url'		=> $this->_department_image_url,
				'department_base_url'		=> $this->_department_base_url,
				'department_insert_time'	=> REQUEST_TIME,
				'department_insert_ip'		=> REQUEST_IP,
				'department_update_time'	=> REQUEST_TIME,
				'department_update_ip'		=> REQUEST_IP,
		));
		$this->_department_seq		= $department->insertId();
	}

	private function _insertDepartmentDescription()
	{
		if(!empty( $this->_department_description_content ))
		{
			$department_description	= new DepartmentDescription();
			$department_description->insert(array(
					'department_seq'					=> $this->_department_seq,
					'department_description_content'	=> $this->_department_description_content,
			));
		}
	}

	private function _insertDepartmentTel()
	{
		$department_tel	= new DepartmentTel();
		foreach( $this->_department_tel_numbers AS $department_tel_number => $department_tel_description )
		{
			$department_tel->insert(array(
					'department_seq'				=> $this->_department_seq,
					'department_tel_number'			=> $department_tel_number,
					'department_tel_description'	=> $department_tel_description,
			));
		}
	}

	private function _insertDepartmentTmp()
	{
		$department_tmp	= new DepartmentTmp();
		$department_tmp->insert(array(
				'department_seq'		=> $this->_department_seq,
				'department_tmp_json'	=> $this->_department_tmp_json,
		));
	}

	private function _insertSearchInfo()
	{
		$search_words	= array_count_values(stringToArray($this->_filterTag($this->_department_name . $this->_department_key_string . $this->_department_address )));

		$search_info	= array();
		foreach( $search_words AS $word	=> $count )
		{
			$search_info[$word]	= "{$this->_department_seq}:{$count};";
		}

		$search		= new Search();

		$existed_search_words	= $search->select(array(
			'where'				=> array("search_word IN ('" . implode( "','", $search->mysql_escape_array(array_keys( $search_words ))) . "')"),
		));

		foreach( $existed_search_words AS $existed_search_word )
		{
			$search_word			= $existed_search_word['search_word'];
			if(isset( $search_info[$search_word] ))
			{
				$search_implode_info	= "{$existed_search_word['search_implode_info']}{$search_info[$search_word]}";
				if(strlen( $search_implode_info ) <= 2000 )
				{
					$search->update(array(
							'set'	=> array( "search_implode_info = '{$search_implode_info}'"),
							'where'	=> array( "search_seq = {$existed_search_word['search_seq']}" ),
					));

					unset( $search_info[$search_word] );
				}
			}
		}

		foreach( $search_info AS $search_word => $search_implode_info )
		{
			$search->insert(array(
					'search_word'			=> $search_word,
					'search_implode_info'	=> $search_implode_info,
			));
		}
	}

	private function _filterTag( $string )
	{
		return str_replace(array(
				' ',
				',',
		), '', $string );
	}
}