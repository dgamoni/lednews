<?php

add_filter( 'cron_schedules', 'deco_precache_time_set_every_min', 999 );
function deco_precache_time_set_every_min( $schedules ) {
	$schedules['deco_precache_min'] = array(
		'interval' => 65,
		'display'  => __( 'Every 1 min' )
	);

	return $schedules;
}

add_action( 'deco_homepage_precache_content', 'deco_homepage_precache_content' );
add_action( 'deco_homepage_precache_content_single', 'deco_homepage_precache_content' );
add_action( 'save_post', 'deco_homepage_precache_content' );
function deco_homepage_precache_content($post) {
	deco_get_deputats_home_block( true );
	deco_get_reforms_home_block( true );
	deco_get_blog_home_block( true );
	deco_get_archive_date_month_and_year( true );
	deco_get_vid_diyalnosti_home_block( true );
}

if ( ! wp_next_scheduled( 'deco_homepage_precache_content' ) ) {
	wp_schedule_event( time(), 'deco_precache_min', 'deco_homepage_precache_content' );
}



