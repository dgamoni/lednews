<?php

class DESTAT_Linkedin {

	public function getSharesCountByUrl( $url ) {

		return json_decode( str_replace( array(
			'IN.Tags.Share.handleCount(',
			');'
		), '', file_get_contents( 'http://www.linkedin.com/countserv/count/share?url=' . urlencode( $url ) ) ) )->count;

	}
}