<?php

class Deco_Widget_Top_Banner7 extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'single-баннер', TEXTDOMAIN ) );

	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$content = $instance['content'];
		?>
			<div class="widget single-banner">
				<?php if ( $content ) { ?>
					<?php echo $content; ?>
				<?php } else { ?>
					<a href="http://maps.lighting/">
						<img src="<?php echo DECO_THEME_URL; ?>images/banner-single.png">
					</a>
				<?php } ?>
			</div>

		<?php
	}


	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['content'] = $new_instance['content'];

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {

		/**
		 * Set Default Value for widget form
		 */

		$content = $instance['content'];

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>">Код банера:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>"
			       name="<?php echo $this->get_field_name( 'content' ); ?>" type="text" value="<?php echo $content; ?>" />
		</p>
		<?php
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("Deco_Widget_Top_Banner7");' ) );