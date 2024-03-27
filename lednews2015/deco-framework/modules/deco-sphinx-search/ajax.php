<?php


add_action( 'wp_ajax_deco_autocentre_search', 'deco_autocentre_search' );
add_action( 'wp_ajax_nopriv_deco_autocentre_search', 'deco_autocentre_search' );
function deco_autocentre_search() {

	if ( class_exists( 'SphinxClient' ) ) {
		$posts_per_page = 6;
		$page           = $_POST['page'];
//		$sort_field     = $_POST['sort_field'];
//		$sort_order     = $_POST['sort_order'];

		if ( empty( $page ) ) {
			$page = 1;

		}

		$offset = $posts_per_page * ( $page - 1 );

		// создаем инстанц клиента
		$cl = new SphinxClient ();

// настройки
		$cl->SetServer( "127.0.0.1", 9312 );
		$cl->resetFilters();

//	$cl->SetConnectTimeout( 10 );
		$cl->SetLimits( $offset, $posts_per_page ); // постраничную навигацию организовываем тут
//	$cl->SetArrayResult( true );
		$cl->SetRankingMode( SPH_RANK_PROXIMITY_BM25 );
//	$cl->SetRankingMode( SPH_RANK_NONE );
		$cl->setFilterFloatRange( 'price', 1, 900000 );
//	$cl->SetMatchMode( SPH_MATCH_ANY );
		$cl->SetMatchMode( SPH_MATCH_EXTENDED );

/*		if ( '$sort_field' $sort_order || $sort_order == 'desc' ) {
			$cl->SetSortMode( SPH_SORT_EXTENDED, "power DESC" );
		} else {
			$cl->SetSortMode( SPH_SORT_EXTENDED, "power ASC" );
		}*/

//		if ( empty( $sort_order ) ) {
			$cl->SetSortMode( SPH_SORT_EXTENDED, "price ASC" );
//			$cl->SetSortMode( SPH_SORT_EXTENDED, "consumption_combined DESC" );
//		}


//	$cl->SetFilter( 'in_stock', array( 0 ) );
//	$cl->SetFilter( 'country_title', array( 'None' ) );
//	$cl->SetFilter( 'body_title_search', array( crc32( 'Хэчбэк' ) ) );
//	$cl->SetFilter( 'body_title', array( iconv('Windows-1252','UTF-8','Фургон') ) );
//	$cl->SetFilter( 'make_id', array( 36 ) );
//	$cl->setSelect();
//	print_r( $cl );
		$result = $cl->Query( '', 'search_offers_index' );
//		$result = $cl->Query( '', 'search_modifications_index' );

		$max_num_pages      = ceil( $result['total'] / $posts_per_page );
		$result['paginate'] = deco_autocentre_search_pagination( array(
			'total'   => $max_num_pages,
			'current' => $page
		) );
//		echo $cl->GetLastError();
//		print_r( $result );
//		print_r( $_GET );
//	die();
		ob_start();
		foreach ( $result['matches'] as $sp_item ) {
			$item = $sp_item['attrs'];
			$link = get_permalink( $item['post_id'] );
			include DECO_THEME_DIR . '/parts/search/content-simple.php';
		}
		$content = ob_get_contents();
		ob_end_clean();

		$result['content'] = $content;
		$result['page']    = $page;
	}
	$result['post'] = $_POST;

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		die( json_encode( $result ) );
	}

	return $result;

}


function deco_autocentre_search_pagination( $args ) {

	$defaults = array(
		'total'     => 1,
		'current'   => 0,
		'show_all'  => false,
		'prev_next' => true,
		'prev_text' => '',
		'next_text' => '',
		'end_size'  => 1,
		'mid_size'  => 2,
		'uri'       => '',
	);


	$args = wp_parse_args( $args, $defaults );

	extract( $args, EXTR_SKIP );


	$current  = (int) $current;
	$end_size = 0 < (int) $end_size ? (int) $end_size : 1;
	$mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
	$r        = '';
	$n        = 0;
	$dots     = false;

	$page_links = '';

	if ( $prev_next && $current && 1 < $current ) {
		$prev_num = $current - 1;
		$page_links .= '<a href="#" class="np-first-page" data-page-num="' . $prev_num . '">' . $prev_text . '</a>';
	}


	for ( $n = 1; $n <= $total; $n ++ ) {
		//echo $total.'<br>';

		if ( $n == $current ) {
			$page_links .= "<span data-page-num='$n'>$n</span>";
			$dots = true;
		} else {
			if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) {

				$page_links .= "<a href='#' data-page-num='$n'>$n</a>";

				$dots = true;
			} elseif ( $dots && ! $show_all ) {
				$page_links .= '<span>' . __( '&hellip;' ) . '</span>';
				$dots = false;
			}
		}
	}

	if ( $prev_next && $current && ( $current < $total || - 1 == $total ) ) {
		$page_links .= '<a href="#" class="np-last-page" data-page-num="' . ( $current + 1 ) . '">' . $next_text . '</a>';
	}

	return $page_links;
}
