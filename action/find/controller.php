<?php
class Find extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$assign_data	= array('is_error' => TRUE, 'error_message' => '未知原因错误.' );

		$search_word	= init_get( 'search_word' );
		$page			= init_get( 'page', 1 );

		$list_length	= 15;
		$list_start		= ($page - 1) * $list_length;

		if(strlen( $search_word ) == 0 ) error('user_message','请输入查询关键词.');

		$search			= new Search();
		$search_datas	= $search->select(array(
				'fields'	=> array('search_implode_info'),
				'where'		=> array("search_word IN('" . implode("','", $search->mysql_escape_array(stringToArray( $search_word ))) . "')"),
		));

		if(empty( $search_datas )) error('user_message','对不起，你要找的电话号码不存在.');


		$department			= new Department();

		$sort_value	= array();
		foreach( $search_datas AS $search_data )
		{
			$search_info	= explode( ";", trim( $search_data['search_implode_info'], ';' ));
			foreach( $search_info AS $search_department_info )
			{
				list( $department_seq, $department_search_word_sum )	=  explode(':', $search_department_info);
				if(!isset(	$sort_value[$department_seq] ))	$sort_value[$department_seq]	= array( 'sum' => 0, 'weight' => 0 );

				$sort_value[$department_seq]['sum']		+= $department_search_word_sum;
				$sort_value[$department_seq]['weight']	+= 10;
			}
		}
		$sort_field			= "CASE";
		foreach( $sort_value AS $department_seq => $value )
		{
			$sort_field	.= "
				WHEN department_seq = {$department_seq} THEN {$value['weight']} + {$value['sum']}
			";
		}
		$sort_field		.= "END AS sort_value";

		$department_seqs	= array_keys( $sort_value );

		$department_lists	= $department->select(array(
				'fields'	=> array(
									'department_seq',
									'department_name',
									'department_address',
									'department_image_url',
									'department_base_url',
									$sort_field,
								),
				'where'		=> array(
									"department_seq IN('" . implode( "','", $department->mysql_escape_array( $department_seqs )) . "')",
								),
				'order'		=> array(
									'sort_value DESC',
									'department_seq DESC',
								),
				'limit'		=> array(
									$list_start,
									$list_length
								),
		));

		$department_seqs		= array_column( $department_lists, 'department_seq' );
		$department_tel			= new DepartmentTel();
		$department_tel_lists	= array_reset_key_to_arr($department_tel->select(array(
				'fields'	=> array(
						'department_seq',
						'department_tel_number',
						'department_tel_description',
				),
				'where'		=> array(
						"department_seq IN('" . implode( "','", $department->mysql_escape_array( $department_seqs )) . "')",
				),
		)), 'department_seq' );


		$department_description			= new DepartmentDescription();
		$department_description_lists	= array_reset_key($department_description->select(array(
				'fields'	=> array(
						'department_seq',
						'department_description_content',
				),
				'where'		=> array(
						"department_seq IN('" . implode( "','", $department->mysql_escape_array( $department_seqs )) . "')",
				),
		)), 'department_seq' );



		foreach( $department_lists AS $key => $department_list )
		{
			$department_seq												= $department_list['department_seq'];
			$department_lists[$key]['department_tels']					= isset( $department_tel_lists[$department_seq] ) ? $department_tel_lists[$department_seq] : '';
			$department_lists[$key]['department_description_content']	= isset( $department_description_lists[$department_seq]['department_description_content'] ) ? $department_description_lists[$department_seq]['department_description_content'] : '';
		}


		$assign_data['search_word']			= $search_word;
		$assign_data['department_lists']	= $department_lists;
		$assign_data['is_error']			= FALSE;

		parent::view( $assign_data );
	}

	public function log()
	{
		$assign_data	= array('is_error' => TRUE, 'error_message' => '未知原因错误.' );

		$search_word					= init_post( 'search_word' );
		$search_log						= new SearchLog();
		$search_log->insert(array(
				'search_log_word'			=> $search_word,
				'search_log_insert_time'	=> REQUEST_TIME,
				'search_log_insert_ip'		=> REQUEST_IP,
		));
		$assign_data['is_error']			= FALSE;

		echo json_encode( $assign_data );
	}
}
