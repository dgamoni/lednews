<?php
/*
 * Plugin Core functions
 */

function opp_old_post_promoter(){
	global $default_options, $opp_options;
	
	$default_options['opp_twitter_consumer_key'] = '';
	$default_options['opp_twitter_consumer_secret'] = '';
	$default_options['opp_twitter_oauth_token'] = '';
	$default_options['opp_twitter_oauth_secret'] = '';
	
	$default_options['oAuth_settings'] = array(
		'oauth_access_token' 		=> "2256465193-KDpAFIYfxpWugX2OU025b1CPs3WB0RJpgA4Gd4h",
		'oauth_access_token_secret' => "abx4Er8qEJ4jI7XDW8a90obzgy8cEtovPXCUNSjmwlpb9",
		'consumer_key' 				=> "ofaYongByVpa3NDEbXa2g",
		'consumer_secret' 			=> "vTzszlMujMZCY3mVtTE6WovUKQxqv3LVgiVku276M"
	);
	
	$default_options['top_logged_in_users'] = '';
	$default_options['top_oauth_token'] = '';
	$default_options['top_oauth_token_secret'] = '';
	$default_options['top_reauthorize'] = '';
	
	$default_options['top_app_id'] = '';
	$default_options['top_app_secret'] = '';
	$default_options['top_lk_app_id'] = '';
	$default_options['top_lk_app_secret'] = '';
	$default_options['top_lk_session_state'] = '';
	
	/**************************************************
	 *	UPDATE OPP INTEGRATION SETTINGS
	 *************************************************/
	if (!empty($_POST['opp_action']) && $_POST['opp_action'] == 'integration') {
		if (isset($_POST['opp_twitter_consumer_key'])) {
			update_option('opp_twitter_consumer_key',$_POST['opp_twitter_consumer_key']);
		}
		if (isset($_POST['opp_twitter_consumer_secret'])) {
			update_option('opp_twitter_consumer_secret',$_POST['opp_twitter_consumer_secret']);
		}
		if (isset($_POST['opp_twitter_oauth_token'])) {
			update_option('opp_twitter_oauth_token',$_POST['opp_twitter_oauth_token']);
		}
		if (isset($_POST['opp_twitter_oauth_secret'])) {
			update_option('opp_twitter_oauth_secret',$_POST['opp_twitter_oauth_secret']);
		}
		
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : OPP_SETTINGS_PAGE;
		wp_redirect(add_query_arg(array('settings-updated' => true), $redirect)); exit;
	}
	
	/**************************************************
	 *	UPDATE OPP GENERAL SETTINGS
	 *************************************************/
	if ( !empty($_POST['opp_action']) && $_POST['opp_action'] == 'general' ) {
		if (isset($_POST['opp_global_settings'])) {
			update_option('opp_global_settings',$_POST['opp_global_settings']);
		} else {
			delete_option('opp_global_settings');
		}
		
		if (isset($_POST['opp_single_reposting'])) {
			update_option('opp_single_reposting',$_POST['opp_single_reposting']);
		} else {
			delete_option('opp_single_reposting');
		}

		if (isset($_POST['opp_show_meta'])) {
			update_option('opp_show_meta',$_POST['opp_show_meta']);
		} else {
			delete_option('opp_show_meta');
		}
		
		if (isset($_POST['opp_promotion_date'])) {
			update_option('opp_promotion_date',$_POST['opp_promotion_date']);
		}
		if (isset($_POST['opp_interval'])) {
			update_option('opp_interval',$_POST['opp_interval']);
		}
		if (isset($_POST['opp_interval_slop'])) {
			update_option('opp_interval_slop',$_POST['opp_interval_slop']);
		}
		if (isset($_POST['opp_age_limit'])) {
			update_option('opp_age_limit',$_POST['opp_age_limit']);
		}
		if (isset($_POST['opp_post_status'])) {
			update_option('opp_post_status',$_POST['opp_post_status']);
		}
		if (isset($_POST['opp_show_original_pubdate'])) {
			update_option('opp_show_original_pubdate',$_POST['opp_show_original_pubdate']);
		}
		if (isset($_POST['opp_give_credit'])) {
			update_option('opp_give_credit',$_POST['opp_give_credit']);
		}
		if (isset($_POST['opp_pos'])) {
			update_option('opp_pos',$_POST['opp_pos']);
		}
		if (isset($_POST['opp_at_top'])) {
			update_option('opp_at_top',$_POST['opp_at_top']);
		}
		if (isset($_POST['post_category'])) {
			update_option('opp_omit_cats',implode(',',$_POST['post_category']));
		}
		else {
			update_option('opp_omit_cats','');			
		}
		
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : OPP_SETTINGS_PAGE;
		wp_redirect(add_query_arg(array('settings-updated' => true), $redirect)); exit;
	}
	
	/**************************************************
	 *	UPDATE TOP GENERAL SETTINGS
	 *************************************************/
	if (!empty($_POST['top_action'])) {
		$message = $message_updated;
		
		// Update options
		if (isset($_POST['top_tweet_type'])) {
			update_option('top_tweet_type', $_POST['top_tweet_type']);
		}
		if (isset($_POST['top_tweet_custom_field'])) {
			update_option('top_tweet_custom_field', $_POST['top_tweet_custom_field']);
		}
		if (isset($_POST['top_add_text'])) {
			update_option('top_add_text', $_POST['top_add_text']);
		}
		if (isset($_POST['top_add_text_at'])) {
			update_option('top_add_text_at', $_POST['top_add_text_at']);
		}
		if (isset($_POST['top_include_link'])) {
			update_option('top_include_link', $_POST['top_include_link']);
		}
		if (isset($_POST['top_custom_url_option'])) {
			update_option('top_custom_url_option', $_POST['top_custom_url_option']);
		} else {
			update_option('top_custom_url_option', 0);
		}
		if (isset($_POST['top_custom_url_field'])) {
			update_option('top_custom_url_field', $_POST['top_custom_url_field']);
		}
		if (isset($_POST['top_use_url_shortner'])) {
			update_option('top_use_url_shortner', $_POST['top_use_url_shortner']);
		} else {
			update_option('top_use_url_shortner', 0);
		}
		if (isset($_POST['top_url_shortner'])) {
			update_option('top_url_shortner', $_POST['top_url_shortner']);
		}
		if (isset($_POST['top_bitly_key'])) {
			update_option('top_bitly_key', $_POST['top_bitly_key']);
		}
		if (isset($_POST['top_bitly_user'])) {
			update_option('top_bitly_user', $_POST['top_bitly_user']);
		}
		if (isset($_POST['top_custom_hashtag_option'])) {
			update_option('top_custom_hashtag_option', $_POST['top_custom_hashtag_option']);
		}
		if (isset($_POST['top_hashtags'])) {
			update_option('top_hashtags', $_POST['top_hashtags']);
		}
		if (isset($_POST['top_custom_hashtag_field'])) {
			update_option('top_custom_hashtag_field', $_POST['top_custom_hashtag_field']);
		}
		if (isset($_POST['top_hashtag_length'])) {
			update_option('top_hashtag_length', $_POST['top_hashtag_length']);
		}
		if (isset($_POST['top_interval'])) {
			update_option('top_interval', $_POST['top_interval']);
		}
		if (isset($_POST['top_age_limit'])) {
			update_option('top_age_limit', $_POST['top_age_limit']);
		}
		if (isset($_POST['top_max_age_limit'])) {
			update_option('top_max_age_limit', $_POST['top_max_age_limit']);
		}
		if (isset($_POST['top_no_of_tweet'])) {
			update_option('top_no_of_tweet', $_POST['top_no_of_tweet']);
		}
		if (isset($_POST['top_post_with_image'])) {
			update_option('top_post_with_image', $_POST['top_post_with_image']);
		} else {
			update_option('top_post_with_image', 0);
		}
		if (isset($_POST['top_tweet_multiple_times'])) {
			update_option('top_tweet_multiple_times', $_POST['top_tweet_multiple_times']);
		} else {
			update_option('top_tweet_multiple_times', 0);
		}
		if (isset($_POST['top_ga_tracking'])) {
			update_option('top_ga_tracking', $_POST['top_ga_tracking']);
		} else {
			update_option('top_ga_tracking', 0);
		}
		if (isset($_POST['post_category'])) {
			update_option('top_omit_cats', implode(',', $_POST['post_category']));
		} else {
			update_option('top_omit_cats', '');
		}
		
		if( isset($_POST['twitter']) ){
			opp_add_social_account( 'twitter', $_POST );
		}elseif( isset($_POST['facebook']) ){
			opp_add_social_account( 'facebook', $_POST );
		}elseif( isset($_POST['linkedin']) ){
			opp_add_social_account( 'linkedin', $_POST );
		}
		
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : OPP_SETTINGS_PAGE;
		wp_redirect(add_query_arg(array('settings-updated' => true), $redirect)); exit;
	}
	
	/******************************************************
	 *	DISCONNECT ACOUNT
	 *****************************************************/
	if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout_user' ){
		$loggedOut = opp_logout_social_users( $_REQUEST['user_id'] );
		
		$redirect = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : OPP_SETTINGS_PAGE;
		wp_redirect(add_query_arg(array('settings-updated' => $loggedOut), $redirect)); exit;
	}
	
	/******************************************************
	 *	PREVIEW FB PAGE
	 *****************************************************/
	if( isset($_POST['preview_page']) && $_POST['preview_page'] != '' ){
		$user_details = array();
		$user_details['profile_image_url'] = $_POST['picture_url'];
		$user_details['name'] = $_POST['page_name'];
		$user_details = (object)$user_details;
		
		$newUser = array(
			'user_id'				=> $_POST['page_id'],
			'oauth_token'			=> $_POST['page_token'],
			'oauth_token_secret'	=> "",
			'oauth_user_details'	=> $user_details,
			'service'				=> 'facebook'
		);
		
		$loggedInUsers = get_option('top_logged_in_users');
		if(empty($loggedInUsers)) { $loggedInUsers = array(); }
		
		foreach ($loggedInUsers as $key=>$user) {
			if( $user['user_id'] == $page_id ) unset($loggedInUsers[$key]);
		}
		
		if( !in_array($newUser, $loggedInUsers) ){
			array_push($loggedInUsers, $newUser);
			update_option('top_logged_in_users', $loggedInUsers);
			
			wp_redirect(add_query_arg(array('settings-updated' => true), OPP_TOP_ADMIN_PAGE)); exit;
		} else {
			wp_redirect(add_query_arg(array('settings-updated' => false, 'message' => 'You already added that user! no can do!'), OPP_TOP_ADMIN_PAGE)); exit;
		}
	}
	
	/******************************************************
	 *	CONNECT ACOUNT TO LINKEDIN
	 *****************************************************/
	if( isset($_POST['linked_api']) && $_POST['linked_api'] != '' ){
		if( $_POST['linked_secret'] == '' ){
			wp_redirect(add_query_arg(array('settings-updated' => false, 'message' => 'LinkedIn App Error'), OPP_TOP_ADMIN_PAGE)); exit;
		}
		
		$top_session_state = uniqid('', true);
		
		$url = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id='
			. $_POST["linked_api"] . '&scope=rw_nus&state='
			. $top_session_state . '&redirect_uri=' . OPP_TOP_ADMIN_PAGE;
		
		update_option('top_lk_session_state', $top_session_state);
		update_option('top_lk_app_id', $_POST['linked_api']);
		update_option('top_lk_app_secret', $_POST['linked_secret']);
		
		wp_redirect( $url ); exit;
	}
	
	/******************************************************
	 *	START TOP SHARING
	 *****************************************************/
	if( isset($_POST['start-sharing']) && $_POST['start-sharing'] != '' ){
		start_tweet_plugin();
		
		$redirect = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : OPP_SETTINGS_PAGE;
		wp_redirect(
			add_query_arg(array(
				'settings-updated' => true,
				'message' => urlencode('Old posts are set to be shared/tweeted.')
			), $redirect)
		); exit;
	}
	
	/******************************************************
	 *	STOP TOP SHARING
	 *****************************************************/
	if( isset($_POST['stop-sharing']) && $_POST['stop-sharing'] != '' ){
		stop_tweet_plugin();
		
		$redirect = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : OPP_SETTINGS_PAGE;
		wp_redirect(
			add_query_arg(array(
				'settings-updated' => true,
				'message' => urlencode('Old posts are stopped from sharing.')
			), $redirect)
		); exit;
	}
	
	/******************************************************
	 *	FILL the plugin options with the current settings
	 *****************************************************/
	$opp_options = array();
	foreach( $default_options as $option=>$value ){
		$new_option = get_option($option);
		if( $new_option == false || $new_option == '' ){
			$opp_options[$option] = $value;
		} else {
			$opp_options[$option] = $new_option;
		}
	}
	
	/**************************************************
	 *	DO MANUAL RE-POST
	 *************************************************/
	if( isset($_POST['opp_repost_now']) ){
		opp_update_old_post( $_POST['post_ID'] );
		wp_redirect( add_query_arg(
			array(
				'post' => $_POST['post_ID'],
				'action' => 'edit',
				'message' => 11
			),
			admin_url('post.php')
		)); exit;
	 }
	
	/**************************************************
	 *	DO REPOSTING (SET FROM GLOBAL SETTINGS
	 *************************************************/
	if($opp_options['opp_global_settings']){
		// Do our scheduled promotion every defined interval
		if( opp_update_time() ){
			update_option('opp_last_update', time());
			opp_promote_old_post();
		}
		
		// Do our scheduled one time promotion in a specified date
		if( opp_promotion_day() ){
			update_option('opp_promotion_day_passed', time());
			opp_promote_old_post();
		}
	}
	
	/**************************************************
	 *	DO REPOSTING (SET FROM SINGLE SETTINGS
	 *************************************************
	
	I'm not sure how to do this but I'll try to do my best.
	
	PROBLEM!!!
	
	1.	I don't know how we can set a scheduled event to repost
		a specific post if the schedule of reposting are different per each post.
		
		It is a lot different from the global settings where it only has a specific
		schedule or time interval to trigger the event and just re-post one post that
		are eligible for re-posting.
	
	2.	If we loop through the posts, the site might slows down every time it loads.
	
	Let's break down our re-posting options with the least possible implementation
	
	1.	After a number of days, week or month.
		-	We query all the posts and loop on each to check if the post age are
			now eligible for re-posting.
	
	2.	Repost on a set date.
		-	We can just query the posts with the metadata that is equals to todays date
			and repost the posts. Much simpler.
	
	3.	Repost after a number of new posts are published? or created?
		-	Ok, this is another problem. This feature must be hooked into the save_post,
			wp_insert_post, or wp_update_post and then we'll query all the posts and check
			if they are eligible for re-posting. But, the admin page will slows down everytime
			a new post is created/published/updated.
	
	4.	Repeating Re-post.
		-	Our biggest problem and your (Mike) most liked feature. First of all, there is a
			lot of settings to check that will probably slows down the site...
				
				eg. checking if the repeat option is set to daily, weekly, and monthly and
					*	Check if the post is ready (start) for re-posting,
					
					*	Check if the post is eligible for re-posting
						depending on the date, day of the week and week of the month.
						
					*	Check if the post are not for re-posting anymore (Ends).
	
	
	I have finally fixed and solved the abovesaid problems!
	*/
	if( $opp_options['opp_single_reposting'] ){
		opp_promote_single_posts();
	}

	// FRONT END
	if( function_exists('woo_post_meta') ){
		add_action( 'woo_filter_post_meta', 'canvas_opp_show_meta', 10 );
		remove_filter( 'the_title', 'opp_show_meta', 10 );
	} else {
		if( !is_admin() ) add_filter( 'the_title', 'opp_show_meta', 10, 2 );
	}
	
	return $opp_options;
}

