<?php

class Deco_Widget_Top_Banner extends WP_Widget {

	/** constructor */
	function __construct() {

		parent::WP_Widget( false, $name = __( 'Правый-баннер', TEXTDOMAIN ) );

	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		extract( $args );

		$content = $instance['content'];
		$link = $instance['link'];

		?>
		<div class="widget side-banner">
			<?php if ( $content ) { ?>
				<?php echo $content; ?>
			<?php } else { ?>
				
						
						<?php if ($link ) { ?>
							<img src="<?php echo $link; ?>">
						<?php } else { ?>
							<img src="<?php echo DECO_THEME_URL; ?>images/banner_300_500.png" alt="" />
						<?php } ?>

			<?php } ?>
		</div>
		<?php
	}


	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['content'] = $new_instance['content'];
		$instance['link'] = $new_instance['link'];

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {

		/**
		 * Set Default Value for widget form
		 */

		$content = $instance['content'];
		$link = $instance['link'];

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>">Код банера:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>"
			       name="<?php echo $this->get_field_name( 'content' ); ?>" type="text" value="<?php echo $content; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>">default image link (240x400) or (300x500)</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>"
			       name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>" />
		</p>
		<?php
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("Deco_Widget_Top_Banner");' ) );