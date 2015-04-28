<?php
class Database
{
	protected static $resource = NULL;
	public $table;
	public function __construct( $database = 'default' )
	{
		$this->mysql_connect( $database );
	}

	public function select( $option )
	{
		if(empty( $option['fields'] )) $option['fields'] = array('*');

		$sql = "
			SELECT
				" . implode(',', $option['fields'] ) . "
			FROM
				`{$this->table}` " .
			(empty( $option['where'] ) ? '' : ' WHERE ' . implode(' AND ', $option['where'] )) .
			(empty( $option['group'] ) ? '' : ' GROUP BY ' . implode(',', $option['group'] )) .
			(empty( $option['order'] ) ? '' : ' ORDER BY ' . implode(',', $option['order'] )) .
			(empty( $option['limit'] ) ? '' : ' LIMIT ' . implode(',', $option['limit'] ));
// echo $sql;
		/*
		 * process
		 */
		$query	= self::$resource->query( $sql );
		if( $query == FALSE )	error('database_error', ISDEV ? $sql : '');

		/*
		 * return
		 */
		$result	= array();
		while( $row = $query->fetch_assoc())
		{
			$result[] = $row;
		}
		return $result;
	}

	public function insert( $data )
	{
		$fields	= array_keys( $data );
		$values	= array_values( $data );

		if($this->checkFields( $fields ) === TRUE)
		{
			$sql	= "
				INSERT INTO {$this->table} (" . implode(",", $fields ) . ")VALUES('" . implode("','", $this->mysql_escape_array( $values )) . "')
			";
			self::$resource->query( $sql );

			if( self::$resource->affected_rows != 1 ) error('database_error', ISDEV ? $sql : '');
		}
	}

	public function update( $option )
	{
		$sql	= "
			UPDATE {$this->table}
			SET	" . implode(',', $option['set']) . "
			WHERE " . implode(',', $option['where']) . "
		";
		self::$resource->query( $sql );

		if( self::$resource->affected_rows != 1 ) error('database_error', ISDEV ? $sql : '');
	}

	/**
	 * get last query primary key.
	 */
	public function insertId()
	{
		return self::$resource->insert_id;
	}


	/**
	 * transaction start
	 */
	public static function transtart()
	{
		self::mysql_connect();
		self::$resource->autocommit(FALSE);
	}

	/**
	 * transaction rollback
	 */
	public static function tranrollback()
	{
		self::$resource->rollback();
		self::$resource->autocommit(TRUE);
	}

	/**
	 * transaction rollback
	 */
	public static function trancommit()
	{
		self::$resource->commit();
		self::$resource->autocommit(TRUE);
	}

	protected static function mysql_connect( $db_key = 'default' )
	{
		global $config;
		if(empty( self::$resource ))
		{
			self::$resource	= new mysqli(
					$config['database'][$db_key]['host'],
					$config['database'][$db_key]['user'],
					$config['database'][$db_key]['password'],
					$config['database'][$db_key]['database'],
					$config['database'][$db_key]['port']
			);
			if(!is_null( self::$resource->connect_error ))	error('database_error', self::$resource->connect_error);
			if( self::$resource->set_charset( $config['database'][$db_key]['charset'] ) == FALSE)	error('database_error', self::$resource->error);
		}
		else
		{
			if( self::$resource->ping() == FALSE ) error('database_error', self::$resource->error);
		}
	}

	public function mysql_escape_array( $data )
	{
		foreach( $data AS $key => $item)
		{
			$data[$key]	= $this->mysql_escape_string( $item );
		}
		return $data;
	}

	protected function mysql_escape_string( $string )
	{
		return self::$resource->real_escape_string( $string );
	}

	protected function checkFields( $fields ){}
}

