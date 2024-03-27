<?php

	class DESTAT_Functions {

		public function getPostTypes() {

			$aPostPypes = get_post_types( array( 'public' => true ), 'objects' );

			return $aPostPypes;
		}

		public function getLog() {

			global $oUniLog;

			if ( is_object( $oUniLog ) ) {
				return $oUniLog;
			} else {
				//				require_once DESTAT_PLUGIN_PATH . "/includes/log.class.php";
				//				$oUniLog = KLogger::instance( DESTAT_PLUGIN_PATH . '/log/', KLogger::DEBUG );

				//				return $oUniLog;
			}
		}

		public static function getHost() {

			$host = false;

			if ( array_key_exists( 'HTTP_HOST', $_SERVER ) ) {
				if ( isset( $_SERVER['HTTP_HOST'] ) ) {
					$host = $_SERVER['HTTP_HOST'];
				}
			} else {
				if ( array_key_exists( 'SERVER_NAME', $_SERVER ) ) {
					if ( isset( $_SERVER['SERVER_NAME'] ) ) {
						$host = $_SERVER['SERVER_NAME'];
					}
				}
			}

			return $host;
		}

		public function getBlogs() {

			global $wpdb;

			$blogs[] = 1;
			if ( is_multisite() ) {
				if ( $blogs_arr = $wpdb->get_results( "SELECT * FROM $wpdb->blogs WHERE spam = 0 AND archived = 0 AND public = 1 AND blog_id != 1" ) ) {
					foreach ( $blogs_arr as $blog ) {
						$blogs[] = $blog->blog_id;
					}
				}
			}

			return $blogs;
		}


		// Обновление и создание опции для мультисайта и синг лайта
		// Работае как с включенным мультисайтом так и в single
		public function updateOption( $option, $value ) {

			if ( empty( $option ) ) {
				return false;
			}

			$blogs = $this->getBlogs();
			foreach ( $blogs as $blog_id ) {
				if ( function_exists( 'switch_to_blog' ) ) {
					switch_to_blog( $blog_id );
				}

				update_option( $option, $value );
				if ( function_exists( 'restore_current_blog' ) ) {
					restore_current_blog();
				}
			}


			return true;
		}

		// Удаление опции из с главного сайта и мультисайтов
		// Оаботает как с включенным мультисайтом так и в режиме single
		public function deleteOption( $option ) {

			if ( empty( $option ) ) {
				return false;
			}

			$blogs = $this->getBlogs();
			foreach ( $blogs as $blog_id ) {
				if ( function_exists( 'switch_to_blog' ) ) {
					switch_to_blog( $blog_id );
				}

				delete_option( $option );
				if ( function_exists( 'restore_current_blog' ) ) {
					restore_current_blog();
				}

			}

			return true;
		}

		// Удаление транзишинов с мультисайтов
		// Оаботает как с включенным мультисайтом так и в режиме single
		public function deleteTransient( $option ) {

			if ( empty( $option ) ) {
				return false;
			}

			$blogs = $this->getBlogs();
			foreach ( $blogs as $blog_id ) {
				if ( function_exists( 'switch_to_blog' ) ) {
					switch_to_blog( $blog_id );
				}

				delete_transient( $option );
				if ( function_exists( 'restore_current_blog' ) ) {
					restore_current_blog();
				}

			}

			return true;
		}
		// Сохраняет транзишин на мультисайтах
		// Оаботает как с включенным мультисайтом так и в режиме single
		public function setTransient( $option, $value, $expires ) {

			if ( empty( $option ) ) {
				return false;
			}

			$blogs = $this->getBlogs();
			foreach ( $blogs as $blog_id ) {
				if ( function_exists( 'switch_to_blog' ) ) {
					switch_to_blog( $blog_id );
				}

				set_transient( $option, $value, $expires );

				if ( function_exists( 'restore_current_blog' ) ) {
					restore_current_blog();
				}

			}

			return true;
		}


		public function executeFunctionAllBlogs( $function ) {

			$blogs = $this->getBlogs();
			foreach ( $blogs as $blog_id ) {
				if ( function_exists( 'switch_to_blog' ) ) {
					switch_to_blog( $blog_id );
				}
				call_user_func( $function );
			}
			if ( function_exists( 'restore_current_blog' ) ) {
				restore_current_blog();
			}


			return true;

		}


		public function getRequestData( $post_index ) {

			return isset( $_REQUEST[ $post_index ] ) ? $_REQUEST[ $post_index ] : '';
		}
	}