<?php

	/*
	Plugin Name: de:statistics
	Plugin URI: http://destatistics.com
	Description: Statistics content from social networks
	Author: destatistics.com
	Author URI: http://destatistics.com
	Version: 0.1
	*/

	require_once 'config.php';


	function destatistics_autoloader_classes( $class ) {

		$file_name = str_replace( '_', '-', strtolower( $class ) );
		if ( ! preg_match( '/destat-/', $file_name, $match ) ) {
			$file_name = 'destat-' . $file_name;
		}

		$file_name = dirname( __FILE__ ) . '/classes/class-' . $file_name . '.php';

		if ( file_exists( $file_name ) ) {
			require_once $file_name;
		}
	}

	spl_autoload_register( 'destatistics_autoloader_classes', true, true );

	$load = new DESTAT_Activation();
	$load = $load->init();

	register_deactivation_hook( __FILE__, array( $load, DESTAT_DEACTIVE ) );
