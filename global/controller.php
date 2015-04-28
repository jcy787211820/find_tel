<?php
class Controller
{
	private	$base_assign_data		= array(	// base assign_data
					'title'			=> '',		// meta title
					'keywords'		=> '',		// meta keywords
					'description'	=> '',		// meta description
					'top'			=> '',		// layout top
					'left'			=> '',		// layout left
					'right'			=> '',		// layout right
					'foot'			=> '',		// layout footer
					'js'			=> '',		// view js
					'css'			=> '',		// view css
	);
	/**
	 * Contruct
	 */
	public function __construct(){}

	/**
	 * get view page
	 * @param (array) $assign_data
	 * @param (int) $expires
	 */
	public function view( $assign_data = array(), $option = array('layout'=>'default','path'=> null ))
	{
		global $router;

		$assign_data	= array_merge( $assign_data, $this->base_assign_data);

		/*
		 * set view page var data
		*/
		foreach( $assign_data AS $key => $data )
		{
			$$key	= $data;
		}


		/*
		 * set view page related layout file
		 */
		$parse_request_uri_arr	= explode( DIRECTORY_SEPARATOR, $_SERVER['REQUEST_URI'] );
		$left					= LAYOUT_DIR . 'html/left.html';
		$top					= LAYOUT_DIR . 'html/top.html';
		$foot					= LAYOUT_DIR . 'html/foot.html';
		$js_file				= dirname( $router->getController() ) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $router->getFunction() . '.js';
		$css_file				= dirname( $router->getController() ) . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $router->getFunction() . '.css';


		ob_start();

		if(is_file( $left ))
		{
			ob_start();
			include $left;
			$left		= ob_get_clean();
		}

		if(is_file( $top ))
		{
			ob_start();
			include $top;
			$top		= ob_get_clean();
		}

		if(is_file( $foot ))
		{
			ob_start();
			include $foot;
			$foot		= ob_get_clean();
		}


		/*
		 * set js content
		 */
		if(is_file( $js_file ))
		{
			ob_start();
			include $js_file;
			$js		= ob_get_clean();
		}

		/*
		 * set css content
		 */
		if(is_file( $css_file )) $css	= file_get_contents( $css_file );

		/*
		 * output
		 */
		ob_start();
		$view	= dirname( $router->getController() ) . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . $router->getFunction() . '.html';
		if(is_file( $view ))	include $view;

		if(!empty( $option['layout'] ))
		{
			$view	= ob_get_clean();
			$layout	= LAYOUT_DIR . "{$option['layout']}.html";
			if(is_file( $layout ))	include $layout;
		}

		while (ob_get_level() > 0)
		{
            ob_end_flush();
        }
	}
}
