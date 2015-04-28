<?php
class ErrorReport extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}error_report";
	}

	protected function checkFields( $fields )
	{
		foreach( $fields AS $field )
		{
			if($this->checkField( $field ) == FALSE ) return FALSE;
		}
		return TRUE;
	}

	protected function checkField( $field )
	{
		switch( $field )
		{
			case 'error_report_seq':			// int(10) UN PK AI
			case 'error_report_type':			// tinyint(3) UN
			case 'error_report_title':			// varchar(100)
			case 'error_report_content':		// text
			case 'error_report_process_flag':	// enum('T','F')
			case 'error_report_insert_time':	// int(10) UN
			case 'error_report_insert_ip':		// varchar(15)
				break;
			default:
				error('search_error',"数据字段不存在{$field}");
				return FALSE;
		}
		return TRUE;
	}
}