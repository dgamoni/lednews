<?php

/**
 * Basic settings view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="basic">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'BASIC', 'signals' ); ?></div>
		<p><?php _e( 'Configure the core settings. Make sure you configure these options carefully as they are important for the working of the plugin.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_status" class="signals-strong"><?php _e( 'Enable Maintenance Mode?', 'signals' ); ?></label>
					<input type="checkbox" class="signals-form-ios" name="signals_csmm_status" value="1"<?php checked( '1', $signals_csmm_options['status'] ); ?>>

					<p class="signals-form-help-block"><?php _e( 'Set the plugin status. Do you want to enable <strong>Maintenance Mode</strong> for your website?', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_title" class="signals-strong"><?php _e( 'Page Title', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_title" id="signals_csmm_title" value="<?php echo esc_attr_e( stripslashes( $signals_csmm_options['title'] ) ); ?>" placeholder="<?php esc_attr_e( 'Please provide a Page Title', 'signals' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php _e( 'Provide title for the maintenance page.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_header" class="signals-strong"><?php _e( 'Header Text', 'signals' ); ?></label>
					<textarea name="signals_csmm_header" id="signals_csmm_header" rows="3" placeholder="<?php esc_attr_e( 'Header text for the maintenance page', 'signals' ); ?>"><?php echo esc_textarea( stripslashes( $signals_csmm_options['header_text'] ) ); ?></textarea>

					<p class="signals-form-help-block"><?php _e( 'Provide header text for the maintenance page. It is not recommended to leave this blank.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_secondary" class="signals-strong"><?php _e( 'Secondary Text', 'signals' ); ?></label>
					<textarea name="signals_csmm_secondary" id="signals_csmm_secondary" rows="3" placeholder="<?php esc_attr_e( 'Secondary text for the maintenance page', 'signals' ); ?>"><?php echo esc_textarea( stripslashes( $signals_csmm_options['secondary_text'] ) ); ?></textarea>

					<p class="signals-form-help-block"><?php _e( 'Provide secondary text for the maintenance page. It is not recommended to leave this blank.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_antispam" class="signals-strong"><?php _e( 'Anti Spam Text', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_antispam" id="signals_csmm_antispam" value="<?php echo esc_attr_e( stripslashes( $signals_csmm_options['antispam_text'] ) ); ?>" placeholder="<?php esc_attr_e( 'Please provide a Anti-spam Text', 'signals' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php _e( 'Provide anti-spam text for the maintenance page.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_custom_login" class="signals-strong"><?php _e( 'Custom login URL', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_custom_login" id="signals_csmm_custom_login" value="<?php echo esc_attr_e( $signals_csmm_options['custom_login_url'] ); ?>" placeholder="<?php esc_attr_e( 'Custom login URL', 'signals' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php _e( 'In case you are using any plugin for custom login, provide your custom login URL over here. This plugin should be able to detect your custom login automatically in most of the situations. In case it fails to do so, you can provide the custom login URL over here.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_showlogged" class="signals-strong"><?php _e( 'Show normal website to logged in users?', 'signals' ); ?></label>
					<input type="checkbox" class="signals-form-ios" name="signals_csmm_showlogged" value="1"<?php checked( '1', $signals_csmm_options['show_logged_in'] ); ?>>

					<p class="signals-form-help-block"><?php _e( 'Enable this option if you want logged in users to view the website normally while visitors see the maintenance page.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_excludese" class="signals-strong"><?php _e( 'Exclude Search Engines?', 'signals' ); ?></label>
					<input type="checkbox" class="signals-form-ios" name="signals_csmm_excludese" value="1"<?php checked( '1', $signals_csmm_options['exclude_se'] ); ?>>

					<p class="signals-form-help-block"><?php _e( 'Do you want to exclude search engines from viewing maintenance page? This will enable search engines to view your regular website and not the Maintenance Mode page even if the plugin is enabled.', 'signals' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div><!-- #basic -->