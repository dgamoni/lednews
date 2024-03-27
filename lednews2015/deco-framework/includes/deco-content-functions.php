<?php
function deco_get_deputats_home_block( $nocache = false ) {
	$key = 'deco_get_deputats_home_block';
	if ( isset( $_GET['flush_cache'] ) ) {
		$nocache = true;
	}
	$content = get_transient( $key );
	if ( empty( $content ) || $nocache ) {

		$args  = array(
			'post_type'      => 'deputats',
			'post_status'    => 'publish',
			'posts_per_page' => 5
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ):
				$query->the_post();
				get_template_part( 'parts/home/content-deputat' );
			endwhile;
			$content = ob_get_contents();
			ob_end_clean();

			wp_reset_query();
		}
		$content = base64_encode( $content );
		set_transient( $key, $content, 24 * 60 * 60 );
	}

	return base64_decode( $content );
//	return $content;

}

function deco_get_reforms_home_block( $nocache = false ) {
	$key           = 'deco_get_reforms_home_block';
	$key_for_array = 'deco_get_reforms_array';
	if ( isset( $_GET['flush_cache'] ) ) {
		$nocache = true;
	}
	$content = get_transient( $key );
	if ( empty( $content ) || $nocache ) {
		ob_start();
		$args  = array(
			'post_type'      => 'reforms',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ):
				$query->the_post();
				$reforms_array[] = $query->post;
				get_template_part( 'parts/home/block-reform' );
			endwhile;
			wp_reset_query();
		}
		$content = ob_get_contents();
		ob_end_clean();
		$content = base64_encode( $content );
		set_transient( $key, $content, 24 * 60 * 60 );
		update_option( $key_for_array, serialize( $reforms_array ) );
	}

	return base64_decode( $content );
//	return $content;

}

function deco_get_blog_home_block( $nocache = false ) {
	$key = 'deco_get_blog_home_block';


	if ( isset( $_GET['flush_cache'] ) ) {
		$nocache = true;
	}
	$content = get_transient( $key );
	if ( empty( $content ) || $nocache ) {
		ob_start();
		$args  = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'tax_query'      => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => array(
						'post-format-video',
						'post-format-gallery',
						'post-format-quote',
						'post-format-link'
					),
					'operator' => 'NOT IN'
				)
			)
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			$reforms_array = '';
			while ( $query->have_posts() ):
				$query->the_post();
				global $postt;
				$postt = $query->post;
				include DECO_THEME_DIR . 'parts/home/block-blog.php';
			endwhile;
			wp_reset_query();
		}
		$content = ob_get_contents();
		ob_end_clean();
		$content = base64_encode( $content );
		set_transient( $key, $content, 24 * 60 * 60 );
	}

	return base64_decode( $content );
//	return $content;

}

function deco_get_archive_date_month_and_year( $nocache = false ) {
	$key = 'deco_get_archive_date_month_and_year';
	if ( isset( $_GET['flush_cache'] ) ) {
		$nocache = true;
	}
	$content = unserialize( get_transient( $key ) );
	if ( empty( $content ) || $nocache ) {
		global $wpdb;
		$dates = $wpdb->get_results( "select DATE_FORMAT(post_date,'%m-%Y') as date from {$wpdb->posts} group by date order by post_date DESC" );
		set_transient( $key, serialize( $dates ), 24 * 60 * 60 );
	}

	return $dates;

}

function deco_get_reforms_array() {
	$key_for_array = 'deco_get_reforms_array';

	return unserialize( get_option( $key_for_array ) );

}


function deco_get_vid_diyalnosti_home_block( $nocache = false ) {
	$key = 'deco_get_vid_diyalnosti_home_block';
	if ( isset( $_GET['flush_cache'] ) ) {
		$nocache = true;
	}
	$content = unserialize( get_transient( $key ) );
	if ( empty( $content ) || $nocache ) {
		$content = deco_get_zviv_pro_robotu();
		$content = base64_encode( serialize( $content ) );
		set_transient( $key, $content, 24 * 60 * 60 );
	}

	return base64_decode( $content );
//	return $content;
}

function deco_get_vid_diyalnosti_archive( $nocache = false ) {
	$key = 'deco_get_vid_diyalnosti_archive';
	if ( isset( $_GET['flush_cache'] ) ) {
		$nocache = true;
	}
	$content = unserialize( get_transient( $key ) );
	if ( empty( $content ) || $nocache ) {
		$content = deco_get_zviv_pro_robotu_archive();
		$content = base64_encode( $content );
		set_transient( $key, $content, 24 * 60 * 60 );
	}

	return base64_decode( $content );
//	return $content;
}
