<?php

/**
 * Support view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="support">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'SUPPORT', 'signals' ); ?></div>
		<p><?php _e( 'Getting help is just a click away now. Report issue using the form below and we will get back to you at your admin email address. If the below support form is not working for you, kindly send us an email at ', 'signals' ); ?><a href="mailto:support@69signals.com">support@69signals.com</a><?php _e( ' explaining the issue you are facing with the plugin.', 'signals' ); ?></p>

		<div class="signals-section-content signals-support-form">
			<div class="signals-ajax-response"></div>

			<div class="signals-form-group">
				<label for="signals_support_email" class="signals-strong"><?php _e( 'Email Address', 'signals' ); ?></label>
				<input type="text" name="signals_support_email" id="signals_support_email" value="<?php echo esc_attr_e( $signals_admin_email ); ?>" placeholder="<?php _e( 'Please provide your email address', 'signals' ); ?>" class="signals-form-control">

				<p class="signals-form-help-block"><?php _e( 'You will receive support response at this email address.', 'signals' ); ?></p>
			</div>

			<div class="signals-form-group" style="border-bottom: none; padding-bottom: 0">
				<label for="signals_support_issue" class="signals-strong"><?php _e( 'Issue / Feedback', 'signals' ); ?></label>
				<textarea name="signals_support_issue" id="signals_support_issue" class="signals-block" rows="10" placeholder="<?php _e( 'Please explain the issue you are facing with the plugin. Provide as much detail as possible.', 'signals' ); ?>"></textarea>

				<p class="signals-form-help-block"><?php _e( 'Please explain the issue you are facing with the plugin. Provide as much detail as possible.', 'signals' ); ?></p>
			</div>

			<button class="signals-btn signals-create-ticket"><strong><?php _e( 'Ask for Support', 'signals' ); ?></strong></button>
		</div>
	</div>
</div><!-- #support -->