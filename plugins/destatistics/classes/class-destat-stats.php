<?php

class DESTAT_Stats {

	/*
	 * Обновление постов за текущий день
	 */
	public function oneDayUpdateSocialStatsPosts() {

		$date_from_new_post = date( 'Y-m-d', strtotime( '-1 days' ) );
		$date_to_new_post   = date( 'Y-m-d' );

		$where_date = "created_time >= '$date_from_new_post 23:59:59' AND created_time <= '$date_to_new_post 23:59:59'";
		$this->getPostsForUpdateStats( $where_date, 13, 'DESTAT_One_Day_Update_Posts' );
	}


	/*
	 * Обновление новых постов
	 */

	public function newUpdateSocialStatsPosts() {

		$new_post_day = get_option( 'DESTAT_New_Posts_Day' );

		$new_posts_time = strtotime( '-' . $new_post_day . ' days' );

		$date_from_new_post = date( 'Y-m-d', $new_posts_time );
		$today              = date( 'Y-m-d' );

		$where_date = "created_time >= '$date_from_new_post' ";
		$where_date .= " AND created_time < '$today' ";

		$this->getPostsForUpdateStats( $where_date, 13, 'DESTAT_New_Update_Posts' );

	}

	public function oldUpdateSocialStatsPosts() {

		$new_post_day = get_option( 'DESTAT_New_Posts_Day' );
		$old_post_day = get_option( 'DESTAT_Old_Posts_Day' );

		$old_post_day += $new_post_day;

		$new_posts_time = strtotime( '-' . $new_post_day . ' days' );
		$old_posts_time = strtotime( '-' . $old_post_day . ' days' );

		$new_date_from = date( 'Y-m-d', $new_posts_time );
		$old_date_from = date( 'Y-m-d', $old_posts_time );

		$where_date = "created_time >= '$old_date_from' ";
		$where_date .= " AND created_time < '$new_date_from' ";

		$this->getPostsForUpdateStats( $where_date, 13, 'DESTAT_Old_Update_Posts' );
	}

	public function allOldUpdateSocialStatsPosts() {

		$new_post_day = get_option( 'DESTAT_New_Posts_Day' );
		$old_post_day = get_option( 'DESTAT_Old_Posts_Day' );

		$old_post_day += $new_post_day;


		$all_old_posts_time = strtotime( '-' . $old_post_day . ' days' );
		$all_old_date_from  = date( 'Y-m-d', $all_old_posts_time );

		$where_date = "created_time < '$all_old_date_from' ";

		$this->getPostsForUpdateStats( $where_date, 13, 'DESTAT_All_Old_Update_Posts' );
	}


