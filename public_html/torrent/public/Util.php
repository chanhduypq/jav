<?php
// First utility file used in main class:
function getSearchResult ( $url )
{

	$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER         => false,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING       => '',
		CURLOPT_AUTOREFERER    => true,
		CURLOPT_MAXREDIRS      => 2,
		CURLOPT_SSL_VERIFYPEER => false // if making https req and do not care of ssl/https certificate then set it off
	);

	$ch  = curl_init( $url );

	curl_setopt_array( $ch, $options );

	$content	= curl_exec( $ch );
	$err		= curl_errno( $ch );
	$errmsg		= curl_error( $ch );
	$info		= curl_getinfo( $ch );

	curl_close( $ch );

	$output['errno']   = $err;
	$output['errmsg']  = $errmsg;
	$output['content'] = $content;

	return $output;

}

?>