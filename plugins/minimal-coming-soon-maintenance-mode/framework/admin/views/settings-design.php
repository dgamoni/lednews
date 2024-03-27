<?php

/**
 * Design settings view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="design">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'DESIGN', 'signals' ); ?></div>
		<p><?php _e( 'Design settings for the plugin. You have the option to modify every aspect of the maintenance page design as per your requirements.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<div class="signals-upload-group signals-clearfix">
				<div class="signals-form-group border-fix">
					<div class="signals-upload-element">
						<label class="signals-strong"><?php _e( 'Logo', 'signals' ); ?></label>

						<?php if ( ! empty( $signals_csmm_options['logo'] ) ) : // If the image url is present, show the image. Else, show the default upload text ?>
							<span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['logo'] ); ?>" /></span>
						<?php else : ?>
							<span class="signals-preview-area"><?php _e( 'Select or upload via WP native uploader', 'signals' ); ?></span>
						<?php endif; ?>

						<input type="hidden" name="signals_csmm_logo" id="signals_csmm_logo" value="<?php esc_attr_e( $signals_csmm_options['logo'] ); ?>">
						<button type="button" name="signals_logo_upload" id="signals_logo_upload" class="signals-btn signals-upload" style="margin-top: 4px"><?php _e( 'Select', 'signals' ); ?></button>

						<span class="signals-upload-append">
							<?php if ( ! empty( $signals_csmm_options['logo'] ) ) : ?>
								&nbsp;<a href="javascript: void(0);" class="signals-remove-image"><?php _e( 'Remove', 'signals' ); ?></a>
							<?php endif; ?>
						</span>
					</div>
				</div>

				<div class="signals-form-group border-fix">
					<div class="signals-upload-element">
						<label class="signals-strong"><?php _e( 'Favicon', 'signals' ); ?></label>

						<?php if ( ! empty( $signals_csmm_options['favicon'] ) ) : // If the image url is present, show the image. Else, show the default upload text ?>
							<span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['favicon'] ); ?>" /></span>
						<?php else : ?>
							<span class="signals-preview-area"><?php _e( 'Select or upload via WP native uploader', 'signals' ); ?></span>
						<?php endif; ?>

						<input type="hidden" name="signals_csmm_favicon" id="signals_csmm_favicon" value="<?php esc_attr_e( $signals_csmm_options['favicon'] ); ?>">
						<button type="button" name="signals_favicon_upload" id="signals_favicon_upload" class="signals-btn signals-upload" style="margin-top: 4px"><?php _e( 'Select', 'signals' ); ?></button>

						<span class="signals-upload-append">
							<?php if ( ! empty( $signals_csmm_options['favicon'] ) ) : ?>
								&nbsp;<a href="javascript: void(0);" class="signals-remove-image"><?php _e( 'Remove', 'signals' ); ?></a>
							<?php endif; ?>
						</span>
					</div>
				</div>

				<div class="signals-form-group border-fix">
					<div class="signals-upload-element">
						<label class="signals-strong"><?php _e( 'Background Cover Image', 'signals' ); ?></label>

						<?php if ( ! empty( $signals_csmm_options['bg_cover'] ) ) : // If the image url is present, show the image. Else, show the default upload text ?>
							<span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['bg_cover'] ); ?>" /></span>
						<?php else : ?>
							<span class="signals-preview-area"><?php _e( 'Select or upload via WP native uploader', 'signals' ); ?></span>
						<?php endif; ?>

						<input type="hidden" name="signals_csmm_bg" id="signals_csmm_bg" value="<?php esc_attr_e( $signals_csmm_options['bg_cover'] ); ?>">
						<button type="button" name="signals_bg_upload" id="signals_bg_upload" class="signals-btn signals-upload" style="margin-top: 4px"><?php _e( 'Select', 'signals' ); ?></button>

						<span class="signals-upload-append">
							<?php if ( ! empty( $signals_csmm_options['bg_cover'] ) ) : ?>
								&nbsp;<a href="javascript: void(0);" class="signals-remove-image"><?php _e( 'Remove', 'signals' ); ?></a>
							<?php endif; ?>
						</span>
					</div>
				</div>
			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_overlay" class="signals-strong"><?php _e( 'Content Overlay', 'signals' ); ?></label>
				<input type="checkbox" class="signals-form-ios" name="signals_csmm_overlay" value="1"<?php checked( '1', $signals_csmm_options['content_overlay'] ); ?>>

				<p class="signals-form-help-block"><?php _e( 'If enabled, applies transparent background to the content section of the maintenance page.', 'signals' ); ?></p>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_width" class="signals-strong"><?php _e( 'Content Width (in px)', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_width" id="signals_csmm_width" value="<?php esc_attr_e( $signals_csmm_options['content_width'] ); ?>" placeholder="<?php _e( 'Set content width for the page', 'signals' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php _e( 'Set maximum width of the content (in pixels) for the maintenance page. Provide only numeric value. Example: Entering 400 will set the width of the content to 400px. Defaults to 440px.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_color" class="signals-strong"><?php _e( 'Background Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_color" id="signals_csmm_color" value="<?php esc_attr_e( $signals_csmm_options['bg_color'] ); ?>" placeholder="<?php _e( 'Background color for the page', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select background color for the page. If the background cover image is set, this option will be ignored.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_position" class="signals-strong"><?php _e( 'Content Position', 'signals' ); ?></label>
					<select name="signals_csmm_position" id="signals_csmm_position">
						<option value="left"<?php selected( 'left', $signals_csmm_options['content_position'] ); ?>><?php _e( 'Left', 'signals' ); ?></option>
						<option value="center"<?php selected( 'center', $signals_csmm_options['content_position'] ); ?>><?php _e( 'Center', 'signals' ); ?></option>
						<option value="right"<?php selected( 'right', $signals_csmm_options['content_position'] ); ?>><?php _e( 'Right', 'signals' ); ?></option>
					</select>

					<p class="signals-form-help-block"><?php _e( 'For the position of the content on the maintenance page. Does not work if the width is set to maximum which is 1170px.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_alignment" class="signals-strong"><?php _e( 'Content Alignment', 'signals' ); ?></label>
					<select name="signals_csmm_alignment" id="signals_csmm_alignment">
						<option value="left"<?php selected( 'left', $signals_csmm_options['content_alignment'] ); ?>><?php _e( 'Left', 'signals' ); ?></option>
						<option value="center"<?php selected( 'center', $signals_csmm_options['content_alignment'] ); ?>><?php _e( 'Center', 'signals' ); ?></option>
						<option value="right"<?php selected( 'right', $signals_csmm_options['content_alignment'] ); ?>><?php _e( 'Right', 'signals' ); ?></option>
					</select>

					<p class="signals-form-help-block"><?php _e( 'For the alignment of the text on the maintenance page. Make it left, center, or right.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_header_font" class="signals-strong"><?php _e( 'Header Font', 'signals' ); ?></label>

					<select name="signals_csmm_header_font" id="signals_csmm_header_font" class="signals-google-fonts">
						<option value="Arial"<?php selected( 'Arial', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Arial', 'signals' ); ?></option>
						<option value="Helvetica"<?php selected( 'Helvetica', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Helvetica', 'signals' ); ?></option>
						<option value="Georgia"<?php selected( 'Georgia', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Georgia', 'signals' ); ?></option>
						<option value="Times New Roman"<?php selected( 'Times New Roman', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Times New Roman', 'signals' ); ?></option>
						<option value="Tahoma"<?php selected( 'Tahoma', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Tahoma', 'signals' ); ?></option>
						<option value="Verdana"<?php selected( 'Verdana', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Verdana', 'signals' ); ?></option>
						<option value="Geneva"<?php selected( 'Geneva', $signals_csmm_options['header_font'] ); ?>><?php _e( 'Geneva', 'signals' ); ?></option>
						<option disabled>-- via google --</option>
						<?php

							// Listing fonts from the array
							foreach ( $signals_google_fonts as $signals_font ) {
								echo '<option value="' . $signals_font . '"' . selected( $signals_font, $signals_csmm_options['header_font'] ) . '>' . $signals_font . '</option>' . "\n";
							}

						?>
					</select>

					<h3><?php _e( 'This is how this font is going to look!', 'signals' ); ?></h3>
					<p class="signals-form-help-block"><?php _e( 'Font for the header text. Listing a total of 668 Google web fonts.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_secondary_font" class="signals-strong"><?php _e( 'Secondary Font', 'signals' ); ?></label>

					<select name="signals_csmm_secondary_font" id="signals_csmm_secondary_font" class="signals-google-fonts">
						<option value="Arial"<?php selected( 'Arial', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Arial', 'signals' ); ?></option>
						<option value="Helvetica"<?php selected( 'Helvetica', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Helvetica', 'signals' ); ?></option>
						<option value="Georgia"<?php selected( 'Georgia', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Georgia', 'signals' ); ?></option>
						<option value="Times New Roman"<?php selected( 'Times New Roman', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Times New Roman', 'signals' ); ?></option>
						<option value="Tahoma"<?php selected( 'Tahoma', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Tahoma', 'signals' ); ?></option>
						<option value="Verdana"<?php selected( 'Verdana', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Verdana', 'signals' ); ?></option>
						<option value="Geneva"<?php selected( 'Geneva', $signals_csmm_options['secondary_font'] ); ?>><?php _e( 'Geneva', 'signals' ); ?></option>
						<option disabled>-- via google --</option>
						<?php

							// Listing fonts from the array
							foreach ( $signals_google_fonts as $signals_font ) {
								echo '<option value="' . $signals_font . '"' . selected( $signals_font, $signals_csmm_options['secondary_font'] ) . '>' . $signals_font . '</option>' . "\n";
							}

						?>
					</select>

					<h3><?php _e( 'This is how this font is going to look!', 'signals' ); ?></h3>
					<p class="signals-form-help-block"><?php _e( 'Font for the secondary text. Listing a total of 668 Google web fonts.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_header_size" class="signals-strong"><?php _e( 'Header Text Size', 'signals' ); ?></label>

					<select name="signals_csmm_header_size" id="signals_csmm_header_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								echo '<option value="' . $i . '"' . selected( $signals_csmm_options['header_font_size'], $i ) . '>' . $i . __( 'px', 'signals' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php _e( 'Font size for the header text.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_secondary_size" class="signals-strong"><?php _e( 'Secondary Text Size', 'signals' ); ?></label>

					<select name="signals_csmm_secondary_size" id="signals_csmm_secondary_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								echo '<option value="' . $i . '"' . selected( $signals_csmm_options['secondary_font_size'], $i ) . '>' . $i . __( 'px', 'signals' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php _e( 'Font size for the secondary text.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_header_color" class="signals-strong"><?php _e( 'Header Text Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_header_color" id="signals_csmm_header_color" value="<?php esc_attr_e( $signals_csmm_options['header_font_color'] ); ?>" placeholder="<?php _e( 'Font color for the Header text', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select font color for the header text.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_secondary_color" class="signals-strong"><?php _e( 'Secondary Text Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_secondary_color" id="signals_csmm_secondary_color" value="<?php esc_attr_e( $signals_csmm_options['secondary_font_color'] ); ?>" placeholder="<?php _e( 'Font color for the Secondary text', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select font color for the secondary text.', 'signals' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_antispam_size" class="signals-strong"><?php _e( 'Antispam Text Size', 'signals' ); ?></label>

					<select name="signals_csmm_antispam_size" id="signals_csmm_antispam_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 10; $i < 21; $i++ ) {
								echo '<option value="' . $i . '"' . selected( $signals_csmm_options['antispam_font_size'], $i ) . '>' . $i . __( 'px', 'signals' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php _e( 'Font size for the antispam text.', 'signals' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_antispam_color" class="signals-strong"><?php _e( 'Antispam Text Color', 'signals' ); ?></label>
					<input type="text" name="signals_csmm_antispam_color" id="signals_csmm_antispam_color" value="<?php esc_attr_e( $signals_csmm_options['antispam_font_color'] ); ?>" placeholder="<?php _e( 'Font color for the Antispam text', 'signals' ); ?>" class="signals-form-control color {required:false}">

					<p class="signals-form-help-block"><?php _e( 'Select font color for the antispam text.', 'signals' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div><!-- #design -->