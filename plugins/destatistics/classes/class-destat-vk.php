<?php

	class DESTAT_Vk {

		public function getSharesCountByUrl( $url ) {

			//			$log     = new Destatistics();
			//			$oUniLog = $log->getLog();

			//			$oUniLog->logDebug( 'Vk gathering shares!' );
			if ( ! ( $request = file_get_contents( 'http://vkontakte.ru/share.php?act=count&index=1&url=' . $url ) ) ) {
				return false;
			}
			$count = array();
			if ( ! ( preg_match( '/^VK.Share.count\((\d+), (\d+)\);$/i', $request, $count ) ) ) {
				return 0;
			}

			return $count[2];
		}
	}