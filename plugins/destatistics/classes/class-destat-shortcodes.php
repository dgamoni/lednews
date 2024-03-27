<?php

	class DESTAT_Shortcodes {

		public function __construct() {

			//			add_shortcode( 'depr-pageviews', 'DePrGetPageviewsShortcode' );
			add_shortcode( 'destat-pageviews', array( $this, 'getPageViews' ) );


			//			add_shortcode( 'depr-socialactions', 'DePrGetSocialActionsShortcode' );
			add_shortcode( 'destat-socials', array( $this, 'getSocialCounts' ) );

			//			add_shortcode( 'depr-total', 'DePrGetTotalShortcode' );
			add_shortcode( 'destat-total', 'getTotalCounts' );


		}

		function getPageViews( $atts ) {

			global $post;
			$post_id = $post->ID;
			$res     = GetStatsByPostId( $post_id );

			return $res->views;
		}

		function getSocialCounts( $atts ) {

			global $post;
			$iPostId = $post->ID;
			$aData   = GetStatsByPostId( $iPostId );

			return $aData['SocialActions'];
		}

		function getTotalCounts( $atts ) {

			global $post, $wpdb;

			$blog_id = DESTAT_CURRENT_BLOG_ID;

			$totals = $wpdb->get_results( "SELECT $wpdb->de_statistics WHERE post_id = $post->ID AND blog_id = $blog_id" );

			return $totals->votes_sum;
		}


	}