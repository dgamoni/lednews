<?php
add_action( 'wp_ajax_deco_get_deputats_news', 'deco_get_deputats_news' );
add_action( 'wp_ajax_nopriv_deco_get_deputats_news', 'deco_get_deputats_news' );
function deco_get_deputats_news( $args_func = array() ) {

	if ( defined( 'DOING_AJAX' ) ) {
		$page  = $_POST['page'];
		$terms = $_POST['terms'];
	}

	if ( empty( $page ) ) {
		$page = 1;
	}
	$page ++;

	$args  = array(
		'post_type'      => 'post',
		'cat'            => 13,
		'posts_per_page' => 8,
		'tax_query'      => array(
			array(
				'taxonomy' => 'deputat',
				'field'    => 'id',
				'terms'    => array( $terms )
			)
		),
		'paged'          => $page
	);
	$query = new WP_Query( $args );

	ob_start();
	$i = 0;
	while ( $query->have_posts() ) :
		$query->the_post();
		$i ++;
		$date_url  = get_site_url() . get_the_date( '/Y/m/d/' );
		list( $time, $date ) = explode( ' ', get_the_time( 'H:m d.n.Y' ) );
		list( $d, $m, $y ) = explode( '.', $date );
		$comment_count = get_comment_count( $post_id )['approved'];
		$post_meta = get_post_meta( get_the_ID() );
		?>
		<?php if ( ( $i == 1 ) || ( $i == 8 ) ) : ?>
		<?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'deco_thumb_560_400' ); ?>
		<div class="achivement__bl is-double">
			<div class="achivement__in"
			     style="background-image: url(<?php echo $thumb_url[0]; ?>)">
				<a class="achivement__date"
				   href="<?php echo $date_url; ?>"><?php echo $d; ?>&nbsp;<?php echo deco_month_num_to_name( $m ); ?></a>

				<div class="achivement__info">
					<div class="achivement__txt">
						<p><?php the_title(); ?></p>

						<p><?php echo deco_cut_text( strip_tags( get_the_content() ), 230 ); ?></p>
					</div>
					<div class="achivement__soc">
						<?php get_template_part( 'content-post-counters' ); ?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="box-url"></a>
			</div>
		</div>
	<?php elseif ( ( $i == 2 ) || ( $i == 7 ) ) : ?>
		<?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'deco_thumb_270_400' ); ?>
		<div class="achivement__bl">
			<div class="achivement__in"
			     style="background-image: url(<?php echo $thumb_url[0]; ?>)">
				<a class="achivement__date"
				   href="<?php echo $date_url; ?>"><?php echo $d; ?>&nbsp;<?php echo deco_month_num_to_name( $m ); ?></a>

				<div class="achivement__info">
					<div class="achivement__txt">
						<p><?php the_title(); ?></p>
					</div>
					<div class="achivement__soc">
						<?php get_template_part( 'content-post-counters' ); ?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="box-url"></a>
			</div>
		</div>
	<?php else : ?>
		<?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'deco_thumb_270_400' ); ?>
		<?php if ( ( $i == 5 ) || ( $i == 3 ) ) : ?>
			<div class="achivement__block">
		<?php endif; ?>
		<div class="achivement__bl">
			<div class="achivement__in"
			     style="background-image: url(<?php echo $thumb_url[0]; ?>)">
				<a class="achivement__date"
				   href="<?php echo $date_url; ?>"><?php echo $d; ?>&nbsp;<?php echo deco_month_num_to_name( $m ); ?></a>

				<div class="achivement__info">
					<div class="achivement__txt">
						<p><?php the_title(); ?></p>
					</div>
					<div class="achivement__soc">
						<?php get_template_part( 'content-post-counters' ); ?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="box-url"></a>
			</div>
		</div>
		<?php if ( ( $i == 6 ) || ( $i == 4 ) ) : ?>
			</div>
		<?php endif;
	endif;
	endwhile;

	$content = ob_get_contents();
	ob_end_clean();

	if ( ! defined( 'DOING_AJAX' ) ) {
		return $content;
	} else {
		$res['content']   = $content;
		$res['page']      = $page;
		$res['max_pages'] = $query->max_num_pages;

		die( json_encode( $res ) );
	}
}

add_action( 'wp_ajax_deco_get_not_deputats_news', 'deco_get_not_deputats_news' );
add_action( 'wp_ajax_nopriv_deco_get_not_deputats_news', 'deco_get_not_deputats_news' );
function deco_get_not_deputats_news( $args_func = array() ) {

	if ( defined( 'DOING_AJAX' ) ) {
		$page  = $_POST['page'];
		$terms = $_POST['terms'];
	}

	if ( empty( $page ) ) {
		$page = 1;
	}
	$page ++;

	$args  = array(
		'post_type'      => 'post',
		'cat'            => 13,
		'posts_per_page' => 4,
		'tax_query'      => array(
			array(
				'taxonomy' => 'deputat',
				'field'    => 'id',
				'operator' => 'NOT_IN',
				'terms'    => array( $terms )
			)
		),
		'paged'          => $page
	);
	$query = new WP_Query( $args );

	ob_start();
	$i = 0;
	while ( $query->have_posts() ) :
		$query->the_post();
		get_template_part( 'content', 'small-news' );
	endwhile;

	$content = ob_get_contents();
	ob_end_clean();

	if ( ! defined( 'DOING_AJAX' ) ) {
		return $content;
	} else {
		$res['content']   = $content;
		$res['page']      = $page;
		$res['max_pages'] = $query->max_num_pages;

		die( json_encode( $res ) );
	}
}

