<?php

if ( ! function_exists( 'ajax_pagination' ) ) {
		function ajax_pagination(
			$custom_query = false,
			$inner_class = '',
			$posts_per_page = 0,
			$ajax_action = 'monin_ajax_pagination',
			$post_type = 'post',
			$exclude_posts = array()
		) {

			global $paged, $post, $wp_query;

			if ( empty( $paged ) ) {
				$paged = 1;
			}

			if ( $custom_query ) {
				$wp_query = $custom_query;
			} else {
				global $wp_query;
			}

			$tax = isset( $wp_query->tax_query->queries[0]['taxonomy'] ) ? $wp_query->tax_query->queries[0]['taxonomy'] : null;
			if ( $tax != null ) {
				$taxonomy = get_taxonomy( $tax );
			}

			if ( $wp_query->max_num_pages > 1 ):
				?>
				<div class="show_more_post"
				     data-post-type="<?php echo $post_type; ?>"
				     data-author-id="<?php if ( is_author() ) {
					     echo get_the_author_meta( 'ID' );
				     } ?>"
				     data-action="<?php echo $ajax_action; ?>"
				     data-posts-per-page="<?php echo $posts_per_page; ?>"
				     data-inner-class="<?php echo $inner_class; ?>"
				     data-taxonomy-name="<?php echo isset( $taxonomy->name ) ? $taxonomy->name : ''; ?>"
				     data-taxonomy-term="<?php echo isset( $wp_query->tax_query->queries[0]['terms'] ) && ! is_home() ? implode( ',', $wp_query->tax_query->queries[0]['terms'] ) : ''; ?>"
				     data-search-string="<?php echo htmlspecialchars( get_query_var( 's' ) ); ?>"
				     data-max-pages="<?php echo $wp_query->max_num_pages; ?>"
				     data-current-page="<?php echo $paged; ?>"
				     data-exclude-posts="<?php echo implode( ',', $exclude_posts ); ?>"
				     data-next-page="<?php echo $paged + 1; ?>">
					<a href="#">Показать еще</a>
				</div>
			<?php
			endif;
		}
	}

	/**
	 * Ajax pagination
	 **/

	add_action( 'wp_ajax_monin_ajax_pagination', 'monin_ajax_pagination' );
	add_action( 'wp_ajax_nopriv_monin_ajax_pagination', 'monin_ajax_pagination' );

	function monin_ajax_pagination() {
		
		
		$post_type      = $_POST['post_type'];
		$max_pages      = absint( $_POST['max_pages'] );
		$current_page   = absint( $_POST['current_page'] );
		$next_page      = absint( $_POST['next_page'] );
		$posts_per_page = absint( $_POST['posts_per_page'] );
		$search_string  = $_POST['search_string'];
		$taxonomy_name  = $_POST['taxonomy_name'];
		$taxonomy_term  = $_POST['taxonomy_term'];
		$exclude_post   = $_POST['exclude_post'];
		$author_id      = absint( $_POST['author_id'] );

		//$response = 'ok!';
		$response['test'] = 'ok!';
		$response['post_type'] = $post_type;
		$response['max_pages'] = $max_pages;
		$response['current_page'] = $current_page;
		$response['next_page'] = $next_page;
		$response['posts_per_page'] = $posts_per_page;
		$response['search_string'] = $search_string;
		$response['taxonomy_name'] = $taxonomy_name;
		$response['taxonomy_term'] = $taxonomy_term;
		$response['exclude_post'] = $exclude_post;
		$response['author_id'] = $author_id;

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'paged'          => $next_page,
			'post_status'    => 'publish'
			
		);

		// $args['category__not_in'] = array( 36 );

		if ( is_array( $exclude_post ) ) {
			$args['post__not_in'] = $exclude_post;
		}


		$the_query = new WP_Query( $args );

		ob_start();
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$exclude_post[] = $the_query->post->ID;
			get_template_part( 'content' );
		endwhile;

		$response['html'] = ob_get_clean();

		echo json_encode( $response );
		exit;
	}




	// AJAX Pagination
	add_action( 'wp_ajax_ain_ajax_pagination', 'ain_ajax_pagination' );
	add_action( 'wp_ajax_nopriv_ain_ajax_pagination', 'ain_ajax_pagination' );

	function ain_ajax_pagination() {

		$post_type      = $_POST['post_type'];
		$max_pages      = absint( $_POST['max_pages'] );
		$current_page   = absint( $_POST['current_page'] );
		$next_page      = absint( $_POST['next_page'] );
		$posts_per_page = absint( $_POST['posts_per_page'] );
		$search_string  = $_POST['search_string'];
		$taxonomy_name  = $_POST['taxonomy_name'];
		$taxonomy_term  = $_POST['taxonomy_term'];
		$exclude_post   = $_POST['exclude_post'];
		$author_id      = absint( $_POST['author_id'] );

		$taxonomy_terms = explode( ',', $taxonomy_term );

		$exclude_post = explode( ',', $exclude_post );

		// $excluded = array_merge( $exclude_post, get_excluded_posts_fog_loops() );
		$excluded =  $exclude_post;


		if ( ! $posts_per_page ) {
			$posts_per_page = get_option( 'posts_per_page' );
		}

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		/* if ($author_id) {
			 $args = array(
				 'post_type' => $post_type,
				 'posts_per_page' => $posts_per_page + 1,
				 'paged' => $next_page,
				 'post_status' => 'publish'
			 );
		 } elseif ($taxonomy_name == 'post_tag') {
			 $args = array(
				 'post_type' => $post_type,
				 'posts_per_page' => $posts_per_page + 1,
				 'paged' => $next_page,
				 'post_status' => 'publish'
			 );
		 } else {*/
		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'paged'          => $next_page,
			'post_status'    => 'publish'
		);
		//$response['test'] = 'ok!';
		//}

		// led
		//$args['category__not_in'] = array( 36 );

		if ( $search_string <> '' ) {
			$args['s'] = $search_string;
		} elseif ( $author_id ) {
			$args['author'] = $author_id;
		} elseif ( $taxonomy_name <> '' && $taxonomy_term <> '' ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy_name,
					'field'    => 'slug',
					'terms'    => $taxonomy_terms
				)
			);

		}


		if ( is_array( $excluded ) ) {
			$args['post__not_in'] = $excluded;
		}

		$the_query = new WP_Query( $args );
		ob_start();
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$exclude_post[] = $the_query->post->ID;
			// get_template_part( 'content' );

			//led content
			if(has_post_thumbnail()){
            	   get_template_part('content');
                } else {
                    if (in_category('map' )) {
                        get_template_part('content');
                    } else {
                        get_template_part('content-nothumb');
                    }
                }
            //end

		endwhile;


		$excluded_new = array_merge( $excluded, $exclude_post );

		$response['html'] = ob_get_clean();

		$response['next_page']     = $next_page + 1;
		$response['current_page']  = $next_page;
		$response['max_pages']     = $max_pages;
		$response['exclude_posts'] = implode( ',', $excluded_new );

		if ( $response['next_page'] >= $max_pages ) {
			$response['hide_link'] = 'yes';
		}
		wp_reset_query();

		echo json_encode( $response );
		exit;
	}

	//

	add_action( 'wp_ajax_ain_ajax_pagination_by_tag', 'ain_ajax_pagination_by_tag' );
	add_action( 'wp_ajax_nopriv_ain_ajax_pagination_by_tag', 'ain_ajax_pagination_by_tag' );

	// Brenging pagination by tag
	function ain_ajax_pagination_by_tag() {

		$max_pages      = absint( $_POST['max_pages'] );
		$current_page   = absint( $_POST['current_page'] );
		$next_page      = absint( $_POST['next_page'] );
		$posts_per_page = absint( $_POST['posts_per_page'] );
		$taxonomy_term  = $_POST['taxonomy_term'];

		$args      = array(
			'post_type'      => 'post',
			'posts_per_page' => $posts_per_page,
			'paged'          => $next_page,
			'tax_query'      => array(
				array(
					'taxonomy' => 'post_tag',
					//'field' => 'id',
					'field'    => 'slug',
					'terms'    => $taxonomy_term
				)
			),
		);
		$the_query = new WP_Query( $args );
		ob_start();
		while ( $the_query->have_posts() ) : $the_query->the_post();
			get_template_part( 'content' );
		endwhile;
		$response['html'] = ob_get_clean();

		$response['next_page']    = $next_page + 1;
		$response['current_page'] = $next_page;
		$response['max_pages']    = $max_pages;

		if ( $response['next_page'] >= $max_pages ) {
			$response['hide_link'] = 'yes';
		}
		wp_reset_query();

		echo json_encode( $response );
		exit;
	}

	//

	add_action( 'wp_ajax_pagination_vacancy', 'pagination_vacancy' );
	add_action( 'wp_ajax_nopriv_pagination_vacancy', 'pagination_vacancy' );
	function pagination_vacancy() {

		$post_type      = $_POST['post_type'];
		$max_pages      = absint( $_POST['max_pages'] );
		$current_page   = absint( $_POST['current_page'] );
		$next_page      = absint( $_POST['next_page'] );
		$posts_per_page = absint( $_POST['posts_per_page'] );
		$taxonomy_name  = $_POST['taxonomy_name'];
		$taxonomy_term  = $_POST['taxonomy_term'];

		//$author_id = absint($_POST['author_id']);

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'paged'          => $next_page
		);

		$taxonomy_terms = explode( ',', $taxonomy_term );
		if ( $taxonomy_name <> '' && $taxonomy_term <> '' ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy_name,
					'field'    => 'id',
					'terms'    => $taxonomy_terms
				)
			);

		}

		$wp_query = new WP_Query( $args );
		ob_start();
		if ( $wp_query->have_posts() ) :
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				get_template_part( 'content', 'vacancy' );
			endwhile;
		endif;

		$response['html'] = ob_get_clean();

		$response['next_page']    = $next_page + 1;
		$response['current_page'] = $next_page;
		$response['max_pages']    = $max_pages;

		if ( $response['next_page'] >= $max_pages ) {
			$response['hide_link'] = 'yes';
		}
		wp_reset_query();

		echo json_encode( $response );
		exit;

	}


	add_action( 'wp_ajax_get_top_posts_by_filters', 'get_top_posts_by_filters' );
	add_action( 'wp_ajax_nopriv_get_top_posts_by_filters', 'get_top_posts_by_filters' );
	function get_top_posts_by_filters() {

		global $wpdb;

		$max_pages    = absint( $_POST['max_pages'] );
		$current_page = absint( $_POST['current_page'] );
		$next_page    = absint( $_POST['next_page'] );
		//$posts_per_page = absint( $_POST['posts_per_page'] );
		$filter_date = $_POST['filter_date'];
		$filter_soc  = $_POST['filter_soc'];


		$posts_per_page = 16;
		$start          = $next_page * $posts_per_page;

		$filter_date_for_paginate = $filter_date;
		if ( $filter_date == 'week' ) {
			$filter_date = date( 'Y-m-d', strtotime( '-7 days' ) );
		} elseif ( $filter_date == 'month' ) {
			$filter_date = date( 'Y-m-d', strtotime( '-1 month' ) );
		} elseif ( $filter_date == 'year' ) {
			$filter_date = date( 'Y-m-d', strtotime( '-12 month' ) );
		} else {
			$filter_date              = date( 'Y-m-d', strtotime( '-7 days' ) );
			$filter_date_for_paginate = 'week';
		}

		if ( $filter_soc == 'views' ) {
			$query = "SELECT p.*, stat.* FROM $wpdb->posts as p ";
			$query .= " INNER JOIN pr_stats as stat ON stat.PostID=p.ID";
			$where   = " WHERE 1=1 ";
			$orderby = " ORDER BY stat.Views DESC";

		} elseif ( $filter_soc == 'likes' ) {
			$query = "SELECT p.*, ";
			$query .= " (SELECT page_votes_sum FROM social_rank WHERE social_rank.page_id = p.ID) AS page_votes_sum ";
			$query .= " FROM $wpdb->posts as p ";
			$where   = " WHERE 1=1 ";
			$orderby = " ORDER BY page_votes_sum DESC";
		} elseif ( $filter_soc == 'comments' ) {
			$query = "SELECT p.*, ";
			$query .= " (SELECT COUNT(*) FROM wp_comments WHERE wp_comments.comment_post_ID = p.ID) AS Comments ";
			$query .= " FROM $wpdb->posts as p ";
			$where   = " WHERE 1=1 ";
			$orderby = " ORDER BY Comments DESC";
		} else {
			$query = "SELECT p.*, stat.* FROM $wpdb->posts as p ";
			$query .= " INNER JOIN pr_stats as stat ON stat.PostID=p.ID";
			$where      = " WHERE 1=1 ";
			$orderby    = " ORDER BY stat.Views DESC";
			$filter_soc = 'views';
		}

		$where .= " AND p.post_type='post'";
		$where .= " AND p.post_status='publish'";
		//$where .= " AND DATE_FORMAT(p.post_date, '%Y-%m-%d') >= '$filter_date' ";
		$where .= " AND p.post_date >= '$filter_date' ";


		///$paged = $next_page


		if ( empty( $max_pages ) ) {
			$count_posts = $wpdb->get_results( $query . $where );
			if ( count( $count_posts ) > $posts_per_page ) {
				$max_pages = ceil( count( $count_posts ) / $posts_per_page );
			}
		}

		$limit = " LIMIT $start, $posts_per_page";

		$query .= " $where $orderby $limit";

		$result = $wpdb->get_results( $query );
		//print_r($result);
		ob_start();
		global $post;
		$temp_post = $post;
		foreach ( $result as $item ) {
			$post = $item;

			get_template_part( 'content' );
			/*
					?>

					<li class="new_post_item">
						<div class="new_img_cont">
							<a href="<?php echo get_permalink($item->ID); ?>">
								<img src=="<?php echo get_img_post($item->ID, 'ain-post'); ?>"
									 alt="<?php echo get_the_title($item->ID); ?>"></a>
							<div class="new_post_likes">
													<span class="new_views">
														<?php $res = get_soc_votes($item->ID);
														echo $res['view']; ?></span>
													<span class="new_comm"><a
															href="<?php echo get_permalink($item->ID); ?>/#comments-box"><?php echo $res['cn']; ?></a></span>
													<span class="new_likes"><?php $res = get_soc_votes($item->ID);
														echo $res['sum']; ?></span>
							</div>
						</div>
						<div class="new_post_meta cf">
							<a href="<?php echo get_author_posts_url($item->post_author, ''); ?>"
							   class="new_author"><?php $user = get_userdata($item->post_author); echo $user->display_name?></a>
							<time class="new_post_date" datetime="<?php echo get_the_time('d-m-Y', $item->ID) ?>">
								<?php the_time('d'); ?> <?php echo month_full_name_ru(get_the_time('n', $item->ID)); ?>
							</time>
						</div>
						<h2><a href="<?php echo get_permalink($item->ID); ?>"><?php echo get_the_title($item->ID); ?></a></h2>
					</li>
				<?php */
		}

		$post = $temp_post;

		$response['next_page']    = $next_page + 1;
		$response['current_page'] = $next_page;
		$response['max_pages']    = $max_pages;
		$response['filter_date']  = $filter_date_for_paginate;
		$response['filter_soc']   = $filter_soc;
		if ( $response['next_page'] >= $max_pages ) {
			$response['hide_link'] = 'yes';
		}
		$response['html'] = ob_get_clean();

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			echo json_encode( $response );
			exit;
		} else {
			return $response;
		}
	}

