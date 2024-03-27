<?php
/*
 *
 */

function opp_options_setup(){
	global $plugin_pages;
	$plugin_pages = array();
	
	$plugin_pages['main'] = add_menu_page(
		__('WP Repost', 'ows'),
		__('WP Repost', 'ows'),
		'manage_options',
		'wprepost',
		'opp_options',
		'dashicons-sort' // plugins_url( '../assets/img/icon.png', __FILE__ )
	);
	
	$plugin_pages['top'] = add_submenu_page(
		'wprepost',
		__('Tweet Old Post', 'ows'),
		__('Tweet Old Post', 'ows'),
		'manage_options',
		'tweetoldpost',
		'top_options'
	);
	
	foreach( $plugin_pages as $screen_id=>$plugin_page ){
		add_action( 'admin_head-' . $plugin_page, 'opp_head_admin' );
		add_action( 'load-'.$plugin_page, 'opp_admin_help_tab_' . $screen_id );
	}
}

function opp_head_admin(){
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('colorbox', plugins_url( '../views/js/jquery.colorbox.js', __FILE__ ));
	wp_enqueue_script('opp_admin_script', plugins_url( '../views/js/admin.js', __FILE__ ));
	
	wp_enqueue_style('jquery-ui-datepicker', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');
	wp_enqueue_style('colorbox', plugins_url( '../views/css/colorbox.css', __FILE__ ));
	wp_enqueue_style('opp_admin_style', plugins_url( '../views/css/admin.css', __FILE__ ));
}

function opp_add_repost_column( $columns ){
	$columns['repost'] = __( 'Repost', 'ows' );
	
	return $columns;
}

function opp_register_sortable_columns($columns){
	$columns['repost'] = __( 'repost', 'ows' );

	return $columns;
}

function opp_repost_column_content( $column, $post_id ){
	switch( $column ){
		case 'repost':
			$post = get_post( $post_id );
			$origPubDate = get_post_meta($post->ID, 'opp_original_pub_date', true);
			$repost_date = unserialize(get_post_meta( $post_id, 'wp_repost_date', true));
			$last_reposted = $reposted[ count($reposted) - 1 ];
			$nextRepost = get_single_repost_schedule( $post_id );
			
			if( !empty($repost_date) ){
				$time = sprintf(
					'<abbr title="%s">%s Last Reposted Date</abbr><br />
					<abbr title="%s">%s Last Updated</abbr><br />
					<abbr title="%s">%s Originally Published</abbr>',
					$post->post_date, date('Y/m/d', strtotime($post->post_date)),
					$post->post_modified, date('Y/m/d', strtotime($post->post_modified)),
					$origPubDate, date('Y/m/d', strtotime($origPubDate))
				);
				
				if( $nextRepost && ($nextRepost > strtotime(date('m/d/Y'))) ){
					$nextRepost = date('Y-m-d H:i:s', $nextRepost);
					$nextRepostDate = date('Y/m/d', strtotime($nextRepost));
					
					$time .= sprintf(
						'<br /><abbr title="%s">%s Next Repost Date</abbr>',
						$nextRepost, $nextRepostDate
					);
				}
			} else {
				$time = sprintf(
					'Published %s ago',
					human_time_diff( get_the_time('U', $post), current_time('timestamp') )
				);
				
				if( $nextRepost ){
					$time .= sprintf(
						'<br /><abbr title="%s">Reposting in %s</abbr>',
						date('Y-m-d H:i:s', $nextRepost),
						human_time_diff( current_time('timestamp'), $nextRepost )
					);
				}
			}

			echo $time;
			break;
	}
}

function opp_sort_repost_column( $query ){
	if( !is_admin() ) return;
	
	$orderby = $query->get( 'orderby');
	
	if( 'repost' == $orderby ) {
        $query->set('meta_key', 'wp_repost_date');
        $query->set('orderby', 'meta_value');
    }
}

function opp_promotion_button(){
	global $opp_options, $post;
    
	if( get_post_type($post) != 'post' ) return;
	if( !$opp_options['opp_single_reposting'] ) return;
	
	$post_id = $post->ID;
	$origPubDate = get_post_meta($post->ID, 'opp_original_pub_date', true);
	$reposted = unserialize(get_post_meta( $post->ID, 'wp_repost_date', $post->post_date ));
	
	if( !empty($reposted) ){
		$last_reposted = $reposted[ count($reposted) - 1 ];
		echo sprintf(
			'<div class="misc-pub-section count misc-pub-reposted alignleft"><span id="repost-count">%s <b>%s</b> times</span></div>',
			__('Reposted ', 'ows'),
			count($reposted)
		);
	}

	echo sprintf('<div class="misc-pub-section alignright"><input type="submit" name="opp_repost_now" id="opp_repost_now" value="Repost Now" class="button button-secondary alignright" /><div class="clear"></div></div>');

	if( !empty($reposted) ){
		$timestamps = '';
		foreach( $reposted as $times ){
			$timestamps .= sprintf(
				'<abbr title="%s">%s</abbr><br />',
				date('U', strtotime($times)),
				date('M j Y, @ h:i', strtotime($times))
			);
		}

		echo sprintf(
			'<div class="misc-pub-section reposted misc-pub-reposted clear"><span id="repost-timestamp">%s <b>%s</b></span> %s <div id="repost-timestampdiv" class="hide-if-js">%s</div></div>',
			__('Last Reposted on ', 'ows'),
			date('M j Y, @ h:i', strtotime($last_reposted)),
			sprintf(
				'<a href="#repost-timestampdiv" class="edit-repost-timestamp hide-if-no-js"><span aria-hidden="true">%s</span></a>',
				__('See all dates', 'ows')
			),
			$timestamps
		);
	}
}

function opp_updated_messages( $messages ){
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );
	
	$messages['post'][11] = __('Post Re-Posted', 'ows');
	
	return $messages;
}

