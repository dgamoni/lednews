<?php

class forum_widget_lasttopics_comments_Widget extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'Форум - обсуждаемое', TEXTDOMAIN ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$posts_num = $instance['posts_num'];

		echo '<div class="widget forum-widget forum-widget-comments">';
			echo '<h6 class="main-title-map">'.$title.'</h6>';
			echo do_shortcode('[lasttopics_comments count="'.$posts_num.'" comments="0"]');
		
			echo '<div class="hr-center"></div><div class="hr"></div>';
		echo '</div>';

		
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

		$title     = esc_attr( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Обсуждаемое';
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


add_action( 'widgets_init', create_function( '', 'return register_widget("forum_widget_lasttopics_comments_Widget");' ) );
