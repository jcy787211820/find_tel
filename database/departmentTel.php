<?php
class DepartmentTel extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}department_tel";
	}

	protected function checkFields( $fields )
	{
		foreach( $fields AS $field )
		{
			switch( $field )
			{
				case 'department_tel_seq':				// int(10) UN PK AI
				case 'department_seq':					// int(10) UN
				case 'department_tel_number':			// text
				case 'department_tel_description':		// varchar(100)
					break;
				default:
					error('department_tel_error',"数据字段不存在{$field}");
					return FALSE;
			}
		}
		return TRUE;
	}
}