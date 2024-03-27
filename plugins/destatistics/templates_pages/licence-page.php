<div class="wrap"><h2><?php _e( 'About', DESTAT_TEXTDOMAIN ); ?></h2>

	<br><br>

	<div id="st-clicks-tags" class="postbox ">
		<h3 class="hndle"><span>&nbsp;&nbsp;&nbsp;<?php _e( 'Information Your Licence', DESTAT_TEXTDOMAIN ); ?></span>
		</h3>

		<div class="inside">
			<input type="hidden" name="filter-wp_nonce" id="filter-wp_nonce" value="<?php echo wp_create_nonce( 'depostrating' ); ?>" />

			<table class="form-table">
				<tr>
					<th>
						<label><?php _e( 'Email', DESTAT_TEXTDOMAIN ); ?>:</label><br>

					</th>
					<th scope="row">
						<label><?php _e( 'Licence', DESTAT_TEXTDOMAIN ); ?>:</label><br>

					</th>

				</tr>
			</table>
			<input type="submit" class="button button-primary" value="<?php _e( 'Deactivate Licence', DESTAT_TEXTDOMAIN ); ?>">


		</div>
	</div>
