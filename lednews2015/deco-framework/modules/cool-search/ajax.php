<?php
	if ( ! function_exists( 'dc_ajax_search' ) ) {
		function dc_ajax_search() {

			if ( isset( $_POST['term'] ) ) {

				$search_query = new WP_Query( array(
					                              's'              => $_POST['term'],
					                              'post_type'      => 'post',
					                              'posts_per_page' => 24,
					                              //'no_found_rows' => true,
					                              'post_status'    => 'publish',
					                              'orderby'        => 'date',
					                              'order'          => 'DESC'
				                              ) );

				if ( $search_query->have_posts() ) {

					while ( $search_query->have_posts() ) {
						$search_query->the_post();
						get_template_part( 'content' );
					}
					wp_reset_postdata();
				} else {
					header( 'Status: 204 No content' );
				}

			}
			die();
		}
	}

	if ( ! function_exists( 'dc_ajax_search_2' ) ) {
		function dc_ajax_search_2() {

			if ( isset( $_POST['term'] ) ) {
				$term = $_POST['term'];
				global $wpdb;

				$request = "SELECT * FROM $wpdb->posts WHERE comment_count > 0 ";
				$request .= " AND post_status = 'publish'";
				$request .= " AND post_type = 'post'";
				$request .= " AND ( post_title LIKE '%$term%' OR post_content LIKE '%$term%' )";
				$request .= " ORDER BY post_date DESC LIMIT 24";

				$posts = $wpdb->get_results( $request );

				foreach ( $posts as $post ) {
					setup_postdata( $post );
					get_template_part( 'content' );
				}
			} else {
				header( 'Status: 204 No content' );
			}
			die();
		}


	}


	add_action( 'wp_ajax_dc_ajax_search', 'dc_ajax_search' );
	add_action( 'wp_ajax_nopriv_dc_ajax_search', 'dc_ajax_search' );