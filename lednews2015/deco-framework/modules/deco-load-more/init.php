<?php

if ( ! is_admin() ) {
	wp_register_script( 'load_more', DECO_FRAMEWORK_MODULES_URL . 'deco-load-more/assets/js/load_more.js', array(), false, true );

	wp_enqueue_script( 'load_more' );
}

require_once 'deco-ajax-load-more.php';
require_once 'deco-functions-load-more.php';
require_once 'deco-paginate.php';
require_once 'deco-load-more.php';
