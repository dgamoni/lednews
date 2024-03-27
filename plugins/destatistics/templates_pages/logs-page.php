<h3>Log (today only log):</h3>
<pre class="log_wrapper">
<?php
	$aResponse = wp_remote_get( plugins_url( 'de-postrating/log/log_' . date( 'Y-m-d', time() ) . '.txt' ) );
	if ( $aResponse['response']['code'] != '404' ) {
		$sLogFile = wp_remote_retrieve_body( $aResponse );
		print $sLogFile;
	} else {
		echo 'Sorry, no logs for today';
	}
?>
</pre>
