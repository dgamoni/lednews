<?php

class Deco_Widget_Premium_Seller extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'Премиум-продавцы', TEXTDOMAIN ) );

	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$posts_num = $instance['posts_num'];

		$args  = array(
			'post_type'      => 'company',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_num,
			'orderby'        => 'post_title',
			'meta_query'     => array(
				array(
					'key'   => 'deco_company_premium',
					'value' => 1,
					'type'  => 'numeric'
				)
			)
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			?>
			<div class="widget">
				<div class="widget-head">
					Премиум-продавцы
				</div>
				<div class="widget-content">
					<ul class="top-sellers">
						<?php
						while ( $query->have_posts() ) {
							$query->the_post();
							$post_id = $query->post->ID;
							$company = deco_company_get_meta( 'post_id', $post_id );
							?>
							<li class="top-seller-item">
								<a href="<?php echo $company->url; ?>">
									<div class="img-cont">
										<img src="<?php echo $company->image; ?>" style="width:55px;" />
									</div>
									<div class="tsi-name"><?php echo $company->name; ?></div>
								</a>
							</li>
							<?php
						}
						wp_reset_query();
						?>
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

		$title     = esc_attr( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Премиум-продавцы';
		$posts_num = $instance['posts_num'] ? $instance['posts_num'] : 5;


		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
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

add_action( 'widgets_init', create_function( '', 'return register_widget("Deco_Widget_Premium_Seller");' ) );