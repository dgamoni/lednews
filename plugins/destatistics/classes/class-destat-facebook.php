<?php

	class DESTAT_Facebook {

		public function getSharesCountByUrl( $url ) {

			$count = @json_decode( file_get_contents( 'http://graph.facebook.com/' . urlencode( $url ) ) )->shares;
			if ( $count ) {
				return $count;
			} else {
				return 0;
			}

		}
	}