<?php
//error_reporting(E_ALL);

class DeGAoAuth{

	protected $GAClientID       = '';
	protected $GAClientSecret   = '';
	protected $GARedirectUri    = '';

	//*****************************************************************************************************
	function __construct(){

        $this->GAClientID 	    = get_option('GAClientID');
        $this->GAClientSecret	= get_option('GAClientSecret');
        $this->GARedirectUri	= get_option('GARedirectUri');

	}

    //*********************************************************************************
    // виводимо лінк аутентифікації
    public function ShowAuthLink() {
        if ( $this->GAClientID ) {
            $aLinkArgs = array(
                    'client_id' => $this->GAClientID,
                    'redirect_uri' => $this->GARedirectUri,
                    'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                    'response_type' => 'code',
                    'access_type' => 'offline',
                    'approval_prompt' => 'force'
                );
            echo '<a class="button-primary" href="'. add_query_arg( $aLinkArgs, 'https://accounts.google.com/o/oauth2/auth' ) .'">Authenticate with Google Analytics</a>';
        } else {
            echo '<p>Аутентификация невозможна! Сначала вы должны указать "Client ID".</p>';
        }
    }

    //*********************************************************************************
    // редірект з Гугла з кодом або помилкою відмови
    public function GoogleCallback(){

	    if( ($_GET['DePrOAuth2Callback'] == 1) && $_GET['code'] ){
            $sAuthorizationCode = $_GET['code'];
            update_option( 'GAAuthorizationCode', $sAuthorizationCode );

            if ( $sAuthorizationCode ) {
                $this->GetAndSaveFirstGoogleAccesToken();
            }

        } elseif( ($_GET['DePrOAuth2Callback'] == 1) && $_GET['error'] == 'access_denied' ) {
            wp_die('Access refused by user.');
	    }

    }

    //*********************************************************************************
    // отримуємо аксес токен і рефреш токен
    public function GetAndSaveFirstGoogleAccesToken(){

        $sUrl = 'https://accounts.google.com/o/oauth2/token';
        $aPostHeaders = array(
                    'method' => 'POST',
                    'host' => 'accounts.google.com',
                    'content-type' => 'application/x-www-form-urlencoded'
                    );
        $aPostData = array(
                'code' => get_option("GAAuthorizationCode"),
                'client_id' => $this->GAClientID,
                'client_secret' => $this->GAClientSecret,
                'redirect_uri' => $this->GARedirectUri,
                'grant_type' => 'authorization_code'
                );

        $aResult = wp_remote_post( $sUrl, array( 'headers' => $aPostHeaders, 'body' => $aPostData ) );

        if ( ! is_wp_error( $aResult ) ) {
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody );

            update_option( 'GAaccess_token', $oResponse->access_token );
            set_transient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );

            update_option( 'GArefresh_token', $oResponse->refresh_token );

            $this->ChooseCurrentSiteProfile();

            $sRedirectUri = get_bloginfo('url').'/wp-admin/options-general.php?page=depostrating-options';
            wp_safe_redirect( $sRedirectUri );
            exit;

        } else {
            wp_die($oResponse->error);
        }

    }

    //*********************************************************************************
    public function IsTokenExpired(){

        $sAccessToken = get_transient( 'GAaccess_token' );

        if ( $sAccessToken != false ) {
            return false; //nope, still active
        } else {
            return true; //yep, expired
        }

    }

    //*********************************************************************************
    // отримуємо новий аксес токен по рефреш токен
    public function GetAndSaveNewGoogleAccesToken(){

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

        $aResult = wp_remote_post( $sUrl, array( 'headers' => $aPostHeaders, 'body' => $aPostData ) );
        print_r($aResult);
        die();
        if ( ! is_wp_error( $aResult ) ) {
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody );

            update_option( 'GAaccess_token', $oResponse->access_token );
            set_transient( 'GAaccess_token', $oResponse->access_token, $oResponse->expires_in );
            return true;
        } else {
            //TODO логувати помилку $aResult->errors
            return false;
        }

    }

    //*********************************************************************************
	public function GetProfiles() {

        $bIsToken = true;
        if ( $this->IsTokenExpired() ) {
            $bIsToken = false;
            $bNewToken = GetAndSaveNewGoogleAccesToken();
        }

        if ( $bIsToken ) {
            $sUrl = 'https://www.googleapis.com/analytics/v3/management/accounts/~all/webproperties/~all/profiles';
            $sUrl = add_query_arg( array( 'access_token' => get_transient( 'GAaccess_token' ) ), $sUrl );

            $aResult = wp_remote_get( $sUrl, array( 'sslverify' => false ) );
            $ResponseBody = wp_remote_retrieve_body( $aResult );
            $oResponse = json_decode( $ResponseBody );
            //print_r($oResponse);
            return $oResponse;
        } else {
            return false;
        }

    }

    //*********************************************************************************
	public function ShowProfiles() {

        $oProfiles = $this->GetProfiles();

        if ( $oProfiles ) {
            $aAccounts = array();
            foreach ( $oProfiles->items as $oItem ) {
                $sId = $oItem->id;
                $sName = $oItem->websiteUrl;

                $sName = str_replace('http://', '', $sName);
                $sName = str_replace('https://', '', $sName);
                $sName = str_replace('www', '', $sName);
                $sName = rtrim($sName, '/');
                $sName = preg_replace( "/^\.+|\.+$/", "", $sName );

                $aAccounts[$sName] = $sId;
            }
            //print_r($aAccounts);
            return $aAccounts;
        }
    }

    //*********************************************************************************
	public function ChooseCurrentSiteProfile() {

        $aAccounts = $this->ShowProfiles();

        $sDomain = get_bloginfo('url');
        $sDomain = str_replace('http://', '', $sDomain);
        $sDomain = str_replace('https://', '', $sDomain);
        $sDomain = str_replace('www', '', $sDomain);

        if ( $aAccounts[$sDomain] )
            update_option( 'GAcurrent_site_acc', $aAccounts[$sDomain] );

    }

    //*********************************************************************************
    public function GetPageviewsByPagePath( $sPath ){

        if ( $this->IsTokenExpired() ) GetAndSaveNewGoogleAccesToken();

        $sUrl = 'https://www.googleapis.com/analytics/v3/data/ga';

        $aArgs = array(
                'ids' => 'ga:'.get_option('GAcurrent_site_acc'),
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
            //print_r($sTotalPageviews);
            return $iTotalPageviews;
        } else {
            return false;
        }

    }

}
?>