<?php

/**
 *
 * @link       http://www.69signals.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 *
 *
 * Plugin Name: 		Minimal Coming Soon & Maintenance Mode
 * Plugin URI: 			http://www.69signals.com/minimal-coming-soon-maintenance-mode-plugin.php
 * Description: 		Simply awesome coming soon & maintenance mode plugin for your WordPress blog. Try it to know why there is no other plugin like this one.
 * Version: 			1.0
 * Author: 				akshitsethi
 * Author URI: 			http://www.69signals.com
 * License: 			GPLv3
 * License URI: 		http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: 		signals
 * Domain Path: 		/framework/langs/
 *
 *
 * Minimal Coming Soon & Maintenance Mode Plugin
 * Copyright (C) 2015, akshitsethi - support@69signals.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Defining constants and activation hook.
 * If this file is called directly, abort.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}



/* Constants we will be using throughout the plugin. */
define( 'SIGNALS_CSMM_URL', plugins_url( '', __FILE__ ) );
define( 'SIGNALS_CSMM_PATH', plugin_dir_path( __FILE__ ) );



/**
 * For the plugin activation & de-activation.
 * We are doing nothing over here.
 */
function csmm_plugin_activation() {

	// Checking if the options exist in the database
	$signals_csmm_options = get_option( 'signals_csmm_options' );

	// Default options for the plugin on activation
	$default_options = array(
		'status'				=> '2',
		'title' 				=> 'Maintenance Mode',
		'header_text' 			=> 'Maintenance Mode',
		'secondary_text' 		=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla pharetra eu felis quis lobortis. Proin vitae rutrum nisl, ut ullamcorper quam. Praesent faucibus ligula ac nisl varius dictum. Maecenas iaculis posuere orci, sed consectetur augue.',
		'antispam_text' 		=> 'And yes, we hate spam too!',
		'custom_login_url' 		=> '',
		'show_logged_in' 		=> '2',
		'exclude_se'			=> '1',
		'mailchimp_api'			=> '',
		'mailchimp_list' 		=> '',
		'logo'					=> '',
		'favicon'				=> '',

		'bg_cover' 				=> '',
		'content_overlay' 		=> '2',
		'content_width'			=> '440',
		'bg_color' 				=> 'ffffff',
		'content_position'		=> 'center',
		'content_alignment'		=> 'left',

		'header_font' 			=> 'Karla',
		'secondary_font' 		=> 'Karla',
		'header_font_size' 		=> '28',
		'secondary_font_size' 	=> '14',
		'header_font_color' 	=> '090909',
		'secondary_font_color' 	=> '090909',

		'antispam_font_size' 	=> '13',
		'antispam_font_color' 	=> 'bbbbbb',

		'input_text' 			=> 'Enter your email address..',
		'button_text' 			=> 'Subscribe',

		'ignore_form_styles' 	=> '2',

		'input_font_size'		=> '13',
		'button_font_size'		=> '12',
		'input_font_color'		=> '090909',
		'button_font_color'		=> 'ffffff',

		'input_bg'				=> '',
		'button_bg'				=> '0f0f0f',
		'input_bg_hover'		=> '',
		'button_bg_hover'		=> '0a0a0a',

		'input_border'			=> 'eeeeee',
		'button_border'			=> '0f0f0f',
		'input_border_hover'	=> 'bbbbbb',
		'button_border_hover'	=> '0a0a0a',

		'disable_settings' 		=> '2',
		'custom_html'			=> '',
		'custom_css'			=> ''
	);

	// If the options are not there in the database, then create the default options for the plugin
	if ( ! $signals_csmm_options ) {
		update_option( 'signals_csmm_options', $default_options );
	} else {
		// If present in the database, merge with the default ones
		// This is to provide compatibility with earlier versions. Although this doesn't solves the purpose completely
		$default_options = array_merge( $default_options, $signals_csmm_options );
		update_option( 'signals_csmm_options', $default_options );
	}

}
register_activation_hook( __FILE__, 'csmm_plugin_activation' );



/* Hook for the plugin deactivation. */
function csmm_plugin_deactivation() {

	// Silence is golden
	// We might use this in future versions

}
register_deactivation_hook( __FILE__, 'csmm_plugin_deactivation' );



/**
 * Including files necessary for the management panel of the plugin.
 * Basically, support panel and option to insert custom .css is provided.
 */
if ( is_admin() ) {
	require SIGNALS_CSMM_PATH . 'framework/admin/init.php';
}



/**
 * Let's start the plugin now.
 * All the widgets are included and registered using the right hook.
 */
require SIGNALS_CSMM_PATH . 'framework/public/init.php';