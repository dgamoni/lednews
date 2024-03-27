<?php
define( 'TEXTDOMAIN', 'lednews' );
define( 'DECO_THEME_URL', get_template_directory_uri() . '/' );
define( 'DECO_THEME_DIR', get_template_directory() . '/' );
define( 'DECO_FRAMEWORK_URL', DECO_THEME_URL . 'deco-framework/' );
define( 'DECO_FRAMEWORK_DIR', DECO_THEME_DIR . 'deco-framework/' );
define( 'DECO_FRAMEWORK_MODULES_URL', DECO_FRAMEWORK_URL . 'modules/' );
define( 'DECO_FRAMEWORK_MODULES_DIR', DECO_FRAMEWORK_DIR . 'modules/' );
         
// wp_register_script( 'deco_main_framework', DECO_FRAMEWORK_URL . 'assets/js/main.js', array(), false, true );
// wp_enqueue_script( 'deco_main_framework' );
// wp_register_script( 'deco_main_framework', DECO_FRAMEWORK_URL . 'assets/js/orphus.js', array(), false, true );
// wp_enqueue_script( 'deco_main_framework' );
require_once DECO_FRAMEWORK_DIR . 'libs/BFI_Thumb.php';
require_once DECO_FRAMEWORK_DIR . 'includes/deco-remove-catecory-base.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-functions.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-content-functions.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-walker-head-menu.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-cron.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-ajax.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-rewrite-rules.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-admin-css-style-custom.php';
// require_once DECO_FRAMEWORK_DIR . 'includes/deco-tagtheme-custom-fields.php';
require_once DECO_FRAMEWORK_MODULES_DIR . 'init.php';

// Автозагрузка библиотек и функций
$dirs = array(
	// CORE_PATH . '/walkers/',
	// CORE_PATH . '/widgets/',
	DECO_FRAMEWORK_DIR . '/taxonomy/',
	DECO_FRAMEWORK_DIR . '/post_types/',
	// CORE_PATH . '/shortcode/',
	// CORE_PATH . '/include/',

);
foreach ( $dirs as $dir ) {
	$other_inits = array();
	if ( is_dir( $dir ) ) {
		if ( $dh = opendir( $dir ) ) {
			while ( false !== ( $file = readdir( $dh ) ) ) {
				if ( $file != '.' && $file != '..' && stristr( $file, '.php' ) !== false ) {
					list( $nam, $ext ) = explode( '.', $file );
					if ( $ext == 'php' ) {
						$other_inits[] = $file;
					}
				}
			}
			closedir( $dh );
		}
	}
	asort( $other_inits );
	foreach ( $other_inits as $other_init ) {
		if ( file_exists( $dir . $other_init ) ) {
			include_once $dir . $other_init;
		}
	}
}