<?php

function deco_form_search_ajax() {
	?>
	<div class="header-search-container disp-none">
		<h1>
			<span><?php _e( 'Search', TEXTDOMAIN ); ?></span>
			<button type="button" onClick="dc_close_search()" class="pull-right dc_close">&times;</button>
			<a class="pull-right header-search-all btn-cool-search btn-sm btn-bordered btn-bordered-light hidden-xs hide"
			   href="#"><?php _e( 'All results', TEXTDOMAIN ); ?></a>
		</h1>
		<input type="text" class="header-search-input">

		<!--		<div class="header-search-results"></div>-->
		<ul class="header-search-results"></ul>
	</div>
	<!--	<div class="header-search-container">-->
	<!--		<h1>-->
	<!--			<span>--><?php //_e( 'Search', TEXTDOMAIN ); ?><!--</span>-->
	<!--			<button type="button" onclick="dc_close_search()" class="dc_close">&times</button>-->
	<!--			<a class="header-search-all btn-cool-searchhidden-xs hide"-->
	<!--			   href="#">--><?php //_e( 'All results', TEXTDOMAIN ); ?><!--</a>-->
	<!--		</h1>-->
	<!--		<input type="text" class="header-search-input">-->
	<!---->
	<!--		<div class="header-search-results"></div>-->
	<!--	</div>-->
<?php
}


add_action( 'wp_ajax_dc_ajax_search', 'deco_cool_search_ajax' );
add_action( 'wp_ajax_nopriv_dc_ajax_search', 'deco_cool_search_ajax' );

//if ( ! function_exists( 'deco_cool_search_ajax' ) ) {
function deco_cool_search_ajax() {

	if ( isset( $_POST['term'] ) ) {

		$search_query = new WP_Query( array(
			's'              => $_POST['term'],
			'post_type'      => array( 'post' ),
			'posts_per_page' => 24,
			//'no_found_rows' => true,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC'
		) );
		if ( $search_query->have_posts() ) {

			while ( $search_query->have_posts() ) {
				$search_query->the_post();
				get_template_part( 'content', 'search' );
			}
			wp_reset_postdata();
		} else {
			header( 'Status: 204 No content' );
		}

	}
	die();
	//}
}

if ( ! function_exists( 'deco_cool_search_ajax_2' ) ) {
	function deco_cool_search_ajax_2() {

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
