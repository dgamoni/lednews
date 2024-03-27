<?php

/**
 * File which gets called on plugin uninstall.
 * Since the plugin does not do any sort of setup, nothing is done over here.
 *
 * @link       http://www.69signals.com
 * @since      0.1
 * @package    Signals_Maintenance_Mode
 *
 * Checking whether the file is called by the Wordpress uninstall action or not
 * If not, then exit and prevent unauthorized access
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/* Removing the options from the database. */
delete_option( 'signals_csmm_options' );