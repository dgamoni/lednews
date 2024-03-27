<?php

/**
 * Form settings view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="form">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'FORM', 'signals' ); ?></div>
		<p><?php _e( 'Form design settings for the plugin. These settings affect the appearance of the Mail Chimp subscription form. You can customize styles for the input field and button.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_text" class="signals-strong"><?php _e( 'Input Text', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_input_text" id="signals_csmm_input_text" value="<?php esc_attr_e( stripslashes( $signals_csmm_options['input_text'] ) ); ?>" placeholder="<?php _e( 'Text for the Input field', 'signals' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php _e( 'Enter the text which you would like to use as a placeholder text for the text input field.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_text" class="signals-strong"><?php _e( 'Button Text', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_button_text" id="signals_csmm_button_text" value="<?php esc_attr_e( stripslashes( $signals_csmm_options['button_text'] ) ); ?>" placeholder="<?php _e( 'Text for the Button', 'signals' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php _e( 'Enter the text for the button. Usually, it will be "Subscribe" or something like that.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_ignore_styles" class="signals-strong"><?php _e( 'Ignore Default Form Styles?', 'signals' ); ?></label>
				<input type="checkbox" class="signals-form-ios" name="signals_csmm_ignore_styles" value="1"<?php checked( '1', $signals_csmm_options['ignore_form_styles'] ); ?>>

				<p class="signals-form-help-block"><?php _e( 'Enable this option if you would like to use your custom form styles. The settings below will only be applicable when this option is turned on.', 'signals' ); ?></p>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_size" class="signals-strong"><?php _e( 'Input Text Size', 'signals' ); ?></label>

					<select name="signals_csmm_input_size" id="signals_csmm_input_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								echo '<option value="' . $i . '"' . selected( $signals_csmm_options['input_font_size'], $i ) . '>' . $i . __( 'px', 'signals' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php _e( 'Font size for the text input field.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_size" class="signals-strong"><?php _e( 'Button Text Size', 'signals' ); ?></label>

					<select name="signals_csmm_button_size" id="signals_csmm_button_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								echo '<option value="' . $i . '"' . selected( $signals_csmm_options['button_font_size'], $i ) . '>' . $i . __( 'px', 'signals' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php _e( 'Font size for the button text.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_color" class="signals-strong"><?php _e( 'Input Text Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_input_color" id="signals_csmm_input_color" value="<?php esc_attr_e( $signals_csmm_options['input_font_color'] ); ?>" placeholder="<?php _e( 'Font color for the Input text', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select font color for the input text field.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_color" class="signals-strong"><?php _e( 'Button Text Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_button_color" id="signals_csmm_button_color" value="<?php esc_attr_e( $signals_csmm_options['button_font_color'] ); ?>" placeholder="<?php _e( 'Font color for the Button text', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select font color for the button text.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_bg" class="signals-strong"><?php _e( 'Input Background Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_input_bg" id="signals_csmm_input_bg" value="<?php esc_attr_e( $signals_csmm_options['input_bg'] ); ?>" placeholder="<?php _e( 'Background color for the Input field', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select background color for the input text field.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_bg" class="signals-strong"><?php _e( 'Button Background Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_button_bg" id="signals_csmm_button_bg" value="<?php esc_attr_e( $signals_csmm_options['button_bg'] ); ?>" placeholder="<?php _e( 'Background color for the Button', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select background color for the button.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_bg_hover" class="signals-strong"><?php _e( 'Input Background Color', 'signals' ); ?> <span class="signals-red-color"><?php _e( 'FOCUS', 'signals' ); ?></span></label>
					<input type="text" name="signals_csmm_input_bg_hover" id="signals_csmm_input_bg_hover" value="<?php esc_attr_e( $signals_csmm_options['input_bg_hover'] ); ?>" placeholder="<?php _e( 'Background color for the Input field when it gets clicked', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select background color for the input text field when it gets clicked.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_bg_hover" class="signals-strong"><?php _e( 'Button Background Color', 'signals' ); ?> <span class="signals-red-color"><?php _e( 'HOVER', 'signals' ); ?></span></label>
					<input type="text" name="signals_csmm_button_bg_hover" id="signals_csmm_button_bg_hover" value="<?php esc_attr_e( $signals_csmm_options['button_bg_hover'] ); ?>" placeholder="<?php _e( 'Background color for the Button on hover', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select background color for the button on mouse hover.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_border" class="signals-strong"><?php _e( 'Input Border Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_input_border" id="signals_csmm_input_border" value="<?php esc_attr_e( $signals_csmm_options['input_border'] ); ?>" placeholder="<?php _e( 'Border color for the Input field', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select border color for the input field.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_border" class="signals-strong"><?php _e( 'Button Border Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_button_border" id="signals_csmm_button_border" value="<?php esc_attr_e( $signals_csmm_options['button_border'] ); ?>" placeholder="<?php _e( 'Border color for the Button', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select border color for the button.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_border_hover" class="signals-strong"><?php _e( 'Input Border Color', 'signals' ); ?> <span class="signals-red-color"><?php _e( 'FOCUS', 'signals' ); ?></span></label>
					<input type="text" name="signals_csmm_input_border_hover" id="signals_csmm_input_border_hover" value="<?php esc_attr_e( $signals_csmm_options['input_border_hover'] ); ?>" placeholder="<?php _e( 'Border color for the Input field when it gets clicked', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select border color for the input field when it gets clicked.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_border_hover" class="signals-strong"><?php _e( 'Button Border Color', 'signals' ); ?> <span class="signals-red-color"><?php _e( 'HOVER', 'signals' ); ?></span></label>
					<input type="text" name="signals_csmm_button_border_hover" id="signals_csmm_button_border_hover" value="<?php esc_attr_e( $signals_csmm_options['button_border_hover'] ); ?>" placeholder="<?php _e( 'Border color for the Button on hover', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select border color for the button on mouse hover.', 'signals' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div><!-- #form -->