	private function getPostsForUpdateStats( $where_date, $post_limit_one_cron_start, $cron_job_name = '' ) {

		global $wpdb;

		$blogid = get_current_blog_id();
		if ( empty( $blogid ) ) {
			$blogid = 1;
		}

		$posts = array();
		// Получим записи для обновления статистистики пачками
		$query = "SELECT * FROM $wpdb->de_statistics WHERE $where_date AND blog_id = $blogid AND flag_update = 0 ORDER BY created_time DESC LIMIT $post_limit_one_cron_start";
		if ( ! $posts = $wpdb->get_results( $query ) ) {

			// Обнуляем флаг для нового обновления если все обновилось
			// за указанный период,и начинаем заново обновлять
			$query = "UPDATE $wpdb->de_statistics SET flag_update = 0 WHERE $where_date AND blog_id = $blogid AND flag_update = 1 ";
			$wpdb->query( $query );


			if ( isset( $_GET['destat_debug'] ) ) {
				print_r( $query );
				die();
			}

			// Repeat the process
			$query = "SELECT * FROM $wpdb->de_statistics WHERE $where_date AND blog_id = $blogid AND flag_update = 0 ORDER BY created_time DESC LIMIT $post_limit_one_cron_start";
			$posts = $wpdb->get_results( $query );

		}


		$i    = 0;
		$time = time() + 1;
		foreach ( $posts as $post ) {

			// Делано для серверного крона, не используя псевдокрон WordPress
			if ( isset( $_GET['destatistics_cron_current_day_posts'] ) || isset( $_GET['destatistics_cron_new_posts'] ) || isset( $_GET['destatistics_cron_old_posts'] ) || isset( $_GET['destatistics_cron_all_old_posts'] ) ) {
//echo "$post->post_id, $blog_id - $cron_job_name";
				$this->singleUpdateStatsPost( $post->post_id, $blogid );
//				do_action( $cron_job_name, $post->post_id, $blog_id );
			} else {
				if ( ! wp_next_scheduled( $cron_job_name ) ) {
					if ( function_exists( 'get_permalink' ) ) {
						try {
							$url = get_permalink( $post->post_id );
							if ( ! empty( $url ) ) {
								wp_schedule_single_event( $time, $cron_job_name, array(
									$post->post_id,
									$blogid,
								) );
								$time += 9;
							}
						} catch ( Exception $e ) {
							if ( isset( $_GET['destat_debug'] ) ) {
								echo 'Caught exception: ', $e->getMessage(), "\n";
							}
						}
					}
				}
			}
		}

	}


