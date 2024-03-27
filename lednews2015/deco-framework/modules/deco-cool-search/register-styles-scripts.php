<?php


add_action( 'wp_enqueue_scripts', 'cool_search_stylesheets' );
function cool_search_stylesheets() {
	wp_enqueue_style( "cool-search", DECO_COOL_SEARCH_URL . "assets/css/cool-search.css", false, "1.0" );
}


add_action( 'wp_head', 'cool_search_js' );

function cool_search_js() {
	wp_enqueue_script( "cool-search-plugins", DECO_COOL_SEARCH_URL . "assets/js/cool-search-plugins.js", array(), '', true );
	wp_enqueue_script( "cool-search", DECO_COOL_SEARCH_URL . "assets/js/cool-search.js", array(), '', true );

}


