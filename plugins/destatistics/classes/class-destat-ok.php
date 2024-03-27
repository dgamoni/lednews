<?php

	/**
	 * class DESTAT_Od
	 * class DESTAT_work with api Odnoklassniki
	 */
	class DESTAT_Ok {

		/*
		 * $url - url post
		 */
		public function getSharesCountByUrl( $url ) {

			if ( ! ( $request = file_get_contents( 'http://www.odnoklassniki.ru/dk?st.cmd=extOneClickLike&uid=odklocs0&ref=' . $url ) ) ) {
				return 0;
			}
			$count = array();
			if ( ! ( preg_match( "/^ODKL.updateCountOC\('[\d\w]+','(\d+)','(\d+)','(\d+)'\);$/i", $request, $count ) ) ) {
				return false;
			}

			return $count[1];
		}
	}