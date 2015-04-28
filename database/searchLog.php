<?php
class SearchLog extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}search_log";
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
			case 'search_log_seq':			// int(10) UN PK AI
			case 'search_log_word':			// varchar(200)
			case 'search_log_insert_time':	// int(10) UN
			case 'search_log_insert_ip':	// varchar(15)
				break;
			default:
				error('search_error',"数据字段不存在{$field}");
				return FALSE;
		}
		return TRUE;
	}
}