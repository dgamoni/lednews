<?php

/**
 * Renders the html template for the plugin.
 *
 * @link       http://www.69signals.com
 * @since      1.0
 * @package    Signals_Maintenance_Mode
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo stripslashes( $options['title'] ); ?></title>
<?php if ( isset( $options['favicon'] ) && ! empty( $options['favicon'] ) ) : ?>
<link rel="shortcut icon" href="<?php echo esc_url_raw( $options['favicon'] ); ?>" />
<?php endif; ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo SIGNALS_CSMM_URL; ?>/framework/public/css/public.css" />
<script src='//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js'></script>
<script>
	WebFont.load( {
		google: {
			families: ['<?php echo $options["header_font"]; ?>', '<?php echo $options["secondary_font"]; ?>']
		}
	} );
</script>
<?php require_once SIGNALS_CSMM_PATH . '/framework/public/include/styles.php'; ?>
</head>
<body class="signals-plugin">
	<div class="maintenance-mode">
		<div class="s-container">
			<div class="content">

				<?php

					// Logo
					if ( ! empty( $options['logo'] ) ) {
						echo '<div class="logo-container">' . "\r\n";
						echo '<img src="' . $options['logo'] . '" class="logo" />' . "\r\n";
						echo '</div>' . "\r\n";
					}


					// Header text
					if ( ! empty( $options['header_text'] ) ) {
						echo '<h1 class="header-text">' . stripslashes( $options['header_text'] ) . '</h1>' . "\r\n";
					}


					// Secondary text
					if ( ! empty( $options['secondary_text'] ) ) {
						echo '<p class="secondary-text">' . stripslashes( $options['secondary_text'] ) . '</p>' . "\r\n";
					}


					// Checking for the mailchimp api. If the api and the list id are present, show the form
					if ( ! empty( $options['mailchimp_api'] ) && ! empty( $options['mailchimp_list'] ) ) {
						// Checking if the form is submitted or not
						if ( isset( $_POST['signals_email'] ) ) {
							// Processing begins
							$signals_email = strip_tags( $_POST['signals_email'] );

							if ( '' === $signals_email ) {
								$signals_msg['code'] 		= 'danger';
								$signals_msg['response'] 	= __( 'Please provide your email address.', 'signals' );
							} else {
								$signals_email = filter_var( strtolower( trim( $signals_email ) ), FILTER_SANITIZE_EMAIL );

								if ( strpos( $signals_email, '@' ) ) {
									require_once SIGNALS_CSMM_PATH . '/framework/admin/include/classes/class-mailchimp.php';

									$signals_connect 	= new MailChimp( $options['mailchimp_api'] );
									$signals_response 	= $signals_connect->call( 'lists/subscribe', array(
										'id'            => $options['mailchimp_list'],
										'email'         => array( 'email' => $signals_email ),
										'double_optin'  => false,
										'send_welcome'  => true
									) );


									// Showing message as per the response from the mailchimp server
									if ( isset( $signals_response['code'] ) && 214 !== $signals_response['code'] ) {
										$signals_msg['code'] 		= 'danger';
										$signals_msg['response'] 	= __( 'Oops! Something went wrong.', 'signals' );
									} elseif ( isset( $signals_response['code'] ) && 214 === $signals_response['code'] ) {
										$signals_msg['code'] 		= 'success';
										$signals_msg['response'] 	= __( 'You are already subscribed!', 'signals' );
									} else {
										$signals_msg['code'] 		= 'success';
										$signals_msg['response'] 	= __( 'Thank you! We\'ll be in touch!', 'signals' );
									}
								} else {
									$signals_msg['code'] 			= 'danger';
									$signals_msg['response'] 		= __( 'Please provide a valid email address.', 'signals' );
								}
							}
						} // signals_email

				?>

						<div class="subscription">
							<?php

								// If the error is set
								if ( isset( $signals_msg ) ) {
									echo '<div class="signals-alert signals-alert-' . $signals_msg['code'] . '">' . $signals_msg['response'] . '</div>';
								}

							?>

							<form role="form" method="post">
								<input type="text" name="signals_email" placeholder="<?php esc_attr_e( stripslashes( $options['input_text'] ) ); ?>">
								<input type="submit" name="submit" value="<?php esc_attr_e( stripslashes( $options['button_text'] ) ); ?>">
							</form>

							<?php

								// If yes, show antispam text
								if ( ! empty( $options['antispam_text'] ) ) {
									// The best part, we don't do spam!
									echo '<p class="anti-spam">' . stripslashes( $options['antispam_text'] ) . '</p>';
								}

							?>
						</div>

				<?php

					} // mailchimp_api || mailchimp_list

				?>
			</div><!-- .content -->
		</div><!-- .s-container -->
	</div><!-- .maintenance-mode -->


	<!-- Maintenance Mode Plugin by 69signals (http://www.69signals.com) -->
	<!-- We are a creative digital agency. We love to weave the web, simple but amazing. We create flawless web and mobile applications. -->
</body>
</html>