<?php
/*
 * OPP Core admin functions
 */

function opp_options(){
	global $opp_options;
	
	$global_settings = $opp_options['opp_global_settings'];
	$single_reposting = $opp_options['opp_single_reposting'];
	$show_meta = $opp_options['opp_show_meta'];
	
	$omitCats = $opp_options['opp_omit_cats'];
	$ageLimit = $opp_options['opp_age_limit'];
	$showPub = $opp_options['opp_show_original_pubdate'];
	$post_status = $opp_options['opp_post_status'];
	$atTop = $opp_options['opp_at_top'];
	$opp_give_credit = $opp_options['opp_give_credit'];
	$opp_pos = $opp_options['opp_pos'];
	$promotion_date = $opp_options['opp_promotion_date'];
	$interval = $opp_options['opp_interval'];
	$slop = $opp_options['opp_interval_slop'];
	$get_posts = $opp_options['opp_get_posts'];
	$qualification = $opp_options['opp_qualification'];
	$date_from = $opp_options['opp_date_from'];
	$date_until = $opp_options['opp_date_until'];
	$days_older = $opp_options['opp_days_older'];
	$days_newer = $opp_options['opp_days_newer'];
	$default_promotion = $opp_options['opp_default_promotion'];
	$twitter_consumer_key = $opp_options['opp_twitter_consumer_key'];
	$twitter_consumer_secret = $opp_options['opp_twitter_consumer_secret'];
	$twitter_oauth_token = $opp_options['opp_twitter_oauth_token'];
	$twitter_oauth_secret = $opp_options['opp_twitter_oauth_secret'];
	
	$posts_status_options = '';
	foreach(get_post_stati() as $status){
		$posts_status_options .= '<option value="'. $status .'" '. selected($post_status, $status, false) .'>' . $status .'</option>' . "\n";
	}
	
	?><div class="wrap">
		<h1>
			<img src="<?php echo plugins_url('../assets/img/icon.jpg', __FILE__ ); ?>" alt="WP Repost" style="display: inline; vertical-align: middle;" />
			<?php _e('WP Repost', 'ows'); ?>
		</h1>

    	<?php opp_options_updated_notice() ?>

        <h2 title="<?php _e('WP Repost', 'ows'); ?>" class="nav-tab-wrapper supt-nav-tab-wrapper">
            <a href="<?php echo admin_url('admin.php?page=wprepost&tab=general'); ?>" class="nav-tab <?php echo ($_GET['tab'] == '' || $_GET['tab'] == 'general') ? 'nav-tab-active' : ''; ?>">General</a>
            <a href="<?php echo admin_url('admin.php?page=wprepost&tab=integration'); ?>" class="nav-tab <?php echo ($_GET['tab'] == 'integration') ? 'nav-tab-active' : ''; ?>">Integation</a>
        </h2><?php
        
        if( $_GET['tab'] == '' || $_GET['tab'] == 'general' ){
			?><form id="opp" class="wprepost" name="oldpostpromoter" action="<?php echo get_permalink(); ?>" method="post">
				<input type="hidden" name="opp_action" value="general" />
				
                <fieldset class="options">
                	<p><label for="opp_global_settings">
                        <input type="checkbox" name="opp_global_settings" value="1" id="opp_global_settings" <?php checked(1, $global_settings); ?> />
                        <?php _e('Enable Global Settings ', 'ows'); ?>
                    </label></p>
                        
                    <p><label for="opp_single_reposting">
                        <input type="checkbox" name="opp_single_reposting" id="opp_single_reposting" value="1" <?php checked(1, $single_reposting); ?> />
                        <?php _e('Enable Single Re-Posting ', 'ows'); ?>
                    </label></p>

                    <p><label for="opp_show_meta">
                    	<input type="checkbox" name="opp_show_meta" id="opp_show_meta" value="1" <?php checked(1, $show_meta); ?> />
                    	<?php _e('Show repost meta details', 'ows'); ?>
                    </label></p>
                </fieldset>
                
                <fieldset id="opp_global_fields" class="options" <?php echo (!$global_settings) ? 'style="display:none;"' : '' ?>>
					<div class="option">
						<label for="opp_promotion_date">
						<?php _e('Set a date when to promote old posts: ', 'ows'); ?>
						</label>
						<input type="text" class="regular-text datepicker" name="opp_promotion_date" id="opp_promotion_date" value="<?php echo $promotion_date; ?>" autocomplete="off" />
					</div>
					
					<div class="option">
						<label for="opp_interval"><?php _e('Minimum Interval Between Old Post Promotions: ', 'ows'); ?></label>
						<select name="opp_interval" id="opp_interval"><?php
							foreach( array( OPP_15_MINUTES,
							OPP_30_MINUTES,
							OPP_1_HOUR,
							OPP_4_HOURS,
							OPP_6_HOURS,
							OPP_12_HOURS,
							OPP_24_HOURS,
							OPP_48_HOURS,
							OPP_72_HOURS,
							OPP_168_HOURS) as $int ){
								?><option value="<?php echo $int; ?>" <?php selected($int, $interval); ?>><?php
									$minutes = $int / 60;
									if( $minutes >= 60 ){
										$hours = $minutes / 60;
										_e($hours . ' Hours', 'ows');
									} else {
										_e($minutes . ' Minutes', 'ows');
									}
								?></option><?php
							}
						?></select>
					</div>
					
					<div class="option">
						<label for="opp_interval_slop">
							<?php _e('Randomness Interval (added to minimum interval): ', 'ows'); ?>
						</label>
						<select name="opp_interval_slop" id="opp_interval_slop"><?php
							foreach( array( OPP_1_HOUR,
							OPP_4_HOURS,
							OPP_6_HOURS,
							OPP_12_HOURS,
							OPP_24_HOURS) as $intSlop){
								?><option value="<?php echo $intSlop; ?>" <?php selected($intSlop, $slop); ?>><?php
									$hours = $intSlop/60/60;
									_e('Upto '. $hours .' Hour', 'ows');
								?></option><?php
							}
						?>
						</select>
					</div>
					
					<div class="option">
						<label for="opp_age_limit"><?php _e('Post Age Before Eligible for Promotion: ', 'ows'); ?></label>
						<select name="opp_age_limit" id="opp_age_limit">
							<option value="30" <?php selected(30,$ageLimit); ?>><?php _e('30 Days', 'ows'); ?></option>
							<option value="60" <?php selected(60,$ageLimit); ?>><?php _e('60 Days', 'ows'); ?></option>
							<option value="90" <?php selected(90,$ageLimit); ?>><?php _e('90 Days', 'ows'); ?></option>
							<option value="120" <?php selected(120,$ageLimit); ?>><?php _e('120 Days', 'ows'); ?></option>
							<option value="240" <?php selected(240,$ageLimit); ?>><?php _e('240 Days', 'ows'); ?></option>
							<option value="365" <?php selected(365,$ageLimit); ?>><?php _e('365 Days', 'ows'); ?></option>
							<option value="730" <?php selected(730,$ageLimit); ?>><?php _e('730 Days', 'ows'); ?></option>
						</select>
					</div>
					
					<div class="option">
						<label for="opp_pos">
							<?php _e('Promote post to position (choosing the 2nd position will leave the most recent post in place): ', 'ows'); ?>
						</label>
						<select name="opp_pos" id="opp_pos">
							<option value="1" <?php selected(1,$opp_pos); ?>><?php _e('1st Position', 'ows'); ?></option>
							<option value="2" <?php selected(2,$opp_pos); ?>><?php _e('2nd Position', 'ows'); ?></option>
						</select>
					</div>
					
					<div class="option">
						<label for="opp_post_status">
							<?php _e('Set a status for promoted posts', 'ows'); ?>
						</label>
						<select name="opp_post_status" id="opp_post_status">
							<option value="0" <?php selected(0, $post_status); ?>><?php _e('Original post status', 'ows'); ?></option>
							<?php echo $posts_status_options ?>
						</select>
					</div>
					
					<div class="option">
						<label for="opp_show_original_pubdate">
							<?php _e('Show Original Publication Date at Post End? ', 'ows'); ?>
						</label>
						<select name="opp_show_original_pubdate" id="opp_show_original_pubdate">
							<option value="1" <?php selected(1,$showPub); ?>><?php _e('Yes', 'ows'); ?></option>
							<option value="0" <?php selected(0,$showPub); ?>><?php _e('No', 'ows'); ?></option>
						</select>
					</div>
					
					<div class="option">
						<label for="opp_at_top"><?php _e('Show Original Publication Date At Top of Post? ', 'ows'); ?></label>
						<select name="opp_at_top" id="opp_at_top">
							<option value="1" <?php selected(1,$atTop); ?>><?php _e('Yes', 'ows'); ?></option>
							<option value="0" <?php selected(0,$atTop); ?>><?php _e('No', 'ows'); ?></option>
						</select>
					</div>
					
					<div class="option">
						<label for="opp_get_posts"><?php __('Get Posts', 'ows') ?></label>
						<select name="opp_get_posts" id="opp_get_posts">
							<option value="random" <?php selected('random', $get_posts); ?>><?php _e('Randomly', 'ows'); ?></option>
							<option value="one-by-one-new-to-old" <?php selected('one-by-one-new-to-old', $get_posts); ?>><?php _e('One-by-One New-to-Old', 'ows'); ?></option>
							<option value="one-by-one-old-to-new" <?php selected('one-by-one-old-to-new', $get_posts); ?>><?php _e('One-by-One Old-to-New', 'ows'); ?></option>
						</select>
						
						<div class="sub-option">
							<label for="opp_qualification_1">
								<input type="radio" <?php checked('date', $qualification); ?> name="opp_qualification" id="opp_qualification_1" value="date" />
								<?php _e('From', 'ows'); ?>
							</label>
							<input type="text" name="opp_date_from" id="opp_date_from" value="<?php echo $date_from; ?>" class="datepicker" />
							<label><?php _e('to', 'ows'); ?>
							<input type="text" name="opp_date_until" id="opp_date_until" value="<?php echo $date_until; ?>" class="datepicker" />
							</label>
						</div>
						
						<div class="sub-option">
							<label for="opp_qualification_2">
								<input type="radio" <?php checked('age', $qualification); ?> name="opp_qualification" id="opp_qualification_2" value="age" />
								<?php _e('Older than', 'ows'); ?>
							</label>
							<input type="text" name="opp_days_older" id="opp_days_older" value="<?php echo $days_older; ?>" class="small" />
							<label><?php _e('Days and Newer than', 'ows'); ?>
							<input type="text" name="opp_days_newer" id="opp_days_newer" value="<?php echo $days_newer; ?>" class="small" /></label>
							<label><?php _e('Days', 'ows'); ?></label>
						</div>
					</div>
					
					<div class="option">
						<label for="opp_default_promotion"><?php _e('New posts will be set by default to:', 'ows'); ?></label>
						<select name="opp_default_promotion" id="opp_default_promotion">
							<option value="enable" <?php selected('enable', $default_promotion); ?>><?php _e('Enabled for Repost', 'ows'); ?></option>
							<option value="disable" <?php selected('disable', $default_promotion); ?>><?php _e('Disabled for Repost', 'ows'); ?></option>
						</select>
						
						<div class="sub-option">
							<a href="#">[Set All Existing Posts to "Enabled for Repost"]</a>
							<a href="#">[Set All Existing Posts to "Disabled for Repost"]</a>
						</div>
					</div>
					
					<div class="option">
						<h3><?php _e('Categories to Omit from Promotion: ', 'ows'); ?></h3></li>
						<div id="categories-all" class="ui-tabs-panel"> 
							<ul id="categorychecklist" class="list:category categorychecklist form-no-clear"><?php
								wp_category_checklist(0, 0, explode(',',$omitCats));
							?></ul>
						</div>
					</div>
				</fieldset>
                
                <p class="submit">
                    <input class="button-primary" type="submit" name="submit" value="<?php _e('Update Options', 'ows'); ?>" />
                </p>
            </form><?php
		}
		
		if( $_GET['tab'] == 'integration' ){
			?><form id="opp" class="wprepost" name="oldpostpromoter" action="<?php echo get_permalink(); ?>" method="post">
				<input type="hidden" name="opp_action" value="integration" />
                <input type="hidden" name="redirect" value="<?php echo admin_url('admin.php?page=wprepost&tab=integration'); ?>" />
                
                <h3><?php _e('Connect to Twitter: ', 'ows'); ?></h3>
				
				<fieldset class="options">
					<div class="option">
						<label for="opp_twitter_consumer_key"><?php _e('Twitter Consumer Key', 'ows'); ?>:</label>
						<input type="text" class="regular-text" size="100" name="opp_twitter_consumer_key" id="opp_twitter_consumer_key" value="<?php echo $twitter_consumer_key; ?>" autocomplete="off" />
					</div>
					
					<div class="option">
						<label for="opp_twitter_consumer_secret"><?php _e('Consumer Secret', 'ows'); ?>:</label>
						<input type="text" class="regular-text" size="100" name="opp_twitter_consumer_secret" id="opp_twitter_consumer_secret" value="<?php echo $twitter_consumer_secret; ?>" autocomplete="off" />
					</div>
					
					<div class="option">
						<label for="opp_twitter_oauth_token"><?php _e('Oauth Token', 'ows'); ?>:</label>
						<input type="text" class="regular-text" size="100" name="opp_twitter_oauth_token" id="opp_twitter_oauth_token" value="<?php echo $twitter_oauth_token; ?>" autocomplete="off" />
					</div>
					
					<div class="option">
						<label for="opp_twitter_oauth_secret"><?php _e('Oauth Secret', 'ows'); ?>:</label>
						<input type="text" class="regular-text" size="100" name="opp_twitter_oauth_secret" id="opp_twitter_oauth_secret" value="<?php echo $twitter_oauth_secret; ?>" autocomplete="off" />
					</div>
					
					<div class="option">
						<label for="opp_give_credit"><?php _e('Give OPP Credit with Link? ', 'ows'); ?></label>
						<select name="opp_give_credit" id="opp_give_credit">
							<option value="1" <?php selected(1,$opp_give_credit); ?>><?php _e('Yes', 'ows'); ?></option>
							<option value="0" <?php selected(0,$opp_give_credit); ?>><?php _e('No', 'ows'); ?></option>
						</select>
					</div>
				</fieldset>
				
				<p class="submit">
                    <input class="button-primary" type="submit" name="submit" value="<?php _e('Update Options', 'ows'); ?>" />
                </p>
			</form><?php
		}
		
    ?></div><?php
}

