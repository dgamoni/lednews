<?php

/**
 * Get social counts by facebook
 *
 * @param $deco_social_account_slug
 *
 * @return string
 */
function deco_social_accounts_fb_followers_count() {

	$deco_transient_key = 'deco_social_accounts_fb_followers_count_SamopomichKyiv';

	$deco_followers_count = intval( get_transient( $deco_transient_key ) );

	if ( empty( $deco_followers_count ) ) {
		// Get count followers
		$deco_response = wp_remote_get( "https://graph.facebook.com/SamopomichKyiv", array( 'sslverify' => false ) );
		if ( is_wp_error( $deco_response ) ) {
			return get_option( $deco_transient_key );
		} else {
			$json                 = json_decode( wp_remote_retrieve_body( $deco_response ) );
			$deco_followers_count = intval( $json->likes );
			if ( $deco_followers_count ) {
				// Save count 1day
				set_transient( $deco_transient_key, $deco_followers_count, 60 * 60 * 24 );
				update_option( $deco_transient_key, $deco_followers_count );
			}
		}

	}

	if ( $deco_followers_count >= 1000 && $deco_followers_count < 1000000 ) {
		$deco_followers_count = round( $deco_followers_count / 1000, 1 ) . 'k';
	} elseif ( $deco_followers_count >= 1000000 ) {
		$deco_followers_count = round( $deco_followers_count / 1000000, 1 ) . 'm';
	}

	return $deco_followers_count;

}

//social count widget logic
function deco_social_accounts_twi_followers_count( $deco_social_slug_name ) {

	$deco_transient_key   = 'deco_social_accounts_twi_followers_count_https://twitter.com/SamopomichKyiv';
	$deco_followers_count = 0;
	$deco_followers_count = intval( get_transient( $deco_transient_key ) );
	if ( empty( $deco_followers_count ) ) {
		$deco_response = wp_remote_get( 'https://twitter.com/SamopomichKyiv' );
		preg_match_all( '/<li class="ProfileNav-item ProfileNav-item--followers(.*?)<\/li>/is', $deco_response['body'], $match );
		preg_match_all( '/title="(.*?)"/is', $match[1][0], $match );
		$deco_followers_count = intval( preg_replace( '~[^0-9]+~', '', $match[0][0] ) );
		if ( empty( $deco_followers_count ) ) {
			$deco_followers_count = get_option( $deco_transient_key );
		} else {
			set_transient( $deco_transient_key, $deco_followers_count, 60 * 60 * 24 );
			update_option( $deco_transient_key, $deco_followers_count );
		}
	}
	if ( $deco_followers_count >= 1000 && $deco_followers_count < 1000000 ) {
		$deco_followers_count = round( $deco_followers_count / 1000, 1 ) . 'k';
	} elseif ( $deco_followers_count >= 1000000 ) {
		$deco_followers_count = round( $deco_followers_count / 1000000, 1 ) . 'm';
	}

	return $deco_followers_count;
}

/**
 * @return mixed|string
 */
function deco_social_accounts_youtube_followers_count() {

	$deco_transient_key = 'deco_social_accounts_youtube_followers_count_UC2QATI_ZDHLZERnLW-xQISg';

	$deco_followers_count = intval( get_transient( $deco_transient_key ) );

	if ( empty( $deco_followers_count ) ) {
		$data                 = file_get_contents( "http://gdata.youtube.com/feeds/api/users/UC2QATI_ZDHLZERnLW-xQISg?alt=json" );
		$data                 = json_decode( $data, true );
		$subscribersDetails   = $data['entry']['yt$statistics'];
		$deco_followers_count = intval( $subscribersDetails['subscriberCount'] );

		if ( empty( $deco_followers_count ) ) {
			$deco_followers_count = get_option( $deco_transient_key );
		} else {
			set_transient( $deco_transient_key, $deco_followers_count, 60 * 60 * 24 );
			update_option( $deco_transient_key, $deco_followers_count );
		}
	}

	if ( $deco_followers_count >= 1000 && $deco_followers_count < 1000000 ) {
		$deco_followers_count = round( $deco_followers_count / 1000, 1 ) . 'k';
	} elseif ( $deco_followers_count >= 1000000 ) {
		$deco_followers_count = round( $deco_followers_count / 1000000, 1 ) . 'm';
	}

	return $deco_followers_count;
}
