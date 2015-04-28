<?php
class Search extends Database
{
	public function __construct()
	{
		global $config;

		parent::__construct();
		$this->table	= "{$config['database']['default']['table_prefix']}search";
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
			case 'search_seq':			// int(10) UN PK AI
			case 'search_word':			// char(1)
			case 'search_implode_info':	// varchar(2000);
				break;
			default:
				error('search_error',"数据字段不存在{$field}");
				return FALSE;
		}
		return TRUE;
	}
}