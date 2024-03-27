<?php
/*
 * slider
 * 
 */


global $post;

$count_slider = get_field('led-slider-count', 'option');

$slider_args = array(
	'post_type'   => 'ledslider',
	'posts_per_page' => $count_slider,
	'order'       => 'DESC',
	//'order'       => 'ASC',
	'orderby'     => 'menu_order',
	//'orderby'     => 'date',
	'post_status' => 'publish'
);

//$slider_post = get_posts( $slider_args );

$slider_post = new WP_Query( $slider_args );


if ( $slider_post->have_posts() ): {
	?>

	<div class="led-slider container-head multiple-items">

		<?php while ($slider_post->have_posts() ) : $slider_post->the_post(); ?>
				<?php 
				$images = get_field('ledslider_image', get_the_ID() );
				$text = get_field('ledslider_text', get_the_ID() );
				$ledslider_link = get_field('ledslider_link', get_the_ID() );
				//$code = get_field('ledslider_flash', get_the_ID() );
				//var_dump($images);

     			 if( $images ): ?>
				        	
				        	<div class="ledslider_content">
				        		<a href="<?php echo $ledslider_link; ?>">
						        	<?php
						        	echo '<p class="ledslider_text">'. $text .'</p>'; 
						        	$params = array( 'height' => 100, 'width' => 300 ); 
									echo "<img src='" . bfi_thumb( $images['url'], $params ) . "'/>";
									//echo $code;
									?>
								</a>
							</div>

				<?php endif; ?>
				<!-- end loop image -->
		<?php endwhile; ?>

	</div>

<?php } endif;

wp_reset_query();