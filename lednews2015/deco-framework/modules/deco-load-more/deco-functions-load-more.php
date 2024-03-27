<?php

function deco_get_pagenum_link( $pagenum = 1, $escape = true, $uri = '' ) {
	global $wp_rewrite;


	$pagenum = (int) $pagenum;

	$request = remove_query_arg( 'paged' );

	if ( $uri ) {
		$request = $uri;
	}

	$home_root = parse_url( home_url() );
	$home_root = ( isset( $home_root['path'] ) ) ? $home_root['path'] : '';
	$home_root = preg_quote( $home_root, '|' );

	$request = preg_replace( '|^' . $home_root . '|i', '', $request );

	$request = preg_replace( '|^/+|', '', $request );

//	if ( ! $wp_rewrite->using_permalinks() || is_admin() ) {
//		$base = trailingslashit( get_bloginfo( 'url' ) );
//
//		if ( $pagenum > 1 ) {
//			$result = add_query_arg( 'paged', $pagenum, $base . $request );
//		} else {
//			$result = $base . $request;
//		}
//	} else {
	$qs_regex = '|\?.*?$|';
	preg_match( $qs_regex, $request, $qs_match );

	if ( ! empty( $qs_match[0] ) ) {
		$query_string = $qs_match[0];
		$request      = preg_replace( $qs_regex, '', $request );
	} else {
		$query_string = '';
	}

	$request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request );
	$request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request );
	$request = ltrim( $request, '/' );

	$base = trailingslashit( get_bloginfo( 'url' ) );

	if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) ) {
		$base .= $wp_rewrite->index . '/';
	}

	if ( $pagenum > 1 ) {
		$request = ( ( ! empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
	}

	$result = $base . $request . $query_string;
//	}

	$result = apply_filters( 'get_pagenum_link', $result );

	if ( $escape ) {
		return esc_url( $result );
	} else {
		return esc_url_raw( $result );
	}
}


function deco_get_post_not_in( $wp_query ) {

//	if ( is_array( $wp_query->post__not_in ) && count( $wp_query->post__not_in ) > 0 ) {
//		$post = $wp_query->post__not_in;
//	} else {
//		$post = $wp_query->query['post__not_in'];
//	}
//
//	foreach ( $wp_query->posts as $item ) {
//		$post[] = $item->ID;
//	}

	if ( is_category() || is_tag() ) {
		return;
	}

	$excludes = deco_get_exclude_posts();

	if ( $excludes ) {
		foreach ( $excludes as $exclude ) {
			$post[] = $exclude;
		}
	}


	return implode( ',', $post );
}
