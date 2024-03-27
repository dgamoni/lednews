<?php

/**
 * Email settings view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="email">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php _e( 'EMAIL', 'signals' ); ?></div>
		<p><?php _e( 'Email settings for the plugin. You can configure your MailChimp account API with this plugin to store collected emails in an list.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<div class="signals-form-group">
				<label for="signals_csmm_api" class="signals-strong"><?php _e( 'MailChimp API', 'signals' ); ?></label>
				<input type="text" name="signals_csmm_api" id="signals_csmm_api" value="<?php esc_attr_e( $signals_csmm_options['mailchimp_api'] ); ?>" placeholder="<?php esc_attr_e( 'MailChimp API', 'signals' ); ?>" class="signals-form-control">

				<p class="signals-form-help-block"><?php _e( 'Provide your MailChimp API over here.', 'signals' ); ?> <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank"><?php _e( 'Click here', 'signals' ); ?></a> <?php _e( 'to know how to get this information. In case you don\'t want to enable subscription option, just leave this field blank.', 'signals' ); ?></p>
			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_list" class="signals-strong"><?php _e( 'MailChimp List', 'signals' ); ?></label>

				<?php

					// Checking if the API key is present in the database
					if ( ! empty( $signals_csmm_options['mailchimp_api'] ) ) {
						// Grabbing lists using the MailChimp API
						$signals_api 	= new MailChimp( $signals_csmm_options['mailchimp_api'] );
						$signals_lists 	= $signals_api->call( 'lists/list',
							array (
		                		'apikey' => $signals_csmm_options['mailchimp_api']
		                	)
						);

						if ( ! $signals_lists ) {
							echo '<p class="signals-form-help-block">' . __( 'There was an error communicating with the MailChimp server. Please make sure that the API Key used is correct and try again.', 'signals' ) . '</p>';
						} else if ( $signals_lists['total'] == 0 ) {
							echo '<p class="signals-form-help-block">' . __( 'It seems that there is no list created for this account. Why not create one on the MailChimp website and then try here.', 'signals' ) . '</p>';
						} else {
							echo '<select name="signals_csmm_list" id="signals_csmm_list">';

							foreach ( $signals_lists['data'] as $signals_single_list ) {
								echo '<option value="' . $signals_single_list['id'] . '"' . selected( $signals_single_list['id'], $signals_csmm_options['mailchimp_list'] ) . '>' . $signals_single_list['name'].'</option>';
							}

							echo '</select>';
							echo '<p class="signals-form-help-block">' . __( 'Select your MailChimp list in which you would like to store the subscribers data.', 'signals' ) . '</p>';
						}
					} else {
						echo '<p class="signals-form-help-block">' . __( 'Provide your MailChimp API key in the above box and click on `Save Changes` option. Your lists will appear over here.', 'signals' ) . '</p>';
					}

				?>
			</div>
		</div>
	</div>
</div><!-- #email -->