<?php

add_action( 'wp_ajax_deco_single_reforms_tag_theme', 'deco_single_reforms_tag_theme' );
add_action( 'wp_ajax_nopriv_deco_single_reforms_tag_theme', 'deco_single_reforms_tag_theme' );
function deco_single_reforms_tag_theme( $post_id = 0 ) {

	if ( empty( $post_id ) && isset( $_POST['post_id'] ) ) {
		$post_id = $_POST['post_id'];
	}

	$page = isset( $_POST['page'] ) ? $_POST['page'] : '';

	if ( empty( $page ) ) {
		$page = 1;
	}

	$terms = wp_get_post_terms( $post_id, 'tagtheme' );

	foreach ( $terms as $term ) {
		$terms_arr[] = $term->term_id;
	}

	$args = array(
		'post_type'      => 'any',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'post__not_in'   => array( $post_id ),
		'paged'          => $page,
		'tax_query'      => array(
			array(
				'taxonomy' => 'tagtheme',
				'field'    => 'term_id',
				'terms'    => $terms_arr
			)
		)
	);
	ob_start();
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		?>
		<div class="do-it-inner">
			<?php while ( $query->have_posts() ): $query->the_post();
				$post_type_object = get_post_type_object( get_post_type( get_the_ID() ) );
				$term_name        = $post_type_object->labels->name;
				?>
				<div class="do-it-box">
					<div class="small-type"><?php echo $post_type_object->labels->name; ?></div>
					<div class="entry-date">
						<?php echo get_the_time( 'd.m.Y' ); ?>
					</div>
					<a class="entry-title" href="<?php echo get_permalink(); ?>">
						<?php echo get_the_title(); ?>
					</a>
				</div>
			<?php endwhile; ?>
		</div>
	<?php }
	wp_reset_query();
	$content = ob_get_contents();
	ob_end_clean();

	$page ++;
	$res['content'] = $content;
	if ( $page < $query->max_num_pages ) {
		$res['paginate'] = '<button class="btn-more" data-page="' . $page . '" data-not-in="' . $post_id . '" onclick="Reform_single_posts.load(this); return false;">Більше</button>';
	}
	if ( defined( 'DOING_AJAX' ) ) {
		die( json_encode( $res ) );
	}

	return $res;

}