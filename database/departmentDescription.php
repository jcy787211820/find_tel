<?php
class DepartmentDescription extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}department_description";
	}

	protected function checkFields( $fields )
	{
		foreach( $fields AS $field )
		{
			switch( $field )
			{
				case 'department_description_seq':		// int(10) UN PK AI
				case 'department_seq':					// int(10) UN
				case 'department_description_content':	// text
					break;
				default:
					error('department_description_error',"数据字段不存在{$field}");
					return FALSE;
			}
		}
		return TRUE;
	}
}