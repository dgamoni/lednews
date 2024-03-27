<?php
/*
 * TOP Core admin functions
 */

function top_add_acount_button( $service = 'twitter' ){
	global $opp_options;
	
	$social_users = $opp_options['top_logged_in_users'];
	$users = 0;
	
	if( !empty($social_users) ){
		$returnView = '';
		foreach($social_users as $user) {
			if( $user['service'] == $service ) {
				$user_details = (is_object($user['oauth_user_details'])) ? $user['oauth_user_details'] : json_decode($user['oauth_user_details']);
				
				$users++;
				$returnView .= '
					<div class="user_details">
						<div class="user_avatar">
							<img src="'. $user_details->profile_image_url .'" alt="'. $user_details->name .'" />
						</div>
						<div class="user_name">'. $user_details->name .'</div>
						<div class="remove_user">
							<a href="'. add_query_arg(array(
								'action' => 'logout_user',
								'user_id' => $user['user_id'],
								'redirect' => OPP_TOP_ADMIN_PAGE,
							), OPP_TOP_ADMIN_PAGE) .'" class="logout_user"></a>
						</div>
					</div><!-- end .user_details -->
                ';
			}
		}
	}
	
	if( !empty($users) ){
		$returnView .= '<button name="'. $service .'" id="'. $service .'-login" class="another-account login" service="'. $service .'">+</button>';
	} else {
		$returnView = '<button name="'. $service .'" id="'. $service .'-login" class="login" service="'. $service .'">Add Account</button>';
	}
	
	return $returnView;
}

