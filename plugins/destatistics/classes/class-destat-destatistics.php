<?php

/*
 * class Destatistics initialization plugin
 */

class Destatistics {

	public function init() {
		$destat_query = new DESTAT_Queries();

		//Cron jobs register
		$stat      = new DESTAT_Stats();
		$cron      = new DESTAT_Cron();
		$view_page = new DESTAT_View_Page();

		// Current day update posts stats
		add_action( 'DESTAT_Cron_Current_Day_Posts', array( &$stat, 'oneDayUpdateSocialStatsPosts' ) );

		add_action( 'DESTAT_Cron_New_Posts', array( &$stat, 'newUpdateSocialStatsPosts' ) );
		add_action( 'DESTAT_Cron_Old_Posts', array( &$stat, 'oldUpdateSocialStatsPosts' ) );
		add_action( 'DESTAT_Cron_All_Old_Posts', array( &$stat, 'allOldUpdateSocialStatsPosts' ) );

		add_action( 'DESTAT_One_Day_Update_Posts', array( &$stat, 'singleUpdateStatsPost' ), 10, 2 );
		add_action( 'DESTAT_New_Update_Posts', array( &$stat, 'singleUpdateStatsPost' ), 10, 2 );
		add_action( 'DESTAT_Old_Update_Posts', array( &$stat, 'singleUpdateStatsPost' ), 10, 2 );
		add_action( 'DESTAT_All_Old_Update_Posts', array( &$stat, 'singleUpdateStatsPost' ), 10, 2 );
		if ( ! is_admin() ) {
			add_action( 'wp_head', array( &$view_page, 'deco_post_views' ) );
			add_action( 'init', array( &$view_page, 'sessionStart' ) );
		}

		/*
http://www.stb.ua/?destatistics_cron_current_day_posts=1 - для постов текущего дня
http://www.stb.ua/?destatistics_cron_new_posts=1 - для новых постов (указывается в настройках,по умолчанию за 5 дней)
http://www.stb.ua/?destatistics_cron_old_posts=1 - для старых постов (указывается в настройках, по умолчанию за 30 дней исключая дни для новых
http://www.stb.ua/?destatistics_cron_all_old_posts=1 - для всех постов не вошедших в новые и старые посты
		 */


		// Делано для серверного крона, не используя псевдокрон WordPress
		if ( isset( $_GET['destatistics_cron_current_day_posts'] ) ) {
			do_action( 'DESTAT_Cron_Current_Day_Posts' );
			die( 'END run cron job' );
		} else if ( isset( $_GET['destatistics_cron_new_posts'] ) ) {
			do_action( 'DESTAT_Cron_New_Posts' );
			die( 'END run cron job' );
		} else if ( isset( $_GET['destatistics_cron_old_posts'] ) ) {
			do_action( 'DESTAT_Cron_Old_Posts' );
			die( 'END run cron job' );
		} else if ( isset( $_GET['destatistics_cron_all_old_posts'] ) ) {
			do_action( 'DESTAT_Cron_All_Old_Posts' );
			die( 'END run cron job' );
		}

		add_filter( 'cron_schedules', array( &$cron, 'addNewCronSchedule' ) );


		$settings = new DESTAT_Settings();

		define ( 'DESTAT_DOMAIN_FOR_TEST_REPLACE_FROM', get_option( "DESTAT_test_mode_replace_domain_from" ) );

		define ( 'DESTAT_DOMAIN_FOR_TEST_REPLACE_TO', get_option( "DESTAT_test_mode_replace_domain_to" ) );

		define ( 'DESTAT_DOMAIN_FOR_TEST', get_option( "DESTAT_test_mode_on" ) ? true : false );

		add_action( 'plugins_loaded', array( &$this, 'loadTextdomain' ) );
		add_action( 'wp_before_admin_bar_render', array( &$this, 'adminBarAddRatingLink' ) );

		add_action( 'admin_menu', array( &$this, 'menuInit' ) );
		add_action( 'network_admin_menu', array( &$this, 'menuInit' ) );


		if ( isset( $_GET['page'] ) ) {

			if ( is_admin() && preg_match( '/destatistics/', $_GET['page'], $match ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'styles_print' ) );
				add_action( 'admin_enqueue_scripts', array( &$this, 'scripts_print' ) );
				//				add_action( 'wp', array( $this, 'scripts_print' ) );
			}
		}

