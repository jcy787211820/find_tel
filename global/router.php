<?php
class Router
{
	/**
	 * var data
	 */
	private		$controller	= NULL,			//Controller file
				$class		= NULL,			//Controller class
				$function	= NULL,			//Controller function
				$params		= NULL;			//Controller params
	
	public function __construct()
	{
		$this->parse();
	}

	public function getController()
	{
		return $this->controller;
	}
	
	public function getFunction()
	{
		return $this->function;
	}

	public function getClass()
	{
		return $this->class;
	}
	
	/**
	 * new controller object
	 */
	public function load()
	{
		require_once $this->controller;
		$controller	= new $this->class();
		$method		= $this->function;
		$params		= $this->params;
		if(method_exists( $controller, $method ) == FALSE ) error('url_exception');
		call_user_func_array(array( $controller, $method ), $params );
	}

	/**
	 * pase request uri set this class property
	 */
	private function parse()
	{
		/*
		 * get URI path
		 */
		$uri_path	= parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );

		/*
		 * unset URI path in request data
		 */
		unset( $_GET[$uri_path], $_POST[$uri_path], $_REQUEST[$uri_path] );

		/*
		 * parse uri
		 */
		$parse_uri	= trim( $uri_path, DIRECTORY_SEPARATOR );
		$parse_uri	= empty( $parse_uri ) == TRUE ?  array( 'index' ) : explode( DIRECTORY_SEPARATOR, $parse_uri );

		/*
		 * set base property
		 */
		$tmp_path	= APPLICATION_DIR;
		foreach( $parse_uri AS $key => $item )
		{
			$tmp_path	.= DIRECTORY_SEPARATOR . strtolower( $item );

			if(is_file( $tmp_path . DIRECTORY_SEPARATOR . 'controller.php' ) == TRUE )
			{
				$this->controller	= $tmp_path . DIRECTORY_SEPARATOR . 'controller.php';
				$this->class		= $item;
				$this->function		= empty( $parse_uri[$key + 1] ) ? 'index' : $parse_uri[$key + 1];
				$this->params		= array_splice( $parse_uri, $key + 2 );
				break;
			}

			if(empty( $parse_uri[$key + 1] ) == FALSE && is_dir( $tmp_path . DIRECTORY_SEPARATOR . strtolower( $parse_uri[$key + 1] )) == TRUE ) continue;
			error('url_exception');
		}
	}
}