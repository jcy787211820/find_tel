<?php
class Department extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}department";
	}

	public function isExisted( $department_unique_key )
	{
		return count($this->select(array(
				'fields'	=> array(1),
				'where'		=> array("department_unique_key = '{$department_unique_key}'"),
				'limit'		=> array(1),
		))) > 0 ;
	}

	protected function checkFields( $fields )
	{
		foreach( $fields AS $field )
		{
			switch( $field )
			{
				case 'department_seq':				// int(10) UN PK AI
				case 'department_unique_key':		// char(32) UN
				case 'department_name':				// varchar(45)
				case 'department_key_string':		// varchar(45)
				case 'department_address':			// varchar(200)
				case 'department_image_url':		// varchar(200)
				case 'department_base_url':			// varchar(100)
				case 'department_insert_time':		// int(10) UN
				case 'department_insert_ip':		// varchar(15)
				case 'department_update_time':		// int(10) UN
				case 'department_update_ip':		// varchar(15)
				case 'department_update_user_id':	// varchar(45)
					break;
				default:
					error('department_error',"数据字段不存在{$field}");
					return FALSE;
			}
		}
		return TRUE;
	}
}