		$ajax  = new DESTAT_Ajax();
		$stats = new DESTAT_Stats();
		add_action( 'publish_post', array( &$stats, 'addPostToStatsOnSave' ), 10, 2 );

		require_once DESTAT_PLUGIN_PATH . '/components/update-records/destatistics-records-updater.php';

		$WebPres_deco__Importer = new WebPres_deco__Importer();

	}

	public function menuInit() {

		if ( is_multisite() ) {
			if ( is_network_admin() ) {
				add_menu_page( __( 'de:statistics', DESTAT_TEXTDOMAIN ), __( 'de:statistics', DESTAT_TEXTDOMAIN ), 'moderate_comments', DESTAT_SLUG, array(
					&$this,
					'pageMenuDestatisticsSettings'
				), 'dashicons-chart-line', 3.2 );

			} else {
				add_menu_page( __( 'de:statistics', DESTAT_TEXTDOMAIN ), __( 'de:statistics', DESTAT_TEXTDOMAIN ), 'moderate_comments', DESTAT_SLUG, array(
					&$this,
					'pageMenuDestatistics'
				), 'dashicons-chart-line', 3.2 );

				add_submenu_page( DESTAT_SLUG, __( 'Settings', DESTAT_TEXTDOMAIN ), __( 'Settings', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_settings', array(
					&$this,
					'pageMenuDestatisticsSettings'
				) );

			}

			//				add_submenu_page( DESTAT_SLUG, __( 'Global update data', DESTAT_TEXTDOMAIN ), __( 'Global update data', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_global_update_data', 'destatistics_global_update_data' );

			add_submenu_page( DESTAT_SLUG, __( 'Debug tools', DESTAT_TEXTDOMAIN ), __( 'Debug tools', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_debug_tools', 'destatistics_debug_tools' );

		} else {

			add_menu_page( __( 'de:statistics', DESTAT_TEXTDOMAIN ), __( 'de:statistics', DESTAT_TEXTDOMAIN ), 'moderate_comments', DESTAT_SLUG, array(
				&$this,
				'pageMenuDestatistics'
			), 'dashicons-chart-line', 3.2 );


			/*				add_submenu_page( DESTAT_SLUG, __( 'FAQ', DESTAT_TEXTDOMAIN ), __( 'FAQ', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_faq', 'destatistics_options_display' );*/

			add_submenu_page( DESTAT_SLUG, __( 'Settings', DESTAT_TEXTDOMAIN ), __( 'Settings', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_settings', array(
				&$this,
				'pageMenuDestatisticsSettings'
			) );

			add_submenu_page( DESTAT_SLUG, __( 'Debug tools', DESTAT_TEXTDOMAIN ), __( 'Debug tools', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_debug_tools', array(
				$this,
				'pageMenuDestatisticsDebugTools'
			) );
			add_submenu_page( DESTAT_SLUG, __( 'Update records', DESTAT_TEXTDOMAIN ), __( 'Update records', DESTAT_TEXTDOMAIN ), 'manage_options', DESTAT_SLUG . '_update_records', array(
				$this,
				'pageMenuDestatisticsUpdateRecords'
			) );

		}

	}

	public function pageMenuDestatistics() {

		include DESTAT_PLUGIN_PATH . '/templates_pages/destatistics-page.php';
	}

	public function pageMenuDestatisticsSettings() {

		include DESTAT_PLUGIN_PATH . '/templates_pages/settings-page.php';
	}

	public function pageMenuDestatisticsFaq() {

		include DESTAT_PLUGIN_PATH . '/templates_pages/faq-page.php';
	}

	public function pageMenuDestatisticsDebugTools() {

		include DESTAT_PLUGIN_PATH . '/templates_pages/debug-tools-page.php';
	}

	public function pageMenuDestatisticsUpdateRecords() {

		$WebPres_deco__Importer = new WebPres_deco__Importer();
		$WebPres_deco__Importer->render_admin_action();

	}

	public function widgetsInit() {

		include( DESTAT_PLUGIN_PATH . '/widgets/widget-toprecommended.php' );
		include( DESTAT_PLUGIN_PATH . '/widgets/widget-toppopular.php' );
		include( DESTAT_PLUGIN_PATH . '/widgets/widget-toptabs.php' );

	}

	public function loadTextdomain() {

		load_plugin_textdomain( DESTAT_LANG_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) );
	}

	public function adminBarAddRatingLink() {

		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'parent' => false,
			'id'     => 'destatistics',
			'title'  => __( 'de:statistics', DESTAT_TEXTDOMAIN ),
			'href'   => admin_url( 'admin.php?page=destatistics' ),
			'meta'   => false
		) );

	}

	public function activatePlugin() {

		//initial plugin options added
		$defaults = new DESTAT_Settings();
		$defaults->defaulsOptionsSetWhenActivated();

		if ( @is_file( ABSPATH . '/wp-admin/includes/upgrade.php' ) ) {
			include_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		} elseif ( @is_file( ABSPATH . '/wp-admin/upgrade-functions.php' ) ) {
			include_once( ABSPATH . '/wp-admin/upgrade-functions.php' );
		} elseif ( @is_file( ABSPATH . '/wp-admin/includes/upgrade.php' ) ) {
			include_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		} else {
			die( __( 'We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'', DESTAT_TEXTDOMAIN ) );
		}

		global $wpdb;

		// Create de:statistics table
		$charset_collate = wpdb::get_charset_collate();

		$create_table = " CREATE TABLE IF NOT EXISTS $wpdb->de_statistics (
			id            int(10) NOT NULL auto_increment,
			blog_id       int(10) NOT NULL,
			post_id       int(10) NOT NULL,
			lang          varchar(80) NOT NULL DEFAULT '',
			url           varchar(8000) NOT NULL,
			post_type     varchar(80) NOT NULL DEFAULT '',
			fb_counts     int(10) NOT NULL,
			tw_counts     int(10) NOT NULL,
			vk_counts     int(10) NOT NULL,
			ln_counts     int(10) NOT NULL,
			gplus_counts  int(10) NOT NULL,
			ok_counts     int(10) NOT NULL,
			pocket_counts int(10) NOT NULL,
			views_counts  int(10) NOT NULL,
			comments_counts  int(10) NOT NULL,
			votes_sum     int(10) NOT NULL,
			updated_time  datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            created_time  datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            flag_update   int(1) NOT NULL,
            text	      text NOT NULL DEFAULT '',
			PRIMARY KEY   (id),
			KEY unique_post_id (blog_id, post_id, id),
			KEY post_type (post_type),
			KEY updated_time (updated_time),
			KEY created_time (created_time)
			) $charset_collate; ";

		maybe_create_table( $wpdb->de_statistics, $create_table );

		//initial plugin options added
		$defaults = new DESTAT_Settings();
		$defaults->defaulsOptionsSetWhenActivated();

	}

	public function deactivatePlugin() {

		$cron = new DESTAT_Cron();
		$cron->deactivateScheduled();
	}

	public function styles_print() {

		global $wp_scripts;
		wp_enqueue_style( "jquery-ui-css", "//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" );
	}

	public function scripts_print() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_register_script( 'destatistics', DESTAT_ASSETS_URL . '/js/destatistics.js', array( 'jquery' ), '1.0' );
		wp_enqueue_script( 'destatistics' );
	}

}
