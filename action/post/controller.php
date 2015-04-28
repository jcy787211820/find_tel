<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/trait/phone.php');
class Post extends Controller
{
	use Phone;

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$assign_data	= array();
		parent::view( $assign_data );
	}

	public function action()
	{
		$assign_data	= array('is_error' => TRUE, 'error_message' => '未知原因错误.' );

		$department_name					= init_post( 'department_name' );
		$department_tel						= init_post( 'department_tel' );
		$department_base_url				= init_post( 'department_base_url' );
		$department_address					= init_post( 'department_address' );
		$department_description_content		= init_post( 'department_description_content' );
		$department_image_url				= '';
		$department_unique_key				= md5('/post/action/' . time());

		if(strlen(trim( $department_name )) == 0 ) error('post_error','请输入公司名.');
		else if(strlen(trim( $department_name )) > 45 ) error('post_error','公司名不能超过45个字符.');

		if(strlen(trim( $department_address )) == 0 ) error('post_error','请输入公司地址.');
		else if(strlen(trim( $department_address )) > 45 ) error('post_error','公司地址不能超过200个字符.');

		if(strlen(trim( $department_tel )) == 0 ) error('post_error','请输入公司电话.');

		$department_tel_numbers				= array();
		foreach(explode( ',',(string) $department_tel ) AS $tel )
		{
			if(strlen( $tel ) > 45 ) error('电话号码错误,请确认.');
			$department_tel_numbers[$tel]	= '';
		}

		if(!empty( $_FILES['department_logo']['size'] ))
		{
			$upload	= new Upload();
			$upload->setProperty('upload_field','department_logo');
			$upload->setProperty('upload_dir', IMAGE_ROOT . DIRECTORY_SEPARATOR . 'department_image');
			$upload->setProperty('upload_base_uri', IMAGE_BASE_URI . DIRECTORY_SEPARATOR . 'department_image');
			$upload->setProperty('upload_limit_size', 2096552);
			$upload->setProperty('upload_limit_extensions', array('jpg','jpeg','gif','bmp','png'));
			$upload_infos	= $upload->doAction();
			if( $upload_infos['error'] == TRUE ) error( 'post_error', $upload_infos['message'] );
			$department_image_url	= $upload_infos['data']['upload_uri'];
		}

		$this->create(array(
				'department_unique_key'				=> $department_unique_key,
				'department_name'					=> $department_name,
				'department_address'				=> $department_address,
				'department_image_url'				=> $department_image_url,
				'department_base_url'				=> $department_base_url,
				'department_tel_numbers'			=> $department_tel_numbers,
				'department_description_content'	=> $department_description_content,
		));

		header("Location: /find?search_word={$department_name}");
	}
}
