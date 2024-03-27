<?php

class Deco_Widget_Read_Also extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'Читать также', TEXTDOMAIN ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$posts_num = $instance['posts_num'];

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 3,

		);

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			?>

			<div class="widget">
				<div class="widget-head">
					<?php echo $title; ?>
				</div>
				<div class="widget-content">
					<ul class="read-also">
						<?php while ( $query->have_posts() ): $query->the_post();
							$model_image = wp_get_attachment_url( get_post_thumbnail_id( $query->post->ID ) );
							$image       = deco_bfi_thumb( $model_image, array( 'height' => 71, 'crop' => true ) );
							?>
							<li class="read-also-item">
								<a href="<?php the_permalink(); ?>">
									<div class="img-cont">
										<img src="<?php echo $image; ?>" style="max-width:71px" />
									</div>
									<div class="tsi-name"><?php the_title(); ?></div>
								</a>
							</li>
						<?php endwhile;
						wp_reset_query();
						?>
						<a class="blue-link readmore" href="">
							<i class="icon-more"></i>
							Читать все
						</a>
					</ul>
				</div>
			</div>
			<?php
		}
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['posts_num'] = strip_tags( $new_instance['posts_num'] );

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {

		/**
		 * Set Default Value for widget form
		 */

		$title     = esc_attr( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Читать также';
		$posts_num = $instance['posts_num'] ? $instance['posts_num'] : 3;
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_num' ); ?>">Количество записей:</label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id( 'posts_num' ); ?>"
			       name="<?php echo $this->get_field_name( 'posts_num' ); ?>" type="text"
			       value="<?php echo $posts_num; ?>" size="3" />
		</p>

		<?php
	}
}


add_action( 'widgets_init', create_function( '', 'return register_widget("Deco_Widget_Read_Also");' ) );