function opp_add_meta_boxes(){
	global $opp_options;
	
	if( !$opp_options['opp_single_reposting'] ) return;
	
	add_meta_box(
		'opp_repost_setting',
		__( 'Re-Posting Settings', 'ows' ),
		'opp_repost_metabox',
		'post'
	);
}

function opp_repost_metabox( $post ){
	$repost_option = get_post_meta( $post->ID, 'opp_repost_option', true );
	$repost_after_number = get_post_meta( $post->ID, 'opp_repost_after_number', true );
	$repost_after_time = get_post_meta( $post->ID, 'opp_repost_after_time', true );
	$repost_after_post = get_post_meta( $post->ID, 'opp_repost_after_post', true );
	$repost_on_date = get_post_meta( $post->ID, 'opp_repost_on_date', true );
	if( !$repost_on_date ) $repost_on_date = date('m/d/Y');
	
	$repost_repeats = get_post_meta( $post->ID, 'opp_repost_repeats', true );
	$repost_every = get_post_meta( $post->ID, 'opp_repost_every', true );
	$repost_on = maybe_unserialize(get_post_meta( $post->ID, 'opp_repost_on', true ));
	$repost_monthly_on = maybe_unserialize(get_post_meta( $post->ID, 'opp_repost_monthly_on', true ));
	$repost_by = get_post_meta( $post->ID, 'opp_repost_by', true );
	$repost_start = get_post_meta( $post->ID, 'opp_repost_start', true );
	if( !$repost_start ) $repost_start = date('m/d/Y');
	
	$repost_end = get_post_meta( $post->ID, 'opp_repost_end', true );
	$repost_end_after = get_post_meta( $post->ID, 'opp_repost_end_after', true );
	$repost_end_on = get_post_meta( $post->ID, 'opp_repost_end_on', true );
	if( !$repost_end_on ) $repost_end_on = date('m/d/Y');

	$repost_schedule = get_post_meta( $post->ID, 'opp_repost_schedule', true );
	
	if( empty( $repost_on ) ) $repost_on = array();
	if( empty( $repost_monthly_on ) ) $repost_monthly_on = array();
	
	?><h2><?php _e('Re-Post Schedule', 'ows'); ?>: <?php
    
	if( $repost_schedule ){
		echo sprintf(
			'<span id="opp_repost_date">%s</span>',
			get_single_repost_schedule( $post->ID, 'string' )
		);
	} else {
		echo sprintf(
			'<span id="opp_repost_date">%s.</span>',
			__('not set', 'ows')
		);
	}
    
    ?></h2><?php
	
    ?><fieldset class="options">
        <div class="option">
            <label>
            	<input type="radio" name="opp_repost_option" id="opp_repost_option_1" value="days" <?php checked('days', $repost_option); ?> />
            	<?php _e('Repost After', 'ows'); ?>
                <input type="text" name="opp_repost_after_number" id="opp_repost_after_number" value="<?php echo $repost_after_number; ?>" class="small" />
                <select name="opp_repost_after_time" id="opp_repost_after_time">
                	<option value="days" <?php selected('days', $repost_after_time) ?> ><?php _e('Days', 'ows'); ?></option>
                    <option value="weeks" <?php selected('weeks', $repost_after_time) ?> ><?php _e('Weeks', 'ows'); ?></option>
                    <option value="months" <?php selected('months', $repost_after_time) ?> ><?php _e('Months', 'ows'); ?></option>
                </select>
                <input type="button" id="opp_schedule_now" value="<?php _e('from now' , 'ows'); ?>" />
            </label>
        </div>
        
        <div class="option">
            <label>
            	<input type="radio" name="opp_repost_option" id="opp_repost_option_2" value="date" <?php checked('date', $repost_option); ?> />
            	<?php _e('Repost on', 'ows'); ?>
                <input type="text" name="opp_repost_on_date" id="opp_repost_on_date" value="<?php echo $repost_on_date; ?>" class="datepicker" />
            </label>
        </div>
        
        <div class="option disabled">
            <label>
            	<input type="radio" name="opp_repost_option" id="opp_repost_option_3" value="post" <?php checked('post', $repost_option); ?> disabled="disabled" />
            	<?php _e('Repost after', 'ows'); ?>
                <input type="text" name="opp_repost_after_post" id="opp_repost_after_post" value="<?php echo $repost_after_post; ?>" class="small" disabled="disabled" />
                <?php _e('New "Original" Posts after original published date.', 'ows'); ?>
            </label>
        </div>
        
        <div class="option">
            <label>
            	<input type="radio" name="opp_repost_option" id="opp_repost_option_4" value="repeat" <?php checked('repeat', $repost_option); ?> />
            	<?php _e('Enable Repeat Reposting', 'ows'); ?>
            </label>
            
            <div class="clear"></div>
            
            <div class="sub-option" id="repeat_repost_option" <?php echo ($repost_option != 'repeat') ? 'style="display:none;"' : ''; ?>>
            	<div><label>
                	<span class="label"><?php _e('Repeats', 'ows'); ?>:</span>
                    <span class="fields"><select name="opp_repost_repeats" id="opp_repost_repeats">
                    	<option value="daily" <?php selected('daily', $repost_repeats) ?> ><?php _e('Daily', 'ows'); ?></option>
                        <option value="weekly" <?php selected('weekly', $repost_repeats) ?> ><?php _e('Weekly', 'ows'); ?></option>
                        <option value="monthly" <?php selected('monthly', $repost_repeats) ?> ><?php _e('Monthly', 'ows'); ?></option>
                        <option value="yearly" <?php selected('yearly', $repost_repeats) ?> ><?php _e('Yearly', 'ows'); ?></option>
                    </select></span>
                </label>
                <div class="clear"></div></div>
                
                <div><label>
                	<span class="label"><?php _e('Repeat every', 'ows'); ?>:</span>
                    <span class="fields"><select name="opp_repost_every" id="opp_repost_every"><?php
						for($i = 1; $i <= 30; $i++ ){
							echo sprintf('<option value="%1$s" %2$s>%1$s</option>', $i, selected($i, $repost_every, false) );
						}
                    ?></select></span>
                </label>
                <div class="clear"></div></div>
                
                <div id="opp_repeat_on_wrap" <?php echo ($repost_repeats != 'weekly') ? 'style="display:none;"' : ''; ?>><label>
                	<span class="label"><?php _e('Repeat on', 'ows'); ?>:</span>
                    <span class="fields">
                    	<label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_1" value="sunday" <?php checked(1, in_array('sunday', $repost_on)); ?> class="opp_repost_on" data-value="0" /> S </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_2" value="monday" <?php checked(1, in_array('monday', $repost_on)); ?> class="opp_repost_on"  data-value="1" /> M </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_3" value="tuesday" <?php checked(1, in_array('tuesday', $repost_on)); ?> class="opp_repost_on"  data-value="2" /> T </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_4" value="wednesday" <?php checked(1, in_array('wednesday', $repost_on)); ?> class="opp_repost_on" data-value="3" /> W </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_5" value="thursday" <?php checked(1, in_array('thursday', $repost_on)); ?> class="opp_repost_on" data-value="4" /> T </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_6" value="friday" <?php checked(1, in_array('friday', $repost_on)); ?> class="opp_repost_on" data-value="5" /> F </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_on[]" id="opp_repost_on_7" value="saturday" <?php checked(1, in_array('saturday', $repost_on)); ?> class="opp_repost_on" data-value="6" /> S </label>
                    </span>
                </label>
                <div class="clear"></div></div>
                
                <div id="opp_repeat_monthly_on_wrap" <?php echo ($repost_repeats != 'monthly') ? 'style="display:none;"' : ''; ?>><label>
                	<span class="label"><?php _e('Repeat on', 'ows'); ?>:</span>
                    <span class="fields offset">
                    	<label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_1" value="january" <?php checked(1, in_array('january', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="0" /> January </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_2" value="february" <?php checked(1, in_array('february', $repost_monthly_on)); ?> class="opp_repost_monthly_on"  data-value="1" /> Feruary </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_3" value="march" <?php checked(1, in_array('march', $repost_monthly_on)); ?> class="opp_repost_monthly_on"  data-value="2" /> March </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_4" value="april" <?php checked(1, in_array('april', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="3" /> April </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_5" value="may" <?php checked(1, in_array('may', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="4" /> May </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_6" value="june" <?php checked(1, in_array('june', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="5" /> June </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_7" value="july" <?php checked(1, in_array('july', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="6" /> July </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_8" value="august" <?php checked(1, in_array('august', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="7" /> August </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_9" value="september" <?php checked(1, in_array('september', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="8" /> September </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_10" value="october" <?php checked(1, in_array('october', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="9" /> October </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_11" value="november" <?php checked(1, in_array('november', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="10" /> November </label>
                        <label class="opp_checklabel" ><input type="checkbox" name="opp_repost_monthly_on[]" id="opp_repost_monthly_on_12" value="december" <?php checked(1, in_array('december', $repost_monthly_on)); ?> class="opp_repost_monthly_on" data-value="11" /> December </label>
                    </span>
                </label>
                <div class="clear"></div></div>

                <div id="opp_repeat_by_wrap" <?php echo ($repost_repeats != 'monthly') ? 'style="display:none;"' : ''; ?>><label>
                	<span class="label"><?php _e('Repeat by', 'ows'); ?>:</span>
                    <span class="fields">
                    	<label><input type="radio" name="opp_repost_by" id="opp_repost_by_month" value="month" <?php checked(1, in_array($repost_by, array('month', ''))); ?> /> day of the month</label>
                        <label><input type="radio" name="opp_repost_by" id="opp_repost_by_week" value="week" <?php checked('week', $repost_by); ?> /> day of the week </label>
                    </span>
                </label>
                <div class="clear"></div></div>
                
                <div><label>
                	<span class="label"><?php _e('Starts on', 'ows'); ?>:</span>
                    <span class="fields"><input type="text" name="opp_repost_start" id="opp_repost_start" value="<?php echo $repost_start; ?>" class="datepicker" /></span>
                </label>
                <div class="clear"></div></div>
                
                <div><label>
                	<span class="label"><?php _e('Ends', 'ows'); ?>:</span>
                    
                    <span class="fields">
                        <label>
                            <input type="radio" name="opp_repost_end" id="opp_repost_end" value="never" <?php checked('never', $repost_end); ?> /> <?php _e('Never', 'ows'); ?>
                        </label><br>
                        
                        <label>
                            <input type="radio" name="opp_repost_end" id="opp_repost_end_1" value="after" <?php checked('after', $repost_end); ?> /> <?php _e('After', 'ows'); ?>
                            <input type="text" name="opp_repost_end_after" id="opp_repost_end_after" value="<?php echo $repost_end_after; ?>" class="small" /> <?php _e('occurences', 'ows'); ?>
                        </label><br>
                        
                        <label>
                            <input type="radio" name="opp_repost_end" id="opp_repost_end_3" value="on" <?php checked('on', $repost_end); ?> /> <?php _e('On', 'ows'); ?>
                            <input type="text" name="opp_repost_end_on" id="opp_repost_end_on" value="<?php echo $repost_end_on; ?>" class="datepicker" />
                        </label>
                    </span>
                </label>
                <div class="clear"></div></div>
                
            </div>
        </div>
        
        <input type="hidden" name="opp_repost_schedule" id="opp_repost_schedule" value="<?php echo date('m/d/Y', strtotime($repost_schedule)); ?>" />
	</fieldset><?php
}

function opp_save_metabox_data( $post_id ){
	
	// If this is just a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) ) return;
	
	$metas = array(
		'opp_repost_option',
		'opp_repost_after_number',
		'opp_repost_after_time',
		'opp_repost_after_post',
		'opp_repost_on_date',
		'opp_repost_repeats',
		'opp_repost_every',
		'opp_repost_on',
		'opp_repost_monthly_on',
		'opp_repost_by',
		'opp_repost_start',
		'opp_repost_end',
		'opp_repost_end_after',
		'opp_repost_end_on',
		'opp_repost_schedule',
	);
	
	// wp_die( '<pre>' . print_r($_POST, true) . '</pre>' );
	
	foreach( $metas as $meta_key ){
		if( isset($_POST[$meta_key]) ){
			update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );
		}
	}
	
	return $post_id;
}