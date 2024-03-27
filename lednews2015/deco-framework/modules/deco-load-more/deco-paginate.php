<?php

function deco_pagination( $args ) {

	$defaults = array(
		'total'     => 1,
		'current'   => 0,
		'show_all'  => false,
		'prev_next' => true,
		'prev_text' => __( 'Предыдущая', TEXTDOMAIN ),
		'next_text' => __( 'Следующая', TEXTDOMAIN ),
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
		$page_links .= '<a href="' . deco_get_pagenum_link( $prev_num, true, $uri ) . '" class="previouspostslink de_item_page_num_' . $n . '" data-page-num="' . $prev_num . '">' . $prev_text . '</a>';
	}


	for ( $n = 1; $n <= $total; $n ++ ) {
		//echo $total.'<br>';

		if ( $n == $current ) {
			$page_links .= "<span class='current-nav-item de_item_page_num_{$n}' data-page-num='$n'>$n</span>";
			$dots = true;
		} else {
			if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) {

				$page_links .= "<a href='" . deco_get_pagenum_link( $n, true, $uri ) . "' class='page-numbers de_item_page_num_{$n}' data-page-num='$n'>$n</a>";

				$dots = true;
			} elseif ( $dots && ! $show_all ) {
				$page_links .= '<span>' . __( '&hellip;' ) . '</span>';
				$dots = false;
			}
		}
	}

	if ( $prev_next && $current && ( $current < $total || - 1 == $total ) ) {
		$page_links .= '<a href="' . deco_get_pagenum_link( $current + 1, true, $uri ) . '" class="nextpostslink de_item_page_num_' . $n . '" data-page-num="' . ( $current + 1 ) . '">' . $next_text . '</a>';
	}

	return $page_links;
}
