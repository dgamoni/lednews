<?php

class DESTAT_View_Page {
	private $max_time = 600;
	private $blogid = 0;
	private $post_id = 0;
	private $session_id = 0;

	function deco_post_views() {

		global $post;

		if ( $_GET['ses_view_page_destroy'] ) {
			session_destroy();
		}

		if ( empty( $post->ID ) ) {
			return;
		}

		$this->post_id = $post->ID;

		$this->blogid = get_current_blog_id();


		$this->session_id = session_id() . '_' . $this->blogid . '_' . $this->post_id . '_time';


		$bots      = array
		(
			'Google Bot'    => 'googlebot'
		,
			'Google Bot'    => 'google'
		,
			'MSN'           => 'msnbot'
		,
			'Alex'          => 'ia_archiver'
		,
			'Lycos'         => 'lycos'
		,
			'Ask Jeeves'    => 'jeeves'
		,
			'Altavista'     => 'scooter'
		,
			'AllTheWeb'     => 'fast-webcrawler'
		,
			'Inktomi'       => 'slurp@inktomi'
		,
			'Turnitin.com'  => 'turnitinbot'
		,
			'Technorati'    => 'technorati'
		,
			'Yahoo'         => 'yahoo'
		,
			'Findexa'       => 'findexa'
		,
			'NextLinks'     => 'findlinks'
		,
			'Gais'          => 'gaisbo'
		,
			'WiseNut'       => 'zyborg'
		,
			'WhoisSource'   => 'surveybot'
		,
			'Bloglines'     => 'bloglines'
		,
			'BlogSearch'    => 'blogsearch'
		,
			'PubSub'        => 'pubsub'
		,
			'Syndic8'       => 'syndic8'
		,
			'RadioUserland' => 'userland'
		,
			'Gigabot'       => 'gigabot'
		,
			'Become.com'    => 'become.com'
		,
			'Baidu'         => 'baiduspider'
		,
			'so.com'        => '360spider'
		,
			'Sogou'         => 'spider'
		,
			'soso.com'      => 'sosospider'
		,
			'Yandex'        => 'yandex'
		);
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		foreach ( $bots as $name => $lookfor ) {
			if ( stristr( $useragent, $lookfor ) !== false ) {
				return false;
			}
		}

		if ( $this->isExperienceTime() ) {
			$this->setViewPageData();
		}
	}

	public function sessionStart() {
		if ( ! session_id() ) {
			session_start();
		}

	} // end sessionStart


	public function setViewPageData() {
		global $wpdb;
		$meta_count = absint( get_post_meta( $this->post_id, "decollete_post_views", true ) );
		$meta_count ++;
		update_post_meta( $this->post_id, "decollete_post_views", $meta_count );
		$post = $wpdb->get_row( "SELECT * FROM $wpdb->de_statistics WHERE blog_id = {$this->blogid} AND post_id = {$this->post_id} " );

		if ( $post->views_counts < $meta_count ) {
			$query = "UPDATE $wpdb->de_statistics SET views_counts = $meta_count WHERE blog_id = {$this->blogid} AND post_id = {$this->post_id}";
			$wpdb->query( $query );
		}
//		echo '<!-- UPDATE COUNT:  ' . $meta_count . ' -->';
		$_SESSION[ $this->session_id ] = time();
	}

	public function isExperienceTime() {
		$to_time        = time();
		$from_time      = $_SESSION[ $this->session_id ];
		$minutes_period = $to_time - $from_time;
		if ( intval( $minutes_period ) >= $this->max_time ) {
			return true;
		}

		return false;
	} // end isExperienceTime
}