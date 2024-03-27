<?php

//error_reporting(E_ALL);

class DESTAT_Google {

	protected $GAClientID = '';
	protected $GAClientSecret = '';
	protected $GARedirectUri = '';
	protected $GAClientScope = '';

	//*****************************************************************************************************
	function __construct() {

		$this->GAClientID     = '627200590940-kjkgsood8ss3sbeqgslactucioue1vft.apps.googleusercontent.com';
		$this->GAClientSecret = 'gqvifDfGvpcPXPQtwtyzW9TT';
		$this->GARedirectUri  = 'urn:ietf:wg:oauth:2.0:oob';
		$this->GAClientScope  = 'https://www.googleapis.com/auth/analytics.readonly';

	}

	// Линк аунтифкации
	public function ShowAuthLink() {

		if ( $this->GAClientID ) {

			$aLinkArgs = array(
				'scope'                  => $this->GAClientScope,
				'client_id'              => $this->GAClientID,
				'redirect_uri'           => $this->GARedirectUri,
				'response_type'          => 'code',
				'next'                   => admin_url( '?page=destatistics_settings' ),
				'access_type'            => 'offline',
				'state'                  => 'profile',
				'approval_prompt'        => 'force',
				'include_granted_scopes' => 'true'
			);

			$auth_link = add_query_arg( $aLinkArgs, 'https://accounts.google.com/o/oauth2/auth' );

			$link = "window.open('$auth_link','_blank','width=935,height=730,resizable=1')";

			echo '<a onclick="' . $link . '" class="button-primary" href="#">' . __( 'Authenticate with Google Analytics', DESTAT_TEXTDOMAIN ) . '</a>';
			//				echo '<a href="' . $auth_link . '" class="button-primary" href="#">' . __( 'Authenticate with Google Analytics', DESTAT_TEXTDOMAIN ) . '</a>';

		} else {

			echo '<p>' . __( 'Authentication is not possible! First you have to specify the "Client ID"', DESTAT_TEXTDOMAIN ) . '</p>';
		}
	}

	//*********************************************************************************
	// виводимо лінк аутентифікації
	public function ShowTokenLink() {

		if ( get_option( "GAAuthCode" ) ) {
			?>
			<form method="POST">
				<input type="hidden" name="GaNewToken" value="1">
				<input type="submit" class="button-primary" value="Get first access token and refresh token">
			</form>
		<?php
		}
	}

