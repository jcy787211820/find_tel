<?php
class spider
{
	use hand;
	public	$ch						= NULL,
			$curlopt_url			= NULL,
			$curlopt_post			= TRUE,
			$curlopt_header			= FALSE,
			$curlopt_postfields		= array(),
			$curlopt_nobody			= FALSE,
			$curlopt_returntransfer	= TRUE,
			$curlopt_timeout		= 60;

	public function curlExec()
	{
		$need_close_curl	= empty( $this->ch ) ? TRUE : FALSE;
		$this->ch			= empty( $this->ch ) ? curl_init() : $this->ch;

		curl_setopt( $this->ch, CURLOPT_URL, $this->curlopt_url );
		curl_setopt( $this->ch, CURLOPT_POST, $this->curlopt_post );
		curl_setopt( $this->ch, CURLOPT_HEADER, $this->curlopt_header );
		curl_setopt( $this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0' );
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, http_build_query($this->curlopt_postfields));
		curl_setopt( $this->ch, CURLOPT_NOBODY, $this->curlopt_nobody );
		curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, $this->curlopt_returntransfer );
		curl_setopt( $this->ch, CURLOPT_TIMEOUT, $this->curlopt_timeout );
		$response = curl_exec( $this->ch );

		if( $need_close_curl == TRUE )	curl_close( $this->ch );

		return $response;
	}
}