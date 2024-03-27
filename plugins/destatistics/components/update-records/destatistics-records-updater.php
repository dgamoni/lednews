<?php

class WebPres_deco__Importer {

	public function __construct() {
		add_action( 'wp_ajax_destatistics-records-updater-ajax', array(
			'WebPres_deco__Importer',
			'render_ajax_action'
		) );
	}

	public function render_admin_action() {
		$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'index';
		require_once( DESTAT_PLUGIN_PATH . '/components/update-records/destatistics-records-updater-common.php' );
		require_once( DESTAT_PLUGIN_PATH . "/components/update-records/destatistics-records-updater-{$action}.php" );
	}

	public function render_ajax_action() {
		require_once( DESTAT_PLUGIN_PATH . '/components/update-records/destatistics-records-updater-ajax.php' );
		die(); // this is required to return a proper result
	}
}