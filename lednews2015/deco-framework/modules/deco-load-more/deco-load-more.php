<?php


function deco_load_more( $args = array() ) {
	global $wp_query;

	//print_r( $wp_query );

	$defaults = array(
		'wpquery'        => array(),
		'content_block'  => '#main',
		'posts_per_page' => 6,
		'post_type'      => '',
		'max_num_pages'  => '',
		'uri'            => '',
		'is_search'      => '',
		'is_author'      => '',
		'lang'           => '',
		'is_category'    => false,
	);

	$args = wp_parse_args( $args, $defaults );

	extract( $args, EXTR_SKIP );


	if ( empty( $wpquery ) ) {
		$wpquery = $wp_query;
	}

	if ( empty( $max_num_pages ) ) {
		$max_num_pages = $wpquery->max_num_pages;
	}

	//print_r( $wp_query );
	if ( $max_num_pages == 1 ) {
		return;
	}

	$page = $wpquery->query_vars['paged'];
	if ( empty( $page ) ) {
		$page = 1;
	}

	$max_pages = $max_num_pages;

	if ( function_exists( 'qtrans_getLanguage' ) && empty( $lang ) ) {
		$lang = qtrans_getLanguage();
	}

	?>
	<div id="deco-paginate">
		<?php
		if ( $page + 1 <= $max_pages ) {
			?>
			<a href="" class="download-more"
			   data-current="<?php echo $page; ?>"
			   data-page="<?php echo $page + 1; ?>"
			   data-cat="<?php echo $wpquery->query_vars['cat']; ?>"
			   data-tax-type="<?php echo 'types'; ?>"
			   data-name-type="<?php echo get_query_var( 'type_cat' ); ?>"
			   data-post-per-page="<?php echo $wpquery->query_vars['post_per_page']; ?>"
			   data-uri="<?php echo $uri ? $uri : $_SERVER['REQUEST_URI']; ?>"
			   data-post-not-in="<?php echo ( ! $is_category ) ? deco_get_post_not_in( $wpquery ) : ''; ?>"
			   data-content-block="<?php echo $content_block; ?>"
			   data-max-num-pages="<?php echo $max_num_pages; ?>"
			   data-is-search="<?php echo ( is_search() || $is_search ) ? 1 : ''; ?>"
			   data-is-author="<?php echo ( is_author() || $is_author ) ? 1 : ''; ?>"
			   data-lang="<?php echo $lang; ?>"
				>

				<?php
				_e( 'Показать больше', TEXTDOMAIN );
				?>
			</a>
		<?php
		}
		?>



		<div class="pagenation">
			<?php

			echo deco_pagination( array(
				'total'     => $max_pages,
				'current'   => $page,
				'prev_text' => '',
				'next_text' => '',
				'uri'       => $uri,
			) );
			?>
		</div>
	</div>

<?php
}
