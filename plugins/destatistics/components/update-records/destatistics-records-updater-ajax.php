<?php

//set_time_limit(18000);
//ini_set('memory_limit', '128M');
//ini_set( "auto_detect_line_endings", true );

$post_data = array(
	'ids'    => $_POST['ids'],
	'limit'  => $_POST['limit'],
	'offset' => $_POST['offset']
);

if ( isset( $post_data['ids'] ) ) {

	$error_messages = array();

	$import_data = json_decode( stripslashes( $post_data['ids'] ) );

	if ( count( $import_data ) == 0 ) {
		$error_messages[] = __( 'No data found.' );
	}

	//total size of data to import (not just what we're doing on this pass)
	$row_count = count( $import_data );

	$limit = intval( $post_data['limit'] );

	$offset = intval( $post_data['offset'] );
	if ( $limit > 0 || $offset > 0 ) {
		$import_data = array_slice( $import_data, $offset, ( $limit > 0 ? $limit : null ), true );
	}

	//a few stats about the current operation to send back to the browser.
	$rows_remaining = ( $row_count - ( $offset + $limit ) ) > 0 ? ( $row_count - ( $offset + $limit ) ) : 0;
	$insert_count   = ( $row_count - $rows_remaining );
	$insert_percent = number_format( ( $insert_count / $row_count ) * 100, 1 );

	//array that will be sent back to the browser with info about what we inserted.
	$inserted_rows = array();

	//this is where the fun begins
	foreach ( $import_data as $id ) {

		add_filter( 'home_url', function ( $url ) {
			return 'ain.ua';
		} );
		$result = '';
		$stats  = new DESTAT_Stats();
		ob_start();
		$stats->singleUpdateStatsPost( $id, 1 );
		$result = ob_get_contents();
		ob_end_clean();

		$new_post_insert_success = true;

		$post_title = get_the_title( $id );
//		$post_title .= ' - ' + get_permalink( $id );

		$inserted_rows[] = array(
			'row_id'       => $row_id,
			'post_id'      => $id,
			'name'         => $post_title,
			'has_errors'   => ( sizeof( $new_post_errors ) > 0 ),
			'errors'       => $result,
			'has_messages' => ( sizeof( $new_post_messages ) > 0 ),
			'messages'     => $new_post_messages,
			'success'      => $new_post_insert_success
		);
	}
}

echo json_encode( array(
	'remaining_count' => $rows_remaining,
	'row_count'       => $row_count,
	'insert_count'    => $insert_count,
	'insert_percent'  => $insert_percent,
	'inserted_rows'   => $inserted_rows,
	'error_messages'  => $error_messages,
	'limit'           => $limit,
	'new_offset'      => ( $limit + $offset ),
	'ids'             => json_decode( stripslashes( $post_data['ids'] ) ),
	'post_data'       => $post_data
) );
?>