function opp_login_social_users( $code = '' ){
	global $default_options, $opp_options;
	
	if( time() - get_option("top_reauthorize") > 2592000 ){
		opp_reauthorize_app();
	}
	
	if( isset($_REQUEST['code']) ) $code = $_REQUEST["code"];
	
	// Twitter
	if( isset($_REQUEST['oauth_token']) ){
		if( $_REQUEST['oauth_token'] == $opp_options['top_oauth_token'] ){
			
			$twitter = new TwitterOAuth(
				$opp_options['oAuth_settings']['consumer_key'],
				$opp_options['oAuth_settings']['consumer_secret'],
				$opp_options['top_oauth_token'],
				$opp_options['top_oauth_token_secret']
			);
			
			$access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
			$user_details = $twitter->get('account/verify_credentials');
			
			$newUser = array(
				'user_id'				=> json_decode($user_details)->id,
				'oauth_token'			=> $access_token['oauth_token'],
				'oauth_token_secret'	=> $access_token['oauth_token_secret'],
				'oauth_user_details'	=> $user_details,
				'service'				=> 'twitter'
			);
			
			$loggedInUsers = $opp_options['top_logged_in_users'];
			if( empty($loggedInUsers) ) $loggedInUsers = array();
			
			if( in_array($newUser, $loggedInUsers) ){
				opp_options_updated_notice( __('You already added that user! no can do !', 'ows') );
			} else {
				array_push($loggedInUsers, $newUser);
				update_option('top_logged_in_users', $loggedInUsers);
			}
			
			wp_redirect( admin_url('admin.php?page=tweetoldpost') );
			exit;
		}
	}
	
	// Facebook
	if( isset($_REQUEST['state']) && (get_option('top_fb_session_state') === $_REQUEST['state']) ){
		$token_url = "https://graph.facebook.com/" . OPP_FB_API_VERSION . "/oauth/access_token?"
			. "client_id=" . get_option('top_app_id') . "&redirect_uri=" . OPP_TOP_ADMIN_PAGE
			. "&client_secret=" . get_option('top_app_secret') . "&code=" . $code;
		
		$params = null;
		$access_token = "";
		$response = wp_remote_get( $token_url );
		
		if( is_array($response) ){
			if( isset($response['body']) ){
				parse_str($response['body'], $params);
				if( isset($params['access_token']) ) $access_token = $params['access_token'];
			}
		}
		
		if( $access_token != "" ) update_option('top_fb_token', $access_token);
		wp_redirect( OPP_TOP_ADMIN_PAGE . '#fbadd' ); exit;
	}
}

