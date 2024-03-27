<?php

class Deco_Widget_Tag_Post extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'на эту тему (for tag)', TEXTDOMAIN ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$posts_num = $instance['posts_num'];

		global $post;
		$tags = wp_get_post_tags($post->ID);
		$categories = get_the_category($post->ID);
		$cat_id = $categories[0]->cat_ID;
		
		if ($tags) {
			$first_tag = $tags[0]->term_id;
			//var_dump($first_tag);
		}
		
		$args = array(
			'tag__in' => array($first_tag),
			'post__not_in' => array($post->ID),
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_num,
			'caller_get_posts'=>1

		);

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			?>

			<div class="hr-center"></div>
			<div class="hr"></div>

			<div class="widget read-also">

				<h6 class="main-title-map"><?php echo $title; ?></h6>
					<ul>
					<?php while ( $query->have_posts() ): $query->the_post();?>
						<li>
							<a href="<?php the_permalink(); ?>">
								<h4><?php the_title(); ?></h4>
							</a>

							<div class="entry-meta">
								<span class="entry-date"><abbr class="published" title="<?php the_time('Y-M-D\TH:i:sO'); ?>"><?php the_time('d F в g:i'); ?></abbr></span>
								<span class="meta-sep">&#149;</span>
								<span class="meta-autor"><?php the_author(); ?></span>
			                </div>
			            </li>
					<?php endwhile;
					wp_reset_query();
					?>
					</ul>
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

		$title     = esc_attr( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'На эту тему';
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


add_action( 'widgets_init', create_function( '', 'return register_widget("Deco_Widget_Tag_Post");' ) );
