<?php
class DepartmentTmp extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}department_tmp";
	}

	protected function checkFields( $fields )
	{
		foreach( $fields AS $field )
		{
			switch( $field )
			{
				case 'department_tmp_seq':		// int(10) UN PK AI
				case 'department_seq':			// int(10) UN
				case 'department_tmp_json':		// varchar(2000)
					break;
				default:
					error('department_tmp',"数据字段不存在{$field}");
					return FALSE;
			}
		}
		return TRUE;
	}
}