function opp_logout_social_users( $userID = null ){
	if( !$userID ) return false;
	
	$users = get_option('top_logged_in_users');
	
	foreach ($users as $id => $user) {
		foreach ($user as $key => $value) {
			if($userID == $value) {
				$user_id = array_search($user, $users);
				unset($users[$user_id]);
			}
		}
	}
	
	update_option('top_logged_in_users', $users);
	
	return true;
}

function opp_reauthorize_app(){
	$top_session_state = uniqid('', true);
	update_option('top_reauthorize', time());
	
	$loggedInUsers = get_option('top_logged_in_users');
	if(empty($loggedInUsers)) $loggedInUsers = array();
	
	$lk = 0;
	$fb = 0;
	
	foreach( $loggedInUsers as $key=>$user ){
		if( $user['service'] === "linkedin" && $lk === 0 ){
			$lk++;
			$url = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id='
				. get_option("top_lk_app_id") . '&scope=rw_nus&state=' . $top_session_state
				. '&redirect_uri=' . OPP_TOP_ADMIN_PAGE;
			
			update_option( 'top_lk_session_state', $top_session_state );
			wp_redirect( $url ); exit;
		}
		
		if( $user['service'] === "facebook" && $fb === 0){
			$top_session_state_fb = md5(uniqid(rand(), TRUE));
			$fb++;
			
			update_option('top_fb_session_state', $top_session_state_fb);
			
			$dialog_url = "https://www.facebook.com/". OPP_FB_API_VERSION . "/dialog/oauth?client_id="
				. get_option('top_app_id') . "&redirect_uri=" . OPP_TOP_ADMIN_PAGE . "&state="
				. $top_session_state_fb . "&scope=publish_stream,publish_actions,manage_pages";
			
			wp_redirect( $dialog_url ); exit;
		}
	}
}

function opp_add_social_account( $service, $args = array() ){
	global $default_options, $opp_options;
	
	switch( $service ){
		case 'twitter':
			$oAuthCallback = $args['redirect'];
			$twitter = new TwitterOAuth(
				$default_options['oAuth_settings']['consumer_key'],
				$default_options['oAuth_settings']['consumer_secret']
			);
			$requestToken = $twitter->getRequestToken($oAuthCallback);
			
			update_option('top_oauth_token', $requestToken['oauth_token']);
			update_option('top_oauth_token_secret', $requestToken['oauth_token_secret']);
			
			switch( $twitter->http_code ){
				case 200:
					$url = $twitter->getAuthorizeURL($requestToken['oauth_token']);
					wp_redirect( $url ); exit;
					break;
				default:
					return __("Could not connect to Twitter!", CWP_TEXTDOMAIN);
					break;
			}
			break;
		
		case 'facebook':
			if( isset($_POST['top_opt_app_id']) && isset($_POST['top_opt_app_secret']) ){
				
				if( $_POST['top_opt_app_id'] == '' or $_POST['top_opt_app_secret'] == '' ){
					return false;
				}
				
				update_option('top_app_id', $_POST['top_opt_app_id']);
				update_option('top_app_secret', $_POST['top_opt_app_secret']);
				
				$top_session_state = md5(uniqid(rand(), TRUE));
				update_option('top_fb_session_state', $top_session_state);
				
				$dialog_url = "https://www.facebook.com/" . OPP_FB_API_VERSION . "/dialog/oauth?client_id="
				. $_POST['top_opt_app_id'] . "&redirect_uri=" . $args['redirect'] . "&state="
				. $top_session_state . "&scope=publish_stream,publish_actions,manage_pages";
				
				wp_redirect( $dialog_url ); exit;
			}
			break;
	}
}

function opp_facebook_display_pages(){
	$access_token = get_option('top_fb_token');
	
	$result1 = "";
	$pagearray1 = "";
	
	$pp = wp_remote_get("https://graph.facebook.com/" . OPP_FB_API_VERSION
		. "/me/accounts?access_token=$access_token&limit=100&offset=0");
	
	$me = wp_remote_get("https://graph.facebook.com/" . OPP_FB_API_VERSION
		. "/me/?access_token=$access_token&limit=100&offset=0");
	
	if( is_array($pp) ){
		$result1 = $pp['body'];
		$result2 = $me['body'];
		
		$pagearray1 = json_decode($result1);
		$pagearray2 = json_decode($result2);
		
		$profile['name'] = $pagearray2->first_name.' '.$pagearray2->last_name;
		$profile['id'] = $pagearray2->id;
		$profile['category'] = 'profile';
		$profile['access_token'] = $access_token;
		
		if( is_array($pagearray1->data) ) $result = array_unshift($pagearray1->data, $profile);
		
		$html = '<h3 style="text-align:center;">Choose a Profile or Page</h3>';
		$html .= '<table class="form-table fbadd">';
			foreach($pagearray1->data as $data){
				$data = (array) $data;
				
				$html .= '<tr valign="top">';
					$html .= '<th scope="row" class="page_avatar">';
						$html .= '<a href="#">';
							$html .= '<img src="https://graph.facebook.com/' . $data['id'] . '/picture"/>';
						$html .= '</a>';
					$html .= '</th>';
					
					$html .= '<td><a href="#">';
						$html .= '<div class="page_name">' . $data['name'] . '</div>';
						$html .= '<div class="page_category">' . substr($data['category'], 0, 9) . '</div>';
					$html .= '</a></td>';
					
					$html .= '<td align="right"><form id="top" class="wprepost" name="tweetoldpost" action="'. admin_url('admin.php?page=tweetoldpost') .'" method="post">';
						$html .= '<input type="submit" class="button button-primary" name="preview_page" value="Select" />';
						$html .= '<input type="hidden" name="picture_url" value="https://graph.facebook.com/' . $data['id'] . '/picture"/" />';
						$html .= '<input type="hidden" name="page_name" value="' . $data['name'] . '" />';
						$html .= '<input type="hidden" name="service" value="facebook" />';
						$html .= '<input type="hidden" name="page_token" value="' . $data['access_token'] . '" />';
						$html .= '<input type="hidden" name="page_id" value="'. $data['id'] .'" />';
					$html .= '</form></td>';
				$html .= '</tr>';
			}
		$html .= '</table>';
		
		echo $html;
	}
	die();
}

function stop_tweet_plugin( $args = array() ){
	global $opp_options;
	
	if( !is_tweet_plugin_started() ) return false;
	
	update_option('top_new_active_status', 'false');
	update_option('top_tweeted_posts', array());
	
	wp_clear_scheduled_hook('top_tweet_cron');
	
	return true;
}

function start_tweet_plugin( $args = array() ){
	global $opp_options;
	
	if( is_tweet_plugin_started() ) return false;
	
	update_option('top_new_active_status', 'true');
	update_option('top_tweeted_posts', array());
	
	$timeNow	= current_time('timestamp', 1);
	$interval	= floatval($opp_options['top_interval']) * 60 * 60;
	$timeNow	= $timeNow+25;
	
	wp_schedule_event( $timeNow, 'top_tweet_schedule', 'top_tweet_cron' );
	
	return true;
}

