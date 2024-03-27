<?php

	class DECOM_Component_Plugin_AutoUpdate extends DECOM_Component {

		public static function onInit() {

			if ( file_exists( __DIR__ . '/includes/plugin-updater.php' ) ) {
				require_once( __DIR__ . '/includes/plugin-updater.php' );

				$config = array(
					'base'      => DECOM_PLUGIN_NAME,
					'dashboard' => true,
					'username'  => true,
					'key'       => '',
					'repo_uri'  => UPDATE_REPO_URL, //required
					'repo_slug' => UPDATE_REPO_SLUG, //required
				);

				new DESTAT_Plugin_Updater( $config );
			}
		}
	}