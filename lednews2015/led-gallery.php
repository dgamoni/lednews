<?php
/*
 * gallery
 * 
 */


global $post;

$slider_args = array(
	'post_type'   => 'ledgallery',
	'posts_per_page' => 1,
	'order'       => 'DESC',
	//'order'       => 'ASC',
	//'orderby'     => 'menu_order',
	'orderby'     => 'date',
	'post_status' => 'publish'
);

//$slider_post = get_posts( $slider_args );

$slider_post = new WP_Query( $slider_args );


if ( $slider_post->have_posts() ): {
	?>
	
	<div id="wrapper" class="container">
		<h6 class="main-title">Новое в галерее</h6>
	</div>

	<div class="led-gallery container-head variable-width">

		<?php while ($slider_post->have_posts() ) : $slider_post->the_post(); ?>
				<?php 
				$images = get_field('ledgallery_field', get_the_ID() );
				//var_dump($images);
				   		
				// loop image 
     			 if( $images ): ?>
				   		<?php $i=0; ?>
				   		<?php $count_loop = get_field('led-gallery-count', 'option'); ?>

				        <?php foreach( $images as $image ): ?>
				        	
				        	<div>
					        	<?php 
					        	$params = array( 'height' => 330 ); 
								echo "<img src='" . bfi_thumb( $image['url'], $params ) . "'/>";
								?>
							</div>

				            <?php // count loop
					            $i++;
								if($i==$count_loop) break;
							?>

				        <?php endforeach; ?>
				<?php endif; ?>
				<!-- end loop image -->
		<?php endwhile; ?>

	</div>

<?php } endif;

wp_reset_query();