function opp_cron_schedules( $schedules ){
	global $opp_options;
	
	$schedules['top_tweet_schedule'] = array(
		'interval'	=> floatval($opp_options['top_interval']) * 60 * 60,
		'display'	=> __("Custom Tweet User Interval", 'opp')
	);
	
	return $schedules;
}

function is_tweet_plugin_started(){
	$is_active = get_option('top_new_active_status');
	if( $is_active === 'true' ){
		return true;
	}
	
	return false;
}

function opp_tweet_now(){
	opp_tweet_old_post_func( $post_id );
}

function opp_tweet_old_post_func( $byID = false ){
	global $opp_options;
	
	$returnedPost = opp_tweets_from_DB();
	if( $byID ) $returnedPost = opp_tweet_by_ID( $byID );
	$k = 0;
	$tweetCount = intval( $opp_options['top_no_of_tweet'] );
	
	if( count($returnedPost) == 0 ){
		/***
		 * There is no suitable post to tweet make sure
		 * excluded categories are properly selected
		 **/
	}
	
	while( $k <= $tweetCount ){
		$isAlreadyTweeted = opp_is_post_tweeted( $returnedPost[$k]->ID );
		if( $opp_options['top_tweet_multiple_times'] == 1 ) $isAlreadyTweeted = false;
		
		if( !$isAlreadyTweeted && ($k < count($returnedPost)) ){
			$post = $returnedPost[$k];
			
			$finalTweet = opp_tweet_from_post( $post );
			if( $opp_options['top_post_with_image'] == 1 ) {
				$resp = opp_tweet_with_image( $finalTweet, $post->ID );
			} else {
				$resp = opp_tweet_post( $finalTweet );
			}
			
			$tweetedPosts = get_option("top_tweeted_posts");
			if( $tweetedPosts == "" ) $tweetedPosts = array();
			array_push( $tweetedPosts, $post->ID );
			
			if( function_exists('w3tc_pgcache_flush') ){
				w3tc_dbcache_flush();
				w3tc_objectcache_flush();
				$cache = ' and W3TC Caches cleared';
			}
			
			update_option("top_tweeted_posts", $tweetedPosts);
			$k++;
		} else {
			if( count($returnedPost) != $tweetCount ){
				/***
				 * Trying to post more tweets that they are available,
				 * try to include more categories or increase the date range
				 */
			}
		}
	}
}

function opp_tweet_post( $finalTweet ){
	global $opp_options;
	
	$nrOfUsers = count( $opp_options['top_logged_in_users'] );
	$k=1;
	
	foreach( $opp_options['top_logged_in_users'] as $user ){
		switch( $user['service'] ){
			case 'twitter':
			default:
				$connection = new TwitterOAuth(
					get_option('opp_twitter_consumer_key'),
					get_option('opp_twitter_consumer_secret'),
					$user['oauth_token'],
					$user['oauth_token_secret']
				);
				$status = $connection->post('statuses/update', array('status' => $finalTweet['message']));
				break;
				
			case 'facebook':
				$args =  array(
					'body' => array(
						'message' => $finalTweet['message'],
						'link' => $finalTweet['link']
					)
				);
				$pp = wp_remote_post("https://graph.facebook.com/". OPP_FB_API_VERSION
					. "/$user[id]/feed?access_token=$user[oauth_token]", $args);
				$status = $pp['response']['message'];
				break;
			
			case 'linkedin':
				$visibility = "anyone";
				$content_xml = "
					<content><title>" . $finalTweet['message'] . "</title>
						<submitted-url>" . $finalTweet['link'] . "</submitted-url>
					</content>
				";
				$url = 'https://api.linkedin.com/v1/people/~/shares?oauth2_access_token='.$user["oauth_token"];
				
				$xml = '
					<?xml version="1.0" encoding="UTF-8"?>
					<share>
						' . $content_xml . '
						<visibility>
							<code>' . $visibility . '</code>
						</visibility>
					</share>
				';
				
				$headers = array(
					"Content-type: text/xml",
					"Content-length: " . strlen($xml),
					"Connection: close",
				);
				
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				
				$status = curl_exec($ch);
				curl_close($ch);
				break;
		}
		
		if ($nrOfUsers == $k) return $status;
		$k++;
	}
}

function opp_tweet_with_image( $finalTweet, $id ){
	global $opp_options;
	
	$nrOfUsers = count( $opp_options['top_logged_in_users'] );
	$k=1;
	
	foreach( $opp_options['top_logged_in_users'] as $user ){
		switch( $user['service'] ){
			case 'twitter':
			default:
				$connection = new TwitterOAuth(
					get_option('opp_twitter_consumer_key'),
					get_option('opp_twitter_consumer_secret'),
					$user['oauth_token'],
					$user['oauth_token_secret']
				);
				
				if( function_exists('topProImage') ) $status = topProImage($connection, $finalTweet['message'], $id);
				break;
			
			case 'facebook':
				$args =  array(
					'body' => array(
						'message' => $finalTweet['message'],
						'link' => $finalTweet['link']
					)
				);
				
				$pp = wp_remote_post( "https://graph.facebook.com/" . OPP_FB_API_VERSION
					. "/$user[id]/feed?access_token=$user[oauth_token]", $args);
				$status = $pp['response']['message'];
				break;
			
			case 'linkedin':
				$visibility = "anyone";
				$content_xml = "<content><title>" . $finalTweet['message'] . "</title>
					<submitted-url>" . $finalTweet['link'] . "</submitted-url></content>";
				
				$url = 'https://api.linkedin.com/v1/people/~/shares?oauth2_access_token=' . $user["oauth_token"];
				
				$xml = '<?xml version="1.0" encoding="UTF-8"?>
					<share>
						' . $content_xml . '
						<visibility>
							<code>' . $visibility . '</code>
						</visibility>
					</share>
				';
				
				$headers = array(
					"Content-type: text/xml",
					"Content-length: " . strlen($xml),
					"Connection: close",
				);
			
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				
				$status = curl_exec($ch);
				curl_close($ch);
				break;
		}
		
		if ($nrOfUsers == $k){
			return $status;
		}
		
		$k++;
	}
}

