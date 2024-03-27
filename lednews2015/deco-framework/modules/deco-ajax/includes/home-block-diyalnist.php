<?php


add_action( 'wp_ajax_deco_get_zviv_pro_robotu', 'deco_get_zviv_pro_robotu' );
add_action( 'wp_ajax_nopriv_deco_get_zviv_pro_robotu', 'deco_get_zviv_pro_robotu' );
function deco_get_zviv_pro_robotu() {

	if ( defined( 'DOING_AJAX' ) ) {
		$vid_diyalnosti = $_POST['vid_diyalnosti'];
		$post_type      = $_POST['post_type'];
		$date           = $_POST['date'];
		$page           = $_POST['page'];
		$month_num      = intval( $_POST['month_num'] );
	}

	if ( empty( $page ) ) {
		$page = 1;
	}

	if ( empty( $post_type ) ) {
		$post_type = 'any';
	}

	if ( $post_type == 'any' ) {
		$post_types = array( 'post', 'projects', 'appeals', 'requests', 'draftdecisions', 'votings', 'answers' );
	} else {
		$post_types = array( $post_type );
	}


	$args = array(
		'post_type'      => $post_types,
		'post_status'    => 'publish',
		'posts_per_page' => 7,
		'paged'          => $page
	);

	if ( $date ) {
		list( $filter_m, $filter_y ) = explode( '-', $date );
		$args['date_query'] = array(
			array(
				'year'  => $filter_y,
				'month' => $filter_m
			)
		);
	}

	$query = new WP_Query( $args );

	$i        = 0;
	$now_year = date( 'Y' );
	if ( $query->have_posts() ) {
		ob_start();
		$num_posts = count( $query->posts );
		global $postt;
		while ( $query->have_posts() ):
			$query->the_post();
			$postt = $query->post;
			$i ++;
			$post_id     = $query->post->ID;
			$post_format = get_post_format( $post_id );
			if ( empty( $post_format ) ) {
				$post_format = 'aside';
			}
			list( $m, $y ) = explode( '-', get_the_time( 'n-Y' ) );
			if ( ( $page == 1 && $i == 1 ) || $month_num != intval( $m ) ) {
				$month_num  = intval( $m );
				$month_name = deco_month_num_to_name2( $month_num );
				if ( $y != $now_year ) {
					$month_name = '';
				}
				echo '<span class="sticker-yellow">' . $month_name . '</span>';
			} else if ( $page > 1 && $i == 1 ) {
				echo '<div class="content__decor"></div>';
			}
			get_template_part( 'content', $post_format );
			if ( $i < $num_posts ) {
				echo '<div class="content__decor"></div>';
			}
		endwhile;
		wp_reset_query();
		$content = ob_get_contents();
		ob_end_clean();
	}
	$page ++;
	$res['content'] = $content;
	if ( $page < $query->max_num_pages ) {
		$res['paginate'] = '<button class="btn-more" data-page="' . $page . '" data-month-num="' . $month_num . '" onclick="Zvit_pro_robotu.load(); return false;">Більше подій</button>';
	}
	if ( defined( 'DOING_AJAX' ) ) {
		die( json_encode( $res ) );
	}

	return $res;
}

