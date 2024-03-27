<?php

class Deco_Recommends_led_Widget extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'Рекомендуем (последние из категорий)', TEXTDOMAIN ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'] );
		//$posts_num = $instance['posts_num'];
		$posts_num = 3;


		global $post;
		
		// news id=2
		// articles id=3
		// forum id =39

		$posts      = array();
		$categories = array(2,39,3);
		foreach($categories as $cat) {
		  	// $posts[] = get_posts('numberposts=3&offset=1&category='.$cat);
			$posts[] = get_posts('numberposts=3&offset=1&orderby=post_date&order=DESC&category='.$cat);
		}
		//var_dump($posts);


		if ($posts) {	?>

			<div class="hr-center"></div>
			<div class="hr"></div>

			<div class="widget-recomends">
				
				<h6 class="main-title-map"><?php echo $title; ?></h6>

				<div class="">
					<ul>
						<?php
						foreach($posts as $key=>$postss){ 
							
							$post =  $postss[$key];
							//var_dump($post);

							setup_postdata($post);

							$post_id = get_the_ID();

							$categories = get_the_category($post_id);
							$cat_link = get_category_link($categories[0]->cat_ID);
							$decollete_post_views = get_field( "decollete_post_views", $post_id ); 
							//echo $decollete_post_views;
							//$sum = $query->post->deco_votes_sum;
							// $decollete_post_views = get_post_meta( $post_id, "decollete_post_views", true ) );
							?>
							<li>
								<?php if( has_post_thumbnail() ) { ?>
									<div class="widget-recomends-img pull-left">
										<?php the_post_thumbnail( array( 190, 125,  'bfi_thumb' => true ) );	?>
									</div>
								<?php } ?>

								<div class="widget-recomends-content">
									<a class="entry-cat" href="<?php echo $cat_link; ?>"><?php echo $categories[0]->cat_name; ?></a>
									<h3 class="entry-title">
										<a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title($post_id); ?></a>
									</h3>
									<div class="entry-meta">
							        	<span class="entry-date"><abbr class="published" title="<?php the_time('Y-M-D\TH:i:sO'); ?>"><?php the_time('d F в g:i'); ?></abbr></span>
										<span class="meta-sep">&#149;</span>
										<span class="meta-autor"><?php the_author(); ?></span>
							        </div>
							        <div class="entry-content">
				   						<p><?php echo codium_now_cleanCut(get_the_excerpt(), 180); ?></p>
				   					</div>

								</div>
								<div class="clear"></div>
							</li>
						<?php
						} // end foreach
						wp_reset_postdata();
						?>
					</ul>
				</div>
			</div>
		<?php
		} // end if
		
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']     = strip_tags( $new_instance['title'] );
		// $instance['posts_num'] = strip_tags( $new_instance['posts_num'] );

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {

		/**
		 * Set Default Value for widget form
		 */

		$title     = esc_attr( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'рекомендованные';
		//$posts_num = $instance['posts_num'] ? $instance['posts_num'] : 3;

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo $title; ?>" />
		</p>
<!-- 		<p>
			<label for="<?php echo $this->get_field_id( 'posts_num' ); ?>">Количество записей:</label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id( 'posts_num' ); ?>"
			       name="<?php echo $this->get_field_name( 'posts_num' ); ?>" type="text"
			       value="<?php echo $posts_num; ?>" size="3" />
		</p> -->

	<?php
	}
}


add_action( 'widgets_init', create_function( '', 'return register_widget("Deco_Recommends_led_Widget");' ) );