function opp_tweet_from_post( $postQuery ){
	global $opp_options;
	
	if($opp_options['top_custom_hashtag_option'] == 'nohashtag') {
		$newHashtags = "";
	}
	
	// Generate the tweet content.
	switch( $opp_options['top_tweet_type'] ) {
		case 'title':
			$tweetContent = $postQuery->post_title . " "; break;
		case 'body':
			$tweetContent = get_post_field('post_content', $postQuery->ID) . " "; break;
		case 'titlenbody':
			$tweetContent = $postQuery->post_title . " " . get_post_field('post_content', $postQuery->ID) . " ";
			break;
		case 'custom-field':
			$tweetContent = get_post_meta($postQuery->ID, $tweet_content_custom_field, true); break;
		default:
			$tweetContent = $finalTweet;
			break;
	}
	
	// Clean content
	$tweetContent = trim(preg_replace('/\s+/', ' ', $tweetContent));
	$tweetContent = preg_replace("/&#?[a-z0-9]+;/i","", $tweetContent);
	$tweetContent = strip_shortcodes( $tweetContent );
	
	if( $opp_options['top_include_link'] == 'true' ){
		if( $opp_options['top_custom_url_option'] == 1 ){
			$post_url = " " . get_post_meta( $postQuery->ID, $opp_options['top_custom_url_field'], true);
			if( $post_url == " ") $post_url = " " . get_permalink($postQuery->ID);
		} else {
			$post_url = " " . get_permalink($postQuery->ID);
		}
		
		if( $opp_options['top_ga_tracking'] == 1 ) {
			$param = 'utm_source=ReviveOldPost&utm_medium=social&utm_campaign=ReviveOldPost';
			$post_url = rtrim($post_url);
			if( strpos($post_url, "?") === FALSE ):
				$post_url.='?'.$param;
			else :
				$post_url.='&'.$param;
			endif;
		}
		
		if( $opp_options['top_use_url_shortner'] == 1 ) {
			$post_url = " " . opp_shortenURL(
				$post_url,
				$opp_options['top_url_shortner'],
				$postQuery->ID,
				$opp_options['top_bitly_key'],
				$opp_options['top_bitly_user']
			);
		}
		
		$post_url = $post_url . " ";
	} else {
		$post_url = "";
	}
	
	if($opp_options['top_custom_hashtag_option'] != 'nohashtag') {
		switch( $opp_options['top_custom_hashtag_option'] ) {
			case 'common':
				$newHashtags = $opp_options['top_hashtags']; break;
			case 'categories':
				$postCategories = get_the_category($postQuery->ID);
				foreach( $postCategories as $category ){
					if(strlen($category->cat_name.$newHashtags) <= $opp_options['top_hashtag_length'] || $opp_options['top_hashtag_length'] == 0) { $newHashtags = $newHashtags . " #" . preg_replace('/-/','',strtolower($category->slug)); }
				}
				break;
			case 'tags':
				$postTags = wp_get_post_tags($postQuery->ID);
				foreach( $postTags as $postTag ){
					if(strlen($postTag->slug.$newHashtags) <= $opp_options['top_hashtag_length'] || $opp_options['top_hashtag_length'] == 0) { $newHashtags = $newHashtags . " #" . preg_replace('/-/','',strtolower($postTag->slug)); }
				}
				break;
			case 'custom':
				$newHashtags = get_post_meta($postQuery->ID, $opp_options['top_custom_hashtag_field'], true); break;
		}
	}
	
	// Generate additional text
	if( $opp_options['top_add_text_at'] == 'beginning' ){
		$additionalTextBeginning = $opp_options['top_add_text'] . " ";
	}
	
	if( $opp_options['top_add_text_at'] == 'end' ){
		$additionalTextEnd = " " . $opp_options['top_add_text'];
	}
	
	$finalTweetLength = 0;
	
	if( !empty($opp_options['top_add_text']) ){
		$additionalTextLength = strlen($opp_options['top_add_text']);
		$finalTweetLength += intval($additionalTextLength);
	}
	
	if(!empty($post_url)) {
		$postURLLength = strlen($post_url); 
		if( $postURLLength > 21 ) $postURLLength = 22;
		$finalTweetLength += intval($postURLLength);
	}
	
	if(!empty($newHashtags)) {
		$hashtagsLength = strlen($newHashtags); 
		$finalTweetLength += intval($hashtagsLength);
	}
	
	if( $opp_options['top_post_with_image'] == 1 ) $finalTweetLength += 25;
	$finalTweetLength = 139 - $finalTweetLength - 5;
	
	$tweetContent = mb_substr($tweetContent, 0, $finalTweetLength) . " ";
	
	$finalTweet = $additionalTextBeginning.$tweetContent."%short_urlshort_urlurl%".$newHashtags.$additionalTextEnd;
	$finalTweet = substr($finalTweet, 0, 139);
	$finalTweet = str_replace("%short_urlshort_urlurl%", $post_url, $finalTweet);
	$fTweet = array();
	$fTweet['message'] = strip_tags($finalTweet);
	return $fTweet;
}

function opp_shortenURL($url, $service, $id, $bitly_key, $bitly_user){
	if( $service == "bit.ly" ){
		$url = trim($url);
		$bitly_key = trim($bitly_key);
		$bitly_user = trim($bitly_user);
		$shortURL = "http://api.bit.ly/v3/shorten?format=txt&login="
			. $bitly_user ."&apiKey=" . $bitly_key . "&longUrl={$url}";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "tr.im" ){
		$shortURL = "http://api.tr.im/api/trim_simple?url={$url}";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "3.ly" ){
		$shortURL = "http://3.ly/?api=em5893833&u={$url}";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "tinyurl" ){
		$shortURL = "http://tinyurl.com/api-create.php?url=" . $url;
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "u.nu" ){
		$shortURL = "http://u.nu/unu-api-simple?url={$url}";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "1click.at" ){
		$shortURL = "http://1click.at/api.php?action=shorturl&url={$url}&format=simple";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "is.gd" ){
		$shortURL = "http://is.gd/api.php?longurl={$url}";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} elseif ( $service == "t.co" ){
		$shortURL = "http://twitter.com/share?url={$url}";
		$shortURL = opp_sendRequest($shortURL, 'GET');
	} else {
		$shortURL = wp_get_shortlink($id);
	}
	
	if($shortURL != ' 400 ' && $shortURL != "500" && $shortURL != "0") return $shortURL;
	
	return $url;
}

function opp_is_post_tweeted( $post_id ){
	$tweetedPosts = get_option("top_tweeted_posts");
	
	if( !$tweetedPosts ){
		add_option("top_tweeted_posts");
		return false;
	}
	
	if( !empty($tweetedPosts) && is_array($tweetedPosts) ){
		if( in_array($post_id, $tweetedPosts) ) return true;
	}
	
	return false;
}

function opp_tweet_posts_dateRange(){
	global $opp_options;
	
	$minAgeLimit = "-" . $opp_options['top_age_limit'] . " days";
	if( $opp_options['top_max_age_limit'] == 0 ):
		$maxAgeLimit = "-9999 days";
	else :
		$maxAgeLimit = "-" . $opp_options['top_max_age_limit'] . " days";
	endif;
	
	$minAgeLimit = date("Y-m-d H:i:s", strtotime($minAgeLimit));
	$maxAgeLimit = date("Y-m-d H:i:s", strtotime($maxAgeLimit));
	
	if( isset($minAgeLimit) || isset($maxAgeLimit) ){
		$dateQuery = array();
		
		if( isset($minAgeLimit) ) $dateQuery['before'] = $maxAgeLimit;
		if( isset($maxAgeLimit) ) $dateQuery['after'] = $minAgeLimit;
		
		$dateQuery['inclusive'] = true;
	}
	
	if( !empty($dateQuery) ) return $dateQuery;
}

function opp_tweet_excluded_posts(){
	global $opp_options;
	
	$postQueryPosts = "";
	$postPosts = $opp_options['top_omit_posts'];
	
	if( !empty($postPosts) && is_array($postPosts) ){
		$lastPostPosts = end($postPosts);
		
		foreach( $postPosts as $key => $cat ){
			if( $cat == $lastPostPosts ){
				$postQueryPosts .= $cat;
			} else {
				$postQueryPosts .= $cat . ", ";
			}
		}
	} else {
		$postQueryPosts = $opp_options['top_omit_posts'];
	}
	
	return $postQueryPosts;
}

function opp_tweet_excluded_cats(){
	global $opp_options;
	
	$postQueryCategories = "";
	$postCategories = $opp_options['top_omit_cats'];
	
	if( !empty($postCategories) && is_array($postCategories) ){
		$lastPostCategory = end($postCategories);
		foreach( $postCategories as $key => $cat ){
			if( $cat == $lastPostCategory ){
				$postQueryCategories .= $cat;
			} else {
				$postQueryCategories .= $cat . ", ";
			}
		}
	} else {
		$postQueryCategories = $opp_options['top_omit_cats'];
	}
	
	return $postQueryCategories;
}

function opp_tweets_from_DB(){
	global $wpdb, $opp_options;
	
	$dateQuery = opp_tweet_posts_dateRange();
	$tweetCount = intval( $opp_options['top_no_of_tweet'] );
	
	$excludedIds = "";
	$tweetedPosts = get_option("top_tweeted_posts");
	if( !$tweetedPosts || $opp_options['top_tweet_multiple_times'] == 1){
		$tweetedPosts = array();
	}
	
	$postQueryExcludedPosts = opp_tweet_excluded_posts();
	if( $postQueryExcludedPosts == "" ) $postQueryExcludedPosts = array();
	
	$excludedPosts = array_merge($tweetedPosts, (array)$postQueryExcludedPosts);
	$nrOfExcludedPosts = count($excludedPosts);
	
	for( $k=0; $k<$nrOfExcludedPosts-1; $k++ ){
		$excludedIds .=$excludedPosts[$k].", ";
	}
	$excludedIds .= $excludedPosts[$nrOfExcludedPosts-1];
	$postQueryExcludedCategories = opp_tweet_excluded_cats();
	
	$somePostType = "'post'"; // Single posts for now
	$query = "
		SELECT *
		FROM {$wpdb->prefix}posts
		INNER JOIN {$wpdb->prefix}term_relationships ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}term_relationships.object_id)
		WHERE 1=1
		AND ((post_date >= '{$dateQuery['before']}' AND post_date <= '{$dateQuery['after']}'))
	";
	
	if( !empty($postQueryExcludedCategories) ){
		$query .= "
			AND ( {$wpdb->prefix}posts.ID NOT IN (
				SELECT object_id
				FROM {$wpdb->prefix}term_relationships
				INNER JOIN {$wpdb->prefix}term_taxonomy ON ( {$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id )
				WHERE {$wpdb->prefix}term_taxonomy.taxonomy =  'category'
				AND {$wpdb->prefix}term_taxonomy.term_id IN ({$postQueryExcludedCategories})
			))
		";
	}
	
	if( !empty($excludedIds) ){
		$query .= "AND ( {$wpdb->prefix}posts.ID NOT IN ({$excludedIds})) ";
	}
	
	$query .= "
		AND {$wpdb->prefix}posts.post_type IN ({$somePostType})
		AND ({$wpdb->prefix}posts.post_status = 'publish')
		GROUP BY {$wpdb->prefix}posts.ID
		ORDER BY RAND() DESC LIMIT 0,{$tweetCount}
	";
	
	$returnedPost = $wpdb->get_results($query);
	return $returnedPost;
}

