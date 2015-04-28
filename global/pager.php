<?php
class Pager
{
	private	$_mode			= 'default',
			$_current_page	= 1;
	public function __construct( $option )
	{
		if(isset( $option['mode'] ))			$this->_mode			= $option['mode'];
		if(isset( $option['current_page'] ))	$this->_current_page	= $option['current_page'];

		switch( $this->_mode )
		{
			case 'simple':
					$this->_simple();
				break;
			default:
				$this->_default();

		}
	}

	private function _simple()
	{
		$url		= preg_replace( '@&page=\d*$@siU', '', $_SERVER['REQUEST_URI'] ) . '&page=';
		echo '
			<div class="pager">
				' . ( $this->_current_page > 1 ? '<a href="' . $url . ( $this->_current_page - 1 ) . '">上一页</a>' : '' ) . '
				<a href="' . $url . ( $this->_current_page + 1 ) . '">下一页</a>
			</div>
		';
	}
}