	//*********************************************************************************
	// отримуємо аксес токен і рефреш токен
	public function GetAndSaveFirstGoogleAccesToken() {

		/**/
		//			$log     = new Destatistics();
		//			$oUniLog = $log->getLog();

		$sUrl         = 'https://accounts.google.com/o/oauth2/token';
		$aPostHeaders = array(
			'method'       => 'POST',
			'host'         => 'accounts.google.com',
			'content-type' => 'application/x-www-form-urlencoded'
		);
		$aPostData    = array(
			'code'          => get_option( "GAAuthCode" ),
			'client_id'     => $this->GAClientID,
			'client_secret' => $this->GAClientSecret,
			'redirect_uri'  => $this->GARedirectUri,
			'grant_type'    => 'authorization_code'
		);

		$aResult = wp_remote_post( $sUrl, array(
			'headers'   => $aPostHeaders,
			'body'      => $aPostData,
			'sslverify' => false
		) );

		if ( ! is_wp_error( $aResult ) ) {
			$ResponseBody = wp_remote_retrieve_body( $aResult );
			$oResponse    = json_decode( $ResponseBody );

			if ( ! $oResponse->error ) {

				if ( is_network_admin() ) {
					$functions = new DESTAT_Functions();

					$functions->updateOption( 'GAaccess_token', $oResponse->access_token );
					$functions->setTransient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );
					$functions->updateOption( 'GArefresh_token', $oResponse->refresh_token );
				} else {
					update_option( 'GAaccess_token', $oResponse->access_token );
					set_transient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );
					update_option( 'GArefresh_token', $oResponse->refresh_token );
				}
			} elseif ( $oResponse->error == 'invalid_grant' ) {
				print_r( $oResponse );
				echo '<a class="button button-primary" href="' . get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=destatistics_settings">' . __( 'Back', DESTAT_TEXTDOMAIN ) . '</a>';
				wp_die( __( 'Error: can not access. Please login again.', DESTAT_TEXTDOMAIN ) );

			} else {

				wp_die( $oResponse->error );

			}

		} else {
			/**/
			//				$oUniLog->logDebug( 'First token NOT recieved. Error: ', $aResult->errors['http_request_failed'][0] );
			wp_die( $aResult->errors['http_request_failed'][0] );
		}

	}

	//*********************************************************************************
	public function IsTokenExpired() {

		$sAccessToken = get_transient( 'GAaccess_token' );

		if ( ! empty( $sAccessToken ) ) {
			return false; //nope, still active
		} else {
			return true; //yep, expired
		}

	}

	//*********************************************************************************
	// отримуємо новий аксес токен по рефреш токен
	public function GetAndSaveNewGoogleAccesToken() {

		/**/
		//			$log     = new Destatistics();
		//			$oUniLog = $log->getLog();

		$sUrl         = 'https://accounts.google.com/o/oauth2/token';
		$aPostHeaders = array(
			'method'       => 'POST',
			'host'         => 'accounts.google.com',
			'content-type' => 'application/x-www-form-urlencoded'
		);
		$aPostData    = array(
			'client_id'     => $this->GAClientID,
			'client_secret' => $this->GAClientSecret,
			'refresh_token' => get_option( 'GArefresh_token' ),
			'grant_type'    => 'refresh_token'
		);

		$aResult = wp_remote_post( $sUrl, array(
			'headers'   => $aPostHeaders,
			'body'      => $aPostData,
			'sslverify' => false
		) );

		if ( ! is_wp_error( $aResult ) ) {
			$ResponseBody = wp_remote_retrieve_body( $aResult );
			$oResponse    = json_decode( $ResponseBody );

			update_option( 'GAaccess_token', $oResponse->access_token );
			set_transient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );

			if ( get_transient( 'GAaccess_token' ) ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	//*********************************************************************************
	public function GetProfiles() {

		if ( get_option( "GAAuthCode" ) ) {

			$bIsToken = true;
			if ( $this->IsTokenExpired() ) {
				$bIsToken = false;
				$bIsToken = $this->GetAndSaveNewGoogleAccesToken();
			}

			if ( $bIsToken ) {
				$sUrl = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles';
				$sUrl = add_query_arg( array( 'access_token' => get_transient( 'GAaccess_token' ) ), $sUrl );

				$aResult      = wp_remote_get( $sUrl, array( 'sslverify' => false ) );
				$ResponseBody = wp_remote_retrieve_body( $aResult );
				$oResponse    = json_decode( $ResponseBody );

				return $oResponse;

			} else {
				return false;
			}

		}

	}

	//*********************************************************************************
	public function ShowProfiles() {

		$oProfiles = $this->GetProfiles();

		if ( $oProfiles->error->code ) {
			echo $oProfiles->error->message;
		} else {
			$aAccounts = array();

			echo '<tr>
                    <th scope="row">' . __( 'Select a profile', DESTAT_TEXTDOMAIN ) . ':</th>';
			echo '<td>';
			echo '<select name="GAUserProfile">';
			echo '<option value=""' . selected( '', get_option( 'GAUserProfile' ) ) . '></option>';
			foreach ( $oProfiles->items as $oItem ) {
				echo '<option value="' . $oItem->id . '"' . selected( $oItem->id, get_option( 'GAUserProfile' ) ) . '>' . $oItem->websiteUrl . ' (' . $oItem->id . ')</option>';
			}
			echo '</td>
                </tr>';

		}
	}

	//*********************************************************************************
	public function getPageviewsByUrl( $sPath, $debug = false ) {

		/**/
		//			$log     = new Destatistics();
		//			$oUniLog = $log->getLog();

		if ( $this->IsTokenExpired() ) {
			/**/
			//				$oUniLog->logDebug( 'Token expired. Retrieving new one.' );
			$bIsToken = false;
			$bIsToken = $this->GetAndSaveNewGoogleAccesToken();
		}
		$sPath   = str_replace( 'http://', '', $sPath );
		$sPath   = str_replace( 'https://', '', $sPath );
		$url_arr = explode( '/', $sPath );
		array_shift( $url_arr );
		$sPath = implode( '/', $url_arr );


		$sUrl = 'https://www.googleapis.com/analytics/v3/data/ga';

		$aArgs = array(
			'ids'          => 'ga:' . get_option( 'GAUserProfile' ),
			'dimensions'   => 'ga:pagePath',
			'metrics'      => 'ga:pageviews',
			'filters'      => 'ga:pagePath=@' . $sPath,
			'start-date'   => date( 'Y-m-d', strtotime( '2 years ago' ) ),
			'end-date'     => date( 'Y-m-d' ),
			'max-results'  => '500',
			'access_token' => get_transient( "GAaccess_token" )
		);

		$sUrl    = add_query_arg( $aArgs, $sUrl );
		$aResult = wp_remote_get( $sUrl, array( 'sslverify' => false ) );

		if ( $debug ) {
			return $aResult;
		} else {

			if ( ! is_wp_error( $aResult ) ) {
				$ResponseBody = wp_remote_retrieve_body( $aResult );
				$oResponse    = json_decode( $ResponseBody, true );


				$iTotalPageviews = $oResponse['totalsForAllResults']['ga:pageviews'];

				return $iTotalPageviews;
			} else {
				return 0;
			}
		}
	}

	public function getSharesCountByUrl( $url ) {

		$ch = curl_init();
		curl_setopt_array( $ch, array(
			CURLOPT_HTTPHEADER     => array( 'Content-type: application/json' ),
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_URL            => 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ'
		) );
		$res = curl_exec( $ch );
		curl_close( $ch );
		$json = json_decode( $res, true );
		if ( intval( @$json[0]['result']['metadata']['globalCounts']['count'] ) ) {
			return @$json[0]['result']['metadata']['globalCounts']['count'];
		}

		return 0;
	}
}

?>
