<?php

/**
 * About view for the plugin
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?>

<div class="signals-tile" id="about">
	<div class="signals-tile-body">
		<p class="signals-strong"><?php _e( 'We are a creative digital agency.', 'signals' ); ?></p>
		<p><?php _e( 'We love to weave the web, simple but amazing. We create flawless web and mobile applications. Our perfectly crafted products will make you believe us.', 'signals' ); ?></p>

		<div class="signals-section-content">
			<?php

				// Checking for the offers transient
				$offers_transient = get_transient( 'signals_csmm_offer' );

				// If the value is saved in the database, then don't ping the server
				if ( $offers_transient ) {
					echo '<div class="signals-tile-title">' . __( 'OFFERS', 'signals' ) . '</div>';
					echo $offers_transient . '<br/>';
				} else {
					// Getting our latest products and offer from the website
					$signals_offers = wp_remote_get( 'http://www.69signals.com/offers.php?product=maintenance' );

					// Checking for the errors
					// If everything is OK, then display the information
					if ( ! is_wp_error( $signals_offers ) ) {
						echo '<div class="signals-tile-title">' . __( 'OFFERS', 'signals' ) . '</div>';
						echo $signals_offers['body'] . '<br/>';

						// Setting the transient over here so that it does not ping the server again for a day atleast
						set_transient( 'signals_csmm_offer', $signals_offers['body'], 60*60*24 );
					} // $signals_offers
				} // $offers_transient

			?>

			<div class="signals-share">
				<p><?php _e( 'Show us some love. Connect with us via Social channels.', 'signals' ); ?></p>
				<a href="https://www.twitter.com/69signals" target="_blank">
					<img src="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/img/twitter.png" />
				</a>

				<a href="https://www.facebook.com/69Signals" target="_blank">
					<img src="<?php echo SIGNALS_CSMM_URL; ?>/framework/admin/img/facebook.png" />
				</a>
			</div>
		</div>
	</div>
</div><!-- #about -->