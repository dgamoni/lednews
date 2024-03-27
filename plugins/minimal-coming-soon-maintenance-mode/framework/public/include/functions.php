<?php

/**
 * Required functions for the plugin.
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

function csmm_render_template( $options ) {

	// Fix for W3 Total Cache plugin
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		ob_end_clean();
		wp_cache_clear_cache();
	}


	// Fix for WP Super Cache plugin
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		ob_end_clean();
		w3tc_pgcache_flush();
	}


	/**
	 * Using the nocache_headers() to ensure that different nocache headers are sent to different browsers.
	 * We don't want any browser to cache the maintainance page.
	 * Also, output buffering is turned on.
	 */
	nocache_headers();
	ob_start();


	// Checking for required options required for the plugin
	if ( empty( $options['title'] ) ) 			:	$options['title'] 			= __( 'Maintainance Mode', 'signals' );				endif;
	if ( empty( $options['input_text'] ) )		:	$options['input_text'] 		= __( 'Enter your email address..', 'signals' ); 	endif;
	if ( empty( $options['button_text'] ) )		:	$options['button_text'] 	= __( 'Subscribe', 'signals' ); 					endif;


	// The template file for the plugin
	if ( '1' == $options['disable_settings'] ) {
		require_once SIGNALS_CSMM_PATH . 'framework/public/views/blank.php';
	} else {
		require_once SIGNALS_CSMM_PATH . 'framework/public/views/html.php';
	}


	ob_flush();
	exit();

}



// To check the referrer
function csmm_check_referrer() {

	// List of crawlers to check for
	$crawlers = array(
		'Abacho'          	=> 	'AbachoBOT',
		'Accoona'         	=> 	'Acoon',
		'AcoiRobot'       	=> 	'AcoiRobot',
		'Adidxbot'        	=> 	'adidxbot',
		'AltaVista robot' 	=> 	'Altavista',
		'Altavista robot' 	=> 	'Scooter',
		'ASPSeek'         	=> 	'ASPSeek',
		'Atomz'           	=> 	'Atomz',
		'Bing'            	=> 	'bingbot',
		'BingPreview'     	=> 	'BingPreview',
		'CrocCrawler'     	=> 	'CrocCrawler',
		'Dumbot' 			=> 	'Dumbot',
		'eStyle Bot'     	=> 	'eStyle',
		'FAST-WebCrawler'	=> 	'FAST-WebCrawler',
		'GeonaBot'       	=> 	'GeonaBot',
		'Gigabot'        	=> 	'Gigabot',
		'Google'         	=> 	'Googlebot',
		'ID-Search Bot'  	=> 	'IDBot',
		'Lycos spider'   	=> 	'Lycos',
		'MSN'            	=> 	'msnbot',
		'MSRBOT'         	=> 	'MSRBOT',
		'Rambler'        	=> 	'Rambler',
		'Scrubby robot'  	=> 	'Scrubby',
		'Yahoo'           	=> 	'Yahoo'
	);


	// Checking for the crawler over here
	if ( csmm_string_to_array( $_SERVER['HTTP_USER_AGENT'], $crawlers ) ) {
		return true;
	}


	return false;

}



// Function to match the user agent with the crawlers array
function csmm_string_to_array( $str, $array ) {

	$regexp = '~(' . implode( '|', array_values( $array ) ) . ')~i';
	return ( bool ) preg_match( $regexp, $str );

}