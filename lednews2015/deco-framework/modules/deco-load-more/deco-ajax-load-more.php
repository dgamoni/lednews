<?php

add_action( 'wp_ajax_load_more', 'prefix_ajax_load_more' );
add_action( 'wp_ajax_nopriv_load_more', 'prefix_ajax_load_more' );


function prefix_ajax_load_more() {
	$page          = $_POST['page'];
	$uri           = $_POST['uri'];
	$cat           = $_POST['cat'];
	$post_not_in   = $_POST['post_not_in'];
	$tax_type      = $_POST['tax_type'];
	$name_type     = $_POST['name_type'];
	$max_num_pages = $_POST['max_num_pages'];
	$content_block = $_POST['content_block'];
	$is_search     = $_POST['is_search'];
	$is_author     = $_POST['is_author'];
	$lang          = $_POST['lang1'];

	$offset      = ( $page - 1 ) * 11;
	$is_category = false;

	$args = array(
		'posts_per_page' => 6,
		'post_status'    => 'publish',
		'paged'          => $page,
		//'page'           => $page,
		'post__not_in'   => explode( ',', $post_not_in ),
		'offset'         => $offset
	);


	if ( $cat && $name_type ) {
		$is_category       = true;
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => array( $cat )
			),
			array(
				'taxonomy' => 'types',
				'field'    => 'slug',
				'terms'    => array( $name_type )
			)
		);
	} else if ( $cat ) {
		$is_category       = true;
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => array( $cat ),
			)
		);
	}


	$query = new WP_Query( $args );

	ob_start();
	$count = 0;
	while ( $query->have_posts() ) {
		$query->the_post();
		$count ++;
		if ( $cat || $is_search || $is_author ) {
			get_template_part( 'content-article' );
		} else {
			if ( $count == 1 ) {
				echo '<section class="grid-row grid-33">';
			}
			get_template_part( 'content-post' );

			if ( $count == 3 ) {
				$count = 0;
				echo '</section>';
			}
		}

	}
	wp_reset_query();

	$content = ob_get_clean();

	ob_start();

	deco_load_more( array(
		'wpquery'       => $query,
		'max_num_pages' => $max_num_pages,
		'content_block' => $content_block,
		'uri'           => $uri,
		'is_search'     => $is_search,
		'is_author'     => $is_author,
		'lang'          => $lang,
		'is_category'   => $is_category,
	) );

	$paginate = ob_get_clean();

	$data = array(
		'content'     => $content,
		'paginate'    => $paginate,
		'post'        => $args,
		'query'       => $query,
		'uri'         => $uri,
		'query_posts' => $query->posts,
		'post_not_in' => $post_not_in,
		'offset'      => $offset,
		'is_category'   => $is_category,
	);

	echo json_encode( $data );
	exit;
}