	public function singleUpdateStatsPost( $post_id, $blogid ) {

		global $wpdb;
//		echo "$post_id, $blog_id";
//		return;
		$lang = '';
		$url  = '';
//		require_once ABSPATH . 'wp-load.php';
		if ( function_exists( 'get_permalink' ) ) {
			try {
				$url = get_permalink( $post_id );
			} catch ( Exception $e ) {
				if ( isset( $_GET['destat_debug'] ) ) {
					echo 'Caught exception: ', $e->getMessage(), "\n";
				}
			}
		}


		// Получаем статистику по посту из соц сетей
		if ( ! empty( $url ) ) {
			if ( isset( $_GET['destat_debug'] ) ) {
				print_r( get_post( $post_id ) );
				echo "$post_id, $blogid <br>";
				echo $url;
			}
			$stats = $this->getStats( $url, $post_id );
			if ( isset( $_GET['destat_debug'] ) ) {
				print_r( $stats );
			}
		}

		// если данные есть, формируем параметры обновления для таблицы

		//исключаем нулевой результат
		if ( count( $stats ) > 0 ) {
			foreach ( $stats as $key => $val ) {
				if ( intval( $val ) ) {
					$sets[] = " $key = $val ";
				}
			}
		}
		// Есть данные для обновления

		/*		// Подмена домена для теста
				if ( DESTAT_DOMAIN_FOR_TEST ) {
					$url = str_replace( DESTAT_DOMAIN_FOR_TEST_REPLACE_FROM, DESTAT_DOMAIN_FOR_TEST_REPLACE_TO, $url );
				}
		*/

		$date_update = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) );

		$query = "UPDATE $wpdb->de_statistics SET flag_update = 1";
		if ( $url ) {
			$query .= ", url = '$url'";
		}

		$query .= ", updated_time = '$date_update'";
		if ( count( $sets ) > 0 ) {
			$query .= ', ' . implode( ',', $sets );
		}
		$query .= " WHERE blog_id = $blogid AND post_id = $post_id; ";

		if ( isset( $_GET['destat_debug'] ) ) {
			echo "\r\n" . $query . "\r\n";
		}

		return array(
			'query_str'    => $query,
			'query_result' => $wpdb->query( $query )
		);

	}

	public function getStats( $url, $post_id = 0 ) {

		global $wpdb;
		/**/

		if ( $old_url = get_post_meta( $post_id, 'old_url', true ) ) {
			$url = $old_url;
		} else {

			if ( DESTAT_DOMAIN_FOR_TEST ) {
				$url = str_replace( DESTAT_DOMAIN_FOR_TEST_REPLACE_FROM, DESTAT_DOMAIN_FOR_TEST_REPLACE_TO, $url );
			}
		}


		/*			$log     = new Functions();
					$oUniLog = $log->getLog();*/

		$fb       = new DESTAT_Facebook();
		$vk       = new DESTAT_Vk();
		$gp       = new DESTAT_Google();
		$pocket   = new DESTAT_Pocket();
		$ok       = new DESTAT_Ok();
		$tw       = new DESTAT_Twitter();
		$ln       = new DESTAT_Linkedin();
		$comments = new DESTAT_Comments();

		$stats['votes_sum'] = 0;

		if ( get_option( 'de_fb_enable' ) ) {
			$stats['fb_counts'] = intval( $fb->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['fb_counts'] );
		}

		if ( get_option( 'de_twi_enable' ) ) {
			$stats['tw_counts'] = intval( $tw->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['tw_counts'] );
		}

		if ( get_option( 'de_vk_enable' ) ) {
			$stats['vk_counts'] = intval( $vk->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['vk_counts'] );
		}

		if ( get_option( 'de_li_enable' ) ) {
			$stats['ln_counts'] = intval( $ln->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['ln_counts'] );
		}

		if ( get_option( 'de_gplus_enable' ) ) {
			$stats['gplus_counts'] = intval( $gp->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['gplus_counts'] );
		}

		if ( get_option( 'de_ok_enable' ) ) {
			$stats['ok_counts'] = intval( $ok->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['ok_counts'] );
		}

		if ( get_option( 'de_pocket_enable' ) ) {
			$stats['pocket_counts'] = intval( $pocket->getSharesCountByUrl( $url ) );
			$stats['votes_sum'] += intval( $stats['pocket_counts'] );
		}

		$stats['comments_counts'] = intval( $comments->getCommentsCountById( $post_id ) );

		$local_page_view_count = absint( get_post_meta( $post_id, "decollete_post_views", true ) );

		$google_analytics_count = $gp->getPageviewsByUrl( $url );

		if ( intval( $google_analytics_count ) && $local_page_view_count > $google_analytics_count ) {

			$stats['views_counts'] = $local_page_view_count;
		} else {

			$stats['views_counts'] = intval( $gp->getPageviewsByUrl( $url ) );
		}


		return $stats;
	}

	public function addPostToStatsOnSave( $post_id ) {


		$post_type = get_post_type( $post_id );

		$allow_posts = get_option( "DESTAT_Post_Type" );

		if ( is_array( $allow_posts ) && in_array( $post_type, $allow_posts ) ) {

			global $wpdb;

			$blog_id      = get_current_blog_id();
			$url          = get_permalink( $post_id );
			$created_time = get_the_time( 'Y-m-d H:i:s', $post_id );
			$lang         = '';

			if ( $wpdb->get_row( "SELECT * FROM $wpdb->de_statistics WHERE blog_id = $blog_id AND post_id = $post_id " ) ) {

				$query = "UPDATE $wpdb->de_statistics SET url = '$url', created_time = $created_time WHERE blog_id = $blog_id AND post_id = $post_id ";

				$wpdb->query( $query );

				/*wp_schedule_single_event( time() + 1, 'destatisticsSingleUpdatePost', array(
					$post_id,
					$blog_id,
					$url
				) );*/
				$this->singleUpdateStatsPost( $post_id, $blog_id );

			} else {
				$query = "INSERT INTO $wpdb->de_statistics (post_id, blog_id, url, lang, created_time) ";
				$query .= "VALUES ($post_id, $blog_id, '$url','', '$created_time')";
				$wpdb->query( $query );


				$this->singleUpdateStatsPost( $post_id, $blog_id );

			}


		}
		$DESTAT_Settings = new DESTAT_Settings();
		$DESTAT_Settings->copyTypePostsToStatTable();

	}

}