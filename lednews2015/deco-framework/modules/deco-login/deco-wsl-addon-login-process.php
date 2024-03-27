<?php
add_action( 'wp_enqueue_scripts', 'deco_wsl_addon_login_process_register_js', 99 );
function deco_wsl_addon_login_process_register_js() {
	wp_enqueue_style( 'deco-wsl-addon-login-process', DECO_WSL_ADDON_LOGIN_PROCESS_URL . 'assets/css/deco-wsl-addon-login-process.css', array(), DECO_WSL_ADDON_LOGIN_PROCESS_VER );

	wp_enqueue_script( "deco-wsl-addon-login-process", DECO_WSL_ADDON_LOGIN_PROCESS_URL . "assets/js/deco-wsl-addon-login-process.js", array(), DECO_WSL_ADDON_LOGIN_PROCESS_VER, true );
}

add_action( 'init', 'deco_wsl_addon_login_process_popup_soc_auth_message' );
function deco_wsl_addon_login_process_popup_soc_auth_message() {
	if ( isset( $_GET['popup_auth'] ) && preg_match( '/wordpress_social_authenticate/', $_SERVER['HTTP_REFERER'], $match ) ) {
		include DECO_WSL_ADDON_LOGIN_PROCESS_PATH . 'page-wsl-auth-for-popup.php';
		die();
	}
}


add_action( 'wp_footer', 'deco_wsl_addon_login_process_popup', 99 );
function deco_wsl_addon_login_process_popup() {
	include_once DECO_WSL_ADDON_LOGIN_PROCESS_PATH . 'popups.php';
}

function deco_wsl_custom_soc_login() {
	if ( defined( 'WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL' ) ) {
		// HOOKABLE:
		do_action( 'wsl_render_login_form_start' );

		GLOBAL $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

		if ( empty( $social_icon_set ) ) {
			$social_icon_set = "wpzoom/";
		} else {
			$social_icon_set .= "/";
		}

		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . '/assets/img/32x32/' . $social_icon_set;

		// HOOKABLE: allow use of other icon sets
		$assets_base_url = apply_filters( 'wsl_later_hook_assets_base_url', $assets_base_url );

		$wsl_settings_connect_with_label = get_option( 'wsl_settings_connect_with_label' );

		$current_page_url = 'http';
		if ( isset( $_SERVER["HTTPS"] ) && ( $_SERVER["HTTPS"] == "on" ) ) {
			$current_page_url .= "s";
		}
		$current_page_url .= "://";
		if ( $_SERVER["SERVER_PORT"] != "80" ) {
			$current_page_url .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$current_page_url .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		}

		$authenticate_base_url = site_url( 'wp-login.php', 'login_post' ) . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) . "action=wordpress_social_authenticate&";

		// overwrite endpoint_url if need'd
		if ( get_option( 'wsl_settings_hide_wp_login' ) == 1 ) {
			$authenticate_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . "/services/authenticate.php?";
		}
		?>

		<div class="soc">
			<?php
			$nok = true;

			// display provider icons
			foreach ( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG AS $item ) {
				$provider_id   = @ $item["provider_id"];
				$provider_name = @ $item["provider_name"];

				$authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode( home_url() . '/?popup_auth=1' );

				if ( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) {
					// HOOKABLE: allow use of other icon sets
					?>
					<a rel="nofollow" href="<?php echo esc_url( $authenticate_url ) ?>"
					   data-provider-name="<?php echo $provider_name; ?>"
					   title="Connect with <?php echo $provider_name ?>" class="wsl_connect_with_provider">
					</a>
					<?php
					$nok = false;
				}
			}

			if ( $nok ) {
				?>
				<p style="background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;">
					<?php _wsl_e( '<strong style="color:red;">WordPress Social Login is not configured yet!</strong><br />Please visit the <strong>Settings\ WP Social Login</strong> administration page to configure this plugin.<br />For more information please refer to the plugin <a href="http://hybridauth.sourceforge.net/userguide/Plugin_WordPress_Social_Login.html">online user guide</a> or contact us at <a href="http://hybridauth.sourceforge.net/">hybridauth.sourceforge.net</a>', 'wordpress-social-login' ) ?>
				</p>
				<style>
					#wp-social-login-connect-with {
						display: none;
					}
				</style>
				<?php
			}
			?>
		</div>
		<!-- /wsl_render_login_form -->
		<?php

		// HOOKABLE:
		do_action( 'wsl_render_login_form_end' );
	}
}

add_action( 'wp_ajax_deco_password_pre_reset_check_email', 'deco_password_pre_reset_check_email' );
add_action( 'wp_ajax_nopriv_deco_password_pre_reset_check_email', 'deco_password_pre_reset_check_email' );