function top_options(){
	global $opp_options;
	
	$top_tweet_type = $opp_options['top_tweet_type'];
	$top_tweet_custom_field = $opp_options['top_tweet_custom_field'];
	$top_add_text = $opp_options['top_add_text'];
	$top_add_text_at = $opp_options['top_add_text_at'];
	$top_include_link = $opp_options['top_include_link'];
	$top_custom_url_option = $opp_options['top_custom_url_option'];
	$top_custom_url_field = $opp_options['top_custom_url_field'];
	$top_use_url_shortner = $opp_options['top_use_url_shortner'];
	$top_url_shortner = $opp_options['top_url_shortner'];
	$top_bitly_key = $opp_options['top_bitly_key'];
	$top_bitly_user = $opp_options['top_bitly_user'];
	$top_custom_hashtag_option = $opp_options['top_custom_hashtag_option'];
	$top_hashtags = $opp_options['top_hashtags'];
	$top_custom_hashtag_field = $opp_options['top_custom_hashtag_field'];
	$top_hashtag_length = $opp_options['top_hashtag_length'];
	$top_interval = $opp_options['top_interval'];
	$top_age_limit = $opp_options['top_age_limit'];
	$top_max_age_limit = $opp_options['top_max_age_limit'];
	$top_no_of_tweet = $opp_options['top_no_of_tweet'];
	$top_post_with_image = $opp_options['top_post_with_image'];
	$top_tweet_multiple_times = $opp_options['top_tweet_multiple_times'];
	$top_ga_tracking = $opp_options['top_ga_tracking'];
	$top_omit_cats = $opp_options['top_omit_cats'];
	
	$top_opt_app_id = $opp_options['top_app_id'];
	$top_opt_app_secret = $opp_options['top_app_secret'];
	
	if( is_tweet_plugin_started() ){
		$tweetSwitch = '<input type="submit" class="button dashicons-before dashicons-twitter" name="stop-sharing" id="stop-sharing" value="Stop Sharing" />';
	} else {
		$tweetSwitch = '<input type="submit" class="button dashicons-before dashicons-twitter" name="start-sharing" id="start-sharing" value="Start Sharing" />';
	}
	
	print('
		<div class="wrap">
			<h1>
				<img src="'. plugins_url('../assets/img/icon.jpg', __FILE__ ) .'" alt="WP Repost" style="display: inline; vertical-align: middle;" />
				'. __('WP Repost', 'ows') .'
			</h1>

			' . opp_options_updated_notice( $_GET['message'] ) . '

			<h2>'. __('Tweet Old Posts', 'ows'). '</h2>
			
			<!-- FACEBOOK - Inline Colorbox -->
			<div style="display:none;">
				<div id="facebook-login-frame">
					<form id="top" class="wprepost" name="tweetoldpost" action="'. admin_url('admin.php?page=tweetoldpost') .'" method="post">
						<input type="hidden" name="top_action" value="top_update_settings" />
						<input type="hidden" name="redirect" value="'. admin_url('admin.php?page=tweetoldpost') .'" />
						
						<fieldset class="options">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><label for="top_opt_app_id_field">
									' . __('Facebook App ID', 'ows') . '
									</label></th>
									<td><input type="text" class="regular-text" name="top_opt_app_id" id="top_opt_app_id_field" value="'.$top_opt_app_id.'" autocomplete="off" placeholder="1487991504767913" class="regular-text" /><br>
									<span class="description">ID from your app created on facebook</span></td>
								</tr>
								
								<tr valign="top">
									<th scope="row"><label for="top_opt_app_secret_field">
									' . __('Facebook App Secret', 'ows') . '
									</label></th>
									<td><input type="text" class="regular-text" name="top_opt_app_secret" id="top_opt_app_secret_field" value="'.$top_opt_app_secret.'" autocomplete="off" placeholder="5124ea6d46e64da3c306f12812d0e4fx" /><br>
									<span class="description">Secret from your app created on facebook</span></td>
								</tr>
							</table>
							
							<button name="facebook" id="facebook-authorize" class="login button button-primary" service="facebook">Authorize App</button>
						</fieldset>
					</form>
				</div>
			</div>
			<!-- END OF FACEBOOK -->
			
			<form id="top" class="wprepost" name="tweetoldpost" action="'. admin_url('admin.php?page=tweetoldpost') .'" method="post">
			
				<input type="hidden" name="top_action" value="top_update_settings" />
				<input type="hidden" name="redirect" value="'. admin_url('admin.php?page=tweetoldpost') .'" />
				
				<fieldset class="options">
					<div class="option">
						<label for="twitter-login">
						' . __('Twitter Login<br> Use your Twitter account.', 'ows') . '
						</label>
						'. top_add_acount_button() .'
					</div>
					
					<div class="option">
						<label for="facebook-login">
						' . __('Facebook Login<br> Use your Facebook account.', 'ows') . '
						</label>
						'. top_add_acount_button( 'facebook' ) .'
					</div>
					
					<!-- <div class="option">
						<label for="linkedin-login">
						' . __('LinkedIn Login<br> Use your LinkedIn account.', 'ows') . '
						</label>
						'. top_add_acount_button( 'linkedin' ) .'
					</div> -->
					
					<div class="option">
						<label for="top_tweet_type">
						' . __('Post Content<br> What do you want to share?', 'ows') . '
						</label>
						<select name="top_tweet_type" id="top_tweet_type">
							<option value="title" '.selected('title',$top_tweet_type,false).'>'.__('Title Only', 'ows').'</option>
							<option value="body" '.selected('body',$top_tweet_type,false).'>'.__('Body Only', 'ows').'</option>
							<option value="titlenbody" '.selected('titlenbody',$top_tweet_type,false).'>'.__('Title and Body', 'ows').'</option>
							<option value="custom-field" '.selected('custom-field',$top_tweet_type,false).'>'.__('Custom Field', 'ows').'</option>
						</select>
					</div>
					
					<div class="option">
						<label for="top_tweet_custom_field">
						' . __('Which custom field to fetch info from?', 'ows') . '
						</label>
						<input type="text" class="regular-text" name="top_tweet_custom_field" id="top_tweet_custom_field" value="'.$top_tweet_custom_field.'" autocomplete="off" placeholder="Which custom field do you want to fetch info from?" />
					</div>
					
					<div class="option">
						<label for="top_add_text">
						' . __('Additional Text<br> Text added to your auto posts.', 'ows') . '
						</label>
						<input type="text" class="regular-text" name="top_add_text" id="top_add_text" value="'.$top_add_text.'" autocomplete="off" placeholder="Texts added to your auto posts" />
					</div>
					
					<div class="option">
						<label for="top_add_text_at">
						' . __('Where do you want the text to be added?', 'ows') . '
						</label>
						<select name="top_add_text_at" id="top_add_text_at">
							<option value="beginning" '.selected('beginning',$top_add_text_at,false).'>'.__('Beginning of Post', 'ows').'</option>
							<option value="end" '.selected('end',$top_add_text_at,false).'>'.__('End of Post', 'ows').'</option>
						</select>
					</div>
					
					<div class="option">
						<label for="top_include_link">
						' . __('Include Link<br> Include a link to your post?', 'ows') . '
						</label>
						<select name="top_include_link" id="top_include_link">
							<option value="true" '.selected('true',$top_include_link,false).'>'.__('Yes', 'ows').'</option>
							<option value="false" '.selected('false',$top_include_link,false).'>'.__('No', 'ows').'</option>
						</select>
					</div>
					
					<div class="option">
						<label for="top_custom_url_option">
						' . __('Fetch URL From Custom Field', 'ows') . '
						</label>
						<input type="checkbox" name="top_custom_url_option" id="top_custom_url_option" value="1" '. checked(1, $top_custom_url_option, false) .'" /> Enable
					</div>
					
					<div class="option">
						<label for="top_custom_url_field">
						' . __('Which custom field to fetch URL from?', 'ows') . '
						</label>
						<input type="text" class="regular-text" placeholder="URL will be fetched from the specified custom field." value="'. $top_custom_url_field .'" name="top_custom_url_field" id="top_custom_url_field">
					</div>
					
					<div class="option">
						<label for="top_use_url_shortner">
						' . __('Use URL Shortner', 'ows') . '
						</label>
						<input type="checkbox" name="top_use_url_shortner" id="top_use_url_shortner" value="1" '. checked(1, $top_use_url_shortner, false) .'" /> Enable
					</div>
					
					<div class="option url_shortner">
						<label for="top_url_shortner">
						' . __('URL Shortner Service', 'ows') . '
						</label>
						<select name="top_url_shortner" id="top_url_shortner">
							<option value="wp_short_url" '.selected('wp_short_url', $top_url_shortner, false).'>'.__('wp short url', 'ows').'</option>
							<option value="is.gd" '.selected('is.gd', $top_url_shortner, false).'>'.__('is.gd', 'ows').'</option>
							<option value="bit.ly" '.selected('bit.ly', $top_url_shortner, false).'>'.__('bit.ly', 'ows').'</option>
						</select>
					</div>
					
					<div class="option bitly">
						<label for="top_bitly_key">
						'. __('Bit.ly Key', 'ows') . '
						</label>
						<input type="text" class="regular-text" name="top_bitly_key" id="top_bitly_key" value="'. $top_bitly_key .'" />
					</div>
					
					<div class="option bitly">
						<label for="top_bitly_user">
						'. __('Bit.ly User', 'ows') . '
						</label>
						<input type="text" class="regular-text" name="top_bitly_user" id="top_bitly_user" value="'. $top_bitly_user .'" />
					</div>
					
					<div class="option">
						<label for="top_custom_hashtag_option">
						' . __('Hashtags<br> Include #hashtags in auto posts?', 'ows') . '
						</label>
						<select name="top_custom_hashtag_option" id="top_custom_hashtag_option">
							<option value="nohashtag" '.selected('nohashtag',$top_custom_hashtag_option,false).'>'.__('Don\'t add any hastags', 'ows').'</option>
							<option value="common" '.selected('common',$top_custom_hashtag_option,false).'>'.__('Common hastags for all shares', 'ows').'</option>
							<option value="categories" '.selected('categories',$top_custom_hashtag_option,false).'>'.__('Create hashtags from Categories', 'ows').'</option>
							<option value="tags" '.selected('tags',$top_custom_hashtag_option,false).'>'.__('Create hashtags from Tags', 'ows').'</option>
							<option value="custom" '.selected('custom',$top_custom_hashtag_option,false).'>'.__('Create hashtags Custom Fields', 'ows').'</option>
						</select>
					</div>
					
					<div class="option">
						<label for="top_hashtags">
						' . __('Common Hashtags<br> Specify hashtags to be used.', 'ows') . '
						</label>
						<input type="text" class="regular-text" name="top_hashtags" id="top_hashtags" value="'.$top_hashtags.'" placeholder="eg. #example, #example2" />
					</div>
					
					<div class="option">
						<label for="top_custom_hashtag_field">
						' . __('Which Custom Field to Fetch hashtags from?', 'ows') . '
						</label>
						<input type="text" class="regular-text" name="top_custom_hashtag_field" id="top_custom_hashtag_field" value="'.$top_custom_hashtag_field.'" placeholder="Fetch hashtags from specified custom field" />
					</div>
					
					<div class="option">
						<label for="top_hashtag_length">
						' . __('Maximum Hashtags Length<br> Set to 0 (characters) to include all.', 'ows') . '
						</label>
						<input type="text" class="regular-text" placeholder="Set to 0 (characters) to include all." value="'.$top_hashtag_length.'" name="top_hashtag_length" id="top_hashtag_length" />
					</div>
					
					<div class="option">
						<label for="top_interval">
						' . __('Minimum time between shares<br> (Hours), 0.4 can be used also.', 'ows') . '
						</label>
						<input type="text" class="regular-text" placeholder="Minimum time between shares (Hour/Hours), 0.4 can be used also." value="'.$top_interval.'" name="top_interval" id="top_interval">
					</div>
					
					<div class="option">
						<label for="top_age_limit">
						' . __('Minimum age of post to be eligible for sharing', 'ows') . '
						</label>
						<input type="text" class="regular-text" placeholder="Day/Days - 0 for Disabled" value="'.$top_age_limit.'" name="top_age_limit" id="top_age_limit">
					</div>
					
					<div class="option">
						<label for="top_max_age_limit">
						' . __('Maximum age of post to be eligible for sharing', 'ows') . '
						</label>
						<input type="text" class="regular-text" placeholder="Day/Days - 0 for Disabled" value="'.$top_max_age_limit.'" name="top_max_age_limit" id="top_max_age_limit">
					</div>
					
					<div class="option">
						<label for="top_no_of_tweet">
						' . __('Number of Posts to share share each time', 'ows') . '
						</label>
						<input type="text" class="regular-text" placeholder="Number of posts to share each time" value="'.$top_no_of_tweet.'" name="top_no_of_tweet" id="top_no_of_tweet">
					</div>
					
					<div class="option">
						<label for="top_post_with_image">
						' . __('Enable Feature Image', 'ows') . '
						</label>
						<input type="checkbox" id="top_post_with_image" name="top_post_with_image" value="1" '. checked(1, $top_post_with_image, false) .' /> Enable
					</div>
					
					<div class="option">
						<label for="top_tweet_multiple_times">
						' . __('Share old posts more than once', 'ows') . '
						</label>
						<input type="checkbox" id="top_tweet_multiple_times" name="top_tweet_multiple_times" value="1" '. checked(1, $top_tweet_multiple_times, false) .' /> Enable
						<span class="description">By default post will not be shared again until you stop/start the plugin</span>
					</div>
					
					<div class="option">
						<label for="top_ga_tracking">
						' . __('Enable Google Analytics Campaign Tracking', 'ows') . '
						</label>
						<input type="checkbox" id="top_ga_tracking" name="top_ga_tracking" value="1" '. checked(1, $top_ga_tracking, false) .'> Enable
					</div>
					
					<div class="option">
						<h3>' . __('Categories to Omit from Tweet:', 'ows') . '</h3>
						<div id="categories-all" class="ui-tabs-panel"> 
							<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
								');
								wp_category_checklist(0, 0, explode(',', $top_omit_cats));
								print('
							</ul>
						</div>
					</div>
				</fieldset>
				
				<p class="submit"><input class="button button-primary" type="submit" name="submit" value="'.__('Update Options', 'ows').'" /></p>
			</form>
			
			<form id="top" class="wprepost" name="tweetoldpost" action="'. admin_url('admin.php?page=tweetoldpost') .'" method="post">
				<input type="hidden" name="redirect" value="'. admin_url('admin.php?page=tweetoldpost') .'" />
				' . $tweetSwitch . '
			</form>
		</div>
	');
}

function opp_admin_help_tab_top(){
	$screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'top_help_tab',
        'title'	=> __('Help'),
		'callback' => 'top_help_tab_callback'
    ) );
}

function top_help_tab_callback( $screen, $tab ){
	printf(
		'<h3>' . __('Add Your Facebook Account by Following The Instructions', 'ows') . '</h3>
		<ol>
			<li>Go on <a href="https://developers.facebook.com/apps/" target="_blank">developers.facebook.com/apps</a></li>
			<li>Click on <strong>Create New App</strong> from the top right corner</li>
			<li>Enter a <strong>Display Name</strong> and <strong>Namespace</strong> and click on Create App</li>
			<li>Once you arrive on the app dashboard, copy your <strong>App ID</strong> and <strong>App Secret</strong> in the fields on the right</li>
			<li>Go on Settings tab from the left sidebar menu add the contact email and click on <strong>Add Platform</strong> and select <strong>Website</strong></li>
			<li>Copy/Paste this url : <strong>'. OPP_TOP_ADMIN_PAGE .'</strong> into App Domains and Site URL fields and <strong>Save</strong></li>
			<li>Go on Status &amp; Review tab and set your app live from the top-right switch.</li>
			<li>Now everything is done, click <a href="#facebook-login-frame" class="inline cboxElement"><strong>Authorize App</strong></a> now.</li>
		</ol>'
	);
}