function opp_tweet_by_ID( $post_id ){
	global $wpdb;
	
	$query = "SELECT * FROM {$wpdb->prefix}posts where ID = '{$post_id}'";
	$returnedPost = $wpdb->get_results($query);
	return $returnedPost;
}

function opp_options_updated_notice( $message = null, $key = false ){
	$message_updated = __("WP Repost Options Updated.", 'ows');
	
	if( $message == null ) $message = $message_updated;
	if( $key == false ) $key = 'settings-updated';
	
	if( isset($_GET[$key]) && $_GET[$key] == true )
	{
		print('
			<div id="message" class="updated fade">
				<p>' . urldecode($message) . '</p>
			</div>
		');
	}
	
	return;
}

function opp_promote_old_post(){
	global $wpdb;
	$omitCats = get_option('opp_omit_cats');
	$ageLimit = get_option('opp_age_limit');
	if (!isset($omitCats)) {
		$omitCats = OPP_OMIT_CATS;
	}
	if (!isset($ageLimit)) {
		$ageLimit = OPP_AGE_LIMIT;
	}
	$sql = "SELECT ID
            FROM $wpdb->posts
            WHERE post_type = 'post'
                  AND post_status = 'publish'
                  AND post_date < curdate( ) - INTERVAL ".$ageLimit." DAY 
                  ";
    if ($omitCats!='') {
    	$sql = $sql."AND NOT(ID IN (SELECT tr.object_id 
                                    FROM $wpdb->terms  t 
                                          inner join $wpdb->term_taxonomy tax on t.term_id=tax.term_id and tax.taxonomy='category' 
                                          inner join $wpdb->term_relationships tr on tr.term_taxonomy_id=tax.term_taxonomy_id 
                                    WHERE t.term_id IN (".$omitCats.")))";
    }            
    $sql = $sql."            
            ORDER BY RAND() 
            LIMIT 1 ";
	$oldest_post = $wpdb->get_var($sql);   
	if (isset($oldest_post)) {
		opp_update_old_post($oldest_post);
	}
}

function opp_promote_single_posts(){
	global $wpdb, $wp_query;
	$today = date('m/d/Y');
	$omitCats = get_option('opp_omit_cats');
	if ( !isset($omitCats) ) $omitCats = OPP_OMIT_CATS;
	
	// Checks if we have already done promoting posts for today
	if( (get_option('last_single_promotion') != false) && strtotime($today) <= get_option('last_single_promotion') ) return;
	
	$original_query = $wp_query;
	$wp_query = new WP_Query(
		array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'post_status' => 'any',
			'category__not_in' => $omitCats,
			'meta_query' => array(
				array(
					'key'		=> 'opp_repost_schedule',
					'value'		=> $today,
					'compare'	=> 'LIKE',
					'type'		=> 'CHAR'
				)
			)
		)
	);
	
	if( $wp_query->have_posts() ){
		while( $wp_query->have_posts() ){
			$wp_query->the_post();
			opp_update_old_post( get_the_ID() );
			
			// Update next post schedule if nescessary
			update_single_repost_schedule( get_the_ID() );
		}
	}
	
	update_option('last_single_promotion', strtotime($today));
	
	$wp_query = $original_query;
	unset($original_query);
	
	return $wp_query;
}

function opp_update_old_post($oldest_post){
	global $wpdb;
	$post = get_post($oldest_post);
	$origPubDate = get_post_meta($oldest_post, 'opp_original_pub_date', true); 
	if (!(isset($origPubDate) && $origPubDate!='')) {
	    $sql = "SELECT post_date from ".$wpdb->posts." WHERE ID = '$oldest_post'";
		$origPubDate=$wpdb->get_var($sql);
		add_post_meta($oldest_post, 'opp_original_pub_date', $origPubDate);
		$origPubDate = get_post_meta($oldest_post, 'opp_original_pub_date', true); 
	}
	$opp_pos = get_option('opp_pos');
	if (!isset($opp_pos)) {
		$opp_pos = 0;
	}
	if ($opp_pos==1) {
		/*****************************************************************
		 *	makes the posts look natural
		 *	$new_time = date('Y-m-d H:i:s');
		 ****************************************************************/
		$post_date = strtotime($post->post_date);
		$d = mktime(date("H", $post_date),date("i", $post_date),date("s", $post_date),date("m"),date("d"),date("Y"));
		$new_time = date('Y-m-d H:i:s', $d );
		$gmt_time = get_gmt_from_date($new_time);
	} else {
		$lastposts = get_posts('numberposts=1&offset=1');
		foreach ($lastposts as $lastpost) {
			$post_date = strtotime($lastpost->post_date);
			$new_time = date('Y-m-d H:i:s',mktime(date("H",$post_date),date("i",$post_date),date("s",$post_date)+1,date("m",$post_date),date("d",$post_date),date("Y",$post_date)));
			$gmt_time = get_gmt_from_date($new_time);
		}
	}
	

	/****************************************************************************************************************
	$sql = "UPDATE $wpdb->posts SET post_date = '$new_time',post_date_gmt = '$gmt_time',post_modified = '$new_time',post_modified_gmt = '$gmt_time' WHERE ID = '$oldest_post'";

	We only want the post to be updated when content was changed!!!
	****************************************************************************************************************/ 
	$sql = "UPDATE $wpdb->posts SET post_date = '$new_time',post_date_gmt = '$gmt_time' WHERE ID = '$oldest_post'";

	$wpdb->query($sql);

	if (function_exists('wp_cache_flush')) {
		wp_cache_flush();
	}
	
	$permalink = get_permalink($oldest_post);
	$shorturl = opp_get_short_url($permalink);
	opp_tweet($post->post_title." ".$shorturl." #OPP");
	
	//reping
	$services = get_settings('ping_sites');
	$services = preg_replace("|(\s)+|", '$1', $services);
	$services = trim($services);
	if ( '' != $services ) {
		set_time_limit(300);
		$services = explode("\n", $services);
		foreach ($services as $service) {
			opp_sendXmlrpc($service,$permalink);
		}
	}
	
	do_action( 'old_post_promoted', $post ); 
}

/***********************************************************************
 * Set Single Re-Post Next Schedule
 * @param (Integer) $post_id
 *
 * @return (date) new schedule
 **********************************************************************/
