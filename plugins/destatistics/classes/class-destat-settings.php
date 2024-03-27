<?php

	class DESTAT_Settings {

		/*
		 * Конструктор класса настроек
		 * обработка post запросов с страницы настроек плагина
		 */
		public function __construct() {

			$functions = new DESTAT_Functions();

			if ( isset( $_POST['GaOptionsForm'] ) ) {

				if ( is_network_admin() ) {

					$functions->updateOption( "GaEnable", $functions->getRequestData( 'GaEnable' ) );

					if ( isset( $_POST["GAAuthCode"] ) && ! empty( $_POST["GAAuthCode"] ) ) {
						$functions->updateOption( "GAAuthCode", $functions->getRequestData( 'GAAuthCode' ) );

						if ( empty( $_POST["GAUserProfile"] ) ) {
							$functions->deleteOption( "GAaccess_token" );
							$functions->deleteTransient( "GAaccess_token" );
							$functions->deleteOption( "GArefresh_token" );
							$functions->deleteOption( "GAUserProfile" );
							$google_api = new DESTAT_Google();
							$google_api->GetAndSaveFirstGoogleAccesToken();
						}

					} else {

						$functions->deleteOption( "GAaccess_token" );
						$functions->deleteTransient( "GAaccess_token" );
						$functions->deleteOption( "GArefresh_token" );
						$functions->deleteOption( "GAUserProfile" );
						$functions->deleteOption( "GAAuthCode" );
					}
					$functions->updateOption( "GAUserProfile", $functions->getRequestData( 'GAUserProfile' ) );
				} else {

					update_option( "GaEnable", $functions->getRequestData( 'GaEnable' ) );

					if ( isset( $_POST["GAAuthCode"] ) && ! empty( $_POST["GAAuthCode"] ) ) {
						update_option( "GAAuthCode", $functions->getRequestData( 'GAAuthCode' ) );

						if ( empty( $_POST["GAUserProfile"] ) ) {
							delete_option( "GAaccess_token" );
							delete_transient( "GAaccess_token" );
							delete_option( "GArefresh_token" );
							delete_option( "GAUserProfile" );
							$google_api = new DESTAT_Google();
							$google_api->GetAndSaveFirstGoogleAccesToken();
						}

					} else {

						delete_option( "GAaccess_token" );
						delete_transient( "GAaccess_token" );
						delete_option( "GArefresh_token" );
						delete_option( "GAUserProfile" );
						delete_option( "GAAuthCode" );
					}
					update_option( "GAUserProfile", $functions->getRequestData( 'GAUserProfile' ) );
				}
			}


			if ( isset( $_POST['CommentsServicesForm'] ) ) {
				if ( is_network_admin() ) {
					$functions->updateOption( "DESTAT_disqus_enable", $functions->getRequestData( 'DESTAT_disqus_enable' ) );
					$functions->updateOption( "DESTAT_disqus_site_id", $functions->getRequestData( 'DESTAT_disqus_site_id' ) );

				} else {
					update_option( "DESTAT_disqus_enable", $functions->getRequestData( 'DESTAT_disqus_enable' ) );
					update_option( "DESTAT_disqus_site_id", $functions->getRequestData( 'DESTAT_disqus_site_id' ) );

				}
			}

			if ( isset( $_POST['DESTAT_test_mode_form'] ) ) {
				update_option( "DESTAT_test_mode_on", $functions->getRequestData( 'DESTAT_test_mode_on' ) );

				update_option( "DESTAT_test_mode_replace_domain_from", $functions->getRequestData( 'DESTAT_test_mode_replace_domain_from' ) );

				update_option( "DESTAT_test_mode_replace_domain_to", $functions->getRequestData( 'DESTAT_test_mode_replace_domain_to' ) );
			}


			//			if ( isset( $_POST['GaNewToken'] ) ) {
			//				delete_option( "GAaccess_token" );
			//				delete_transient( "GAaccess_token" );
			//				delete_option( "GArefresh_token" );
			//				delete_option( "GAUserProfile" );
			//				$google_api = new DESTAT_Google();
			//				$google_api->GetAndSaveFirstGoogleAccesToken();
			//			}

			if ( isset( $_POST['OtherOptionsForm'] ) ) {

				$functions->updateOption( "de_fb_enable", $functions->getRequestData( 'de_fb_enable' ) );

				$functions->updateOption( "de_fb_method", $functions->getRequestData( 'de_fb_method' ) );

				$functions->updateOption( "de_fbAppId", $functions->getRequestData( 'de_fbAppId' ) );

				$functions->updateOption( "de_fbSecretKey", $functions->getRequestData( 'de_fbSecretKey' ) );

				$functions->updateOption( "de_vk_enable", $functions->getRequestData( 'de_vk_enable' ) );

				$functions->updateOption( "de_vk_type", $functions->getRequestData( 'de_vk_type' ) );

				$functions->updateOption( "de_vk_method", $functions->getRequestData( 'de_vk_method' ) );

				$functions->updateOption( "de_vkAppId", $functions->getRequestData( 'de_vkAppId' ) );

				$functions->updateOption( "de_vkSecretKey", $functions->getRequestData( 'de_vkSecretKey' ) );

				$functions->updateOption( "de_twi_enable", $functions->getRequestData( 'de_twi_enable' ) );

				$functions->updateOption( "de_pocket_enable", $functions->getRequestData( 'de_pocket_enable' ) );

				$functions->updateOption( "de_li_enable", $functions->getRequestData( 'de_li_enable' ) );

				$functions->updateOption( "de_gplus_enable", $functions->getRequestData( 'de_gplus_enable' ) );

				$functions->updateOption( "de_ok_enable", $functions->getRequestData( 'de_ok_enable' ) );

				$functions->updateOption( "DESTAT_Post_Type", $functions->getRequestData( 'DESTAT_Post_Type' ) );

				$functions->updateOption( "DESTAT_New_Posts_Day", $functions->getRequestData( 'DESTAT_New_Posts_Day' ) );

				$functions->updateOption( "DESTAT_Old_Posts_Day", $functions->getRequestData( 'DESTAT_Old_Posts_Day' ) );
			}

			if ( isset( $_POST['DeCronJobs'] ) ) {

				$functions->updateOption( "DESTAT_Cron_Enable", $functions->getRequestData( 'DESTAT_Cron_Enable' ) );

				$cron = new DESTAT_Cron();
				if ( isset( $_POST["DESTAT_Cron_Enable"] ) ) {

					$functions->updateOption( 'DESTAT_Cron_One_Day_Posts', $functions->getRequestData( 'DESTAT_Cron_One_Day_Posts' ) );

					$functions->updateOption( "DESTAT_Cron_New_Posts", $functions->getRequestData( 'DESTAT_Cron_New_Posts' ) );

					$functions->updateOption( "DESTAT_Cron_Old_Posts", $functions->getRequestData( 'DESTAT_Cron_Old_Posts' ) );

					$functions->updateOption( "DESTAT_Cron_All_Old_Posts", $functions->getRequestData( 'DESTAT_Cron_All_Old_Posts' ) );

					$cron->scheduleCron();

				} else {
					$cron->unscheduleCron();
				}
			}

			if ( isset( $_POST['DeleteGAAccess'] ) ) {
				$functions->updateOption( "GAAuthCode", '' );
				$functions->updateOption( "GAUserProfile", '' );
			}

			if ( isset( $_POST['DeleteOptionsForm'] ) ) {
				$this->deleteOptionsAndTables();
			}

			if ( isset( $_POST['CopyTypePostsToStatsTable'] ) ) {
				$this->copyTypePostsToStatTable();
			}

		}

		//*********************************************************************************
		function deleteOptionsAndTables() {

			$functions = new DESTAT_Functions();

			$functions->deleteOption( "GaEnable" );
			$functions->deleteOption( "GAClientID" );
			$functions->deleteOption( "GAClientSecret" );
			$functions->deleteOption( "GARedirectUri" );
			$functions->deleteOption( "GAAuthorizationCode" );
			$functions->deleteOption( "GAaccess_token" );
			$functions->deleteOption( "GAcurrent_site_acc" );

			$functions->deleteOption( "de_fb_enable" );
			$functions->deleteOption( "de_fb_method" );
			$functions->deleteOption( "de_fbAppId" );
			$functions->deleteOption( "de_fbSecretKey" );

			$functions->deleteOption( "de_vk_enable" );
			$functions->deleteOption( "de_vk_type" );
			$functions->deleteOption( "de_vk_method" );
			$functions->deleteOption( "de_vkAppId" );
			$functions->deleteOption( "de_vkSecretKey" );

			$functions->deleteOption( "de_twi_enable" );
			$functions->deleteOption( "de_li_enable" );
			$functions->deleteOption( "de_gplus_enable" );
			$functions->deleteOption( "de_ok_enable" );
			$functions->deleteOption( "de_new_posts_day" );

			$functions->deleteOption( 'DESTAT_Cron_One_Day_Posts' );
			$functions->deleteOption( "DESTAT_Cron_New_Posts" );
			$functions->deleteOption( "DESTAT_Cron_Old_Posts" );
			$functions->deleteOption( "DESTAT_Cron_All_Old_Posts" );

			$functions->deleteOption( "DESTAT_Post_Type" );

			$functions->deleteOption( "DESTAT_New_Posts_Day" );
			$functions->deleteOption( "DESTAT_Old_Posts_Day" );
			$functions->deleteOption( 'activation_email' );
			$functions->deleteOption( 'activation_key' );
			$functions->deleteOption( 'destatistics' );

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS $wpdb->de_statistics" );
		}

		function defaulsOptionsSetWhenActivated() {

			$functions = new DESTAT_Functions();
			// Default set post types

			if ( ! get_option( 'DESTAT_Post_Type' ) ) {
				$functions->updateOption( 'DESTAT_Post_Type', array( 'post' ) );
			}

			if ( ! get_option( "DESTAT_New_Posts_Day" ) ) {
				$functions->updateOption( "DESTAT_New_Posts_Day", 5 );
			}

			if ( ! get_option( "DESTAT_Old_Posts_Day" ) ) {
				$functions->updateOption( "DESTAT_Old_Posts_Day", 30 );
			}
		}

		/*
		 * Copy type posts when activate plugin
		 */
		public function copyTypePostsToStatTable() {

			global $wpdb;


			if ( @is_file( ABSPATH . '/wp-admin/includes/upgrade.php' ) ) {
				include_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			} elseif ( @is_file( ABSPATH . '/wp-admin/upgrade-functions.php' ) ) {
				include_once( ABSPATH . '/wp-admin/upgrade-functions.php' );
			} elseif ( @is_file( ABSPATH . '/wp-admin/includes/upgrade.php' ) ) {
				include_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			} else {
				die( __( 'We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'', DESTAT_TEXTDOMAIN ) );
			}

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


			$blogs[]     = 1;
			$allow_posts = get_option( "DESTAT_Post_Type" );

			if ( count( $allow_posts ) > 0 ) {
				$where_arr = array();
				foreach ( $allow_posts as $post_type ) {
					$where_arr[] = "post_type = '$post_type'";
				}

				// If multisites table the copy posts by blog ID
				if ( is_multisite() ) {
					if ( $other_blogs = $wpdb->get_results( " SELECT * FROM $wpdb->blogs WHERE spam = 0 AND archived = 0 AND public = 1 AND blog_id != 1 " ) ) {
						foreach ( $other_blogs as $blog ) {
							$blogs[] = $blog->blog_id;
						}
					}
				}
				// Copy data
				foreach ( $blogs as $blog_id ) {

					if ( function_exists( 'switch_to_blog' ) ) {
						switch_to_blog( $blog_id );
					}


					global $wpdb;

					if ( $blog_id == 0 ) {
						continue;
					}
					$where = implode( ' OR ', $where_arr );
					//				echo $where;
					$wpdb->query( "INSERT INTO $wpdb->de_statistics ( blog_id, post_id, post_type, created_time ) SELECT '$blog_id', ID, post_type, post_modified FROM $wpdb->posts WHERE ( $where ) AND post_status = 'publish' AND NOT EXISTS (SELECT * FROM $wpdb->de_statistics tmp WHERE tmp.blog_id = '$blog_id' AND {$wpdb->posts}.ID = tmp.post_id  ) " );

					if ( function_exists( 'restore_current_blog' ) ) {
						restore_current_blog();
					}

					// AND tmp.blog_id !=  $blog_id
				}
			} // END IF exists post type list
		}

	}