function deco_password_pre_reset_check_email() {
	$user_login = $_POST['user_login'];
	if ( is_email( $user_login ) ) {
		$user = get_user_by( 'email', $user_login );
		if ( $user ) {
			$res['status'] = 'success';
		} else {
			$res['mes']    = 'Пользователя с таким email нет в системе!';
			$res['status'] = 'error';
		}
	} else {
		$res['mes']    = 'Не верный email!';
		$res['status'] = 'error';
	}
	echo json_encode( $res );
	die();
}


add_action( 'wp_ajax_deco_get_auth_is_success', 'deco_get_auth_is_success' );
add_action( 'wp_ajax_nopriv_deco_get_auth_is_success', 'deco_get_auth_is_success' );
function deco_get_auth_is_success() {
	if ( is_user_logged_in() ) {
		$status = 1;
	} else {
		$status = 0;
	}
	echo json_encode( array( 'status' => $status ) );
	die();
}

add_action( 'wp_ajax_deco_get_is_email_registered', 'deco_get_is_email_registered' );
add_action( 'wp_ajax_nopriv_deco_get_is_email_registered', 'deco_get_is_email_registered' );
function deco_get_is_email_registered() {

	$user_login = $_POST['log'];
	$pass       = $_POST['pwd'];

	$res['post'] = $_POST;

	if ( email_exists( $user_login ) || username_exists( $user_login ) ) {
		$res['status'] = 'user_exist';

		if ( is_email( $user_login ) ) {
			$user = get_user_by( 'email', $user_login );
		} else {
			$user = get_user_by( 'login', $user_login );
		}
		if ( ! wp_check_password( $pass, $user->user_pass, $user->ID ) ) {
			$res['status'] = 'error_pass';
			$res['mes']    = 'Не верный email или пароль';
			$res['user']   = $user;
			$res['post']   = $_POST;
		}
	} else {
		if ( ! is_email( $user_login ) ) {
			$res['status'] = 'error_email';
			$res['mes']    = 'Не верный email';
		} else {
			$res['status'] = 'user_not_exist';
		}
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_deco_registered_and_logging_user_in_process', 'deco_registered_and_logging_user_in_process' );
add_action( 'wp_ajax_nopriv_deco_registered_and_logging_user_in_process', 'deco_registered_and_logging_user_in_process' );
function deco_registered_and_logging_user_in_process() {
	$res['status'] = 'error';
	if ( isset( $_POST['user_email'] ) ) {
		if ( ! is_user_logged_in() ) {


			$user_email = $_POST['user_email'];
			$user_pass  = $_POST['pwd'];

			list( $username, $email_domain ) = explode( '@', $user_email );


			// Check the e-mail address
			if ( $user_email == '' ) {
				$res['status'] = 'error';
				$res['mes']    = __( '<strong>ERROR</strong>: Please type your e-mail address.' );
				die( json_encode( $res ) );

			} elseif ( ! is_email( $user_email ) ) {
				$res['status'] = 'error';
				$res['mes']    = __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' );
				die( json_encode( $res ) );
			} elseif ( email_exists( $user_email ) ) {
				$res['status'] = 'error';
				$res['mes']    = __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' );
				die( json_encode( $res ) );

			}

			if ( empty( $user_pass ) ) {
				$user_pass = wp_generate_password( 12, false );
			}

			$user_id = wp_create_user( $user_email, $user_pass, $user_email );
			if ( ! $user_id || is_wp_error( $user_id ) ) {
				$res['status'] = 'error';
				$res['mes']    = __( '<strong>ERROR</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:' . get_option( 'admin_email' ) . '">webmaster</a> !' );
				die( json_encode( $res ) );
			}

			update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
			update_user_meta( $user_id, 'first_name', $username );

			wp_new_user_notification( $user_id, $user_pass );

			if ( $user_id ) {
				$res['status'] = 'success';
				$res['mes']    = 'Регистрация завершена. Проверьте вашу почту.';
			} else {
				$res['status'] = 'error';
				$res['mes']    = 'Неизвестная ошибка. Повторите регистрацию позже';
			}

		}
	}
	die( json_encode( $res ) );
}

//*********************************************************************************
add_filter( "wsl_hook_process_login_alter_insert_user", "deco_get_user_id_by_api_data_filter" );
function deco_get_user_id_by_api_data_filter( $userdata, $provider = '', $hybridauth_user_profile = '' ) {


	$user_email = get_user_by( 'email', $hybridauth_user_profile->email );
	$user_login = get_user_by( 'login', $userdata['user_login'] );

	//		print_r( $user_email );
	if ( $user_email ) {
		return $user_email->ID;
	} else if ( $user_login ) {
		return $user_login->ID;
	} elseif ( is_email( $userdata->user_email ) ) {
		return wp_insert_user( $userdata );
	}

}