function update_single_repost_schedule( $post_id ){
	$option = get_post_meta( $post_id, 'opp_repost_option', true );
	$number = get_post_meta( $post_id, 'opp_repost_after_number', true );
	$time = get_post_meta( $post_id, 'opp_repost_after_time', true );
	$after_post = get_post_meta( $post_id, 'opp_repost_after_post', true );
	$repeats = get_post_meta( $post_id, 'opp_repost_repeats', true );
	$every = get_post_meta( $post_id, 'opp_repost_every', true );
	$weekly = maybe_unserialize(get_post_meta( $post_id, 'opp_repost_on', true ));
	if( empty($weekly) ) $weekly = array();
	
	$monthly = get_post_meta( $post_id, 'opp_repost_by', true );
	$monthly_on = maybe_unserialize(get_post_meta( $post_id, 'opp_repost_monthly_on', true ));
	if( empty($monthly_on) ) $monthly_on = array();

	$start = get_post_meta( $post_id, 'opp_repost_start', true );
	$end = get_post_meta( $post_id, 'opp_repost_end', true );
	$occurence = get_post_meta( $post_id, 'opp_repost_end_after', true );
	$return = '';
	
	$on = strtotime(get_post_meta( $post_id, 'opp_repost_on_date', true ));
	$end_on = strtotime(get_post_meta( $post_id, 'opp_repost_end_on', true ));
	$schedule = strtotime(get_post_meta( $post_id, 'opp_repost_schedule', true ));
	$today = date('m/d/Y');
	
	$reposted = unserialize(get_post_meta( $post->ID, 'wp_repost_date', $post->post_date ));
	if( empty($reposted) ) $reposted = array();
	
	if( $option != 'repeat' ) return;
	
	switch( $repeats ){
		case 'yearly':
			$new_schedule = date('m/d/Y', strtotime('+'. $every .' years', $schedule));
			break;
		
		case 'monthly':
			$new_schedule = date('m/d/Y', strtotime('+1 months', $schedule));
			if( $monthly == 'week' ){
				$new_schedule = date('m/d/Y', strtotime(literalDate(date('Y-m-d', $schedule)), $new_schedule));
			}

			// Check if next month is turned on
			if( !empty($monthly_on) ){
				while( !in_array(strtolower(date('F', strtotime($new_schedule))), $monthly_on) ){
					$new_schedule = date('m/d/Y', strtotime('+1 months', $schedule));

					if( $monthly == 'week' ){
						$new_schedule = date('m/d/Y', strtotime(literalDate(date('Y-m-d', $schedule)), $new_schedule));
					}
				}
			}
			break;
		
		case 'weekly':
			if( count($weekly) >= 7 ){ // This is same as daily update
				$new_schedule = date('m/d/Y', strtotime('+'. $every .' days', $schedule));
			} else {
				$weekArray = array(
					'sunday', 'monday', 'tuesday', 'wednesday',
					'thursday', 'friday', 'saturday'
				);
				$currWeekDay = date('l', strtotime($today));
				$nextWeekDay = array_search(strtolower($currWeekDay), $weekArray);
				$nextDay = null;

				while( $nextDay == null ){
					$nextWeekDay++;

					if( $nextWeekDay > 6 ){
						$nextWeekDay = -1;
					}

					if( in_array($weekArray[$nextWeekDay], $weekly) ){
						$nextDay = $weekArray[$nextWeekDay];
					}
				}

				if( $every >= 1 ){
					$new_schedule = date('m/d/Y', strtotime('+'.$every.' weeks', strtotime('next ' . ucfirst($nextDay), $schedule)));
				} else {
					$new_schedule = date('m/d/Y', strtotime('next ' . ucfirst($nextDay), $schedule));
				}
			}
			break;
		
		case 'daily':
		default:
			$new_schedule = date('m/d/Y', strtotime('+'. $every .' days', $schedule));
			break;
	}
	
	if( $end == 'on' ){
		if( strtotime($new_schedule) >= $end_on ){
			$new_schedule = date('m/d/Y', $schedule);
		}
	} elseif( $end == 'after' ){
		if( count($reposted) > $occurence ){
			$new_schedule = date('m/d/Y', $schedule);
		}
	}
	
	update_post_meta( $post_id, 'opp_repost_schedule', $new_schedule );
	return $new_schedule;
}

function literalDate($timestamp) {
    $timestamp = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);
    $weekday   = date('l', $timestamp);
    $month     = date('M', $timestamp);   
    $ord       = 0;

    while(date('M', ($timestamp = strtotime('-1 week', $timestamp))) == $month) {
        $ord++;
    }

    $lit = array('first', 'second', 'third', 'fourth', 'fifth');
    return strtolower($lit[$ord].' '.$weekday);
}

/***********************************************************************
 * Get Single Re-Post Schedule
 * @param (Integer) $post_id
 * @param (String) Return Type [string | raw]
 *
 * @return (string) Repost Schedule | (date) schedule
 **********************************************************************/
function get_single_repost_schedule( $post_id, $return_type = 'raw' ){
	$option = get_post_meta( $post_id, 'opp_repost_option', true );
	$number = get_post_meta( $post_id, 'opp_repost_after_number', true );
	$time = get_post_meta( $post_id, 'opp_repost_after_time', true );
	$after_post = get_post_meta( $post_id, 'opp_repost_after_post', true );
	$repeats = get_post_meta( $post_id, 'opp_repost_repeats', true );
	$every = get_post_meta( $post_id, 'opp_repost_every', true );
	$weekly = maybe_unserialize(get_post_meta( $post_id, 'opp_repost_on', true ));
	if( empty($weekly) ) $weekly = array();
	
	$monthly = get_post_meta( $post_id, 'opp_repost_by', true );
	$start = get_post_meta( $post_id, 'opp_repost_start', true );
	$end = get_post_meta( $post_id, 'opp_repost_end', true );
	$occurence = get_post_meta( $post_id, 'opp_repost_end_after', true );
	$return = '';
	
	$on = strtotime(get_post_meta( $post_id, 'opp_repost_on_date', true ));
	$end_on = strtotime(get_post_meta( $post_id, 'opp_repost_end_on', true ));
	$schedule = get_post_meta( $post_id, 'opp_repost_schedule', true );
	$schedule = ($schedule) ? strtotime($schedule) : false;
	$today = date('m/d/Y');
	
	switch( $option ){
		case 'days':
		case 'date':
			if ( strtotime($today) == $schedule ) {
				$return .= sprintf('Set today, <strong>%s</strong>.', date('F j, Y', $schedule));
			} elseif( strtotime($today) >= $schedule ) {
				$return .= sprintf('Was set on <strong>%s</strong>.', date('F j, Y', $schedule));
			} else {
				$return .= sprintf('Set on <strong>%s</strong>.', date('F j, Y', $schedule));
			}
			break;
		
		case 'repeat':
			switch( $repeats ){
				case 'yearly':
					$return .= sprintf(
						'Set %s',
						($every > 1) ? 'every ' . $every . ' years' : ' Yearly'
					);
					break;
				
				case 'monthly':
					$return .= sprintf(
						'Set %s on %s',
						($every > 1) ? 'every ' . $every . ' months' : ' Monthly',
						($monthly == 'month') ? 'day '.date('d', $schedule) : 'the '.opp_get_nth_week($schedule). ' '.date('l', $schedule)
					);
					break;
				
				case 'weekly':
					$weekdays = array();
					foreach($weekly as $weekday){
						$weekdays[] = ucfirst($weekday);
					}
					$return .= sprintf(
						'Set %s on %s',
						($every > 1) ? 'every ' . $every . ' weeks' : ' Weekly',
						(!empty($weekly)) ? ((count($weekly) >= 7) ? 'all days' : implode(', ', $weekdays)) : date('l', $schedule)
					);
					break;
				
				default:
				case 'daily':
					$return .= sprintf(
						'Set %s',
						($every > 1) ? 'every ' . $every . ' days' : ' Daily'
					);
					break;
			}
			if ( $end == 'after' ) {
				$return .= sprintf(', %s times.', $occurence);
			} elseif( $end == 'on' ) {
				$return .= sprintf(', until %s.', date('F j, Y', $end_on));
			}
			$return .= sprintf(' Next repost schedule is on %s', date('m/d/Y', $schedule));
			break;
	}
	
	if( $return_type == 'raw' ){
		return $schedule;
	}
	
	return $return;
}

function canvas_opp_show_meta( $post_info ){
	global $opp_options, $post;

	if( get_post_type() != 'post' ) return $post_info;
	if( !$opp_options['opp_show_meta'] ) return $post_info;

	$origPubDate = get_post_meta($post->ID, 'opp_original_pub_date', true);
	$reposted = unserialize(get_post_meta( $post->ID, 'wp_repost_date', $post->post_date ));
	$last_reposted = $reposted[ count($reposted) - 1 ];

	if( !empty($reposted) ){
		$post_info .= '<span class="entry-meta">';

		$post_info .= sprintf(
			'Originally Posted on %s',
			date(get_option('date_format'), strtotime($origPubDate))
		);

		if((strtotime($post->post_modified) != strtotime($origPubDate)) && (strtotime($post->post_modified) != strtotime($last_reposted))){
			$post_info .= sprintf(
				', last updated on %s',
				date(get_option('date_format'), strtotime($post->post_modified))
			);
		}

		$post_info .= sprintf(
			' and reposted on %s',
			date(get_option('date_format'), strtotime($last_reposted))
		);

		$post_info .= '</span>';
	}

	return $post_info;
}

