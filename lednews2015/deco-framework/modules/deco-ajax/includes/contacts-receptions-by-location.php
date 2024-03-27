<?php

add_action( 'wp_ajax_deco_receptions_by_location', 'deco_receptions_by_location' );
add_action( 'wp_ajax_nopriv_deco_receptions_by_location', 'deco_receptions_by_location' );
function deco_receptions_by_location() {
	$term_id = $_POST['term_id'];
	if ( empty( $term_id ) ) {
		return;
	}

	$GLOBALS['deco_receptions_location_term_id'] = $term_id;
	get_template_part( 'parts/receptions' );
	die();
}