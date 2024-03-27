<?php

add_action( 'wp_ajax_deco_get_zviv_pro_robotu_archive', 'deco_get_zviv_pro_robotu_archive' );
add_action( 'wp_ajax_nopriv_deco_get_zviv_pro_robotu_archive', 'deco_get_zviv_pro_robotu_archive' );
function deco_get_zviv_pro_robotu_archive() {

	$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
	$tax_slug  = isset( $_POST['tax_slug'] ) ? $_POST['tax_slug'] : '';
	$page      = isset( $_POST['page'] ) ? $_POST['page'] : '';

	list( $tmp, $filter_params ) = explode( 'diyalnist', $_SERVER['REQUEST_URI'] );
	if ( ! empty( $filter_params ) ) {
		$link_request = array_filter( explode( '/', $filter_params ) );
		if ( is_array( $link_request ) && count( $link_request ) > 0 ) {
			foreach ( $link_request as $item ) {
				$params[] = $item;
			}
			if ( isset( $params[0] ) ) {
				$post_type = $params[0];
			}

			if ( isset( $params[1] ) ) {
				$tax_slug = $params[1];
			}

		}
	}
	if ( empty( $page ) ) {
		$page = 1;
	}

	if ( empty( $post_type ) || $post_type == 'all-types' ) {
		$post_type = array(
//			'deputats',
			'projects',
			'reforms',
			'appeals',
			'requests',
			'draftdecisions',
			'votings',
			'answers',
			'commissionstable',
			'commissiontemp'
		);

	}
	$args = array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'paged'          => $page
	);

	if ( $tax_slug ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'tagtheme',
				'field'    => 'slug',
				'terms'    => $tax_slug
			)
		);
	}
//	print_r( $args );
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		ob_start();
		while ( $query->have_posts() ):
			$query->the_post();
			global $postt;
			$postt = $query->post;
			get_template_part( 'content', 'diyalnist' );
		endwhile;
		$content = ob_get_contents();
		ob_end_clean();
	}
	wp_reset_query();

	$page ++;
	if ( empty( $content ) ) {
		$res['content']    = 'Нiчого не знайдено!';
		$res['title_list'] = 'ПУСТО';
	} else {
		if ( is_array( $post_type ) ) {
			$res['title_list'] = 'Всi типи дiяльностi';
		} else {
			$post_type_obj     = get_post_type_object( $post_type );
			$res['title_list'] = $post_type_obj->labels->name;
		}
		$res['content'] = $content;
	}
	$res['post-type'] = $post_type;
	$res['tax-slug']  = $tax_slug;

	if ( $page < $query->max_num_pages ) {
		$res['paginate'] = '<button class="btn-more" data-page="' . $page . '" data-month-num="' . $month_num . '" onclick="Zvit_pro_robotu_archive.load(); return false;">Більше ' . $res['title_list'] . '</button>';
	}
	if ( defined( 'DOING_AJAX' ) ) {
		die( json_encode( $res ) );
	}

	return $res;
}
