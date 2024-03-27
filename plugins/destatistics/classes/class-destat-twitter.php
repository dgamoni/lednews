<?php

	class DESTAT_Twitter {

		public function getSharesCountByUrl( $url ) {

			$response = file_get_contents( 'http://urls.api.twitter.com/1/urls/count.json?url=' . urlencode( $url ) );

			$response = json_decode( $response, true );
			if ( $response['count'] ) {
				$count = $response['count'];
			} else {
				$count = 0;
			}

			return $count;
		}
	}