<?php
//error_reporting(E_ALL);

class DeGAoAuth{

	protected $GAClientID       = '';
	protected $GAClientSecret   = '';
	protected $GARedirectUri    = '';
    protected $GAClientScope    = '';

	//*****************************************************************************************************
	function __construct(){

        $this->GAClientID 	    = '627200590940-kjkgsood8ss3sbeqgslactucioue1vft.apps.googleusercontent.com';
        $this->GAClientSecret	= 'gqvifDfGvpcPXPQtwtyzW9TT';
        $this->GARedirectUri	= 'urn:ietf:wg:oauth:2.0:oob';
        $this->GAClientScope    = 'https://www.googleapis.com/auth/analytics.readonly';

	}

    //*********************************************************************************
    // виводимо лінк аутентифікації
    public function ShowAuthLink() {
        if ( $this->GAClientID ) {
            $aLinkArgs = array(
                    'client_id' => $this->GAClientID,
                    'redirect_uri' => $this->GARedirectUri,
                    'response_type' => 'code',
                    'scope' => $this->GAClientScope,
                    'next' => admin_url('/options-general.php?page=depostrating-options')
                );
            echo '<a target="_blank" class="button-primary" href="'. add_query_arg( $aLinkArgs, 'https://accounts.google.com/o/oauth2/auth' ) .'">Authenticate with Google Analytics</a>';
        } else {
            echo '<p>Аутентификация невозможна! Сначала вы должны указать "Client ID".</p>';
        }
    }

    //*********************************************************************************
    // виводимо лінк аутентифікації
    public function ShowTokenLink() {
        if ( get_option("GAAuthCode") ) { ?>
    <form method="POST">
        <input type="hidden" name="GaNewToken" value="1">
        <input type="submit" class="button-primary" value="Get first access token and refresh token">
    </form>
    <?php
        }
    }

    //*********************************************************************************
    // отримуємо аксес токен і рефреш токен
    public function GetAndSaveFirstGoogleAccesToken(){
        /**/ $oUniLog	= GetLog();
        $sUrl = 'https://accounts.google.com/o/oauth2/token';
        $aPostHeaders = array(
                    'method' => 'POST',
                    'host' => 'accounts.google.com',
                    'content-type' => 'application/x-www-form-urlencoded'
                    );
        $aPostData = array(
                'code' => get_option("GAAuthCode"),
                'client_id' => $this->GAClientID,
                'client_secret' => $this->GAClientSecret,
                'redirect_uri' => $this->GARedirectUri,
                'grant_type' => 'authorization_code'
                );

        $aResult = wp_remote_post( $sUrl, array( 'headers' => $aPostHeaders, 'body' => $aPostData, 'sslverify' => false ) );

        if ( ! is_wp_error( $aResult ) ) {
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody );

            if ( !$oResponse->error ) {

                update_option( 'GAaccess_token', $oResponse->access_token );
                set_transient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );

                update_option( 'GArefresh_token', $oResponse->refresh_token );

            } elseif ( $oResponse->error == 'invalid_grant' ) {

                wp_die('Ошибка: не могу получить доступ. Пожалуйста, авторизируйтесь снова.');

            } else {

                wp_die($oResponse->error);

            }

        } else {
            /**/ $oUniLog->logDebug('First token NOT recieved. Error: ', $aResult->errors['http_request_failed'][0]);
            wp_die($aResult->errors['http_request_failed'][0]);
        }

    }

    //*********************************************************************************
    public function IsTokenExpired(){

        $sAccessToken = get_transient( 'GAaccess_token' );

        if ( !empty($sAccessToken) ) {
            return false; //nope, still active
        } else {
            return true; //yep, expired
        }

    }

    //*********************************************************************************
    // отримуємо новий аксес токен по рефреш токен
    public function GetAndSaveNewGoogleAccesToken(){
        /**/ $oUniLog	= GetLog();
        $sUrl = 'https://accounts.google.com/o/oauth2/token';
        $aPostHeaders = array(
                    'method' => 'POST',
                    'host' => 'accounts.google.com',
                    'content-type' => 'application/x-www-form-urlencoded'
                    );
        $aPostData = array(
                'client_id' => $this->GAClientID,
                'client_secret' => $this->GAClientSecret,
                'refresh_token' => get_option( 'GArefresh_token' ),
                'grant_type' => 'refresh_token'
                );

        $aResult = wp_remote_post( $sUrl, array( 'headers' => $aPostHeaders, 'body' => $aPostData, 'sslverify' => false ) );

        if ( ! is_wp_error( $aResult ) ) {
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody );

            update_option( 'GAaccess_token', $oResponse->access_token );
            set_transient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );

            if ( get_transient( 'GAaccess_token' ) ) {
                /**/ $oUniLog->logDebug('New token recieved.');
                return true;
            } else {
                /**/ $oUniLog->logDebug('New token NOT recieved. Response: ', $oResponse);
                return false;
            }
        } else {
            /**/ $oUniLog->logDebug('New token NOT recieved. Error: ', $aResult->errors);
            return false;
        }

    }

    //*********************************************************************************
	public function GetProfiles() {

        if ( get_option("GAAuthCode") ) {

        $bIsToken = true;
        if ( $this->IsTokenExpired() ) {
            $bIsToken = false;
            $bIsToken = $this->GetAndSaveNewGoogleAccesToken();
        }

        if ( $bIsToken ) {
            $sUrl = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles';
            $sUrl = add_query_arg( array( 'access_token' => get_transient( 'GAaccess_token' ) ), $sUrl );

            $aResult = wp_remote_get( $sUrl, array( 'sslverify' => false ) );
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody );
            
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
                    <th scope="row">Выберите профайл:</th>';
            echo '<td>';
            echo '<select name="GAUserProfile">';
                echo '<option value=""'.selected('', get_option('GAUserProfile')).'></option>';
            foreach ( $oProfiles->items as $oItem ) {
                echo '<option value="'.$oItem->id.'"'.selected($oItem->id, get_option('GAUserProfile')).'>'.$oItem->websiteUrl.' ('.$oItem->id.')</option>';
            }

            echo '</td>
                </tr>';

        }
    }

    //*********************************************************************************
    public function GetPageviewsByPagePath( $sPath ){

        /**/ $oUniLog	= GetLog();
        if ( $this->IsTokenExpired() ) {
            /**/ $oUniLog->logDebug('Token expired. Retrieving new one.');
            $bIsToken = false;
            $bIsToken = $this->GetAndSaveNewGoogleAccesToken();
        }

        $sUrl = 'https://www.googleapis.com/analytics/v3/data/ga';

        $aArgs = array(
                'ids' => 'ga:'.get_option('GAUserProfile'),
                'dimensions' => 'ga:pagePath',
                'metrics' => 'ga:pageviews',
                'filters' => 'ga:pagePath=@'.$sPath,
                'start-date' => date('Y-m-d',strtotime('2 years ago')),
                'end-date' => date('Y-m-d'),
                'max-results' => '500',
                'access_token' => get_transient("GAaccess_token")
                );

        $sUrl = add_query_arg( $aArgs, $sUrl );

        $aResult = wp_remote_get( $sUrl, array( 'sslverify' => false ) );

        if ( ! is_wp_error( $aResult ) ) {
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody, true );

            $iTotalPageviews = $oResponse['totalsForAllResults']['ga:pageviews'];
            
            return $iTotalPageviews;
        } else {
            return false;
        }

    }

}
?>