function opp_show_meta( $title, $id = null ){
	global $opp_options;

	if( get_post_type($id) != 'post' ) return $title;
	if( !$opp_options['opp_show_meta'] ) return $title;

	$post = get_post( $id );
	$origPubDate = get_post_meta($id, 'opp_original_pub_date', true);
	$reposted = unserialize(get_post_meta( $id, 'wp_repost_date', $post->post_date ));
	$last_reposted = $reposted[ count($reposted) - 1 ];

	if( !empty($reposted) ){
		$title .= '<span class="entry-meta">';

		$title .= sprintf(
			'Originally Posted on %s',
			date(get_option('date_format'), strtotime($origPubDate))
		);

		if( (strtotime($post->post_modified) != strtotime($origPubDate)) && (strtotime($post->post_modified) != strtotime($last_reposted)) ){
			$title .= sprintf(
				', last updated on %s',
				date(get_option('date_format'), strtotime($post->post_modified))
			);
		}

		$title .= sprintf(
			' and reposted on %s',
			date(get_option('date_format'), strtotime($last_reposted))
		);

		$title .= '</span>';
	}

	return $title;
}

function opp_get_nth_week( $date ){
	$array = array(
		"", "first", "second", "third", "fourth", "fifth"
	);
	
	return $array[ceil((date("d", $date) - date("w", $date) - 1) / 7)];
}

function opp_set_promoted_post_metadata( $post ){
	$reposted = unserialize(get_post_meta( $post->ID, 'wp_repost_date', $post->post_date ));
	if( empty($reposted) ) $reposted = array();

	$reposted[] = date('Y-m-d H:i:s'); // $post->post_date; Set to today's date
	
	update_post_meta( $post->ID, 'wp_repost_date', maybe_serialize($reposted) );
	return $post;
}

function opp_set_promoted_post_status( $post ){
	global $opp_options;

	if( !$opp_options['opp_global_settings'] ) return;
	
	$post_status = $opp_options['opp_post_status'];
	
	if( $post_status != 0 ){
		wp_update_post(array(
			'ID'			=>	$post->ID,
			'post_date'		=>	$post->post_date,
			'post_date_gmt'	=>	$post->post_date_gmt,
			'post_status'	=>	$post_status
		));
	}
	return $post;
}

function opp_get_short_url($url){
	$shorturl = $url;
	$wppost = array();
	$wppost["site"] = get_option('siteurl');
	$wppost["url"] = $url;
	$f=new xmlrpcmsg('bte.shorturl',
		array(php_xmlrpc_encode($wppost))
	);
	$c=new xmlrpc_client(OPP_XMLRPC, OPP_XMLRPC_URI, 80);
	if (false) {
		$c->setDebug(1);
	}
	$r=&$c->send($f);
	if(!$r->faultCode()) {
		$sno=$r->value();
		if ($sno->kindOf()!="array") {
			$err="Found non-array as parameter 0";
		} else {
			for($i=0; $i<$sno->arraysize(); $i++)
			{
				$rec=$sno->arraymem($i);
				$shorturl = $rec->structmem("shorturl");
				if ($shorturl!=null) {
					$shorturl = $shorturl->scalarval();
				}	
			}		
		}
	} else {
		error_log("[".date('Y-m-d H:i:s')."][bte_opp.opp_get_short_url] ".$post->guid." error code: ".htmlspecialchars($r->faultCode()));
		error_log("[".date('Y-m-d H:i:s')."][bte_opp.opp_get_short_url] ".$post->guid." reason: ".htmlspecialchars($r->faultString()));
	}
	return $shorturl;
}


/**
 * A modified version of WP's ping functionality "weblog_ping" in functions.php
 * Uses correct extended Ping format and logs response from service.
 * @param string $server
 * @param string $path
 */
function opp_sendXmlrpc($server, $permalink){
	include_once (ABSPATH . WPINC . '/class-IXR.php');
	$path = '';
	// using a timeout of 3 seconds should be enough to cover slow servers
	$client = new IXR_Client($server, ((!strlen(trim($path)) || ('/' == $path)) ? false : $path));
	$client->timeout = 3;
	$client->useragent .= ' -- WordPress/OPP';

	// when set to true, this outputs debug messages by itself
	$client->debug = false;
	$home = trailingslashit(get_option('home'));
			
	///$this->_post_title = $this->_post_title.'###'.$check_url;///
	// the extendedPing format should be "blog name", "blog url", "permalink", and "feed url",
	// but it would seem as if the standard has been mixed up. It's therefore good to repeat the feed url.
	// $this->_post_type = 2 if new post and 3 if future post
	if ( $client->query('weblogUpdates.extendedPing', get_settings('blogname'), $home, $permalink, get_bloginfo('rss2_url')) ) { 
		//error_log($server." was successfully pinged (extended format)");
	} else {
		if ( $client->query('weblogUpdates.ping', get_settings('blogname'), $home) ) {
			//error_log($server." was successfully pinged");
		} else {
			//error_log($server." could not be pinged. Error message: \"".$client->error->message."\"");
		}
	}
}


function opp_tweet($tweet){
	$consumer_key = get_option('opp_twitter_consumer_key');
	$consumer_secret = get_option('opp_twitter_consumer_secret');
	$oauth_token = get_option('opp_twitter_oauth_token');
	$oauth_secret = get_option('opp_twitter_oauth_secret');
	
	if (empty($consumer_key) 
		|| empty($consumer_secret) 
		|| empty($oauth_token) 
		|| empty($oauth_secret) 
		|| empty($tweet)
	) {
		return;
	}
	
	require_once('twitteroauth.php');
	$connection = new TwitterOAuth(
			$consumer_key, 
			$consumer_secret, 
			$oauth_token, 
			$oauth_secret
		);
	$connection->useragent = 'Old Post Promoter http://www.blogtrafficexchange.com/old-post-promoter/';
	
	$connection->post(
		BTE_RT_API_POST_STATUS
		, array(
			'status' => $tweet
			, 'source' => 'Old Post Promoter'
		)
	);
	if (strcmp($connection->http_code, '200') == 0) {
		return true;
	}
	return false;
}


function opp_the_content($content){
	global $post;
	$showPub = get_option('opp_show_original_pubdate');
	if (!isset($showPub)) {
		$showPub = 1;
	}
	$givecredit = get_option('opp_give_credit');
	if (!isset($givecredit)) {
		$givecredit = 1;
	}
	$origPubDate = get_post_meta($post->ID, 'opp_original_pub_date', true);
	$dateline = '';
	if (isset($origPubDate) && $origPubDate!='') {
		if ($showPub || $givecredit) {
			$dateline.='<p id="bte_opp"><small>';
			if ($showPub) {
				$dateline.='Originally posted '.$origPubDate.'. ';
			}
			if ($givecredit) {
					$dateline.='Republished by  <a href="http://www.blogtrafficexchange.com/old-post-promoter/">Blog Post Promoter</a>';
			}
			$dateline.='</small></p>';
		}
	}
	$atTop = get_option('opp_at_top');
	if (isset($atTop) && $atTop) {
		$content = $dateline.$content;
	} else {
		$content = $content.$dateline;
	}
	return $content;
}

function opp_update_time(){
	$last = get_option('opp_last_update');		
	$interval = get_option('opp_interval');		
	if (!(isset($interval) && is_numeric($interval))) {
		$interval = OPP_INTERVAL;
	}
	$slop = get_option('opp_interval_slop');		
	if (!(isset($slop) && is_numeric($slop))) {
		$slop = OPP_INTERVAL_SLOP;
	}
	if (false === $last) {
		$ret = 1;
	} else if (is_numeric($last)) { 
		$ret = ( (time() - $last) > ($interval+rand(0,$slop)));
	}
	return $ret;
}

function opp_promotion_day(){
	$promotion_date = get_option('opp_promotion_date');
	$promotion_passed = get_option('opp_promotion_day_passed');
	if( !$promotion_date ) $promotion_date = OPP_PROMOTION_DATE;
	
	if( $promotion_date ){
		// Check if today is the promotion date
		if( strtotime($promotion_date) == strtotime(date('m/d/y')) ){
			// Make sure to promote just once on promotion day
			if( !$promotion_passed ){
				return true;
			} else {
				// Check if promotion has been made today
				if( strtotime(date('m/d/Y')) == strtotime(date('m/d/Y', $promotion_passed)) ){
					return false;
				} else {
					return true;
				}
			}
		}
	}
	
	return false;
}

function opp_sendRequest($url, $method='GET', $data='', $auth_user='', $auth_pass='') {
	$ch = curl_init($url);

	if (strtoupper($method) == "POST") {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}

	if (ini_get('open_basedir') == '' && ini_get('safe_mode') == 'Off') {
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	}

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	if ($auth_user != '' && $auth_pass != '') {
		curl_setopt($ch, CURLOPT_USERPWD, "{$auth_user}:{$auth_pass}");
	}

	$response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	if ($httpcode != 200) {
		return $httpcode;
	}

	return $response;
}