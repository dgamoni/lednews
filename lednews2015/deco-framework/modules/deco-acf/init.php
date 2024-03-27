<?php

//define( 'ACF_LITE', true );
define( "OPTIONS_ICON", 'dashicons-admin-settings' );
define( "OPTIONS_NAME", 'Настройки темы' );

$current_dir = dirname( __FILE__ ) . '/';


require_once $current_dir . 'advanced-custom-fields/acf.php';
require_once $current_dir . 'addons/acf-repeater/acf-repeater.php';
require_once $current_dir . 'addons/acf-options-page/acf-options-page.php';
//include_once $current_dir . 'addons/acf-field-date-time-picker-master/acf-date_time_picker.php';
require_once $current_dir . 'addons/acf-gallery/acf-gallery.php';

if ( function_exists( "register_field_group" ) ) {
	$dirs = array(
		//DECO_FRAMEWORK_MODULES_DIR . 'deco-acf/custom_fields/',
	);

	foreach ( $dirs as $dir ) {
		$custome_fields_init = array();
		if ( is_dir( $dir ) ) {
			if ( $dh = opendir( $dir ) ) {
				while ( false !== ( $file = readdir( $dh ) ) ) {
					if ( $file != '.' && $file != '..' && stristr( $file, '.php' ) !== false ) {
						list( $nam, $ext ) = explode( '.', $file );
						if ( $ext == 'php' ) {
							$custome_fields_init[] = $file;
						}
					}
				}
				closedir( $dh );
			}
		}
		asort( $custome_fields_init );
		foreach ( $custome_fields_init as $other_init ) {
			include_once $dir . $other_init;
		}
	}
}
