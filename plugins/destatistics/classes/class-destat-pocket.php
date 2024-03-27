<?php

	class DESTAT_Pocket {

		public function getSharesCountByUrl( $url ) {

			$content = wp_remote_fopen( "https://widgets.getpocket.com/v1/button?align=center&count=horizontal&label=pocket&url=$url" );
			//<em id="cnt">0</em>
			if ( preg_match( '/<em(.*)>(.*)<\/em>/isU', $content, $match ) ) {
				return $match[2];
			}




			return 0;
		}
	}
