<?php

define( 'DECO_COOL_SEARCH_DIR', dirname( __FILE__ ) . '/' );
define( 'DECO_COOL_SEARCH_URL', str_replace( ABSPATH, site_url() . '/', DECO_COOL_SEARCH_DIR ) );

require_once 'deco-cool-search.php';

add_action( 'wp_enqueue_scripts', 'deco_cool_search_stylesheets' );
function deco_cool_search_stylesheets() {
	wp_enqueue_style( "deco-cool-search", DECO_COOL_SEARCH_URL . "assets/css/cool-search.css", false, "1.0" );
}


add_action( 'wp_head', 'deco_cool_search_js' );

function deco_cool_search_js() {
	wp_enqueue_script( "deco-cool-search-plugins", DECO_COOL_SEARCH_URL . "assets/js/cool-search-plugins.js", array(), '', true );
	wp_enqueue_script( "deco-cool-search-js", DECO_COOL_SEARCH_URL . "assets/js/cool-search.js", array(), '', true );

}

