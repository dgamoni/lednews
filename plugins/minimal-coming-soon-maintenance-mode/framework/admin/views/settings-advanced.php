<?php

/**
 * Advanced settings view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="advanced">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'ADVANCED', 'signals' ); ?></div>
		<p><?php _e( 'You can add custom HTML & CSS in this section. Making wrong changes over here will affect the working of the plugin.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<div class="signals-form-group">
				<label for="signals_csmm_disable" class="signals-strong"><?php _e( 'Use Custom HTML only', 'signals' ); ?></label>
				<input type="checkbox" class="signals-form-ios" name="signals_csmm_disable" value="1"<?php checked( '1', $signals_csmm_options['disable_settings'] ); ?>>

				<p class="signals-form-help-block"><?php _e( 'If you enable this option, the plugin will ignore everything except the HTML you provide.', 'signals' ); ?></p>
				<p class="signals-form-help-block"><?php _e( 'Basically, you will have a blank template which you can fill with your provided html content. Only basic css gets added by the plugin which does the task of browser styling reset. You should style your html content either inline or by inserting styling in the custom css section. In short, use this option only if you know what you are doing.', 'signals' ); ?></p>
			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_html" class="signals-strong"><?php _e( 'Custom HTML', 'signals' ); ?></label>
				<div id="signals_csmm_html_editor"></div>
				<textarea name="signals_csmm_html" id="signals_csmm_html" rows="8" placeholder="<?php _e( 'Custom HTML for the plugin', 'signals' ); ?>"><?php echo stripslashes( $signals_csmm_options['custom_html'] ); ?></textarea>

				<p class="signals-form-help-block"><?php echo __( 'Custom HTML for the plugin goes over here. Please note that ', 'signals' ) . '<i style="color: #f96773">' . __( '[html], [head], [title], [meta], [body], and similar tags', 'signals' ) . '</i>' . __( ' are not required. Only provide content HTML for the page.', 'signals' ); ?></p>
				<p class="signals-form-help-block"><?php _e( 'To insert subscription form anywhere in the html, simply use the placeholder <strong>{{form}}</strong> and you are done.', 'signals' ); ?></p>
			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_css" class="signals-strong"><?php _e( 'Custom CSS', 'signals' ); ?></label>
				<div id="signals_csmm_css_editor"></div>
				<textarea name="signals_csmm_css" id="signals_csmm_css" class="Signals_csmm_Block" rows="8" placeholder="<?php _e( 'Custom CSS for the plugin', 'signals' ); ?>"><?php echo stripslashes( $signals_csmm_options['custom_css'] ); ?></textarea>

				<p class="signals-form-help-block"><?php _e( 'Custom CSS for the plugin goes over here.', 'signals' ); ?></p>
			</div>
		</div>
	</div>
</div><!-- #advanced -->