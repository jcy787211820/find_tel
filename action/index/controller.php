<?php
class Index extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$assign_data	= array();
		parent::view( $assign_data, array('layout' => 'blank'));
	}
}