function opp_admin_help_tab_main(){
	$screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'opp_help_tab',
        'title'	=> __('Help'),
		'callback' => 'opp_help_tab_callback'
    ) );
}

function opp_help_tab_callback( $screen, $tab ){
	printf(
		'<h3>' . __('Connecting to Twitter (Tweet on Old Post Promotion)', 'ows') . '</h3>
		<p>' . __('In order to get started, we need to follow some steps to get this site registered with Twitter. This process is awkward and more complicated than it should be. We hope to have a better solution for this in a future release, but for now this system is what Twitter supports.  You can reuse settings from other twitter plugins if the install instructions are similar.', 'related-tweets').'</p> 
		<h4>'.__('1. Register this site as an application on ', 'related-tweets') . '<a href="http://dev.twitter.com/apps/new" title="'.__('Twitter App Registration','related-tweets').'" target="_blank">'.__('Twitter\'s app registration page','related-tweets').'</a></h4>
		<div id="aktt_sub_instructions">
			<ul>
				<li>' . __('If you\'re not logged in, you can use your Twitter username and password' , 'ows').'</li>
				<li>' . __('Your Application\'s Name will be what shows up after "via" in your twitter stream' , 'ows') . '</li>
				<li>' . __('Application Type should be set on ' , 'ows') . '<strong>' . __('Browser' , 'ows') . '</strong></li>
				<li>' . __('The Callback URL should be ' , 'ows') . '<strong>' .  get_bloginfo( 'url' ) . '</strong></li>
				<li>' . __('Default Access type should be set to ' , 'ow') . '<strong>' . __('Read &amp; Write' , 'ows').'</strong> ' . __('(this is NOT the default)' , 'ows') . '</li>
			</ul>
		<p>' . __('Once you have registered your site as an application, you will be provided with a consumer key and a comsumer secret.' , 'ows') . '</p>
		</div>
		<h4>' . __('2. Copy and paste your consumer key and consumer secret into the fields below' , 'ows') . '</h4>				
		<h4>3. Copy and paste your Access Token and Access Token Secret into the fields below</h4>
		<p>On the right hand side of your application page, click on \'My Access Token\'.</p>'
	);
}