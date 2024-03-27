<?php

	class DESTAT_Activation {

		public function init() {

			$load = unserialize( get_option( 'destatistics' ) );
			if ( $load instanceof Destatistics ) {
				$load->init();

				return $load;
			} else {
				add_action( 'admin_menu', array( &$this, 'initMenu' ) );
				add_action( 'network_admin_menu', array( &$this, 'initMenu' ) );
			}
		}

		public function initMenu() {

			add_menu_page( __( 'de:statistics', DESTAT_TEXTDOMAIN ), __( 'de:statistics', DESTAT_TEXTDOMAIN ), 'moderate_comments', DESTAT_SLUG, array(
				&$this,
				'licForm1'
			), 'dashicons-chart-line', 3.2 );
		}

		/*
		 * Activation licence product
		 */
		public function activation() {

			return $this->sendRequest( $this->generateParams( 'activation' ) );
		}

		/*
		 * Deactivation licence product
		 */
		public function deactivation() {

			$this->sendRequest( $this->generateParams( 'deactivation' ) );

		}

		public static function isNotEmpty() {

			return true;
		}

		public function generateParams( $request ) {

			$email = $_POST['activation_email'];
			$key   = $_POST['activation_code'];

			$args = array(
				'request'     => $request,
				'email'       => $email,
				'licence_key' => $key,
				'product_id'  => DESTAT_PRODUCT_ID

			);

			return add_query_arg( 'wc-api', 'software-api', DESTAT_ACTIVATION_DOMAIN ) . '&' . http_build_query( $args );

		}


		public function sendRequest( $url ) {


			$data = wp_remote_get( $url );

			return $this->checkResponse( $data );

		}


		public function checkResponse( $response ) {


			if ( array_key_exists( 'response', $response ) ) {

				if ( array_key_exists( 'code', $response['response'] ) && $response['response']['code'] == 200 ) {
					if ( array_key_exists( 'body', $response ) ) {
						//						var_dump( $response );
						$body = json_decode( $response['body'] );
						if ( ! isset( $body->error ) && $body->activated == true ) {
							$functions = new DESTAT_Functions();
							$functions->updateOption( 'activation_email', $_POST['activation_email'] );
							$functions->updateOption( 'activation_key', base64_encode( $_POST['activation_code'] ) );

							$load = DESTAT_STATISTICS;

							$load = $load ? new $load () : '';

							if ( $load instanceof Destatistics ) {
								$serialize = serialize( $load );
								$functions->updateOption( 'destatistics', $serialize );
								$load->activatePlugin();
							}

							return true;
						}
					}
				}
			}

			return false;
		}

		public function licForm1() {

			$result = false;
			if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

				$result = $this->activation();
			}
			//			$functions = new DESTAT_Functions();
			//
			//			print_r( $functions->getBlogs() );

			if ( $result ) {
				?>
				<form method="post">
					<div class="wrap"><h2><?php _e( 'de:statistics', DESTAT_TEXTDOMAIN ); ?></h2>
						<br><br>

						<div id="st-clicks-tags" class="postbox ">
							<h3 class="hndle">
								<span>&nbsp;&nbsp;&nbsp;<?php _e( 'Product activated', DESTAT_TEXTDOMAIN ); ?></span>
							</h3>

							<div class="inside">
								<input type="hidden" name="activation-wp_nonce" id="activation-wp_nonce" value="<?php echo wp_create_nonce( 'de:statistics' ); ?>" />

								<table class="form-table">
									<tr>
										<td colspan="2"><?php //_e( 'Error! Please enter licence key' ); ?></td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Plugin has been activated successfully', DESTAT_TEXTDOMAIN ); ?>
										</th>
									</tr>
								</table>
								<input type="submit" class="button button-primary ac act" value="<?php _e( 'Ok', DESTAT_TEXTDOMAIN ); ?>">
							</div>
						</div>
					</div>
				</form>
			<?php
			} else {


				?>
				<form method="post">
					<div class="wrap"><h2><?php _e( 'de:statistics', DESTAT_TEXTDOMAIN ); ?></h2>
						<br><br>

						<div id="st-clicks-tags" class="postbox ">
							<h3 class="hndle">
								<span>&nbsp;&nbsp;&nbsp;<?php _e( 'Activation', DESTAT_TEXTDOMAIN ); ?></span>
							</h3>

							<div class="inside">
								<input type="hidden" name="activation-wp_nonce" id="activation-wp_nonce" value="<?php echo wp_create_nonce( 'de:statistics' ); ?>" />

								<table class="form-table">
									<tr>
										<td colspan="2"><?php //_e( 'Error! Please enter licence key' ); ?></td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Email', DESTAT_TEXTDOMAIN ); ?>
											<input type="text" name="activation_email">
										</th>
										<th scope="row">
											<?php _e( 'Key', DESTAT_TEXTDOMAIN ); ?>
											<input type="text" name="activation_code">
										</th>
									</tr>
								</table>
								<input type="submit" class="button button-primary ac act" value="<?php _e( 'Go', DESTAT_TEXTDOMAIN ); ?>">
							</div>
						</div>
					</div>
				</form>
			<?php
			}
		}
	}