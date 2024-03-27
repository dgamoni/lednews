<?php
/*
 * Plugin Name: WP Repost
 * Plugin URI: http://www.onewebsite.ca
 * Description: A sythesis of Old Post Promoter and Revive Old Post (Former Tweet Old Post) plugin.
 * Version: 0.1
 * Author: OneWebsite
 * Author URI: http://www.onewebsite.ca
 * License: GNU GPL
 *
 
 *  Copyright 2014 OneWebsite

    This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require_once( 'inc/twitterOAuth.php' );
require_once( 'inc/opp_core.php' );

require_once( 'admin/admin.php' );
require_once( 'admin/opp_admin.php' );
require_once( 'admin/top_admin.php' );

if( !function_exists('xmlrpc_encode_entitites') && !class_exists('xmlrpcresp') && !class_exists('xmlrpcmsg') ){
	require_once('lib/xmlrpc.inc');
}

define ('OPP_PLUGINPATH', realpath(dirname(__FILE__)));
define ('OPP_PLUGINBASENAME', plugin_basename(__FILE__));
define ('OPP_FB_API_VERSION', 'v2.0');

define ('OPP_RT_API_POST_STATUS', 'http://twitter.com/statuses/update.json');
define ('OPP_XMLRPC_URI', 'bteservice.com');
define ('OPP_XMLRPC', 'bte.php');

define ('OPP_SETTINGS_PAGE', admin_url('admin.php?page=wprepost'));
define ('OPP_TOP_ADMIN_PAGE', admin_url('admin.php?page=tweetoldpost'));

define ('OPP_1_MINUTE', 60); 
define ('OPP_15_MINUTES', 15*OPP_1_MINUTE); 
define ('OPP_30_MINUTES', 30*OPP_1_MINUTE); 
define ('OPP_1_HOUR', 60*OPP_1_MINUTE); 
define ('OPP_4_HOURS', 4*OPP_1_HOUR); 
define ('OPP_6_HOURS', 6*OPP_1_HOUR); 
define ('OPP_12_HOURS', 12*OPP_1_HOUR); 
define ('OPP_24_HOURS', 24*OPP_1_HOUR); 
define ('OPP_48_HOURS', 48*OPP_1_HOUR); 
define ('OPP_72_HOURS', 72*OPP_1_HOUR); 
define ('OPP_168_HOURS', 168*OPP_1_HOUR); 
define ('OPP_PROMOTION_DATE', 0);
define ('OPP_INTERVAL', OPP_12_HOURS); 
define ('OPP_INTERVAL_SLOP', OPP_4_HOURS); 
define ('OPP_AGE_LIMIT', 120); // 120 days
define ('OPP_OMIT_CATS', "");

global $default_options, $opp_options;

$default_options = array(
	'opp_global_settings' => false,
	'opp_single_reposting' => false,
	'opp_promotion_date' => OPP_PROMOTION_DATE,
	'opp_interval' => OPP_INTERVAL,
	'opp_interval_slop' => OPP_INTERVAL_SLOP,
	'opp_age_limit' => OPP_AGE_LIMIT,
	'opp_omit_cats' => OPP_OMIT_CATS,
	'opp_post_status' => 0,
	'opp_show_original_pubdate' => 1,
	'opp_pos' => 0,
	'opp_give_credit' => 1,
	'opp_at_top' => 0,
	
	'opp_get_posts' => 'random',
	'opp_qualification' => 'date',
	'opp_date_from' => '',
	'opp_date_until' => '',
	'opp_days_older' => '',
	'opp_days_newer' => '',
	'opp_default_promotion' => 'enable',

	'opp_show_meta' => false,
	
	'top_tweet_type' => 'title',
	'top_tweet_custom_field' => '',
	'top_add_text' => '',
	'top_add_text_at' => 'end',
	'top_include_link' => 'true',
	'top_custom_url_option' => 0,
	'top_custom_url_field' => '',
	'top_use_url_shortner' => 0,
	'top_url_shortner' => '',
	'top_bitly_key' => '',
	'top_bitly_user' => '',
	'top_custom_hashtag_option' => 'tags',
	'top_hashtags' => '',
	'top_custom_hashtag_field' => '',
	'top_hashtag_length' => 0,
	'top_interval' => 4,
	'top_age_limit' => 30,
	'top_max_age_limit' => 0,
	'top_no_of_tweet' => 1,
	'top_post_with_image' => 0,
	'top_tweet_multiple_times' => 0,
	'top_ga_tracking' => 0,
	'top_omit_cats' => '',
);

register_activation_hook( __FILE__, 'opp_activate' );
register_deactivation_hook(__FILE__, 'opp_deactivate');

add_action( 'init', 'opp_old_post_promoter' );
add_action( 'admin_init', 'opp_login_social_users' );
add_action( 'admin_menu', 'opp_options_setup' );

add_action( 'old_post_promoted', 'opp_set_promoted_post_metadata' );
add_action( 'old_post_promoted', 'opp_set_promoted_post_status' );

add_action( 'admin_head-post.php', 'opp_head_admin');
add_action( 'admin_head-post-new.php', 'opp_head_admin');

add_action( 'post_submitbox_misc_actions', 'opp_promotion_button' );
add_action( 'add_meta_boxes', 'opp_add_meta_boxes' );
add_action( 'save_post', 'opp_save_metabox_data' );

add_action( 'manage_posts_custom_column' , 'opp_repost_column_content', 10, 2 );
add_action( 'pre_get_posts', 'opp_sort_repost_column' );
add_filter( 'manage_edit-post_sortable_columns', 'opp_register_sortable_columns' );
add_filter( 'manage_posts_columns' , 'opp_add_repost_column' );
add_filter( 'post_updated_messages', 'opp_updated_messages' );

add_filter( 'the_content', 'opp_the_content' );
add_filter( 'plugin_action_links', 'opp_plugin_action_links', 10, 2 );

add_action( 'wp_ajax_display_pages', 'opp_facebook_display_pages' );
add_action( 'opp_deactivate_hook', 'stop_tweet_plugin' );
add_filter( 'cron_schedules', 'opp_cron_schedules' );
add_action( 'top_tweet_cron', 'opp_tweet_old_post_func' );

function opp_activate(){
	global $default_options;
	
	foreach( $default_options as $option=>$value )
	{
		add_option( $option, $value );
	}
	
	do_action('opp_activate_hook');
}

function opp_deactivate(){
	global $default_options;
	
	foreach( $default_options as $option=>$value )
	{
		delete_option( $option );
	}
	do_action('opp_deactivate_hook');
}

function opp_plugin_action_links($links, $file){
	$plugin_file = basename(__FILE__);
	if (basename($file) == $plugin_file) {
		$settings_link = '<a href="'. OPP_SETTINGS_PAGE .'">'.__('Settings', 'RelatedTweets').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}