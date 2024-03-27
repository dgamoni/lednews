<?php

	global $wpdb;

	$wpdb->de_statistics = $wpdb->base_prefix . 'de_statistics';

	define( 'DESTAT_PLUGIN_PATH', dirname( __FILE__ ) );
	define( 'DESTAT_PLUGIN_FOLDER', basename( DESTAT_PLUGIN_PATH ) );
	define( 'DESTAT_TEXTDOMAIN', 'destatistics' );
	define( 'DESTAT_SLUG', 'destatistics' );

	define( 'DESTAT_PLUGIN_URL', WP_PLUGIN_URL . '/' . DESTAT_PLUGIN_FOLDER );
	define( 'DESTAT_ASSETS_URL', DESTAT_PLUGIN_URL . '/assets' );
	define( 'DESTAT_IMAGES_URL', DESTAT_PLUGIN_URL . '/assets/img' );
	define( 'DESTAT_COMPONENTS_URL', DESTAT_PLUGIN_URL . '/components' );

	define( 'DESTAT_COMPONENTS_PATH', DESTAT_PLUGIN_PATH . '/components' );


	define( 'DESTAT_LANG_DOMAIN', DESTAT_PLUGIN_FOLDER );

	//define( 'DESTAT_ACTIVATION_DOMAIN', 'http://destatistics.com' );
	define( 'DESTAT_PRODUCT_ID', 'destat' );

	define( 'DESTAT_STATISTICS', base64_decode( 'RGVzdGF0aXN0aWNz' ) );
	define( 'DESTAT_ACTIVE', base64_decode( 'YWN0aXZhdGVQbHVnaW4=' ) );
	define( 'DESTAT_DEACTIVE', base64_decode( 'ZGVhY3RpdmF0ZVBsdWdpbg==' ) );