add_action( 'wp_ajax_deco_get_archive_news', 'deco_get_archive_news' );
add_action( 'wp_ajax_nopriv_deco_get_archive_news', 'deco_get_archive_news' );
function deco_get_archive_news( $args_func = array() ) {

	if ( defined( 'DOING_AJAX' ) ) {
		$page          = $_POST['page'];
		$category_name = $_POST['category_name'];
		$year          = $_POST['year'];
		$month         = $_POST['month'];
		$day           = $_POST['day'];
		$author        = $_POST['author'];
		$tag           = $_POST['tag'];
	}

	if ( empty( $page ) ) {
		$page = 1;
	}
	$page ++;

	$args = array(
		'post_type'      => 'post',
		'cat'            => 13,
		'posts_per_page' => 16,
		'paged'          => $page
	);

	if ( $category_name ) {
		$args['category_name'] = $category_name;
	}

	if ( $year ) {
		$args['year'] = $year;
	}

	if ( $month ) {
		$args['monthnum'] = $month;
	}

	if ( $day ) {
		$args['day'] = $day;
	}

	if ( $author ) {
		$args['author)name'] = $author;
	}

	if ( $tag ) {
		$args['tag'] = $tag;
	}

	$query = new WP_Query( $args );

	ob_start();
	$i = 0;
	while ( $query->have_posts() ) :
		$query->the_post();
		$i ++;
		$date_url  = get_site_url() . get_the_date( '/Y/m/d/' );
		list( $time, $date ) = explode( ' ', get_the_time( 'H:m d.n.Y' ) );
		list( $d, $m, $y ) = explode( '.', $date );
		$comment_count = get_comment_count( $post_id )['approved'];
		$post_meta = get_post_meta( get_the_ID() );
		?>
		<?php if ( ( $i == 1 ) || ( $i == 8 ) ) : ?>
		<?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'deco_thumb_560_400' ); ?>
		<div class="achivement__bl is-double">
			<div class="achivement__in"
			     style="background-image: url(<?php echo $thumb_url[0]; ?>)">
				<a class="achivement__date"
				   href="<?php echo $date_url; ?>"><?php echo $d; ?>&nbsp;<?php echo deco_month_num_to_name( $m ); ?></a>

				<div class="achivement__info">
					<div class="achivement__txt">
						<p><?php echo deco_cut_text( get_the_title(), 50 ); ?></p>

						<p><?php echo deco_cut_text( strip_tags( get_the_content() ), 230 ); ?></p>
					</div>
					<div class="achivement__soc">
						<?php get_template_part( 'content-post-counters' ); ?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="box-url"></a>
			</div>
		</div>
	<?php elseif ( ( $i == 2 ) || ( $i == 7 ) ) : ?>
		<?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'deco_thumb_270_400' ); ?>
		<div class="achivement__bl">
			<div class="achivement__in"
			     style="background-image: url(<?php echo $thumb_url[0]; ?>)">
				<a class="achivement__date"
				   href="<?php echo $date_url; ?>"><?php echo $d; ?>&nbsp;<?php echo deco_month_num_to_name( $m ); ?></a>

				<div class="achivement__info">
					<div class="achivement__txt">
						<p><?php the_title(); ?></p>
					</div>
					<div class="achivement__soc">
						<?php get_template_part( 'content-post-counters' ); ?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="box-url"></a>
			</div>
		</div>
	<?php else : ?>
		<?php $thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'deco_thumb_270_400' ); ?>
		<?php if ( ( $i == 5 ) || ( $i == 3 ) ) : ?>
			<div class="achivement__block">
		<?php endif; ?>
		<div class="achivement__bl">
			<div class="achivement__in"
			     style="background-image: url(<?php echo $thumb_url[0]; ?>)">
				<a class="achivement__date"
				   href="<?php echo $date_url; ?>"><?php echo $d; ?>&nbsp;<?php echo deco_month_num_to_name( $m ); ?></a>

				<div class="achivement__info">
					<div class="achivement__txt">
						<p><?php the_title(); ?></p>
					</div>
					<div class="achivement__soc">
						<?php get_template_part( 'content-post-counters' ); ?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="box-url"></a>
			</div>
		</div>
		<?php if ( ( $i == 6 ) || ( $i == 4 ) ) : ?>
			</div>
		<?php endif;
	endif;
		if ( $i == 8 ) {
			$i = 0;
		}
	endwhile;

	$content = ob_get_contents();
	ob_end_clean();

	if ( ! defined( 'DOING_AJAX' ) ) {
		return $content;
	} else {
		$res['content']   = $content;
		$res['page']      = $page;
		$res['max_pages'] = $query->max_num_pages;

		die( json_encode( $res ) );
	}
}
