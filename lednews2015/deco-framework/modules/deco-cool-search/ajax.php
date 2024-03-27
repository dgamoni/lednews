<?php
if ( ! function_exists( 'dc_ajax_search' ) ) {
	function dc_ajax_search() {

		if ( isset( $_POST['term'] ) ) {


//			post,page,peoples,projects,deputats,reforms,commissionstable
//,commissiontemp,appeals,requests,draftdecisions,votings,answers,fraction,candidat
//
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
				?>
				<?php
				while ( $search_query->have_posts() ) {
					$search_query->the_post();
					$post_id = $search_query->post->ID;
					$image   = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'deco_thumb_280_200' );
					if ( $image[0] ) {
						$img = $image[0];
					}
					list( $time, $date ) = explode( ' ', get_the_time( 'H:m d.n.Y' ) );
					list( $d, $m, $y ) = explode( '.', $date );
					$coment_count = get_comment_count( $post_id );
					$coment_count = $coment_count['approved'];
					$link         = get_the_permalink();
					$now_year     = date( 'Y' );
					$counts       = deco_get_post_counts( $post_id );
					?>
					<div class="search-tile">
						<div class="st-img" style="background-image: url(
                        <?php if($img){ echo $img;} else {  echo DECO_THEME_URL; ?>/assets/img/placeholder.jpg<?php } ?>)"></div>
                        <div class="st-title">
                            <time ><?php echo $d . ' ' . deco_month_num_to_name( $m ); ?><?php echo ( $y != $now_year ) ? $y : ''; ?></time>

                            <p><?php the_title(); ?></p>
                        </div>
                        <a class="st-link" href="<?php echo $link; ?>"></a>
					</div>

					<?php
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