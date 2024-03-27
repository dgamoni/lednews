<?php

	class DESTAT_Comments {

		public function getCommentsCountById( $post_id = 0, $url = '' ) {

			if ( ! $post_id ) {
				return 0;
			}

			global $wpdb;

			if ( $comments = $wpdb->get_row( "SELECT COUNT(*) as comment_counts FROM $wpdb->comments WHERE comment_post_ID = $post_id AND comment_approved = 1" ) ) {
				$comment_counts = $comments->comment_counts;


				if ( $disqus = $this->getDisqusCommentsCountByUrl( $url ) && $disqus > $comment_counts ) {
					$comment_counts = $disqus;
				}

				return $comment_counts;

			}

			return 0;
		}

		public function getDisqusCommentsCountByUrl( $url ) {

			if ( get_option( "DESTAT_disqus_enable" ) ) {

				$url     = 'http://disqus.com/embed/comments/?base=default&disqus_version=c40f9aae&f=' . get_option( "DESTAT_disqus_site_id" ) . '&t_u=' . urlencode( $url );
				$content = wp_remote_get( $url, array( 'sslverify' => false ) );
				//				print_r($content);
				//				$content = file_get_contents( $url );

				if ( preg_match( '/<span class="comment-count">(.*)<\/span>/isU', $content, $match ) ) {
					return intval( $match[2] );
				}
			}

			return 0;
		}

	}
