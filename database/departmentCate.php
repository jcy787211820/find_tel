<?php
class DepartmentCate extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}department_cate";
	}

	protected function checkFields( $fields )
	{
		foreach( $fields AS $field )
		{
			switch( $field )
			{
				case 'department_cate_seq':		// int(10) UN PK AI
				case 'department_seq':			// int(10) UN
				case 'cate_code':				// char(10)
					break;
				default:
					error('department_cate_error',"数据字段不存在{$field}");
					return FALSE;
			}
		}
		return TRUE;
	}
}